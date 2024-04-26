<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use Cacheable;
    
    /**
     * Partners can have a parent partner
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'parent_id');
    }

    /**
     * Partners can have many child partners
     */
    public function children(): HasMany
    {
        return $this->hasMany(Partner::class, 'parent_id');
    }

    public function rebates(): HasMany
    {
        return $this->parent
            ? $this->parent->rebates()
            : $this->hasMany(Rebate::class)
        ;
    }

    /**
     * Returns any partners that have a rebate for the
     * current fiscal year (or provided fiscal year).
     * The rebate does NOT have to have any remaining.
     *
     * If the partner has a parent, we query for the parent rebates as well.
     */
    public function scopeHasActiveRebate($query, $fiscalYear = null)
    {
        $fiscalYear = $fiscalYear ?: fiscal_year();

        return $query
            ->select('partners.*')
            ->distinct()
            ->whereExists(function($query) use ($fiscalYear) {
                $query
                    ->select('*')
                    ->from('rebates')
                    ->where(function($query) {
                        $query
                            ->whereRaw('partners.id = rebates.partner_id')
                            ->orWhereRaw('partners.parent_id = rebates.partner_id')
                        ;
                    })
                    ->where('rebates.fy_year', $fiscalYear)
                ;
            })
        ;
    }

    /**
     * Every partner should only have one active rebate
     * per fiscal year but this IS NOT enforced.
     */
    public function activeRebate(): Rebate
    {
        $rebate = $this->rebates()->where('fy_year', fiscal_year())
            ->where('remaining', '>', 0)
            ->first()
        ;

        if ($rebate) {
            return $rebate;
        }

        $active = $this->rebates()->where('fy_year', fiscal_year())
            ->first()
        ;

        if (empty($active)) {
            throw new \RuntimeException("Sorry, there are currently no rebates available for {$this->name}", 422);
        }

        return $active;
    }
}

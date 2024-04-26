<?php

namespace App;

use App\Cacheable;
use App\WaitListedApplication;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Nova\Actions\Actionable;

class Rebate extends Model
{

    use Actionable;
    use Cacheable;

    public function rebateType(): BelongsTo
    {
        return $this->belongsTo(RebateType::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function relevantApplications(): HasMany
    {
        return $this->hasMany(Application::class)->hasClaimedRebates();
    }

    public function waitingApplications(): HasMany
    {
        return $this->hasMany(WaitListedApplication::class);
    }

    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(Claim::class, Application::class);
    }

    public function isAvailable(): bool
    {
        return $this->remaining > 0;
    }

    public function canSatisfyAmount(int $num): bool
    {
        return $this->remaining >= $num;
    }

    public function canSatisfyApplication(Application $application): bool
    {
        return $this->remaining >= $application->rebate_count;
    }

    public function partnerYear(): string
    {
        return $this->partner->account_key.' - '.$this->fy_year;
    }

    /**
     * Balance the rebates lists
     */
    
    public function getUsedAttribute()
    {
        return $this->inventory - $this->remaining;
    }

    /**
     * Total number of rebates/toilets that are currently held. 
     * This SHOULD match the number "used"
     * 
     * @return [type] [description]
     */
    public function totalToiletsInApplications()
    {
        return $this->relevantApplications()->sum('rebate_count');
    }

    /**
     * Total number of rebates/toilets that are fullfilled (or pending fullfillment)
     * 
     * @return [type] [description]
     */
    public function totalToiletsFulfilled()
    {
        return $this
            ->relevantApplications()
            ->hasApprovedClaim()
            ->sum('rebate_count');
    }

    /**
     * Total number of rebates/toilets that are held but not yet fullfilled (or pending fullfillment)
     * 
     * @return [type] [description]
     */
    public function totalToiletsPending()
    {
        return $this
            ->relevantApplications()
            ->hasPendingClaim()
            ->sum('rebate_count');
    }

    /**
     * Total number of rebates/toilets on the wait list for this rebate
     * 
     * @return [type] [description]
     */
    public function totalToiletsOnWaitlist()
    {
        return $this->waitingApplications()->sum('rebate_count');
    }

}

<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Every Application has a property
 *
 * Properties have owners if the Applicant is not the owner.
 *
 * The property contact is the person who owns the property so
 * it is either the applicant or an Owner model.
 */
class Property extends Model
{
    use Addressable;
    use Cacheable;

    const PROPERTY_TYPES = [
        'Residential',
        'Commercial/Business',
        'Institutional',
        'Non-Profit',
    ];

    const BUILDING_TYPES = [
        'Single Family',
        'Townhouse',
        'Condo',
        'Duplex/Quad',
        'CoOp',
        'Multi-family',
        'Mobile Home',
    ];

    const COMMERCIAL = 'commercial';

    const RESIDENTIAL = 'residential';

    protected static function boot(): void{
        parent::boot();

        static::deleting(function ($model) {

            optional($model->owner)->delete();

            optional($model->utilityAccount)->delete();

        });
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function getBathroomsAttribute($number) 
    {
        if ( $number ) {
            return $number;
        } else {
            return $this->full_bathrooms + $this->half_bathrooms;
        }
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class);
    }

    public function utilityAccount(): HasOne
    {
        return $this->hasOne(UtilityAccount::class);
    }

    public function ownedByApplicant(): bool
    {
        return empty($this->owner);
    }

    /**
     * If there is no Owner, Applicant is the property owner
     */
    public function contact(): Contactable
    {
        return $this->ownedByApplicant()
            ? $this->applicant
            : $this->owner
        ;
    }
}

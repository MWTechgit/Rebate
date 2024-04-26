<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Owner model exists if an application is submitted by
 * a person who does not own the property the application
 * is being submitted for.
 *
 * The property owner must be contacted for approval
 * of the rebate application in the case that the applicant
 * is not the property owner.
 */
class Owner extends Model
{
    use Addressable;
    use Cacheable;

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function application(): Application
    {
        return $this->property->application;
    }

    public function getApplication(): Application
    {
        return $this->property->application;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}

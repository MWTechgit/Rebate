<?php

namespace App;

use App\Cacheable;

class UtilityAccount extends Model
{
    use Addressable;
    use Cacheable;

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getApplication(): Application
    {
        return $this->property->application;
    }
}

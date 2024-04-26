<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Builder;

class WaitListedApplication extends Application
{
    use Cacheable;

    protected $table = 'applications';

    protected static function addScopes()
    {
        static::addGlobalScope('wait_listed', function(Builder $builder) {
            $builder->where('wait_listed', true);
        });
    }
}

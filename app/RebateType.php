<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * The type of appliance of the rebate is for.
 *
 * The primary rebate type is "toilet".
 * The live application (at the time of this writing)
 * also has a "faucet" type.
 */
class RebateType extends Model
{
    use SoftDeletes;
    use Cacheable;

    protected $dates = ['deleted_at'];

    public function rebates(): HasMany
    {
        return $this->hasMany(Rebate::class);
    }
}

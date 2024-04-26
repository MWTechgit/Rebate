<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenceType extends Model
{
    use SoftDeletes;
    use Cacheable;

    protected $dates = ['deleted_at'];

    public function references(): HasMany
    {
        return $this->hasMany(Reference::class);
    }

    public function requiresMoreInfo()
    {
        return false === empty($this->info_text);
    }
}

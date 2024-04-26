<?php

namespace App;

use App\Cacheable;
use App\Claim;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExportBatch extends Model
{
    use Cacheable;
    
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function claims(): BelongsToMany
    {
        return $this->belongsToMany(Claim::class, 'export_batches_claims');
    }

    public function getClaimsCountAttribute($value) {
        return $value ?? $this->claims()->count();
    }
}

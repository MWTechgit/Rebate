<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An application transaction represents the approval
 * or denial of an application by an Admin.
 */
class ApplicationTransaction extends Transaction
{
    use Cacheable;

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}

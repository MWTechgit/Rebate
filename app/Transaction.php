<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Transaction extends Model
{
    const ST_APPROVED = 'approved';
    const ST_DENIED = 'denied';

    /**
     * Transactions always belong to an admin.
     *
     * This will never be null because admins are soft deleted.
     * Non deletion of the admin is enforced on the database level
     * with a FK constraint using the default "restrict" behavior.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function isApproved(): bool
    {
        return static::ST_APPROVED == $this->type;
    }

    public function isDenied(): bool
    {
        return static::ST_DENIED == $this->type;
    }

    public function reason(): string
    {
        return $this->description;
    }
}

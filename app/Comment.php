<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface Comment
{
    public function admin(): BelongsTo;
}
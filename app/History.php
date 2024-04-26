<?php

namespace App;

use App\Address;
use App\Application;
use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    use Cacheable;

    protected $table = 'history';

    protected $dates = ['submitted_at'];

    public static function boot()
    {
        parent::boot();

        self::saving(function($model) {
            if (!$model->isDirty('address_index')) {
                $model->address_index = Address::buildIndex($model);
            }
        });
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function __toString()
    {
        return $this->line_one.' '.$this->line_two.' '.$this->city.', '.$this->state.' '.$this->postcode;
    }
}

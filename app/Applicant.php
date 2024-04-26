<?php

namespace App;

use App\Cacheable;
use App\WaterSense;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Applicants represent a rebate request by a person.
 * Every time a rebate is requested, a new Applicant is created.
 * Users can't have multiple applications, that's why there are no users.
 *
 * Basically, people make a new account every time they request a rebate.
 */
class Applicant extends Authenticatable
{
    use Notifiable;
    use Cacheable;

    protected $guarded = [];

    protected static function boot(): void{
        parent::boot();

        static::deleting(function ($model) {

            optional($model->reference)->delete();

        });
    }

    public function application(): HasOne
    {
        return $this->hasOne(Application::class);
    }

    public function claim(): HasOne
    {
        return $this->hasOne(Claim::class);
    }

    public function watersense(): HasOne
    {
        return $this->hasOne(WaterSense::class);
    }

    /**
     * How did the applicant hear about the program?
     */
    public function reference(): HasOne
    {
        return $this->hasOne('App\Reference');
    }

    public function getWaterSenseReasonAttribute()
    {
        return optional($this->watersense)->reason;
    }
}

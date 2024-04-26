<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DocumentSet extends Model
{
    use Cacheable;
    
    protected $dates = ['purchased_at'];

    const FILES = ['receipt', 'installation_photo', 'upc'];

    protected static function boot(): void{
        parent::boot();

        static::deleting(function ($model) {

            return collect(static::FILES)->each(function($prop) use ($model) {

                Storage::disk('public')->delete("claims/{$model->$prop}");

            });
            
        });
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function numFiles(): int
    {
        return collect(static::FILES)->filter(function($prop) {
            return false === empty($this->$prop);
        })->count();
    }

    public function hasAllFiles(): bool
    {
        return collect(static::FILES)->every(function($prop) {
            return false === empty($this->$prop);
        });
    }

    public function getApplicationAttribute()
    {
        return optional($this->claim)->application;
    }

    public function getApplicantAttribute()
    {
        return optional($this->claim)->applicant;
    }
}

<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use Cacheable;

    protected $appends = ['full'];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFullAttribute()
    {
        return (string) $this;
    }

    public function __toString()
    {
        return $this->line_one.' '.$this->line_two.' '.$this->city.', '.$this->state.' '.$this->postcode;
    }

    public static function buildIndex($address)
    {
        $line_one = static::stripAddress($address->line_one);
        $line_two = isset($address->line_two) ? static::stripAddress($address->line_two) : '';
        return $line_one.' '.$line_two.' '.$address->city.' '.static::normalizeState($address->state).' '.$address->postcode;
    }

    public static function stripAddress($string)
    {
        $l1 = strtolower($string);

        $words = [
            'drive',
            'avenue',
            'street',
            'road',
            'appartment',
            'court',
            'place',
            'boulevard',
            'expressway',
            'highway',
            'junction',
            'lane',
            'parkway',
            'square',
            'terrace',
            'valley',
            'room',
            'suite'
        ];

        foreach($words as $word) {
            $l1 = strtr($l1, [$word => '']);
        }

        $words = [
            'dr',
            'ave',
            'st',
            'rd',
            'apt',
            'ct',
            'pl',
            'blvd',
            'expy',
            'hwy',
            'jct',
            'ln',
            'pkwy',
            'sq',
            'ter',
            'vly',
            'rm',
            'ste',
        ];

        $punc = [
            '#',
            '.'
        ];

        foreach($words as $word) {
            $l1 = strtr($l1, [$word => '']);
            $l1 = strtr($l1, [$word.'.' => '']);
        }

        foreach($punc as $word) {
            $l1 = str_replace($word, '', $l1);
        }

        $l1 = preg_replace('/\\b(\d+)(?:st|nd|rd|th)\\b/', '$1', $l1);

        return $l1;
    }

    public static function normalizeState($state)
    {
        if ( strtolower($state) === 'fl' || strtolower($state) === 'florida') {
            return 'FL';
        }
        return $state;
    }
}

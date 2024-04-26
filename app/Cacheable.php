<?php

namespace App;

use App\Address;
use App\Applicant;
use App\Application;
use App\Claim;
use App\Partner;
use App\Rebate;
use App\RebateType;
use App\Reference;
use App\ReferenceType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

trait Cacheable {

    protected static function bootCacheable(): void {

        static::deleting(function ($model) {
            Cache::forget(static::getCacheableKey($model->id));
        });

        static::saved(function ($model) {
        	Cache::forget(static::getCacheableKey($model->id));
        });
    }

    private static function getCacheableSeconds()
    {
    	switch(get_called_class()) {
    		case Rebate::class:
    		case Partner::class:
    		case RebateType::class:
    		case Reference::class:
    		case ReferenceType::class:
    		case ExportBatch::class:
    			return 60 * 60; // 1 hour
    		default:
    			return 60 * 5;// 5 minutes
    	
    	}
    }

    private static function getCacheableKey($id)
    {
    	return (new self())->getTable().'_'.$id;
    }

	public function __call($method, $parameters)
    {

    	// Cache the 'find' method
        if ($method == 'find') {

			$id      = Arr::get($parameters,0);
			$columns = Arr::get($parameters, 1, null);

			if ($id && !$columns) {
				return Cache::remember(static::getCacheableKey($id), static::getCacheableSeconds(), function () use ($method, $parameters) {
					return parent::__call($method, $parameters);
				});
			}			
		}
		
        return parent::__call($method, $parameters);
    }

}

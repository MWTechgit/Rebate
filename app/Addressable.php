<?php

namespace App;

use App\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Addressable {
	public function address(): MorphOne {
		return $this->morphOne(Address::class, 'addressable');
	}

	/**
	 * Cascade delete polymorhpic.
	 *
	 * We'd prefer DB level cascades but that isn't
	 * possible in polymorphic tables.
	 *
	 * Laravel magic recognizes any boot function
	 * in a model trait and calls it.
	 *
	 * https://laravel.com/docs/5.0/eloquent#global-scopes
	 */
	public static function bootAddressableTrait(): void {
		static::deleting(function ($model) {
			$model->address()->delete();
		});
	}
}
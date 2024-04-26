<?php

namespace App\Nova;

use Bwp\Image\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image as NovaImage;
use Laravel\Nova\Fields\Text;

class DocumentSet extends Resource {

	const THUMBNAIL_DEFAULT_SRC = '/svg/file.svg';

	public static $model = 'App\DocumentSet';

	public static $title = 'id';

	# Need this else "Other" shows up in navigation
	public static $group = 'Claims';

	public static $with = ['claim', 'claim.application', 'claim.applicant'];

	public static $search = [];

	public static $globallySearchable = false;

	public static $displayInNavigation = false;

	public static function label() {
		return 'Document Sets';
	}

	public static function singularLabel() {
		return 'Document Set';
	}

	public function fields(Request $request) {
		return [
			ID::make()->sortable(),

			// BelongsTo::make('Claim')
			//     ->onlyOnDetail(),

			Text::make('Claim', function () {
				return $this->application && $this->applicant ?
				'<a class="no-underline dim text-primary font-bold" href="/admin/resources/claims/' . $this->claim->id . '">' . $this->application->rebate_code . '</a>, ' .
				htmlspecialchars($this->applicant->full_name) : '';
			})->asHtml()
				->onlyOnDetail(),

			# You can't change the claim that the document set belongs to
			Text::make('Claim', 'claim.id')
				->onlyWhenUpdating()
				->metaReadOnly(),

			Image::make('Receipt')
				->exceptOnForms(),

			Image::make('Product UPC', 'upc')
				->exceptOnForms(),

			Image::make('Installation Photo')
				->exceptOnForms(),

			NovaImage::make('Receipt')
				->onlyOnForms(),

			NovaImage::make('Product UPC', 'upc')
				->onlyOnForms(),

			NovaImage::make('Installation Photo')
				->onlyOnForms(),

			Date::make('Purchased On', 'purchased_at'),
		];
	}

	public function cards(Request $request) {
		return [];
	}

	public function filters(Request $request) {
		return [];
	}

	public function lenses(Request $request) {
		return [];
	}

	public function actions(Request $request) {
		return [];
	}

	/**
	 * Get the image src if the file is an image. Otherwise return a file icon
	 * @param  string  $value File location in storage
	 * @param  string  $disk  Name of storage disk
	 * @return string
	 */
	private function getThumbnail($value, $disk) {
		if (!$value) {
			return null;
		}

		if (Str::startsWith($value, 'codeigniter_uploads')) {
			return static::THUMBNAIL_DEFAULT_SRC;
		}

		if (!Storage::disk($disk)->exists($value)) {
			return null;
		}

		return $this->isImage($value, $disk) ?
		Storage::disk($disk)->url($value) :
		static::THUMBNAIL_DEFAULT_SRC;
	}

	/**
	 * Determines whether the given file is an image
	 * @param  string  $value File location in storage
	 * @param  string  $disk  Name of storage disk
	 * @return boolean
	 */
	private function isImage($value, $disk) {
		$mime = Storage::disk($disk)->mimeType($value);
		return Str::startsWith($mime, 'image/');
	}
}

<?php

namespace Bwp\Image;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Image as Base;

class Image extends Base
{

	const THUMBNAIL_DEFAULT_SRC = '/svg/file.svg';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'image';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  string|null  $disk
     * @param  callable|null  $storageCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {
    	parent::__construct($name,$attribute,$disk,$storageCallback);

    	$this->thumbnail(function($value,$disk) { return $this->getThumbnail($value,$disk); });
    }

	/**
	 * Get the image src if the file is an image. Otherwise return a file icon
	 * @param  string  $value File location in storage
	 * @param  string  $disk  Name of storage disk
	 * @return string
	 */
	protected function getThumbnail($value, $disk)
	{
		if (!$value) {
			return null;
		}

		// if (Str::startsWith($value, 'codeigniter_uploads')) {
		// 	return static::THUMBNAIL_DEFAULT_SRC;
		// }

		if (!Storage::disk($this->getStorageDisk())->exists($value)) {
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
	protected function isImage($value, $disk) {
		$mime = Storage::disk($disk)->mimeType($value);
		return Str::startsWith($mime, 'image/') || in_array(strtolower(File::extension($value)), ['png','jpg','svg','gif','jpeg']);
	}


    /**
     * Get the disk that the field is stored on.
     *
     * @return string|null
     */
    public function getStorageDisk()
    {
		// if (Str::startsWith($this->value, 'codeigniter_uploads')) {
		// 	return 'codeigniter';
		// }

        return $this->disk;
    }
}

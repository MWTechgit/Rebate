<?php

namespace App\Nova;

use Illuminate\Http\Resources\MergeValue;
use Illuminate\Support\Arr;

trait GroupDisplayMethods
{
    public function onlyOnDetail(array $fields)
    {
        return $this->mapCalledMethod($fields, __METHOD__);
    }

    public function onlyOnIndex(array $fields)
    {
        return $this->mapCalledMethod($fields, __METHOD__);
    }

    /**
     * This is a macro defined in NovaServiceProvider
     */
    public function onlyWhenUpdating(array $fields)
    {
        return $this->mapCalledMethod($fields, __METHOD__);
    }

    /**
     * This is a macro defined in NovaServiceProvider
     */
    public function onlyWhenCreating(array $fields)
    {
        return $this->mapCalledMethod($fields, __METHOD__);
    }

    /**
     * Map over fields and call __METHOD__
     *
     * Allows you to call onlyOnDetail, onlyOnIndex etc
     * to a group of fields with only one call.
     */
    protected function mapCalledMethod(array $fields, string $method)
    {
        $method = Arr::last(explode('::', $method));
        return collect($fields)->map(function($item) use ($method) {
            if ($item instanceof MergeValue) {
                collect($item->data)->each(function($field) use ($method) {
                    $field->$method();
                });
            } else {
                $item->$method();
            }

            return $item;
        })->toArray();
    }
}
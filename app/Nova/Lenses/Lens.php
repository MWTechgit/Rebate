<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens as BaseLens;
use App\Jobs\TracksNextFromLens;

abstract class Lens extends BaseLens
{
    /**
     * Allows setting default order by
     * when no ordering is present in the query string
     */
    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters($query));

        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            static::defaultOrderBy($request, $query);
        }

        static::applyFilters($request, $query);

        rescue( function () use ($query) {
            TracksNextFromLens::saveLensData($query, get_called_class() );
        });

        return $query;
    }

    public static function applyFilters(LensRequest $request, $query): void
    {
    }

    public static function defaultOrderBy(LensRequest $request, $query): void
    {
    }
}
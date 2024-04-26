<?php

namespace Bwp\FullpageSearch\Http\Controllers;

use Bwp\FullpageSearch\GlobalSearch;
// use Laravel\Nova\GlobalSearch;
use Laravel\Nova\Http\Controllers\SearchController as Base;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class Controller extends Base
{
    /*
     * Get the global search results for the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
    */ 
    public function index(NovaRequest $request)
    {
        return (new GlobalSearch(
            $request, Nova::globallySearchableResources($request)
        ))->get();
    }
}
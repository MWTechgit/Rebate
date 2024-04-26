<?php

namespace Bwp\FullpageSearch;

use Laravel\Nova\GlobalSearch as Base;
use Laravel\Nova\Nova;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;

class GlobalSearch extends Base
{

    protected $limit = 500;

    /**
     * Get the search results for the resources.
     *
     * @return array
     */
    protected function getSearchResults()
    {
        $results = [];

        foreach ($this->resources as $resource) {
            $query = $resource::buildIndexQuery(
                $this->request, $resource::newModel()->newQuery(),
                $this->request->search
            );

            if (count($models = $query->limit($this->limit)->get()) > 0) {
                $results[$resource] = $models;
            }
        }

        return collect($results)->sortKeys()->all();
    }

    /**
     * Get the matching resources.
     *
     * @return array
     */
    public function get()
    {
        $formatted = [];

        foreach ($this->getSearchResults() as $resource => $models) {
            foreach ($models as $model) {
                $instance = new $resource($model);

                $formatted[] = [
                    'resourceName' => $resource::uriKey(),
                    'resourceTitle' => $resource::label(),
                    'title' => $instance->fullPageSearchTitle(),
                    'subTitle' => $instance->fullPageSearchSubtitle(),
                    'resourceId' => $model->getKey(),
                    'status' => rescue(function()use($instance) { return $instance->status; }),
                    'url' => url(Nova::path().'/resources/'.$resource::uriKey().'/'.$model->getKey()),
                    'avatar' => $instance->resolveAvatarUrl($this->request),
                ];
            }
        }

        return $formatted;
    }

}

<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;
use Bwp\Applications\Applications;
use Laravel\Nova\Fields\Field;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Field::macro('showOnIndex', function() {
            $this->showOnIndex = true;

            return $this;
        });

        Field::macro('onlyWhenUpdating', function() {
            return $this->hideWhenCreating()
                ->hideFromDetail()
                ->hideFromIndex()
            ;
        });

        Field::macro('onlyWhenCreating', function() {
            return $this
                ->hideWhenUpdating()
                ->hideFromDetail()
                ->hideFromIndex()
            ;
        });

        Field::macro('metaReadOnly', function() {
            return $this->withMeta(['extraAttributes' => ['readonly' => true]]);
        });

        Nova::serving(function () {
            // observers specific to nova can go here
        });

        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user instanceof \App\Admin;
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new \App\Nova\Metrics\NewApplications,
            new \App\Nova\Metrics\ApplicationsPerDay,
            new \App\Nova\Metrics\ApplicationsByStatus,
            new \App\Nova\Metrics\ClaimsByStatus,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
             new \Bwp\CreateApplication\CreateApplication,
             new \Bwp\FullpageSearch\FullpageSearch,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

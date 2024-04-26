<?php

namespace Bwp\ReviewApplication;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            // Provide the gmap api key to JS so we can embed the map
            Nova::provideToScript([
                // js => Nova.config.gmap_api_key, Nova.config.gmap_kml_url
                'gmap_api_key'      => config('broward.gmap_api_key'),
                'gmap_kml_file_url' => config('broward.gmap_kml_url'),
            ]);
            Nova::script('review-application', __DIR__.'/../dist/js/tool.js');
            Nova::style('review-application', __DIR__.'/../dist/css/tool.css');
            Nova::remoteScript('https://maps.googleapis.com/maps/api/js?key='.config('broward.gmap_api_key'));
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/review-application')
                ->group(__DIR__.'/../routes/api.php');
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

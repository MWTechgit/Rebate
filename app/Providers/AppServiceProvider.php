<?php

namespace App\Providers;

use App\Observers\AdminObserver;
use App\Observers\ApplicantObserver;
use App\Observers\ClaimObserver;
use App\Observers\DocumentSetObserver;
use App\Observers\RebateObserver;
use App\Observers\SetCommentAuthorObserver;
use App\Providers\TelescopeServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Twig;
use Twig_SimpleFilter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        \App\Admin::observe(AdminObserver::class);
        \App\Applicant::observe(ApplicantObserver::class);
        \App\Claim::observe(ClaimObserver::class);
        \App\ClaimComment::observe(SetCommentAuthorObserver::class);
        \App\ApplicationComment::observe(SetCommentAuthorObserver::class);
        \App\Rebate::observe(RebateObserver::class);
        \App\DocumentSet::observe(DocumentSetObserver::class);

        # TWIG PHONE FILTER
        Twig::addFilter(new Twig_SimpleFilter('phone', function ($num) {
            return ($num)?'('.substr($num,0,3).') '.substr($num,3,3).'-'.substr($num,6,4):'';
        }));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (app()->environment('local')) {
            app()->register(TelescopeServiceProvider::class);
        }
    }
}

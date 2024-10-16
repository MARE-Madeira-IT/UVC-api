<?php

namespace App\Providers;

use App\Models\Benthic;
use App\Models\Locality;
use App\Models\Motile;
use App\Models\Report;
use App\Models\Site;
use App\Models\Taxa;
use App\Observers\BenthicObserver;
use App\Observers\LocalityObserver;
use App\Observers\MotileObserver;
use App\Observers\ReportObserver;
use App\Observers\SiteObserver;
use App\Observers\TaxaObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Report::observe(ReportObserver::class);
        Taxa::observe(TaxaObserver::class);
        Benthic::observe(BenthicObserver::class);
        Motile::observe(MotileObserver::class);
        Locality::observe(LocalityObserver::class);
        Site::observe(SiteObserver::class);
    }
}

<?php

namespace App\Providers;

use App\Models\Benthic;
use App\Models\Depth;
use App\Models\Indicator;
use App\Models\Locality;
use App\Models\Motile;
use App\Models\Report;
use App\Models\Site;
use App\Models\SizeCategory;
use App\Models\Substrate;
use App\Models\Taxa;
use App\Models\TaxaCategory;
use App\Observers\BenthicObserver;
use App\Observers\DepthObserver;
use App\Observers\IndicatorObserver;
use App\Observers\LocalityObserver;
use App\Observers\MotileObserver;
use App\Observers\ReportObserver;
use App\Observers\SiteObserver;
use App\Observers\SizeCategoryObserver;
use App\Observers\SubstrateObserver;
use App\Observers\TaxaCategoryObserver;
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

        Depth::observe(DepthObserver::class);
        Indicator::observe(IndicatorObserver::class);
        SizeCategory::observe(SizeCategoryObserver::class);
        Substrate::observe(SubstrateObserver::class);
        TaxaCategory::observe(TaxaCategoryObserver::class);
    }
}

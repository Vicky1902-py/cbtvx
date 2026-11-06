<?php

namespace App\Providers;

use App\Models\MasterSekolah;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        Paginator::defaultView('partials.pagination');
        Paginator::defaultSimpleView('partials.pagination');

        // Share master sekolah & kop data automatically to all admin, layout, and document views
        View::composer(['admin.*', 'layouts.*', 'auth.*', 'peserta.*'], function ($view) {
            if (Schema::hasTable('master_sekolah')) {
                $view->with('sekolah', MasterSekolah::first() ?? new MasterSekolah());
            } else {
                $view->with('sekolah', new MasterSekolah());
            }
        });
    }
}

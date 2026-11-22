<?php

namespace App\Providers;

use App\Models\KopLaporan;
use Illuminate\Support\Facades\URL;
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
        // Paksa HTTPS di production (Vercel berada di belakang proxy)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view): void {
            static $activeKop = null;
            static $checked = false;

            if (! $checked) {
                try {
                    $activeKop = KopLaporan::getActive();
                } catch (\Throwable) {
                    $activeKop = null;
                }
                $checked = true;
            }

            $view->with('activeKop', $activeKop);
        });
    }
}

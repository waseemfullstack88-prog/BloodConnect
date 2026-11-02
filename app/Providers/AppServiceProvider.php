<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fix 419 CSRF errors on LAN: generate routes relative to the actual
        // host the browser used, so form actions and session cookies always match.
        if (!$this->app->runningInConsole()) {
            $this->app['url']->forceRootUrl(
                request()->getSchemeAndHttpHost()
            );
        }
    }
}

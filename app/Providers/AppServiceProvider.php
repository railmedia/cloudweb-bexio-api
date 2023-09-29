<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\UserMeta;

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
        view()->composer('*', function($view) {
            $access_token = '';
            if( Auth::user() ) {
                $access_token = UserMeta::getUserMeta( Auth::id(), 'bexio_access_token' );
            }
            View::share('access_token', $access_token);
        });
    }
}

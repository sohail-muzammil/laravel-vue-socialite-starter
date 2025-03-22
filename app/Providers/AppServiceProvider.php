<?php

namespace App\Providers;

use App\Models\OauthProvider;
use Illuminate\Support\ServiceProvider;

use Inertia\Inertia;

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
        Inertia::share([
            'oauth_providers' => fn () => OauthProvider::where('enabled', true)
            ->orderBy('name')
            ->get(),
        ]);
    }
}

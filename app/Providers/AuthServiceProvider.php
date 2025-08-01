<?php

namespace App\Providers;

use App\Contracts\AuthServiceContract;
use App\Services\Auth\AuthServiceFactory;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \Log::info('AuthServiceProvider loaded');
        $this->app->bind(AuthServiceContract::class, function () {
            return AuthServiceFactory::create();
        });
    }
}

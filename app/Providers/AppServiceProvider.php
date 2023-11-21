<?php

namespace App\Providers;

use App\Helpers\Implementation\RedisPubSubPublisher;
use App\Helpers\IPubSubPublisher;
use App\Repository\Implementation\UserRepository;
use App\Repository\IUserRepository;
use App\Service\IAuthService;
use App\Service\Implementation\AuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IPubSubPublisher::class, RedisPubSubPublisher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

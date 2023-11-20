<?php

namespace App\Providers;

use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\DashboardRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\DashboardRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class,BookingRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class,DashboardRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

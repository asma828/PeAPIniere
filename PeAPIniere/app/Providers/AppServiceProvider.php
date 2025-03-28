<?php

namespace App\Providers;

use App\Repositories\CategoryDAO;
use App\Repositories\Interfaces\CategoryDAOInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\PlantDAOInterface;
use App\Repositories\OrderRepository;
use App\Repositories\PlantDAO;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlantDAOInterface::class, PlantDAO::class);
        $this->app->bind(CategoryDAOInterface::class, CategoryDAO::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

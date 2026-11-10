<?php

namespace App\Providers;

use App\Application\Inventory\Ports\ProductRepository;
use App\Infrastructure\Persistence\Mongo\MongoProductRepository;
use Illuminate\Support\ServiceProvider;

final class InventoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepository::class, MongoProductRepository::class);
    }
}

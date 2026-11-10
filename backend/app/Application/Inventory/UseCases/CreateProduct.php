<?php

namespace App\Application\Inventory\UseCases;

use App\Application\Inventory\Ports\ProductRepository;
use App\Domain\Inventory\Entities\Product;

final readonly class CreateProduct
{
    public function __construct(private ProductRepository $products)
    {
    }

    public function execute(string $name, string $sku, int $stock, float $price): Product
    {
        return $this->products->create(new Product(null, trim($name), trim($sku), $stock, $price));
    }
}

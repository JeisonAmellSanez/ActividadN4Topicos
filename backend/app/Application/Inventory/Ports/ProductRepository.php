<?php

namespace App\Application\Inventory\Ports;

use App\Domain\Inventory\Entities\Product;

interface ProductRepository
{
    /** @return list<Product> */
    public function all(): array;

    public function create(Product $product): Product;

    public function delete(string $id): void;
}

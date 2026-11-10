<?php

namespace App\Application\Inventory\UseCases;

use App\Application\Inventory\Ports\ProductRepository;

final readonly class ListProducts
{
    public function __construct(private ProductRepository $products)
    {
    }

    public function execute(): array
    {
        return $this->products->all();
    }
}

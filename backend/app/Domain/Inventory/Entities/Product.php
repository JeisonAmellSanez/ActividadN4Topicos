<?php

namespace App\Domain\Inventory\Entities;

use InvalidArgumentException;

final readonly class Product
{
    public function __construct(
        public ?string $id,
        public string $name,
        public string $sku,
        public int $stock,
        public float $price,
    ) {
        if ($name === '' || $sku === '') {
            throw new InvalidArgumentException('El nombre y el SKU son obligatorios.');
        }

        if ($stock < 0 || $price < 0) {
            throw new InvalidArgumentException('El stock y el precio no pueden ser negativos.');
        }
    }
}

<?php

namespace App\Infrastructure\Persistence\Mongo;

use App\Application\Inventory\Ports\ProductRepository;
use App\Domain\Inventory\Entities\Product;

final class MongoProductRepository implements ProductRepository
{
    public function all(): array
    {
        return ProductRecord::query()->latest()->get()
            ->map(fn (ProductRecord $record) => $this->toEntity($record))
            ->all();
    }

    public function create(Product $product): Product
    {
        return $this->toEntity(ProductRecord::query()->create([
            'name' => $product->name,
            'sku' => $product->sku,
            'stock' => $product->stock,
            'price' => $product->price,
        ]));
    }

    public function delete(string $id): void
    {
        ProductRecord::query()->findOrFail($id)->delete();
    }

    private function toEntity(ProductRecord $record): Product
    {
        return new Product((string) $record->getKey(), $record->name, $record->sku, $record->stock, $record->price);
    }
}

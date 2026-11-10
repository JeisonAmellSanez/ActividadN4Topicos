<?php

namespace App\Infrastructure\Persistence\Mongo;

use MongoDB\Laravel\Eloquent\Model;

final class ProductRecord extends Model
{
    protected $collection = 'products';

    protected $fillable = ['name', 'sku', 'stock', 'price'];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'float',
    ];
}

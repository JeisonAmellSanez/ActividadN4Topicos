<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class ProductRecord extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'sku', 'stock', 'price'];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'float',
    ];
}

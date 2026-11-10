<?php

namespace App\Http\Controllers;

use App\Application\Inventory\UseCases\CreateProduct;
use App\Application\Inventory\UseCases\ListProducts;
use App\Infrastructure\Persistence\Mongo\MongoProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

final class ProductController extends Controller
{
    public function index(ListProducts $listProducts): JsonResponse
    {
        return response()->json($listProducts->execute());
    }

    public function store(Request $request, CreateProduct $createProduct): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:40', 'unique:products,sku'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $product = $createProduct->execute(
                $data['name'],
                $data['sku'],
                $data['stock'],
                (float) $data['price'],
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages(['product' => $exception->getMessage()]);
        }

        return response()->json($product, 201);
    }

    public function destroy(string $id, MongoProductRepository $products): JsonResponse
    {
        $products->delete($id);

        return response()->json(status: 204);
    }
}

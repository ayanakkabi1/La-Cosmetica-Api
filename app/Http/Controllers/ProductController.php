<?php

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return response()->json($this->productService->getAll());
    }

    public function show(string $slug)
    {
        $product = $this->productService->findBySlug($slug);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(ProductRequest $request)
    {
        $dto = new ProductDTO($request->validated());
        $product = $this->productService->create($dto);

        return response()->json($product, 201);
    }

    public function update(ProductRequest $request, string $id)
    {
        $dto = new ProductDTO($request->validated());
        $product = $this->productService->update((int) $id, $dto);

        return response()->json($product);
    }

    public function destroy(string $id)
    {
        $deleted = $this->productService->delete((int) $id);

        if (!$deleted) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['message' => 'Product deleted']);
    }
}

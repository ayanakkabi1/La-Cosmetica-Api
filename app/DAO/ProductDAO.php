<?php

namespace App\DAO;

use App\Models\Product;

class ProductDAO
{
    public function getAll()
    {
        return Product::with(['category', 'images'])->get();
    }

    public function findBySlug(string $slug)
    {
        return Product::with(['category', 'images'])->where('slug', $slug)->first();
    }

    public function findById(int $id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id)
    {
        return Product::destroy($id);
    }
}

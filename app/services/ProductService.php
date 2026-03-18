<?php

namespace App\Services;

use App\DAO\ProductDAO;
use App\DTO\ProductDTO;
use Illuminate\Support\Str;

class ProductService
{
    private ProductDAO $productDAO;

    public function __construct(ProductDAO $productDAO)
    {
        $this->productDAO = $productDAO;
    }

    public function getAll()
    {
        return $this->productDAO->getAll();
    }

    public function findBySlug(string $slug)
    {
        return $this->productDAO->findBySlug($slug);
    }

    public function create(ProductDTO $dto)
    {
        return $this->productDAO->create([
            'name'        => $dto->name,
            'slug'        => Str::slug($dto->name),
            'description' => $dto->description,
            'price'       => $dto->price,
            'category_id' => $dto->category_id,
        ]);
    }

    public function update(int $id, ProductDTO $dto)
    {
        return $this->productDAO->update($id, [
            'name'        => $dto->name,
            'slug'        => Str::slug($dto->name),
            'description' => $dto->description,
            'price'       => $dto->price,
            'category_id' => $dto->category_id,
        ]);
    }

    public function delete(int $id)
    {
        return $this->productDAO->delete($id);
    }
}

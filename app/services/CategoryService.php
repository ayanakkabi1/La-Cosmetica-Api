<?php

namespace App\Services;

use App\DAO\CategoryDAO;
use App\DTO\CategoryDTO;
use Illuminate\Support\Str;

class CategoryService
{
    private CategoryDAO $categoryDAO;

    public function __construct(CategoryDAO $categoryDAO)
    {
        $this->categoryDAO = $categoryDAO;
    }

    public function getAll()
    {
        return $this->categoryDAO->getAll();
    }

    public function findById(int $id)
    {
        return $this->categoryDAO->findById($id);
    }

    public function create(CategoryDTO $dto)
    {
        return $this->categoryDAO->create([
            'name' => $dto->name,
            'slug' => Str::slug($dto->name),
        ]);
    }

    public function update(int $id, CategoryDTO $dto)
    {
        return $this->categoryDAO->update($id, [
            'name' => $dto->name,
            'slug' => Str::slug($dto->name),
        ]);
    }

    public function delete(int $id)
    {
        return $this->categoryDAO->delete($id);
    }
}

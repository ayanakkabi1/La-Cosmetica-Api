<?php

namespace App\DAO;

use App\Models\Category;

class CategoryDAO
{
    public function getAll()
    {
        return Category::all();
    }

    public function findById(int $id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(int $id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete(int $id)
    {
        return Category::destroy($id);
    }
}

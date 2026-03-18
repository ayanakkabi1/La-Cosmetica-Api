<?php

namespace App\Http\Controllers;

use App\DTO\CategoryDTO;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return response()->json($this->categoryService->getAll());
    }

    public function store(CategoryRequest $request)
    {
        $dto = new CategoryDTO($request->validated());
        $category = $this->categoryService->create($dto);

        return response()->json($category, 201);
    }

    public function show(string $id)
    {
        $category = $this->categoryService->findById((int) $id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    public function update(CategoryRequest $request, string $id)
    {
        $dto = new CategoryDTO($request->validated());
        $category = $this->categoryService->update((int) $id, $dto);

        return response()->json($category);
    }

    public function destroy(string $id)
    {
        $deleted = $this->categoryService->delete((int) $id);

        if (!$deleted) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['message' => 'Category deleted']);
    }
}

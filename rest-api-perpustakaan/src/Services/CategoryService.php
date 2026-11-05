<?php

namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryService
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories(): array
    {
        return $this->repository->findAll();
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->repository->findById($id);
    }

    public function createCategory(Category $category): bool
    {
        return $this->repository->create($category);
    }

    public function updateCategory(int $id, Category $category): bool
    {
        return $this->repository->update($id, $category);
    }

    public function deleteCategory(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): void
    {
        $categories = $this->service->getAllCategories();

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'nama' => $category->getNama(),
                'deskripsi' => $category->getDeskripsi()
            ];
        }

        echo json_encode($data);
    }

    public function show(Request $request, int $id): void
    {
        $category = $this->service->getCategoryById($id);

        if (!$category) {
            http_response_code(404);
            echo json_encode(['message' => 'Kategori tidak ditemukan']);
            return;
        }

        echo json_encode([
            'id' => $category->getId(),
            'nama' => $category->getNama(),
            'deskripsi' => $category->getDeskripsi()
        ]);
    }

    public function store(Request $request): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['nama']) || empty(trim($input['nama']))) {
            http_response_code(400);
            echo json_encode(['message' => 'Nama kategori wajib diisi']);
            return;
        }

        $category = new Category(
            $input['nama'],
            $input['deskripsi'] ?? null
        );

        $this->service->createCategory($category);

        http_response_code(201);
        echo json_encode(['message' => 'Kategori berhasil ditambahkan']);
    }

    public function update(Request $request, int $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['nama']) || empty(trim($input['nama']))) {
            http_response_code(400);
            echo json_encode(['message' => 'Nama kategori wajib diisi']);
            return;
        }

        $category = new Category(
            $input['nama'],
            $input['deskripsi'] ?? null
        );

        $this->service->updateCategory($id, $category);

        echo json_encode(['message' => 'Kategori berhasil diperbarui']);
    }

    public function destroy(Request $request, int $id): void
    {
        $this->service->deleteCategory($id);

        echo json_encode(['message' => 'Kategori berhasil dihapus']);
    }
}

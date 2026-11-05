<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Database\Connection;
use App\Models\Category;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];

        foreach ($rows as $row) {
            $categories[] = new Category(
                $row['nama'],
                $row['deskripsi'],
                (int) $row['id']
            );
        }

        return $categories;
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category(
            $row['nama'],
            $row['deskripsi'],
            (int) $row['id']
        );
    }

    public function create(Category $category): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (nama, deskripsi) VALUES (?, ?)"
        );

        return $stmt->execute([
            $category->getNama(),
            $category->getDeskripsi()
        ]);
    }

    public function update(int $id, Category $category): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE categories SET nama = ?, deskripsi = ? WHERE id = ?"
        );

        return $stmt->execute([
            $category->getNama(),
            $category->getDeskripsi(),
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");

        return $stmt->execute([$id]);
    }
}
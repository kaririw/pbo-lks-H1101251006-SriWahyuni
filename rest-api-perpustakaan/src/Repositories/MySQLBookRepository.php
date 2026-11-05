<?php

namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Database\Connection;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $books = [];

        foreach ($rows as $row) {
            $books[] = new Book(
                $row['judul'],
                $row['penulis'],
                (int) $row['tahun_terbit'],
                (int) $row['stok'],
                (int) $row['id']
            );
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Book(
            $row['judul'],
            $row['penulis'],
            (int) $row['tahun_terbit'],
            (int) $row['stok'],
            (int) $row['id']
        );
    }

    public function create(Book $book): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO books (judul, penulis, tahun_terbit, stok) VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $book->getJudul(),
            $book->getPenulis(),
            $book->getTahunTerbit(),
            $book->getStok()
        ]);
    }

    public function update(int $id, Book $book): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE books SET judul = ?, penulis = ?, tahun_terbit = ?, stok = ? WHERE id = ?"
        );

        return $stmt->execute([
            $book->getJudul(),
            $book->getPenulis(),
            $book->getTahunTerbit(),
            $book->getStok(),
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");

        return $stmt->execute([$id]);
    }
}

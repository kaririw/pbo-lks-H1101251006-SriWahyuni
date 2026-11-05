<?php

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use Exception;

class BookService
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(): array
    {
        return $this->repository->findAll();
    }

    public function find(int $id): Book
    {
        $book = $this->repository->findById($id);

        if (!$book) {
            throw new Exception("Buku dengan ID {$id} tidak ditemukan");
        }

        return $book;
    }

    public function create(array $data): array
    {
        $this->validate($data);

        $book = new Book(
            $data['judul'],
            $data['penulis'],
            (int) $data['tahun_terbit'],
            (int) $data['stok']
        );

        if (!$this->repository->create($book)) {
            throw new Exception("Gagal menambahkan buku");
        }

        $books = $this->repository->findAll();
        $createdBook = end($books);

        return [
            'id' => $createdBook->getId(),
            'judul' => $book->getJudul()
        ];
    }

    public function update(int $id, array $data): bool
    {
        $this->find($id);
        $this->validate($data);

        $book = new Book(
            $data['judul'],
            $data['penulis'],
            (int) $data['tahun_terbit'],
            (int) $data['stok'],
            $id
        );

        if (!$this->repository->update($id, $book)) {
            throw new Exception("Gagal mengubah buku");
        }

        return true;
    }

    public function delete(int $id): bool
    {
        $this->find($id);

        if (!$this->repository->delete($id)) {
            throw new Exception("Gagal menghapus buku");
        }

        return true;
    }

    private function validate(array $data): void
    {
        if (empty(trim($data['judul'] ?? ''))) {
            throw new Exception("Judul wajib diisi");
        }

        if (empty(trim($data['penulis'] ?? ''))) {
            throw new Exception("Penulis wajib diisi");
        }

        if (!isset($data['tahun_terbit']) || !is_numeric($data['tahun_terbit']) || $data['tahun_terbit'] <= 0) {
            throw new Exception("Tahun terbit wajib diisi");
        }

        if (!isset($data['stok']) || !is_numeric($data['stok']) || $data['stok'] < 0) {
            throw new Exception("Stok tidak valid");
        }
    }
}

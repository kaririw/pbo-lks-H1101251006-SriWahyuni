<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\BookService;
use Exception;

class BookController
{
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): void
    {
        Response::success(
            $this->service->list(),
            'Daftar buku'
        );
    }

    public function show(Request $request, int $id): void
    {
        try {
            $book = $this->service->find($id);

            Response::success(
                $book->jsonSerialize(),
                'Detail buku'
            );
        } catch (Exception $e) {
            Response::error($e->getMessage(), 404);
        }
    }

    public function store(Request $request): void
    {
        try {
            $data = $request->getBody();
            $result = $this->service->create($data);

            Response::json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $result
            ], 201);
        } catch (Exception $e) {
            Response::error($e->getMessage(), 422);
        }
    }

    public function update(Request $request, int $id): void
    {
        try {
            $data = $request->getBody();
            $this->service->update($id, $data);

            Response::success([], 'Buku berhasil diubah');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 422);
        }
    }

    public function destroy(Request $request, int $id): void
    {
        try {
            $this->service->delete($id);

            Response::success([], 'Buku berhasil dihapus');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 404);
        }
    }
}

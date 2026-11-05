<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Repositories\MySQLBookRepository;
use App\Services\BookService;

Env::load(__DIR__ . '/.env');

$service = new BookService(new MySQLBookRepository());

// Test 1: list
echo "Daftar buku: " . count($service->list()) . PHP_EOL;

// Test 2: validasi
try {
    $service->create(['judul' => '']);
} catch (Exception $e) {
    echo "Validasi berjalan: " . $e->getMessage() . PHP_EOL;
}

// Test 3: tambah buku
$new = $service->create([
    'judul'   => 'Pulang',
    'penulis' => 'Leila S. Chudori',
    'tahun'   => 2012,
    'stok'    => 4,
]);

echo "Buku dibuat: {$new['judul']} (ID: {$new['id']})" . PHP_EOL;
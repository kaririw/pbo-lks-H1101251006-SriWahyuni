<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Repositories\MySQLBookRepository;

Env::load(__DIR__ . '/.env');

$repository = new MySQLBookRepository();
$books = $repository->findAll();

echo "Total buku: " . count($books) . PHP_EOL;

foreach ($books as $book) {
    echo "- " . $book->getJudul();
    echo " (" . $book->getPenulis() . ")";
    echo " - Stok: " . $book->getStok() . PHP_EOL;
}


<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Contracts\BookRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Repositories\MySQLBookRepository;
use App\Repositories\MySQLCategoryRepository;
use App\Services\BookService;
use App\Services\CategoryService;
use App\Controllers\BookController;
use App\Controllers\CategoryController;

Env::load(__DIR__ . '/../.env');

$container = new Container();

// Binding Repository Buku
$container->bind(BookRepositoryInterface::class, function () {
    return new MySQLBookRepository();
});

// Binding Service Buku
$container->bind(BookService::class, function () {
    return new BookService(new MySQLBookRepository());
});

// Binding Controller Buku
$container->bind(BookController::class, function () {
    return new BookController(
        new BookService(new MySQLBookRepository())
    );
});

// Binding Repository Kategori
$container->bind(CategoryRepositoryInterface::class, function () {
    return new MySQLCategoryRepository();
});

// Binding Service Kategori
$container->bind(CategoryService::class, function () {
    return new CategoryService(new MySQLCategoryRepository());
});

// Binding Controller Kategori
$container->bind(CategoryController::class, function () {
    return new CategoryController(
        new CategoryService(new MySQLCategoryRepository())
    );
});

$router = new Router();

// Controller Buku
$controller = $container->make(BookController::class);

// Route daftar dan tambah buku
$router->get('/api/books', [$controller, 'index']);
$router->post('/api/books', [$controller, 'store']);

// Route detail, ubah, dan hapus buku
$router->get('/api/books/{id}', [$controller, 'show']);
$router->put('/api/books/{id}', [$controller, 'update']);
$router->delete('/api/books/{id}', [$controller, 'destroy']);

// Controller Kategori
$categoryController = $container->make(CategoryController::class);

// Route daftar dan tambah kategori
$router->get('/api/categories', [$categoryController, 'index']);
$router->post('/api/categories', [$categoryController, 'store']);

// Route detail, ubah, dan hapus kategori
$router->get('/api/categories/{id}', [$categoryController, 'show']);
$router->put('/api/categories/{id}', [$categoryController, 'update']);
$router->delete('/api/categories/{id}', [$categoryController, 'destroy']);

$router->resolve(new Request());

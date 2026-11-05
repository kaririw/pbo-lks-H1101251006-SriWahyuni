<?php

namespace App\Core;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public static function success(array $data, string $message = 'Success'): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error(string $message, int $statusCode = 400): void
    {
        self::json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}
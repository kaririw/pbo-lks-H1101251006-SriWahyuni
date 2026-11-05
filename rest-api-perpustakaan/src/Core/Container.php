<?php

namespace App\Core;

use Exception;

class Container
{
    private array $bindings = [];

    public function bind(string $abstract, callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make(string $abstract): mixed
    {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception("Binding tidak ditemukan: {$abstract}");
        }

        return call_user_func($this->bindings[$abstract]);
    }
}
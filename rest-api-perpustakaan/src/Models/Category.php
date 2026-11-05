<?php

namespace App\Models;

class Category
{
    private ?int $id;
    private string $nama;
    private ?string $deskripsi;

    public function __construct(
        string $nama,
        ?string $deskripsi = null,
        ?int $id = null
    ) {
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getDeskripsi(): ?string
    {
        return $this->deskripsi;
    }
}
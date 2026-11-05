
CREATE DATABASE IF NOT EXISTS perpustakaan;

USE perpustakaan;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    stok INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT
);

INSERT INTO books (judul, penulis, tahun_terbit, stok)
VALUES
('Laskar Pelangi', 'Andrea Hirata', 2005, 5),
('Bumi Manusia', 'Pramoedya Ananta Toer', 1980, 3);

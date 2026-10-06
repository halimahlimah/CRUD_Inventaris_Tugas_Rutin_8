CREATE DATABASE IF NOT EXISTS inventaris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS supplier;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    telepon VARCHAR(30) NOT NULL,
    alamat VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    id_supplier INT NOT NULL,
    kode_produk VARCHAR(30) NOT NULL UNIQUE,
    nama_produk VARCHAR(150) NOT NULL,
    jumlah INT NOT NULL DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_produk_supplier FOREIGN KEY (id_supplier) REFERENCES supplier(id_supplier)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO kategori (nama_kategori) VALUES
('Elektronik'), ('Alat Tulis'), ('Perabot'), ('Perlengkapan Kantor'), ('Jaringan');

INSERT INTO supplier (nama_supplier, telepon, alamat) VALUES
('PT Maju Jaya','081234567890','Medan'),
('CV Sumber Makmur','081234567891','Binjai'),
('PT Teknologi Nusantara','081234567892','Deli Serdang'),
('CV Berkah Abadi','081234567893','Tebing Tinggi'),
('PT Sentosa Supply','081234567894','Pematangsiantar');

INSERT INTO produk (id_kategori,id_supplier,kode_produk,nama_produk,jumlah,harga) VALUES
(1,1,'ELK001','Laptop',10,7500000),
(1,3,'ELK002','Monitor LED',15,2100000),
(2,2,'ATK001','Buku Tulis',50,8000),
(3,4,'PRB001','Kursi Kantor',20,650000),
(5,5,'NET001','Router WiFi',12,450000);

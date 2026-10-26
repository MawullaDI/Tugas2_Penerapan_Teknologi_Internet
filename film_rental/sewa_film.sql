CREATE DATABASE IF NOT EXISTS db_film_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_film_rental;

DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE peminjaman (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_peminjam VARCHAR(100) NOT NULL,
  judul_film VARCHAR(150) NOT NULL,
  tanggal_pinjam DATE NOT NULL,
  tanggal_kembali DATE NOT NULL,
  status ENUM('dipinjam','dikembalikan','terlambat') NOT NULL DEFAULT 'dipinjam',
  catatan VARCHAR(255) DEFAULT ''
) ENGINE=InnoDB;

-- login: admin / admin123
INSERT INTO users (username, password) VALUES ('admin', '$2b$10$x2X6Uj1vBVqEujnSJG7D/.LkKwvLHmtjb.kxZOu3I4zgbHuZ1juTq');

INSERT INTO peminjaman (nama_peminjam, judul_film, tanggal_pinjam, tanggal_kembali, status, catatan) VALUES
('Budi Santoso','Interstellar','2026-10-15','2026-10-18','dipinjam','Cakram DVD'),
('Siti Aminah','Parasite','2026-10-10','2026-10-13','dikembalikan',''),
('Andi Wijaya','Your Name','2026-10-05','2026-10-08','terlambat','Belum kembali');

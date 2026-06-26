CREATE DATABASE IF NOT EXISTS dp_sistem;
USE dp_sistem;

-- Tabel Users (pengguna umum)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Gejala
CREATE TABLE gejala (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama_gejala VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Penyakit
CREATE TABLE penyakit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama_penyakit VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    solusi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Rule (relasi gejala-penyakit + nilai probabilitas/bobot untuk Bayes)
-- nilai_probabilitas = P(gejala | penyakit) yang diinput admin (0 - 1)
CREATE TABLE rule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    penyakit_id INT NOT NULL,
    gejala_id INT NOT NULL,
    nilai_probabilitas DECIMAL(4,3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penyakit_id) REFERENCES penyakit(id) ON DELETE CASCADE,
    FOREIGN KEY (gejala_id) REFERENCES gejala(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rule (penyakit_id, gejala_id)
);

-- Tabel Riwayat Diagnosa
CREATE TABLE riwayat_diagnosa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    penyakit_id INT NOT NULL,
    persentase DECIMAL(5,2) NOT NULL,
    gejala_terpilih TEXT NOT NULL COMMENT 'simpan id gejala terpilih, dipisah koma',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (penyakit_id) REFERENCES penyakit(id) ON DELETE CASCADE
);

-- Seeder admin default (password: admin123, sudah di-hash bcrypt)
INSERT INTO admin (nama, username, password) VALUES
('Administrator', 'admin', '$2y$10$YQq8s6CwQzKZxKzKxKzKxOQq8s6CwQzKZxKzKxKzKxOQq8s6CwQzK');
--Catatan: hash password di atas hanya placeholder — kita generate ulang yang valid di Sesi 2 (saat membuat sistem auth), supaya pasti cocok dengan fungsi password_verify() PHP.
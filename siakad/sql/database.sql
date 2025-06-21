-- Buat database (opsional)
CREATE DATABASE IF NOT EXISTS akademik1;
USE akademik_3nf;

-- TABEL JURUSAN
CREATE TABLE jurusan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_jurusan VARCHAR(100) NOT NULL
);

-- TABEL MAHASISWA
CREATE TABLE mahasiswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nim VARCHAR(20) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jurusan_id INT NOT NULL,
  tanggal_lahir DATE,
  FOREIGN KEY (jurusan_id) REFERENCES jurusan(id)
);

-- TABEL MATAKULIAH
CREATE TABLE matakuliah (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_mk VARCHAR(20) NOT NULL,
  nama_mk VARCHAR(100) NOT NULL,
  sks INT,
  jurusan_id INT NOT NULL,
  FOREIGN KEY (jurusan_id) REFERENCES jurusan(id)
);

-- TABEL NILAI
CREATE TABLE nilai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mahasiswa_id INT NOT NULL,
  matakuliah_id INT NOT NULL,
  nilai_angka INT,
  tanggal_input DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id),
  FOREIGN KEY (matakuliah_id) REFERENCES matakuliah(id)
);

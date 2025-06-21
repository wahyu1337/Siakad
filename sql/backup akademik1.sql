-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 10:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akademik1`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `bidang` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `nip`, `nama`, `jenis_kelamin`, `email`, `bidang`) VALUES
(1, '001', 'Dr. IT., Ir. SUPRIADI SAHIBU, S.Kom., MT', 'Laki-laki', '', 'TI');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `matakuliah_id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `matakuliah_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `kelas_id`, `tahun_ajaran_id`) VALUES
(1, 1, 1, 'Selasa', '10:00:00', '12:00:00', 1, 1),
(2, 1, 1, 'Selasa', '08:00:00', '10:00:00', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama_jurusan`) VALUES
(1, 'Teknik Informatika');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `jurusan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `jurusan_id`) VALUES
(1, 'TI-A', 1),
(2, 'TI-B', 1),
(3, 'TI-C', 1);

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `matakuliah_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jurusan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_kelas`
--

CREATE TABLE `mahasiswa_kelas` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL,
  `jurusan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `jurusan_id`) VALUES
(1, 'TIRPL06', 'Rekayasa Perangkat Lunak', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `matakuliah_id` int(11) NOT NULL,
  `nilai_angka` int(11) NOT NULL,
  `semester` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` int(11) NOT NULL,
  `tahun_mulai` int(11) NOT NULL,
  `tahun_selesai` int(11) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `tahun_mulai`, `tahun_selesai`, `semester`, `status_aktif`) VALUES
(1, 2024, 2025, 'Genap', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dosen','mahasiswa') NOT NULL,
  `dosen_id` int(11) DEFAULT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `dosen_id`, `mahasiswa_id`) VALUES
(4, 'admin', '$2y$10$k8MxyRzdLwkNYvsRfhkRGuMq1IHILnkFuJ1GOCk.HshWh3BMfDWKy', 'admin', NULL, NULL),
(10, 'supriadi001', '$2y$10$XxFTcNqqKnVHcG.Iw.7d8OVxocrigBxGEKyYVOT.e.bmGqjUTrNnS', 'dosen', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matakuliah_id` (`matakuliah_id`),
  ADD KEY `dosen_id` (`dosen_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `fk_jadwal_tahun_ajaran` (`tahun_ajaran_id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas_jurusan` (`jurusan_id`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `matakuliah_id` (`matakuliah_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indexes for table `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `matakuliah_id` (`matakuliah_id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `dosen_id` (`dosen_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_jadwal_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`),
  ADD CONSTRAINT `krs_ibfk_3` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`),
  ADD CONSTRAINT `krs_ibfk_4` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`);

--
-- Constraints for table `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  ADD CONSTRAINT `mahasiswa_kelas_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `mahasiswa_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `mahasiswa_kelas_ibfk_3` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`);

--
-- Constraints for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD CONSTRAINT `matakuliah_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`);

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 08:03 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kelulusan`
--

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `kode_mapel` varchar(10) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`kode_mapel`, `nama_mapel`) VALUES
('AGM01', 'Pendidikan Agama Islam'),
('BIN01', 'Bahasa Indonesiaa'),
('ING01', 'Bahasa Inggris'),
('IPA01', 'Ilmu Pengetahuan Alam'),
('IPS01', 'Ilmu Pengetahuan Sosial'),
('MTK01', 'Matematika'),
('MUL01', 'Muatan Lokal'),
('PJO01', 'Pendidikan Jasmani dan Olahraga'),
('PKN01', 'Pendidikan Kewarganegaraan'),
('PRA01', 'Prakarya'),
('SEN01', 'Seni Budaya'),
('TIK01', 'Informatika');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `kode_mapel` varchar(10) NOT NULL,
  `smt1` decimal(5,2) DEFAULT NULL,
  `smt2` decimal(5,2) DEFAULT NULL,
  `smt3` decimal(5,2) DEFAULT NULL,
  `smt4` decimal(5,2) DEFAULT NULL,
  `smt5` decimal(5,2) DEFAULT NULL,
  `uas` decimal(5,2) DEFAULT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `kkm` decimal(5,2) DEFAULT 80.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `nis`, `kode_mapel`, `smt1`, `smt2`, `smt3`, `smt4`, `smt5`, `uas`, `tahun_ajaran`, `kkm`) VALUES
(2, '12345', 'BIN01', '12.00', '100.00', '82.00', '100.00', '100.00', '85.00', '2024/2025', '80.00'),
(4, '20231002', 'IPA01', '81.00', '80.00', '83.00', '100.00', '100.00', '85.00', '2024/2025', '80.00'),
(5, '20231003', 'BIN01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00'),
(10, '20231004', 'IPA01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00'),
(11, '20231003', 'IPA01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00'),
(12, '20231003', 'AGM01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00'),
(13, '20231003', 'ING01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00'),
(14, '20231003', 'IPS01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00'),
(15, '20231003', 'SEN01', '100.00', '100.00', '100.00', '100.00', '100.00', '100.00', '2024/2025', '80.00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `kop_surat` text DEFAULT NULL,
  `jadwal_pengumuman` datetime DEFAULT NULL,
  `nama_kepala_sekolah` varchar(100) DEFAULT NULL,
  `nip_kepala_sekolah` varchar(30) DEFAULT NULL,
  `nama_sekolah` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `tahun_ajaran`, `logo`, `kop_surat`, `jadwal_pengumuman`, `nama_kepala_sekolah`, `nip_kepala_sekolah`, `nama_sekolah`) VALUES
(2, '2024/2025', '../img/logo/kop.JPG', 'DINAS PENDIDIKAN', '2025-07-31 10:20:20', 'idil putra', '11132312321213123', 'SMPN 4 KOTA SOLOK');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `nama_orang_tua` varchar(100) DEFAULT NULL,
  `kelas` varchar(10) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status_kelulusan` enum('Lulus','Lulus Bersyarat','Tidak Lulus') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nisn`, `nama`, `tempat_lahir`, `nama_orang_tua`, `kelas`, `tahun_ajaran`, `password`, `status_kelulusan`) VALUES
('090998', '08980089809', 'idil ', 'padang', 'idil', '78', '2024/2025', NULL, NULL),
('12345', NULL, 'idil', NULL, NULL, '7A', '2024/2025', NULL, 'Tidak Lulus'),
('20231001', NULL, 'Ahmad Fauziia', NULL, NULL, '7A', '2024/2025', NULL, 'Tidak Lulus'),
('20231002', NULL, 'Putri Ayu', NULL, NULL, '7A', '2024/2025', NULL, 'Tidak Lulus'),
('20231003', NULL, 'Mira Lestari', NULL, NULL, '7B', '2024/2025', NULL, 'Tidak Lulus'),
('20231004', NULL, 'Rian Pratama', NULL, NULL, '7B', '2024/2025', NULL, 'Tidak Lulus'),
('asdasdasda', NULL, 'asdsad', NULL, NULL, '78', '2024/2025', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','guru_mapel','wali_kelas','siswa') NOT NULL,
  `kode_mapel` varchar(10) DEFAULT NULL,
  `kelas_wali` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `kode_mapel`, `kelas_wali`) VALUES
(1, 'admin01', '$2y$10$admin', 'Administrator Satu', 'admin', NULL, NULL),
(2, 'guru_mtk', '$2y$10$gurumtk', 'Budi Santoso', 'guru_mapel', 'BIN01', NULL),
(3, 'guru_bindo', '$2y$10$gurubindo', 'Siti Aminah', 'guru_mapel', 'BIN01', NULL),
(4, 'wali_7a', '$2y$10$AyFnZzkqKlgT1swK2DbdtOf3fgvKc8tvqvEQqM/4Q4.Tvgh1wEazq', 'Dedi Wali', 'wali_kelas', NULL, '7A'),
(5, 'wali_7b', '$2y$10$wali7b', 'Rina Wali', 'wali_kelas', NULL, '7B'),
(7, 'admin011', '$2y$10$UFL5nB0j8MJTANWYEUNq7O5IUVN1jHFFG4239H0jrgpYeEGvqKBuy', 'asd', 'admin', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`kode_mapel`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `nis` (`nis`),
  ADD KEY `kode_mapel` (`kode_mapel`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2024 at 05:54 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akun`
--

CREATE TABLE `tbl_akun` (
  `kode` varchar(20) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `no_telp` varchar(13) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jurusan` enum('Sistem Informasi','Sistem Komputer') DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `terakhir_diubah_oleh` varchar(20) DEFAULT NULL,
  `terakhir_diubah_pada` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_akun`
--

INSERT INTO `tbl_akun` (`kode`, `jabatan_id`, `nama_lengkap`, `no_telp`, `email`, `password`, `jurusan`, `semester`, `tanggal_bergabung`, `dibuat_oleh`, `dibuat_pada`, `terakhir_diubah_oleh`, `terakhir_diubah_pada`) VALUES
('14115717', 4, 'Admin Aplikasi', '', 'admin@elibrary-ubk.com', '$2y$10$2jVohspZPBW.w89tit7usOpbHV2QQpQ3BLNKq5WmdrEO0EeVBvkbq', NULL, NULL, NULL, '14115717', '2023-09-22 17:00:00', NULL, '2023-09-24 14:10:34'),
('7201190013', 1, 'Mahasiswa 1', '', '', '$2y$10$WeTUIka/Ijl9UeKFMjemD.g5VohBQVChC6f2D15hOXXP2iX81ulzC', 'Sistem Informasi', 10, '2019-03-01', '7201190013', '2024-02-22 12:18:45', '7201190013', '2024-02-22 12:18:45'),
('998', 2, 'Kaprodi SI', NULL, '', '$2y$10$8EeulAkf38LdE8wmCjZiHuZBbOXbZWsEeUo..heNTisO20l4ibw0S', 'Sistem Informasi', NULL, NULL, '14115717', '2024-02-22 12:18:13', '14115717', '2024-02-22 12:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dokumen_akhir`
--

CREATE TABLE `tbl_dokumen_akhir` (
  `id` int(11) NOT NULL,
  `pengajuan_id` int(11) NOT NULL,
  `dokumen_akhir` varchar(250) NOT NULL,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `terakhir_diubah_oleh` varchar(20) DEFAULT NULL,
  `terakhir_diubah_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dokumen_akhir`
--

INSERT INTO `tbl_dokumen_akhir` (`id`, `pengajuan_id`, `dokumen_akhir`, `dibuat_oleh`, `dibuat_pada`, `terakhir_diubah_oleh`, `terakhir_diubah_pada`) VALUES
(6, 7, 'LPK-Final-7201190013-1709549728.pdf', '7201190013', '2024-03-04 10:55:28', '7201190013', '2024-03-04 10:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jabatan`
--

CREATE TABLE `tbl_jabatan` (
  `id` int(11) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `terakhir_diubah_oleh` varchar(20) DEFAULT NULL,
  `terakhir_diubah_pada` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_jabatan`
--

INSERT INTO `tbl_jabatan` (`id`, `jabatan`, `dibuat_oleh`, `dibuat_pada`, `terakhir_diubah_oleh`, `terakhir_diubah_pada`) VALUES
(1, 'Mahasiswa', 'System', '2023-09-23 13:45:43', '', '2023-09-24 14:09:25'),
(2, 'Kaprodi SI', 'System', '2023-09-23 13:47:02', '', '2023-09-24 14:09:25'),
(3, 'Kaprodi SK', 'System', '2023-09-23 13:47:02', '', '2023-09-24 14:09:25'),
(4, 'Admin', 'System', '2023-09-23 13:47:02', '', '2023-09-24 14:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengajuan`
--

CREATE TABLE `tbl_pengajuan` (
  `id` int(11) NOT NULL,
  `tipe_pengajuan_id` int(11) NOT NULL,
  `status_pengajuan_id` int(11) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `dosen_pembimbing` varchar(250) NOT NULL,
  `dokumen_pengajuan` varchar(250) NOT NULL,
  `surat_validasi` varchar(250) DEFAULT NULL,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `terakhir_diubah_oleh` varchar(20) DEFAULT NULL,
  `terakhir_diubah_pada` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pengajuan`
--

INSERT INTO `tbl_pengajuan` (`id`, `tipe_pengajuan_id`, `status_pengajuan_id`, `judul`, `dosen_pembimbing`, `dokumen_pengajuan`, `surat_validasi`, `dibuat_oleh`, `dibuat_pada`, `terakhir_diubah_oleh`, `terakhir_diubah_pada`) VALUES
(7, 1, 6, 'Test Pengajuan LKP', 'Test Pembimbing SI', 'Proposal-LKP-7201190013-1709549656.pdf', 'Surat-Validasi-LPK-7201190013-1709549728.pdf', '7201190013', '2024-03-04 10:54:16', '998', '2024-03-04 10:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_proses_pengajuan`
--

CREATE TABLE `tbl_proses_pengajuan` (
  `id` int(11) NOT NULL,
  `pengajuan_id` int(11) NOT NULL,
  `status_pengajuan_id` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `dokumen_revisi` varchar(500) DEFAULT NULL,
  `tampilkan` int(11) NOT NULL DEFAULT 1,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_proses_pengajuan`
--

INSERT INTO `tbl_proses_pengajuan` (`id`, `pengajuan_id`, `status_pengajuan_id`, `catatan`, `dokumen_revisi`, `tampilkan`, `dibuat_oleh`, `dibuat_pada`) VALUES
(55, 7, 2, NULL, NULL, 1, '7201190013', '2024-03-04 10:54:16'),
(56, 7, 3, NULL, NULL, 0, '7201190013', '2024-03-04 10:54:16'),
(57, 7, 4, '', NULL, 1, '998', '2024-03-04 10:54:45'),
(58, 7, 9, NULL, NULL, 0, '998', '2024-03-04 10:54:45'),
(59, 7, 10, NULL, NULL, 1, '7201190013', '2024-03-04 10:55:28'),
(60, 7, 3, NULL, NULL, 0, '7201190013', '2024-03-04 10:55:28'),
(61, 7, 11, '', NULL, 1, '998', '2024-03-04 10:55:41'),
(62, 7, 6, NULL, NULL, 0, '998', '2024-03-04 10:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status_pengajuan`
--

CREATE TABLE `tbl_status_pengajuan` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `terakhir_diubah_oleh` varchar(20) NOT NULL,
  `terakhir_diubah_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_status_pengajuan`
--

INSERT INTO `tbl_status_pengajuan` (`id`, `status`, `deskripsi`, `dibuat_oleh`, `dibuat_pada`, `terakhir_diubah_oleh`, `terakhir_diubah_pada`) VALUES
(1, 'Simpan Proposal sebagai Draft', 'Status Proses', 'System', '2023-09-25 00:14:05', 'System', '2023-09-25 00:14:05'),
(2, 'Submit Proposal Baru', 'Aksi Pengguna', 'System', '2023-09-25 00:14:05', 'System', '2023-09-25 00:14:05'),
(3, 'Menunggu Persetujuan', 'Status Proses', 'System', '2023-09-25 00:15:50', 'System', '2023-09-25 00:15:50'),
(4, 'Pengajuan Diterima', 'Aksi Pengguna', 'System', '2023-09-25 00:15:50', 'System', '2023-09-25 00:15:50'),
(5, 'Pengajuan Direvisi', 'Aksi Pengguna', 'System', '2023-09-25 00:18:07', 'System', '2023-09-25 00:18:07'),
(6, 'Pengajuan Selesai', 'Status Proses', 'System', '2023-09-25 00:18:07', 'System', '2023-09-25 00:18:07'),
(7, 'Menunggu Pengajuan Direvisi', 'Status Proses', 'System', '2023-09-25 00:21:07', 'System', '2023-09-25 00:21:07'),
(8, 'Merevisi Pengajuan', 'Aksi Pengguna', 'System', '2023-09-25 00:21:07', 'System', '2023-09-25 00:21:07'),
(9, 'Menunggu Unggahan Dokumen Akhir', 'Status Proses', 'System', '2023-09-26 00:07:53', 'System', '2023-09-26 00:07:53'),
(10, 'Mengunggah Dokumen Akhir', 'Aksi Pengguna', 'System', '2023-09-26 16:27:41', 'System', '2023-09-26 16:27:41'),
(11, 'Dokumen Akhir Diterima', 'Aksi Pengguna', 'System', '2023-09-26 17:21:10', 'System', '2023-09-26 17:21:10'),
(12, 'Dokumen Akhir Direvisi', 'Aksi Pengguna', 'System', '2023-09-26 17:21:10', 'System', '2023-09-26 17:21:10'),
(13, 'Menunggu Dokumen Akhir Direvisi', 'Status Proses', 'System', '2023-09-26 17:23:47', 'System', '2023-09-26 17:23:47'),
(14, 'Merevisi Dokumen Akhir', 'Aksi Pengguna', 'System', '2023-09-26 17:23:47', 'System', '2023-09-26 17:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tipe_pengajuan`
--

CREATE TABLE `tbl_tipe_pengajuan` (
  `id` int(11) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `terakhir_diubah_oleh` varchar(20) DEFAULT NULL,
  `terakhir_diubah_pada` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_tipe_pengajuan`
--

INSERT INTO `tbl_tipe_pengajuan` (`id`, `tipe`, `deskripsi`, `dibuat_oleh`, `dibuat_pada`, `terakhir_diubah_oleh`, `terakhir_diubah_pada`) VALUES
(1, 'Laporan Kerja Praktek', 'Tugas akhir untuk semester 6 atau D3', 'System', '2023-09-25 14:59:31', 'System', '2023-09-25 14:59:31'),
(2, 'Skripsi', 'Tugas akhir untuk semester 8 atau S1', 'System', '2023-09-25 14:59:31', 'System', '2023-09-25 14:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unduhan_dokumen_akhir`
--

CREATE TABLE `tbl_unduhan_dokumen_akhir` (
  `id` int(11) NOT NULL,
  `dokumen_akhir_id` int(11) NOT NULL,
  `dibuat_oleh` varchar(20) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_unduhan_dokumen_akhir`
--

INSERT INTO `tbl_unduhan_dokumen_akhir` (`id`, `dokumen_akhir_id`, `dibuat_oleh`, `dibuat_pada`) VALUES
(1, 6, '7201190013', '2024-03-05 01:44:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  ADD PRIMARY KEY (`kode`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`);

--
-- Indexes for table `tbl_dokumen_akhir`
--
ALTER TABLE `tbl_dokumen_akhir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_proses_pengajuan`
--
ALTER TABLE `tbl_proses_pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_status_pengajuan`
--
ALTER TABLE `tbl_status_pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tipe_pengajuan`
--
ALTER TABLE `tbl_tipe_pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_unduhan_dokumen_akhir`
--
ALTER TABLE `tbl_unduhan_dokumen_akhir`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_dokumen_akhir`
--
ALTER TABLE `tbl_dokumen_akhir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_proses_pengajuan`
--
ALTER TABLE `tbl_proses_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `tbl_status_pengajuan`
--
ALTER TABLE `tbl_status_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_tipe_pengajuan`
--
ALTER TABLE `tbl_tipe_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_unduhan_dokumen_akhir`
--
ALTER TABLE `tbl_unduhan_dokumen_akhir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

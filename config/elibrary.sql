-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2023 at 08:46 AM
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
('14115717', 4, 'Super Admin', '', 'admin@demo.com', '$2y$10$2jVohspZPBW.w89tit7usOpbHV2QQpQ3BLNKq5WmdrEO0EeVBvkbq', NULL, NULL, NULL, '14115717', '2023-09-22 17:00:00', NULL, '2023-09-24 14:10:34'),
('7149175839', 1, 'Firman Hermawan', '088812654321', 'firman.h88@yahoo.com', '$2y$10$/.zeEhGHj0Tm6WgC3IJGJ.WoK8RsooSN58/PeRZXNKkyEwWLJ8zTa', 'Sistem Komputer', 6, '2021-01-01', '7249175839', '2023-09-23 15:29:26', NULL, '2023-09-24 14:10:34'),
('7201190013', 1, 'M Fardian Nopandi', '0812863755512', 'm.fardiannopandi@gmail.com', '$2y$10$F5YewqceLYVnW0pxD9LJsOBMyqEf87Hqlfw76RpUxVEmO.KcAhC76', 'Sistem Informasi', 8, '2019-03-01', '7201190013', '2023-09-23 15:08:30', NULL, '2023-09-24 14:10:34'),
('7223997651', 1, 'Adhitya Rachmah', '', '', '$2y$10$BMCNeGuil0IZ2cUycZ5lIefTe/4wco6JPc7rM.ZzMXezTPiZHTjrm', 'Sistem Informasi', 3, '2022-06-01', '7223997651', '2023-09-28 13:42:32', '7223997651', '2023-09-28 13:42:32'),
('998', 3, 'Henry Purwa, S.Kom, M.M.S.I', NULL, '', '$2y$10$/.zeEhGHj0Tm6WgC3IJGJ.WoK8RsooSN58/PeRZXNKkyEwWLJ8zTa', 'Sistem Komputer', NULL, '2014-09-04', 'System', '2023-09-25 18:35:50', 'System', '2023-09-25 18:35:50'),
('999', 2, 'Fauziyah, S.Kom, M.M.S.I', NULL, '', '$2y$10$SHmSgsogP.CoHtll5Yi14.5ts7XezVcF1HgMQGdAcpQjNhqBw.jfm', 'Sistem Informasi', NULL, NULL, '14115717', '2023-09-29 15:32:05', '14115717', '2023-09-29 15:32:05');

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
(1, 1, 'LPK-Final-7149175839.pdf', '7149175839', '2023-09-29 15:56:31', '7149175839', '2023-09-29 15:56:31'),
(2, 2, 'LPK-Final-7201190013.pdf', '7201190013', '2023-09-30 04:53:32', '7201190013', '2023-09-30 04:53:32');

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
(1, 1, 6, 'Laporan Kerja Praktek Proposal Revisi', 'Gentur Maulana', 'Proposal-LKP-7149175839.docx', 'Surat-Validasi-LPK-7149175839.docx', '7149175839', '2023-09-28 14:22:55', '998', '2023-09-28 14:22:55'),
(2, 1, 6, 'LPK Proposal SI Test Revisi', 'Fauziyah', 'Proposal-LKP-7201190013.docx', 'Surat-Validasi-LPK-7201190013.docx', '7201190013', '2023-09-29 16:01:50', '999', '2023-09-29 16:01:50'),
(3, 2, 3, 'Skripsi Proposal', 'Julian Valentino', 'Proposal-Skripsi-7201190013.docx', NULL, '7201190013', '2023-09-30 04:58:08', '7201190013', '2023-09-30 04:58:08');

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
(1, 1, 2, NULL, NULL, 1, '7149175839', '2023-09-28 14:22:55'),
(2, 1, 3, NULL, NULL, 0, '7149175839', '2023-09-28 14:22:55'),
(3, 1, 5, 'Coba ganti', NULL, 1, '998', '2023-09-28 16:03:55'),
(4, 1, 7, NULL, NULL, 0, '998', '2023-09-28 16:03:55'),
(5, 1, 8, NULL, NULL, 1, '7149175839', '2023-09-29 15:51:59'),
(6, 1, 3, NULL, NULL, 0, '7149175839', '2023-09-29 15:51:59'),
(7, 1, 4, 'Oke', NULL, 1, '998', '2023-09-29 15:54:23'),
(8, 1, 9, NULL, NULL, 0, '998', '2023-09-29 15:54:23'),
(9, 1, 10, NULL, NULL, 1, '7149175839', '2023-09-29 15:56:31'),
(10, 1, 3, NULL, NULL, 0, '7149175839', '2023-09-29 15:56:31'),
(11, 1, 11, '', NULL, 1, '998', '2023-09-29 15:56:54'),
(12, 1, 6, NULL, NULL, 0, '998', '2023-09-29 15:56:54'),
(13, 2, 2, NULL, NULL, 1, '7201190013', '2023-09-29 16:01:50'),
(14, 2, 3, NULL, NULL, 0, '7201190013', '2023-09-29 16:01:50'),
(15, 2, 5, 'Test revisi', NULL, 1, '999', '2023-09-30 04:07:52'),
(16, 2, 7, NULL, NULL, 0, '999', '2023-09-30 04:07:52'),
(17, 2, 8, NULL, NULL, 1, '7201190013', '2023-09-30 04:35:49'),
(18, 2, 3, NULL, NULL, 0, '7201190013', '2023-09-30 04:35:49'),
(19, 2, 5, 'sdfsdf', 'Dokumen Revisi-1696049497-999.docx', 1, '999', '2023-09-30 04:51:37'),
(20, 2, 7, NULL, NULL, 0, '999', '2023-09-30 04:51:37'),
(21, 2, 8, NULL, NULL, 1, '7201190013', '2023-09-30 04:52:10'),
(22, 2, 3, NULL, NULL, 0, '7201190013', '2023-09-30 04:52:10'),
(23, 2, 4, '', NULL, 1, '999', '2023-09-30 04:52:43'),
(24, 2, 9, NULL, NULL, 0, '999', '2023-09-30 04:52:43'),
(25, 2, 10, NULL, NULL, 1, '7201190013', '2023-09-30 04:53:32'),
(26, 2, 3, NULL, NULL, 0, '7201190013', '2023-09-30 04:53:32'),
(27, 2, 11, '', NULL, 1, '999', '2023-09-30 04:53:55'),
(28, 2, 6, NULL, NULL, 0, '999', '2023-09-30 04:53:55'),
(29, 3, 2, NULL, NULL, 1, '7201190013', '2023-09-30 04:58:08'),
(30, 3, 3, NULL, NULL, 0, '7201190013', '2023-09-30 04:58:08');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_dokumen_akhir`
--
ALTER TABLE `tbl_dokumen_akhir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_proses_pengajuan`
--
ALTER TABLE `tbl_proses_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

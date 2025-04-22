-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Des 2024 pada 10.48
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_v5.2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absen`
--

CREATE TABLE `absen` (
  `absen_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `jam_kerja_id` int(11) NOT NULL,
  `jam_kerja_in` time NOT NULL,
  `jam_kerja_toleransi` time NOT NULL,
  `jam_kerja_out` time NOT NULL,
  `absen_in` time NOT NULL,
  `absen_out` time NOT NULL,
  `foto_in` varchar(150) NOT NULL,
  `foto_out` varchar(150) NOT NULL,
  `status_masuk` varchar(30) NOT NULL,
  `status_pulang` varchar(30) NOT NULL,
  `kehadiran` varchar(20) NOT NULL,
  `latitude_longtitude_in` varchar(150) NOT NULL,
  `latitude_longtitude_out` varchar(150) NOT NULL,
  `radius` varchar(10) NOT NULL,
  `tipe` varchar(10) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `absen`
--

INSERT INTO `absen` (`absen_id`, `user_id`, `tanggal`, `lokasi_id`, `jam_kerja_id`, `jam_kerja_in`, `jam_kerja_toleransi`, `jam_kerja_out`, `absen_in`, `absen_out`, `foto_in`, `foto_out`, `status_masuk`, `status_pulang`, `kehadiran`, `latitude_longtitude_in`, `latitude_longtitude_out`, `radius`, `tipe`, `keterangan`) VALUES
(70, 4, '2024-12-11', 2, 1, '07:30:00', '08:00:00', '16:15:00', '16:40:32', '16:44:59', '', '', 'Telat', '', 'Hadir', '', '', 'Y', 'qrcode', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(5) NOT NULL,
  `fullname` varchar(40) NOT NULL,
  `username` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `avatar` varchar(150) NOT NULL,
  `registrasi_date` date NOT NULL,
  `tanggal_login` datetime NOT NULL,
  `time` datetime NOT NULL,
  `status` varchar(10) NOT NULL,
  `level` int(5) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `browser` varchar(40) NOT NULL,
  `active` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`admin_id`, `fullname`, `username`, `phone`, `email`, `password`, `avatar`, `registrasi_date`, `tanggal_login`, `time`, `status`, `level`, `ip`, `browser`, `active`) VALUES
(1, 'Coki Widodo', 'Widodo', '089666665781', 'swidodo.com@gmail.com', '$2y$10$iUZpF3UFbPjH4U/zErn5I.mixtbptmapoRYp6tIi69MqTVqaNirRy', 'avatar-Widodo-1707116784.jpg', '2022-03-22', '2024-12-11 16:47:48', '2024-12-11 16:47:48', 'Online', 1, '1', 'Google Crome', 'Y'),
(6, 'Intan Permata sari', 'Intan', '089666665781', 'intanpermatasari@gmail.com', '$2y$10$lIKR1cqN8kNusBU45zqvAuINgD.g9X3/2rDBC6qvjT4oejy1jP53S', 'avatar.jpg', '2022-12-01', '2022-12-03 10:22:26', '2024-02-05 13:38:56', 'Offline', 3, '::1', 'Google Chrome 107.0.0.0', 'Y'),
(7, 'Demo admin', 'admin', '083160901108', 'swidodo.com2@gmail.com', '$2y$10$LcyeyTD.fQysDt22FlhsBO0sYjUK/auDu2OeQSVPImwArp/t0Q5Z.', 'avatar.jpg', '2024-06-20', '2024-06-20 09:18:43', '2024-06-20 09:18:43', 'Offline', 1, '::1', 'Google Chrome 126.0.0.0', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `artikel`
--

CREATE TABLE `artikel` (
  `artikel_id` int(11) NOT NULL,
  `penerbit` varchar(50) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `domain` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(150) NOT NULL,
  `kategori` varchar(40) NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL,
  `statistik` varchar(10) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `artikel`
--

INSERT INTO `artikel` (`artikel_id`, `penerbit`, `judul`, `domain`, `deskripsi`, `foto`, `kategori`, `time`, `date`, `statistik`, `active`) VALUES
(2, 'Widodo', 'INformasi', 'informasi', '&lt;p&gt;&lt;img src=&quot;/absensi-v5/sw-content/artikel/image/Kalung_Cimoy.jpg&quot; alt=&quot;&quot; /&gt;&lt;/p&gt;\r\n&lt;p&gt;Aplikasi Absensi ini di bangun menggunakan framework bootstrap dan PHP Prosedural/Naive MYSQLi support PHP V.7.6, Mudah di akses di hp maupun laptop.&lt;br /&gt;&lt;br /&gt;Perbedaan Sama versi yang sebelumnya yaitu di cordinat radius, Jadi Versi ini memiliki Radius artinya Pegawai hanya bisa Absen didalam radius yg sudah ditentukan oleh Admin.&lt;br /&gt;&lt;br /&gt;Aplikasi ini merecord absensi menggunakan QR CODE yang bisa di download oleh pengguna beserta ID CARDnya. sudah support di HP maupun di buat Web view/Android View karena tampilanya sudah mobile.&lt;br /&gt;.&lt;br /&gt;Penambahan Fitur Ada pada bagian Laporan, ID Card, Qr Code dan Cordinat Lokasi&lt;br /&gt;&lt;br /&gt;*SEMUA PRODUK FULL SOURCE CODE YA BISA LANGSUNG DIGUNAKAN&lt;br /&gt;.&lt;br /&gt;&lt;br /&gt;Fitur :&lt;br /&gt;+ Tampilan Home ditambah fitur Counter per bulan&lt;br /&gt;+ Tampilan Member/Karyawan sudah Mobile&lt;br /&gt;+ Mudah di Operasikan&lt;br /&gt;+ Absen Menggunakan QR Codedari Webcame dan Kemera Hp&lt;br /&gt;+ Terdapat ID Card yang bisa diubah Temanya dan dapat di download Oleh pengguna/Karyawan&lt;br /&gt;+ Terdapat Geo Location Absen masuk mencatat latitude google&lt;br /&gt;+ Responsive&lt;br /&gt;+ Memiliki Fitur Login Google&lt;br /&gt;+ Terdapat Fitur Laporan PDF dan Excel berdasarkan Tanggal&lt;br /&gt;+ Laporan Details terdapat Durasi kerja, pulang cepat dan terlambat&lt;br /&gt;+ Laporan Semua Karyawan/Pegawai Selama per bulan&lt;br /&gt;+ Terpadat Permohonan Cuti&lt;br /&gt;+ Tampilan Lebih Bagus dibanding dengan ygng V.3&lt;br /&gt;+ Support PHP Versi 7.4&lt;br /&gt;+ Memiliki Cordinat Lokasi&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;* Sudah ada Panduan Konfigurasi Ke hosting atau localhost&lt;br /&gt;* Sudah ada Panduan Konfigurasi Login dengan Google&lt;br /&gt;* Sudah ada Panduan jika Hp tidak mau akses Lokasi&lt;/p&gt;', 'kalung-cimoyjpg.jpg', 'pengumuman', '16:53:10', '2022-11-29', '12', 'Y'),
(3, 'Widodo', 'Aplikasi Absensi Mengunakan QR Code', 'aplikasi-absensi-mengunakan-qr-code', '<p>Aplikasi Absensi ini di bangun menggunakan framework bootstrap dan PHP Prosedural/Naive MYSQLi support PHP V.7.6, Mudah di akses di hp maupun laptop.</p>\r\n<p><img src=\"/absensi-v5/sw-content/artikel/image/Kalung_Cimoy.jpg\" alt=\"\" /><br /><br />Perbedaan Sama versi yang sebelumnya yaitu di cordinat radius, Jadi Versi ini memiliki Radius artinya Pegawai hanya bisa Absen didalam radius yg sudah ditentukan oleh Admin.<br /><br />Aplikasi ini merecord absensi menggunakan QR CODE yang bisa di download oleh pengguna beserta ID CARDnya. sudah support di HP maupun di buat Web view/Android View karena tampilanya sudah mobile.<br />.<br />Penambahan Fitur Ada pada bagian Laporan, ID Card, Qr Code dan Cordinat Lokasi<br /><br />*SEMUA PRODUK FULL SOURCE CODE YA BISA LANGSUNG DIGUNAKAN<br />.<br /><br />Fitur :<br />+ Tampilan Home ditambah fitur Counter per bulan<br />+ Tampilan Member/Karyawan sudah Mobile<br />+ Mudah di Operasikan<br />+ Absen Menggunakan QR Codedari Webcame dan Kemera Hp<br />+ Terdapat ID Card yang bisa diubah Temanya dan dapat di download Oleh pengguna/Karyawan<br />+ Terdapat Geo Location Absen masuk mencatat latitude google<br />+ Responsive<br />+ Memiliki Fitur Login Google<br />+ Terdapat Fitur Laporan PDF dan Excel berdasarkan Tanggal<br />+ Laporan Details terdapat Durasi kerja, pulang cepat dan terlambat<br />+ Laporan Semua Karyawan/Pegawai Selama per bulan<br />+ Terpadat Permohonan Cuti<br />+ Tampilan Lebih Bagus dibanding dengan ygng V.3<br />+ Support PHP Versi 7.4<br />+ Memiliki Cordinat Lokasi<br /><br /><br />* Sudah ada Panduan Konfigurasi Ke hosting atau localhost<br />* Sudah ada Panduan Konfigurasi Login dengan Google<br />* Sudah ada Panduan jika Hp tidak mau akses Lokasi<br />.<br />===============DEMO===================<br /><br />- Url : https://absensiv4-radius.sman1waylima.sch.id<br /><br />User : swidodo.com@gmail.com<br />Password : 123456</p>', 'kalung-cimoyjpg.jpg', 'pengumuman', '10:41:55', '2022-11-30', '53', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `datetime` datetime NOT NULL,
  `status` varchar(10) NOT NULL,
  `status_user` varchar(5) NOT NULL,
  `status_parent` varchar(5) NOT NULL,
  `status_admin` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `chat`
--

INSERT INTO `chat` (`chat_id`, `user_id`, `parent_user_id`, `admin_id`, `pesan`, `datetime`, `status`, `status_user`, `status_parent`, `status_admin`) VALUES
(25, 4, 0, 1, 'tes', '2024-02-05 15:00:55', 'user', 'Y', '-', '-'),
(26, 4, 0, 1, '😃🤣🤠', '2024-02-05 15:05:05', 'user', 'Y', '-', '-'),
(27, 4, 0, 1, 'Kerja kerja kerja', '2024-02-05 15:06:11', 'user', 'Y', '-', '-'),
(28, 4, 0, 1, 'tes', '2024-02-05 15:06:40', 'user', 'Y', '-', '-'),
(29, 4, 0, 1, '😌', '2024-02-05 15:09:18', 'user', 'Y', '-', '-'),
(30, 4, 0, 1, 'sfsfsf', '2024-02-05 15:43:23', 'user', 'Y', '-', '-'),
(32, 4, 0, 1, 'Balas', '2024-02-05 18:16:22', 'admin', '-', 'Y', 'Y'),
(33, 4, 0, 1, 'iya', '2024-02-05 19:36:08', 'user', 'Y', '-', '-'),
(34, 4, 0, 1, 'oke', '2024-02-05 19:36:21', 'user', 'Y', '-', '-'),
(35, 4, 0, 1, 'Tes', '2024-02-05 19:43:16', 'user', 'Y', '-', '-'),
(36, 4, 0, 1, 'Tes lagi', '2024-02-05 22:30:30', 'admin', '-', '-', 'Y'),
(37, 4, 0, 1, 'sdsd', '2024-02-06 00:57:53', 'admin', '-', '-', 'Y'),
(38, 4, 0, 1, 'Aku sayang kamu', '2024-02-06 03:51:04', 'user', 'Y', '-', '-'),
(39, 4, 0, 1, 'Halo kak', '2024-02-08 00:35:12', 'user', 'Y', '-', '-'),
(40, 4, 0, 1, 'iya', '2024-02-08 00:35:42', 'admin', '-', '-', 'Y'),
(41, 4, 0, 1, 'ada yg bisa di bantu', '2024-02-08 00:35:49', 'admin', '-', '-', 'Y'),
(42, 4, 0, 1, '😅', '2024-02-08 00:36:47', 'user', 'Y', '-', '-'),
(43, 4, 0, 1, 'ssdsd', '2024-02-08 01:09:46', 'user', 'Y', '-', '-'),
(44, 4, 0, 1, '😃', '2024-02-08 01:10:06', 'user', 'Y', '-', '-'),
(45, 4, 0, 1, '🤣', '2024-02-08 01:15:29', 'admin', '-', '-', 'Y'),
(46, 4, 0, 1, 'Udah malam', '2024-02-17 03:07:15', 'admin', '-', '-', 'Y'),
(47, 4, 0, 1, 'hallo', '2024-02-18 00:15:35', 'admin', '-', '-', 'Y'),
(48, 4, 0, 6, 'p', '2024-08-12 12:06:55', 'user', 'N', '-', '-'),
(49, 4, 0, 1, 'hallo', '2024-08-12 12:07:29', 'user', 'Y', '-', '-'),
(50, 4, 0, 1, 'oke', '2024-08-12 12:07:46', 'admin', '-', '-', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_list`
--

CREATE TABLE `chat_list` (
  `chat_list_id` int(11) NOT NULL,
  `user_id` int(5) NOT NULL,
  `parent_user_id` int(5) NOT NULL,
  `admin_id` int(5) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `chat_list`
--

INSERT INTO `chat_list` (`chat_list_id`, `user_id`, `parent_user_id`, `admin_id`, `datetime`) VALUES
(8, 4, 0, 1, '2024-02-18 00:15:00'),
(10, 5, 0, 1, '2024-02-05 17:27:07'),
(11, 4, 0, 0, '2024-02-08 00:49:31'),
(12, 4, 0, 6, '2024-02-18 02:20:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cuti`
--

CREATE TABLE `cuti` (
  `cuti_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `jenis` varchar(40) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jumlah` varchar(10) NOT NULL,
  `keterangan` text NOT NULL,
  `atasan` int(5) NOT NULL,
  `files` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `cuti`
--

INSERT INTO `cuti` (`cuti_id`, `user_id`, `nama_lengkap`, `jenis`, `tanggal_mulai`, `tanggal_selesai`, `jumlah`, `keterangan`, `atasan`, `files`, `date`, `time`, `status`) VALUES
(16, 4, 'Widodo', 'Liburan', '2024-07-29', '2024-07-31', '3', 'liburan ini', 6, '', '2024-07-27', '14:48:27', 'Y'),
(17, 4, 'Widodo', 'Liburan', '2024-08-02', '2024-08-02', '1', 'Liburan keluar kota', 6, '1722586749-widodo.png', '2024-08-02', '15:40:14', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hak_cuti`
--

CREATE TABLE `hak_cuti` (
  `hak_cuti_id` int(11) NOT NULL,
  `posisi_id` int(11) NOT NULL,
  `jumlah` varchar(10) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `hak_cuti`
--

INSERT INTO `hak_cuti` (`hak_cuti_id`, `posisi_id`, `jumlah`, `active`) VALUES
(1, 1, '10', 'Y'),
(2, 4, '10', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `izin`
--

CREATE TABLE `izin` (
  `izin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `files` varchar(150) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `keterangan` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `izin`
--

INSERT INTO `izin` (`izin_id`, `user_id`, `nama_lengkap`, `tanggal_mulai`, `tanggal_selesai`, `files`, `jenis`, `keterangan`, `date`, `time`, `status`) VALUES
(12, 4, 'Widodo', '2024-02-03', '2024-02-04', '1706900480-widodo.jpg', 'Izin', 'sssssssss', '2024-02-03', '02:01:20', 'N'),
(13, 4, 'Widodo', '2024-02-05', '2024-02-06', '1707108975-widodo.jpg', 'Izin', 'Izin Keluar kota', '2024-02-05', '11:56:15', 'Y'),
(14, 4, 'Widodo', '2024-07-25', '2024-07-26', '1721896218-widodo.jpeg', 'Sakit', 'ddgdg dgdg', '2024-07-25', '15:30:15', '-'),
(15, 4, 'Widodo', '2024-08-01', '2024-08-02', '1722487448-widodo.jpg', 'Sakit', 'Sakit keterangan', '2024-08-01', '11:44:08', 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam_kerja`
--

CREATE TABLE `jam_kerja` (
  `jam_kerja_id` int(11) NOT NULL,
  `jam_kerja_master_id` int(8) NOT NULL,
  `hari` varchar(25) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_telat` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jam_kerja`
--

INSERT INTO `jam_kerja` (`jam_kerja_id`, `jam_kerja_master_id`, `hari`, `jam_masuk`, `jam_telat`, `jam_pulang`, `active`) VALUES
(111, 1, 'Minggu', '07:30:00', '08:00:00', '16:15:00', 'Y'),
(112, 1, 'Senin', '07:30:00', '08:00:00', '16:15:00', 'Y'),
(113, 1, 'Selasa', '07:30:00', '08:00:00', '16:15:00', 'Y'),
(114, 1, 'Rabu', '07:30:00', '08:00:00', '16:15:00', 'Y'),
(115, 1, 'Kamis', '07:30:00', '08:00:00', '16:15:00', 'Y'),
(116, 1, 'Jumat', '07:30:00', '08:00:00', '16:15:00', 'Y'),
(117, 1, 'Sabtu', '07:30:00', '08:00:00', '16:15:00', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam_kerja_master`
--

CREATE TABLE `jam_kerja_master` (
  `jam_kerja_master_id` int(8) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jam_kerja_master`
--

INSERT INTO `jam_kerja_master` (`jam_kerja_master_id`, `user_id`, `nama`) VALUES
(1, 0, 'Umum Senin - Sabtu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kartu_nama`
--

CREATE TABLE `kartu_nama` (
  `kartu_nama_id` int(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `foto` varchar(60) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kartu_nama`
--

INSERT INTO `kartu_nama` (`kartu_nama_id`, `nama`, `foto`, `active`) VALUES
(1, 'Tema ID Card Blue', 'slider-2024-02-02-1706851667.jpg', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(8) NOT NULL,
  `title` varchar(50) NOT NULL,
  `seotitle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `title`, `seotitle`) VALUES
(1, 'Pengumuman', 'pengumuman'),
(15, 'Berita', 'berita');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kunjungan`
--

CREATE TABLE `kunjungan` (
  `kunjungan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `lokasi` varchar(70) NOT NULL,
  `keterangan` text NOT NULL,
  `foto` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kunjungan`
--

INSERT INTO `kunjungan` (`kunjungan_id`, `user_id`, `lokasi_id`, `lokasi`, `keterangan`, `foto`, `date`, `time`, `status`) VALUES
(9, 4, 2, 'Kunjungan A', 'Keterangan', 'widodo1727756833.jpg', '2024-10-01', '11:27:12', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lain_lain`
--

CREATE TABLE `lain_lain` (
  `lain_lain_id` int(5) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `tipe` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lain_lain`
--

INSERT INTO `lain_lain` (`lain_lain_id`, `nama`, `tipe`) VALUES
(1, 'Asia/Jakarta', 'timezone'),
(2, 'Asia/Makassar', 'timezone'),
(3, 'Asia/Jayapura', 'timezone'),
(4, 'Izin', 'izin'),
(5, 'Sakit', 'izin'),
(6, 'Dinas Dalam Kota', 'izin'),
(7, 'Dinas Keluar Kota', 'izin'),
(8, 'Sakit', 'cuti'),
(9, 'Liburan', 'cuti'),
(10, 'Menikah', 'cuti'),
(11, 'Melahirkan', 'cuti');

-- --------------------------------------------------------

--
-- Struktur dari tabel `level`
--

CREATE TABLE `level` (
  `level_id` int(5) NOT NULL,
  `level_nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `level`
--

INSERT INTO `level` (`level_id`, `level_nama`) VALUES
(1, 'Superadmin'),
(2, 'User'),
(3, 'Atasan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `libur`
--

CREATE TABLE `libur` (
  `libur_id` int(5) NOT NULL,
  `libur_hari` varchar(20) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `libur`
--

INSERT INTO `libur` (`libur_id`, `libur_hari`, `active`) VALUES
(1, 'Sabtu', 'N'),
(2, 'Minggu', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `libur_nasional`
--

CREATE TABLE `libur_nasional` (
  `libur_nasional_id` int(11) NOT NULL,
  `libur_tanggal` date NOT NULL,
  `keterangan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `libur_nasional`
--

INSERT INTO `libur_nasional` (`libur_nasional_id`, `libur_tanggal`, `keterangan`) VALUES
(94, '2024-12-25', 'Hari Raya Natal'),
(95, '2024-10-05', 'Hari Raya Kuningan'),
(96, '2024-09-26', 'Umanis Galungan'),
(97, '2024-09-25', 'Hari Raya Galungan'),
(98, '2024-09-24', 'Penampahan Galungan'),
(99, '2024-09-15', 'Maulid Nabi Muhammad SAW'),
(100, '2024-08-17', 'Hari Proklamasi Kemerdekaan RI'),
(101, '2024-07-13', 'Hari Saraswati'),
(102, '2024-07-07', 'Tahun Baru Islam 1446 Hijriyah'),
(103, '2024-06-17', 'Hari Raya Idul Adha 1445 Hijriyah'),
(104, '2024-06-01', 'Hari Lahirnya Pancasila'),
(105, '2024-05-23', 'Hari Raya Waisak 2568'),
(106, '2024-05-09', 'Kenaikan Isa Al Masih'),
(107, '2024-05-01', 'Hari Buruh Internasional'),
(108, '2024-04-11', 'Hari Raya Idul Fitri 1445 Hijriyah'),
(109, '2024-04-10', 'Hari Raya Idul Fitri 1445 Hijriyah'),
(110, '2024-03-29', 'Wafat Isa Al Masih'),
(111, '2024-03-11', 'Hari Raya Nyepi'),
(112, '2024-03-09', 'Hari Raya Kuningan'),
(113, '2024-02-29', 'Umanis Galungan'),
(114, '2024-02-28', 'Hari Raya Galungan'),
(115, '2024-02-27', 'Penampahan Galungan'),
(116, '2024-02-10', 'Tahun Baru Imlek 2575 Kongzili'),
(117, '2024-02-08', 'Isra Mikraj Nabi Muhammad SAW'),
(118, '2024-01-09', 'Hari Siwa Ratri'),
(119, '2024-01-01', 'Tahun Baru Masehi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi`
--

CREATE TABLE `lokasi` (
  `lokasi_id` int(8) NOT NULL,
  `lokasi_nama` varchar(30) NOT NULL,
  `lokasi_alamat` text NOT NULL,
  `lokasi_latitude` varchar(100) NOT NULL,
  `lokasi_longitude` varchar(100) NOT NULL,
  `lokasi_radius` varchar(20) NOT NULL,
  `lokasi_qrcode` varchar(100) NOT NULL,
  `lokasi_tanggal` date NOT NULL,
  `lokasi_jam_mulai` time NOT NULL,
  `lokasi_jam_selesai` time NOT NULL,
  `lokasi_status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lokasi`
--

INSERT INTO `lokasi` (`lokasi_id`, `lokasi_nama`, `lokasi_alamat`, `lokasi_latitude`, `lokasi_longitude`, `lokasi_radius`, `lokasi_qrcode`, `lokasi_tanggal`, `lokasi_jam_mulai`, `lokasi_jam_selesai`, `lokasi_status`) VALUES
(2, 'Optik Modern', 'Jl. Rizai kedaton bandar lampung', '-5.410945277946005', '105.26103565480452', '900', '1722174785', '2022-11-18', '16:48:53', '16:48:53', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi_user`
--

CREATE TABLE `lokasi_user` (
  `lokasi_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lokasi_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lokasi_user`
--

INSERT INTO `lokasi_user` (`lokasi_user_id`, `user_id`, `lokasi_id`) VALUES
(1, 7, 2),
(2, 4, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `modul_id` int(11) NOT NULL,
  `modul_nama` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`modul_id`, `modul_nama`) VALUES
(1, 'Artikel'),
(2, 'Pegawai'),
(3, 'Lokasi'),
(4, 'Jam Kerja'),
(5, 'Posisi/Jabatan'),
(6, 'Libur Kantor'),
(7, 'ID Card'),
(8, 'Izin'),
(9, 'Cuti'),
(10, 'Semua Laporan'),
(11, 'Pengaturan Aplikasi'),
(12, 'Slider'),
(13, 'Admin'),
(14, 'Hak Akses'),
(15, 'Hak Cuti'),
(16, 'Pesan Absen WhastApp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `notifikasi_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `link` varchar(20) NOT NULL,
  `tanggal` varchar(40) NOT NULL,
  `datetime` datetime NOT NULL,
  `tipe` varchar(5) NOT NULL,
  `status` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`notifikasi_id`, `user_id`, `nama`, `keterangan`, `link`, `tanggal`, `datetime`, `tipe`, `status`) VALUES
(1, 4, 'Widodo', 'Baru saja megajukan cuti', 'cuti', '2023-10-02', '2023-10-02 08:00:00', '1', 'Y'),
(2, 4, 'Widodo', 'Baru saja megajukan izin', 'cuti', '2023-10-02', '0000-00-00 00:00:00', '1', 'Y'),
(3, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2023-10-05', '2023-10-02 23:21:01', '1', 'Y'),
(4, 4, 'Widodo', 'Baru saja megajukan izin', 'izin', '2023-10-14', '2023-10-02 23:24:11', '1', 'Y'),
(5, 4, 'Widodo', 'Baru saja megajukan izin', 'izin', '2024-01-28', '2024-01-28 02:10:45', '1', 'Y'),
(6, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2024-01-31', '2024-02-01 00:12:49', '1', 'Y'),
(7, 4, 'Widodo', 'Baru saja megajukan izin', 'izin', '2024-02-03', '2024-02-03 01:26:13', '1', 'Y'),
(8, 4, 'Widodo', 'Baru saja megajukan izin', 'izin', '2024-02-05', '2024-02-05 11:15:00', '1', 'Y'),
(9, 4, 'Widodo', 'Permohonan Izin Anda disetujui', 'izin', '2024-02-05', '2024-02-05 12:21:44', '2', 'Y'),
(10, 4, 'Widodo', 'Permohonan Izin Anda ditolak', 'izin', '2024-02-05', '2024-02-05 12:41:06', '2', 'Y'),
(11, 4, 'Widodo', 'Permohonan Izin Anda disetujui', 'izin', '2024-02-06', '2024-02-06 00:37:57', '2', 'Y'),
(12, 4, 'Widodo', 'Baru saja menambah Uraian kerja', 'uraian-kerja', '2024-06-20', '2024-06-20 09:21:05', '1', 'Y'),
(13, 4, 'Widodo', 'Baru saja menambah Uraian kerja', 'uraian-kerja', '2024-06-20', '2024-06-20 09:23:49', '1', 'Y'),
(14, 0, '', 'Baru saja menambah Uraian kerja', 'uraian-kerja', '2024-06-20', '2024-06-20 09:38:55', '1', 'Y'),
(15, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2024-07-03', '2024-07-03 08:47:02', '1', 'Y'),
(16, 0, 'Widodo', 'Permohonan Cuti Anda disetujui', 'cuti', '2024-07-03', '2024-07-03 08:50:41', '2', 'Y'),
(24, 4, 'Widodo', 'Baru saja megajukan izin', 'izin', '2024-07-25', '2024-07-25 15:30:15', '1', 'Y'),
(25, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '1970-01-01', '2024-07-27 14:24:03', '1', 'Y'),
(26, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2024-07-29', '2024-07-27 14:31:48', '1', 'Y'),
(27, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2024-08-01', '2024-07-27 14:33:27', '1', 'Y'),
(28, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2024-07-29', '2024-07-27 14:48:27', '1', 'Y'),
(29, 0, 'Widodo', 'Permohonan Cuti Anda disetujui', 'cuti', '2024-07-27', '2024-07-27 14:49:15', '2', 'Y'),
(30, 4, 'Widodo', 'Baru saja megajukan izin', 'izin', '2024-08-01', '2024-08-01 11:44:08', '1', 'Y'),
(31, 4, 'Widodo', 'Permohonan Izin Anda ditolak', 'izin', '2024-08-01', '2024-08-01 11:44:30', '2', 'Y'),
(32, 4, 'Widodo', 'Baru saja mengajukan cuti', 'cuti', '2024-08-02', '2024-08-02 14:57:27', '1', 'Y'),
(33, 4, 'Widodo', 'Baru saja kunjungan', 'laporan-kunjungan', '2024-09-06', '2024-09-06 14:45:47', '1', 'Y'),
(34, 4, 'Widodo', 'Baru saja kunjungan', 'laporan-kunjungan', '2024-10-01', '2024-10-01 11:27:12', '1', 'Y'),
(35, 4, 'Widodo', 'Baru saja menambah Uraian kerja', 'uraian-kerja', '2024-10-01', '2024-10-01 12:49:44', '1', 'Y'),
(36, 4, 'Widodo', 'Baru saja menambah Uraian kerja', 'uraian-kerja', '2024-10-15', '2024-10-15 21:32:44', '1', 'Y'),
(37, 4, 'Widodo', 'Baru saja menambah Uraian kerja', 'uraian-kerja', '2024-10-15', '2024-10-15 21:41:08', '1', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `overtime`
--

CREATE TABLE `overtime` (
  `overtime_id` int(11) NOT NULL,
  `overtime` varchar(60) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `tanggal_in` date NOT NULL,
  `tanggal_out` date NOT NULL,
  `absen_in` time NOT NULL,
  `absen_out` time NOT NULL,
  `latitude_in` varchar(150) NOT NULL,
  `latitude_out` varchar(150) NOT NULL,
  `keterangan` text NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `overtime`
--

INSERT INTO `overtime` (`overtime_id`, `overtime`, `user_id`, `lokasi_id`, `tanggal_in`, `tanggal_out`, `absen_in`, `absen_out`, `latitude_in`, `latitude_out`, `keterangan`, `status`) VALUES
(11, '41680684606', 4, 0, '2023-04-05', '0000-00-00', '15:50:06', '00:00:00', '-5.4027272,105.2603055', '', 'dsfgdsgdg', '1'),
(12, '41682757506', 4, 0, '2023-04-29', '0000-00-00', '15:38:26', '00:00:00', '-5.3971396,105.2667887', '', 'dfsdaf', '1'),
(14, '41683085863', 4, 0, '2023-05-03', '2023-05-03', '10:51:03', '11:02:53', '-5.4026226,105.2604174', '-5.4026226,105.2604174', 'Lembur', '2'),
(15, '41683706063', 4, 0, '2023-05-10', '2023-05-10', '15:07:43', '15:37:00', '-5.4027566,105.2601829', '-5.4027566,105.2601829', 'Izin Lembur', '1'),
(16, '41696518905', 4, 0, '2023-10-05', '2023-10-05', '22:15:05', '22:41:38', '-5.3706752,105.2606464', '-5.3706752,105.2606464', 'Lembur', '1'),
(18, '41706813071', 4, 0, '2024-02-02', '2024-02-02', '01:44:31', '02:11:41', '-5.3870592,105.2606464', '-5.3870592,105.2606464', 'Lembur', 'N'),
(19, '41721200747', 4, 0, '2024-07-17', '2024-07-17', '14:19:06', '14:19:16', '-5.3764843,105.2780096', '-5.3641216,105.2606464', 'fsfsf', 'N'),
(20, '41721877489', 4, 2, '2024-07-25', '2024-07-25', '08:18:08', '10:18:16', '-5.3968896,105.2147712', '-5.3968896,105.2147712', 'vcbb', 'Y'),
(22, '41729103980', 4, 0, '2024-10-17', '0000-00-00', '01:39:40', '00:00:00', '-5.4099968,105.2606464', '', 'sfsf dvsdv', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penugasan`
--

CREATE TABLE `penugasan` (
  `penugasan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `keterangan` text NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `posisi`
--

CREATE TABLE `posisi` (
  `posisi_id` int(8) NOT NULL,
  `posisi_nama` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `posisi`
--

INSERT INTO `posisi` (`posisi_id`, `posisi_nama`) VALUES
(1, 'Admin'),
(2, 'Manager'),
(3, 'Marketing'),
(4, 'Acounting');

-- --------------------------------------------------------

--
-- Struktur dari tabel `recognition`
--

CREATE TABLE `recognition` (
  `recognition_id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `photo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `recognition`
--

INSERT INTO `recognition` (`recognition_id`, `user_id`, `photo`) VALUES
(16, '4', 'widodo1708110662.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `level_id` int(5) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `lihat` varchar(5) NOT NULL,
  `modifikasi` varchar(5) NOT NULL,
  `hapus` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`role_id`, `level_id`, `modul_id`, `lihat`, `modifikasi`, `hapus`) VALUES
(1, 1, 1, 'Y', 'Y', 'Y'),
(2, 1, 2, 'Y', 'Y', 'Y'),
(3, 1, 3, 'Y', 'Y', 'Y'),
(4, 1, 4, 'Y', 'Y', 'Y'),
(5, 1, 5, 'Y', 'Y', 'Y'),
(6, 1, 6, 'Y', 'Y', 'Y'),
(7, 1, 7, 'Y', 'Y', 'Y'),
(8, 1, 8, 'Y', 'Y', 'Y'),
(9, 1, 9, 'Y', 'Y', 'Y'),
(10, 1, 10, 'Y', 'Y', 'Y'),
(11, 1, 11, 'Y', 'Y', 'Y'),
(12, 1, 12, 'Y', 'Y', 'Y'),
(13, 1, 13, 'Y', 'Y', 'Y'),
(14, 1, 14, 'Y', 'Y', 'Y'),
(15, 2, 1, 'Y', 'Y', 'Y'),
(16, 2, 2, 'N', 'Y', 'Y'),
(17, 2, 3, 'N', 'Y', 'Y'),
(18, 2, 4, 'N', 'Y', 'Y'),
(19, 2, 5, 'N', 'Y', 'Y'),
(20, 2, 6, 'N', 'Y', 'Y'),
(21, 2, 7, 'N', 'Y', 'Y'),
(22, 2, 8, 'N', 'Y', 'Y'),
(23, 2, 9, 'N', 'Y', 'Y'),
(24, 2, 10, 'N', 'Y', 'Y'),
(25, 2, 11, 'N', 'Y', 'Y'),
(26, 2, 12, 'N', 'Y', 'Y'),
(27, 2, 13, 'N', 'N', 'N'),
(28, 2, 14, 'N', 'N', 'N'),
(29, 1, 15, 'Y', 'Y', 'Y'),
(30, 1, 16, 'Y', 'Y', 'Y'),
(31, 3, 1, 'Y', 'Y', 'Y'),
(32, 3, 2, 'Y', 'Y', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `site_id` int(4) NOT NULL,
  `site_name` varchar(50) NOT NULL,
  `site_phone` char(12) NOT NULL,
  `site_address` text NOT NULL,
  `site_owner` varchar(50) NOT NULL,
  `site_logo` varchar(100) NOT NULL,
  `site_favicon` varchar(60) NOT NULL,
  `site_url` varchar(100) NOT NULL,
  `site_email` varchar(30) NOT NULL,
  `gmail_host` varchar(50) NOT NULL,
  `gmail_username` varchar(30) NOT NULL,
  `gmail_password` varchar(50) NOT NULL,
  `gmail_port` varchar(10) NOT NULL,
  `gmail_active` varchar(5) NOT NULL,
  `google_client_id` varchar(200) NOT NULL,
  `google_client_secret` varchar(200) NOT NULL,
  `google_client_active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `setting`
--

INSERT INTO `setting` (`site_id`, `site_name`, `site_phone`, `site_address`, `site_owner`, `site_logo`, `site_favicon`, `site_url`, `site_email`, `gmail_host`, `gmail_username`, `gmail_password`, `gmail_port`, `gmail_active`, `google_client_id`, `google_client_secret`, `google_client_active`) VALUES
(1, 'App. Absensi V.5', '083160901108', 'Jl. Zainal Bidin Labuhan Ratu gg. Harapan 1 No 18', 'Widodo', 'swlogowebpng.png', 'swfaviconpng.png', 'http://localhost/absensi-v5', 'swidodo.com@gmail.com', 'smtp.gmail.com', 'swidodo.com@gmail.com', 'cqpveixfqexoqfak', '465', 'Y', '482205120603-hf6aqm1mgr29ubsi2qttcrmfhmm2uklb.apps.googleusercontent.com', '7EjMuD8XO88nR-5mtqYhh4Y3', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_absen`
--

CREATE TABLE `setting_absen` (
  `setting_absen_id` int(5) NOT NULL,
  `timezone` varchar(30) NOT NULL,
  `tipe_absen` varchar(20) NOT NULL,
  `radius` varchar(20) NOT NULL,
  `mulai_absen_masuk` time NOT NULL,
  `mulai_absen_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `setting_absen`
--

INSERT INTO `setting_absen` (`setting_absen_id`, `timezone`, `tipe_absen`, `radius`, `mulai_absen_masuk`, `mulai_absen_pulang`) VALUES
(1, 'Asia/Jakarta', 'selfie', 'Y', '06:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `slider`
--

CREATE TABLE `slider` (
  `slider_id` int(5) NOT NULL,
  `slider_nama` varchar(50) NOT NULL,
  `slider_url` varchar(50) NOT NULL,
  `foto` varchar(150) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `slider`
--

INSERT INTO `slider` (`slider_id`, `slider_nama`, `slider_url`, `foto`, `active`) VALUES
(1, 'sdfds', '#', '2022-11-29-1669693034.jpg', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `uraian_kerja`
--

CREATE TABLE `uraian_kerja` (
  `uraian_kerja_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lokasi_id` int(8) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `files` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `uraian_kerja`
--

INSERT INTO `uraian_kerja` (`uraian_kerja_id`, `user_id`, `lokasi_id`, `nama`, `tanggal`, `keterangan`, `files`, `date`, `time`, `status`) VALUES
(11, 4, 2, 'zdfsdf', '2024-10-15', 'sdfsdfsdf', '1729003268-5074ad7414847ab612bdc7fdd79b16fd.jpg', '2024-10-15', '21:41:08', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(8) NOT NULL,
  `email` varchar(70) NOT NULL,
  `password` varchar(200) NOT NULL,
  `nip` varchar(40) NOT NULL,
  `nama_lengkap` varchar(70) NOT NULL,
  `tempat_lahir` varchar(40) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `telp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `posisi_id` int(11) NOT NULL,
  `qrcode` varchar(150) NOT NULL,
  `avatar` varchar(160) NOT NULL,
  `tanggal_registrasi` datetime NOT NULL,
  `tanggal_login` datetime NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(30) NOT NULL,
  `browser` varchar(40) NOT NULL,
  `status` varchar(10) NOT NULL,
  `active` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `nip`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `telp`, `alamat`, `lokasi_id`, `posisi_id`, `qrcode`, `avatar`, `tanggal_registrasi`, `tanggal_login`, `time`, `ip`, `browser`, `status`, `active`) VALUES
(4, 'swidodo.com@gmail.com', '$2y$10$v0gqxtJDUU0h3vhsbzQm3uIVHxpMxP4JfI8jCPdTGmPq6L/s1CRBW', '435438597', 'Widodo', 'Kudus', '1991-07-30', 'Laki-laki', '6283160901108', 'Bandar Lampung', 2, 1, '2022/6258/2022/04', '1724901787-widodo.jpg', '2022-12-03 14:12:46', '2024-09-23 14:02:12', '2024-12-11 16:45:56', '::1', 'Google Chrome 107.0.0.0', 'Online', 'Y'),
(5, 'cokiwidodo@gmail.com', '$2y$10$im7KEBeGgGAUAKMZAZjlXeAJIbcWW0qZCceTy3HJRCdWbMFe93aDC', '423423424', 'Coki Widodo', 'Kudus', '2022-12-22', 'Laki-laki', '089666665781', 'Bandar Lampung', 2, 4, '2022/DA8C/2022/05', 'avatar.jpg', '2022-12-18 13:37:54', '2022-12-18 13:37:54', '2024-02-06 00:23:05', '::1', 'Google Chrome 108.0.0.0', 'Offline', 'Y'),
(8, 'swidodo.com33@gmail.com', '$2y$10$kN/vX0cSluKE3Q9Dzg13QOyNh3BhjTXb5cNS3sCvod4F/5ge7LDYe', '35435435', 'Pegawai', 'Bandar Lampung', '1991-08-07', 'Laki-laki', '083160901108', 'Lampung bandar lampung', 2, 4, '647E9-8', 'avatar.jpg', '2024-06-20 09:20:00', '2024-06-20 09:20:00', '2024-06-20 09:20:00', '::1', 'Google Chrome 126.0.0.0', 'Offline', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_jam_kerja`
--

CREATE TABLE `user_jam_kerja` (
  `user_jam_kerja_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jam_kerja_master_id` int(11) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user_jam_kerja`
--

INSERT INTO `user_jam_kerja` (`user_jam_kerja_id`, `user_id`, `jam_kerja_master_id`, `active`) VALUES
(11, 4, 1, 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `waktu`
--

CREATE TABLE `waktu` (
  `waktu_id` int(5) NOT NULL,
  `hari` varchar(15) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_telat` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `tipe` int(2) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `waktu`
--

INSERT INTO `waktu` (`waktu_id`, `hari`, `jam_masuk`, `jam_telat`, `jam_pulang`, `tipe`, `active`) VALUES
(1, 'Senin', '07:30:00', '08:00:00', '14:00:00', 1, 'Y'),
(2, 'Selasa', '07:30:00', '07:30:00', '07:30:00', 1, 'Y'),
(3, 'Rabu', '07:30:00', '07:30:00', '07:30:00', 1, 'Y'),
(4, 'Kamis', '07:30:00', '07:30:00', '07:30:00', 1, 'Y'),
(5, 'Jumat', '07:30:00', '07:30:00', '07:30:00', 1, 'Y'),
(6, 'Sabtu', '07:30:00', '07:30:00', '07:30:00', 1, 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `whatsapp_api`
--

CREATE TABLE `whatsapp_api` (
  `whatsapp_api_id` int(5) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `token` varchar(200) NOT NULL,
  `domain_server` varchar(150) NOT NULL,
  `whatsapp_tipe` varchar(20) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `whatsapp_api`
--

INSERT INTO `whatsapp_api` (`whatsapp_api_id`, `phone`, `token`, `domain_server`, `whatsapp_tipe`, `active`) VALUES
(1, '6281397885014', 'aH0IYVrIOqfIdMElAawdz4HptCbZqbGneeJwUV92Of1pQ8qpXpuQka8QMos3VX0E', 'https://kudus.wablas.com/api/v2/send-message', 'wablas', 'Y'),
(2, '628813993416', 'a81suncJufTbPrYm8RBqXf75QdE1Qq', 'https://sart.54r.my.id/send-message', 'universal', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `whatsapp_pesan`
--

CREATE TABLE `whatsapp_pesan` (
  `whatsapp_pesan_id` int(2) NOT NULL,
  `pembukaan` varchar(70) NOT NULL,
  `pesan_masuk` text NOT NULL,
  `penutupan` varchar(70) NOT NULL,
  `pesan_pulang` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `whatsapp_pesan`
--

INSERT INTO `whatsapp_pesan` (`whatsapp_pesan_id`, `pembukaan`, `pesan_masuk`, `penutupan`, `pesan_pulang`) VALUES
(1, 'Assalamualaikum warahmatullahi wabarakatuh', 'Terimakasih sudah absen masuk, selamat beraktifitas.', 'Waalaikumsalam warahmatullahi wabarakatuh', 'Terimakasih sudah absen pulang, sampai ketemu besok kembali.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`absen_id`);

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indeks untuk tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`artikel_id`);

--
-- Indeks untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indeks untuk tabel `chat_list`
--
ALTER TABLE `chat_list`
  ADD PRIMARY KEY (`chat_list_id`);

--
-- Indeks untuk tabel `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`cuti_id`);

--
-- Indeks untuk tabel `hak_cuti`
--
ALTER TABLE `hak_cuti`
  ADD PRIMARY KEY (`hak_cuti_id`);

--
-- Indeks untuk tabel `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`izin_id`);

--
-- Indeks untuk tabel `jam_kerja`
--
ALTER TABLE `jam_kerja`
  ADD PRIMARY KEY (`jam_kerja_id`);

--
-- Indeks untuk tabel `jam_kerja_master`
--
ALTER TABLE `jam_kerja_master`
  ADD PRIMARY KEY (`jam_kerja_master_id`);

--
-- Indeks untuk tabel `kartu_nama`
--
ALTER TABLE `kartu_nama`
  ADD PRIMARY KEY (`kartu_nama_id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indeks untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`kunjungan_id`);

--
-- Indeks untuk tabel `lain_lain`
--
ALTER TABLE `lain_lain`
  ADD PRIMARY KEY (`lain_lain_id`);

--
-- Indeks untuk tabel `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indeks untuk tabel `libur`
--
ALTER TABLE `libur`
  ADD PRIMARY KEY (`libur_id`);

--
-- Indeks untuk tabel `libur_nasional`
--
ALTER TABLE `libur_nasional`
  ADD PRIMARY KEY (`libur_nasional_id`);

--
-- Indeks untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`lokasi_id`);

--
-- Indeks untuk tabel `lokasi_user`
--
ALTER TABLE `lokasi_user`
  ADD PRIMARY KEY (`lokasi_user_id`);

--
-- Indeks untuk tabel `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`modul_id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`notifikasi_id`);

--
-- Indeks untuk tabel `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`overtime_id`);

--
-- Indeks untuk tabel `penugasan`
--
ALTER TABLE `penugasan`
  ADD PRIMARY KEY (`penugasan_id`);

--
-- Indeks untuk tabel `posisi`
--
ALTER TABLE `posisi`
  ADD PRIMARY KEY (`posisi_id`);

--
-- Indeks untuk tabel `recognition`
--
ALTER TABLE `recognition`
  ADD PRIMARY KEY (`recognition_id`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indeks untuk tabel `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`site_id`);

--
-- Indeks untuk tabel `setting_absen`
--
ALTER TABLE `setting_absen`
  ADD PRIMARY KEY (`setting_absen_id`);

--
-- Indeks untuk tabel `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`slider_id`);

--
-- Indeks untuk tabel `uraian_kerja`
--
ALTER TABLE `uraian_kerja`
  ADD PRIMARY KEY (`uraian_kerja_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `user_jam_kerja`
--
ALTER TABLE `user_jam_kerja`
  ADD PRIMARY KEY (`user_jam_kerja_id`);

--
-- Indeks untuk tabel `waktu`
--
ALTER TABLE `waktu`
  ADD PRIMARY KEY (`waktu_id`);

--
-- Indeks untuk tabel `whatsapp_api`
--
ALTER TABLE `whatsapp_api`
  ADD PRIMARY KEY (`whatsapp_api_id`);

--
-- Indeks untuk tabel `whatsapp_pesan`
--
ALTER TABLE `whatsapp_pesan`
  ADD PRIMARY KEY (`whatsapp_pesan_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absen`
--
ALTER TABLE `absen`
  MODIFY `absen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `artikel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `chat_list`
--
ALTER TABLE `chat_list`
  MODIFY `chat_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `cuti`
--
ALTER TABLE `cuti`
  MODIFY `cuti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `hak_cuti`
--
ALTER TABLE `hak_cuti`
  MODIFY `hak_cuti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `izin`
--
ALTER TABLE `izin`
  MODIFY `izin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `jam_kerja`
--
ALTER TABLE `jam_kerja`
  MODIFY `jam_kerja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT untuk tabel `jam_kerja_master`
--
ALTER TABLE `jam_kerja_master`
  MODIFY `jam_kerja_master_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `kartu_nama`
--
ALTER TABLE `kartu_nama`
  MODIFY `kartu_nama_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `kunjungan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `lain_lain`
--
ALTER TABLE `lain_lain`
  MODIFY `lain_lain_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `level`
--
ALTER TABLE `level`
  MODIFY `level_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `libur`
--
ALTER TABLE `libur`
  MODIFY `libur_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `libur_nasional`
--
ALTER TABLE `libur_nasional`
  MODIFY `libur_nasional_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `lokasi_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lokasi_user`
--
ALTER TABLE `lokasi_user`
  MODIFY `lokasi_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `modul_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `notifikasi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `overtime`
--
ALTER TABLE `overtime`
  MODIFY `overtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `penugasan`
--
ALTER TABLE `penugasan`
  MODIFY `penugasan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `posisi`
--
ALTER TABLE `posisi`
  MODIFY `posisi_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `recognition`
--
ALTER TABLE `recognition`
  MODIFY `recognition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `setting`
--
ALTER TABLE `setting`
  MODIFY `site_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `setting_absen`
--
ALTER TABLE `setting_absen`
  MODIFY `setting_absen_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `slider`
--
ALTER TABLE `slider`
  MODIFY `slider_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `uraian_kerja`
--
ALTER TABLE `uraian_kerja`
  MODIFY `uraian_kerja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user_jam_kerja`
--
ALTER TABLE `user_jam_kerja`
  MODIFY `user_jam_kerja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `waktu`
--
ALTER TABLE `waktu`
  MODIFY `waktu_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `whatsapp_api`
--
ALTER TABLE `whatsapp_api`
  MODIFY `whatsapp_api_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `whatsapp_pesan`
--
ALTER TABLE `whatsapp_pesan`
  MODIFY `whatsapp_pesan_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

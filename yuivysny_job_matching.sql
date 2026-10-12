-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2025 at 01:27 AM
-- Server version: 10.6.23-MariaDB
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yuivysny_job_matching`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `judul_jp` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `slug_jp` varchar(255) DEFAULT NULL,
  `konten` text DEFAULT NULL,
  `konten_jp` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `kategori_jp` varchar(255) DEFAULT NULL,
  `penulis` varchar(255) DEFAULT NULL,
  `penulis_jp` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `status_jp` enum('下書き（したがき','公開（こうかい') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `name_jp` varchar(255) DEFAULT NULL,
  `slug_jp` varchar(255) DEFAULT NULL,
  `description_jp` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `kategori_jp` varchar(50) DEFAULT NULL,
  `id_ref` bigint(20) UNSIGNED DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `caption_jp` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_main` varchar(10) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lowongan_bahasa`
--

CREATE TABLE `lowongan_bahasa` (
  `id` int(11) NOT NULL,
  `lowongan_id` varchar(11) DEFAULT NULL,
  `bahasa_id` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_jp` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `slug_jp` varchar(255) DEFAULT NULL,
  `type` enum('utama','submenu') DEFAULT 'utama',
  `type_jp` enum('主メニュー','サブメニュー') DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `status_jp` enum('有効（ゆうこう）','無効（むこう') DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_jp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `section_category_id` int(11) DEFAULT NULL,
  `section_name_jp` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `slug_jp` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `content_jp` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `status_jp` enum('有効（ゆうこう）','無効（むこう') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_categories`
--

CREATE TABLE `section_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_jp` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `slug_jp` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_jp` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nama_jp` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_agama`
--

CREATE TABLE `tb_agama` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_jp` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_industri`
--

CREATE TABLE `tb_industri` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_jp` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_lamaran`
--

CREATE TABLE `tb_lamaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pekerja_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lowongan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT 'id diambil ke tb_status_perusahaan',
  `status_jp` enum('送信済み','処理中','拒否された','承認済み') DEFAULT NULL,
  `tanggal_lamar` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_level_bahasa`
--

CREATE TABLE `tb_level_bahasa` (
  `id` int(11) NOT NULL,
  `nama` varchar(10) DEFAULT NULL,
  `nama_jp` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_log`
--

CREATE TABLE `tb_log` (
  `id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tabel` varchar(40) DEFAULT NULL,
  `aksi` varchar(40) DEFAULT NULL COMMENT 'CRUD',
  `user` varchar(40) DEFAULT NULL COMMENT 'admin-id',
  `ip` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL COMMENT 'value sebelum Aksi',
  `serial` varchar(250) DEFAULT NULL COMMENT 'https/213',
  `header_agent` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_lowongan_kerja`
--

CREATE TABLE `tb_lowongan_kerja` (
  `id` int(11) NOT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `tempat_kerja` varchar(255) DEFAULT NULL,
  `tempat_kerja_jp` varchar(255) DEFAULT NULL,
  `industri_id` int(11) DEFAULT NULL,
  `jenis_pekerjaan` varchar(255) DEFAULT NULL,
  `jenis_pekerjaan_jp` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `deskripsi_jp` text DEFAULT NULL,
  `syarat` text DEFAULT NULL,
  `syarat_jp` text DEFAULT NULL,
  `level_bahasa` varchar(11) DEFAULT NULL,
  `gaji` varchar(100) DEFAULT NULL,
  `gaji_jp` varchar(100) DEFAULT NULL,
  `bonus` varchar(100) DEFAULT NULL,
  `bonus_jp` varchar(100) DEFAULT NULL,
  `kenaikan_gaji` varchar(100) DEFAULT NULL,
  `kenaikan_gaji_jp` varchar(100) DEFAULT NULL,
  `masa_percobaan` varchar(100) DEFAULT NULL,
  `masa_percobaan_jp` varchar(100) DEFAULT NULL,
  `jam_kerja` varchar(100) DEFAULT NULL,
  `jam_kerja_jp` varchar(100) DEFAULT NULL,
  `lembur` varchar(100) DEFAULT NULL,
  `lembur_jp` varchar(100) DEFAULT NULL,
  `asrama` varchar(255) DEFAULT NULL,
  `asrama_jp` varchar(255) DEFAULT NULL,
  `proses_pemilihan` text DEFAULT NULL,
  `proses_pemilihan_jp` text DEFAULT NULL,
  `informasi_perusahaan` text DEFAULT NULL,
  `informasi_perusahaan_jp` varchar(255) DEFAULT NULL,
  `status_pekerjaan` int(11) DEFAULT NULL,
  `kualifikasi` varchar(255) DEFAULT NULL,
  `kualifikasi_jp` varchar(255) DEFAULT NULL,
  `hari_libur` varchar(100) DEFAULT NULL,
  `hari_libur_jp` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `jenis_visa` varchar(100) DEFAULT NULL,
  `jenis_visa_jp` varchar(100) DEFAULT NULL,
  `fasilitas_kesejahteraan` text DEFAULT NULL,
  `fasilitas_kesejahteraan_jp` text DEFAULT NULL,
  `status_approval` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_lowongan_tag`
--

CREATE TABLE `tb_lowongan_tag` (
  `id` int(11) NOT NULL,
  `lowongan_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_minat_perusahaan`
--

CREATE TABLE `tb_minat_perusahaan` (
  `id` int(11) NOT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `pekerja_id` int(11) DEFAULT NULL,
  `metode` enum('Nomor Telepon','E-Mail','Keduanya Mungkin Saja') DEFAULT NULL,
  `metode_jp` enum('電話','メール','両方') DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `keterangan_jp` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pekerja`
--

CREATE TABLE `tb_pekerja` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_jp` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `nomor_telepon_jp` varchar(20) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_jp` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` int(10) DEFAULT NULL,
  `jenis_kelamin_jp` enum('男','女') DEFAULT NULL,
  `tempat_tinggal` text DEFAULT NULL,
  `tempat_tinggal_jp` text DEFAULT NULL,
  `tinggi` varchar(10) DEFAULT NULL,
  `berat` varchar(11) DEFAULT NULL,
  `agama` int(11) DEFAULT NULL,
  `agama_jp` varchar(50) DEFAULT NULL,
  `visa_keterampilan_khusus` int(11) DEFAULT NULL,
  `visa_keterampilan_khusus_jp` int(11) DEFAULT NULL,
  `visa_keterampilan_lain` varchar(255) DEFAULT NULL,
  `visa_keterampilan_lain_jp` varchar(200) DEFAULT NULL,
  `tingkat_bahasa` int(11) DEFAULT NULL,
  `tingkat_bahasa_jp` int(11) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `resume_path_jp` varchar(255) DEFAULT NULL,
  `sertifikat_jlpt_path` varchar(255) DEFAULT NULL,
  `sertifikat_jlpt_path_jp` varchar(255) DEFAULT NULL,
  `sertifikat_ssw_path` varchar(255) DEFAULT NULL,
  `sertifikat_ssw_path_jp` varchar(255) DEFAULT NULL,
  `pernah_magang` enum('Ya','Tidak') DEFAULT NULL,
  `pernah_magang_jp` enum('はい','いいえ') DEFAULT 'いいえ',
  `bidang_magang` varchar(256) DEFAULT NULL COMMENT 'id diambil dari tb_industri',
  `bidang_magang_jp` varchar(256) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `motivasi` text DEFAULT NULL,
  `motivasi_jp` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status_approval` int(11) DEFAULT NULL,
  `status_seleksi` int(11) DEFAULT NULL,
  `golongan_darah` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_perusahaan`
--

CREATE TABLE `tb_perusahaan` (
  `id` int(11) NOT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL,
  `nama_perusahaan_jp` varchar(255) DEFAULT NULL,
  `nama_departemen` varchar(255) DEFAULT NULL,
  `nama_departemen_jp` varchar(255) DEFAULT NULL,
  `nama_penanggung_jawab` varchar(255) DEFAULT NULL,
  `nama_penanggung_jawab_jp` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `alamat_jp` text DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `kode_pos_jp` varchar(20) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hubungi_kami` enum('Nomor Telepon','E-Mail','Keduanya Mungkin Saja') DEFAULT NULL,
  `hubungi_kami_jp` enum('電話番号 でんわばんごう','メールアドレス','両方とも可能 りょうほうとも かのう') DEFAULT NULL,
  `konten_pertanyaan` enum('Postingan Iklan Pekerjaan','Perekrutan') DEFAULT NULL,
  `konten_pertanyaan_jp` enum('求人広告 きゅうじんこうこく','採用（さいよう）') DEFAULT NULL,
  `keterangan_lain` text DEFAULT NULL,
  `keterangan_lain_jp` text DEFAULT NULL,
  `tokutei_gino` varchar(255) DEFAULT NULL,
  `tokutei_gino_jp` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_profil`
--

CREATE TABLE `tb_profil` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_riwayat_pekerjaan`
--

CREATE TABLE `tb_riwayat_pekerjaan` (
  `id` int(11) NOT NULL,
  `pekerja_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_jp` varchar(255) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `bulan_jp` varchar(20) DEFAULT NULL,
  `tahun` varchar(10) DEFAULT NULL,
  `tahun_jp` varchar(10) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keterangan_jp` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_riwayat_pendidikan`
--

CREATE TABLE `tb_riwayat_pendidikan` (
  `id` int(11) NOT NULL,
  `pekerja_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_jp` varchar(255) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `bulan_jp` varchar(20) DEFAULT NULL,
  `tahun` varchar(10) DEFAULT NULL,
  `tahun_jp` varchar(10) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keterangan_jp` text DEFAULT NULL,
  `riwayat_pendidikan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sertifikat`
--

CREATE TABLE `tb_sertifikat` (
  `id` int(11) NOT NULL,
  `pekerja_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_jp` varchar(255) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `bulan_jp` varchar(20) DEFAULT NULL,
  `tahun` varchar(10) DEFAULT NULL,
  `tahun_jp` varchar(10) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keterangan_jp` text DEFAULT NULL,
  `nilai` varchar(100) DEFAULT NULL,
  `nilai_jp` varchar(100) DEFAULT NULL,
  `sertifikat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_kerja`
--

CREATE TABLE `tb_status_kerja` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nama_jp` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_status_perusahaan`
--

CREATE TABLE `tb_status_perusahaan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_jp` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lowongan_bahasa`
--
ALTER TABLE `lowongan_bahasa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_categories`
--
ALTER TABLE `section_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_agama`
--
ALTER TABLE `tb_agama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_industri`
--
ALTER TABLE `tb_industri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_lamaran`
--
ALTER TABLE `tb_lamaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_level_bahasa`
--
ALTER TABLE `tb_level_bahasa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_log`
--
ALTER TABLE `tb_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_lowongan_kerja`
--
ALTER TABLE `tb_lowongan_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_lowongan_tag`
--
ALTER TABLE `tb_lowongan_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_minat_perusahaan`
--
ALTER TABLE `tb_minat_perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pekerja`
--
ALTER TABLE `tb_pekerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_perusahaan`
--
ALTER TABLE `tb_perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_profil`
--
ALTER TABLE `tb_profil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_riwayat_pekerjaan`
--
ALTER TABLE `tb_riwayat_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_riwayat_pendidikan`
--
ALTER TABLE `tb_riwayat_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_sertifikat`
--
ALTER TABLE `tb_sertifikat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_status_kerja`
--
ALTER TABLE `tb_status_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_status_perusahaan`
--
ALTER TABLE `tb_status_perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lowongan_bahasa`
--
ALTER TABLE `lowongan_bahasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_categories`
--
ALTER TABLE `section_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_agama`
--
ALTER TABLE `tb_agama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_industri`
--
ALTER TABLE `tb_industri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_lamaran`
--
ALTER TABLE `tb_lamaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_level_bahasa`
--
ALTER TABLE `tb_level_bahasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_log`
--
ALTER TABLE `tb_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_lowongan_kerja`
--
ALTER TABLE `tb_lowongan_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_lowongan_tag`
--
ALTER TABLE `tb_lowongan_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_minat_perusahaan`
--
ALTER TABLE `tb_minat_perusahaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_pekerja`
--
ALTER TABLE `tb_pekerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_perusahaan`
--
ALTER TABLE `tb_perusahaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_profil`
--
ALTER TABLE `tb_profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_riwayat_pekerjaan`
--
ALTER TABLE `tb_riwayat_pekerjaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_riwayat_pendidikan`
--
ALTER TABLE `tb_riwayat_pendidikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_sertifikat`
--
ALTER TABLE `tb_sertifikat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_status_kerja`
--
ALTER TABLE `tb_status_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_status_perusahaan`
--
ALTER TABLE `tb_status_perusahaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

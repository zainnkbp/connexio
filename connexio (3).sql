-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 30 Jul 2026 pada 13.46
-- Versi server: 8.0.30
-- Versi PHP: 8.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `connexio`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `assignments`
--

CREATE TABLE `assignments` (
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `id_pelanggan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_teknisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_alur` enum('Pengambilan','Pengembalian','Dismantling') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_approval` enum('Pending','In_Hand','Approved_by_Admin','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `foto_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alasan_rusak` text COLLATE utf8mb4_unicode_ci,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `assignments`
--

INSERT INTO `assignments` (`id_transaksi`, `id_pelanggan`, `id_teknisi`, `serial_number`, `tipe_alur`, `status_approval`, `foto_bukti`, `alasan_rusak`, `keterangan`, `created_at`, `updated_at`, `catatan_admin`) VALUES
(5, 'PLG-2026-OLD', 'usr-teknisi', 'SN-STB-OLD-999', 'Pengambilan', 'Approved_by_Admin', 'seeded_old_device_proof.jpg', NULL, NULL, '2026-05-31 07:34:05', '2026-05-31 07:34:05', NULL),
(6, 'PLG-2026-002', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-05-31 07:34:05', '2026-06-03 08:36:01', NULL),
(7, 'PLG-2026-001', 'usr-teknisi', 'SN-MODEM-ZTE-RETURN', 'Pengembalian', 'Approved_by_Admin', 'storage/assignments/seeded_return_proof.jpg', 'Modem sering restart sendiri', NULL, '2026-05-31 07:34:05', '2026-05-31 08:25:49', NULL),
(8, 'PLG-2026-001', 'usr-teknisi', 'SN-STB-ZTE-DISMANTLE', 'Dismantling', 'Rejected', 'storage/assignments/seeded_dismantle_proof.jpg', NULL, NULL, '2026-05-31 07:34:05', '2026-06-03 08:36:09', NULL),
(9, '123123123', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-05-31 07:35:46', '2026-06-03 08:36:03', NULL),
(10, '123123123', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-05-31 07:35:49', '2026-06-03 08:36:04', NULL),
(11, '123123123', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-05-31 07:35:50', '2026-06-03 08:36:05', NULL),
(12, '123', 'usr-teknisi', '123123', 'Dismantling', 'Approved_by_Admin', 'storage/assignments/1780243058_dismantle_123.jpg', NULL, NULL, '2026-05-31 07:57:38', '2026-05-31 08:27:34', NULL),
(13, '123', 'usr-teknisi', '12312312', 'Pengembalian', 'Approved_by_Admin', 'storage/assignments/1780243540_return_12312312.jpg', NULL, NULL, '2026-05-31 08:05:40', '2026-05-31 08:25:46', NULL),
(14, '123', 'usr-teknisi', '21312', 'Dismantling', 'Approved_by_Admin', 'storage/assignments/1780243569_dismantle_123.jpg', NULL, NULL, '2026-05-31 08:06:10', '2026-05-31 08:25:51', NULL),
(15, '1234', 'usr-teknisi', 'SN-MODEM-ZTE-001', 'Pengambilan', 'Approved_by_Admin', 'storage/assignments/1780243714_deploy_15.jpg', NULL, NULL, '2026-05-31 08:07:23', '2026-05-31 08:08:34', NULL),
(16, '123', 'usr-teknisi', '123123', 'Pengembalian', 'Approved_by_Admin', 'storage/assignments/1780244824_return_123123.jpg', NULL, NULL, '2026-05-31 08:27:04', '2026-05-31 08:27:59', NULL),
(17, '1', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-06-03 08:35:09', '2026-06-03 08:36:07', NULL),
(18, '1', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-06-03 08:35:09', '2026-06-03 08:35:58', NULL),
(19, '1', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-06-03 08:36:22', '2026-06-03 08:38:17', NULL),
(20, '1', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, NULL, '2026-06-03 08:36:22', '2026-06-03 08:37:51', NULL),
(21, '1', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, 'Request: Modem', '2026-06-03 08:38:38', '2026-06-03 08:43:48', NULL),
(22, '1', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, 'Request: STB', '2026-06-03 08:38:38', '2026-06-03 08:43:45', NULL),
(23, '1', 'usr-teknisi', 'SN-MODEM-HUA-002', 'Pengambilan', 'Approved_by_Admin', 'storage/assignments/1780505713_deploy_group_23.jpg', NULL, 'Request: Modem (1), STB (1)', '2026-06-03 08:44:04', '2026-06-03 08:55:13', NULL),
(24, '1', 'usr-teknisi', 'SN-STB-ZTE-004', 'Pengambilan', 'Approved_by_Admin', 'storage/assignments/1780505713_deploy_group_23.jpg', NULL, 'Request: Modem (1), STB (1)', '2026-06-03 08:49:56', '2026-06-03 08:55:13', NULL),
(25, '1', 'usr-teknisi', '20131231123', 'Pengambilan', 'Approved_by_Admin', 'storage/assignments/1780506161_deploy_group_25.jpg', NULL, 'Request: Modem (1)', '2026-06-03 08:57:33', '2026-06-03 09:02:41', NULL),
(26, '1', 'usr-teknisi', '123123123', 'Pengambilan', 'Approved_by_Admin', 'assignments/1780506693_deploy_group_26.jpg', NULL, 'Request: Modem (1), STB (1)', '2026-06-03 09:10:25', '2026-06-03 09:11:33', NULL),
(27, '1', 'usr-teknisi', '12312332', 'Pengambilan', 'Approved_by_Admin', 'assignments/1780506693_deploy_group_26.jpg', NULL, 'Request: Modem (1), STB (1)', '2026-06-03 09:11:18', '2026-06-03 09:11:33', NULL),
(28, '85677865', 'usr-teknisi', NULL, 'Pengambilan', 'Rejected', NULL, NULL, 'Request: Modem (1)', '2026-06-21 01:47:43', '2026-06-21 01:48:10', NULL),
(29, '85677865', 'usr-teknisi', '7867876', 'Pengambilan', 'Approved_by_Admin', 'assignments/1782035450_deploy_group_29.jpg', NULL, 'Request: Modem (1)', '2026-06-21 01:47:45', '2026-06-21 01:50:50', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id_pelanggan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pelanggan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_pemasangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status_langganan` enum('Active','Suspended','Terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id_pelanggan`, `nama_pelanggan`, `no_telepon`, `alamat_pemasangan`, `latitude`, `longitude`, `status_langganan`, `created_at`, `updated_at`) VALUES
('1', 'admin', '1', 'jl ruhui rahayu 25', -1.27120000, 116.87530000, 'Active', '2026-06-03 08:17:46', '2026-06-03 08:17:46'),
('123', 'panda', '1212', 'adaas', -1.27120000, 116.87530000, 'Terminated', '2026-05-31 07:50:20', '2026-05-31 08:25:51'),
('123123123', 'arif', '0895705013398', 'aasaa', -0.42150000, 117.22290000, 'Active', '2026-05-31 07:35:41', '2026-05-31 07:35:41'),
('1234', 'aman', '1210', 'adjasja', -1.27120000, 116.87530000, 'Active', '2026-05-31 08:07:18', '2026-05-31 08:07:18'),
('85677865', 'arif', '0895705013398', 'jl hjgjhhjgh', -1.27120000, 116.87530000, 'Active', '2026-06-21 01:47:32', '2026-06-21 01:47:32'),
('PLG-2026-001', 'John Doe', '081234567890', 'Jl. Sudirman No. 12, Jakarta', -6.20880000, 106.84560000, 'Active', '2026-05-31 07:34:05', '2026-05-31 07:34:05'),
('PLG-2026-002', 'Jane Smith', '081298765432', 'Gedung Artha Graha Lt. 15, Jakarta', NULL, NULL, 'Active', '2026-05-31 07:34:05', '2026-05-31 07:34:05'),
('PLG-2026-OLD', 'Bambang Wijaya', '081300001111', 'Jl. Menteng Raya No. 4, Jakarta', -6.18520000, 106.83130000, 'Active', '2026-05-31 07:34:05', '2026-05-31 07:34:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `devices`
--

CREATE TABLE `devices` (
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_merek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_perangkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_kondisi` enum('Terpasang','Rusak','Dismantling') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alasan_rusak` text COLLATE utf8mb4_unicode_ci,
  `tanggal_pasang_awal` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `devices`
--

INSERT INTO `devices` (`serial_number`, `jenis_merek`, `tipe_perangkat`, `status_kondisi`, `alasan_rusak`, `tanggal_pasang_awal`, `created_at`, `updated_at`) VALUES
('123123', 'STB Huawei', 'Manual Input', 'Rusak', NULL, NULL, '2026-05-31 07:57:38', '2026-05-31 08:27:59'),
('12312312', 'STB Huawei', 'b860h', 'Rusak', NULL, NULL, '2026-05-31 08:05:40', '2026-05-31 08:25:46'),
('123123123', 'STB Huawei', 'huawei210', 'Terpasang', NULL, '2026-06-03 17:11:33', '2026-06-03 09:11:18', '2026-06-03 09:11:33'),
('12312332', 'Modem Huawei', 'f609', 'Terpasang', NULL, '2026-06-03 17:11:33', '2026-06-03 09:11:18', '2026-06-03 09:11:33'),
('20131231123', 'Modem ZTE', 'f609', 'Terpasang', NULL, '2026-06-03 17:02:41', '2026-06-03 08:58:42', '2026-06-03 09:02:41'),
('21312', 'STB Huawei', '609f', 'Dismantling', NULL, NULL, '2026-05-31 08:06:09', '2026-05-31 08:25:51'),
('7867876', 'STB Huawei', 'huawei210', 'Terpasang', NULL, '2026-06-21 09:50:50', '2026-06-21 01:49:24', '2026-06-21 01:50:50'),
('SN-MODEM-HUA-002', 'Modem Huawei', 'HG8245H', 'Terpasang', NULL, '2026-06-03 16:55:13', '2026-05-31 07:34:05', '2026-06-03 08:55:13'),
('SN-MODEM-ZTE-001', 'Modem ZTE', 'F609', 'Terpasang', NULL, '2026-05-31 16:08:34', '2026-05-31 07:34:05', '2026-05-31 08:08:34'),
('SN-MODEM-ZTE-RETURN', 'Modem ZTE', 'F609', 'Rusak', 'Modem sering restart sendiri', '2025-12-01 15:34:05', '2026-05-31 07:34:05', '2026-05-31 08:25:49'),
('SN-STB-HUA-003', 'STB Huawei', 'huawei790', NULL, NULL, NULL, '2026-05-31 07:34:05', '2026-05-31 07:34:05'),
('SN-STB-OLD-999', 'STB Huawei', 'huawei790', 'Terpasang', NULL, '2023-03-31 15:34:05', '2026-05-31 07:34:05', '2026-05-31 07:34:05'),
('SN-STB-ZTE-004', 'STB ZTE', 'B860H', 'Terpasang', NULL, '2026-06-03 16:55:13', '2026-05-31 07:34:05', '2026-06-03 08:55:13'),
('SN-STB-ZTE-DISMANTLE', 'STB ZTE', 'B860H', 'Terpasang', NULL, '2025-05-31 15:34:05', '2026-05-31 07:34:05', '2026-05-31 07:34:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_05_31_000001_create_customers_table', 1),
(6, '2026_05_31_000002_create_devices_table', 1),
(7, '2026_05_31_000003_create_assignments_table', 1),
(8, '2026_06_03_163139_add_keterangan_to_assignments_table', 2),
(9, '2026_06_21_113432_add_catatan_admin_to_assignments_table', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Super Admin','Admin','Teknisi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_jelas`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
('usr-admin', 'Admin Gudang', 'admin', '$2y$10$9lsL9QJw.krkB5x.YnWxeObvmhoIWIJvqVjuKyS/RXh83aLti7Heq', 'Admin', NULL, '2026-05-31 07:34:05', '2026-05-31 07:34:05'),
('usr-superadmin', 'Super Admin Utama', 'superadmin', '$2y$10$ABgnERq8Utndn4GMJUIu9eo4oHFB3MwX.gdAiirUDkUihoy6t7KAm', 'Super Admin', NULL, '2026-05-31 07:34:05', '2026-05-31 07:34:05'),
('usr-teknisi', 'Budi Santoso', 'teknisi', '$2y$10$/tEEtSuPsWPqDEe0CwDQ2.7y57SyPbxMXr03vwUzq4O3X7gBZA6Na', 'Teknisi', NULL, '2026-05-31 07:34:05', '2026-05-31 07:34:05');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `assignments_id_pelanggan_foreign` (`id_pelanggan`),
  ADD KEY `assignments_id_teknisi_foreign` (`id_teknisi`),
  ADD KEY `assignments_serial_number_foreign` (`serial_number`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`serial_number`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id_transaksi` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `customers` (`id_pelanggan`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_id_teknisi_foreign` FOREIGN KEY (`id_teknisi`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_serial_number_foreign` FOREIGN KEY (`serial_number`) REFERENCES `devices` (`serial_number`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

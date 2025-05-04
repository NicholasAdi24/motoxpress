-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 08:29 AM
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
-- Database: `motoxpress`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `nama_barang`, `harga`, `stok`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Oli Shell Advance AX7 10W-40 0.8 Liter', 50000.00, 10, 'images/tDCAeXzbPMmm621zW6DORUW2BmCJ8ZB31MY3UCXj.jpg', '2025-02-12 07:37:13', '2025-03-24 00:22:20'),
(3, 'Sepasang Ban Motor Luar Matic Beat Vario Mio Spacy ( Ukuran Depan 80/90 & Belakang 90/90 Ring 14 )', 158000.00, 20, 'gambar_barang/KOquZkkwkYeORdWnv9ZYvKHZBiQJtx37jCmWy9Rl.jpg', '2025-03-02 21:59:46', '2025-03-02 21:59:46'),
(6, 'ban', 20000.00, 19, 'gambar_barang/lOsZZjWvYMJJD4JYXBkNMiGpgoIscFg8S7bRRa9e.jpg', '2025-03-03 02:16:35', '2025-03-24 00:22:38');

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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_07_051135_add_role_to_users_table', 2),
(5, '2025_02_07_052852_create_barangs_table', 3),
(6, '2025_03_19_160034_create_pembayarans_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('kasir4@gmail.com', '$2y$12$tu6LNQ3A.bhEhzBiOIM.je7aMFFSwIZERaJgWnD0/.rOIqV7971/K', '2025-03-19 00:28:28');

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pesanan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'default_value' CHECK (json_valid(`pesanan`)),
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('cash','qris') NOT NULL,
  `status_pembayaran` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `waktu_pembayaran` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `pesanan`, `total_harga`, `metode_pembayaran`, `status_pembayaran`, `bukti_pembayaran`, `waktu_pembayaran`, `created_at`, `updated_at`) VALUES
(1, '\"[{\\\"nama\\\":\\\"Oli Shell Advance AX7 10W-40 0.8 Liter\\\",\\\"qty\\\":1,\\\"total\\\":50000}]\"', 50000.00, 'cash', 'paid', NULL, '2025-03-19 10:36:36', '2025-03-19 10:36:36', '2025-03-19 10:36:36'),
(2, '\"[{\\\"nama\\\":\\\"Sepasang Ban Motor Luar Matic Beat Vario Mio Spacy ( Ukuran Depan 80\\/90 & Belakang 90\\/90 Ring 14 )\\\",\\\"qty\\\":1,\\\"total\\\":158000}]\"', 158000.00, 'cash', 'paid', NULL, '2025-03-20 04:26:06', '2025-03-20 04:26:06', '2025-03-20 04:26:06'),
(3, '\"[{\\\"nama\\\":\\\"Sepasang Ban Motor Luar Matic Beat Vario Mio Spacy ( Ukuran Depan 80\\/90 & Belakang 90\\/90 Ring 14 )\\\",\\\"qty\\\":2,\\\"total\\\":316000},{\\\"nama\\\":\\\"Oli Shell Advance AX7 10W-40 0.8 Liter\\\",\\\"qty\\\":1,\\\"total\\\":50000},{\\\"nama\\\":\\\"test\\\",\\\"qty\\\":1,\\\"total\\\":20000}]\"', 386000.00, 'cash', 'paid', NULL, '2025-03-20 04:29:14', '2025-03-20 04:29:14', '2025-03-20 04:29:14'),
(4, '\"[{\\\"nama\\\":\\\"Sepasang Ban Motor Luar Matic Beat Vario Mio Spacy ( Ukuran Depan 80\\/90 & Belakang 90\\/90 Ring 14 )\\\",\\\"qty\\\":1,\\\"total\\\":158000}]\"', 158000.00, 'cash', 'paid', NULL, '2025-03-20 04:53:11', '2025-03-20 04:53:11', '2025-03-20 04:53:11'),
(5, '\"[{\\\"nama\\\":\\\"test\\\",\\\"qty\\\":1,\\\"total\\\":20000}]\"', 20000.00, 'qris', 'paid', '1742471686_f08ac136-2b61-4bff-ae68-a9e85fd2f93b.jpg', '2025-03-20 04:54:46', '2025-03-20 04:54:47', '2025-03-20 04:54:47'),
(6, '\"[{\\\"id\\\":\\\"1\\\",\\\"nama\\\":\\\"Oli Shell Advance AX7 10W-40 0.8 Liter\\\",\\\"qty\\\":1,\\\"total\\\":50000}]\"', 50000.00, 'cash', 'paid', NULL, '2025-03-20 23:35:07', '2025-03-20 23:35:07', '2025-03-20 23:35:07'),
(7, '\"[{\\\"id\\\":\\\"6\\\",\\\"nama\\\":\\\"test\\\",\\\"qty\\\":1,\\\"total\\\":20000}]\"', 20000.00, 'cash', 'paid', NULL, '2025-03-20 23:44:19', '2025-03-20 23:44:19', '2025-03-20 23:44:19'),
(8, '\"[{\\\"id\\\":\\\"1\\\",\\\"nama\\\":\\\"Oli Shell Advance AX7 10W-40 0.8 Liter\\\",\\\"qty\\\":1,\\\"total\\\":50000}]\"', 50000.00, 'cash', 'paid', NULL, '2025-03-20 23:44:39', '2025-03-20 23:44:39', '2025-03-20 23:44:39'),
(9, '\"[{\\\"id\\\":\\\"6\\\",\\\"nama\\\":\\\"test\\\",\\\"qty\\\":2,\\\"total\\\":40000}]\"', 40000.00, 'qris', 'paid', '1742576267_WhatsApp Image 2024-10-28 at 22.06.35_10dd3fed.jpg', '2025-03-21 09:57:47', '2025-03-21 09:57:47', '2025-03-21 09:57:47'),
(10, '\"[{\\\"id\\\":\\\"1\\\",\\\"nama\\\":\\\"Oli Shell Advance AX7 10W-40 0.8 Liter\\\",\\\"qty\\\":1,\\\"total\\\":50000},{\\\"id\\\":\\\"6\\\",\\\"nama\\\":\\\"ban\\\",\\\"qty\\\":1,\\\"total\\\":20000}]\"', 70000.00, 'qris', 'paid', '1742800834_028177000_1539697411-Keraton_Surakarta__1_.jpg', '2025-03-24 00:20:34', '2025-03-24 00:20:35', '2025-03-24 00:20:35'),
(11, '\"[{\\\"id\\\":\\\"1\\\",\\\"nama\\\":\\\"Oli Shell Advance AX7 10W-40 0.8 Liter\\\",\\\"qty\\\":5,\\\"total\\\":250000}]\"', 250000.00, 'cash', 'paid', NULL, '2025-03-24 00:21:19', '2025-03-24 00:21:19', '2025-03-24 00:21:19');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('foWqpl5INSAryVt2nst6VzKVZbZmlJxHY8Ynrv65', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibTUySkR5anZxa251dnluMTdQN3NTQlJJVHlOdTZRMWppRDVDZWUzbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYXNpciI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjg7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQyODAxMDY4O319', 1742801235),
('HjAwztHPkwbWuvrAecFVDZeHnITeX9h5gqgxE6XK', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNE9XdHVXVGRVc2l6MHBSeEtKbW5KWHhGVndFM25CVW1Ualp3cVZDaiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Jpd2F5YXQtdHJhbnNha3NpIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yaXdheWF0LXRyYW5zYWtzaSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjg7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQyNTc2MTQ4O319', 1742577914);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('kasir','admin','pemilik') NOT NULL DEFAULT 'kasir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin1', 'admin1@gmail.com', NULL, '$2y$12$6CFt7AlMCL4eMhBdBY2feuGzyT/sKoVK/dR1VS5A3UPWtqa/hUbyq', NULL, '2025-02-06 22:47:48', '2025-02-06 22:47:48', 'admin'),
(2, 'kasir', 'kasir@gmail.com', NULL, '$2y$12$L/QNMDVS4fWBWHprQzrs7u9QbjXxTYLh4L8pc.fsTChtkun7fkVRm', NULL, '2025-02-06 22:50:19', '2025-02-06 22:50:19', 'kasir'),
(4, 'admin', 'admin@gmail.com', NULL, '$2y$12$/q9i1uXhCGsvxgLmZB2pZuSXLVxRWnNcTmg9SXTBFpktV2IfIwt2a', 'OuA0XzFfLeexDRhsqHqO8MTK6rkGRWaU878vvQbzR6wNqUNCzpmPUr96q797', '2025-02-09 10:30:09', '2025-02-09 10:30:09', 'admin'),
(5, 'kasir', 'kasir1@gmail.com', NULL, '$2y$12$JZc0pPTH44iGFiBoAkpGq.LbuLvEfV2vWM9nKllNW7XRdX.QRnLve', NULL, '2025-02-09 11:08:04', '2025-02-09 11:08:04', 'kasir'),
(6, 'kasir2', 'kasir2@gmail.com', NULL, '$2y$12$9u8OlQmrTxovnbAuGxYzd.nbwLxswxH8E3YV30rbBksj5ayjHZmC.', NULL, '2025-03-03 06:45:24', '2025-03-03 06:45:24', 'kasir'),
(7, 'kasir3', 'kasir3@gmail.com', NULL, '$2y$12$gd1dVt/bgPgqDfeCo.hYO.CvsFhPcZRhIHM1V/tiEV6BuFkDBem6S', NULL, '2025-03-03 07:21:06', '2025-03-03 07:21:06', 'kasir'),
(8, 'kasir', 'kasir4@gmail.com', NULL, '$2y$12$bFxa7v7x1eg.Gb6CaD8vVusZoueqtoyvczLjwcq.HRxYi.D6n.pCC', 'IGNKJqS758CptW6YvzqaHSsrAqGTf5aLJPqREp0n5giPOqzCJG4oS0OuDIgP', '2025-03-14 22:45:16', '2025-03-14 22:45:16', 'kasir'),
(9, 'kasirrr', 'kasir5@gmail.com', NULL, '$2y$12$zd1QA.6DC1yLwivRkyO0TOz8OvyZyaGRf8fpNjw/fHpnCxLE.Eo/q', NULL, '2025-03-19 00:39:50', '2025-03-19 00:39:50', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
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
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Nov 30, 2023 at 07:46 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laporin`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attachmentable_type` varchar(255) NOT NULL,
  `attachmentable_id` bigint(20) UNSIGNED NOT NULL,
  `uploader_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `attachmentable_type`, `attachmentable_id`, `uploader_id`, `path`, `filename`, `size`, `description`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Report', 1, 1, '2023/Report/0001/', '(20231129103915) Untitled.jpg', 270446, NULL, '2023-11-29 03:39:15', '2023-11-29 03:39:15'),
(2, 'App\\Models\\Report', 1, 1, '2023/Report/0001/', '(20231129144956) Untitled.jpg', 270446, NULL, '2023-11-29 07:49:56', '2023-11-29 07:49:56'),
(3, 'App\\Models\\Report', 3, 2, '2023/Report/0003/', '(20231130092025) png-clipart-billy-cranston-kimberly-hart-tommy-oliver-tabletop-role-playing-game-power-rangers-super-ninja-steel-cartoon-power-ranger-blue-fictional-character.png', 26827, NULL, '2023-11-30 02:20:25', '2023-11-30 02:20:25'),
(4, 'App\\Models\\Report', 3, 1, '2023/Report/0003/', '(20231130133805) 86e99c4cdad940a3dca5d7ed824d4042.jpg', 16599, NULL, '2023-11-30 06:38:05', '2023-11-30 06:38:05');

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
-- Table structure for table `login_activities`
--

CREATE TABLE `login_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `browser` varchar(255) NOT NULL,
  `platform` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_activities`
--

INSERT INTO `login_activities` (`id`, `user_id`, `ip_address`, `browser`, `platform`, `created_at`, `updated_at`) VALUES
(1, 1, '127.0.0.1', 'Firefox 119', 'Windows 10', '2023-11-23 07:11:37', '2023-11-23 07:11:37'),
(2, 1, '127.0.0.1', 'Firefox 119', 'Windows 10', '2023-11-23 07:12:05', '2023-11-23 07:12:05'),
(3, 1, '127.0.0.1', 'Firefox 119', 'Windows 10', '2023-11-23 07:14:43', '2023-11-23 07:14:43'),
(4, 2, '127.0.0.1', 'Firefox 119', 'Windows 10', '2023-11-23 07:55:27', '2023-11-23 07:55:27'),
(5, 1, '127.0.0.1', 'Firefox 119', 'Windows 10', '2023-11-24 01:07:03', '2023-11-24 01:07:03'),
(6, 1, '127.0.0.1', 'Firefox 119', 'Windows 10', '2023-11-24 06:50:27', '2023-11-24 06:50:27'),
(7, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-27 00:28:46', '2023-11-27 00:28:46'),
(8, 2, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-27 01:42:44', '2023-11-27 01:42:44'),
(9, 3, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-27 02:33:17', '2023-11-27 02:33:17'),
(10, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-27 06:24:26', '2023-11-27 06:24:26'),
(11, 3, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-27 08:02:04', '2023-11-27 08:02:04'),
(12, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-28 00:11:02', '2023-11-28 00:11:02'),
(13, 3, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-28 02:28:55', '2023-11-28 02:28:55'),
(14, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-28 06:11:35', '2023-11-28 06:11:35'),
(15, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-29 00:25:56', '2023-11-29 00:25:56'),
(16, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-29 02:36:52', '2023-11-29 02:36:52'),
(17, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-29 06:19:36', '2023-11-29 06:19:36'),
(18, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-30 00:04:13', '2023-11-30 00:04:13'),
(19, 2, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-30 02:19:10', '2023-11-30 02:19:10'),
(20, 1, '127.0.0.1', 'Firefox 120', 'Windows 10', '2023-11-30 06:31:50', '2023-11-30 06:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'circle',
  `route_or_url` varchar(255) DEFAULT '#',
  `counter_handler` varchar(512) DEFAULT NULL,
  `position` tinyint(3) UNSIGNED NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 1,
  `deleteable` tinyint(1) NOT NULL DEFAULT 1,
  `actives` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `parent_id`, `name`, `icon`, `route_or_url`, `counter_handler`, `position`, `enable`, `deleteable`, `actives`, `created_at`, `updated_at`) VALUES
(1, NULL, 'dashboard', 'tachometer-alt', 'dashboard', NULL, 1, 1, 0, '[]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(2, NULL, 'builtin', 'circle', '#', NULL, 2, 1, 0, '[]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(3, 2, 'permission', 'key', 'superuser.permission.index', NULL, 1, 1, 0, '[\"superuser.permission.*\"]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(4, 2, 'role', 'user-cog', 'superuser.role.index', NULL, 2, 1, 0, '[\"superuser.role.*\"]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(5, 2, 'user', 'user', 'superuser.user.index', NULL, 3, 1, 0, '[\"superuser.user.*\"]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(6, 2, 'menu', 'bars', 'superuser.menu.index', NULL, 4, 1, 0, '[\"superuser.menu.*\"]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(7, 2, 'translation', 'language', 'superuser.translation.index', NULL, 5, 1, 0, '[\"superuser.translation.*\"]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(8, NULL, 'activities', 'address-card', '#', NULL, 3, 1, 0, '[]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(9, 8, 'login', 'user-clock', 'superuser.activity.login', NULL, 1, 1, 0, '[\"superuser.activity.login\"]', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(10, NULL, 'Laporin', 'file-signature', 'report.index', NULL, 4, 1, 1, '[]', '2023-11-23 07:13:27', '2023-11-24 01:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `menu_permission`
--

CREATE TABLE `menu_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_permission`
--

INSERT INTO `menu_permission` (`id`, `menu_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, NULL),
(2, 2, 6, NULL, NULL),
(3, 2, 10, NULL, NULL),
(4, 2, 14, NULL, NULL),
(5, 3, 5, NULL, NULL),
(6, 3, 8, NULL, NULL),
(7, 3, 6, NULL, NULL),
(8, 3, 7, NULL, NULL),
(9, 4, 9, NULL, NULL),
(10, 4, 12, NULL, NULL),
(11, 4, 10, NULL, NULL),
(12, 4, 11, NULL, NULL),
(13, 5, 1, NULL, NULL),
(14, 5, 4, NULL, NULL),
(15, 5, 2, NULL, NULL),
(16, 5, 3, NULL, NULL),
(17, 6, 13, NULL, NULL),
(18, 6, 16, NULL, NULL),
(19, 6, 14, NULL, NULL),
(20, 6, 15, NULL, NULL),
(21, 8, 17, NULL, NULL),
(22, 9, 18, NULL, NULL);

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
(1, '0000_00_00_000000_create_websockets_statistics_entries_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2022_07_13_064039_create_sessions_table', 1),
(8, '2022_07_15_060249_add_username_column_to_users_table', 1),
(9, '2022_07_15_192352_create_permission_tables', 1),
(10, '2022_07_15_192421_create_menus_table', 1),
(11, '2022_07_15_192434_create_menu_permission_table', 1),
(12, '2022_07_29_034330_create_jobs_table', 1),
(13, '2022_07_29_123750_create_logins_table', 1),
(14, '2022_07_29_140107_create_temporary_tokens_table', 1),
(15, '2022_08_29_092615_add_counter_column_to_menus_table', 1),
(16, '2023_11_24_090936_create_reports_table', 2),
(17, '2023_11_24_094056_create_attachments_table', 3),
(18, '2023_11_24_094822_create_reports_table', 4),
(19, '2023_11_27_100431_create_reports_table', 5),
(20, '2023_11_27_133113_create_reports_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'create user', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(2, 'read user', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(3, 'update user', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(4, 'delete user', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(5, 'create permission', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(6, 'read permission', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(7, 'update permission', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(8, 'delete permission', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(9, 'create role', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(10, 'read role', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(11, 'update role', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(12, 'delete role', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(13, 'create menu', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(14, 'read menu', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(15, 'update menu', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(16, 'delete menu', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(17, 'read activities', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(18, 'read login activities', 'web', '2023-11-23 07:10:21', '2023-11-23 07:10:21'),
(19, 'create report', 'web', '2023-11-27 01:41:23', '2023-11-27 01:41:23'),
(20, 'read report', 'web', '2023-11-27 01:41:32', '2023-11-27 01:41:32'),
(21, 'update report', 'web', '2023-11-27 01:41:41', '2023-11-27 01:41:41'),
(22, 'delete report', 'web', '2023-11-27 01:41:52', '2023-11-27 01:41:52');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(13, 'App\\Models\\User', 3, '656550678871a', 'f223a76001f98d2a29cc1a085434b0c144d8187d7a8d63edc26e63253073490e', '[\"*\"]', NULL, '2023-11-28 02:28:55', '2023-11-28 02:28:55'),
(19, 'App\\Models\\User', 2, '6567f11e84bc5', 'b5862c422a44108ec991e8f2a5d99dc31fbd0949a7682be2225eb64e44438758', '[\"*\"]', NULL, '2023-11-30 02:19:10', '2023-11-30 02:19:10'),
(20, 'App\\Models\\User', 1, '65682c5566fa5', '86633423456d7077160021b3bbebe1ade1c815d5ba923c38c5504a9479252225', '[\"*\"]', NULL, '2023-11-30 06:31:49', '2023-11-30 06:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `keluhan` varchar(255) NOT NULL,
  `penyebab` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `bagian` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` int(11) DEFAULT 1,
  `catatan` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `kode`, `keluhan`, `penyebab`, `kategori`, `bagian`, `url`, `status`, `catatan`, `file_path`, `created_by_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Jika kamu masih mengalami kesulitan, mungkin ada baiknya untuk memeriksa kembali implementasi dari fungsi findModel() dan memastikan bahwa variabel $type telah dideklarasikan dengan benar di dalam fungsi tersebut.', 'a', 'Minor', 'E-KFPB', 'http://localhost:8000/report', 1, NULL, '', 1, '2023-11-28 02:43:22', '2023-11-30 02:25:09'),
(3, NULL, 'llllll', 'sdqwe', 'Minor', 'E-SDM KFPB', 'https://kfpb.kimiafarma.co.id/coba2/home.php?pages=sintertp&act=tambah3', 1, NULL, '', 2, '2023-11-30 02:20:14', '2023-11-30 02:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superuser', 'web', '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(2, 'user', 'web', '2023-11-27 01:40:31', '2023-11-27 01:40:31'),
(3, 'it', 'web', '2023-11-27 02:32:09', '2023-11-27 02:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 3),
(3, 1),
(3, 3),
(4, 1),
(4, 3),
(5, 1),
(5, 3),
(6, 1),
(6, 3),
(7, 1),
(7, 3),
(8, 1),
(8, 3),
(9, 1),
(9, 3),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(12, 3),
(13, 1),
(13, 3),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(17, 3),
(18, 1),
(18, 3),
(19, 1),
(19, 2),
(19, 3),
(20, 1),
(20, 2),
(20, 3),
(21, 1),
(21, 2),
(21, 3),
(22, 1),
(22, 2),
(22, 3);

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
('1WJ9D6BHiFm09rBLQ54J86CRR48v3SCubsyxL8Uc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSktDZkJkbGhNanhpWWFEVGRBOE1QQm02OURqT1g2UHNmUFk2RWo4MyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcmVwb3J0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjU6InRva2VuIjtzOjQzOiIyMHw3ZjdxcUVHU0hyUk9SSDRPU2NCeFNFVGRJNnhwUjV0YmJHYVlSaElLIjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCR5MEdwaUlYSzR6U3hXSW9URFpZdjRlLkg4L1Y2WWF5Mzh2U0VnWDNCd2ZyQlJJMWN5VzIwRyI7fQ==', 1701326379);

-- --------------------------------------------------------

--
-- Table structure for table `temporary_tokens`
--

CREATE TABLE `temporary_tokens` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `temporary_tokens`
--

INSERT INTO `temporary_tokens` (`user_id`, `token_id`, `created_at`, `updated_at`) VALUES
(1, 20, NULL, NULL),
(2, 19, NULL, NULL),
(3, 13, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'root', 'su', 'root@local', '2023-11-23 07:10:20', '$2y$10$y0GpiIXK4zSxWIoTDZYv4e.H8/V6Yay38vSEgX3BwfrBRI1cyW20G', NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-23 07:10:20', '2023-11-23 07:10:20'),
(2, 'user', 'user', 'user@gmail.com', '2023-11-23 07:10:20', '$2y$10$y0GpiIXK4zSxWIoTDZYv4e.H8/V6Yay38vSEgX3BwfrBRI1cyW20G', NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-23 07:10:20', '2023-11-27 01:43:24'),
(3, 'it', 'it', 'it@gmail.com', '2023-11-27 02:32:53', '$2y$10$5IdpJwdJk8o4Ri8MPOwmD.Mk3UOdU5MJtVfjKxV4WkHJe2ZAFtA1m', NULL, NULL, NULL, NULL, NULL, NULL, '2023-11-27 02:32:53', '2023-11-27 02:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `websockets_statistics_entries`
--

CREATE TABLE `websockets_statistics_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_id` varchar(255) NOT NULL,
  `peak_connection_count` int(11) NOT NULL,
  `websocket_message_count` int(11) NOT NULL,
  `api_message_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_attachmentable_type_attachmentable_id_index` (`attachmentable_type`,`attachmentable_id`),
  ADD KEY `attachments_uploader_id_foreign` (`uploader_id`);

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
-- Indexes for table `login_activities`
--
ALTER TABLE `login_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `menu_permission`
--
ALTER TABLE `menu_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_permission_menu_id_foreign` (`menu_id`),
  ADD KEY `menu_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `temporary_tokens`
--
ALTER TABLE `temporary_tokens`
  ADD UNIQUE KEY `temporary_tokens_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `temporary_tokens_token_id_unique` (`token_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `login_activities`
--
ALTER TABLE `login_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `menu_permission`
--
ALTER TABLE `menu_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_uploader_id_foreign` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_activities`
--
ALTER TABLE `login_activities`
  ADD CONSTRAINT `login_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_permission`
--
ALTER TABLE `menu_permission`
  ADD CONSTRAINT `menu_permission_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temporary_tokens`
--
ALTER TABLE `temporary_tokens`
  ADD CONSTRAINT `temporary_tokens_token_id_foreign` FOREIGN KEY (`token_id`) REFERENCES `personal_access_tokens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temporary_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

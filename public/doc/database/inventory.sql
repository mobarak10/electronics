-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 29, 2020 at 08:50 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `accesslogs`
--

CREATE TABLE `accesslogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genus` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accesslogs`
--

INSERT INTO `accesslogs` (`id`, `user`, `ip`, `genus`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '192.168.0.105', 'web', 'in', '2020-03-12 03:51:05', '2020-03-12 03:51:05'),
(2, 1, '192.168.0.105', 'web', 'in', '2020-03-12 04:05:30', '2020-03-12 04:05:30'),
(3, 1, '192.168.0.105', 'web', 'in', '2020-03-12 04:12:48', '2020-03-12 04:12:48'),
(4, 1, '192.168.0.109', 'web', 'in', '2020-03-12 04:49:14', '2020-03-12 04:49:14'),
(5, 1, '192.168.0.105', 'web', 'in', '2020-03-12 05:35:24', '2020-03-12 05:35:24'),
(6, 1, '192.168.0.109', 'web', 'in', '2020-03-12 07:11:06', '2020-03-12 07:11:06'),
(7, 16, '192.168.0.105', 'admin', 'in', '2020-03-12 11:30:25', '2020-03-12 11:30:25'),
(8, 1, '192.168.0.112', 'web', 'in', '2020-03-12 11:48:14', '2020-03-12 11:48:14'),
(9, 1, '192.168.0.109', 'web', 'in', '2020-03-12 11:53:48', '2020-03-12 11:53:48'),
(10, 1, '192.168.0.112', 'web', 'in', '2020-03-14 04:48:49', '2020-03-14 04:48:49'),
(11, 1, '192.168.0.109', 'web', 'in', '2020-03-14 05:16:56', '2020-03-14 05:16:56'),
(12, 1, '192.168.0.109', 'web', 'in', '2020-03-14 11:24:42', '2020-03-14 11:24:42'),
(13, 1, '192.168.0.112', 'web', 'in', '2020-03-15 04:53:00', '2020-03-15 04:53:00'),
(14, 1, '192.168.0.109', 'web', 'in', '2020-03-15 05:04:21', '2020-03-15 05:04:21'),
(15, 1, '192.168.0.113', 'web', 'in', '2020-03-15 05:34:42', '2020-03-15 05:34:42'),
(16, 1, '192.168.0.113', 'web', 'in', '2020-03-15 06:40:08', '2020-03-15 06:40:08'),
(17, 1, '192.168.0.113', 'web', 'in', '2020-03-15 06:44:39', '2020-03-15 06:44:39'),
(18, 1, '192.168.0.113', 'web', 'in', '2020-03-15 06:46:08', '2020-03-15 06:46:08'),
(19, 1, '192.168.0.113', 'web', 'in', '2020-03-15 06:47:43', '2020-03-15 06:47:43'),
(20, 1, '192.168.0.113', 'web', 'in', '2020-03-15 06:48:31', '2020-03-15 06:48:31'),
(21, 1, '192.168.0.126', 'web', 'in', '2020-03-15 07:31:17', '2020-03-15 07:31:17'),
(22, 1, '192.168.0.112', 'web', 'out', '2020-03-15 10:14:44', '2020-03-15 14:13:20'),
(23, 1, '192.168.0.105', 'web', 'in', '2020-03-16 04:03:23', '2020-03-16 04:03:23'),
(24, 1, '192.168.0.112', 'web', 'in', '2020-03-16 04:20:54', '2020-03-16 04:20:54'),
(25, 1, '192.168.0.112', 'web', 'in', '2020-03-16 07:03:26', '2020-03-16 07:03:26'),
(26, 1, '192.168.0.105', 'web', 'out', '2020-03-16 09:08:09', '2020-03-16 12:07:13'),
(27, 1, '192.168.0.112', 'web', 'out', '2020-03-16 11:07:50', '2020-03-16 14:44:32'),
(28, 1, '192.168.0.105', 'web', 'in', '2020-03-16 12:07:19', '2020-03-16 12:07:19'),
(29, 1, '192.168.0.112', 'web', 'in', '2020-03-17 04:27:05', '2020-03-17 04:27:05'),
(30, 1, '192.168.0.113', 'web', 'in', '2020-03-17 05:53:27', '2020-03-17 05:53:27'),
(31, 1, '192.168.0.112', 'web', 'in', '2020-03-17 10:36:17', '2020-03-17 10:36:17'),
(32, 1, '192.168.0.113', 'web', 'in', '2020-03-18 04:18:33', '2020-03-18 04:18:33'),
(33, 1, '192.168.0.112', 'web', 'in', '2020-03-18 04:46:32', '2020-03-18 04:46:32'),
(34, 1, '192.168.0.106', 'web', 'in', '2020-03-18 04:52:39', '2020-03-18 04:52:39'),
(35, 1, '192.168.0.113', 'web', 'in', '2020-03-18 09:24:28', '2020-03-18 09:24:28'),
(36, 1, '192.168.0.106', 'web', 'in', '2020-03-18 09:50:22', '2020-03-18 09:50:22'),
(37, 1, '192.168.0.112', 'web', 'out', '2020-03-18 10:57:36', '2020-03-18 14:41:05'),
(38, 1, '192.168.0.106', 'web', 'in', '2020-03-19 05:01:31', '2020-03-19 05:01:31'),
(39, 1, '192.168.0.113', 'web', 'in', '2020-03-19 05:14:54', '2020-03-19 05:14:54'),
(40, 1, '192.168.0.112', 'web', 'out', '2020-03-19 06:05:02', '2020-03-19 06:29:49'),
(41, 1, '192.168.0.112', 'web', 'in', '2020-03-19 06:29:57', '2020-03-19 06:29:57'),
(42, 1, '192.168.0.113', 'web', 'in', '2020-03-19 09:30:02', '2020-03-19 09:30:02'),
(43, 1, '192.168.0.106', 'web', 'in', '2020-03-19 10:35:36', '2020-03-19 10:35:36'),
(44, 1, '127.0.0.1', 'web', 'out', '2020-03-23 05:40:36', '2020-03-23 05:49:56'),
(45, 1, '127.0.0.1', 'web', 'in', '2020-03-23 06:07:46', '2020-03-23 06:07:46'),
(46, 1, '127.0.0.1', 'web', 'out', '2020-07-26 06:51:51', '2020-07-26 09:59:20'),
(47, 1, '127.0.0.1', 'web', 'in', '2020-07-26 09:59:24', '2020-07-26 09:59:24'),
(48, 1, '127.0.0.1', 'web', 'in', '2020-07-27 05:18:55', '2020-07-27 05:18:55'),
(49, 1, '127.0.0.1', 'web', 'in', '2020-07-28 04:19:42', '2020-07-28 04:19:42'),
(50, 1, '127.0.0.1', 'web', 'in', '2020-07-28 09:16:11', '2020-07-28 09:16:11'),
(51, 1, '127.0.0.1', 'web', 'in', '2020-07-29 05:15:20', '2020-07-29 05:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 or 1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `phone`, `username`, `email`, `password`, `thumbnail`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Jayanta Biswas', '01775219457', 'bjayanta', 'bjayanta.me@gmail.com', '$2y$10$/z7b8pAxERc9Jg0MD506xu4qWXW3tvzgt9FAjZ5k6SE3mOardojnC', NULL, 1, '47spAvtYRMqF2IJYZ40AWFiUHlqcOCzpOclFmOEGnirreKjMiAHIt5QZPDRu', '2018-12-11 21:36:17', '2019-10-16 09:47:49'),
(3, 'Mobarak Hossain', '01701028220', 'mobarak-hossain', 'mobarak.hossain.cse12@gmail.com', '$2y$10$2j6.fes7CxsAx3/gnHMHKe/Rsgm6RN28mu4zOIgb.MGcn96NTfUOm', NULL, 1, 'OoYdpuI9CTXPYhQQmRNT1X9QE2IjuzX3tvIP8UhKA6BSwPnNTKYpB1DFJg3H', '2019-01-04 15:17:02', '2019-12-08 10:16:34'),
(7, 'Shibbir Ahmed', '01766263681', 'shibbirweb', 'shibbirweb@gmail.com', '$2y$10$EFloWEj6q8JLGowgqhFO2O7/8MQ5qXK/BbLoGL/crPO/JMab5x6DG', NULL, 1, 'CYrujQwwpTAGNi5brU1eEscGGFA3LeS7r528wG8mHZfRbfTZKfYvERSK5Q17', '2019-09-29 09:22:34', '2019-09-29 09:22:34'),
(10, 'Amor Chandra', '01617555112', 'rtr.amor', 'rtr.amor@gmail.com', '$2y$10$/z7b8pAxERc9Jg0MD506xu4qWXW3tvzgt9FAjZ5k6SE3mOardojnC', NULL, 1, 'uj4G5GUmYIx5FnFtoFKzl99INk76nXtmJO1Zm8sFxQk2QIMKtLuQToYmqQGz', '2019-10-02 10:54:40', '2019-10-13 12:51:54'),
(11, 'Md Ikramuzzaman Akand', '01786494650', 'ikram.akand', 'ikram.akand@gmail.com', '$2y$10$oLMzeRo3q/2m2obcTFsdc.gSYsHtYvPGOgVIAnE9gMRa34HZZepgK', NULL, 1, 'B1586Qzqj1QgUpsWt51CeXevm7Jr2ZoaQiS0WYYjUrC4W88JfpAKBg7qgY4W', '2019-10-15 11:15:30', '2019-10-15 11:15:30'),
(13, 'Antor Biswas', '01798568344', 'antor_biswas', 'tokdoikg150@gmail.com', '$2y$10$sd0tEFdIWRKP9WBRcOd6NOgddXQ3/Kq70AWtYrhNBnMGgTvArCyw.', '1571294306567', 1, 'vFjDOCS5lE0vLBj7y4pQYSgQpKroMslD40NzHAfmeaSjDfwzsl7ys1uf7lSm', '2019-10-30 06:13:43', '2019-10-30 06:13:43'),
(14, 'Baky Billah', '01749019633', 'baky', 'bakybillah63@gmail.com', '$2y$10$/z7b8pAxERc9Jg0MD506xu4qWXW3tvzgt9FAjZ5k6SE3mOardojnC', NULL, 1, 'igD9ZkmYo0bboOomTJzQkOM8Vm70kvFHk3ZWgixwx7C9npEZ3sl4mGRLH3eg', '2018-12-11 21:36:17', '2019-10-16 09:47:49'),
(15, 'Maruf Hasan', '01735189237', 'marufhasan', 'emarufhasan@gmail.com', '$2y$10$/z7b8pAxERc9Jg0MD506xu4qWXW3tvzgt9FAjZ5k6SE3mOardojnC', NULL, 1, 'gVyxCXIzF5qo3FBWshYE33LmyhQ1PxjyjeTMGtd1DOrhpNG6MHgqBiXXVv6g', '2018-12-11 21:36:17', '2019-10-16 09:47:49'),
(16, 'Shohoz Sales', '01318601301', 'shohozadmin', 'admin@shohozsales.com', '$2y$10$/z7b8pAxERc9Jg0MD506xu4qWXW3tvzgt9FAjZ5k6SE3mOardojnC', '1578199339901', 1, 'KcS3V6vec4OMNm3f4aRZYt2hvRgaigdeNujaDQSxvTcEcRUXifd3FSD7rcmo', '2018-12-11 21:36:17', '2020-01-05 05:07:07'),
(17, 'S.M Jasim Uddin', '01781841174', 'jasim_uddin', 'juddin4444@gmail.com', '$2y$10$/z7b8pAxERc9Jg0MD506xu4qWXW3tvzgt9FAjZ5k6SE3mOardojnC', NULL, 1, 'VYceNipk5j2tlmgkZCTxLfhkTjkPgzvE84s59xG9naxW3rUNcGrO4H7Wu2HJ', '2018-12-11 21:36:17', '2019-10-16 09:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `advanced_salaries`
--

CREATE TABLE `advanced_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `adv_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `installment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 or 1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `balance_transfers`
--

CREATE TABLE `balance_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_date` date NOT NULL,
  `transfer_from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bank/cash',
  `transfer_from_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bank account/cash id',
  `transfer_to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bank/cash',
  `transfer_to_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bank account/cash id',
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_issue_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator` int(11) UNSIGNED NOT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `code`, `name`, `slug`, `status`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'BNK0001', 'Bangladesh Bank', 'bangladesh-bank', 1, 1, NULL, '2020-03-19 06:46:10', '2020-03-19 06:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `bank_id`, `account_name`, `account_number`, `branch`, `type`, `balance`, `note`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Joy', '21564894842315', 'Mymensingh', '1', '7300.00', NULL, 1, NULL, '2020-03-19 06:46:38', '2020-07-29 08:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `code`, `party_id`, `name`, `slug`, `active`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'BRA000001', 1, 'RFL Garden Chair', 'rfl-garden-chair', 1, 1, NULL, '2020-03-12 04:53:21', '2020-03-12 04:53:21'),
(2, 'BRA000002', 1, 'RFL Tiles', 'rfl-tiles', 1, 1, NULL, '2020-03-15 10:19:13', '2020-03-15 10:19:13'),
(3, 'BRA000003', 4, 'Blue Dream', 'blue-dream', 1, 1, NULL, '2020-03-15 12:34:42', '2020-03-15 12:34:42'),
(4, 'BRA000004', 1, 'Test', 'test', 1, 1, NULL, '2020-07-28 06:59:14', '2020-07-28 06:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `brand_category`
--

CREATE TABLE `brand_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand_category`
--

INSERT INTO `brand_category` (`id`, `brand_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 1, NULL, NULL),
(5, 4, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `broken_commissions`
--

CREATE TABLE `broken_commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'media code',
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `thumbnail`, `address`, `phone`, `email`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Test Business', '1577940963502', '5/B Green house, Mymensingh', '01701028220', 'test@gmail.com', NULL, NULL, '2020-02-16 11:17:20', '2020-02-16 11:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `cashes`
--

CREATE TABLE `cashes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Main',
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'main',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cashes`
--

INSERT INTO `cashes` (`id`, `title`, `slug`, `amount`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Main Cash', 'main-cash', '10029320.00', 1, NULL, '2020-01-15 06:23:03', '2020-03-23 11:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `code`, `name`, `slug`, `description`, `active`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'CAT000001', 'Furniture', 'furniture', NULL, 1, 1, NULL, '2020-03-12 04:52:05', '2020-03-12 04:52:52'),
(2, 'CAT000002', 'Tiles', 'tiles', NULL, 1, 1, NULL, '2020-03-15 10:18:45', '2020-03-15 10:18:45'),
(3, 'CAT000003', 'Jeans', 'jeans', NULL, 1, 1, NULL, '2020-03-15 12:33:38', '2020-03-15 12:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `damage_stocks`
--

CREATE TABLE `damage_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `due_manages`
--

CREATE TABLE `due_manages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'supplier paid means given amount to supplier, customer paid means taken amount from customer.For receive vice versa',
  `cash_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `check_issue_date` date DEFAULT NULL,
  `check_number` decimal(50,2) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenditures`
--

CREATE TABLE `expenditures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gl_account_id` bigint(20) UNSIGNED NOT NULL,
  `gl_account_head_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `cash_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenditures`
--

INSERT INTO `expenditures` (`id`, `gl_account_id`, `gl_account_head_id`, `date`, `amount`, `cash_id`, `bank_id`, `bank_account_id`, `note`, `user_id`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2020-03-19', '100.00', 1, NULL, NULL, NULL, 1, 1, '2020-03-19 05:19:10', '2020-03-19 05:19:10'),
(2, 3, 2, '2020-03-19', '500.00', 1, NULL, NULL, NULL, 1, 1, '2020-03-19 05:21:49', '2020-03-19 05:21:49'),
(3, 2, 1, '2020-03-19', '200.00', NULL, 1, 1, NULL, 1, 1, '2020-03-19 06:46:58', '2020-03-19 06:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `gl_accounts`
--

CREATE TABLE `gl_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gl_accounts`
--

INSERT INTO `gl_accounts` (`id`, `code`, `name`, `description`, `operator_id`, `status`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 'GL0001', 'Office Cost', NULL, 1, 1, 4, '2019-12-31 05:27:50', '2019-12-31 05:27:50'),
(2, 'GL0002', 'Office Cost', NULL, 1, 1, 1, '2020-01-30 06:22:14', '2020-01-30 06:22:14'),
(3, 'GL0003', 'Transport Cost', NULL, 1, 1, 1, '2020-03-04 07:34:42', '2020-03-04 07:34:42');

-- --------------------------------------------------------

--
-- Table structure for table `gl_account_heads`
--

CREATE TABLE `gl_account_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gl_account_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operator_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gl_account_heads`
--

INSERT INTO `gl_account_heads` (`id`, `code`, `name`, `type`, `gl_account_id`, `description`, `operator_id`, `status`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 'GLH0001', 'Employee Cost', 'expense', 2, NULL, 1, 1, 1, '2020-01-30 06:22:40', '2020-01-30 06:22:40'),
(2, 'GLH0002', 'GAS Bill', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:35:13', '2020-03-04 07:35:13'),
(3, 'GLH0003', 'Load Labor', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:35:29', '2020-03-04 07:35:29'),
(4, 'GLH0004', 'Unload labor', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:35:38', '2020-03-04 07:35:38'),
(5, 'GLH0005', 'Factory Extra', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:35:52', '2020-03-04 07:35:52'),
(6, 'GLH0006', 'Driver Expense', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:36:04', '2020-03-04 07:36:04'),
(7, 'GLH0007', 'Car Premium', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:36:15', '2020-03-04 07:36:15'),
(8, 'GLH0008', 'Labor Welfare', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:36:25', '2020-03-04 07:36:25'),
(9, 'GLH0009', 'Paura shabha', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:36:43', '2020-03-04 07:36:43'),
(10, 'GLH0010', 'Police', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:37:01', '2020-03-04 07:37:01'),
(11, 'GLH0011', 'Car expense', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:37:11', '2020-03-04 07:37:11'),
(12, 'GLH0012', 'Tool', 'expense', 3, NULL, 1, 1, 1, '2020-03-04 07:37:30', '2020-03-04 07:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `installment_pays`
--

CREATE TABLE `installment_pays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advanced_salary_id` bigint(20) UNSIGNED NOT NULL,
  `inst_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ledgerables`
--

CREATE TABLE `ledgerables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ledger_id` bigint(20) UNSIGNED NOT NULL,
  `ledgerable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ledgerable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ledgerables`
--

INSERT INTO `ledgerables` (`id`, `ledger_id`, `ledgerable_type`, `ledgerable_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Cash', 1, NULL, NULL),
(2, 2, 'App\\Models\\Cash', 1, NULL, NULL),
(3, 3, 'App\\Models\\Party', 8, NULL, NULL),
(4, 4, 'App\\Models\\Cash', 1, NULL, NULL),
(5, 5, 'App\\Models\\Party', 3, NULL, NULL),
(6, 6, 'App\\Models\\Cash', 1, NULL, NULL),
(7, 7, 'App\\Models\\Cash', 1, NULL, NULL),
(8, 8, 'App\\Models\\Cash', 1, NULL, NULL),
(9, 9, 'App\\Models\\Cash', 1, NULL, NULL),
(10, 10, 'App\\Models\\Cash', 1, NULL, NULL),
(11, 11, 'App\\Models\\Cash', 1, NULL, NULL),
(12, 12, 'App\\Models\\Cash', 1, NULL, NULL),
(13, 13, 'App\\Models\\Party', 8, NULL, NULL),
(14, 14, 'App\\Models\\Cash', 1, NULL, NULL),
(15, 15, 'App\\Models\\Cash', 1, NULL, NULL),
(16, 16, 'App\\Models\\Cash', 1, NULL, NULL),
(17, 17, 'App\\Models\\Cash', 1, NULL, NULL),
(18, 18, 'App\\Models\\Cash', 1, NULL, NULL),
(19, 19, 'App\\Models\\Party', 7, NULL, NULL),
(20, 20, 'App\\Models\\Cash', 1, NULL, NULL),
(21, 21, 'App\\Models\\Cash', 1, NULL, NULL),
(22, 22, 'App\\Models\\Cash', 1, NULL, NULL),
(23, 23, 'App\\Models\\Cash', 1, NULL, NULL),
(24, 24, 'App\\Models\\Cash', 1, NULL, NULL),
(25, 25, 'App\\Models\\Cash', 1, NULL, NULL),
(26, 26, 'App\\Models\\Cash', 1, NULL, NULL),
(27, 27, 'App\\Models\\Cash', 1, NULL, NULL),
(28, 28, 'App\\Models\\Cash', 1, NULL, NULL),
(29, 29, 'App\\Models\\Cash', 1, NULL, NULL),
(30, 30, 'App\\Models\\Cash', 1, NULL, NULL),
(31, 31, 'App\\Models\\Party', 9, NULL, NULL),
(32, 32, 'App\\Models\\Cash', 1, NULL, NULL),
(33, 33, 'App\\Models\\Party', 7, NULL, NULL),
(34, 34, 'App\\Models\\Cash', 1, NULL, NULL),
(35, 35, 'App\\Models\\Party', 7, NULL, NULL),
(36, 36, 'App\\Models\\Cash', 1, NULL, NULL),
(37, 37, 'App\\Models\\Party', 7, NULL, NULL),
(38, 38, 'App\\Models\\Cash', 1, NULL, NULL),
(39, 39, 'App\\Models\\Party', 7, NULL, NULL),
(40, 40, 'App\\Models\\Cash', 1, NULL, NULL),
(41, 41, 'App\\Models\\Party', 8, NULL, NULL),
(42, 42, 'App\\Models\\Cash', 1, NULL, NULL),
(43, 43, 'App\\Models\\Cash', 1, NULL, NULL),
(44, 44, 'App\\Models\\Cash', 1, NULL, NULL),
(45, 45, 'App\\Models\\Cash', 1, NULL, NULL),
(46, 46, 'App\\Models\\Party', 8, NULL, NULL),
(47, 47, 'App\\Models\\Cash', 1, NULL, NULL),
(48, 48, 'App\\Models\\Party', 8, NULL, NULL),
(49, 49, 'App\\Models\\Cash', 1, NULL, NULL),
(50, 50, 'App\\Models\\Party', 8, NULL, NULL),
(51, 51, 'App\\Models\\Cash', 1, NULL, NULL),
(52, 52, 'App\\Models\\Party', 7, NULL, NULL),
(53, 53, 'App\\Models\\Cash', 1, NULL, NULL),
(54, 54, 'App\\Models\\Party', 8, NULL, NULL),
(55, 55, 'App\\Models\\Cash', 1, NULL, NULL),
(56, 56, 'App\\Models\\Party', 7, NULL, NULL),
(57, 57, 'App\\Models\\Cash', 1, NULL, NULL),
(58, 58, 'App\\Models\\Cash', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ledgers`
--

CREATE TABLE `ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ledgers`
--

INSERT INTO `ledgers` (`id`, `date`, `description`, `debit`, `credit`, `balance`, `created_at`, `updated_at`) VALUES
(1, '2020-03-15', 'Product sell', '4000.00', '0.00', '10045400.00', '2020-03-15 05:12:57', '2020-03-15 05:12:57'),
(2, '2020-03-15', 'Product sell', '3000.00', '0.00', '10048400.00', '2020-03-15 10:42:49', '2020-03-15 10:42:49'),
(3, '2020-03-17', 'Product purchase', '0.00', '3000.00', '7000.00', '2020-03-17 04:57:42', '2020-03-17 04:57:42'),
(4, '2020-03-17', 'Product sell', '0.00', '0.00', '10018400.00', '2020-03-17 04:57:42', '2020-03-17 04:57:42'),
(5, '2020-03-17', 'Product purchase', '0.00', '1100.00', '-1100.00', '2020-03-17 05:16:18', '2020-03-17 05:16:18'),
(6, '2020-03-17', 'Product sell', '0.00', '0.00', '10018400.00', '2020-03-17 05:16:18', '2020-03-17 05:16:18'),
(7, '2020-03-17', 'Product sell', '1950.00', '0.00', '10020350.00', '2020-03-17 12:54:52', '2020-03-17 12:54:52'),
(8, '2020-03-18', 'Product sell', '600.00', '0.00', '10020950.00', '2020-03-18 04:55:54', '2020-03-18 04:55:54'),
(9, '2020-03-18', 'Product sell', '1750.00', '0.00', '10022700.00', '2020-03-18 05:00:37', '2020-03-18 05:00:37'),
(10, '2020-03-18', 'Product sell', '150.00', '0.00', '10022850.00', '2020-03-18 05:31:25', '2020-03-18 05:31:25'),
(11, '2020-03-18', 'Product sell', '60.00', '0.00', '10022910.00', '2020-03-18 05:32:31', '2020-03-18 05:32:31'),
(12, '2020-03-18', 'Product sell', '150.00', '0.00', '10023060.00', '2020-03-18 05:37:17', '2020-03-18 05:37:17'),
(13, '2020-03-18', 'Product purchase', '0.00', '10.00', '7020.00', '2020-03-18 06:31:18', '2020-03-18 06:31:18'),
(14, '2020-03-18', 'Product sell', '50.00', '0.00', '10023110.00', '2020-03-18 06:31:19', '2020-03-18 06:31:19'),
(15, '2020-03-18', 'Product sell', '150.00', '0.00', '10023260.00', '2020-03-18 06:33:03', '2020-03-18 06:33:03'),
(16, '2020-03-18', 'Product sell', '150.00', '0.00', '10023410.00', '2020-03-18 06:36:28', '2020-03-18 06:36:28'),
(17, '2020-03-18', 'Product sell', '90.00', '0.00', '10023500.00', '2020-03-18 06:36:56', '2020-03-18 06:36:56'),
(18, '2020-03-18', 'Product sell', '60.00', '0.00', '10023560.00', '2020-03-18 06:54:56', '2020-03-18 06:54:56'),
(19, '2020-03-18', 'Product purchase', '0.00', '20.00', '-52.00', '2020-03-18 06:55:19', '2020-03-18 06:55:19'),
(20, '2020-03-18', 'Product sell', '40.00', '0.00', '10023600.00', '2020-03-18 06:55:19', '2020-03-18 06:55:19'),
(21, '2020-03-18', 'Product sell', '90.00', '0.00', '10023690.00', '2020-03-18 06:55:55', '2020-03-18 06:55:55'),
(22, '2020-03-18', 'Product sell', '210.00', '0.00', '10023900.00', '2020-03-18 06:58:25', '2020-03-18 06:58:25'),
(23, '2020-03-18', 'Product sell', '90.00', '0.00', '10023990.00', '2020-03-18 06:59:01', '2020-03-18 06:59:01'),
(24, '2020-03-18', 'Product sell', '90.00', '0.00', '10024080.00', '2020-03-18 07:00:15', '2020-03-18 07:00:15'),
(25, '2020-03-18', 'Product sell', '60.00', '0.00', '10024140.00', '2020-03-18 09:25:21', '2020-03-18 09:25:21'),
(26, '2020-03-18', 'Product sell', '90.00', '0.00', '10024230.00', '2020-03-18 09:26:22', '2020-03-18 09:26:22'),
(27, '2020-03-18', 'Product sell', '90.00', '0.00', '10024320.00', '2020-03-18 09:26:50', '2020-03-18 09:26:50'),
(28, '2020-03-18', 'Product sell', '90.00', '0.00', '10024410.00', '2020-03-18 09:27:15', '2020-03-18 09:27:15'),
(29, '2020-03-18', 'Product sell', '90.00', '0.00', '10024500.00', '2020-03-18 09:29:04', '2020-03-18 09:29:04'),
(30, '2020-03-18', 'Product sell', '90.00', '0.00', '10024590.00', '2020-03-18 09:43:16', '2020-03-18 09:43:16'),
(31, '2020-03-18', 'Product purchase', '0.00', '40.00', '-50040.00', '2020-03-18 09:44:01', '2020-03-18 09:44:01'),
(32, '2020-03-18', 'Product sell', '50.00', '0.00', '10024640.00', '2020-03-18 09:44:02', '2020-03-18 09:44:02'),
(33, '2020-03-18', 'Product purchase', '0.00', '70.00', '-102.00', '2020-03-18 09:44:50', '2020-03-18 09:44:50'),
(34, '2020-03-18', 'Product sell', '20.00', '0.00', '10024660.00', '2020-03-18 09:44:50', '2020-03-18 09:44:50'),
(35, '2020-03-18', 'Product purchase', '0.00', '70.00', '-172.00', '2020-03-18 09:45:30', '2020-03-18 09:45:30'),
(36, '2020-03-18', 'Product sell', '20.00', '0.00', '10024680.00', '2020-03-18 09:45:30', '2020-03-18 09:45:30'),
(37, '2020-03-18', 'Product purchase', '0.00', '30.00', '-202.00', '2020-03-18 09:46:16', '2020-03-18 09:46:16'),
(38, '2020-03-18', 'Product sell', '120.00', '0.00', '10024800.00', '2020-03-18 09:46:16', '2020-03-18 09:46:16'),
(39, '2020-03-18', 'Product purchase', '0.00', '50.00', '-252.00', '2020-03-18 09:46:43', '2020-03-18 09:46:43'),
(40, '2020-03-18', 'Product sell', '100.00', '0.00', '10024900.00', '2020-03-18 09:46:43', '2020-03-18 09:46:43'),
(41, '2020-03-18', 'Product purchase', '0.00', '40.00', '7020.00', '2020-03-18 10:07:56', '2020-03-18 10:07:56'),
(42, '2020-03-18', 'Product sell', '50.00', '0.00', '10024950.00', '2020-03-18 10:07:56', '2020-03-18 10:07:56'),
(43, '2020-03-18', 'Product sell', '90.00', '0.00', '10025040.00', '2020-03-18 10:09:01', '2020-03-18 10:09:01'),
(44, '2020-03-18', 'Product sell', '90.00', '0.00', '10025130.00', '2020-03-18 10:09:27', '2020-03-18 10:09:27'),
(45, '2020-03-18', 'Product sell', '90.00', '0.00', '10025220.00', '2020-03-18 10:17:08', '2020-03-18 10:17:08'),
(46, '2020-03-18', 'Product purchase', '0.00', '90.00', '6930.00', '2020-03-18 10:22:41', '2020-03-18 10:22:41'),
(47, '2020-03-18', 'Product sell', '90.00', '0.00', '10025310.00', '2020-03-18 10:27:05', '2020-03-18 10:27:05'),
(48, '2020-03-18', 'Product purchase', '0.00', '4200.00', '2730.00', '2020-03-18 10:30:05', '2020-03-18 10:30:05'),
(49, '2020-03-18', 'Product sell', '0.00', '0.00', '10025310.00', '2020-03-18 10:30:05', '2020-03-18 10:30:05'),
(50, '2020-03-18', 'Product purchase', '0.00', '2730.00', '0.00', '2020-03-18 10:39:30', '2020-03-18 10:39:30'),
(51, '2020-03-18', 'Product sell', '270.00', '0.00', '10025580.00', '2020-03-18 10:39:31', '2020-03-18 10:39:31'),
(52, '2020-03-18', 'Product purchase', '0.00', '60.00', '-292.00', '2020-03-18 10:52:26', '2020-03-18 10:52:26'),
(53, '2020-03-18', 'Product sell', '0.00', '0.00', '10025580.00', '2020-03-18 10:52:26', '2020-03-18 10:52:26'),
(54, '2020-03-19', 'Product purchase', '0.00', '2300.00', '-2300.00', '2020-03-19 11:22:38', '2020-03-19 11:22:38'),
(55, '2020-03-19', 'Product sell', '0.00', '0.00', '10024920.00', '2020-03-19 11:22:38', '2020-03-19 11:22:38'),
(56, '2020-03-19', 'Product purchase', '0.00', '60.00', '-352.00', '2020-03-19 11:26:34', '2020-03-19 11:26:34'),
(57, '2020-03-19', 'Product sell', '0.00', '0.00', '10024920.00', '2020-03-19 11:26:35', '2020-03-19 11:26:35'),
(58, '2020-03-23', 'Product sell', '5500.00', '0.00', '10030420.00', '2020-03-23 06:23:51', '2020-03-23 06:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `real_path` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `absolute_path` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'with http',
  `mime_type` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` decimal(10,2) DEFAULT NULL,
  `extension` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `code`, `title`, `description`, `file_path`, `real_path`, `absolute_path`, `mime_type`, `size`, `extension`, `created_at`, `updated_at`) VALUES
(4, '1595844358570', 'Shibbir', 'Shibbir', 'upload/media/ssT25CnCvm5Ts8a9QLT6V13ior3cd4u6MSjmTtSl.jpeg', 'public/storage/upload/media/ssT25CnCvm5Ts8a9QLT6V13ior3cd4u6MSjmTtSl.jpeg', 'http://inventory.test/public/storage/upload/media/ssT25CnCvm5Ts8a9QLT6V13ior3cd4u6MSjmTtSl.jpeg', 'image/jpeg', '865220.00', 'jpg', '2020-07-27 10:05:58', '2020-07-27 10:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'dashboard', ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis eligendi sint exercitationem similique laudantium ipsum, eius, facere ipsa recusandae distinctio fugit quod reprehenderit magni tempore. Accusantium fugit laboriosam aliquam voluptas!', NULL, NULL),
(2, 'Media', 'media', ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis eligendi sint exercitationem similique laudantium ipsum, eius, facere ipsa recusandae distinctio fugit quod reprehenderit magni tempore. Accusantium fugit laboriosam aliquam voluptas!', NULL, NULL),
(3, 'Cash', 'cash', NULL, NULL, NULL),
(4, 'Bank', 'bank', NULL, NULL, NULL),
(5, 'Bank Account', 'bank-account', NULL, NULL, NULL),
(6, 'Balance Transfer', 'balance-transfer', NULL, NULL, NULL),
(7, 'Expenditure', 'expenditure', NULL, NULL, NULL),
(8, 'Stock', 'stock', NULL, NULL, NULL),
(9, 'Damage Stock', 'damage-stock', NULL, NULL, NULL),
(10, 'Supplier', 'supplier', NULL, NULL, NULL),
(11, 'Brand', 'brand', NULL, NULL, NULL),
(12, 'Category', 'category', NULL, NULL, NULL),
(13, 'Product', 'product', NULL, NULL, NULL),
(14, 'Warehouse', 'warehouse', NULL, NULL, NULL),
(15, 'Unit', 'unit', NULL, NULL, NULL),
(16, 'Purchase', 'purchase', NULL, NULL, NULL),
(17, 'POS', 'pos', NULL, NULL, NULL),
(18, 'Business', 'business', NULL, '2019-12-07 18:00:00', '2019-12-07 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `metas`
--

CREATE TABLE `metas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `metaable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metaable_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `metas`
--

INSERT INTO `metas` (`id`, `metaable_type`, `metaable_id`, `meta_key`, `meta_value`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Party', 1, 'contact_person', NULL, NULL, '2020-03-11 11:34:46', '2020-03-11 11:34:46'),
(2, 'App\\Models\\Party', 1, 'contact_person_phone', NULL, NULL, '2020-03-11 11:34:46', '2020-03-11 11:34:46'),
(3, 'App\\Models\\Party', 2, 'contact_person', 'Officia molestiae do', NULL, '2020-03-12 05:47:55', '2020-03-12 05:47:55'),
(4, 'App\\Models\\Party', 2, 'contact_person_phone', '+1 (697) 125-6454', NULL, '2020-03-12 05:47:55', '2020-03-12 05:47:55'),
(5, 'App\\Models\\Party', 4, 'contact_person', NULL, NULL, '2020-03-15 12:34:10', '2020-03-15 12:34:10'),
(6, 'App\\Models\\Party', 4, 'contact_person_phone', NULL, NULL, '2020-03-15 12:34:10', '2020-03-15 12:34:10'),
(7, 'App\\Models\\Party', 6, 'contact_person', 'Dolore suscipit rem', NULL, '2020-03-16 10:11:56', '2020-03-16 10:11:56'),
(8, 'App\\Models\\Party', 6, 'contact_person_phone', '+1 (197) 228-9038', NULL, '2020-03-16 10:11:56', '2020-03-16 10:11:56'),
(9, 'App\\Models\\Party', 7, 'contact_person', 'Qui ducimus explica', NULL, '2020-03-16 10:15:41', '2020-03-16 10:15:41'),
(10, 'App\\Models\\Party', 7, 'contact_person_phone', '+1 (124) 291-6992', NULL, '2020-03-16 10:15:41', '2020-03-16 10:15:41'),
(11, 'App\\Models\\Party', 8, 'contact_person', NULL, NULL, '2020-03-17 04:54:21', '2020-03-17 04:54:21'),
(12, 'App\\Models\\Party', 8, 'contact_person_phone', NULL, NULL, '2020-03-17 04:54:21', '2020-03-17 04:54:21'),
(13, 'App\\Models\\Party', 9, 'contact_person', NULL, NULL, '2020-03-17 06:29:35', '2020-03-17 06:29:35'),
(14, 'App\\Models\\Party', 9, 'contact_person_phone', NULL, NULL, '2020-03-17 06:29:36', '2020-03-17 06:29:36'),
(15, 'App\\Models\\Party', 10, 'contact_person', NULL, NULL, '2020-03-17 06:37:06', '2020-03-17 06:37:06'),
(16, 'App\\Models\\Party', 10, 'contact_person_phone', NULL, NULL, '2020-03-17 06:37:06', '2020-03-17 06:37:06'),
(17, 'App\\Models\\Party', 12, 'contact_person', 'Sunt facere aut dol', NULL, '2020-07-27 09:40:50', '2020-07-27 09:40:50'),
(18, 'App\\Models\\Party', 12, 'contact_person_phone', '+1 (622) 332-7444', NULL, '2020-07-27 09:40:50', '2020-07-27 09:40:50'),
(19, 'App\\Models\\User\\User', 1, 'dob', '1970-01-01', NULL, '2020-07-27 10:06:26', '2020-07-27 10:06:26'),
(20, 'App\\Models\\User\\User', 1, 'address', NULL, NULL, '2020-07-27 10:06:26', '2020-07-27 10:06:26');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_12_01_102758_create_admins_table', 2),
(6, '2016_06_01_000001_create_oauth_auth_codes_table', 5),
(7, '2016_06_01_000002_create_oauth_access_tokens_table', 5),
(8, '2016_06_01_000003_create_oauth_refresh_tokens_table', 5),
(9, '2016_06_01_000004_create_oauth_clients_table', 5),
(10, '2016_06_01_000005_create_oauth_personal_access_clients_table', 5),
(13, '2019_02_24_074218_create_appdatas_table', 6),
(14, '2019_04_03_112459_create_accesslogs_table', 7),
(16, '2019_09_12_180940_create_cashes_table', 8),
(18, '2019_09_15_192511_create_metas_table', 10),
(23, '2019_09_16_124843_create_warehouses_table', 14),
(27, '2019_09_15_194303_create_media_table', 15),
(30, '2019_09_17_105747_create_banks_table', 17),
(33, '2019_09_17_110110_create_bank_accounts_table', 18),
(36, '2019_09_18_145151_create_brands_table', 19),
(38, '2019_09_19_114104_create_categories_table', 20),
(39, '2019_09_19_115241_create_units_table', 21),
(42, '2019_09_19_165546_create_products_table', 23),
(43, '2019_09_21_104936_create_balance_transfers_table', 24),
(44, '2019_09_21_135513_create_stocks_table', 25),
(45, '2019_09_21_183650_create_brand_category_table', 26),
(53, '2019_09_23_141000_create_ledgers_table', 31),
(57, '2019_09_29_174055_create_damage_stocks_table', 34),
(63, '2019_10_08_163531_create_sales_table', 36),
(64, '2019_10_08_170054_create_sale_details_table', 37),
(65, '2019_10_08_174216_create_sale_details_warehouses_table', 38),
(66, '2018_12_03_124720_create_menus_table', 39),
(68, '2018_12_03_124721_create_permissions_table', 40),
(69, '2018_12_03_124722_create_roles_table', 41),
(71, '2019_10_13_124617_create_purchases_table', 42),
(72, '2019_10_13_130258_create_purchase_details_table', 42),
(73, '2019_10_13_131749_create_purchase_quantities_table', 42),
(74, '2019_10_13_133257_create_gl_accounts_table', 43),
(75, '2019_10_14_111117_create_gl_account_heads_table', 44),
(76, '2019_10_28_111117_create_expenditures_table', 45),
(85, '2019_10_31_012403_create_sale_returns_table', 46),
(86, '2019_10_31_113010_create_sale_return_products_table', 46),
(87, '2019_10_31_113020_create_sale_return_quantities_table', 46),
(88, '2019_11_03_114517_create_sale_rest_quantities_table', 47),
(96, '2019_11_05_183219_create_due_manages_table', 50),
(106, '2019_11_09_172134_create_purchase_returns_table', 51),
(107, '2019_11_09_172256_create_purchase_return_products_table', 51),
(108, '2019_11_09_172327_create_purchase_return_quantities_table', 51),
(110, '2020_01_04_120258_create_cylinder_manages_table', 53),
(114, '2020_01_04_133001_create_settings_table', 54),
(115, '2019_11_05_180713_create_transfer_products_table', 60),
(116, '2020_01_25_114928_create_order_manages_table', 56),
(117, '2020_01_28_105140_create_product_transfers_table', 61),
(119, '2013_12_08_123744_create_businesses_table', 1),
(129, '2020_02_20_113353_create_advanced_salaries_table', 64),
(130, '2020_02_20_113958_create_installment_pays_table', 65),
(131, '2020_02_20_115601_create_salaries_table', 66),
(132, '2020_02_20_120617_create_salary_details_table', 67),
(133, '2020_03_01_152150_create_broken_commissions_table', 68),
(134, '2019_09_15_185842_create_parties_table', 69),
(135, '2020_07_27_184510_create_sale_payments_table', 70);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_manages`
--

CREATE TABLE `order_manages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genus` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'supplier,customer',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thana` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'supplier: positive(+) balance means receivable and negative(-) is payable, customer: positive(+) balance means payable and negative(-) is receivable',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `code`, `genus`, `name`, `description`, `phone`, `email`, `division`, `district`, `thana`, `address`, `thumbnail`, `balance`, `active`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PRT00000001', 'supplier', 'RFL', 'All kind of RFL product Supplier', '01701028220', 'rfl@gmail.com', 'Dhaka', 'Gazipur', 'Gazipur', '36/A Gazipur, Dhaka', NULL, '0.00', 1, 1, NULL, '2020-03-11 11:34:46', '2020-03-12 11:32:22'),
(2, 'PRT00000002', 'supplier', 'Minerva Crane', 'Dicta facilis volupt', '+1 (695) 908-6514', 'xatyny@mailinator.com', 'Temporibus molestiae', 'Inventore et cupidat', 'Non ea ullamco dolor', 'Voluptatem velit i', '32', '40.00', 1, 1, NULL, '2020-03-12 05:47:55', '2020-03-12 11:54:04'),
(3, 'PRT00000003', 'customer', 'Jayanta', NULL, '01775219457', NULL, NULL, NULL, NULL, 'abc', NULL, '-1100.00', 1, 1, NULL, '2020-03-15 05:12:21', '2020-03-17 05:16:18'),
(4, 'PRT00000004', 'supplier', 'Blue Dream', NULL, '01775219458', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', 1, 1, NULL, '2020-03-15 12:34:10', '2020-03-15 12:46:37'),
(6, 'PRT00000005', 'supplier', 'Beverly Cote', NULL, '01701028222', 'wali@mailinator.com', 'Saepe officia culpa', 'Illum dolorum odit', 'Ullam eum commodi qu', NULL, '83', '92.00', 1, 1, NULL, '2020-03-16 10:11:56', '2020-03-16 10:11:56'),
(7, 'PRT00000007', 'customer', 'Rosalyn Ewing', 'Tempore asperiores', '+1 (494) 735-3718', 'kasyc@mailinator.com', 'Ut aut delectus vol', 'Veritatis nihil pers', 'Nemo qui distinctio', 'Dolore ipsum velit', '48', '-352.00', 1, 1, NULL, '2020-03-16 10:15:41', '2020-03-19 11:26:34'),
(8, 'PRT00000008', 'customer', 'Shibbir', NULL, '01766263681', NULL, NULL, NULL, NULL, NULL, NULL, '12200.00', 1, 1, NULL, '2020-03-17 04:54:21', '2020-03-23 11:01:34'),
(9, 'PRT00000009', 'customer', 'Maruf Hasan', NULL, '01775219456', NULL, NULL, NULL, NULL, NULL, NULL, '-50040.00', 1, 1, NULL, '2020-03-17 06:29:35', '2020-03-18 09:44:01'),
(10, 'PRT00000010', 'supplier', 'Baky', NULL, '01775219453', NULL, NULL, NULL, NULL, NULL, NULL, '10000.00', 1, 1, NULL, '2020-03-17 06:37:06', '2020-03-17 06:37:06'),
(11, 'PRT00000011', 'customer', 'Unknown', NULL, '13267987', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', 1, 1, NULL, '2020-03-17 07:06:44', '2020-03-17 07:06:44'),
(12, 'PRT00000012', 'supplier', 'Benjamin Stuart', 'Nobis magnam ipsam e', '+1 (667) 176-8497', 'vucys@mailinator.com', 'Laboriosam fuga Id', 'Sint nemo fugiat ir', 'Dignissimos assumend', 'Fugiat quisquam debi', NULL, '89.00', 1, 1, NULL, '2020-07-27 09:40:50', '2020-07-27 09:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `wholesale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `retail_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_alert` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `barcode`, `model`, `name`, `slug`, `party_id`, `brand_id`, `category_id`, `unit_id`, `purchase_price`, `wholesale_price`, `retail_price`, `stock_alert`, `description`, `active`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PRD001', '0001', 'CH001', 'RFL Garden Chair (Small)', 'rfl-garden-chair-small', 1, 1, 1, 1, '500.00', '550.00', '600.00', '20.00', NULL, 1, 1, NULL, '2020-03-12 04:54:53', '2020-03-12 11:32:23'),
(2, 'PRD002', 'PRD002', NULL, 'RFL Garden Chair Green', 'rfl-garden-chair-green', 1, 1, 1, 1, '300.00', '350.00', '400.00', '50.00', NULL, 1, 1, NULL, '2020-03-15 07:31:59', '2020-03-15 07:31:59'),
(3, 'PRD003', 'PRD003', NULL, 'Red Chair', 'red-chair', 1, 1, 1, 1, '400.00', '450.00', '500.00', '50.00', NULL, 1, 1, NULL, '2020-03-15 07:32:04', '2020-03-15 07:32:04'),
(4, 'PRD004', 'PRD004', NULL, 'Khandany Chair', 'khandany-chair', 1, 1, 1, 1, '500.00', '550.00', '600.00', '300.00', NULL, 1, 1, NULL, '2020-03-15 07:33:04', '2020-03-15 07:33:04'),
(5, 'PRD005', 'PRD005', NULL, 'RFL Garden Chair Blue', 'rfl-garden-chair-blue', 1, 1, 1, 1, '280.00', '300.00', '350.00', '50.00', NULL, 1, 1, NULL, '2020-03-15 07:33:13', '2020-03-15 07:33:13'),
(6, 'PRD006', 'PRD006', NULL, 'RFL Black Wall Tiles', 'rfl-black-wall-tiles', 1, 2, 2, 4, '20.00', '25.00', '30.00', '20.00', NULL, 1, 1, NULL, '2020-03-15 10:20:20', '2020-03-15 10:20:20'),
(7, 'PRD007', 'PRD007', NULL, 'Blue Dream Narrow 32', 'blue-dream-narrow-32', 4, 3, 3, 1, '600.00', '1000.00', '1100.00', '50.00', NULL, 1, 1, NULL, '2020-03-15 12:41:19', '2020-03-15 12:41:19'),
(8, 'PRD008', 'PRD008', NULL, 'Blue Dream Narrow 34', 'blue-dream-narrow-34', 4, 3, 3, 1, '600.00', '1000.00', '1100.00', '50.00', NULL, 1, 1, NULL, '2020-03-15 12:43:43', '2020-03-15 12:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_transfers`
--

CREATE TABLE `product_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `to_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Supplier id',
  `cash_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_account_id` bigint(11) UNSIGNED DEFAULT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '''''''percentage''''''' COMMENT 'percentage/flat',
  `paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `party_id`, `cash_id`, `bank_account_id`, `voucher_no`, `date`, `subtotal`, `discount`, `discount_type`, `paid`, `note`, `business_id`, `created_at`, `updated_at`) VALUES
(3, 1, 1, NULL, '52543275', '2020-03-12', '50000.00', '0.00', 'flat', '60000.00', NULL, 1, '2020-03-12 11:32:22', '2020-03-12 11:32:22'),
(5, 4, 1, NULL, '1007', '2020-03-15', '30000.00', '0.00', 'flat', '30000.00', NULL, 1, '2020-03-15 12:46:37', '2020-03-15 12:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `line_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`id`, `purchase_id`, `product_id`, `line_total`, `purchase_price`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '50000.00', '500.00', '2020-03-12 11:32:23', '2020-03-12 11:32:23'),
(2, 5, 8, '30000.00', '600.00', '2020-03-15 12:46:37', '2020-03-15 12:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_quantities`
--

CREATE TABLE `purchase_quantities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_details_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `free_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_quantities`
--

INSERT INTO `purchase_quantities` (`id`, `purchase_details_id`, `warehouse_id`, `product_id`, `quantity`, `free_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 50, 0, '2020-03-12 11:32:23', '2020-03-12 11:32:23'),
(2, 1, 2, 1, 50, 0, '2020-03-12 11:32:23', '2020-03-12 11:32:23'),
(3, 2, 1, 8, 50, 0, '2020-03-15 12:46:37', '2020-03-15 12:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'operator id',
  `adjustment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `charge_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage' COMMENT 'flat/percentage',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_products`
--

CREATE TABLE `purchase_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `return_price` decimal(10,2) NOT NULL COMMENT 'per unit',
  `charge` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'per unit',
  `charge_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage' COMMENT 'flat/percentage',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_quantities`
--

CREATE TABLE `purchase_return_quantities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ex: Manager/Salesman/Marketer etc.',
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `salary_of_month_year` date NOT NULL,
  `given_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_details`
--

CREATE TABLE `salary_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_id` bigint(20) UNSIGNED NOT NULL,
  `purpose` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dtls_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `party_id` bigint(20) UNSIGNED NOT NULL COMMENT 'customer id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'operator id',
  `payment_type` enum('cash','bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'percentage',
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'flat/percentage',
  `tendered` decimal(10,2) NOT NULL DEFAULT 0.00,
  `due` decimal(10,2) NOT NULL DEFAULT 0.00,
  `change` decimal(10,2) NOT NULL DEFAULT 0.00,
  `customer_balance` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Customer balance after completing sale',
  `adjust_to_customer_balance` tinyint(1) NOT NULL COMMENT 'Status of adjust to customer balance. True for adjust to customer balance. False for not adjust to customer balance.',
  `business_id` int(20) UNSIGNED NOT NULL,
  `delivered` tinyint(1) NOT NULL COMMENT 'Delivery status of sale',
  `salesman_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Salesman id of user table',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_no`, `party_id`, `user_id`, `payment_type`, `subtotal`, `vat`, `discount`, `discount_type`, `tendered`, `due`, `change`, `customer_balance`, `adjust_to_customer_balance`, `business_id`, `delivered`, `salesman_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(43, 'INV0001', 11, 1, 'cash', '4000.00', '0.00', '0.00', 'flat', '4000.00', '0.00', '0.00', '0.00', 0, 1, 1, 1, NULL, '2020-07-29 08:02:14', '2020-07-29 08:02:14'),
(44, 'INV0044', 11, 1, 'cash', '4000.00', '0.00', '0.00', 'flat', '4000.00', '0.00', '0.00', '0.00', 0, 1, 1, 1, NULL, '2020-07-29 08:08:39', '2020-07-29 08:08:39'),
(45, 'INV0045', 11, 1, 'cash', '1000.00', '0.00', '0.00', 'flat', '1000.00', '0.00', '0.00', '0.00', 0, 1, 1, 1, NULL, '2020-07-29 08:09:40', '2020-07-29 08:09:40'),
(46, 'INV0046', 11, 1, 'bank', '2000.00', '0.00', '0.00', 'flat', '2000.00', '0.00', '0.00', '0.00', 0, 1, 1, 1, NULL, '2020-07-29 08:17:39', '2020-07-29 08:17:39'),
(47, 'INV0047', 11, 1, 'bank', '1000.00', '0.00', '0.00', 'flat', '1000.00', '0.00', '0.00', '0.00', 0, 1, 1, 1, NULL, '2020-07-29 08:19:45', '2020-07-29 08:19:45'),
(48, 'INV0048', 11, 1, 'bank', '2500.00', '0.00', '0.00', 'flat', '2500.00', '0.00', '0.00', '0.00', 0, 1, 1, 1, NULL, '2020-07-29 08:46:41', '2020-07-29 08:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

CREATE TABLE `sale_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL COMMENT 'per unit',
  `sale_price` decimal(10,2) NOT NULL COMMENT 'per unit',
  `sale_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'retail/wholesale',
  `discount` decimal(10,2) NOT NULL,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'flat/percentage',
  `line_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `sale_id`, `product_id`, `purchase_price`, `sale_price`, `sale_type`, `discount`, `discount_type`, `line_total`, `created_at`, `updated_at`) VALUES
(44, 44, 2, '300.00', '400.00', 'retail', '0.00', 'flat', '4000.00', '2020-07-29 08:08:39', '2020-07-29 08:08:39'),
(45, 45, 3, '400.00', '500.00', 'retail', '0.00', 'flat', '1000.00', '2020-07-29 08:09:41', '2020-07-29 08:09:41'),
(46, 46, 2, '300.00', '400.00', 'retail', '0.00', 'flat', '2000.00', '2020-07-29 08:17:40', '2020-07-29 08:17:40'),
(47, 47, 3, '400.00', '500.00', 'retail', '0.00', 'flat', '1000.00', '2020-07-29 08:19:45', '2020-07-29 08:19:45'),
(48, 48, 3, '400.00', '500.00', 'retail', '0.00', 'flat', '2500.00', '2020-07-29 08:46:41', '2020-07-29 08:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `sale_details_warehouses`
--

CREATE TABLE `sale_details_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_details_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_details_warehouses`
--

INSERT INTO `sale_details_warehouses` (`id`, `sale_id`, `sale_details_id`, `warehouse_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(45, 44, 44, 1, 2, 10, '2020-07-29 08:08:40', '2020-07-29 08:08:40'),
(46, 45, 45, 1, 3, 2, '2020-07-29 08:09:41', '2020-07-29 08:09:41'),
(47, 46, 46, 1, 2, 5, '2020-07-29 08:17:40', '2020-07-29 08:17:40'),
(48, 47, 47, 1, 3, 2, '2020-07-29 08:19:46', '2020-07-29 08:19:46'),
(49, 48, 48, 1, 3, 5, '2020-07-29 08:46:41', '2020-07-29 08:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'For cash',
  `bank_account_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'For bank',
  `cheque_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For bank',
  `issue_date` date DEFAULT NULL COMMENT 'For bank',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_payments`
--

INSERT INTO `sale_payments` (`id`, `sale_id`, `cash_id`, `bank_account_id`, `cheque_number`, `issue_date`, `created_at`, `updated_at`) VALUES
(1, 43, 1, NULL, NULL, NULL, '2020-07-29 08:02:14', '2020-07-29 08:02:14'),
(2, 44, 1, NULL, NULL, NULL, '2020-07-29 08:08:39', '2020-07-29 08:08:39'),
(3, 45, NULL, 1, NULL, '2020-07-29', '2020-07-29 08:09:41', '2020-07-29 08:09:41'),
(4, 46, NULL, 1, '123', '2020-07-29', '2020-07-29 08:17:39', '2020-07-29 08:17:39'),
(5, 47, NULL, 1, '123', '2020-07-29', '2020-07-29 08:19:45', '2020-07-29 08:19:45'),
(6, 48, NULL, 1, '1123145', '2020-07-29', '2020-07-29 08:46:41', '2020-07-29 08:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `sale_rest_quantities`
--

CREATE TABLE `sale_rest_quantities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_details_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'operator id',
  `adjustment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `charge_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage' COMMENT 'flat/percentage',
  `paid_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'cash/bank_account',
  `adjust_to_customer_balance` tinyint(1) NOT NULL COMMENT 'Adjust balance in customer balance',
  `customer_balance` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'User balance at this return state',
  `cash_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_products`
--

CREATE TABLE `sale_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `return_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'per unit',
  `charge` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'per unit',
  `charge_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage' COMMENT 'flat/percentage',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_quantities`
--

CREATE TABLE `sale_return_quantities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `warehouse_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 90, '2020-03-12 04:54:53', '2020-03-18 10:39:31'),
(2, 1, 2, 100, '2020-03-12 04:54:53', '2020-03-12 11:32:23'),
(3, 2, 1, 485, '2020-03-15 07:31:59', '2020-07-29 08:17:40'),
(4, 3, 1, 191, '2020-03-15 07:32:05', '2020-07-29 08:46:41'),
(5, 4, 2, 499, '2020-03-15 07:33:05', '2020-03-18 04:55:54'),
(6, 5, 1, 55, '2020-03-15 07:33:13', '2020-03-18 05:00:37'),
(7, 5, 2, 50, '2020-03-15 07:33:13', '2020-03-15 07:33:13'),
(8, 6, 1, 1442, '2020-03-15 10:20:20', '2020-03-19 11:26:35'),
(9, 6, 2, 1449, '2020-03-15 10:20:20', '2020-03-19 11:26:35'),
(10, 7, 1, 98, '2020-03-15 12:41:19', '2020-03-19 11:22:38'),
(11, 8, 1, 144, '2020-03-15 12:43:44', '2020-03-23 11:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_products`
--

CREATE TABLE `transfer_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `to_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `labels` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `labels`, `relation`, `description`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Piece', '0001', 'Piece', '1', '1 Piece', 1, NULL, '2020-01-15 06:02:38', '2020-01-15 06:02:38'),
(2, 'Box', '0002', 'Box/Kg', '1/50', '1 Box = 50 Kg', 1, NULL, '2020-01-15 09:59:57', '2020-01-15 09:59:57'),
(3, 'fghj', '0003', 'vgg', '15', 'fff', 1, '2020-01-28 06:14:04', '2020-01-28 06:14:01', '2020-01-28 06:14:04'),
(4, 'Box 8x12', '0003', 'Box/Pcs/SFT', '1/25/0.666666', '1Box = 25Pcs/0.666666SFT', 1, NULL, '2020-03-15 10:18:35', '2020-03-15 10:18:35'),
(5, 'Box 12x12', '0004', 'Box/Pcs/SFT', '1/20/0.666666', '1Box = 20Pcs/0.666666SFT', 1, NULL, '2020-03-15 10:18:35', '2020-03-15 10:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_id` int(20) UNSIGNED NOT NULL,
  `account_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `thumbnail` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activation_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 or 1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `username`, `email`, `email_verified_at`, `password`, `business_id`, `account_type`, `thumbnail`, `activation_token`, `status`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Shohoz Sales', '01701028220', 'shohoz_sales', 'user@shohozsales.com', NULL, '$2y$10$/lMA59A7QJ9apb5Q250zL.0SbFdo6ka6pWqSJElNqgYNs8Ys8JT6K', 1, NULL, '1595844358570', NULL, 1, 'bWiSRlYbQW0YTTk194jTFOnwlbT2KCMx8p8NLW0TirYqksW43mmqrxbQIxj1', NULL, '2020-02-24 08:07:34', '2020-07-27 10:06:26'),
(2, 'Halee Castaneda', '+1 (117) 302-9669', 'mekucod', 'balazapyho@mailinator.com', NULL, '$2y$10$v7iHnDYHsA.9IVzwtMD4l.WnELX3jhCVj8dKaPTMchAp.KdYItvHu', 1, NULL, 'Mollitia quam omnis', NULL, 0, NULL, NULL, '2020-02-24 11:51:50', '2020-02-24 11:51:50'),
(3, 'Georgia Martin', '+1 (691) 778-6665', 'sykolaqigy', 'janaz@mailinator.net', NULL, '$2y$10$36nmX./y3Ks916Rv1EnCXO7AZf7sLSqsLE1cfvXOE8eb6juR2KyNG', 1, NULL, 'Quia totam voluptate', NULL, 0, NULL, NULL, '2020-02-25 05:39:36', '2020-02-25 05:39:36'),
(4, 'Drake Logan', '+1 (946) 558-9885', 'wabolevas', 'nawywacare@mailinator.com', NULL, '$2y$10$xgTPc.MYh0z0Nls8G8Lefum3yi2./QvWiX0eTS9SlsNkQ5GTtWm.u', 1, NULL, 'Sit iste quia sequi', NULL, 0, NULL, NULL, '2020-02-25 09:40:09', '2020-02-25 09:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` int(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `title`, `address`, `user_id`, `description`, `status`, `business_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'WAH0001', 'গাঙ্গিনাপার গুদাম', 'গাঙ্গিনাপার ময়মনসিংহ', 1, NULL, 1, 1, NULL, '2020-01-15 06:03:28', '2020-01-15 06:03:28'),
(2, 'WAH0002', 'Notun Bazar Gudam', 'mymensingh', 1, NULL, 1, 1, NULL, '2020-01-19 11:00:27', '2020-01-19 11:00:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesslogs`
--
ALTER TABLE `accesslogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_phone_unique` (`phone`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `advanced_salaries`
--
ALTER TABLE `advanced_salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advanced_salaries_user_id_foreign` (`user_id`);

--
-- Indexes for table `balance_transfers`
--
ALTER TABLE `balance_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_accounts_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_code_unique` (`code`),
  ADD KEY `brands_party_id_foreign` (`party_id`);

--
-- Indexes for table `brand_category`
--
ALTER TABLE `brand_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_category_brand_id_foreign` (`brand_id`),
  ADD KEY `brand_category_category_id_foreign` (`category_id`);

--
-- Indexes for table `broken_commissions`
--
ALTER TABLE `broken_commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashes`
--
ALTER TABLE `cashes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_code_unique` (`code`);

--
-- Indexes for table `damage_stocks`
--
ALTER TABLE `damage_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `damage_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `damage_stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `due_manages`
--
ALTER TABLE `due_manages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `due_manages_party_id_foreign` (`party_id`),
  ADD KEY `due_manages_cash_id_foreign` (`cash_id`),
  ADD KEY `due_manages_bank_id_foreign` (`bank_id`),
  ADD KEY `due_manages_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `due_manages_user_id_foreign` (`user_id`);

--
-- Indexes for table `expenditures`
--
ALTER TABLE `expenditures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenditures_gl_account_id_foreign` (`gl_account_id`),
  ADD KEY `expenditures_gl_account_head_id_foreign` (`gl_account_head_id`),
  ADD KEY `expenditures_cash_id_foreign` (`cash_id`),
  ADD KEY `expenditures_bank_id_foreign` (`bank_id`),
  ADD KEY `expenditures_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `expenditures_user_id_foreign` (`user_id`);

--
-- Indexes for table `gl_accounts`
--
ALTER TABLE `gl_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gl_account_heads`
--
ALTER TABLE `gl_account_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gl_account_heads_gl_id_foreign` (`gl_account_id`);

--
-- Indexes for table `installment_pays`
--
ALTER TABLE `installment_pays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installment_pays_advanced_salary_id_foreign` (`advanced_salary_id`);

--
-- Indexes for table `ledgerables`
--
ALTER TABLE `ledgerables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ledgerable_ledgerable_type_ledgerable_id_index` (`ledgerable_type`,`ledgerable_id`),
  ADD KEY `ledgerable_ledger_id_foreign` (`ledger_id`);

--
-- Indexes for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_code_unique` (`code`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metas`
--
ALTER TABLE `metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metas_metaable_type_metaable_id_index` (`metaable_type`,`metaable_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `order_manages`
--
ALTER TABLE `order_manages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_manages_product_id_foreign` (`product_id`),
  ADD KEY `order_manages_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `order_manages_business_id_foreign` (`business_id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parties_code_unique` (`code`),
  ADD UNIQUE KEY `parties_phone_unique` (`phone`),
  ADD UNIQUE KEY `parties_email_unique` (`email`),
  ADD KEY `parties_business_id_foreign` (`business_id`);

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
  ADD KEY `permissions_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD KEY `permission_role_role_id_foreign` (`role_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_code_unique` (`code`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `products_party_id_foreign` (`party_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `product_transfers`
--
ALTER TABLE `product_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  ADD KEY `product_transfers_product_id_foreign` (`product_id`),
  ADD KEY `product_transfers_to_warehouse_id_foreign` (`to_warehouse_id`),
  ADD KEY `product_transfers_business_id_foreign` (`business_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_party_id_foreign` (`party_id`),
  ADD KEY `purchases_cash_id_foreign` (`cash_id`),
  ADD KEY `purchases_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_details_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_quantities`
--
ALTER TABLE `purchase_quantities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_quantities_purchase_details_id_foreign` (`purchase_details_id`),
  ADD KEY `purchase_quantities_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_quantities_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_returns_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_returns_user_id_foreign` (`user_id`);

--
-- Indexes for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_products_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_return_quantities`
--
ALTER TABLE `purchase_return_quantities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_quantities_purchase_return_product_id_foreign` (`purchase_return_product_id`),
  ADD KEY `purchase_return_quantities_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_return_quantities_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_return_quantities_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD KEY `role_user_user_id_foreign` (`user_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salaries_user_id_foreign` (`user_id`);

--
-- Indexes for table `salary_details`
--
ALTER TABLE `salary_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_details_salary_id_foreign` (`salary_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_no_unique` (`invoice_no`),
  ADD KEY `sales_party_id_foreign` (`party_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_salesman_id_foreign` (`salesman_id`);

--
-- Indexes for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_details_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_details_warehouses`
--
ALTER TABLE `sale_details_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_details_warehouses_sale_details_id_foreign` (`sale_details_id`),
  ADD KEY `sale_details_warehouses_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_details_warehouses_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_details_warehouses_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_payments_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_payments_cash_id_foreign` (`cash_id`),
  ADD KEY `sale_payments_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `sale_rest_quantities`
--
ALTER TABLE `sale_rest_quantities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_rest_quantities_sale_details_id_foreign` (`sale_details_id`),
  ADD KEY `sale_rest_quantities_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_rest_quantities_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_rest_quantities_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_returns_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_returns_user_id_foreign` (`user_id`),
  ADD KEY `sale_returns_cash_id_foreign` (`cash_id`),
  ADD KEY `sale_returns_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_products_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_return_quantities`
--
ALTER TABLE `sale_return_quantities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_quantities_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_quantities_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_return_quantities_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_return_quantities_product_id_foreign` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_business_id_foreign` (`business_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`),
  ADD KEY `stocks_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `transfer_products`
--
ALTER TABLE `transfer_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_products_from_warehouse_id_foreign` (`from_warehouse_id`),
  ADD KEY `transfer_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_products_to_warehouse_id_foreign` (`to_warehouse_id`),
  ADD KEY `transfer_products_business_id_foreign` (`business_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesslogs`
--
ALTER TABLE `accesslogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `advanced_salaries`
--
ALTER TABLE `advanced_salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `balance_transfers`
--
ALTER TABLE `balance_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `brand_category`
--
ALTER TABLE `brand_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `broken_commissions`
--
ALTER TABLE `broken_commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cashes`
--
ALTER TABLE `cashes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `damage_stocks`
--
ALTER TABLE `damage_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `due_manages`
--
ALTER TABLE `due_manages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenditures`
--
ALTER TABLE `expenditures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gl_accounts`
--
ALTER TABLE `gl_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gl_account_heads`
--
ALTER TABLE `gl_account_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `installment_pays`
--
ALTER TABLE `installment_pays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ledgerables`
--
ALTER TABLE `ledgerables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `metas`
--
ALTER TABLE `metas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_manages`
--
ALTER TABLE `order_manages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_transfers`
--
ALTER TABLE `product_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_quantities`
--
ALTER TABLE `purchase_quantities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_quantities`
--
ALTER TABLE `purchase_return_quantities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_details`
--
ALTER TABLE `salary_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sale_details`
--
ALTER TABLE `sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sale_details_warehouses`
--
ALTER TABLE `sale_details_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sale_rest_quantities`
--
ALTER TABLE `sale_rest_quantities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale_return_quantities`
--
ALTER TABLE `sale_return_quantities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transfer_products`
--
ALTER TABLE `transfer_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advanced_salaries`
--
ALTER TABLE `advanced_salaries`
  ADD CONSTRAINT `advanced_salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD CONSTRAINT `bank_accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `brand_category`
--
ALTER TABLE `brand_category`
  ADD CONSTRAINT `brand_category_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `brand_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `damage_stocks`
--
ALTER TABLE `damage_stocks`
  ADD CONSTRAINT `damage_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `damage_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `due_manages`
--
ALTER TABLE `due_manages`
  ADD CONSTRAINT `due_manages_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `due_manages_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `due_manages_cash_id_foreign` FOREIGN KEY (`cash_id`) REFERENCES `cashes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `due_manages_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `due_manages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenditures`
--
ALTER TABLE `expenditures`
  ADD CONSTRAINT `expenditures_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenditures_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenditures_cash_id_foreign` FOREIGN KEY (`cash_id`) REFERENCES `cashes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenditures_gl_account_head_id_foreign` FOREIGN KEY (`gl_account_head_id`) REFERENCES `gl_account_heads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenditures_gl_account_id_foreign` FOREIGN KEY (`gl_account_id`) REFERENCES `gl_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenditures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gl_account_heads`
--
ALTER TABLE `gl_account_heads`
  ADD CONSTRAINT `gl_account_heads_gl_id_foreign` FOREIGN KEY (`gl_account_id`) REFERENCES `gl_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `installment_pays`
--
ALTER TABLE `installment_pays`
  ADD CONSTRAINT `installment_pays_advanced_salary_id_foreign` FOREIGN KEY (`advanced_salary_id`) REFERENCES `advanced_salaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ledgerables`
--
ALTER TABLE `ledgerables`
  ADD CONSTRAINT `ledgerable_ledger_id_foreign` FOREIGN KEY (`ledger_id`) REFERENCES `ledgers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_manages`
--
ALTER TABLE `order_manages`
  ADD CONSTRAINT `order_manages_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_manages_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_manages_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parties`
--
ALTER TABLE `parties`
  ADD CONSTRAINT `parties_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_transfers`
--
ALTER TABLE `product_transfers`
  ADD CONSTRAINT `product_transfers_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_transfers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchases_cash_id_foreign` FOREIGN KEY (`cash_id`) REFERENCES `cashes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchases_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `purchase_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_quantities`
--
ALTER TABLE `purchase_quantities`
  ADD CONSTRAINT `purchase_quantities_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_quantities_purchase_details_id_foreign` FOREIGN KEY (`purchase_details_id`) REFERENCES `purchase_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_quantities_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_returns_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD CONSTRAINT `purchase_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_return_quantities`
--
ALTER TABLE `purchase_return_quantities`
  ADD CONSTRAINT `purchase_return_quantities_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_return_quantities_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_return_quantities_purchase_return_product_id_foreign` FOREIGN KEY (`purchase_return_product_id`) REFERENCES `purchase_return_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_return_quantities_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `salary_details`
--
ALTER TABLE `salary_details`
  ADD CONSTRAINT `salary_details_salary_id_foreign` FOREIGN KEY (`salary_id`) REFERENCES `salaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_salesman_id_foreign` FOREIGN KEY (`salesman_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD CONSTRAINT `sale_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_payments_cash_id_foreign` FOREIGN KEY (`cash_id`) REFERENCES `cashes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transfer_products`
--
ALTER TABLE `transfer_products`
  ADD CONSTRAINT `transfer_products_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transfer_products_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transfer_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transfer_products_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2024 at 04:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tohed`
--

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
-- Table structure for table `masjid_models`
--

CREATE TABLE `masjid_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `masjid_name` varchar(255) NOT NULL,
  `masjid_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `imam_name` varchar(255) NOT NULL,
  `khateeb_name` varchar(255) DEFAULT NULL,
  `images` text NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `masjid_models`
--

INSERT INTO `masjid_models` (`id`, `name`, `masjid_name`, `masjid_address`, `city`, `country`, `imam_name`, `khateeb_name`, `images`, `contact_number`, `content`, `status`, `created_at`, `updated_at`) VALUES
(2, 'عبداللہ احمد', 'مسجد ام القری', 'کراچی، پاکستان', 'کراچی', 'پاکستان', 'مولانا عبدالحکیم', 'مولانا فیاض', '17951712014251.38731711825218.94881711574792.masjid.png,62191712014251.52851712006681.fahrul-azmi-5K549TS6F08-unsplash.jpg', '+92-300-1234567', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-05 15:58:36'),
(3, 'خالد محمود', 'مسجد الحسین', 'اسلام آباد، پاکستان', 'اسلام آباد', 'پاکستان', 'مولانا عبدالواسع', 'مولانا طاہر', '53571712013955.dhru-j-r7H8kkoYyCY-unsplash.jpg,22211712013955.david-rodrigo-kZ1zThg6G40-unsplash.jpg', '+92-333-9876543', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-01 18:25:55'),
(4, 'زوہر احمد', 'مسجد ابوبکر', 'پشاور، پاکستان', 'پشاور', 'پاکستان', 'مولانا عبدالقادر', 'مولانا نعمان', '54271712013965.fahrul-azmi-gyKmF0vnfBs-unsplash.jpg,59841712013965.fahrul-azmi-5K549TS6F08-unsplash.jpg', '+92-321-5678901', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-01 18:26:05'),
(5, 'صفیہ علی', 'مسجد الصدیق', 'راولپنڈی، پاکستان', 'سکھر', 'پاکستان', 'مولانا اسلم', 'مولانا حفیظ', '72801712013976.fahrul-azmi-gyKmF0vnfBs-unsplash.jpg,46331712013976.fahrul-azmi-5K549TS6F08-unsplash.jpg', '+92-345-7890123', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-02 20:00:55'),
(6, 'نور احمد', 'مسجد عمر', 'سکھر، پاکستان', 'سکھر', 'پاکستان', 'مولانا عبدالمجید', 'مولانا احمد', '82751712013999.fahrul-azmi-gyKmF0vnfBs-unsplash.jpg,95111712013999.fahrul-azmi-5K549TS6F08-unsplash.jpg', '+92-345-8901234', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-01 18:26:39'),
(7, 'زینب خانم', 'مسجد النور', 'ملتان، پاکستان', 'ملتان', 'پاکستان', 'مولانا انور', 'مولانا حسن', '95161712013986.fatih-yurur-kNSREmtaGOE-unsplash.jpg,56131712013986.fahrul-azmi-gyKmF0vnfBs-unsplash.jpg', '+92-333-6789123', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-01 18:26:26'),
(8, 'علی حسین', 'مسجد ابوہریرہ', 'گوجرانوالہ، پاکستان', 'گوجرانوالہ', 'پاکستان', 'مولانا عبداللہ', 'مولانا جواد', '95121712014080.meric-dagli-xmZ7nuqK7kg-unsplash.jpg,56291712014080.fatih-yurur-kNSREmtaGOE-unsplash (1).jpg', '+92-300-4567890', '<h2><strong>ٹیکنالوجی کمپنی اسپیس</strong></h2><p>ٹیکنالوجی کمپنی اسپیس ایکس نے مریخ پر بھیجے جانے والے خلائی جہاز کی آزمائشی پروازوں کی شرح میں فوری اضافے کے پیشِ نظر نئے اسٹار شپ راکٹ انجنوں کا ٹیسٹ کیا ہے۔ &nbsp;یہ اسٹیٹک فائر ٹیسٹ، اسپیس ایکس کی جانب سے اسٹار شپ کو پہلی بار کامیابی کے ساتھ مدار میں لانچ<br>&nbsp; &nbsp;کرنے کے 11 روز بعد کیا گیا ہے۔ اگرچہ اس ٹیسٹ میں اپر اسٹیج راکٹ اور سپر ہیوی بوسٹر تباہ ہوگئے تھے لیکن اس آزمائش کے بنیادی مقاصد کو حاصل کر لیا گیا تھا جو کہ اسٹار شپ کی تکمیل کی واضح اشارہ ہے۔</p>', 1, '2024-04-01 22:24:14', '2024-04-01 18:28:00'),
(40, 'فاطمہ علی', 'مسجد ابو بکر', '۲۲۲ مین روڈ', 'کراچی', 'پاکستان', 'مولانا احمد رضا', 'مولانا حسین احمد', '41091712570067.fahrul-azmi-5K549TS6F08-unsplash.jpg', '4077774212', NULL, 0, '2024-04-08 04:54:27', '2024-04-08 04:54:27');

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
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(10, '2024_03_26_201600_create_masjid_models_table', 2),
(12, '0001_01_01_000000_create_users_table', 3),
(15, '2024_04_04_205712_create_rishta_models_table', 4),
(18, '2024_04_04_222137_create_rishta_models_table', 5);

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
-- Table structure for table `rishta_models`
--

CREATE TABLE `rishta_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('میل','فی میل') NOT NULL,
  `birthdate` date NOT NULL,
  `marital_status` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `mother_tongue` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `ethnicity` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `images` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rishta_models`
--

INSERT INTO `rishta_models` (`id`, `full_name`, `gender`, `birthdate`, `marital_status`, `city`, `country`, `mother_tongue`, `education`, `profession`, `ethnicity`, `content`, `status`, `images`, `created_at`, `updated_at`) VALUES
(1, 'علی', 'میل', '1990-05-15', 'شادی شدہ', 'کراچی', 'پاکستان', 'اردو', 'انجینئرنگ', 'مهندس', 'پنجابی', '', 1, '92211712356326.sergio-de-paula-c_GmwfHBDzk-unsplash.jpg', '2024-04-05 22:31:26', '2024-04-05 17:32:40'),
(2, 'فاطمہ خان', 'فی میل', '1995-08-20', 'سنگل', 'اسلام آباد', 'پاکستان', 'پشتو', 'کامیابی', 'ڈاکٹر', 'بلوچ', '<h2><strong>رشتہ درکار ہے</strong></h2>', 1, '94981712356405.stefan-stefancik-QXevDflbl8A-unsplash.jpg', '2024-04-05 22:31:26', '2024-04-05 17:39:44'),
(3, 'حسن علی', 'میل', '1985-03-10', 'شادی شدہ', 'لاہور', 'پاکستان', 'پنجابی', 'ماسٹرز', 'تجارتی', 'پنجابی', NULL, 0, NULL, '2024-04-05 22:31:26', '2024-04-05 22:31:26'),
(4, 'سمیعہ اختر', 'فی میل', '1998-11-25', 'سنگل', 'کراچی', 'پاکستان', 'سندھی', 'ماسٹرز', 'اورنجیزی', 'سندھی', '', 0, NULL, '2024-04-05 22:31:26', '2024-04-05 22:31:26'),
(5, 'وسیم عباس', 'میل', '1992-07-05', 'شادی شدہ', 'راولپنڈی', 'پاکستان', 'پنجابی', 'بزنس', 'بزنس مین', 'پنجابی', NULL, 0, NULL, '2024-04-05 22:31:26', '2024-04-05 22:31:26'),
(6, 'multiple', 'میل', '2024-04-25', 'سنگل', 'multiple', 'multiple', 'multiple', 'multiple', 'multiple', 'multiple', NULL, 1, '86461712359039.stefan-stefancik-QXevDflbl8A-unsplash.jpg,61781712359039.sergio-de-paula-c_GmwfHBDzk-unsplash.jpg', '2024-04-05 18:17:19', '2024-04-05 18:18:58');

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
('HOGr1gYyyPp84rQtKzmmwyJL4yhKrtMwnsaokVAx', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUEtkeHVhbGd5Wk1SN1ZDaVpjUnIyTkFkaEdrZjJZMUlBY3Rjc1ZFYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1712570734),
('Xsql1tPSPMqWUokBaNNu1z3NGjvsjxvLeCFKTft3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS0RPbVk1a1N2UnZvQ1N1cE1HZUhCbmVqSnppQmQ3dkNrb0xFSVlPZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Jpc2h0YS9mb3JtIjt9fQ==', 1712359349),
('Z0vaXQbBs1fT4TPsz2F58Izo8aqNiGN8USOiYzrw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSm1iM0h4YTVhUVY1aTY0TktQaFBJNnh2SmxPM2hTRjFJbG5yaU1yMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXNqaWQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1712358602);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', NULL, '$2y$12$wQ0P/rb8ZhI1Mu53PeaSxOPeaBdsFX6K/jiR9X9We6c0mV06uy.w6', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `masjid_models`
--
ALTER TABLE `masjid_models`
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
-- Indexes for table `rishta_models`
--
ALTER TABLE `rishta_models`
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
-- AUTO_INCREMENT for table `masjid_models`
--
ALTER TABLE `masjid_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rishta_models`
--
ALTER TABLE `rishta_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

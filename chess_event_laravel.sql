-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 19 2026 г., 09:21
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `chess_event_laravel`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `elo_histories`
--

CREATE TABLE `elo_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `elo_before` int(11) NOT NULL,
  `elo_after` int(11) NOT NULL,
  `change` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `elo_history`
--

CREATE TABLE `elo_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED DEFAULT NULL,
  `elo_before` int(11) NOT NULL,
  `elo_after` int(11) NOT NULL,
  `change` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL DEFAULT 'game',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
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
-- Структура таблицы `gamemodes`
--

CREATE TABLE `gamemodes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `control_time` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gamemodes`
--

INSERT INTO `gamemodes` (`id`, `name`, `control_time`) VALUES
(1, 'Классика - 90 минут + 30 секунд на ход', '90 минут + 30 секунд на ход'),
(2, 'Классика - 60 минут + 30 секунд на ход', '60 минут + 30 секунд на ход'),
(3, 'Классика - 2 часа на партию', '2 часа на партию'),
(4, 'Рапид - 15 минут + 10 секунд на ход', '15 минут + 10 секунд на ход'),
(5, 'Рапид - 15 минут на партию', '15 минут на партию'),
(6, 'Рапид - 10 минут + 5 секунд на ход', '10 минут + 5 секунд на ход'),
(7, 'Рапид - 10 минут на партию', '10 минут на партию'),
(8, 'Блиц - 5 минут + 3 секунды на ход', '5 минут + 3 секунды на ход'),
(9, 'Блиц - 5 минут на партию', '5 минут на партию'),
(10, 'Блиц - 3 минуты + 2 секунды на ход', '3 минуты + 2 секунды на ход'),
(11, 'Блиц - 3 минуты на партию', '3 минуты на партию'),
(12, 'Пуля - 2 минуты + 1 секунда на ход', '2 минуты + 1 секунда на ход'),
(13, 'Пуля - 2 минуты на партию', '2 минуты на партию'),
(14, 'Пуля - 1 минута + 1 секунда на ход', '1 минута + 1 секунда на ход'),
(15, 'Пуля - 1 минута на партию', '1 минута на партию');

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `white_user_id` bigint(20) UNSIGNED NOT NULL,
  `black_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tournament_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','active','finished','draw','white_win','black_win') NOT NULL DEFAULT 'pending',
  `current_fen` varchar(255) DEFAULT NULL,
  `last_move_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `winner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`id`, `white_user_id`, `black_user_id`, `tournament_id`, `status`, `current_fen`, `last_move_at`, `started_at`, `finished_at`, `winner_id`, `created_at`, `updated_at`) VALUES
(1, 4, 2, NULL, 'black_win', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', '2026-07-12 02:28:46', '2026-07-12 02:28:02', '2026-07-12 02:28:46', 2, '2026-07-12 02:28:02', '2026-07-12 02:28:46'),
(2, 2, 4, NULL, 'white_win', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', '2026-07-12 02:41:06', '2026-07-12 02:40:31', '2026-07-12 02:41:06', 2, '2026-07-12 02:40:31', '2026-07-12 02:41:06'),
(3, 2, 4, NULL, 'white_win', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', '2026-07-12 02:47:32', '2026-07-12 02:47:04', '2026-07-12 02:47:32', 2, '2026-07-12 02:47:04', '2026-07-12 02:47:32');

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
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
-- Структура таблицы `job_batches`
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
-- Структура таблицы `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(85) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `levels`
--

INSERT INTO `levels` (`id`, `name`) VALUES
(1, 'Международный уровень'),
(2, 'Федеральный уровень'),
(3, 'Муниципальный уровень'),
(4, 'Региональный уровень'),
(5, 'Уровень учреждения');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_07_064159_create_regions_table', 1),
(5, '2026_07_07_064200_add_extra_fields_to_users_table', 1),
(6, '2026_07_07_064201_create_gamemodes_table', 1),
(7, '2026_07_07_064202_create_levels_table', 1),
(8, '2026_07_07_064204_create_tournaments_table', 1),
(9, '2026_07_07_064205_create_reg_to_tournaments_table', 1),
(10, '2026_07_07_064206_create_games_table', 1),
(11, '2026_07_07_064207_create_moves_table', 1),
(12, '2026_07_07_064208_create_elo_history_table', 1),
(13, '2026_07_07_064210_create_plannings_table', 1),
(14, '2026_07_07_064211_create_tournament_matches_table', 1),
(15, '2026_07_07_064217_create_tournament_byes_table', 1),
(16, '2026_07_10_075108_change_status_column_in_planning_table', 2),
(17, '2026_07_11_082513_add_cascade_delete_to_user_relations', 3),
(18, '2026_07_12_080252_create_elo_histories_table', 4),
(19, '2026_07_13_061059_add_telegram_id_to_users_table', 5),
(20, '2026_07_13_095322_add_vk_id_to_users_table', 6),
(21, '2026_07_13_095811_remove_telegram_fields_from_users_table', 7),
(22, '2026_07_17_053617_create_tournament_subscriptions_table', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `moves`
--

CREATE TABLE `moves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `move_number` int(11) NOT NULL,
  `move_san` varchar(10) NOT NULL,
  `move_fen` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `moves`
--

INSERT INTO `moves` (`id`, `game_id`, `user_id`, `move_number`, `move_san`, `move_fen`, `created_at`) VALUES
(1, 1, 4, 1, 'e4', 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', '2026-07-12 02:28:13'),
(2, 1, 2, 2, 'f6', 'rnbqkbnr/ppppp1pp/5p2/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq - 0 2', '2026-07-12 02:28:24'),
(3, 1, 4, 3, 'd4', 'rnbqkbnr/ppppp1pp/5p2/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq d3 0 2', '2026-07-12 02:28:31'),
(4, 1, 2, 4, 'g5', 'rnbqkbnr/ppppp2p/5p2/6p1/3PP3/8/PPP2PPP/RNBQKBNR w KQkq g6 0 3', '2026-07-12 02:28:38'),
(5, 1, 4, 5, 'Qh5#', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', '2026-07-12 02:28:45'),
(6, 2, 2, 1, 'd4', 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq d3 0 1', '2026-07-12 02:40:36'),
(7, 2, 4, 2, 'f6', 'rnbqkbnr/ppppp1pp/5p2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq - 0 2', '2026-07-12 02:40:44'),
(8, 2, 2, 3, 'e4', 'rnbqkbnr/ppppp1pp/5p2/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq e3 0 2', '2026-07-12 02:40:51'),
(9, 2, 4, 4, 'g5', 'rnbqkbnr/ppppp2p/5p2/6p1/3PP3/8/PPP2PPP/RNBQKBNR w KQkq g6 0 3', '2026-07-12 02:40:57'),
(10, 2, 2, 5, 'Qh5#', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', '2026-07-12 02:41:05'),
(11, 3, 2, 1, 'e4', 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1', '2026-07-12 02:47:08'),
(12, 3, 4, 2, 'f6', 'rnbqkbnr/ppppp1pp/5p2/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq - 0 2', '2026-07-12 02:47:14'),
(13, 3, 2, 3, 'd4', 'rnbqkbnr/ppppp1pp/5p2/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq d3 0 2', '2026-07-12 02:47:20'),
(14, 3, 4, 4, 'g5', 'rnbqkbnr/ppppp2p/5p2/6p1/3PP3/8/PPP2PPP/RNBQKBNR w KQkq g6 0 3', '2026-07-12 02:47:26'),
(15, 3, 2, 5, 'Qh5#', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', '2026-07-12 02:47:32');

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `plannings`
--

CREATE TABLE `plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content` varchar(255) NOT NULL,
  `organizer` varchar(85) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gamemode_id` bigint(20) UNSIGNED NOT NULL,
  `imageFile` varchar(255) NOT NULL,
  `quantity_rounds` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `plannings`
--

INSERT INTO `plannings` (`id`, `content`, `organizer`, `user_id`, `gamemode_id`, `imageFile`, `quantity_rounds`, `created_at`, `updated_at`, `status`) VALUES
(2, 'ikhjvgfhdfsa', 'КПК', 2, 14, 'planning_images/c043yUg0OBXlIf58ko1Bzvu7KeYw2doKb6529iGc.png', 9, '2026-07-10 04:00:59', '2026-07-10 04:02:11', 'approved');

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Республика Адыгея'),
(2, 'Республика Алтай'),
(3, 'Республика Башкортостан'),
(4, 'Республика Бурятия'),
(5, 'Республика Дагестан'),
(6, 'Республика Ингушетия'),
(7, 'Кабардино-Балкарская Республика'),
(8, 'Республика Калмыкия'),
(9, 'Карачаево-Черкесская Республика'),
(10, 'Республика Карелия'),
(11, 'Республика Коми'),
(12, 'Республика Крым'),
(13, 'Республика Марий Эл'),
(14, 'Республика Мордовия'),
(15, 'Республика Саха (Якутия)'),
(16, 'Республика Северная Осетия — Алания'),
(17, 'Республика Татарстан'),
(18, 'Республика Тыва'),
(19, 'Удмуртская Республика'),
(20, 'Республика Хакасия'),
(21, 'Чеченская Республика'),
(22, 'Чувашская Республика'),
(23, 'Алтайский край'),
(24, 'Забайкальский край'),
(25, 'Камчатский край'),
(26, 'Краснодарский край'),
(27, 'Красноярский край'),
(28, 'Пермский край'),
(29, 'Приморский край'),
(30, 'Ставропольский край'),
(31, 'Хабаровский край'),
(32, 'Амурская область'),
(33, 'Архангельская область'),
(34, 'Астраханская область'),
(35, 'Белгородская область'),
(36, 'Брянская область'),
(37, 'Владимирская область'),
(38, 'Волгоградская область'),
(39, 'Вологодская область'),
(40, 'Воронежская область'),
(41, 'Ивановская область'),
(42, 'Иркутская область'),
(43, 'Калининградская область'),
(44, 'Калужская область'),
(45, 'Кемеровская область'),
(46, 'Кировская область'),
(47, 'Костромская область'),
(48, 'Курганская область'),
(49, 'Курская область'),
(50, 'Ленинградская область'),
(51, 'Липецкая область'),
(52, 'Магаданская область'),
(53, 'Московская область'),
(54, 'Мурманская область'),
(55, 'Нижегородская область'),
(56, 'Новгородская область'),
(57, 'Новосибирская область'),
(58, 'Омская область'),
(59, 'Оренбургская область'),
(60, 'Орловская область'),
(61, 'Пензенская область'),
(62, 'Псковская область'),
(63, 'Ростовская область'),
(64, 'Рязанская область'),
(65, 'Самарская область'),
(66, 'Саратовская область'),
(67, 'Сахалинская область'),
(68, 'Свердловская область'),
(69, 'Смоленская область'),
(70, 'Тамбовская область'),
(71, 'Тверская область'),
(72, 'Томская область'),
(73, 'Тульская область'),
(74, 'Тюменская область'),
(75, 'Ульяновская область'),
(76, 'Челябинская область'),
(77, 'Ярославская область'),
(78, 'Москва'),
(79, 'Санкт-Петербург'),
(80, 'Севастополь'),
(81, 'Еврейская автономная область'),
(82, 'Ненецкий автономный округ'),
(83, 'Ханты-Мансийский автономный округ - Югра'),
(84, 'Чукотский автономный округ'),
(85, 'Ямало-Ненецкий автономный округ'),
(86, 'Донецкая Народная Республика'),
(87, 'Луганская Народная Республика'),
(88, 'Запорожская область'),
(89, 'Херсонская область');

-- --------------------------------------------------------

--
-- Структура таблицы `reg_to_tournaments`
--

CREATE TABLE `reg_to_tournaments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reg_to_tournaments`
--

INSERT INTO `reg_to_tournaments` (`id`, `tournament_id`, `user_id`, `registration_date`) VALUES
(4, 3, 5, '2026-07-16 19:00:00'),
(5, 3, 2, '2026-07-16 19:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
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
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('OqoVqr8WqrWzpZ5dwGHvgZl3SUYQbSOsmIElGLaw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXVFdGhmTHZXd1M0M0I0VkNXckw5SFlwVDFjdHY4QXVKZGVoTVAweCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1784356432),
('RQEu4woyu95d6KXNqgBOeNkrjbFXqX7FEUfL6S7c', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjNreW5Ec3l3U0taWUh3d3JUWjhuak9UYVptV25Xb2pBV1dCcjJNViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90b3VybmFtZW50cy8xIjtzOjU6InJvdXRlIjtzOjE3OiJ0b3VybmFtZW50cy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1784443616);

-- --------------------------------------------------------

--
-- Структура таблицы `tournaments`
--

CREATE TABLE `tournaments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `img` varchar(255) NOT NULL,
  `name` varchar(90) NOT NULL,
  `description` varchar(255) NOT NULL,
  `gamemode_id` bigint(20) UNSIGNED NOT NULL,
  `location` varchar(90) NOT NULL,
  `quantity_rounds` int(11) NOT NULL,
  `status` enum('Запланирован','В процессе','Завершен') NOT NULL DEFAULT 'Запланирован',
  `level_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tournaments`
--

INSERT INTO `tournaments` (`id`, `img`, `name`, `description`, `gamemode_id`, `location`, `quantity_rounds`, `status`, `level_id`) VALUES
(3, 'U6h5vEdlc6pjEILrqk35Cb4Gmetp9l5rDUDF1asI.png', 'Тестовый турнир', 'Для теста!', 8, 'Курган', 3, 'Завершен', 2),
(4, 'Rcd6DjIvMR7ZxeBmuuctnKFzFpQW9VJRld8jevMB.png', 'Новый тестовый', 'Новый тестовый турнир для всех!', 6, 'Курган', 9, 'Запланирован', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_byes`
--

CREATE TABLE `tournament_byes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `round` int(11) NOT NULL,
  `points` decimal(3,1) NOT NULL DEFAULT 1.0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_matches`
--

CREATE TABLE `tournament_matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `round` int(11) NOT NULL,
  `white_player_id` bigint(20) UNSIGNED NOT NULL,
  `black_player_id` bigint(20) UNSIGNED NOT NULL,
  `result` enum('pending','white_win','black_win','draw') NOT NULL DEFAULT 'pending',
  `winner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','played') NOT NULL DEFAULT 'pending',
  `played_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tournament_matches`
--

INSERT INTO `tournament_matches` (`id`, `tournament_id`, `round`, `white_player_id`, `black_player_id`, `result`, `winner_id`, `status`, `played_at`, `created_at`) VALUES
(3, 3, 1, 5, 2, 'white_win', 5, 'played', '2026-07-17 01:16:21', '2026-07-17 01:15:06'),
(4, 3, 2, 5, 2, 'black_win', 2, 'played', '2026-07-17 01:54:18', '2026-07-17 01:52:38'),
(5, 3, 3, 2, 5, 'draw', NULL, 'played', '2026-07-17 02:00:10', '2026-07-17 01:59:43');

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_subscriptions`
--

CREATE TABLE `tournament_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `notify_draw` tinyint(1) NOT NULL DEFAULT 1,
  `notify_start` tinyint(1) NOT NULL DEFAULT 1,
  `notify_result` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tournament_subscriptions`
--

INSERT INTO `tournament_subscriptions` (`id`, `user_id`, `tournament_id`, `notify_draw`, `notify_start`, `notify_result`, `created_at`, `updated_at`) VALUES
(2, 5, 3, 1, 1, 1, '2026-07-17 01:59:30', '2026-07-17 01:59:30');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `vk_id` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `elo` int(11) NOT NULL DEFAULT 1000,
  `role` int(11) NOT NULL DEFAULT 0,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `vk_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `first_name`, `last_name`, `elo`, `role`, `region_id`) VALUES
(1, 'AdminChess', 'dimalakaev@gmail.com', NULL, NULL, '$2y$12$ulC3g82C5G8CxickbGQ9VuHqYYlWiE8dBOBlmHDzpehXlkxgaRv/y', 'pQHtHlW3saa32H4e1WwGGk86dcVTTDciWtRKiNJwRESyRoPAQymOesB8tcQ3', '2026-07-08 02:21:22', '2026-07-08 02:21:22', 'Дмитрий', 'Лакаев', 1000, 1, 11),
(2, 'ZoxaChess', 'z@zoxa.ru', NULL, NULL, '$2y$12$VUjPl/oOFOKHs.q0saEY4.KdZUWxn7EIwieMrDfNUMtmt4Id60q0G', 'Mb8VGaUZfKcvk8Daf7KZ4qBoimI2cRVaNOzxdzrGizLxv6CU9nJYUDwZCFox', '2026-07-08 03:08:03', '2026-07-12 02:47:32', 'Захар', 'Корнилов', 1021, 0, 15),
(4, 'TestUser67', 'test@test', NULL, NULL, '$2y$12$KsgPfIoshlnVD2RmJJEBNugoL1EeMS6ruLzZfbzG2s4ZKEzR2ywNC', NULL, '2026-07-12 02:27:47', '2026-07-12 02:47:32', 'Тест', 'Тестер', 979, 0, 14),
(5, 'DLchess52', 'dimitrilakaev@gmail.com', '412435801', NULL, '$2y$12$jRt7mM/imOyUfLXDKbQTU.RbhNuH7oqzaJETQoUuDkCYKn6lTuGv.', NULL, '2026-07-13 03:14:12', '2026-07-14 03:53:34', 'Дмитрий', 'Лакаев', 1245, 0, 11);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Индексы таблицы `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Индексы таблицы `elo_histories`
--
ALTER TABLE `elo_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `elo_histories_user_id_foreign` (`user_id`),
  ADD KEY `elo_histories_game_id_foreign` (`game_id`);

--
-- Индексы таблицы `elo_history`
--
ALTER TABLE `elo_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `elo_history_user_id_foreign` (`user_id`),
  ADD KEY `elo_history_game_id_foreign` (`game_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `gamemodes`
--
ALTER TABLE `gamemodes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `games_white_user_id_foreign` (`white_user_id`),
  ADD KEY `games_black_user_id_foreign` (`black_user_id`),
  ADD KEY `games_tournament_id_foreign` (`tournament_id`),
  ADD KEY `games_winner_id_foreign` (`winner_id`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `moves`
--
ALTER TABLE `moves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `moves_game_id_foreign` (`game_id`),
  ADD KEY `moves_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `plannings`
--
ALTER TABLE `plannings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plannings_gamemode_id_foreign` (`gamemode_id`),
  ADD KEY `plannings_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reg_to_tournaments`
--
ALTER TABLE `reg_to_tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reg_to_tournaments_tournament_id_foreign` (`tournament_id`),
  ADD KEY `reg_to_tournaments_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournaments_gamemode_id_foreign` (`gamemode_id`),
  ADD KEY `tournaments_level_id_foreign` (`level_id`);

--
-- Индексы таблицы `tournament_byes`
--
ALTER TABLE `tournament_byes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_byes_tournament_id_foreign` (`tournament_id`),
  ADD KEY `tournament_byes_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_matches_tournament_id_foreign` (`tournament_id`),
  ADD KEY `tournament_matches_white_player_id_foreign` (`white_player_id`),
  ADD KEY `tournament_matches_black_player_id_foreign` (`black_player_id`),
  ADD KEY `tournament_matches_winner_id_foreign` (`winner_id`);

--
-- Индексы таблицы `tournament_subscriptions`
--
ALTER TABLE `tournament_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tournament_subscriptions_user_id_tournament_id_unique` (`user_id`,`tournament_id`),
  ADD KEY `tournament_subscriptions_tournament_id_foreign` (`tournament_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_vk_id_unique` (`vk_id`),
  ADD KEY `users_region_id_foreign` (`region_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `elo_histories`
--
ALTER TABLE `elo_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `elo_history`
--
ALTER TABLE `elo_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `gamemodes`
--
ALTER TABLE `gamemodes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `moves`
--
ALTER TABLE `moves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `plannings`
--
ALTER TABLE `plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `reg_to_tournaments`
--
ALTER TABLE `reg_to_tournaments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `tournament_byes`
--
ALTER TABLE `tournament_byes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tournament_matches`
--
ALTER TABLE `tournament_matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tournament_subscriptions`
--
ALTER TABLE `tournament_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `elo_histories`
--
ALTER TABLE `elo_histories`
  ADD CONSTRAINT `elo_histories_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `elo_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `elo_history`
--
ALTER TABLE `elo_history`
  ADD CONSTRAINT `elo_history_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `elo_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_black_user_id_foreign` FOREIGN KEY (`black_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `games_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`),
  ADD CONSTRAINT `games_white_user_id_foreign` FOREIGN KEY (`white_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `games_winner_id_foreign` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `moves`
--
ALTER TABLE `moves`
  ADD CONSTRAINT `moves_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `moves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `plannings`
--
ALTER TABLE `plannings`
  ADD CONSTRAINT `plannings_gamemode_id_foreign` FOREIGN KEY (`gamemode_id`) REFERENCES `gamemodes` (`id`),
  ADD CONSTRAINT `plannings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reg_to_tournaments`
--
ALTER TABLE `reg_to_tournaments`
  ADD CONSTRAINT `reg_to_tournaments_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reg_to_tournaments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `tournaments_gamemode_id_foreign` FOREIGN KEY (`gamemode_id`) REFERENCES `gamemodes` (`id`),
  ADD CONSTRAINT `tournaments_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`);

--
-- Ограничения внешнего ключа таблицы `tournament_byes`
--
ALTER TABLE `tournament_byes`
  ADD CONSTRAINT `tournament_byes_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_byes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD CONSTRAINT `tournament_matches_black_player_id_foreign` FOREIGN KEY (`black_player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_matches_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_matches_white_player_id_foreign` FOREIGN KEY (`white_player_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_matches_winner_id_foreign` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournament_subscriptions`
--
ALTER TABLE `tournament_subscriptions`
  ADD CONSTRAINT `tournament_subscriptions_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tournament_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

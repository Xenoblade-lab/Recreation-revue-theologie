-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 19, 2026 at 11:31 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `revue`
--

-- --------------------------------------------------------

--
-- Table structure for table `abonnements`
--

CREATE TABLE `abonnements` (
  `id` bigint UNSIGNED NOT NULL,
  `utilisateur_id` bigint UNSIGNED NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` enum('en_attente','actif','refuse','expire') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abonnements`
--

INSERT INTO `abonnements` (`id`, `utilisateur_id`, `date_debut`, `date_fin`, `statut`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-10-18', '2025-12-18', 'actif', '2025-10-14 12:56:28', '2025-10-18 08:44:58'),
(2, 4, '2025-10-18', '2025-11-18', 'actif', '2025-10-18 09:05:07', '2025-10-18 09:08:46'),
(3, 4, '2025-10-18', '2025-11-18', 'actif', '2025-10-18 09:08:36', '2025-10-18 09:08:36'),
(4, 6, '2026-01-27', '2027-01-27', 'expire', '2026-01-27 01:01:00', '2026-01-27 03:26:34'),
(5, 10, '2026-01-27', '2027-01-27', 'actif', '2026-01-27 02:01:02', '2026-01-27 02:01:02'),
(6, 10, '2026-01-27', '2027-01-27', 'actif', '2026-01-27 02:01:31', '2026-01-27 02:01:31'),
(7, 10, '2026-01-27', '2027-01-27', 'actif', '2026-01-27 02:04:28', '2026-01-27 02:04:28'),
(8, 6, '2026-01-27', '2027-01-27', 'expire', '2026-01-27 03:29:36', '2026-02-11 18:25:05'),
(9, 11, '2026-02-06', '2027-02-06', 'expire', '2026-02-06 14:15:24', '2026-02-06 14:42:51'),
(10, 6, '2026-02-11', '2027-02-11', 'expire', '2026-02-11 18:25:43', '2026-02-11 21:00:53'),
(11, 6, '2026-02-11', '2027-02-11', 'expire', '2026-02-11 21:02:42', '2026-02-17 19:08:22'),
(12, 6, '2026-02-17', '2027-02-17', 'actif', '2026-02-17 19:09:41', '2026-02-17 19:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `titre` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fichier_path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auteur_id` bigint UNSIGNED NOT NULL,
  `statut` enum('soumis','valide','rejete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'soumis',
  `date_soumission` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `issue_id` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `contenu`, `fichier_path`, `auteur_id`, `statut`, `date_soumission`, `created_at`, `updated_at`, `issue_id`) VALUES
(1, 'Mise en place d’un honeypot pour la détection, l’analyse et la contre-attaque des APT (Advanced Persistent Threats)', 'BNHJH', 'articles/Zj4qL5ryhXMJ6vmIDEvKPMhLjQ2L7A8Sshy7qqjb.pdf', 2, 'rejete', '2025-10-14 21:20:18', '2025-10-14 21:20:18', '2025-10-16 09:41:40', NULL),
(3, 'Les Hauts et les Bas', 'sfddfk', 'articles/GFqbAu6xzOGK0N0OLUgsPZ9dwXl9WYG6YjhTSvfl.pdf', 5, 'valide', '2025-10-18 09:16:09', '2025-10-18 09:16:09', '2025-10-18 09:19:54', NULL),
(4, 'Système de Gestion de bibliotheque en ligne', 'reussite', 'uploads/articles/article_693b0a6d0fce60.11626829_1765476973.pdf', 6, 'valide', '2025-12-11 18:16:13', '2025-12-11 18:16:13', '2025-12-18 18:25:00', 19),
(5, 'Système de Gestion de bibliotheque en ligne', 'reussite', 'C:\\laragon\\www\\Revue-Theologie-Upc\\assets\\pdf\\DOCUMENTATION — Site web pour la Revue de la Faculté de Théologie (Université Protestante au Congo, UPC).pdf', 3, 'soumis', '2025-12-12 22:36:55', '2025-12-12 22:36:55', '2025-12-14 01:57:01', NULL),
(8, 'E.S.G.O.S. digital bank', 'mieux gerer les banques etc...', 'uploads/articles/article_693e2422e62548.00045139_1765680162.pdf', 6, 'valide', '2025-12-13 08:27:45', '2025-12-13 08:27:45', '2025-12-15 12:43:40', NULL),
(9, 'jvj', 'fhjfh', 'uploads/articles/article_694037da9d0458.72422811_1765816282.pdf', 6, 'soumis', '2025-12-15 16:31:22', '2025-12-15 16:31:22', '2025-12-15 16:31:22', NULL),
(10, 'VIVRE LA FOI CHRETIENNE DANS UNE AFRIQUE DESHUMANISEE', 'Cette réflexion a porté sur Vivre la foi chrétienne dans une Afrique déshumanisée ». Nous avons voulu, à travers ces quelques lignes analyser comment il faut inculturer la foi en Afrique pour la rendre crédible dans le vécu quotidien de l\'africain.\r\n\r\nLa question majeure qui nous a guidé était celle de savoir comment les chrétiens vivent leur foi au quotidien et le constat en était que la foi en Afrique ne semble pas produire des effets voulus et attendus. Nous avons pu relever quelques raisons qui, de quelque manière, freinent la réalisation du règne de Dieu dans ce continent.\r\n\r\nVu toutes ces réalités, nous avons proposé à ce que la théologie, en Afrique, soit, repensée conformément aux requêtes africaines. Ainsi, doit-on, d\'abord repenser la crise africaine en profondeur. Il faut, par ailleurs, penser sur la responsabilité des Eglises africaines dans cette crise. Le Christianisme doit, ensuite. accepter de prendre les risques d\'être revu à l\'africain pour permettre à tous les croyants de se retrouver en matière de foi et enfin, il faut pouvoir inventer une théologie propre pour permettre à tous et à chacun de se retrouver dans la foi.\r\n\r\nAu lieu de s\'attarder à reproduire ce que les autres ont réalisé par rapport à leur conditionnement de vie, les africains doivent dire leur foi et arriver à expliciter leur compréhension par rapport à la Parole de Dieu et s\'en approprier le sens.\r\n\r\nL\'enjeu, aujourd\'hui, est d\'avoir un langage propre à l\'Evangile qui parte des gens eux-mêmes pour permettre à tous et chacun de comprendre ce qui est dit dans l\'Evangile. Ce langage aiderait tous les chrétiens à bien exprimer leur foi et à transformer leur monde en paradis terrestre. C\'est dans ce sens que Dieu se mettra à parler << africainement >> à des africains, avec les images de leur culture et de leurs ressources d\'expression.\r\n', 'uploads/articles/article_6978eb4e253a32.33838960_1769532238.pdf', 6, 'valide', '2026-01-27 16:43:58', '2026-01-27 16:43:58', '2026-02-19 23:21:49', 20);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` bigint UNSIGNED NOT NULL,
  `utilisateur_id` bigint UNSIGNED NOT NULL,
  `revue_id` bigint UNSIGNED NOT NULL,
  `contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commentaires`
--

INSERT INTO `commentaires` (`id`, `utilisateur_id`, `revue_id`, `contenu`, `created_at`, `updated_at`) VALUES
(1, 4, 18, 'Pas mal', '2025-10-18 09:06:01', '2025-10-18 09:06:01'),
(2, 4, 16, 'OHHH', '2025-10-18 09:14:14', '2025-10-18 09:14:14'),
(3, 5, 16, 'C\'est fort', '2025-10-18 09:17:14', '2025-10-18 09:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `evaluateur_id` bigint UNSIGNED NOT NULL,
  `statut` enum('en_attente','en_cours','termine','annule') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_assignation` timestamp NULL DEFAULT NULL,
  `date_echeance` date DEFAULT NULL,
  `date_soumission` timestamp NULL DEFAULT NULL,
  `recommendation` enum('accepte','accepte_avec_modifications','revision_majeure','rejete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualite_scientifique` tinyint DEFAULT NULL COMMENT 'Note sur 10',
  `originalite` tinyint DEFAULT NULL COMMENT 'Note sur 10',
  `pertinence` tinyint DEFAULT NULL COMMENT 'Note sur 10',
  `clarte` tinyint DEFAULT NULL COMMENT 'Note sur 10',
  `note_finale` tinyint DEFAULT NULL COMMENT 'Note moyenne sur 10',
  `commentaires_public` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Commentaires visibles par les auteurs',
  `commentaires_prives` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Commentaires pour le comité éditorial',
  `suggestions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Suggestions d''amélioration',
  `decision_finale` enum('en_attente','accepte','rejete','revision_requise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `article_id`, `evaluateur_id`, `statut`, `date_assignation`, `date_echeance`, `date_soumission`, `recommendation`, `qualite_scientifique`, `originalite`, `pertinence`, `clarte`, `note_finale`, `commentaires_public`, `commentaires_prives`, `suggestions`, `decision_finale`, `created_at`, `updated_at`) VALUES
(2, 5, 9, 'termine', '2025-12-13 15:04:40', '2025-12-27', '2025-12-15 12:18:29', 'accepte', 8, 7, 8, 6, 7, 'c\'est bon rien a redire', 'travail impecable', 'structurer mieux', 'en_attente', '2025-12-13 15:04:40', '2025-12-15 12:18:29'),
(4, 8, 9, 'termine', '2025-12-15 12:15:23', '2025-12-29', '2025-12-15 12:21:04', 'accepte', 8, 7, 8, 7, 8, 'c\'est mieux', 'ameliorer', 'tout va', 'en_attente', '2025-12-15 12:15:23', '2025-12-15 12:21:04'),
(3, 4, 9, 'termine', '2025-12-13 23:19:17', '2025-12-27', '2025-12-13 23:29:11', 'accepte', 7, 5, 8, 7, 7, 'mnl', 'lnlnl', 'jlnl', 'en_attente', '2025-12-13 23:19:17', '2025-12-13 23:29:11'),
(18, 9, 8, 'en_attente', '2025-12-18 20:07:48', '2026-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en_attente', '2025-12-18 20:07:48', '2025-12-18 20:07:48'),
(20, 10, 9, 'en_cours', '2026-02-17 19:11:09', '2026-03-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en_attente', '2026-02-17 19:11:09', '2026-02-17 19:11:28');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_08_091828_create_personal_access_tokens_table', 1),
(5, '2025_08_08_104820_create_permission_tables', 1),
(6, '2025_08_08_113259_create_articles_table', 1),
(7, '2025_08_08_113737_create_revues_table', 1),
(8, '2025_08_08_113857_create_revue_article_table', 1),
(9, '2025_08_08_114403_create_abonnements_table', 1),
(10, '2025_08_08_114720_create_paiements_table', 1),
(11, '2025_08_08_115121_create_commentaires_table', 1),
(12, '2025_08_08_115626_create_notes_table', 1),
(13, '2025_08_10_115047_create_notifications_table', 1),
(14, '2025_08_10_130034_rename_article_id_to_revue_id_in_commentaires_table', 1),
(15, '2025_08_11_052407_add_unique_index_to_notes', 1),
(16, '2025_08_11_111425_alter_abonnements_statut_add_expire', 1),
(17, '2025_08_11_112439_add_fichier_path_to_revues_table', 1),
(18, '2025_08_11_112554_create_telechargements_table', 1),
(19, '2025_08_14_065959_add_fichier_path_to_articles_table', 1),
(20, '2025_09_04_092517_create_revue_photos_table', 1),
(21, '2025_09_05_091009_add_numero_to_revues_table', 1),
(22, '2025_09_07_222802_change_date_publication_to_string_in_revues_table', 1),
(23, '2025_09_11_225513_create_revue_parts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint UNSIGNED NOT NULL,
  `revue_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `valeur` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `revue_id`, `user_id`, `valeur`, `created_at`, `updated_at`) VALUES
(1, 18, 3, 5, '2025-10-14 12:57:45', '2025-10-14 12:57:45'),
(2, 18, 4, 3, '2025-10-18 09:06:00', '2025-10-18 09:06:00'),
(3, 16, 4, 5, '2025-10-18 09:14:13', '2025-10-18 09:14:13'),
(4, 16, 5, 3, '2025-10-18 09:17:13', '2025-10-18 09:17:13');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('435f44be-0bb6-46af-94e7-c6ed68cb634a', 'App\\Notifications\\PaymentStatusNotification', 'App\\Models\\User', 3, '{\"type\":\"paiement\",\"statut\":\"valide\",\"paiement_id\":1,\"message\":\"Votre paiement a \\u00e9t\\u00e9 valid\\u00e9.\"}', NULL, '2025-10-18 08:44:58', '2025-10-18 08:44:58'),
('512d7b67-3c5b-487b-8f35-1bd5f7c87bea', 'App\\Notifications\\AbonnementActivatedNotification', 'App\\Models\\User', 3, '{\"type\":\"abonnement\",\"statut\":\"actif\",\"date_debut\":\"2025-10-18\",\"date_fin\":\"2025-12-18\",\"message\":\"Votre abonnement est actif jusqu\\u2019au 2025-12-18.\"}', NULL, '2025-10-18 08:44:58', '2025-10-18 08:44:58'),
('14140f98-5a01-41d0-9d91-a95ea6d64c80', 'App\\Notifications\\PaymentStatusNotification', 'App\\Models\\User', 4, '{\"type\":\"paiement\",\"statut\":\"valide\",\"paiement_id\":2,\"message\":\"Votre paiement a \\u00e9t\\u00e9 valid\\u00e9.\"}', NULL, '2025-10-18 09:08:36', '2025-10-18 09:08:36'),
('08bea3a9-50e4-4210-af10-dda808a66d69', 'App\\Notifications\\AbonnementActivatedNotification', 'App\\Models\\User', 4, '{\"type\":\"abonnement\",\"statut\":\"actif\",\"date_debut\":\"2025-10-18\",\"date_fin\":\"2025-11-18\",\"message\":\"Votre abonnement est actif jusqu\\u2019au 2025-11-18.\"}', NULL, '2025-10-18 09:08:36', '2025-10-18 09:08:36');

-- --------------------------------------------------------

--
-- Table structure for table `paiements`
--

CREATE TABLE `paiements` (
  `id` bigint UNSIGNED NOT NULL,
  `utilisateur_id` bigint UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `moyen` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recu_path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('en_attente','valide','refuse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_paiement` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paiements`
--

INSERT INTO `paiements` (`id`, `utilisateur_id`, `montant`, `moyen`, `recu_path`, `statut`, `date_paiement`, `created_at`, `updated_at`) VALUES
(1, 3, 34.00, 'mobile_money', 'paiements/a0HDuC2CJRxfnvNYGgFY4UAzXwkVcQbZxrmtC3uB.pdf', 'valide', '2025-10-18 08:44:58', '2025-10-14 12:56:28', '2025-10-18 08:44:58'),
(2, 4, 35.00, 'mobile_money', 'paiements/8cMTupbujYzGNjQ3LZLafyn2F3a77XtUca92yzJv.pdf', 'valide', '2025-10-18 09:08:36', '2025-10-18 09:05:07', '2025-10-18 09:08:36'),
(3, 6, 25.00, 'mpesa', NULL, 'valide', '2026-01-27 01:01:00', '2026-01-27 01:01:00', '2026-01-27 01:01:00'),
(4, 10, 25.00, 'mpesa', NULL, 'valide', '2026-01-27 02:01:02', '2026-01-27 02:01:02', '2026-01-27 02:01:02'),
(5, 10, 25.00, 'mpesa', NULL, 'valide', '2026-01-27 02:01:31', '2026-01-27 02:01:31', '2026-01-27 02:01:31'),
(6, 10, 25.00, 'mpesa', NULL, 'valide', '2026-01-27 02:04:28', '2026-01-27 02:04:28', '2026-01-27 02:04:28'),
(7, 6, 30.00, 'mpesa', 'receipts/recu_7_1769486026.html', 'valide', '2026-01-27 03:29:36', '2026-01-27 03:29:36', '2026-01-27 03:53:46'),
(8, 11, 25.00, 'mpesa', NULL, 'valide', '2026-02-06 14:15:24', '2026-02-06 14:15:24', '2026-02-06 14:15:24'),
(9, 6, 25.00, 'mpesa', NULL, 'valide', '2026-02-11 18:25:43', '2026-02-11 18:25:43', '2026-02-11 18:25:43'),
(10, 6, 25.00, 'mpesa', NULL, 'valide', '2026-02-11 21:02:42', '2026-02-11 21:02:42', '2026-02-11 21:02:42'),
(11, 6, 25.00, 'mpesa', NULL, 'valide', '2026-02-17 19:09:41', '2026-02-17 19:09:41', '2026-02-17 19:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(24, 'App\\Models\\User', 4, 'auth_token', 'efa7b5275bad854deec3c673dcef9276e8552e69fd79c66d67666d1b7b57bcfe', '[\"*\"]', '2025-10-18 09:14:46', NULL, '2025-10-18 09:09:08', '2025-10-18 09:14:46');

-- --------------------------------------------------------

--
-- Table structure for table `revues`
--

CREATE TABLE `revues` (
  `id` bigint UNSIGNED NOT NULL,
  `numero` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fichier_path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_publication` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `volume_id` int DEFAULT NULL,
  `type` enum('issue','special') COLLATE utf8mb4_unicode_ci DEFAULT 'issue',
  `issue_id` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revues`
--

INSERT INTO `revues` (`id`, `numero`, `titre`, `description`, `fichier_path`, `date_publication`, `created_at`, `updated_at`, `volume_id`, `type`, `issue_id`) VALUES
(1, '1e Année', 'Revue n°1', 'P. STADLER : DIANGENDA Kuntima, l’histoire du Kimbanguisme, Kinshasa, Editions Kimbanguiste, 1984, 343Pages.', NULL, 'Décembre 1986', '2025-09-17 09:31:24', '2025-09-17 09:31:24', NULL, 'issue', NULL),
(2, '2e Année', 'Revue n°2', NULL, NULL, 'Janvier 1988', '2025-09-17 09:36:35', '2025-09-17 09:36:35', NULL, 'issue', NULL),
(3, '6e Année', 'Revue n°6', NULL, NULL, '1992', '2025-09-17 09:38:50', '2025-09-17 09:38:50', NULL, 'issue', NULL),
(4, '7-8e Année', 'Revue n°7-8', NULL, NULL, '1993-1994', '2025-09-17 09:42:11', '2025-09-17 09:42:11', NULL, 'issue', NULL),
(5, '9-10e Année', 'Revue n°9-10', NULL, NULL, '1995-1996', '2025-09-17 09:44:29', '2025-09-17 09:44:29', NULL, 'issue', NULL),
(6, '11e Année', 'Revue n°11', 'Jubilé d\'argent de la faculté de théologie protestante au Zaïre', NULL, '1959-1984', '2025-09-17 09:50:38', '2025-09-17 09:50:38', NULL, 'issue', NULL),
(7, '12e Année', 'Revue n°12', 'Société Moderne, Postmoderne et Eglise au seuil du 3e millénaire', NULL, '1998', '2025-09-17 09:56:33', '2025-09-17 09:56:33', NULL, 'issue', NULL),
(8, '13e Année', 'Revue n°13', NULL, NULL, '1999', '2025-09-17 09:59:59', '2025-09-17 09:59:59', NULL, 'issue', NULL),
(9, '16-17e Année', 'Revue n°16-17', 'Personne, pensée et œuvre du Professeur Jean Massamba ma Mpolo', NULL, '2003-2004', '2025-09-17 10:03:45', '2025-09-17 10:03:45', NULL, 'issue', NULL),
(10, '20e Année', 'Revue n°20', 'La théologie protestante et l\'avenir de la société congolaise', NULL, '2008', '2025-09-17 10:14:03', '2025-09-17 10:14:03', NULL, 'issue', NULL),
(11, '21e Année', 'Revue n°22', 'L\'homme d\'itunda sa personne et son œuvre', NULL, '2010', '2025-09-17 10:19:40', '2025-09-17 10:19:40', NULL, 'issue', NULL),
(12, '22e Année', 'Revue n°23', 'Eglise de réveil : Défis messianiques et eschatologiques', NULL, 'Spécial 2012', '2025-09-17 10:24:15', '2025-12-18 12:11:12', 1, 'issue', NULL),
(13, '23-24e Année', 'Revue n°24-25', 'EGLISE ET NATIONALISME', NULL, '2013-2014', '2025-09-17 10:27:30', '2025-09-17 10:27:30', NULL, 'issue', NULL),
(15, '26e Année', 'Revue n°27', 'CHRISTIANISME ET INTERCULTURALITE : REGARD CROISE', 'revues/2025/PvRNFKDxNbuYNWUTtZtaywTvxH5UYdhR9pGhd3l2.pdf', '2021', '2025-09-17 13:02:56', '2025-09-17 13:22:59', NULL, 'issue', NULL),
(16, '27e Année', 'Revue n°28', 'THEOLOGIE ET COVID - 19', 'revues/2025/kI99j9TZ0lo0fRzMQmTkTPdKWvAZ84lCDkSnVlOh.pdf', '2022', '2025-09-17 13:07:32', '2025-09-17 13:24:43', NULL, 'issue', NULL),
(17, '28e Année', 'Revue n°29', 'L’Eglise et l’Etat congolais face aux défis  \r\nde la paix et de la sauvegarde de la création : \r\nEnjeux et perspectives croisées', 'revues/2025/UW0bMJsfsPoxjflqdqY2s6bCJDDbtMnLxZHiVre4.pdf', '2023', '2025-09-17 13:43:18', '2025-09-17 13:43:18', NULL, 'issue', NULL),
(18, '29e Année', 'Revue n°30', NULL, 'revues/2025/eE11FffSiS9CI61l8wyEXoKSAOwdk9OQAg7WCrj9.pdf', '2024', '2025-09-17 13:45:00', '2025-09-17 13:45:00', NULL, 'issue', NULL),
(19, 'Numero 1', 'Développement du système de suivi des projets étudiants', 'Enseignemt', NULL, '2024-05-21', '2025-12-18 11:09:56', '2025-12-18 11:09:56', 1, 'issue', NULL),
(20, 'Numero 31', 'Publication Annuelle', 'REVUE CONGOLAISE DE THÉOLOGIE PROTESTANTE\r\n\r\nPubliée par\r\n\r\nUNIVERSITE PROTESTANTE AU CONGO\r\n\r\nCentre de Recherches Interdisciplinaires en Développement: Economie, Santé et Société (CRIDESS)\r\n\r\nProduction de la Faculté de Théologie\r\n\r\nRédacteur en Chef: Prof. KALOMBO Kapuku Sébastien Secrétariat et administration: M. SAMBA Kanguashu Kelly\r\n\r\nEditeur Responsable: Université Protestante au Congo, Croisement de l\'Avenue de Libération et Boulevard Triomphal,\r\n\r\nKinshasa/Lingwala\r\n\r\nTél.: +243 999957767/+243 812279400\r\n\r\ne-mail: rectorat@upc.ac.cd\r\n\r\nB.P. 4745 KINSHASA II', NULL, '2026-01-25', '2026-01-27 16:00:40', '2026-01-27 16:00:40', 3, 'issue', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `revue_article`
--

CREATE TABLE `revue_article` (
  `id` bigint UNSIGNED NOT NULL,
  `revue_id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revue_info`
--

CREATE TABLE `revue_info` (
  `id` int NOT NULL,
  `nom_officiel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Revue de Théologie de l''UPC',
  `description` text COLLATE utf8mb4_unicode_ci,
  `ligne_editoriale` text COLLATE utf8mb4_unicode_ci,
  `objectifs` text COLLATE utf8mb4_unicode_ci,
  `domaines_couverts` text COLLATE utf8mb4_unicode_ci,
  `issn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comite_scientifique` text COLLATE utf8mb4_unicode_ci,
  `comite_redaction` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revue_info`
--

INSERT INTO `revue_info` (`id`, `nom_officiel`, `description`, `ligne_editoriale`, `objectifs`, `domaines_couverts`, `issn`, `comite_scientifique`, `comite_redaction`, `created_at`, `updated_at`) VALUES
(1, 'Revue de Théologie de l\'UPC', 'Revue scientifique de la Faculté de Théologie de l\'Université Protestante au Congo', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-18 10:39:06', '2025-12-18 10:39:06');

-- --------------------------------------------------------

--
-- Table structure for table `revue_parts`
--

CREATE TABLE `revue_parts` (
  `id` bigint UNSIGNED NOT NULL,
  `revue_id` bigint UNSIGNED NOT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auteurs` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pages` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordre` smallint UNSIGNED NOT NULL DEFAULT '0',
  `file_path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_free_preview` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revue_parts`
--

INSERT INTO `revue_parts` (`id`, `revue_id`, `type`, `titre`, `auteurs`, `pages`, `ordre`, `file_path`, `is_free_preview`, `created_at`, `updated_at`) VALUES
(1, 16, 'autre', 'Page de garde', NULL, '1-2', 0, '16/page-de-garde.pdf', 1, '2025-09-17 13:56:54', '2025-09-17 13:56:54'),
(2, 16, 'autre', 'Sommaire', NULL, '3-4', 1, '16/sommaire.pdf', 1, '2025-09-17 13:57:33', '2025-09-17 13:57:33'),
(3, 16, 'editorial', 'Editorial', NULL, '5', 2, '16/editorial.pdf', 1, '2025-09-17 13:57:55', '2025-09-17 13:57:55'),
(5, 16, 'article', 'QUEL ETAIT LE PECHE DE CHAM SELON Gn 9, 20-27 ?   LA THEORIE DE L’INCESTE REVISITEE', 'BOKUNDOA bo-Likabe', '7-29', 3, '16/quel-etait-le-peche-de-cham-selon-gn-9-20-27-la-theorie-de-linceste-revisitee.pdf', 0, '2025-09-17 14:00:49', '2025-09-17 14:00:49'),
(6, 16, 'article', 'LE CULTE EN PERIODE POST-CONFINEMENT COVID-19 A LA LUMIERE DE LA BIBLE', 'MEME Dingadie Monger', '31-50', 4, '16/le-culte-en-periode-post-confinement-covid-19-a-la-lumiere-de-la-bible.pdf', 0, '2025-09-17 14:02:31', '2025-09-17 14:02:31'),
(7, 16, 'article', 'PRUDENCE DANS LA BIBLE : REGARDS NEOTESTAMENTAIRES. POUR QUELLE THEOLOGIE FACE A LA COVID-19', 'KABUE Mbala Simon', '51-57', 5, '16/prudence-dans-la-bible-regards-neotestamentaires-pour-quelle-theologie-face-a-la-covid-19.pdf', 0, '2025-09-17 14:03:33', '2025-09-17 14:03:33'),
(8, 16, 'article', 'REFLEXION THEOLOGIQUE SUR LE HANDICAP                A LA LUMIERE D’EXODE 4, 10-12 ET LEVITIQUE 21, 16-24', 'ALIPANAZANGA Ataningamu Faustin', '59-70', 6, '16/reflexion-theologique-sur-le-handicap-a-la-lumiere-dexode-4-10-12-et-levitique-21-16-24.pdf', 0, '2025-09-17 14:05:02', '2025-09-17 14:05:02'),
(9, 16, 'article', 'LECTURE CONTEXTUELLE DE JEAN 17, 20-23 ET SON IMPLI CATION DANS LA VIE DU CONGOLAIS', 'IWEWE Nkoy Jean Robert', '71-88', 7, '16/lecture-contextuelle-de-jean-17-20-23-et-son-impli-cation-dans-la-vie-du-congolais.pdf', 0, '2025-09-17 14:06:19', '2025-09-17 14:06:19'),
(10, 16, 'article', 'LA RELIGION ET LA MAGIE : SŒURS SIAMOISES OU JUMELLES ?', 'MANDEFU Buanga Jeannot', '89-112', 8, '16/la-religion-et-la-magie-soeurs-siamoises-ou-jumelles.pdf', 0, '2025-09-17 14:07:21', '2025-09-17 14:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `revue_photos`
--

CREATE TABLE `revue_photos` (
  `id` bigint UNSIGNED NOT NULL,
  `revue_id` bigint UNSIGNED NOT NULL,
  `path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revue_photos`
--

INSERT INTO `revue_photos` (`id`, `revue_id`, `path`, `caption`, `created_at`, `updated_at`) VALUES
(1, 1, 'revues/photos/2025/fG2RcmRrplmYS6r5H6UjFRcXZYbInNDTGW05XV8Q.jpg', NULL, '2025-09-17 09:31:25', '2025-09-17 09:31:57'),
(2, 1, 'revues/photos/2025/rWEItAXuuwOsgRY6l06IVQfPNFW4leg7hkxYGcw4.jpg', NULL, '2025-09-17 09:31:25', '2025-09-17 09:32:05'),
(3, 1, 'revues/photos/2025/ckttJetZ04RQ53hlAgkLWh4p5WF24oWttOW3WV7K.jpg', NULL, '2025-09-17 09:31:25', '2025-09-17 09:32:14'),
(4, 1, 'revues/photos/2025/X6xnErSqB5d1TGtaGWnXUmexIFQdy99TuAZRSBHy.jpg', NULL, '2025-09-17 09:31:25', '2025-09-17 09:32:29'),
(5, 1, 'revues/photos/2025/0XvYsEknxoxZg050WnYw8yOxJP1fRRjH7jdNq6Vb.jpg', NULL, '2025-09-17 09:31:25', '2025-09-17 09:32:37'),
(6, 1, 'revues/photos/2025/hggxix6jLSXjKKFoVOGcWaRezqQgn31kaXvdRRqs.jpg', NULL, '2025-09-17 09:31:25', '2025-09-17 09:32:48'),
(7, 2, 'revues/photos/2025/a3t5Fg35SLvIjGbXpulIrfBsuxSTabSQxLE1b5t9.jpg', NULL, '2025-09-17 09:36:35', '2025-09-17 09:36:35'),
(8, 2, 'revues/photos/2025/qhv9Lfg63SfW7J9XaiWsie6RvbwGn7Tde9UZpJp5.jpg', NULL, '2025-09-17 09:36:56', '2025-09-17 09:36:56'),
(9, 2, 'revues/photos/2025/F835BpDF8EzNUQzDBQtwJffSgwpRwnTLnKovisF1.jpg', NULL, '2025-09-17 09:36:56', '2025-09-17 09:36:56'),
(10, 2, 'revues/photos/2025/wYfXDJub4Q7AgWmZ9UV3xZDukTS5xEYRlvxCXSAJ.jpg', NULL, '2025-09-17 09:37:11', '2025-09-17 09:37:11'),
(11, 2, 'revues/photos/2025/3CBcThjqZm2WGlm6PE5BDX8SHgGE9znPFCA8Y25R.jpg', NULL, '2025-09-17 09:37:11', '2025-09-17 09:37:11'),
(12, 3, 'revues/photos/2025/1mccUKFNtlnPaZ9oLpC2l3a7HRcFikaG4xwbzyLQ.jpg', NULL, '2025-09-17 09:38:50', '2025-09-17 09:38:50'),
(13, 4, 'revues/photos/2025/JABzEX1oBAnmYioddc2v4wtTLHlaucwtgMAcKBMz.jpg', NULL, '2025-09-17 09:42:11', '2025-09-17 09:42:11'),
(14, 4, 'revues/photos/2025/ScYvloUjFdonjD6de40yxCOOtRWSySWHOZzwem6i.jpg', NULL, '2025-09-17 09:42:24', '2025-09-17 09:42:24'),
(15, 4, 'revues/photos/2025/BuG5xwsA2lYXYQBKs6tVZWYk0JLXmQYOTRVfRZml.jpg', NULL, '2025-09-17 09:42:24', '2025-09-17 09:42:24'),
(16, 5, 'revues/photos/2025/a2ZRECAWkJGuqpeiOEUwzxhJeoLwpuCgEhVSqKCW.jpg', NULL, '2025-09-17 09:44:29', '2025-09-17 09:44:29'),
(17, 5, 'revues/photos/2025/4AWVagIKCsybynOcRubI6iUhf8tmAXRTbipxWG21.jpg', NULL, '2025-09-17 09:45:30', '2025-09-17 09:45:30'),
(18, 5, 'revues/photos/2025/ZUYam9svSXGcLqjJJYJ5kAO650lmr60IgfNcNW5q.jpg', NULL, '2025-09-17 09:45:30', '2025-09-17 09:45:30'),
(19, 5, 'revues/photos/2025/IL008p1gDuLOEDvCEirAUprWNRZGy50btSWVvCii.jpg', NULL, '2025-09-17 09:45:30', '2025-09-17 09:45:30'),
(20, 6, 'revues/photos/2025/fXUeW4WHH2WMxuDYz7lWuHngEAJLiW5LKq9mq4f5.jpg', NULL, '2025-09-17 09:50:38', '2025-09-17 09:50:38'),
(21, 6, 'revues/photos/2025/ifYCqv2aNABKeQ66E8DFXW6LODN9g5YgvxcA7nSW.jpg', NULL, '2025-09-17 09:50:50', '2025-09-17 09:50:50'),
(22, 6, 'revues/photos/2025/EbndekzxfyjT93BXgWNsEiIy8vKjQlBnzn3DhlhC.jpg', NULL, '2025-09-17 09:50:50', '2025-09-17 09:50:50'),
(23, 6, 'revues/photos/2025/oLhedR9VufYHFY5Zp2p2c86rRBEZmKZy6ueLzEYP.jpg', NULL, '2025-09-17 09:50:50', '2025-09-17 09:50:50'),
(24, 7, 'revues/photos/2025/7AIeac5uZJhwRd6A7SLc95Pu4G8dciSk6xB4gwYY.jpg', NULL, '2025-09-17 09:56:33', '2025-09-17 09:56:33'),
(25, 7, 'revues/photos/2025/NnF7zRsfXdJeyxFH5rTfYisVIkSCUDluZMCsEGYY.jpg', NULL, '2025-09-17 09:56:40', '2025-09-17 09:56:40'),
(26, 7, 'revues/photos/2025/Q9M18vvTHGcCqRvll6NiaEH54FgQ3RooVTs4og5v.jpg', NULL, '2025-09-17 09:56:49', '2025-09-17 09:56:49'),
(27, 7, 'revues/photos/2025/orJmuHc5Y0egEn91kIRyz7hnK9wEh5gwqzBpIOH6.jpg', NULL, '2025-09-17 09:56:49', '2025-09-17 09:56:49'),
(28, 7, 'revues/photos/2025/N8G1Dwa7K5MBMrUWgZcZhQyw5mxY5DUxEWcigehn.jpg', NULL, '2025-09-17 09:56:49', '2025-09-17 09:56:49'),
(29, 8, 'revues/photos/2025/7nJH8pV05zloPdwWYaBZj8pGKdsJ82zhTlZWYcYP.jpg', NULL, '2025-09-17 09:59:59', '2025-09-17 09:59:59'),
(30, 8, 'revues/photos/2025/xGzPc2B2Nw9HtgNZbM1IqL0NHChk7LBKaimedQAz.jpg', NULL, '2025-09-17 10:00:10', '2025-09-17 10:00:10'),
(31, 8, 'revues/photos/2025/OgHnKUf3Jfr7GNfMZFmqaa9AyojnZ8glhiUSF0gA.jpg', NULL, '2025-09-17 10:00:23', '2025-09-17 10:00:23'),
(32, 8, 'revues/photos/2025/jnEVC7A7JdvmGXhHHy5VfmWP4tSnEClZeoJi1ebj.jpg', NULL, '2025-09-17 10:00:23', '2025-09-17 10:00:23'),
(33, 9, 'revues/photos/2025/tVxjuXjQnwj5BPOKIdpWkAtCtufduwgQupZG1UVW.jpg', NULL, '2025-09-17 10:03:45', '2025-09-17 10:03:45'),
(34, 9, 'revues/photos/2025/8D2KOsRP4IYsJ702iyugDGUNvoua5vM10pWcQDNE.jpg', NULL, '2025-09-17 10:03:56', '2025-09-17 10:03:56'),
(35, 9, 'revues/photos/2025/ztGULQ1mHMTzAByx2dvpvEPel01hZ36eGdGVKyt0.jpg', NULL, '2025-09-17 10:04:04', '2025-09-17 10:04:04'),
(36, 9, 'revues/photos/2025/2fbXVkszkHKXZGqo7rYLF3hj60oQFTC1wl14NV8V.jpg', NULL, '2025-09-17 10:04:04', '2025-09-17 10:04:04'),
(37, 10, 'revues/photos/2025/BqhfCGtks2G2TJoVyb01D1EpvGFTw8uCOGiFHf12.jpg', NULL, '2025-09-17 10:14:03', '2025-09-17 10:14:03'),
(38, 10, 'revues/photos/2025/yhXjeAkZL5sVAydrWTg2kpGd4LZDTRw4Fvtq14jC.jpg', NULL, '2025-09-17 10:14:18', '2025-09-17 10:14:18'),
(39, 10, 'revues/photos/2025/uFRDs9NVLAm1bxqVl0ibNSDyA6IONLMrLygz5jbE.jpg', NULL, '2025-09-17 10:14:25', '2025-09-17 10:14:25'),
(40, 10, 'revues/photos/2025/VcGChubEtJxuLZtk6SD3tr4iJkOK5l0tRYCDgAkj.jpg', NULL, '2025-09-17 10:14:36', '2025-09-17 10:14:36'),
(41, 10, 'revues/photos/2025/PUKBw2kg4sw08uceiROV7hgcWAUBQyBLvWDmD6FM.jpg', NULL, '2025-09-17 10:14:36', '2025-09-17 10:14:36'),
(42, 10, 'revues/photos/2025/gi65xaJbIUfA2AqYTrZE0Jz5VB49DQkzQ4LMZa4z.jpg', NULL, '2025-09-17 10:14:36', '2025-09-17 10:14:36'),
(43, 11, 'revues/photos/2025/6JMfAQI2HulElUaY2QWe6S8YZJESltw7liHb2ayD.jpg', NULL, '2025-09-17 10:19:40', '2025-09-17 10:19:40'),
(44, 11, 'revues/photos/2025/fKjxDelK3DTX36yjABo2Uqiz843yJTQUixwq2c6P.jpg', NULL, '2025-09-17 10:20:36', '2025-09-17 10:20:36'),
(45, 11, 'revues/photos/2025/G8q2iA5jBcf2kubHdTEaHtxJDu1FCaU2G5XQ4TYg.jpg', NULL, '2025-09-17 10:20:53', '2025-09-17 10:20:53'),
(46, 11, 'revues/photos/2025/uFUCX2NrzgpoQSUvJ7DzKqtRVQIegbKbYLqOEDcQ.jpg', NULL, '2025-09-17 10:20:53', '2025-09-17 10:20:53'),
(47, 11, 'revues/photos/2025/bSNFnvUSlSxnrWNjU9fpYaWjbEQV6X7gUgBOJcGn.jpg', NULL, '2025-09-17 10:20:53', '2025-09-17 10:20:53'),
(48, 11, 'revues/photos/2025/CVKcQDVqoEzMHzpXDvSHpoFWea6Ezuo1UPJFMluY.jpg', NULL, '2025-09-17 10:20:53', '2025-09-17 10:20:53'),
(49, 11, 'revues/photos/2025/GRP1y8jtix2WKgqPuk7fKIxSF3ighgNq5s0iSnVq.jpg', NULL, '2025-09-17 10:20:53', '2025-09-17 10:20:53'),
(50, 12, 'revues/photos/2025/yOHX7tHpioqOD3uUAt0ucdCU5YCdePIHVUWQSncD.jpg', NULL, '2025-09-17 10:24:15', '2025-09-17 10:24:15'),
(51, 12, 'revues/photos/2025/sUocu1TjCTGhD97RKtZlKjNZuLfUKJZiIL54eKtt.jpg', NULL, '2025-09-17 10:25:21', '2025-09-17 10:25:21'),
(52, 12, 'revues/photos/2025/9b8zmUzN2EOh4WTxlBDj46wQ0211dQYXNWPzVax6.jpg', NULL, '2025-09-17 10:25:30', '2025-09-17 10:25:30'),
(53, 12, 'revues/photos/2025/ZLwbKDR9rl4kEOeaypm36YBClsx1Apqsk5Qyzrpw.jpg', NULL, '2025-09-17 10:25:30', '2025-09-17 10:25:30'),
(54, 12, 'revues/photos/2025/GbULgaEU8SfRd8OFYZL1ut7uhbLs0AVAnj5NLzVf.jpg', NULL, '2025-09-17 10:25:30', '2025-09-17 10:25:30'),
(55, 13, 'revues/photos/2025/bTOyokPNbI0wFR1Rd6s2EcSrk5utCGkOZQBPO1ru.jpg', NULL, '2025-09-17 10:27:30', '2025-09-17 10:27:30'),
(56, 13, 'revues/photos/2025/2he918m6iHfQRdV6WMBw43Y4w5RQko3yhCG86Ogx.jpg', NULL, '2025-09-17 10:27:39', '2025-09-17 10:27:39'),
(57, 13, 'revues/photos/2025/xXEv9gfSoPLO3kcxXJuP09eOs1mDIbVndJJuYYDa.jpg', NULL, '2025-09-17 10:27:46', '2025-09-17 10:27:46'),
(58, 13, 'revues/photos/2025/wzlBpNXdyOdjFTN84B0kRDtcma12forIQqcSae09.jpg', NULL, '2025-09-17 10:27:54', '2025-09-17 10:27:54'),
(59, 13, 'revues/photos/2025/eMOnNqNzqrw8dqrLbuKMRd7pYgjmnSp6m2xANwDQ.jpg', NULL, '2025-09-17 10:27:54', '2025-09-17 10:27:54'),
(60, 16, 'revues/photos/2025/1Q44ER2TsMr16QMSGn0lS9JN41yYVE0KH6FJmqMl.jpg', NULL, '2025-09-17 13:07:32', '2025-09-17 13:07:32'),
(61, 16, 'revues/photos/2025/p93CjmkcnZmAM1rtuTwY9ThJ4fndLTONtlJGhpwr.jpg', NULL, '2025-09-17 13:11:18', '2025-09-17 13:11:18'),
(62, 16, 'revues/photos/2025/hGBlMDlCKLfQXHafyrZtANOUVzzMR1reWwXVSez6.jpg', NULL, '2025-09-17 13:11:38', '2025-09-17 13:11:38'),
(63, 16, 'revues/photos/2025/peMRqmzSrYWOQjl8jGmAHfCsJKJI3KufOPzyeQDI.jpg', NULL, '2025-09-17 13:12:11', '2025-09-17 13:12:11'),
(64, 16, 'revues/photos/2025/TSCM7paSWPGu2VqJRoySovtJFfziPt8sBkLhZqaM.jpg', NULL, '2025-09-17 13:12:20', '2025-09-17 13:12:20'),
(65, 17, 'revues/photos/2025/qkTwoTIQiwTgbia66pgVtSMf9SAAg5smat86Chg0.jpg', NULL, '2025-09-17 13:43:18', '2025-09-17 13:43:18'),
(66, 17, 'revues/photos/2025/0kTiYmNYkm2dqyH3tHIQaY5OALcnrpCOJ7GV61er.jpg', NULL, '2025-09-17 13:43:28', '2025-09-17 13:43:28'),
(67, 17, 'revues/photos/2025/J6fFx4kF2lqeVmiJ0OtELg7BJR0fungqEwT1FAIg.jpg', NULL, '2025-09-17 13:43:28', '2025-09-17 13:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'rédacteur en chef', 'sanctum', '2025-09-17 09:23:29', '2025-09-17 09:23:29'),
(2, 'auteur', 'sanctum', '2025-09-17 09:23:29', '2025-09-17 09:23:29'),
(3, 'abonné', 'sanctum', '2025-09-17 09:23:29', '2025-09-17 09:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sdObAdrfUd9PlbmCHscE2GGFy7yjqCvo6lam5lqJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidHlLU0haZ29ubU40Wk9SRmswVkJ0cmFISTFtV1g5eDhEa01xcXpsZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760783897),
('BZdXt5is5gYApt9bXnQ9Bcdlt3vXFSaO9DAEylyC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRDV6Qk9BQVlRMDRHNFhGS21Sd2JFT2lPVHJtY25ka2lBT05IRG5WZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zcmMvYXNzZXRzL3V1dS53ZWJwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760780722);

-- --------------------------------------------------------

--
-- Table structure for table `telechargements`
--

CREATE TABLE `telechargements` (
  `id` bigint UNSIGNED NOT NULL,
  `utilisateur_id` bigint UNSIGNED NOT NULL,
  `revue_id` bigint UNSIGNED NOT NULL,
  `date_heure` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `telechargements`
--

INSERT INTO `telechargements` (`id`, `utilisateur_id`, `revue_id`, `date_heure`, `ip`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 4, 16, '2025-10-18 09:12:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-18 09:12:14', '2025-10-18 09:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('actif','suspendu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','user','auteur','redacteur','redacteur en chef') COLLATE utf8mb4_unicode_ci DEFAULT 'user'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `email_verified_at`, `password`, `statut`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'Principal', 'admin@revue.com', NULL, '$2y$12$jX3Godwf15jBvolMj/K99OkrT378w5XEZocfbr0XtaAHxE41DqA6W', 'actif', NULL, '2025-09-17 09:23:29', '2025-09-17 09:23:29', 'user'),
(2, 'lumbu', 'Beni', 'lumbubeni3@gmail.com', NULL, '$2y$12$yVfW6cLn/yh6oJSKLwFA.uJxX5.2s7/taMbmla7sJj0LOpqUglGIe', 'actif', NULL, '2025-10-12 09:21:38', '2025-10-14 21:19:34', 'user'),
(3, 'lumbu', 'Beni', 'abonne@revue.com', NULL, '$2y$12$qCeQXvdWPLufwk6Pcne/c.Q1mhjQoLkwlIjWBcl/Ox5TJkhQvq6Fq', 'actif', NULL, '2025-10-14 12:55:11', '2025-10-14 12:55:11', 'user'),
(4, 'Wazenga', 'Gondwe', 'waz@gmail.com', NULL, '$2y$12$gz6UVx3CogI61Ofb0mnAWuKlL7KqzEn2393cBK6kDbpdOcDYtKlkG', 'actif', NULL, '2025-10-18 08:57:30', '2025-10-18 08:57:30', 'user'),
(5, 'Singo', 'Stephanie', 'Kobi@gmail.com', NULL, '$2y$12$1QyOI4ZlYXLdMR.XLo4zwOG08How5pIhqZmbrI5V20A7YRldVYTb2', 'actif', NULL, '2025-10-18 08:58:23', '2025-10-18 08:58:23', 'user'),
(6, 'Mwamba', 'Nathan', 'nathymwamba@gmail.com', NULL, '$2y$10$PS/M6hvBUHf.ZzQxgeY0veHgcL.nrskcQbqLrEUq0AsjruqJYgGf.', 'actif', NULL, '2025-12-10 22:41:49', '2026-02-17 19:09:41', 'auteur'),
(8, 'Noir', 'Zetsu', 'zetsunoir@gmail.com', NULL, '$2y$12$9YhGh2NbHeGHU30lywA4Ku1SfQ3Mz2PirI/JfPKL2r/erORA.cNFe', 'actif', NULL, '2025-12-12 12:12:20', '2025-12-12 12:12:20', 'admin'),
(9, 'Paysayo', 'Cesar', 'cesar@gmail.com', NULL, '$2y$10$n5qvyNs1JYP8E2wtma6EP.XgGLLUF/MLaMtuHOwutieC/YXbqBq.S', 'actif', NULL, '2025-12-13 12:57:46', '2025-12-13 12:57:46', 'redacteur'),
(10, 'Ngonde', 'Bradley', 'bradley.ngonde3@gmail.com', NULL, '$2y$10$YgbFnP4fz/3bP6D7hUGVWeMsP6Ufvh1PD9CTFOKn3opvj1nzhKZEi', 'actif', NULL, '2026-01-27 01:35:06', '2026-01-27 02:37:15', 'auteur'),
(11, 'Mus', 'Petelo ', 'petelo@gmail.com', NULL, '$2y$10$S8tiNKXVVJ/moMIAXaq1pOkWeI6Ymsc5cJ75f1lzmOVcGdlwWQuci', 'actif', NULL, '2026-02-06 14:13:57', '2026-02-06 14:42:51', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `volumes`
--

CREATE TABLE `volumes` (
  `id` int NOT NULL,
  `annee` int NOT NULL,
  `numero_volume` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `comite_editorial` text COLLATE utf8mb4_unicode_ci,
  `redacteur_chef` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comite_scientifique` text COLLATE utf8mb4_unicode_ci,
  `comite_redaction` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `volumes`
--

INSERT INTO `volumes` (`id`, `annee`, `numero_volume`, `description`, `comite_editorial`, `redacteur_chef`, `comite_scientifique`, `comite_redaction`, `created_at`, `updated_at`) VALUES
(1, 2025, 'Volume 1', 'Enseignement', NULL, NULL, NULL, NULL, '2025-12-18 10:48:17', '2025-12-18 10:48:17'),
(2, 2024, 'Volume 1', 'Enseignement', NULL, NULL, NULL, NULL, '2025-12-18 13:26:24', '2025-12-18 13:26:24'),
(3, 2026, '30 eme Annee', 'EDITORIAL\r\n\r\nLa Revue Congolaise de Théologie Protestante est une publication académique qui porte une histoire et une mission singulières. Son premier numéro, paru en 1986, témoignait déjà de l\'ambition d\'offrir un espace de réflexion scientifique et théologique. À cette époque, l\'université Protestante au Congo (UPC) ne comptait que la seule Faculté de Théologie. Aujourd\'hui, l\'UPC a grandi, s\'est diversifiée et s\'est dotée de plusieurs autres facultés, ouvrant la voie à un dialogue fécond entre disciplines.\r\n\r\nC\'est précisément cette ouverture qui confère à la Revue son caractère interdisciplinaire: ici, les théologies se conjuguent au pluriel: plurialité culturelle, plurialité contextuelle, plurarialité d\'approches. La réflexion théologique ne se limite pas à ses frontières classiques, mais s\'enrichit de l\'histoire, des sciences sociales, du droit, de la médecine, de l\'économie, des sciences informatiques, et de tous les champs de savoir qui permettent de mieux comprendre l\'homme, la société et Dieu à l\'œuvre dans le monde.\r\n\r\nAfin d\'assurer sa crédibilité scientifique et sa visibilité internationale, la Revue invite ses contributeurs à s\'inscrire dans le respect des lignes éditoriales et des directives de publication. La rigueur académique, la pertinence contextuelle et l\'originalité des contributions constituent les fondements sur lesquels repose son rayonnement.\r\n\r\nCette orientation s\'aligne sur la politique de recherche de l\'Université Protestante au Congo, qui vise à faire de l\'UPC une université de recherche reconnue. compétitive et connectée aux débats scientifiques mondiaux. La Revue s\'affirme ainsi comme l\'un des instruments de valorisation de la recherche, de diffusion des savoirs et de positionnement de l\'UPC sur la scène internationale.\r\n\r\nÀ travers chaque numéro, la Revue Congolaise de Théologie Protestante souhaite devenir davantage qu\'un lieu de publication: un espace de rencontre des disciplines, un carrefour des théologies, et une voix porteuse d\'innovation et de pertinence pour l\'Église, l\'Université et la société.\r\n\r\nProfesseur BAKENGELA Shamba Patrick\r\n\r\nSecrétaire Général à la Recherche', 'Comité scientifique et de Rédaction\r\n\r\nProf. N\'KWIM Bibi-Bikan Robert, Prof. Em. MUNAYI Muntu-Monji Thomas, Prof. BOKUNDOA Bo-Likabe André-Gédéon, Prof. MEME Dingadie Monger Andermon, Prof. KALOMBO Kapuku Sébastien, Prof. MUENYI Kamwinga Honoré, Prof. VIBILA Vuadi Liz, Prof. MUSHAGALUSA Baciyunjuze Timothée, Prof. MAKUTA Likombe Bijoux, Prof. KABUE Mbala Simon, Prof. ANGENDU Mongenzo Raymond, Prof. NGANGURA ManYanya Lévi, Prof. SANGUMA T. Mossai, Prof. N\'LANDU Moyo Esther, Prof. RAZANAD-RAKOTO A. Haritsima, Prof. KABASELE Mukenge André, Prof. NGALULA Tshianda José, Prof. M\'BULU Zola-di-Muanzabang Jean, Prof. KIAZAYILA Kingengo Pierre, Prof. ABEL Olivier, Prof. FATH Sébastien, Prof. ALIANGO Marachto Dédé, Prof. ALIPANAZANGA Atan-Igamu Faustin, Prof. NGONGO Kilongo Fatuma, Prof. MANDEFU Buanga Jeannot, Prof.BAKENGELA Shamba Patric, Prof. KALALA Tshimpaka Frederic, Prof. Johann Udo Steffens, Prof. KISUKULU Kayembelyuba Don Yves, Prof. LYANDJA Betulu Jean-Calvin, C.T. GBEMA Mbanza Moïse, CT. MOSSI Nzimba Wanyolo Sadath, C.T. MUTEJ Jonathan, CT. ALENGE Wedianu Gisèle, C.T. MAZUNDA Ibula Reagan et Monsieur NGAIE Jean-Victor.', NULL, NULL, NULL, '2026-01-27 15:54:54', '2026-01-30 22:38:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abonnements`
--
ALTER TABLE `abonnements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `abonnements_utilisateur_id_foreign` (`utilisateur_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articles_auteur_id_foreign` (`auteur_id`),
  ADD KEY `idx_issue_id` (`issue_id`);

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
-- Indexes for table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commentaires_utilisateur_id_foreign` (`utilisateur_id`),
  ADD KEY `commentaires_article_id_foreign` (`revue_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_article_evaluateur` (`article_id`,`evaluateur_id`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_date_echeance` (`date_echeance`),
  ADD KEY `idx_recommendation` (`recommendation`),
  ADD KEY `idx_evaluateur_id` (`evaluateur_id`),
  ADD KEY `idx_article_id` (`article_id`);

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
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notes_revue_user_unique` (`revue_id`,`user_id`),
  ADD KEY `notes_user_id_foreign` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiements_utilisateur_id_foreign` (`utilisateur_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `revues`
--
ALTER TABLE `revues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_volume_id` (`volume_id`),
  ADD KEY `idx_issue_id` (`issue_id`);

--
-- Indexes for table `revue_article`
--
ALTER TABLE `revue_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revue_article_revue_id_foreign` (`revue_id`),
  ADD KEY `revue_article_article_id_foreign` (`article_id`);

--
-- Indexes for table `revue_info`
--
ALTER TABLE `revue_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revue_parts`
--
ALTER TABLE `revue_parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `revue_parts_revue_id_ordre_unique` (`revue_id`,`ordre`);

--
-- Indexes for table `revue_photos`
--
ALTER TABLE `revue_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revue_photos_revue_id_foreign` (`revue_id`);

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
-- Indexes for table `telechargements`
--
ALTER TABLE `telechargements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `telechargements_revue_id_foreign` (`revue_id`),
  ADD KEY `telechargements_utilisateur_id_revue_id_index` (`utilisateur_id`,`revue_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `volumes`
--
ALTER TABLE `volumes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `annee` (`annee`),
  ADD KEY `idx_annee` (`annee`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abonnements`
--
ALTER TABLE `abonnements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `revues`
--
ALTER TABLE `revues`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `revue_article`
--
ALTER TABLE `revue_article`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revue_info`
--
ALTER TABLE `revue_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `revue_parts`
--
ALTER TABLE `revue_parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `revue_photos`
--
ALTER TABLE `revue_photos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `telechargements`
--
ALTER TABLE `telechargements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `volumes`
--
ALTER TABLE `volumes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

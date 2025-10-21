-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 oct. 2025 à 23:58
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `trouvix`
--

-- --------------------------------------------------------

--
-- Structure de la table `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) DEFAULT 'Anonyme',
  `category` varchar(50) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `video` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `validated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `title`, `content`, `author`, `category`, `date`, `video`, `attachment`, `validated`) VALUES
(13, 'Sit et alias tempore', 'Non quos pariatur Q', 'Qui unde illum mini', 'annonces', '2025-10-20 09:52:36', 'https://www.bilofetuxucapi.tv', '', 1),
(14, 'Eum elit quia qui d', 'Et laboriosam volup', 'Incidunt reiciendis', 'annonces', '2025-10-20 09:52:45', 'https://www.qadopaja.info', '', 1),
(15, 'Deleniti nostrud ali', 'Dicta sit quae deser', 'Officia tempor non m', 'general', '2025-10-20 09:53:08', 'https://www.rusyvejuveb.mobi', '', 1),
(16, 'Consectetur id conse', 'Voluptate aut offici', 'Suscipit culpa quibu', 'entraide', '2025-10-20 09:53:27', 'https://www.had.biz', '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `host` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salons`
--

CREATE TABLE `salons` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  `maxJoueurs` int(11) NOT NULL,
  `longueurMot` int(11) NOT NULL,
  `joueurs` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `nom_hote` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salons`
--

INSERT INTO `salons` (`id`, `nom`, `code`, `maxJoueurs`, `longueurMot`, `joueurs`, `created_at`, `nom_hote`) VALUES
(116, 'SALON1', 'VIX-TGRM', 3, 7, '[{\"nom\":\"TEST\",\"photo\":\"..\\/uploads\\/photo_68e8f6312759c5.92520553.png\",\"estHote\":true}]', 1760648081, 'TEST'),
(117, 'Autem sunt aut est commodo no', 'VIX-AQI7', 2, 11, '[{\"nom\":\"USER-TEST \",\"photo\":\"..\\/uploads\\/photo_68f5200cd8a164.86001932.jpg\",\"estHote\":true},{\"nom\":\"\",\"photo\":\"\",\"estHote\":false}]', 1761080651, 'USER-TEST ');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `last_activity` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `otp`, `email`, `ville`, `created_at`, `is_admin`, `last_activity`, `photo`) VALUES
(4, 'admin', '$2y$10$ydFmmyftrG3Lr72d/DwXGOw9YmqDruksJfkWTnDF/mnlR4w6k5G1.', 'atexotest20@gmail.com', 'Lille', '2025-10-08 01:18:03', 1, '2025-10-20 09:57:52', NULL),
(22, 'USER-TEST ', '$2y$10$XGTRcCu6auJvOQ58yzRaKedjX2RV9RzEZBzYhTd6oGrqwkg.MV9GG', 'trouvix@mailinator.com', 'Reiciendis atque non', '2025-10-19 17:29:48', 0, '2025-10-21 23:36:52', '../uploads/photo_68f5200cd8a164.86001932.jpg'),
(23, 'Benchou', '$2y$10$VRXyU2MM22FgLpewAdJPgOCVbeQSluLuBgA.u1S7cFJjoEOibIW4W', 'benchou@mailinator.com', 'Qui sint deleniti ad', '2025-10-19 17:31:52', 0, '2025-10-21 23:24:01', '../uploads/photo_68f5208841fc72.74397387.png'),
(24, 'Ferrari', '$2y$10$pCYCp04MtnG/vifwVm4pCuBFQAxrFm9zeXh9ieu9oAiyFzYn4W4si', 'ferrari@mailina.com', 'Brazzaville', '2025-10-19 17:35:30', 0, '2025-10-20 09:57:45', '../uploads/photo_68f52162eb11b2.89504932.jfif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `salons`
--
ALTER TABLE `salons`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT pour la table `salons`
--
ALTER TABLE `salons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

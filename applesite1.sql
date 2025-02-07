-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 17 août 2024 à 05:03
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `applesite1`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id_adresse` int(11) NOT NULL,
  `rue` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `pays` varchar(50) DEFAULT 'Canada',
  `numero` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adresse_utilisateur`
--

CREATE TABLE `adresse_utilisateur` (
  `id_adresse` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `date_commande` datetime NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `quantite_total` int(11) NOT NULL,
  `prix_total` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `date_commande`, `id_utilisateur`, `quantite_total`, `prix_total`) VALUES
(1, '2024-07-22 20:51:28', 17, 2, '290'),
(2, '2024-07-22 20:55:01', 17, 1, '145'),
(3, '2024-07-22 21:10:07', 17, 5, '725'),
(4, '2024-07-22 22:08:53', 17, 1, '145'),
(5, '2024-07-23 00:00:58', 17, 1, '145'),
(6, '2024-07-23 00:59:23', 17, 2, '290'),
(7, '2024-07-23 01:54:50', 17, 2, '290'),
(8, '2024-07-23 14:56:30', 17, 4, '580'),
(9, '2024-07-23 14:57:43', 17, 1, '145'),
(10, '2024-07-23 14:58:38', 17, 2, '290'),
(11, '2024-07-23 15:28:06', 17, 1, '145'),
(12, '2024-07-23 19:50:07', 17, 1, '145'),
(13, '2024-07-23 23:06:38', 17, 1, '145'),
(14, '2024-07-25 06:04:53', 17, 201, '29145'),
(15, '2024-07-26 05:23:07', 17, 8, '1160'),
(16, '2024-07-31 20:43:08', 17, 11, '799'),
(17, '2024-08-12 17:40:12', 19, 2, '1158'),
(18, '2024-08-12 17:54:11', 19, 3, '4777');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id_image` int(11) NOT NULL,
  `chemin` text NOT NULL,
  `id_produit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id_image`, `chemin`, `id_produit`) VALUES
(3, 'images/iphone_15_pro.png', 21),
(4, 'images/iphone_14.png', 22),
(5, 'images/iphone_13.png', 23),
(6, 'images/iphone_se.png', 24),
(10, 'images/product_tile_mbp.png', 30),
(11, 'images/product_tile_mba_13_15.png', 31),
(12, 'images/pt_ipad_pro.png', 32),
(13, 'images/pt_ipad_air.png', 33),
(14, 'images/pt_ipad_10th.png', 34),
(15, 'images/pt_ipad_mini.png', 35);

-- --------------------------------------------------------

--
-- Structure de la table `messagerie`
--

CREATE TABLE `messagerie` (
  `id_messagerie` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` datetime NOT NULL,
  `id_expediteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messagerie`
--

INSERT INTO `messagerie` (`id_messagerie`, `id_utilisateur`, `message`, `date_envoi`, `id_expediteur`) VALUES
(1, 1, 'bonjour', '2024-07-22 09:35:44', 0),
(2, 2, 'birnncmvsdmb,lknfd nlkdbkf', '2024-07-22 09:36:54', 0),
(3, 1, 'gdshvdbkgnfh,é,.gh', '2024-07-22 11:43:55', 0),
(4, 1, 'bonjour comment allez vous', '2024-07-24 23:29:31', 17),
(6, 17, 'fsdagfshgd', '2024-07-25 12:50:42', 1),
(7, 17, '', '2024-07-31 15:07:22', 17),
(8, 17, '', '2024-07-31 15:09:04', 17),
(9, 17, 'fhbdkjlijok;dfpsbdlfjkb.jdnfkfd.jb.dfbbd', '2024-07-31 15:09:20', 17),
(10, 1, 'jfhgkjhghg', '2024-08-16 14:47:37', 17);

-- --------------------------------------------------------

--
-- Structure de la table `panier_sauvegarde`
--

CREATE TABLE `panier_sauvegarde` (
  `id_utilisateur` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `panier_sauvegarde`
--

INSERT INTO `panier_sauvegarde` (`id_utilisateur`, `id_produit`, `quantite`) VALUES
(18, 12, 1),
(18, 21, 3),
(18, 22, 1),
(18, 24, 1),
(18, 35, 2),
(19, 24, 1),
(19, 30, 1),
(19, 33, 1),
(20, 24, 1),
(20, 35, 1);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prix_unitaire` double(10,2) NOT NULL,
  `serie` text DEFAULT NULL,
  `descriptionp` text DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `categorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom`, `prix_unitaire`, `serie`, `descriptionp`, `quantite`, `categorie`) VALUES
(21, 'iphone 15 Pro', 0.00, '900', 'Nouvelle Technologie', 9, 'iphone'),
(22, 'iphone_14', 0.00, 'S2020', 'Nouvelle Technologie', 10, 'iphone'),
(23, 'iphone_13', 849.00, 'S2021', 'Nouvelle Technologie', 10, 'iphone'),
(24, 'iphone_se', 579.00, 'S2019', 'Nouvelle Technologie', 7, 'iphone'),
(30, 'MacBook Pro 14 po et 16 po', 2099.00, 'Puce M3, M3 Pro ou M3 Max', 'Le portable Mac le plus avancé pour les projets les plus ambitieux.', 18, 'mac'),
(31, 'MacBook Air 13 po et 15 po ', 1299.00, ' Puce M2 ou M3', 'Une finesse et une rapidité inouïes pour travailler, jouer ou créer partout.', 15, 'mac'),
(32, 'iPad Pro', 0.00, 'S15', 'Le summum des technologies et de l’expérience iPad.', 25, 'ipade'),
(33, 'iPad Air', 799.00, 'S20', 'Des performances solides.\r\nUn design fin et léger.', 12, 'ipade'),
(34, 'iPad', 678.00, '8000', 'Un iPad coloré à écran intégral pensé pour votre quotidien.', 74, 'ipade'),
(35, 'iPad mini', 169.75, 'Si_mini', 'L’expérience iPad complète en format ultraportable.', 16, 'ipade');

-- --------------------------------------------------------

--
-- Structure de la table `produit_commande`
--

CREATE TABLE `produit_commande` (
  `id_produit` int(11) DEFAULT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `nombre_article` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit_commande`
--

INSERT INTO `produit_commande` (`id_produit`, `id_commande`, `nombre_article`) VALUES
(21, 16, 1),
(33, 16, 1),
(24, 17, 2),
(24, 18, 1),
(30, 18, 2);

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id_produit` int(11) NOT NULL,
  `pourcentage` int(11) NOT NULL,
  `datedefin` date NOT NULL,
  `prix_initial` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id_produit`, `pourcentage`, `datedefin`, `prix_initial`) VALUES
(21, 60, '2024-07-30', 0.00),
(22, 50, '2024-08-01', 0.00),
(32, 60, '2024-08-13', 0.00),
(35, 50, '2024-08-20', 339.50);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `description`) VALUES
(1, 'Admin'),
(2, 'client'),
(3, 'SuperAdmin');

-- --------------------------------------------------------

--
-- Structure de la table `role_utilisateur`
--

CREATE TABLE `role_utilisateur` (
  `id_role` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role_utilisateur`
--

INSERT INTO `role_utilisateur` (`id_role`, `id_utilisateur`) VALUES
(2, 13),
(2, 14),
(2, 15),
(1, 16),
(3, 17),
(2, 18),
(2, 19),
(2, 20);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `mot_de_passe` text DEFAULT NULL,
  `courriel` varchar(200) NOT NULL,
  `telephone` varchar(35) DEFAULT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `date_naissance`, `mot_de_passe`, `courriel`, `telephone`, `statut`) VALUES
(1, 'Tonthe Tsasse Yemfou', 'Wilfried', '2024-07-02', '1234', 'willytonthe1@gmail.com', '4389236173', ''),
(2, 'Tonthe Tsasse Yemfou', 'Wilfried', '2024-07-02', '$2y$10$7QIByyUVavu0Znw/Tfzgqu0y799GNzZ3U62HrxzG8PS8ilfrXmBnS', 'willytonthe@gmail.com', '4389236173', ''),
(4, 'azerty', 'Wilfried', '2024-07-09', '$2y$10$QLVIij58St9OaWI9ee4dbecNnvIBWUj/Gz0XmcYmzDAK6qDtTfvWS', 'willytonthe2@gmail.com', '4389236173', ''),
(5, 'azerty', 'Wilfried', '2024-07-11', '$2y$10$ZskOpygZ9W8onH71Rtgo3e5e8OcyyxO12UXxn9WjyONtMKGFmStaa', 'willytonthe3@gmail.com', '4389236173', ''),
(13, 'Tonthe Tsasse Yemfou', 'Wilfried', '2024-07-12', '$2y$10$gsrCq75lv/bsedWgyteJ.unuva3ZEyYHKCpKk/D35JO1DZ1hMrmKe', 'willytonthe12@gmail.com', '4389236173', 'inactif'),
(14, 'tonthe', 'Wilfried', '2024-07-12', '$2y$10$vxtPYzzKmBv93aWMWoySCezFnTisKZ7rMKqMQAFpsJ/mgV/3jvBcm', 'willytonthe11@gmail.com', '4389236173', 'actif'),
(15, 'tonthe', 'Wilfried', '2024-07-05', '$2y$10$kOftgI3.GE70HeK4pr.KGOl5juJivIKF7ml2GY.jzR1qySTCLHNj6', 'willytonthe112@gmail.com', '4389236173', 'actif'),
(16, 'tonthe', 'Wilfried', '2024-07-05', '$2y$10$bBpF2rpMuphrwGBRRL/AtOFa.dugkTUheUTmkY42T2XIKH/sWk64G', 'willytonthe02@gmail.com', '4389236173', 'actif'),
(17, 'Tonthe Tsasse Yemfou', 'Wilfried', '2024-07-17', '$2y$10$Es45yvRZ7MoSJ05CQ.fB7ucmLV/tKkoPpDfFUGueLEQX9XkfGVfNm', 'willytonthe5@gmail.com', '4389236173', 'actif'),
(18, 'Tonthe Tsasse Yemfou', 'Wilfried', '2024-07-17', '$2y$10$nkc7a.A0SEm0HIziH.cOdenbUwWNRxNDn.VqDwPx/9hRISo3n7Wqu', 'willytonthe6@gmail.com', '4389236173', 'actif'),
(19, 'mumu', 'annie', '2024-07-25', '$2y$10$IUMK9R4F/v8W7lsu/FlL1ek.iMvUil76ERJBKbln7g5DcSXv4VZq6', 'mumu@gmail.com', '4567891234', 'actif'),
(20, 'MURIEL', 'Denise Fabrice45', '2024-08-16', '$2y$10$.SFFjBF.FBd.v0khc41Q4.LnFwVdmyc//xOUEeRwo/hHzn9AIYbZe', 'murielle@gmail.con', '5144622415', 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id_adresse`);

--
-- Index pour la table `adresse_utilisateur`
--
ALTER TABLE `adresse_utilisateur`
  ADD KEY `fk_adresse_utilisateur` (`id_adresse`),
  ADD KEY `fk_utilisateur_adresse` (`id_utilisateur`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_commande_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `fk_image_produit` (`id_produit`);

--
-- Index pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD PRIMARY KEY (`id_messagerie`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `panier_sauvegarde`
--
ALTER TABLE `panier_sauvegarde`
  ADD PRIMARY KEY (`id_utilisateur`,`id_produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD KEY `fk_produit_commande` (`id_produit`),
  ADD KEY `fk_commande_produit` (`id_commande`);

--
-- Index pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `role_utilisateur`
--
ALTER TABLE `role_utilisateur`
  ADD KEY `fk_role_utilisateur` (`id_role`),
  ADD KEY `fk_utilisateur_role` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `courriel` (`courriel`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `messagerie`
--
ALTER TABLE `messagerie`
  MODIFY `id_messagerie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresse_utilisateur`
--
ALTER TABLE `adresse_utilisateur`
  ADD CONSTRAINT `fk_adresse_utilisateur` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_adresse` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD CONSTRAINT `messagerie_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD CONSTRAINT `fk_commande_produit` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit_commande` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `promotion_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `role_utilisateur`
--
ALTER TABLE `role_utilisateur`
  ADD CONSTRAINT `fk_role_utilisateur` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_role` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

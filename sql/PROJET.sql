-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 14 juil. 2023 à 11:01
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `PROJET`
--

-- --------------------------------------------------------

--
-- Structure de la table `Notes`
--

CREATE TABLE `Notes` (
  `IdNote` int(11) NOT NULL,
  `DateNote` date DEFAULT NULL,
  `MessageNote` text DEFAULT NULL,
  `IdTicket` int(11) DEFAULT NULL,
  `StatutNote` int(11) DEFAULT NULL,
  `IdCreateurNote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Notes`
--

INSERT INTO `Notes` (`IdNote`, `DateNote`, `MessageNote`, `IdTicket`, `StatutNote`, `IdCreateurNote`) VALUES
(1, '2023-07-08', 'Première note', 1, 1, 1),
(2, '2023-07-09', 'Deuxième note', 2, 2, 1),
(3, '2023-07-10', 'Troisième note', 1, 1, 1),
(6, '2023-07-14', 'aaa', 1, 2, 1),
(7, '2023-07-14', 'aaaa', 1, 1, 1),
(8, '2023-07-14', 'test1', 1, 1, 1),
(9, '2023-07-14', 'cccc', 1, 1, 1),
(10, '2023-07-14', 'aaaaaa', 4, 2, 1),
(11, '2023-07-14', 'aaaa', 7, 1, 1),
(12, '2023-07-14', 'test', 5, 1, 1),
(13, '2023-07-14', 'aaaaaaa', 4, 1, 1),
(14, '2023-07-14', 'testest', 4, 2, 1),
(15, '2023-07-14', 'bbbbbb', 4, 2, 1),
(16, '2023-07-14', 'ccccccccc', 4, 1, 1),
(17, '2023-07-14', 'aaaaa', 1, 1, 1),
(18, '2023-07-14', 'abc', 1, 1, 1),
(19, '2023-07-14', 'test2', 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Priorite`
--

CREATE TABLE `Priorite` (
  `IdPriorite` int(11) NOT NULL,
  `NomPriorite` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Priorite`
--

INSERT INTO `Priorite` (`IdPriorite`, `NomPriorite`) VALUES
(1, 'basse'),
(2, 'moyenne'),
(3, 'élevée'),
(4, 'critique');

-- --------------------------------------------------------

--
-- Structure de la table `StatutNote`
--

CREATE TABLE `StatutNote` (
  `idStatutNote` int(11) NOT NULL,
  `NomStatut` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `StatutNote`
--

INSERT INTO `StatutNote` (`idStatutNote`, `NomStatut`) VALUES
(1, 'attente action client'),
(2, 'en cours');

-- --------------------------------------------------------

--
-- Structure de la table `StatutTicket`
--

CREATE TABLE `StatutTicket` (
  `IdStatutTicket` int(11) NOT NULL,
  `NomStatut` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `StatutTicket`
--

INSERT INTO `StatutTicket` (`IdStatutTicket`, `NomStatut`) VALUES
(1, 'Ouvert'),
(2, 'Fermé');

-- --------------------------------------------------------

--
-- Structure de la table `Tickets`
--

CREATE TABLE `Tickets` (
  `NumeroTicket` int(11) NOT NULL,
  `IdCreateurTicket` int(11) NOT NULL,
  `SujetTicket` varchar(255) NOT NULL,
  `PrioriteTicket` int(11) NOT NULL,
  `TypeDemandeTicket` int(11) NOT NULL,
  `StatutTicket` int(11) NOT NULL DEFAULT 1,
  `MessageTicket` text NOT NULL,
  `DateTicket` date DEFAULT current_timestamp(),
  `PieceJointeTicket` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Tickets`
--

INSERT INTO `Tickets` (`NumeroTicket`, `IdCreateurTicket`, `SujetTicket`, `PrioriteTicket`, `TypeDemandeTicket`, `StatutTicket`, `MessageTicket`, `DateTicket`, `PieceJointeTicket`) VALUES
(1, 2, 'Premier test', 4, 3, 1, 'Ceci est un simple test.', '2023-07-08', 'test.txt'),
(2, 2, 'Problème de connexion', 3, 1, 2, 'Je rencontre des difficultés pour me connecter au système.', '2023-07-09', NULL),
(3, 2, 'a', 3, 1, 1, 'a', '2023-07-09', NULL),
(4, 2, 'Demande de support technique', 2, 3, 2, 'Le problème a été résolu.', '2023-07-15', 'fichier.pdf'),
(5, 2, 'Demande d assistance commerciale', 1, 1, 2, 'La demande a été traitée avec succès.', '2023-07-16', 'image.jpg'),
(6, 2, 'aa', 1, 1, 1, 'aa', '2023-07-09', 'Page_De_Connexion.fig'),
(7, 2, 'aaaaaaaa?', 4, 2, 1, 'aaaaaaa', '2023-07-09', 'Page_De_Connexion.fig'),
(8, 2, 'testencore', 4, 3, 1, 'aaa', '2023-07-09', '/opt/lampp/htdocs/test/stockage/Page_De_Connexion.fig'),
(9, 2, 'testavecIdCreateur', 1, 1, 1, 'testavecIdCreateur', '2023-07-09', NULL),
(10, 1, '1', 1, 1, 1, '1', '2023-07-09', NULL),
(11, 1, 'testencore2', 1, 1, 1, 'a', '2023-07-09', NULL),
(12, 1, 'aaaaaaa', 1, 1, 1, 'aaaaaaaaa', '2023-07-11', NULL),
(13, 1, 'aaaaaaa', 3, 2, 1, 'aaaaaaaaa', '2023-07-14', NULL),
(14, 1, 'ddddddddddd', 2, 2, 1, 'ddddddddddddddddd', '2023-07-14', NULL),
(15, 1, 'eeeeeeeee', 3, 2, 1, 'eeeeeeeeeeee', '2023-07-14', NULL),
(16, 1, 'fff', 2, 2, 1, 'fff', '2023-07-14', 'index.php');

-- --------------------------------------------------------

--
-- Structure de la table `TypeDemande`
--

CREATE TABLE `TypeDemande` (
  `IdTypeDemande` int(11) NOT NULL,
  `NomTypeDemande` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `TypeDemande`
--

INSERT INTO `TypeDemande` (`IdTypeDemande`, `NomTypeDemande`) VALUES
(1, 'Système'),
(2, 'BDD'),
(3, 'Web');

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `IdUser` int(11) NOT NULL,
  `IdentifiantUser` varchar(25) NOT NULL,
  `MotDePasseUser` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`IdUser`, `IdentifiantUser`, `MotDePasseUser`) VALUES
(1, 'aaa', 'aaa'),
(2, 'bbb', 'bbb');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Notes`
--
ALTER TABLE `Notes`
  ADD PRIMARY KEY (`IdNote`),
  ADD KEY `Note_statut_note` (`StatutNote`),
  ADD KEY `Notes_ibfk_1` (`IdTicket`),
  ADD KEY `FK_Notes_Users` (`IdCreateurNote`);

--
-- Index pour la table `Priorite`
--
ALTER TABLE `Priorite`
  ADD PRIMARY KEY (`IdPriorite`);

--
-- Index pour la table `StatutNote`
--
ALTER TABLE `StatutNote`
  ADD PRIMARY KEY (`idStatutNote`);

--
-- Index pour la table `StatutTicket`
--
ALTER TABLE `StatutTicket`
  ADD PRIMARY KEY (`IdStatutTicket`);

--
-- Index pour la table `Tickets`
--
ALTER TABLE `Tickets`
  ADD PRIMARY KEY (`NumeroTicket`),
  ADD KEY `PrioriteTicket` (`PrioriteTicket`),
  ADD KEY `TypeDemandeTicket` (`TypeDemandeTicket`),
  ADD KEY `StatutTicket` (`StatutTicket`),
  ADD KEY `FK_Ticket_Createur` (`IdCreateurTicket`);

--
-- Index pour la table `TypeDemande`
--
ALTER TABLE `TypeDemande`
  ADD PRIMARY KEY (`IdTypeDemande`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Notes`
--
ALTER TABLE `Notes`
  MODIFY `IdNote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `Priorite`
--
ALTER TABLE `Priorite`
  MODIFY `IdPriorite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `StatutTicket`
--
ALTER TABLE `StatutTicket`
  MODIFY `IdStatutTicket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Tickets`
--
ALTER TABLE `Tickets`
  MODIFY `NumeroTicket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `TypeDemande`
--
ALTER TABLE `TypeDemande`
  MODIFY `IdTypeDemande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Notes`
--
ALTER TABLE `Notes`
  ADD CONSTRAINT `FK_Notes_Users` FOREIGN KEY (`IdCreateurNote`) REFERENCES `Users` (`IdUser`),
  ADD CONSTRAINT `Note_statut_note` FOREIGN KEY (`StatutNote`) REFERENCES `StatutNote` (`idStatutNote`),
  ADD CONSTRAINT `Notes_ibfk_1` FOREIGN KEY (`IdTicket`) REFERENCES `Tickets` (`NumeroTicket`);

--
-- Contraintes pour la table `Tickets`
--
ALTER TABLE `Tickets`
  ADD CONSTRAINT `FK_Ticket_Createur` FOREIGN KEY (`IdCreateurTicket`) REFERENCES `Users` (`IdUser`),
  ADD CONSTRAINT `Tickets_ibfk_1` FOREIGN KEY (`PrioriteTicket`) REFERENCES `Priorite` (`IdPriorite`),
  ADD CONSTRAINT `Tickets_ibfk_2` FOREIGN KEY (`TypeDemandeTicket`) REFERENCES `TypeDemande` (`IdTypeDemande`),
  ADD CONSTRAINT `Tickets_ibfk_3` FOREIGN KEY (`StatutTicket`) REFERENCES `StatutTicket` (`IdStatutTicket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

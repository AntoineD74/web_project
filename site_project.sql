-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 04 jan. 2021 à 18:16
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site_project`
--

-- --------------------------------------------------------

--
-- Structure de la table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(250) DEFAULT NULL,
  `city_country` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`, `city_country`) VALUES
(1, 'Allinges', 'France');

-- --------------------------------------------------------

--
-- Structure de la table `degree_of_studies`
--

CREATE TABLE `degree_of_studies` (
  `study_id` int(11) NOT NULL,
  `degree_of_study` varchar(250) DEFAULT NULL,
  `field_of_study` varchar(250) DEFAULT NULL,
  `name_school` varchar(250) DEFAULT NULL,
  `diploma` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `degree_of_studies`
--

INSERT INTO `degree_of_studies` (`study_id`, `degree_of_study`, `field_of_study`, `name_school`, `diploma`) VALUES
(1, '2nd year of college', 'engineering', 'ECAM', 'engineering degree');

-- --------------------------------------------------------

--
-- Structure de la table `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
  `person_name` varchar(250) DEFAULT NULL,
  `person_surname` varchar(250) DEFAULT NULL,
  `person_birthdate` int(11) DEFAULT NULL,
  `person_username` varchar(250) DEFAULT NULL,
  `person_password` varchar(250) DEFAULT NULL,
  `mail_adress` varchar(250) DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `study_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `person`
--

INSERT INTO `person` (`person_id`, `person_name`, `person_surname`, `person_birthdate`, `person_username`, `person_password`, `mail_adress`, `city_id`, `study_id`) VALUES
(2, 'Antoine', 'Dupuis', 19, 'antoineD', '$2y$10$xp/5RVqjOJTpl7/MmtHryucgQOeOJKWatbfPgdmpOtFY5MwksxedC', 'antoine.dupuis74@icloud.com', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

CREATE TABLE `subjects` (
  `subjects_id` int(11) NOT NULL,
  `subject_name` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `subjects`
--

INSERT INTO `subjects` (`subjects_id`, `subject_name`) VALUES
(1, 'Maths'),
(2, 'English'),
(3, 'Web development'),
(4, 'Biology'),
(5, 'Macroeconomics'),
(6, 'Art history'),
(7, 'Chemistry'),
(8, 'Differential equations');

-- --------------------------------------------------------

--
-- Structure de la table `subjects_attended`
--

CREATE TABLE `subjects_attended` (
  `person_subjects_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `subjects_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `subjects_attended`
--

INSERT INTO `subjects_attended` (`person_subjects_id`, `person_id`, `subjects_id`) VALUES
(4, 2, 2),
(5, 2, 1),
(6, 2, 3),
(8, 2, 7),
(48, 2, 5);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Index pour la table `degree_of_studies`
--
ALTER TABLE `degree_of_studies`
  ADD PRIMARY KEY (`study_id`);

--
-- Index pour la table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`),
  ADD KEY `place_id` (`city_id`),
  ADD KEY `study_id` (`study_id`);

--
-- Index pour la table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subjects_id`);

--
-- Index pour la table `subjects_attended`
--
ALTER TABLE `subjects_attended`
  ADD PRIMARY KEY (`person_subjects_id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `subjects_id` (`subjects_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `degree_of_studies`
--
ALTER TABLE `degree_of_studies`
  MODIFY `study_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subjects_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `subjects_attended`
--
ALTER TABLE `subjects_attended`
  MODIFY `person_subjects_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_2` FOREIGN KEY (`study_id`) REFERENCES `degree_of_studies` (`study_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `person_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `subjects_attended`
--
ALTER TABLE `subjects_attended`
  ADD CONSTRAINT `subjects_attended_ibfk_3` FOREIGN KEY (`subjects_id`) REFERENCES `subjects` (`subjects_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subjects_attended_ibfk_4` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Client :  mysql51-44.perso
-- Généré le :  Ven 11 Août 2017 à 16:23
-- Version du serveur :  5.5.55-0+deb7u1-log
-- Version de PHP :  5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `arthurribase37`
--

-- --------------------------------------------------------

--
-- Structure de la table `VI__accounts`
--

CREATE TABLE IF NOT EXISTS `VI__accounts` (
  `ID` int(11) NOT NULL,
  `Login` varchar(21) NOT NULL,
  `Password` varchar(21) NOT NULL,
  `Email` varchar(21) NOT NULL,
  `DateInscription` datetime NOT NULL,
  `IDPartieEnCours` int(11) NOT NULL DEFAULT '-1',
  `NombrePartiesJouees` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `VI__accounts`
--

INSERT INTO `VI__accounts` (`ID`, `Login`, `Password`, `Email`, `DateInscription`, `IDPartieEnCours`, `NombrePartiesJouees`) VALUES
(1, 'docjackhal', 'test', 'test@vi.com', '2017-08-09 20:41:28', 2, 2),
(2, 'test1', 'test1', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(3, 'test2', 'test2', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(4, 'test3', 'test3', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(5, 'test4', 'test4', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(6, 'test5', 'test5', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(7, 'test6', 'test6', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(8, 'test7', 'test7', 'test@vi.com', '0000-00-00 00:00:00', 2, 0),
(9, 'Ninjaretelle', 'champomy', 'c3henaut@gmail.com', '2017-08-10 10:21:15', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `VI__heros`
--

CREATE TABLE IF NOT EXISTS `VI__heros` (
  `ID` int(11) NOT NULL,
  `Sexe` enum('h','f') NOT NULL DEFAULT 'f',
  `Titre` varchar(21) NOT NULL,
  `Nom` varchar(21) NOT NULL,
  `Prenom` varchar(21) NOT NULL,
  `Age` int(11) NOT NULL,
  `Origine` varchar(21) NOT NULL,
  `Profession` varchar(21) NOT NULL,
  `Biographie` varchar(2000) NOT NULL,
  `FaimMax` int(10) NOT NULL,
  `SoifMax` int(10) NOT NULL,
  `FatigueMax` int(10) NOT NULL,
  `PvMax` int(6) NOT NULL,
  `PaMax` int(4) NOT NULL,
  `PmMax` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `VI__heros`
--

INSERT INTO `VI__heros` (`ID`, `Sexe`, `Titre`, `Nom`, `Prenom`, `Age`, `Origine`, `Profession`, `Biographie`, `FaimMax`, `SoifMax`, `FatigueMax`, `PvMax`, `PaMax`, `PmMax`) VALUES
(1, 'h', 'Capitaine', 'Williams', 'Kurt', 46, 'USA', 'Militaire', 'Kurt Williams est rentré dans l’armée américaine à l’âge de 16 ans. Par ses talents de leader, son don pour le combat et son charisme, il est rapidement monté en grade et est aujourd’hui capitaine. Son expérience sur le terrain est une chance pour le reste du groupe. Il est inégalé au combat au corps à corps et ses capacités de survie sont supérieures à la moyenne. \nKurt Williams est indiscutablement un élément fort du groupe, tant que personne ne remet en question son autorité...\n\nCitation: “Le plus grand problème durant une guerre, ce sont les civils”.', 10, 10, 10, 6, 4, 4),
(2, 'f', 'Docteur', 'Vilhelm', 'Hanna', 31, 'Suède', 'Médecin', 'Ayant obtenu son diplome à l’âge de 24 ans seulement, le docteur Vilhelm a connut ensuite une carrière fulgurante. Réputée pour ses découvertes en biologie monocélulaire, elle est aujourd’hui, à l’âge de 31 ans seulement, une des plus grande spécialiste en matière de virus et maladies de notre ère. Athlétique et bonne vivante, Hanna est un atout majeur pour la survie du groupe, malgrès les dangers encore inconnus de cette île. Elle est végétarienne. Heuresement que c’est une île tropicale.   Citation: “La plupart d’entre vous seront plus utiles une fois mort.”', 10, 10, 10, 6, 4, 4),
(3, 'h', 'Professeur', 'Fisherman', 'John', 35, 'Angleterre', 'Professeur d''histoire', 'Professeur d’histoire à l’université d’Oxford, John est avant tout un voyageur passionné. Incollable sur l’histoire des tribus d’Amérique du sud et sur les croyances locales et vêtu de son chapeau fétiche, il est l’un des seuls survivants à être plus enjoué à l’idée d’aller découvrir les trésors dont regorge l’île, plutôt que de survivre à ses multiples dangers.  Son humour british, ses connaissances et son enthousiasme en font un solide pilier pour le moral du groupe.  Citation: “C’est un peu comme un devoir de vacances, n’est-il pas?”', 10, 10, 10, 6, 4, 4),
(4, 'h', '', 'Moskovski', 'Sergei', 52, 'Russie', 'Chef cuisinier', 'Avant d’être embauché comme chef cuistot à bord du Santa Marina, Sergeï travaillait comme chercheur agro-industriel dans l’armée soviétique, d’où ses difficultés à bien s’entendre avec le capitaine Williams.  Sur Voodoo Island, Sergeï va de nouveau devoir rationner et confectionner de nouveaux repas pour les survivants, avec les moyens du bord... Comme au bon vieux temps, comme il le dit lui -même.  “ Avec de la vodka, même le sable se mange”.', 10, 10, 10, 6, 4, 4),
(5, 'h', '', 'Lombardi', 'Enzo', 16, 'Italie', 'Mousse', 'Enzo est le plus jeune des survivants. Il n’était qu’un simple mousse à bord du Santa Marina. Pourquoi donc a-t-il été épargné? Enzo est victime d’un sentiment d’infériorité grandissant à côté des autres survivants, et pourtant, ce jeune homme charmant et plein de ressources est capable de beaucoup, pourvu qu’on l’aide un peu à avoir confiance en lui.   Citation: “Si je survis, je serai un homme. Mais en suis-je capable?”', 10, 10, 10, 6, 4, 4),
(6, 'f', '', 'Lopez', 'Abby', 26, 'Mexique', 'Mécanicienne', 'Depuis qu’elle est gamine, Abby est une fana de mécanique. Aucun engin n’a de secret pour elle, de la voiture à l’avion. Bien que la technologie ne soit pas présente partout ici, elle se fera une joie de récupérer un maximum de débris afin de construire de fantastiques machines. Ses talents et ses connaissances pourraient bien être les seules chance de survie du groupe.   Citation: “Quand une femme est plus forte qu’eux sur leur propre terrain, les mecs ne savent plus où se mettre. J’adore ça!”', 10, 10, 10, 6, 4, 4),
(7, 'h', '', '', 'Kenny', 40, 'Nouvelle-Zélande', 'Repris de justice', 'Kenny est un homme mysterieux, qui en dit très peu sur son passé. Il tait même son nom de famille, comme par honte. On sait juste, par la journaliste Yuri Lin,  qu’il a tué un homme, et qu’il fuit la justice de son pays. C’est un homme discret, torturé et sur le qui-vive. Il fait preuve d’un grand calme, mais un véritable brasier brûle en lui.    Citation: “Ne me posez pas de questions, où nous ne sortirons jamais d’ici vivant”.', 10, 10, 10, 6, 4, 4),
(8, 'f', '', 'Lin', 'Yuri', 28, 'Japon', 'Journaliste', 'Yuri est une talentueuse reporter spécialisée dans les affaires de meurtre. Fascinée par Kenny, c’est en le filant discrètement qu’elle s’est retrouvée à bord du Santa Marina. Par acquis de conscience, elle ne parlera jamais aux autres de ce qu’elle sait sur lui. Ses talents pour l’enquête et l’investigation font d’elle une excellente exploratrice, mais sa passion et son naturel dominent le reste: elle observe et examine les autres membres du groupe, et garde une trace de cette aventure à travers son journal.', 10, 10, 10, 6, 4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `VI__parties`
--

CREATE TABLE IF NOT EXISTS `VI__parties` (
  `ID` int(11) NOT NULL,
  `Joueur1` int(11) NOT NULL DEFAULT '-1',
  `Joueur2` int(11) NOT NULL DEFAULT '-1',
  `Joueur3` int(11) NOT NULL DEFAULT '-1',
  `Joueur4` int(11) NOT NULL DEFAULT '-1',
  `Joueur5` int(11) NOT NULL DEFAULT '-1',
  `Joueur6` int(11) NOT NULL DEFAULT '-1',
  `Joueur7` int(11) NOT NULL DEFAULT '-1',
  `Joueur8` int(11) NOT NULL DEFAULT '-1',
  `Cycle` int(11) NOT NULL,
  `Jour` int(11) NOT NULL,
  `Etat` enum('en_creation','en_cours') NOT NULL DEFAULT 'en_creation',
  `DateDemarrage` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `VI__parties`
--

INSERT INTO `VI__parties` (`ID`, `Joueur1`, `Joueur2`, `Joueur3`, `Joueur4`, `Joueur5`, `Joueur6`, `Joueur7`, `Joueur8`, `Cycle`, `Jour`, `Etat`, `DateDemarrage`) VALUES
(1, 1, 2, 3, 4, 5, 6, 7, 8, 0, 1, 'en_cours', '2017-08-09 00:00:00'),
(2, 1, 2, 9, 3, 4, 5, 6, 7, 0, 1, 'en_cours', '2017-08-10 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `VI__personnages`
--

CREATE TABLE IF NOT EXISTS `VI__personnages` (
  `IDHeros` int(11) NOT NULL,
  `IDPartie` int(11) NOT NULL,
  `Joueur` int(11) NOT NULL,
  `FaimActuel` int(11) NOT NULL,
  `FaimMax` int(11) NOT NULL,
  `SoifActuel` int(11) NOT NULL,
  `SoifMax` int(11) NOT NULL,
  `FatigueActuel` int(11) NOT NULL,
  `FatigueMax` int(11) NOT NULL,
  `PvActuel` int(11) NOT NULL,
  `PvMax` int(11) NOT NULL,
  `PaActuel` int(11) NOT NULL,
  `PaMax` int(11) NOT NULL,
  `PmActuel` int(11) NOT NULL,
  `PmMax` int(11) NOT NULL,
  `RegionActuelle` int(11) NOT NULL,
  `DateArriveeLieu` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `VI__personnages`
--

INSERT INTO `VI__personnages` (`IDHeros`, `IDPartie`, `Joueur`, `FaimActuel`, `FaimMax`, `SoifActuel`, `SoifMax`, `FatigueActuel`, `FatigueMax`, `PvActuel`, `PvMax`, `PaActuel`, `PaMax`, `PmActuel`, `PmMax`, `RegionActuelle`, `DateArriveeLieu`) VALUES
(1, 1, 1, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 3, 4, 1, '2017-08-09 21:21:46'),
(1, 2, 1, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 4, '2017-08-10 10:23:12'),
(2, 1, 2, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 5, '2017-08-09 21:21:46'),
(2, 2, 2, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 3, '2017-08-10 10:23:12'),
(3, 1, 3, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 5, '2017-08-09 21:21:46'),
(3, 2, 9, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 2, '2017-08-10 10:23:12'),
(4, 1, 4, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 5, '2017-08-09 21:21:46'),
(4, 2, 3, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 2, '2017-08-10 10:23:12'),
(5, 1, 5, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 2, '2017-08-09 21:21:46'),
(5, 2, 4, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 5, '2017-08-10 10:23:12'),
(6, 1, 6, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 3, '2017-08-09 21:21:46'),
(6, 2, 5, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 2, '2017-08-10 10:23:12'),
(7, 1, 7, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 4, '2017-08-09 21:21:46'),
(7, 2, 6, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 5, '2017-08-10 10:23:12'),
(8, 1, 8, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 4, '2017-08-09 21:21:46'),
(8, 2, 7, 10, 10, 10, 10, 10, 10, 6, 6, 4, 4, 4, 4, 1, '2017-08-10 10:23:12');

-- --------------------------------------------------------

--
-- Structure de la table `VI__regions`
--

CREATE TABLE IF NOT EXISTS `VI__regions` (
  `ID` int(11) NOT NULL,
  `Nom` varchar(21) NOT NULL,
  `Type` enum('plage','jungle','montagne','volcan') NOT NULL,
  `Lien1` int(11) DEFAULT '-1',
  `Lien2` int(11) NOT NULL DEFAULT '-1',
  `Lien3` int(11) NOT NULL DEFAULT '-1',
  `Lien4` int(11) NOT NULL DEFAULT '-1',
  `Lien5` int(11) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `VI__regions`
--

INSERT INTO `VI__regions` (`ID`, `Nom`, `Type`, `Lien1`, `Lien2`, `Lien3`, `Lien4`, `Lien5`) VALUES
(1, 'Plage ouest', 'plage', 6, 7, 8, -1, -1),
(2, 'Plage sud', 'plage', 8, 9, 4, -1, -1),
(3, 'Plage nord', 'plage', 5, 4, -1, -1, -1),
(4, 'Jungle de l''est', 'jungle', 5, 3, 2, 9, 10),
(5, 'Jungle du nord', 'jungle', 3, 4, 10, 6, -1),
(6, 'Jungle du nord-ouest', 'jungle', 5, 10, 7, 1, -1),
(7, 'Jungle de l''ouest', 'jungle', 6, 10, 9, 8, 1),
(8, 'Jungle du sud', 'jungle', 1, 7, 9, 2, -1),
(9, 'Montagne', 'montagne', 10, 4, 2, 8, 7),
(10, 'Volcan', 'volcan', 5, 4, 9, 7, 6);

-- --------------------------------------------------------

--
-- Structure de la table `VI__tchats`
--

CREATE TABLE IF NOT EXISTS `VI__tchats` (
  `ID` int(11) NOT NULL,
  `Auteur` varchar(21) NOT NULL,
  `IDPartie` int(11) NOT NULL,
  `Message` varchar(300) NOT NULL,
  `Canal` int(11) NOT NULL,
  `DateEnvoie` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `VI__accounts`
--
ALTER TABLE `VI__accounts`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `VI__heros`
--
ALTER TABLE `VI__heros`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID` (`ID`);

--
-- Index pour la table `VI__parties`
--
ALTER TABLE `VI__parties`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `VI__personnages`
--
ALTER TABLE `VI__personnages`
  ADD UNIQUE KEY `HerosByParty` (`IDHeros`,`IDPartie`);

--
-- Index pour la table `VI__regions`
--
ALTER TABLE `VI__regions`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `VI__tchats`
--
ALTER TABLE `VI__tchats`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `VI__accounts`
--
ALTER TABLE `VI__accounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `VI__parties`
--
ALTER TABLE `VI__parties`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

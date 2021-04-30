-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2021 at 04:34 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cao`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `number` int(2) NOT NULL,
  `count` int(2) NOT NULL,
  `hit_dice` int(2) NOT NULL,
  `health` int(2) NOT NULL,
  `cost` int(2) NOT NULL,
  `ranged` tinyint(1) NOT NULL,
  `effect` varchar(1023) NOT NULL,
  `code` varchar(1023) NOT NULL,
  `image` varchar(1023) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`name`, `type`, `number`, `count`, `hit_dice`, `health`, `cost`, `ranged`, `effect`, `code`, `image`) VALUES
('Abraham', 'Leader', 20, 1, 3, 5, 0, 1, 'At the beginning of your attack phase, you may spend 1 energy to choose any Freedom Force unit. Add 1 to the attack value of the chosen unit until the end of your turn!!', '', ''),
('Brooklyn Blur', 'Common', 4, 6, 3, 2, 3, 0, 'During your movement phase, instead of moving this Brooklyn Blur normally, you may move her up to 7 clear straight line spaces!!', '', ''),
('Cinco De Mayo', 'Event', 8, 2, 0, 0, 0, 0, 'Choose one common unit in your discard pile. Quetzalcoatl gains the ability of the chosen unit until the start of your next turn!!', '', ''),
('Crushmore', 'Champion', 0, 1, 3, 6, 6, 0, 'After attacking with Crushmore, if he is next to Abraham, you may spend 1 energy to move Crushmore up to 2 spaces. After doing so, place Abraham next to him!!', '', ''),
('Doom Quixote', 'Champion', 1, 1, 0, 5, 3, 0, 'Add 1 to Doom Quixote\'s attack value for every 2 cards in your energy pile!! Doom\'s attack cannot go higher than 4!!', '', ''),
('El Muerto', 'Champion', 0, 1, 2, 4, 5, 1, 'During your event phases, you may place this card on the bottom of your draw pile. When El Muerto is destroyed by an opponent, you may spend up to 4 energy. For each energy point spent like this, remove 1 damage marker from Quetzalcoalt!!', '', ''),
('El Niño', 'Common', 5, 6, 1, 1, 1, 1, 'When moving this El Niño during your movement phase, you may move him 1 extra space!! This El Niño may move through other units, but must end its move on an empty space!!', '', ''),
('Hispanic Panic', 'Event', 7, 2, 0, 0, 0, 0, 'Until the start of your next turn, when an opponent attacks Quetzalcoatl or a Mexican Marvel unit within 2 spaces of him, that unit only receives damage from dice results of 5 or higher!!', '', ''),
('Independence Day', 'Event', 11, 2, 0, 0, 0, 0, 'Until the start of your next turn, all of your opponent\'s units have no abilities!!', '', ''),
('Just Juan More', 'Event', 9, 2, 0, 0, 0, 0, 'This card is not played during your event phase. Instead, play this card whenever a Mexican Marvel common or champion unit receives damage. Ignore the damage and place 1 damage marker on Quetzalcoatl!!', '', ''),
('Justice For All', 'Event', 9, 2, 0, 0, 0, 0, 'Choose Abraham or a Freedom Force unit within 2 spaces of him. When an opponent rolls to attack this unit, it only receives damage from dice results of 5 or higher!!', '', ''),
('Land of the Free', 'Event', 18, 2, 0, 0, 0, 0, 'Search your discard pile for a Freedom Force event card. Reveal the card, then place it in your hand!!', '', ''),
('Let Freedom Ring', 'Event', 8, 2, 0, 0, 0, 0, 'Choose Abraham or a Freedom Force unit within 2 spaces of him. When moving this unit during your movement phase, you may move it up to 3 extra spaces!!', '', ''),
('Liberty Belle', 'Champion', 1, 1, 1, 5, 5, 1, 'Once per turn, after attacking with Liberty Belle you may immediately attack with Liberty Belle one more time!!', '', ''),
('Llamador', 'Common', 3, 6, 1, 2, 2, 0, 'Add 1 to the attack value of every other Mexican Marvel common or champion unit that is within 2 spaces of any Llamador you control!! This ability does not stack!!', '', ''),
('Matadora', 'Champion', 2, 1, 2, 6, 6, 0, 'At the start of your movement phase, you may place 1 Mexican Marvel common unit you control next to Matadora!!', '', ''),
('Mexican Mayhem', 'Event', 10, 2, 0, 0, 0, 0, 'Choose 1 of the following: Place 2 damage markers on a common or champion unit within 3 spaces of your leader!! Or Place 4 damage markers on a fort within 3 spaces of your leader!!', '', ''),
('Minuteman', 'Common', 5, 6, 2, 1, 1, 0, 'When moving this Minuteman during your movement phase, you may move him up to 1 extra space!!', '', ''),
('Piñata', 'Common', 6, 6, 1, 3, 2, 0, 'When a friendly common unit within 2 spaces of this Piñata would receive damage from an attack by an enemy unit, you may place all those damage markers on this Piñata instead!! You may not use this ability on another Piñata!!', '', ''),
('Quetzalcoatl', 'Leader', 12, 1, 3, 6, 0, 0, 'At the start of your turn, choose 1 Mexican Marvel common unit within 2 spaces of Quetzalcoatl. Add 1 to that unit\'s attack value until the end of the turn. You may immediately move that unit up to 1 space!!', '', ''),
('Riveteer', 'Common', 6, 6, 1, 1, 1, 1, 'This Riveteer may attack through other units. Only the targeted card is affected by the attack!!', '', ''),
('Winged Wonder', 'Champion', 2, 1, 3, 4, 4, 0, 'If the Winged Wonder is the only unit that moves during your movement phase, you may move him up to 2 extra spaces!!', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `faction`
--

DROP TABLE IF EXISTS `faction`;
CREATE TABLE IF NOT EXISTS `faction` (
  `name` varchar(50) NOT NULL,
  `leader` varchar(50) NOT NULL,
  `champion1` varchar(50) NOT NULL,
  `champion2` varchar(50) NOT NULL,
  `champion3` varchar(50) NOT NULL,
  `common1` varchar(50) NOT NULL,
  `common2` varchar(50) NOT NULL,
  `common3` varchar(50) NOT NULL,
  `event1` varchar(50) NOT NULL,
  `event2` varchar(50) NOT NULL,
  `event3` varchar(50) NOT NULL,
  `event4` varchar(50) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faction`
--

INSERT INTO `faction` (`name`, `leader`, `champion1`, `champion2`, `champion3`, `common1`, `common2`, `common3`, `event1`, `event2`, `event3`, `event4`) VALUES
('Freedom Force', 'Abraham', 'Liberty Belle', 'Winged Wonder', 'Crushmore', 'Brooklyn Blur', 'Minuteman', 'Riveteer', 'Let Freedom Ring', 'Independence Day', 'Land of the Free', 'Justice For All'),
('Mexican Marvels', 'Quetzalcoatl', 'El Muerto', 'Doom Quixote', 'Matadora', 'Llamador', 'El Niño', 'Piñata', 'Hispanic Panic', 'Cinco De Mayo', 'Just Juan More', 'Mexican Mayhem');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `gameid` int(100) NOT NULL,
  `user1` varchar(50) NOT NULL,
  `user2` varchar(50) NOT NULL,
  PRIMARY KEY (`gameid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setup`
--

DROP TABLE IF EXISTS `setup`;
CREATE TABLE IF NOT EXISTS `setup` (
  `card` varchar(50) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `factionname` varchar(50) NOT NULL,
  PRIMARY KEY (`card`,`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setup`
--

INSERT INTO `setup` (`card`, `x`, `y`, `factionname`) VALUES
('Abraham', 6, 2, 'Freedom Force'),
('Brooklyn Blur', 7, 0, 'Freedom Force'),
('El Niño', 7, 2, 'Mexican Marvels'),
('Fort', 5, 2, 'Mexican Marvels'),
('Fort', 5, 3, 'Freedom Force'),
('Llamador', 6, 3, 'Mexican Marvels'),
('Minuteman', 5, 2, 'Freedom Force'),
('Minuteman', 6, 4, 'Freedom Force'),
('Piñata', 5, 1, 'Mexican Marvels'),
('Piñata', 5, 3, 'Mexican Marvels'),
('Quetzalcoatl', 6, 2, 'Mexican Marvels'),
('Riveteer', 6, 3, 'Freedom Force');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

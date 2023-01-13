-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 13 jan 2023 om 11:34
-- Serverversie: 10.9.2-MariaDB-1:10.9.2+maria~ubu2204
-- PHP-versie: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e3t`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Availability`
--

CREATE TABLE `Availability` (
  `TalentID` int(11) NOT NULL,
  `weekNumber` int(11) NOT NULL,
  `Date` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Customer`
--

CREATE TABLE `Customer` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `City` varchar(30) NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `Customer`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Event`
--

CREATE TABLE `Event` (
  `CustomerID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL,
  `EventName` varchar(25) NOT NULL,
  `Location` varchar(40) NOT NULL,
  `Price` int(20) NOT NULL,
  `Date` date NOT NULL,
  `Description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `eventOccasion`
--

CREATE TABLE `eventOccasion` (
  `TalentID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `File`
--

CREATE TABLE `File` (
  `TalentID` int(11) NOT NULL,
  `FileID` int(11) NOT NULL,
  `fileName` varchar(40) NOT NULL,
  `fileType` varchar(15) NOT NULL,
  `ownerName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Login`
--

CREATE TABLE `Login` (
  `LoginID` int(11) NOT NULL,
  `TalentID` int(11) DEFAULT NULL,
  `Username` varchar(40) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Role` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `Login`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Talentprofile`
--

CREATE TABLE `Talentprofile` (
  `LoginID` int(11) NOT NULL,
  `TalentID` int(11) NOT NULL,
  `TalentName` varchar(60) NOT NULL,
  `Country` varchar(30) NOT NULL,
  `Phone` varchar(12) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `Talentprofile`
--

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `Availability`
--
ALTER TABLE `Availability`
  ADD KEY `TalentID` (`TalentID`);

--
-- Indexen voor tabel `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexen voor tabel `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexen voor tabel `eventOccasion`
--
ALTER TABLE `eventOccasion`
  ADD KEY `EventID` (`EventID`),
  ADD KEY `TalentID` (`TalentID`);

--
-- Indexen voor tabel `File`
--
ALTER TABLE `File`
  ADD PRIMARY KEY (`FileID`),
  ADD KEY `TalentID` (`TalentID`);

--
-- Indexen voor tabel `Login`
--
ALTER TABLE `Login`
  ADD PRIMARY KEY (`LoginID`),
  ADD KEY `TalentID` (`TalentID`);

--
-- Indexen voor tabel `Talentprofile`
--
ALTER TABLE `Talentprofile`
  ADD PRIMARY KEY (`TalentID`),
  ADD KEY `LoginID` (`LoginID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `Customer`
--
ALTER TABLE `Customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `Event`
--
ALTER TABLE `Event`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `File`
--
ALTER TABLE `File`
  MODIFY `FileID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `Login`
--
ALTER TABLE `Login`
  MODIFY `LoginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `Talentprofile`
--
ALTER TABLE `Talentprofile`
  MODIFY `TalentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `Availability`
--
ALTER TABLE `Availability`
  ADD CONSTRAINT `Availability_ibfk_1` FOREIGN KEY (`TalentID`) REFERENCES `Talentprofile` (`TalentID`);

--
-- Beperkingen voor tabel `Event`
--
ALTER TABLE `Event`
  ADD CONSTRAINT `Event_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`);

--
-- Beperkingen voor tabel `eventOccasion`
--
ALTER TABLE `eventOccasion`
  ADD CONSTRAINT `eventOccasion_ibfk_1` FOREIGN KEY (`EventID`) REFERENCES `Event` (`EventID`) ON DELETE CASCADE,
  ADD CONSTRAINT `eventOccasion_ibfk_2` FOREIGN KEY (`TalentID`) REFERENCES `Talentprofile` (`TalentID`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `File`
--
ALTER TABLE `File`
  ADD CONSTRAINT `File_ibfk_1` FOREIGN KEY (`TalentID`) REFERENCES `Talentprofile` (`TalentID`);

--
-- Beperkingen voor tabel `Login`
--
ALTER TABLE `Login`
  ADD CONSTRAINT `Login_ibfk_1` FOREIGN KEY (`TalentID`) REFERENCES `Talentprofile` (`TalentID`);

--
-- Beperkingen voor tabel `Talentprofile`
--
ALTER TABLE `Talentprofile`
  ADD CONSTRAINT `Talentprofile_ibfk_1` FOREIGN KEY (`LoginID`) REFERENCES `Login` (`LoginID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

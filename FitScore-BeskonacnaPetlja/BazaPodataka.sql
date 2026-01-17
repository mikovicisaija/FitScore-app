-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 03:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitscore`
--

-- --------------------------------------------------------

--
-- Table structure for table `clan`
--

CREATE TABLE `clan` (
  `email` varchar(255) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `korime` varchar(100) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `napravljen_u` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `clan`
--

INSERT INTO `clan` (`email`, `ime`, `korime`, `lozinka`, `napravljen_u`, `remember_token`) VALUES
('kosticveljko78@gmail.com', 'Veljko', 'Veljko321', '$2y$10$KZG6.vCf4o1hkKH9/yxStuAAqQ5odiUCy1BrRhpd3U.yjTVcYg152', '2026-01-15 20:00:15', NULL),
('mihajlo@gmail.com', 'mane', 'mane', '$2y$10$/T6qCXDHSobLXGnNtJ4ouO4v6RUBvPCrDGiJ9qz0k85lLzvt8L0WW', '2026-01-16 11:23:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fitscore_aktivnosti`
--

CREATE TABLE `fitscore_aktivnosti` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `datum` date NOT NULL,
  `voda` decimal(4,2) DEFAULT 0.00,
  `san` decimal(4,2) DEFAULT 0.00,
  `aktivnost_opis` varchar(100) DEFAULT '',
  `aktivnost_vreme` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `fitscore_aktivnosti`
--

INSERT INTO `fitscore_aktivnosti` (`id`, `email`, `datum`, `voda`, `san`, `aktivnost_opis`, `aktivnost_vreme`) VALUES
(6, 'kosticveljko78@gmail.com', '2026-01-04', 3.00, 6.00, 'Kardio trening', 2),
(7, 'kosticveljko78@gmail.com', '2026-01-01', 1.00, 1.00, 'Hodanje', 1),
(8, 'kosticveljko78@gmail.com', '2026-01-02', 5.00, 5.00, 'Teretana (snaga)', 5),
(9, 'kosticveljko78@gmail.com', '2026-01-16', 8.00, 8.00, 'Hodanje', 100),
(10, 'mihajlo@gmail.com', '2026-01-01', 5.00, 6.00, 'Teretana (snaga)', 30),
(13, 'kosticveljko78@gmail.com', '2026-01-15', 6.00, 8.00, 'Kardio trening', 6),
(14, 'kosticveljko78@gmail.com', '2026-01-14', 7.00, 7.00, 'Joga', 70),
(15, 'kosticveljko78@gmail.com', '2026-01-13', 1.00, 3.00, 'Joga', 50),
(16, 'kosticveljko78@gmail.com', '2025-12-19', 3.00, 3.00, 'Hodanje', 3),
(17, 'kosticveljko78@gmail.com', '2025-11-04', 3.00, 3.00, 'Kardio trening', 3);

-- --------------------------------------------------------

--
-- Table structure for table `fitscore_upitnik`
--

CREATE TABLE `fitscore_upitnik` (
  `email` varchar(255) NOT NULL,
  `ciljevi` text NOT NULL,
  `aktivnost` varchar(20) NOT NULL,
  `iskustvo` varchar(20) NOT NULL,
  `datum_rodjenja` date NOT NULL,
  `visina` int(11) NOT NULL,
  `tezina` decimal(5,2) NOT NULL,
  `pol` char(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `fitscore_upitnik`
--

INSERT INTO `fitscore_upitnik` (`email`, `ciljevi`, `aktivnost`, `iskustvo`, `datum_rodjenja`, `visina`, `tezina`, `pol`, `created_at`) VALUES
('kosticveljko78@gmail.com', 'mršavljenje', '3-5', 'srednji', '2007-05-31', 150, 45.00, 'M', '2026-01-15 20:00:35'),
('mihajlo@gmail.com', 'masa', '3-5', 'napredni', '2007-03-21', 195, 85.00, 'M', '2026-01-16 11:23:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clan`
--
ALTER TABLE `clan`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `korime` (`korime`),
  ADD UNIQUE KEY `korime_2` (`korime`);

--
-- Indexes for table `fitscore_aktivnosti`
--
ALTER TABLE `fitscore_aktivnosti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email_datum` (`email`,`datum`);

--
-- Indexes for table `fitscore_upitnik`
--
ALTER TABLE `fitscore_upitnik`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fitscore_aktivnosti`
--
ALTER TABLE `fitscore_aktivnosti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fitscore_upitnik`
--
ALTER TABLE `fitscore_upitnik`
  ADD CONSTRAINT `fitscore_upitnik_ibfk_1` FOREIGN KEY (`email`) REFERENCES `clan` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 02:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rezervace`
--

-- --------------------------------------------------------

--
-- Table structure for table `mistnosti`
--

CREATE TABLE `mistnosti` (
  `id` int(11) NOT NULL,
  `nazev_mistnosti` varchar(100) NOT NULL,
  `kapacita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mistnosti`
--

INSERT INTO `mistnosti` (`id`, `nazev_mistnosti`, `kapacita`) VALUES
(1, 'Konferenční místnost 1', 10),
(2, 'Konferenční místnost 2', 20),
(3, 'Zasedací místnost 1', 6),
(4, 'Zasedací místnost 2', 8),
(5, 'Školící místnost', 25),
(6, 'Relaxační místnost', 12),
(7, 'Místnost projektového týmu A', 8),
(8, 'Místnost projektového týmu B', 10),
(9, 'Kreativní místnost', 15);

-- --------------------------------------------------------

--
-- Table structure for table `rezervace`
--

CREATE TABLE `rezervace` (
  `id` int(11) NOT NULL,
  `mistnost_id` int(11) NOT NULL,
  `jmeno_osoby` varchar(50) NOT NULL,
  `prijmeni_osoby` varchar(50) NOT NULL,
  `datum` date NOT NULL,
  `zacatek` time NOT NULL,
  `konec` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezervace`
--

INSERT INTO `rezervace` (`id`, `mistnost_id`, `jmeno_osoby`, `prijmeni_osoby`, `datum`, `zacatek`, `konec`) VALUES
(1, 1, 'dfasdga', 'afd', '2025-10-22', '21:50:00', '21:53:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mistnosti`
--
ALTER TABLE `mistnosti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rezervace`
--
ALTER TABLE `rezervace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mistnost` (`mistnost_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mistnosti`
--
ALTER TABLE `mistnosti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rezervace`
--
ALTER TABLE `rezervace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rezervace`
--
ALTER TABLE `rezervace`
  ADD CONSTRAINT `fk_mistnost` FOREIGN KEY (`mistnost_id`) REFERENCES `mistnosti` (`id`),
  ADD CONSTRAINT `rezervace_ibfk_1` FOREIGN KEY (`mistnost_id`) REFERENCES `mistnosti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

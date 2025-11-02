-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 09:19 PM
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
-- Database: `reservations`
--

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
  `konec` time NOT NULL,
  `heslo` varchar(128) NOT NULL,
  `id_uzivatele` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezervace`
--

INSERT INTO `rezervace` (`id`, `mistnost_id`, `jmeno_osoby`, `prijmeni_osoby`, `datum`, `zacatek`, `konec`, `heslo`, `id_uzivatele`) VALUES
(1, 1, 'Adam', 'Novák', '2025-11-02', '08:00:00', '09:00:00', '', NULL),
(2, 1, 'Eva', 'Hrubá', '2025-11-02', '09:15:00', '10:15:00', 'test', NULL),
(3, 1, 'Marek', 'Sýkora', '2025-11-03', '13:00:00', '14:30:00', '', NULL),
(4, 2, 'Lucie', 'Krejčí', '2025-11-02', '10:00:00', '11:00:00', '', NULL),
(5, 2, 'Petr', 'Bartoš', '2025-11-04', '08:30:00', '09:30:00', 'heslo123', NULL),
(6, 2, 'Barbora', 'Vávrová', '2025-11-04', '10:00:00', '11:30:00', '', NULL),
(7, 3, 'Karel', 'Mach', '2025-11-02', '12:00:00', '13:00:00', '', NULL),
(8, 3, 'David', 'Fiala', '2025-11-03', '09:00:00', '10:00:00', 'admin', NULL),
(9, 3, 'Monika', 'Říhová', '2025-11-03', '14:30:00', '15:30:00', '', NULL),
(10, 4, 'Jana', 'Procházková', '2025-11-05', '08:00:00', '09:00:00', '', NULL),
(11, 4, 'Pavel', 'Kučera', '2025-11-05', '09:30:00', '10:30:00', '', NULL),
(12, 4, 'Kateřina', 'Malá', '2025-11-05', '11:00:00', '12:30:00', '', NULL),
(13, 5, 'Tomáš', 'Urban', '2025-11-03', '08:00:00', '09:00:00', '', NULL),
(14, 5, 'Simona', 'Růžičková', '2025-11-03', '09:15:00', '10:15:00', 'editme', NULL),
(15, 5, 'Filip', 'Sedlák', '2025-11-06', '13:00:00', '14:00:00', '', NULL),
(16, 6, 'Nikola', 'Valentová', '2025-11-02', '15:00:00', '16:30:00', '', NULL),
(17, 6, 'Ondřej', 'Pospíšil', '2025-11-02', '16:45:00', '18:00:00', '', NULL),
(18, 7, 'Marie', 'Benešová', '2025-11-04', '13:30:00', '14:30:00', '', NULL),
(19, 8, 'Jakub', 'Doležal', '2025-11-02', '19:00:00', '20:00:00', '', NULL),
(20, 2, 'Petr', 'Černý', '2025-11-02', '08:00:00', '09:00:00', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rezervace`
--
ALTER TABLE `rezervace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mistnost` (`mistnost_id`),
  ADD KEY `fk_rezervace_uzivatel` (`id_uzivatele`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rezervace`
--
ALTER TABLE `rezervace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rezervace`
--
ALTER TABLE `rezervace`
  ADD CONSTRAINT `fk_mistnost` FOREIGN KEY (`mistnost_id`) REFERENCES `mistnosti` (`id`),
  ADD CONSTRAINT `fk_rezervace_user` FOREIGN KEY (`ID_uzivatele`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `fk_rezervace_uzivatel` FOREIGN KEY (`id_uzivatele`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rezervace_ibfk_1` FOREIGN KEY (`mistnost_id`) REFERENCES `mistnosti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

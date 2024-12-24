-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 12:54 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saf`
--

-- --------------------------------------------------------

--
-- Table structure for table `ajk`
--

CREATE TABLE `ajk` (
  `FULL_NAME` varchar(255) NOT NULL,
  `MATRIX_NUMBER` varchar(20) NOT NULL,
  `IC_NUMBER` varchar(20) NOT NULL,
  `GENDER` enum('Male','Female','Other') NOT NULL,
  `RELIGION` varchar(50) NOT NULL,
  `RACE` varchar(50) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `YEAR` int(11) NOT NULL CHECK (`YEAR` between 1 and 4),
  `COURSE` enum('BIW','BIS','BIT','BIP','BIM') NOT NULL,
  `POSITION` enum('Food AJK','Media AJK','Manager','Assistant Manager','Gift AJK','Safety AJK','Protocol AJK') NOT NULL,
  `SEMESTER` enum('SEMESTER 1','SEMESTER 2','SEMESTER 3','SEMESTER 4','SEMESTER 5','SEMESTER 6') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ajk`
--

INSERT INTO `ajk` (`FULL_NAME`, `MATRIX_NUMBER`, `IC_NUMBER`, `GENDER`, `RELIGION`, `RACE`, `EMAIL`, `YEAR`, `COURSE`, `POSITION`, `SEMESTER`) VALUES
('ARUN', 'CI230128', '021024-10-1977', 'Male', 'Hinduism', 'indian', 'ak0794755@gmail.com', 2, 'BIW', 'Manager', 'SEMESTER 3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ajk`
--
ALTER TABLE `ajk`
  ADD PRIMARY KEY (`MATRIX_NUMBER`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

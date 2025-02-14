-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 11:48 AM
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
-- Database: `nstu_academia`
--

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Age` int(11) NOT NULL,
  `Address` text NOT NULL,
  `Edu_Mail` varchar(255) NOT NULL,
  `NID` varchar(20) NOT NULL,
  `Blood_Group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
  `Designation` enum('Lecturer','Assistant Professor','Director') NOT NULL,
  `Is_Provost` enum('Yes','No') NOT NULL,
  `Hall_Name` varchar(255) DEFAULT NULL,
  `Picture` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `Name`, `Age`, `Address`, `Edu_Mail`, `NID`, `Blood_Group`, `Designation`, `Is_Provost`, `Hall_Name`, `Picture`, `created_at`, `updated_at`) VALUES
(1, 'Tasniya Ahmed', 23, 'Noakhali', 'taniyaahmed0011@gmail.com', '669762', 'B+', 'Assistant Professor', 'Yes', 'Bangabandhu Sheikh Mujibur Rahman Hall', 'uploads', '2024-12-02 09:34:47', '2024-12-04 10:48:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Edu_Mail` (`Edu_Mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

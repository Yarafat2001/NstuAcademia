-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 04:44 AM
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
-- Table structure for table `admit`
--

CREATE TABLE `admit` (
  `admit_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `hall_name` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `semester` enum('1st','2nd') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admit`
--

INSERT INTO `admit` (`admit_id`, `student_id`, `profile_image`, `hall_name`, `year`, `semester`) VALUES
(1, 1, 'image', 'BangaMata', 1, '1st');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `profiles_id` int(11) NOT NULL,
  `teacherid` int(11) NOT NULL,
  `Course_id` int(11) NOT NULL,
  `stipulated_class` int(11) NOT NULL,
  `no_of_classes_held` int(11) NOT NULL,
  `attendance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `profiles_id`, `teacherid`, `Course_id`, `stipulated_class`, `no_of_classes_held`, `attendance`) VALUES
(35, 1, 1, 1, 20, 20, 15),
(36, 1, 1, 2, 20, 20, 15),
(37, 1, 1, 3, 20, 20, 15),
(38, 1, 1, 4, 20, 20, 15),
(39, 1, 1, 5, 20, 20, 15),
(40, 1, 1, 6, 20, 20, 15),
(41, 1, 1, 7, 20, 20, 15),
(42, 1, 1, 8, 20, 20, 15),
(43, 1, 1, 9, 20, 20, 15);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `Course_id` int(11) NOT NULL,
  `Department_id` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `Semester` enum('1st','2nd') NOT NULL,
  `Course_Code` varchar(50) NOT NULL,
  `Course_Name` varchar(255) NOT NULL,
  `Credit_Hours` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`Course_id`, `Department_id`, `Year`, `Semester`, `Course_Code`, `Course_Name`, `Credit_Hours`) VALUES
(1, 1, 1, '1st', 'CSE1101', 'Structured Programming', 1),
(2, 1, 1, '1st', 'CSE1102', 'Structured Programming Lab', 2),
(3, 1, 1, '1st', 'CSE1103', 'Discrete Mathematics', 3),
(4, 1, 1, '1st', 'STAT1105', 'Probability and Statistics for Engineers-I', 3),
(5, 1, 1, '1st', 'MATH1107', 'Calculus and Analytical Geometry', 3),
(6, 1, 1, '1st', 'GE1109', 'Soft Skill Communication', 3),
(7, 1, 1, '1st', 'GE1111', 'Technology and Society', 2),
(8, 1, 1, '1st', 'GE1112', 'Technology and Society Lab', 1),
(9, 1, 1, '1st', 'SE1113', 'Introduction to Software Engineering', 3),
(10, 1, 1, '2nd', 'CSE1201', 'Data Structure', 1),
(11, 1, 1, '2nd', 'CSE1202', 'Data Structure Lab', 2),
(12, 1, 1, '2nd', 'CSE1203', 'Computer Organization', 2),
(13, 1, 1, '2nd', 'CSE1204', 'Computer Organization Lab', 1),
(14, 1, 1, '2nd', 'STAT1205', 'Probability and Statistics for Engineers-II', 3),
(15, 1, 1, '2nd', 'MATH1207', 'Ordinary Differential Equations', 3),
(16, 1, 1, '2nd', 'GE1209', 'History of Emergence of Bangladesh', 3),
(17, 1, 1, '2nd', 'GE1211', 'Bengali Literature', 3),
(18, 1, 1, '2nd', 'SE1213', 'Object Oriented Concepts I', 2),
(19, 1, 1, '2nd', 'SE1214', 'Object Oriented Concepts I Lab', 1),
(31, 1, 2, '1st', 'CSE2101', 'Algorithm Analysis', 2),
(32, 1, 2, '1st', 'CSE2102', 'Algorithm Analysis Lab', 1),
(33, 1, 2, '1st', 'CSE2108', 'Theory of Computation', 3),
(34, 1, 2, '1st', 'SE2110', 'Theory of Computation Lab', 1),
(35, 1, 2, '1st', 'CSE2105', 'Computer Networks', 2),
(36, 1, 2, '1st', 'CSE2106', 'Computer Networks Lab', 1),
(37, 1, 2, '1st', 'MATH2107', 'Numerical Analysis for Engineers', 2),
(38, 1, 2, '1st', 'MATH2108', 'Numerical Analysis for Engineers Lab', 1),
(39, 1, 2, '1st', 'SE2111', 'Object Oriented Concepts II', 2),
(40, 1, 2, '1st', 'SE2112', 'Object Oriented Concepts II Lab', 1),
(41, 1, 2, '1st', 'SE2113', 'Software Project I', 2),
(42, 1, 2, '2nd', 'CSE2201', 'Operating Systems and System Programming', 2),
(43, 1, 2, '2nd', 'CSE2202', 'Operating Systems and System Programming Lab', 1),
(44, 1, 2, '2nd', 'GE2203', 'Business Psychology', 3),
(45, 1, 2, '2nd', 'CSE2205', 'Information Security', 2),
(46, 1, 2, '2nd', 'CSE2206', 'Information Security Lab', 1),
(47, 1, 2, '2nd', 'CSE2207', 'Database Management System-I', 2),
(48, 1, 2, '2nd', 'CSE2208', 'Database Management System-I Lab', 1),
(49, 1, 2, '2nd', 'SE2209', 'Software Requirements Spec. and Analysis', 2),
(50, 1, 2, '2nd', 'SE2210', 'Software Requirements Spec. and Analysis Lab', 1),
(51, 1, 2, '2nd', 'BUS2211', 'Business Studies for Engineers', 3),
(52, 1, 3, '1st', 'SE3101', 'Professional Ethics for Information Systems', 3),
(53, 1, 3, '1st', 'CSE3103', 'Web Technology', 1),
(54, 1, 3, '1st', 'CSE3104', 'Web Technology Lab', 2),
(55, 1, 3, '1st', 'CSE3105', 'Data Science and Analytics – DBMS II', 1),
(56, 1, 3, '1st', 'CSE3106', 'Data Science and Analytics – DBMS II Lab', 2),
(57, 1, 3, '1st', 'BUS3107', 'Business Communications', 2),
(58, 1, 3, '1st', 'BUS3108', 'Business Communications Lab', 1),
(59, 1, 3, '1st', 'SE3109', 'Design Pattern', 2),
(60, 1, 3, '1st', 'SE3110', 'Design Pattern Lab', 1),
(61, 1, 3, '1st', 'SE3112', 'Software Project Lab II', 3),
(62, 1, 3, '2nd', 'CSE3201', 'Distributed Systems', 1),
(63, 1, 3, '2nd', 'CSE3202', 'Distributed Systems Lab', 2),
(64, 1, 3, '2nd', 'SE3203', 'Software Metrics', 2),
(65, 1, 3, '2nd', 'SE3204', 'Software Metrics Lab', 1),
(66, 1, 3, '2nd', 'SE3205', 'Software Security', 2),
(67, 1, 3, '2nd', 'SE3206', 'Software Security Lab', 1),
(68, 1, 3, '2nd', 'CSE3207', 'Artificial Intelligence', 2),
(69, 1, 3, '2nd', 'CSE3208', 'Artificial Intelligence Lab', 1),
(70, 1, 3, '2nd', 'SE3209', 'Software Testing and Quality Assurance', 2),
(71, 1, 3, '2nd', 'SE3210', 'Software Testing and Quality Assurance Lab', 1),
(72, 1, 3, '2nd', 'SE3211', 'Software Design and Architecture', 2),
(73, 1, 3, '2nd', 'SE3212', 'Software Design and Architecture Lab', 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Department_id` int(11) NOT NULL,
  `Department_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`Department_id`, `Department_Name`) VALUES
(1, 'Institute Of Information Technology'),
(2, 'Computer Science and Engineering'),
(3, 'Mechanical Engineering'),
(4, 'Civil Engineering'),
(5, 'Mathematics'),
(6, 'Physics'),
(7, 'Chemistry'),
(8, 'Biochemistry and Molecular Biology'),
(9, 'Pharmacy'),
(10, 'Business Administration'),
(11, 'Economics'),
(12, 'Accounting and Information Systems'),
(13, 'English Language and Literature'),
(14, 'Environmental Science and Disaster Management'),
(15, 'Applied Statistics'),
(16, 'Fisheries and Marine Science'),
(17, 'Microbiology'),
(18, 'Genetic Engineering and Biotechnology'),
(19, 'Information and Communication Technology'),
(20, 'Tourism and Hospitality Management'),
(21, 'Law and Justice');

-- --------------------------------------------------------

--
-- Table structure for table `iit`
--

CREATE TABLE `iit` (
  `iit_id` int(11) NOT NULL,
  `Department_id` int(11) NOT NULL,
  `Student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iit`
--

INSERT INTO `iit` (`iit_id`, `Department_id`, `Student_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `iit2`
--

CREATE TABLE `iit2` (
  `iit2_id` int(11) NOT NULL,
  `Teacher_id` int(11) NOT NULL,
  `Department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iit2`
--

INSERT INTO `iit2` (`iit2_id`, `Teacher_id`, `Department_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `registration_id`, `amount`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, 6000.00, 'pending', '2024-12-03 07:18:13', '2024-12-03 07:18:13'),
(2, 1, 8510.00, 'pending', '2024-12-03 07:20:36', '2024-12-03 07:20:36'),
(3, 10, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(4, 11, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(5, 12, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(6, 13, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(7, 14, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(8, 15, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(9, 16, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(10, 17, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(11, 18, 30317.00, 'completed', '2024-12-03 09:37:30', '2024-12-03 09:56:14'),
(12, 10, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(13, 11, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(14, 12, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(15, 13, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(16, 14, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(17, 15, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(18, 16, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(19, 17, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(20, 18, 30317.00, 'completed', '2024-12-03 09:40:03', '2024-12-03 09:56:14'),
(21, 10, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(22, 11, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(23, 12, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(24, 13, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(25, 14, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(26, 15, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(27, 16, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(28, 17, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(29, 18, 443.00, 'completed', '2024-12-03 09:53:14', '2024-12-03 09:56:14'),
(30, 10, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(31, 11, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(32, 12, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(33, 13, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(34, 14, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(35, 15, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(36, 16, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(37, 17, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(38, 18, 2720.00, 'completed', '2024-12-03 09:57:24', '2024-12-03 09:57:28'),
(39, 10, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(40, 11, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(41, 12, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(42, 13, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(43, 14, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(44, 15, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(45, 16, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(46, 17, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(47, 18, 263710.00, 'completed', '2024-12-03 10:59:59', '2024-12-03 11:00:04'),
(48, 10, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(49, 11, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(50, 12, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(51, 13, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(52, 14, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(53, 15, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(54, 16, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(55, 17, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(56, 18, 31817.00, 'completed', '2024-12-03 11:00:37', '2024-12-03 11:00:47'),
(57, 10, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(58, 11, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(59, 12, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(60, 13, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(61, 14, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(62, 15, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(63, 16, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(64, 17, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(65, 18, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(66, 19, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(67, 20, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(68, 21, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(69, 22, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(70, 23, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(71, 24, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(72, 25, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(73, 26, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(74, 27, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(75, 28, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(76, 29, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(77, 30, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(78, 31, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41'),
(79, 32, 3250.00, 'completed', '2024-12-04 03:02:37', '2024-12-04 03:02:41');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `studentName` varchar(255) NOT NULL,
  `fatherName` varchar(255) NOT NULL,
  `courseCoordinator` varchar(255) NOT NULL,
  `studentId` int(11) NOT NULL,
  `session` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `hallName` varchar(100) NOT NULL,
  `seatNumber` varchar(50) NOT NULL,
  `profileImage` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `studentName`, `fatherName`, `courseCoordinator`, `studentId`, `session`, `department`, `hallName`, `seatNumber`, `profileImage`, `created_at`, `updated_at`) VALUES
(1, 'Arpita', 'Uttam', 'Dipak Chandra Das', 15, '2020-2021', 'IIT', 'Bangamata', 'not allocated', 'GOPR0018.JPG', '2024-12-02 07:04:12', '2024-12-02 07:04:12'),
(2, 'John Doe', 'Robert Doe', 'Dr. Smith', 2023001, '2023-2024', 'IIT', 'Hall A', 'Seat 12', 'image.jpg', '2024-12-02 07:16:07', '2024-12-02 07:16:07'),
(5, 'Adiba', 'Ashraf', 'Dipak Chandra Das', 34, '2020-2021', 'Institute Of Information Technology', 'Bangamata', '0', 'GOPR0017.JPG', '2024-12-03 11:14:53', '2024-12-03 11:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `profiles_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `Semester` enum('1st','2nd') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `profiles_id`, `course_id`, `Year`, `Semester`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(2, 2, 2, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(3, 2, 3, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(4, 2, 6, 1, '1st', 'active', '2024-12-03 07:04:30', '2024-12-04 02:38:25'),
(5, 2, 7, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(6, 2, 8, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(7, 2, 5, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(8, 2, 9, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(9, 2, 4, 1, '1st', '', '2024-12-03 07:04:30', '2024-12-03 07:04:30'),
(10, 1, 1, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(11, 1, 2, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(12, 1, 3, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(13, 1, 6, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(14, 1, 7, 1, '1st', 'active', '2024-12-03 08:22:03', '2024-12-04 02:38:29'),
(15, 1, 8, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(16, 1, 5, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(17, 1, 9, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(18, 1, 4, 1, '1st', '', '2024-12-03 08:22:03', '2024-12-03 08:22:03'),
(19, 1, 1, 1, '1st', '', '2024-12-03 13:48:45', '2024-12-03 13:48:45'),
(20, 1, 2, 1, '1st', '', '2024-12-03 13:48:45', '2024-12-03 13:48:45'),
(21, 1, 3, 1, '1st', '', '2024-12-03 13:48:45', '2024-12-03 13:48:45'),
(22, 1, 6, 1, '1st', '', '2024-12-03 13:48:45', '2024-12-03 13:48:45'),
(23, 1, 7, 1, '1st', '', '2024-12-03 13:48:45', '2024-12-03 13:48:45'),
(24, 1, 6, 1, '1st', '', '2024-12-03 13:50:03', '2024-12-03 13:50:03'),
(25, 1, 7, 1, '1st', 'active', '2024-12-03 13:50:03', '2024-12-04 02:38:19'),
(26, 1, 1, 1, '1st', '', '2024-12-03 13:53:26', '2024-12-03 13:53:26'),
(27, 1, 2, 1, '1st', '', '2024-12-03 13:53:26', '2024-12-03 13:53:26'),
(28, 1, 3, 1, '1st', '', '2024-12-03 13:53:26', '2024-12-03 13:53:26'),
(29, 1, 6, 1, '1st', '', '2024-12-03 13:53:26', '2024-12-03 13:53:26'),
(30, 1, 7, 1, '1st', '', '2024-12-03 13:53:26', '2024-12-03 13:53:26'),
(31, 1, 8, 1, '1st', '', '2024-12-03 13:53:26', '2024-12-03 13:53:26'),
(32, 1, 1, 1, '1st', '', '2024-12-04 02:44:57', '2024-12-04 02:44:57');

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
(1, 'Arpi', 23, 'ewwervv', 'arpi@gmail.com', '669762', 'B+', 'Lecturer', 'Yes', 'cecbwiwncoil', 'dcrevr', '2024-12-02 09:34:47', '2024-12-03 14:36:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admit`
--
ALTER TABLE `admit`
  ADD PRIMARY KEY (`admit_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `Course_Code` (`Course_id`),
  ADD KEY `teacherid` (`teacherid`),
  ADD KEY `profiles_id` (`profiles_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`Course_id`),
  ADD UNIQUE KEY `Department_id` (`Department_id`,`Year`,`Semester`,`Course_Code`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Department_id`);

--
-- Indexes for table `iit`
--
ALTER TABLE `iit`
  ADD PRIMARY KEY (`iit_id`),
  ADD KEY `Department_id` (`Department_id`),
  ADD KEY `Student_id` (`Student_id`);

--
-- Indexes for table `iit2`
--
ALTER TABLE `iit2`
  ADD PRIMARY KEY (`iit2_id`),
  ADD KEY `Teacher_id` (`Teacher_id`),
  ADD KEY `Department_id` (`Department_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registration_id` (`registration_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_id` (`profiles_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `Year` (`Year`),
  ADD KEY `Year_2` (`Year`);

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
-- AUTO_INCREMENT for table `admit`
--
ALTER TABLE `admit`
  MODIFY `admit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `Course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `Department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `iit`
--
ALTER TABLE `iit`
  MODIFY `iit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `iit2`
--
ALTER TABLE `iit2`
  MODIFY `iit2_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admit`
--
ALTER TABLE `admit`
  ADD CONSTRAINT `admit_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `profiles` (`id`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacherid`) REFERENCES `teacher` (`id`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`Course_id`) REFERENCES `course` (`Course_id`),
  ADD CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`profiles_id`) REFERENCES `profiles` (`id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`Department_id`) REFERENCES `department` (`Department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `iit`
--
ALTER TABLE `iit`
  ADD CONSTRAINT `iit_ibfk_1` FOREIGN KEY (`Department_id`) REFERENCES `department` (`Department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `iit_ibfk_2` FOREIGN KEY (`Student_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `iit2`
--
ALTER TABLE `iit2`
  ADD CONSTRAINT `iit2_ibfk_1` FOREIGN KEY (`Teacher_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `iit2_ibfk_2` FOREIGN KEY (`Department_id`) REFERENCES `department` (`Department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`profiles_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registration_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`Course_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

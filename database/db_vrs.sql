-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2023 at 05:12 PM
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
-- Database: `db_vrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `details` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `dateAdded` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `number_sequence`
--

CREATE TABLE `number_sequence` (
  `id` int(11) NOT NULL,
  `page_name` varchar(15) NOT NULL,
  `last_number` int(11) NOT NULL,
  `CreatedDateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `number_sequence`
--

INSERT INTO `number_sequence` (`id`, `page_name`, `last_number`, `CreatedDateTime`) VALUES
(1, 'venues', 4, '2022-05-18 14:52:45'),
(2, 'users', 7, '2022-05-18 14:52:58'),
(3, 'programs', 6, '2022-07-16 13:20:28'),
(4, 'reservations', 10, '2022-08-21 14:42:40');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `programID` varchar(15) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(15) DEFAULT NULL,
  `incharge_organization` varchar(255) DEFAULT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `dateUpdated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `programID`, `name`, `color`, `incharge_organization`, `dateAdded`, `dateUpdated`) VALUES
(1, 'PRG0001', 'College of Education', '#1acedb', 'IMCC', '2023-09-03 00:00:00', '2023-09-11 13:36:45'),
(2, 'PRG0002', 'College of Business Administration', '#eba40a', 'IMCC', '2023-09-03 00:00:00', '2023-09-11 13:37:27'),
(3, 'PRG0003', 'College of Medical Technology', '#33d738', 'IMCC', '2023-09-10 00:00:00', '2023-09-11 13:37:33'),
(5, 'PRG0005', 'College of Art and Sciences', '#ec0909', 'IMCC', '2023-09-10 00:00:00', '2023-09-11 13:37:50'),
(6, 'PRG0006', 'College of Computer Studies', '#7d7d7d', 'IMCC', '2023-09-10 00:00:00', '2023-09-11 13:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(11) NOT NULL,
  `reservationID` varchar(255) NOT NULL,
  `venueID` int(15) DEFAULT NULL,
  `programID` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` varchar(50) NOT NULL,
  `time_end` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `num_participants` int(11) NOT NULL,
  `tag_color` varchar(7) DEFAULT '#ffffff',
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `last_notified` date DEFAULT NULL,
  `cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `act_form_file` varchar(255) NOT NULL,
  `letter_approve_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `reservationID`, `venueID`, `programID`, `date_start`, `date_end`, `time_start`, `time_end`, `name`, `address`, `contact`, `description`, `num_participants`, `tag_color`, `notified`, `last_notified`, `cancelled`, `deleted`, `approved`, `act_form_file`, `letter_approve_file`) VALUES
(2, 'RES000001', 1, 3, '2023-09-07', '2023-09-07', '', '', 'fgf', 'fg', '34343353535', 'er', 0, '#164dbb', 0, NULL, 0, 0, 0, '', ''),
(3, 'RES000002', 1, 1, '2023-09-06', '2023-09-08', '', '', 'Sample', 'sample', '21212212121', 'resr', 0, '#ee1b1b', 0, NULL, 0, 0, 0, '', ''),
(4, 'RES000003', 5, 6, '2023-09-07', '2023-09-07', '', '', 'Sample', 'dd', '21212212121', 're', 0, '#1aff34', 0, NULL, 0, 0, 0, '', ''),
(5, 'RES000004', 4, 5, '2023-09-09', '2023-09-09', '', '', 'Sample', NULL, NULL, 'ss', 0, '#ffffff', 0, NULL, 0, 0, 0, '', ''),
(8, 'RES000005', 2, 3, '2023-10-11', '2023-10-11', '', '', 'SSG ELECTION', NULL, NULL, 'SSG Elections for grade 6 students', 125, '#ffffff', 0, NULL, 0, 0, 0, '6527e9bc2f12c.png', '6527e9bc2f23c.png'),
(9, 'RES000007', 3, 2, '2023-10-13', '2023-10-13', '', '', 'PE 2 Final Performance', NULL, NULL, 'Final Requirement for P2 ', 30, '#ffffff', 0, NULL, 0, 0, 0, '6527ebe506660.png', '6527ebe506740.png'),
(10, 'RES000008', 4, 5, '2023-10-13', '2023-10-13', '', '', 'Sumbaganay lang sa', NULL, NULL, 'Sumbagay w/ executives', 10, '#ffffff', 0, NULL, 0, 0, 0, '6527eda766fe4.png', '6527eda7670c5.png'),
(11, 'RES000009', 4, 1, '2023-10-16', '2023-10-16', '', '', 'HIV Awareness Orientation', NULL, NULL, 'To raise awareness on HIV', 50, '#7d7d7d', 0, NULL, 0, 0, 0, '65282783e35c1.png', '65282783e3ee5.png'),
(13, 'RES000010', 1, 2, '2023-10-17', '2023-10-18', '08:00', '09:00', 'Sumbagay Dev vs HR', NULL, NULL, 'sumbagay nlng sa kay gahi kayog ulo ang HR', 25, '#ffffff', 0, NULL, 0, 0, 0, '652898b1f3b60.png', '652898b1f3cb8.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userID` varchar(25) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `password` varchar(255) NOT NULL,
  `change_pass` tinyint(1) NOT NULL DEFAULT 0,
  `position` varchar(50) NOT NULL,
  `programID` int(11) DEFAULT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `dateUpdated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userID`, `first_name`, `middle_name`, `last_name`, `contact`, `username`, `password`, `change_pass`, `position`, `programID`, `dateAdded`, `dateUpdated`) VALUES
(4, 'USR0002', 'Administrator', NULL, NULL, NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'DSA', NULL, '2023-09-10 06:08:09', NULL),
(3, 'USR0001', 'Student Officer', NULL, NULL, NULL, 'stud_officer', 'cd73502828457d15655bbd7a63fb0bc8', 1, 'STO', 2, '2023-09-10 06:06:58', NULL),
(6, 'USR0004', 'Dimple', 'Grace', 'Normadination', '09090909090', 'admin1', 'e00cf25ad42683b3df678c61f42c6bda', 1, 'STO', 6, '2023-10-10 22:38:21', '2023-10-10 22:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `venueID` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `dateUpdated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `venueID`, `name`, `dateAdded`, `dateUpdated`) VALUES
(1, 'VN000001', 'Audio-Visual Room', '2023-09-10 00:24:22', NULL),
(2, 'VN000002', 'Review Center', '2023-09-10 00:24:33', NULL),
(4, 'VN000003', 'Covered Court', '2023-09-10 00:24:55', '2023-09-10 00:32:21'),
(5, 'VN000004', 'Auditorium', '2023-09-10 00:25:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `number_sequence`
--
ALTER TABLE `number_sequence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pagename` (`page_name`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `VarietyID` (`venueID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `number_sequence`
--
ALTER TABLE `number_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

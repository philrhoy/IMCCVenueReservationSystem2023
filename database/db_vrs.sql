-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 03, 2023 at 10:26 PM
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
(2, 'users', 8, '2022-05-18 14:52:58'),
(3, 'programs', 6, '2022-07-16 13:20:28'),
(4, 'reservations', 18, '2022-08-21 14:42:40');

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
(5, 'PRG0005', 'College of Art and Sciences', '#ec0958', 'IMCC', '2023-09-10 00:00:00', '2023-11-21 20:32:45'),
(6, 'PRG0006', 'College of Computer Studies', '#7d7d7d', 'IMCC', '2023-09-10 00:00:00', '2023-09-11 13:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(11) NOT NULL,
  `reservationID` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  `venueID` int(15) DEFAULT NULL,
  `programID` int(11) NOT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'P',
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` varchar(50) NOT NULL,
  `time_end` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `num_participants` int(11) NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `last_notified` date DEFAULT NULL,
  `cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL,
  `rejectedByAdmin` int(1) NOT NULL DEFAULT 0,
  `approvedByAdmin` int(1) NOT NULL DEFAULT 0,
  `act_form_file` varchar(255) NOT NULL,
  `letter_approve_file` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `material` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `reservationID`, `userID`, `venueID`, `programID`, `status`, `date_start`, `date_end`, `time_start`, `time_end`, `name`, `contact`, `description`, `num_participants`, `notified`, `last_notified`, `cancelled`, `deleted`, `rejectedByAdmin`, `approvedByAdmin`, `act_form_file`, `letter_approve_file`, `notes`, `material`) VALUES
(2, 'RES000001', 4, 1, 3, 'A', '2023-09-07', '2023-09-07', '', '', 'fgf', '34343353535', 'er', 0, 0, NULL, 0, 0, 0, 0, '', '', 'test', 'PR'),
(3, 'RES000002', 6, 1, 1, 'R', '2023-09-06', '2023-09-08', '', '', 'SampleMODAL', '21212212121', 'resr', 22, 0, NULL, 0, 0, 4, 0, '', '', 'yawards', ''),
(4, 'RES000003', 6, 5, 6, 'P', '2023-09-07', '2023-09-07', '', '', 'Sample', '21212212121', 're', 0, 0, NULL, 0, 0, 0, 0, '', '', '', ''),
(5, 'RES000004', 4, 4, 5, 'P', '2023-09-09', '2023-09-09', '', '', 'Sample', NULL, 'ss', 0, 0, NULL, 0, 0, 0, 0, '', '', 'TEST', ''),
(8, 'RES000005', 4, 2, 3, 'P', '2023-10-11', '2023-10-11', '', '', 'SSG ELECTION', NULL, 'SSG Elections for grade 6 students', 125, 0, NULL, 0, 0, 0, 0, '6527e9bc2f12c.png', '6527e9bc2f23c.png', '', ''),
(9, 'RES000007', 6, 5, 2, 'P', '2023-10-13', '2023-10-13', '', '', 'PE 2 Final Performance', NULL, 'Final Requirement for P2 ', 30, 0, NULL, 0, 0, 0, 0, '6527ebe506660.png', '6527ebe506740.png', 'test', ''),
(10, 'RES000008', 4, 4, 5, 'P', '2023-10-13', '2023-10-13', '', '', 'Campus Scavenger Hunt', NULL, 'Campus Scavenger Hunt', 10, 0, NULL, 0, 0, 0, 0, '6527eda766fe4.png', '6527eda7670c5.png', '', ''),
(11, 'RES000009', 6, 4, 1, 'P', '2023-10-16', '2023-10-16', '', '', 'HIV Awareness Orientation', NULL, 'To raise awareness on HIV', 50, 0, NULL, 0, 0, 0, 0, '65282783e35c1.png', '65282783e3ee5.png', '', ''),
(13, 'RES000010', 4, 1, 2, 'P', '2023-10-17', '2023-10-18', '08:00', '09:00', 'Career fairs', NULL, 'Career fairs', 25, 0, NULL, 0, 0, 0, 0, '652898b1f3b60.png', '652898b1f3cb8.png', '', ''),
(14, 'RES000011', 4, 1, 5, 'P', '2023-10-01', '2023-10-01', '17:50', '17:50', 'Test 10/30/2023', NULL, 'Test objectives', 15, 0, NULL, 0, 0, 0, 0, '653f7cf006c5f.jpg', '653f7cf00747f.png', '', ''),
(15, 'RES000012', 4, 4, 6, 'P', '2023-10-30', '2023-10-30', '17:56', '17:56', 'Test bug fix 1', NULL, 'Test objectives', 10, 0, NULL, 0, 0, 0, 0, '653f7de1dfb3d.png', '653f7de1dfdd5.jpg', '', ''),
(16, 'RES000013', 6, 4, 6, 'P', '2023-10-01', '2023-10-02', '21:09', '21:09', 'TEST UPLOAD IMAGE', NULL, 'TEST UPLOAD IMAGE', 21, 0, NULL, 0, 0, 0, 0, '653fab140816f.jpg', '653fab140853c.png', 'TEST NOTE', 'E1'),
(17, 'RES000014', 6, 1, 2, 'A', '0000-00-00', '2023-11-30', '', '10:12', 'SK VOTE TEST', NULL, 'SK VOTE TEST', 25, 0, NULL, 0, 0, 0, 4, '653fababdd1e5.jpg', '653fababdd350.jpg', 'TEST NOTA', ''),
(18, 'RES000015', 6, 4, 2, 'A', '2023-11-01', '2023-11-01', '09:05', '11:05', 'TEST USER ID FLAG', NULL, 'TEST TEST ONLY ONLY', 69, 0, NULL, 0, 0, 4, 4, '65424d578c3ed.png', '65424d578c5fd.png', '', ''),
(19, 'RES000016', 6, 1, 6, 'A', '2023-11-02', '2023-11-02', '09:53', '10:54', 'TEST Image Preview', NULL, 'TEST Image Preview po', 65, 0, NULL, 0, 0, 4, 4, '654258a6d6420.png', '654258a6d652f.png', 'TEST NOTEs', ''),
(20, 'RES000017', 4, 5, 5, 'P', '2023-11-13', '2023-11-13', '13:31', '23:36', 'TEST HIGH RESOLUTION IMAGE', NULL, '1. Temporary removed image validation\r\n2. Test out image scroll spy', 1, 0, NULL, 0, 0, 0, 0, '65505554369e2.jpg', '6550555436b4b.jpg', '', ''),
(21, 'RES000018', 6, 5, 2, 'P', '2023-11-16', '2023-11-16', '13:00', '14:00', 'TEST PDF IMAGE & PDF VIEWER', NULL, '1. Added new button for file preview\r\n2. Added PDF to accepted file ext.\r\n', 1, 0, NULL, 0, 0, 0, 0, '655093284d456.pdf', '655093284d52f.png', '', '');

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
(6, 'USR0004', 'Dimple', 'Grace', 'Normadination', '09090909090', 'admin1', 'e00cf25ad42683b3df678c61f42c6bda', 1, 'STO', 6, '2023-10-10 22:38:21', '2023-10-10 22:40:11'),
(7, 'USR0008', 'Property', '', 'Custodian', '09090909090', 'prop1', '366fad496447472a7fcf154888e09282', 1, 'PTC', NULL, '2023-10-30 20:00:03', '2023-10-30 20:00:30');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `venueID` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp(),
  `dateUpdated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `venueID`, `name`, `capacity`, `dateAdded`, `dateUpdated`) VALUES
(1, 'VN000001', 'Audio-Visual Room', '500 seats', '2023-09-10 00:24:22', '2023-12-04 05:25:35'),
(2, 'VN000002', 'Review Center', '1000 Max', '2023-09-10 00:24:33', '2023-12-04 05:25:57'),
(4, 'VN000003', 'Covered Court', '1000 or More', '2023-09-10 00:24:55', '2023-12-04 05:26:13'),
(5, 'VN000004', 'Auditorium', '5000 Max', '2023-09-10 00:25:04', '2023-12-04 05:26:23');

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
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

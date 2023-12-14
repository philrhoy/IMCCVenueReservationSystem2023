-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 14, 2023 at 07:18 AM
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
  `type` varchar(15) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `sourceUser` int(11) NOT NULL,
  `recipient` int(11) DEFAULT NULL,
  `notifyToAllUserType` varchar(50) DEFAULT NULL,
  `isRead` int(1) NOT NULL DEFAULT 0,
  `link` text DEFAULT NULL,
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `details`, `sourceUser`, `recipient`, `notifyToAllUserType`, `isRead`, `link`, `dateAdded`) VALUES
(2, 'CREATE', 'DIMPLE NORMADINATION created a new reservation [RES000020]. Please review.', 6, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=20', '2023-12-12 19:39:18'),
(3, 'CREATE', 'DIMPLE NORMADINATION created a new reservation [RES000021]. Please review.', 6, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=21', '2023-12-12 19:41:47'),
(4, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000020]. Please review the updates.', 6, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=23', '2023-12-12 19:42:35'),
(5, 'REJECT', 'Reservation [RES000020] was Rejected by Admin JOHN DOE. Please review and update the reservation.', 4, 6, NULL, 0, 'edit_reservation.php?reservation_id=23', '2023-12-12 19:43:28'),
(6, 'APPROVE', 'Reservation [RES000021] was Approved by Admin JOHN DOE.', 4, 6, NULL, 0, 'edit_reservation.php?reservation_id=24', '2023-12-12 19:46:00'),
(7, 'APPROVE', 'Reservation [RES000021] was Approved by Admin JOHN DOE.', 4, NULL, 'PTC', 0, 'edit_reservation.php?reservation_id=24', '2023-12-12 19:46:00'),
(8, 'UPDATE', 'JOHN DOE updated reservation [RES000021]. Please review the updates.', 4, 6, NULL, 0, 'edit_reservation.php?reservation_id=24', '2023-12-12 19:47:01'),
(9, 'APPROVE', 'Reservation [RES000021] was Approved by Admin ADMINISTRATOR .', 4, NULL, 'PTC', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:09:12'),
(10, 'APPROVE', 'Reservation [RES000021] was Approved by Admin ADMINISTRATOR .', 4, 6, NULL, 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:11:19'),
(11, 'APPROVE', 'Reservation [RES000021] was Approved by Admin ADMINISTRATOR .', 4, NULL, 'PTC', 1, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:11:19'),
(12, 'REJECT', 'ADMINISTRATOR  updated reservation [RES000021]. Please review the updates.', 4, 6, NULL, 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:11:24'),
(13, 'REJECT', 'Reservation [RES000021] was Rejected by Admin ADMINISTRATOR . Please review and update the reservation.', 4, 6, NULL, 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:12:56'),
(14, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:14:23'),
(15, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:15:06'),
(16, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:15:34'),
(17, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:15:36');

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
(4, 'reservations', 21, '2022-08-21 14:42:40');

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
(23, 'RES000020', 6, 1, 5, 'R', '2023-12-12', '2023-12-12', '19:38', '20:38', 'TEST NOTIFY ADMIN', '09090909090', 'SHOULD NOTIFY ADMIN - TEST STUDENT UPDATE', 10, 0, NULL, 0, 0, 4, 0, '6578466613838.pdf', '', 'TEST REJECT', ''),
(24, 'RES000021', 6, 2, 5, 'R', '2023-12-12', '2023-12-12', '21:40', '22:40', 'TEST NOTIFY ADMIN 2', '09090909090', '1. SHOULD INCREMENT ADMIN NOTIFICATION COUNTER\r\n\r\n2. SHOULD NOTIFY STUDENT', 9, 0, NULL, 0, 0, 4, 4, '657846fb236d8.pdf', '657846fb23884.png', 'yuyu', '');

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
(4, 'USR0002', 'John', NULL, 'Doe', NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'DSA', NULL, '2023-09-10 06:08:09', NULL),
(3, 'USR0001', 'Liza', NULL, 'Soberano', NULL, 'stud_officer', 'e00cf25ad42683b3df678c61f42c6bda', 1, 'STO', 2, '2023-09-10 06:06:58', NULL),
(6, 'USR0004', 'Dimple', 'Grace', 'Normadination', '09090909090', 'admin1', 'e00cf25ad42683b3df678c61f42c6bda', 1, 'STO', 6, '2023-10-10 22:38:21', '2023-10-10 22:40:11'),
(7, 'USR0008', 'Mark', '', 'Monter', '09090909090', 'prop1', '366fad496447472a7fcf154888e09282', 1, 'PTC', NULL, '2023-10-30 20:00:03', '2023-10-30 20:00:30');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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

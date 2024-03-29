-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 10, 2024 at 07:22 PM
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
(5, 'REJECT', 'Reservation [RES000020] was Rejected by Admin JOHN DOE. Please review and update the reservation.', 4, 6, NULL, 1, 'edit_reservation.php?reservation_id=23', '2023-12-12 19:43:28'),
(6, 'APPROVE', 'Reservation [RES000021] was Approved by Admin JOHN DOE.', 4, 6, NULL, 1, 'edit_reservation.php?reservation_id=24', '2023-12-12 19:46:00'),
(7, 'APPROVE', 'Reservation [RES000021] was Approved by Admin JOHN DOE.', 4, NULL, 'PTC', 0, 'edit_reservation.php?reservation_id=24', '2023-12-12 19:46:00'),
(8, 'UPDATE', 'JOHN DOE updated reservation [RES000021]. Please review the updates.', 4, 6, NULL, 1, 'edit_reservation.php?reservation_id=24', '2023-12-12 19:47:01'),
(9, 'APPROVE', 'Reservation [RES000021] was Approved by Admin ADMINISTRATOR .', 4, NULL, 'PTC', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:09:12'),
(10, 'APPROVE', 'Reservation [RES000021] was Approved by Admin ADMINISTRATOR .', 4, 6, NULL, 1, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:11:19'),
(11, 'APPROVE', 'Reservation [RES000021] was Approved by Admin ADMINISTRATOR .', 4, NULL, 'PTC', 1, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:11:19'),
(12, 'REJECT', 'ADMINISTRATOR  updated reservation [RES000021]. Please review the updates.', 4, 6, NULL, 1, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:11:24'),
(13, 'REJECT', 'Reservation [RES000021] was Rejected by Admin ADMINISTRATOR . Please review and update the reservation.', 4, 6, NULL, 1, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:12:56'),
(14, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:14:23'),
(15, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:15:06'),
(16, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:15:34'),
(17, 'UPDATE', 'DIMPLE NORMADINATION updated reservation [RES000021]. Please review the updates.', 6, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=24', '2023-12-14 14:15:36'),
(18, 'CREATE', 'JOHN DOE created a new reservation [RES000022]. Please review.', 4, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=22', '2023-12-14 14:33:03'),
(19, 'APPROVE', 'Reservation [RES000022] was Approved by Admin JOHN DOE.', 4, 4, NULL, 0, 'edit_reservation.php?reservation_id=25', '2023-12-14 14:44:59'),
(20, 'APPROVE', 'Reservation [RES000022] was Approved by Admin JOHN DOE.', 4, NULL, 'PTC', 0, 'edit_reservation.php?reservation_id=25', '2023-12-14 14:44:59'),
(21, 'CREATE', 'JOHN DOE created a new reservation [RES000023]. Please review.', 4, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=RES000023', '2023-12-26 22:37:39'),
(22, 'CREATE', 'JOHN DOE created a new reservation [RES000024]. Please review.', 4, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=RES000024', '2023-12-31 03:12:14'),
(23, 'APPROVE', 'Reservation [RES000024] was Approved by Admin JOHN DOE.', 4, 4, NULL, 1, 'view_reservation.php?reservation_id=RES000024', '2023-12-31 03:31:30'),
(24, 'APPROVE', 'Reservation [RES000024] was Approved by Admin JOHN DOE.', 4, NULL, 'PTC', 0, 'edit_reservation.php?reservation_id=RES000024', '2023-12-31 03:31:31'),
(25, 'CREATE', 'MARK MONTER created a new reservation [RES000025]. Please review.', 7, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=RES000025', '2023-12-31 05:22:33'),
(26, 'CREATE', 'MARK MONTER created a new reservation [RES000026]. Please review.', 7, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=RES000026', '2023-12-31 11:06:44'),
(27, 'CREATE', 'JOHN DOE created a new reservation [RES000027]. Please review.', 4, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=RES000027', '2023-12-31 11:10:10'),
(28, 'UPDATE', 'JOHN DOE updated reservation [RES000027]. Please review the updates.', 4, 4, NULL, 1, 'edit_reservation.php?reservation_id=RES000027', '2024-01-09 00:33:24'),
(29, 'UPDATE', 'MARK MONTER updated reservation [RES000027]. Please review the updates.', 7, NULL, 'DSA', 1, 'edit_reservation.php?reservation_id=RES000027', '2024-01-09 00:34:17'),
(30, 'UPDATE', 'MARK MONTER updated reservation [RES000025]. Please review the updates.', 7, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=RES000025', '2024-01-11 01:15:28'),
(31, 'UPDATE', 'MARK MONTER updated reservation [RES000025]. Please review the updates.', 7, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=RES000025', '2024-01-11 01:20:15'),
(32, 'UPDATE', 'MARK MONTER updated reservation [RES000025]. Please review the updates.', 7, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=RES000025', '2024-01-11 01:21:32'),
(33, 'UPDATE', 'MARK MONTER updated reservation [RES000024]. Please review the updates.', 7, NULL, 'DSA', 0, 'edit_reservation.php?reservation_id=RES000024', '2024-01-11 01:24:57');

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
(1, 'venues', 7, '2022-05-18 14:52:45'),
(2, 'users', 9, '2022-05-18 14:52:58'),
(3, 'programs', 7, '2022-07-16 13:20:28'),
(4, 'reservations', 27, '2022-08-21 14:42:40');

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
(6, 'PRG0006', 'College of Computer Studies', '#7d7d7d', 'IMCC', '2023-09-10 00:00:00', '2023-09-11 13:37:54'),
(7, 'PRG0007', 'ADDED BY PTC', '#000000', 'IMCC', '2023-12-31 04:32:12', '2023-12-31 04:32:25');

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
  `status` varchar(3) NOT NULL DEFAULT 'D',
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `time_start` varchar(50) NOT NULL,
  `time_end` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `num_participants` int(11) NOT NULL,
  `sponsor` varchar(100) NOT NULL,
  `contribution` decimal(10,0) NOT NULL,
  `incharge` varchar(100) NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `last_notified` date DEFAULT NULL,
  `cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `rejectedByAdmin` int(1) NOT NULL DEFAULT 0,
  `approvedByAdmin` int(1) NOT NULL DEFAULT 0,
  `act_form_file` varchar(255) NOT NULL,
  `letter_approve_file` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `sound_system` varchar(2) DEFAULT NULL,
  `microphone` varchar(2) DEFAULT NULL,
  `others_material` text DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `reservationID`, `userID`, `venueID`, `programID`, `status`, `date_start`, `date_end`, `time_start`, `time_end`, `name`, `contact`, `description`, `num_participants`, `sponsor`, `contribution`, `incharge`, `notified`, `last_notified`, `cancelled`, `deleted`, `rejectedByAdmin`, `approvedByAdmin`, `act_form_file`, `letter_approve_file`, `notes`, `sound_system`, `microphone`, `others_material`, `date_added`) VALUES
(23, 'RES000020', 6, 1, 5, 'R', '2023-12-12', '2023-12-12', '19:38', '20:38', 'TEST NOTIFY ADMIN', '09090909090', 'SHOULD NOTIFY ADMIN - TEST STUDENT UPDATE', 10, '', 0, '', 0, NULL, 0, 0, 4, 0, '6578466613838.pdf', '', 'TEST REJECT', NULL, NULL, '', '2023-12-24 01:30:15'),
(24, 'RES000021', 6, 2, 5, 'R', '2023-12-12', '2023-12-12', '21:40', '22:40', 'TEST NOTIFY ADMIN 2', '09090909090', '1. SHOULD INCREMENT ADMIN NOTIFICATION COUNTER\r\n\r\n2. SHOULD NOTIFY STUDENT', 9, '', 0, '', 0, NULL, 0, 0, 4, 4, '657846fb236d8.pdf', '657846fb23884.png', 'yuyu', NULL, NULL, '', '2023-12-24 01:30:15'),
(25, 'RES000022', 4, 5, 5, 'A', '2023-12-14', '2023-12-14', '14:31', '14:31', 'test', '09125455451', '\"\" test \"\" ', 45, '', 0, '', 0, NULL, 0, 0, 0, 4, '657aa13479f5d.pdf', '657aa1347a54d.png', '', NULL, NULL, NULL, '2023-12-24 01:30:15'),
(26, 'RES000022', 4, 5, 5, 'P', '2023-12-14', '2023-12-14', '14:31', '14:31', 'test', '09125455451', '\"\" test \"\" ', 45, '', 0, '', 0, NULL, 0, 0, 0, 0, '657aa19f75051.pdf', '657aa19f7565e.png', NULL, NULL, NULL, NULL, '2023-12-24 01:30:15'),
(27, 'RES000023', 4, 1, 5, 'P', '2023-12-27', '2023-12-27', '10:37', '22:37', 'TEST DATE ADDED TIME', '09090909090', 'TEST DATE ADDED TIME', 12, '', 0, '', 0, NULL, 0, 0, 0, 0, '658ae5331cc5a.pdf', '', NULL, NULL, NULL, NULL, '2023-12-26 22:37:39'),
(28, 'RES000024', 4, 5, 5, 'A', '2024-01-01', '2024-01-01', '03:11', '15:11', 'TEST JANUARY SCHED', '09090909090', 'JAN SCHED', 5, '', 0, '', 0, NULL, 0, 0, 0, 4, '65906b8ebc0ba.pdf', '', '', '', '', '', '2023-12-31 03:12:14'),
(29, 'RES000025', 7, 5, 7, 'P', '2024-01-02', '2024-01-02', '05:21', '17:21', 'x', '09090909090', 'x', 2, '', 0, '', 0, NULL, 0, 0, 0, 0, '65908a190a373.pdf', '', '', '12', '2', 'Test', '2023-12-31 05:22:33'),
(30, 'RES000026', 7, 1, 5, 'D', '2024-01-02', '2024-01-02', '11:05', '23:05', 'TEST CREATE NEW RES', '09090909090', 'TEST CREATE NEW RES', 5, 'TEST SPONSOR', 101, 'TEST INCHARGE', 0, NULL, 0, 0, 0, 0, '', '', NULL, '1', '2', 'test', '2023-12-31 11:06:44'),
(31, 'RES000027', 4, 8, 7, 'R', '2024-01-03', '2024-01-03', '11:09', '23:09', 'TEST NEW RES + STATUS DRAFTED', '09090909090', 'TEST NEW RES + STATUS DRAFTED', 5, 'SPONSOR1', 500, 'PERSON1', 0, NULL, 0, 0, 7, 0, '659c23d44226b.png', '', 'test', NULL, NULL, '', '2023-12-31 11:10:10');

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
(7, 'USR0008', 'Mark', '', 'Monter', '09090909090', 'prop1', '366fad496447472a7fcf154888e09282', 1, 'PTC', NULL, '2023-10-30 20:00:03', '2023-10-30 20:00:30'),
(8, 'USR0009', 'TEST', 'ADD', 'PTC', '09090909090', 'testtest', '05a671c66aefea124cc08b76ea6d30bb', 0, 'STO', 7, '2023-12-31 05:01:43', NULL);

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
(5, 'VN000004', 'Auditorium', '5000 Max', '2023-09-10 00:25:04', '2023-12-04 05:26:23'),
(8, 'VN000007', 'ADDED BY PTC + TEST UPDATE', '12', '2023-12-31 04:55:08', '2023-12-31 04:55:20');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `number_sequence`
--
ALTER TABLE `number_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

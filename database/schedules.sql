-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2023 at 05:08 PM
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
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `num_participants` int(11) NOT NULL,
  `tag_color` varchar(7) DEFAULT '#ffffff',
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

INSERT INTO `schedules` (`id`, `reservationID`, `userID`, `venueID`, `programID`, `status`, `date_start`, `date_end`, `time_start`, `time_end`, `name`, `address`, `contact`, `description`, `num_participants`, `tag_color`, `notified`, `last_notified`, `cancelled`, `deleted`, `rejectedByAdmin`, `approvedByAdmin`, `act_form_file`, `letter_approve_file`, `notes`, `material`) VALUES
(2, 'RES000001', 4, 1, 3, 'A', '2023-09-07', '2023-09-07', '', '', 'fgf', 'fg', '34343353535', 'er', 0, '#164dbb', 0, NULL, 0, 0, 0, 0, '', '', 'test', 'PR'),
(3, 'RES000002', 6, 1, 1, 'R', '2023-09-06', '2023-09-08', '', '', 'SampleMODAL', 'sample', '21212212121', 'resr', 22, '#ee1b1b', 0, NULL, 0, 0, 4, 0, '', '', 'yawards', ''),
(4, 'RES000003', 6, 5, 6, 'P', '2023-09-07', '2023-09-07', '', '', 'Sample', 'dd', '21212212121', 're', 0, '#1aff34', 0, NULL, 0, 0, 0, 0, '', '', '', ''),
(5, 'RES000004', 4, 4, 5, 'P', '2023-09-09', '2023-09-09', '', '', 'Sample', NULL, NULL, 'ss', 0, '#ffffff', 0, NULL, 0, 0, 0, 0, '', '', 'TEST', ''),
(8, 'RES000005', 4, 2, 3, 'P', '2023-10-11', '2023-10-11', '', '', 'SSG ELECTION', NULL, NULL, 'SSG Elections for grade 6 students', 125, '#ffffff', 0, NULL, 0, 0, 0, 0, '6527e9bc2f12c.png', '6527e9bc2f23c.png', '', ''),
(9, 'RES000007', 6, 5, 2, 'P', '2023-10-13', '2023-10-13', '', '', 'PE 2 Final Performance', NULL, NULL, 'Final Requirement for P2 ', 30, '#ffffff', 0, NULL, 0, 0, 0, 0, '6527ebe506660.png', '6527ebe506740.png', 'test', ''),
(10, 'RES000008', 4, 4, 5, 'P', '2023-10-13', '2023-10-13', '', '', 'Sumbaganay lang sa', NULL, NULL, 'Sumbagay w/ executives', 10, '#ffffff', 0, NULL, 0, 0, 0, 0, '6527eda766fe4.png', '6527eda7670c5.png', '', ''),
(11, 'RES000009', 6, 4, 1, 'P', '2023-10-16', '2023-10-16', '', '', 'HIV Awareness Orientation', NULL, NULL, 'To raise awareness on HIV', 50, '#7d7d7d', 0, NULL, 0, 0, 0, 0, '65282783e35c1.png', '65282783e3ee5.png', '', ''),
(13, 'RES000010', 4, 1, 2, 'P', '2023-10-17', '2023-10-18', '08:00', '09:00', 'Sumbagay Dev vs HR', NULL, NULL, 'sumbagay nlng sa kay gahi kayog ulo ang HR', 25, '#ffffff', 0, NULL, 0, 0, 0, 0, '652898b1f3b60.png', '652898b1f3cb8.png', '', ''),
(14, 'RES000011', 4, 1, 5, 'P', '2023-10-01', '2023-10-01', '17:50', '17:50', 'Test 10/30/2023', NULL, NULL, 'Test objectives', 15, '#ffffff', 0, NULL, 0, 0, 0, 0, '653f7cf006c5f.jpg', '653f7cf00747f.png', '', ''),
(15, 'RES000012', 4, 4, 6, 'P', '2023-10-30', '2023-10-30', '17:56', '17:56', 'Test bug fix 1', NULL, NULL, 'Test objectives', 10, '#ffffff', 0, NULL, 0, 0, 0, 0, '653f7de1dfb3d.png', '653f7de1dfdd5.jpg', '', ''),
(16, 'RES000013', 6, 4, 6, 'P', '2023-10-01', '2023-10-02', '21:09', '21:09', 'TEST UPLOAD IMAGE', NULL, NULL, 'TEST UPLOAD IMAGE', 21, '#ffffff', 0, NULL, 0, 0, 0, 0, '653fab140816f.jpg', '653fab140853c.png', 'TEST NOTE', 'E1'),
(17, 'RES000014', 6, 1, 2, 'A', '0000-00-00', '2023-11-30', '', '10:12', 'SK VOTE TEST', NULL, NULL, 'SK VOTE TEST', 25, '#ffffff', 0, NULL, 0, 0, 0, 4, '653fababdd1e5.jpg', '653fababdd350.jpg', 'TEST NOTA', ''),
(18, 'RES000015', 6, 4, 2, 'A', '2023-11-01', '2023-11-01', '09:05', '11:05', 'TEST USER ID FLAG', NULL, NULL, 'TEST TEST ONLY ONLY', 69, '#ffffff', 0, NULL, 0, 0, 4, 4, '65424d578c3ed.png', '65424d578c5fd.png', '', ''),
(19, 'RES000016', 6, 1, 6, 'A', '2023-11-02', '2023-11-02', '09:53', '10:54', 'TEST Image Preview', NULL, NULL, 'TEST Image Preview po', 65, '#ffffff', 0, NULL, 0, 0, 4, 4, '654258a6d6420.png', '654258a6d652f.png', 'TEST NOTEs', ''),
(20, 'RES000017', 4, 5, 5, 'P', '2023-11-13', '2023-11-13', '13:31', '23:36', 'TEST HIGH RESOLUTION IMAGE', NULL, NULL, '1. Temporary removed image validation\r\n2. Test out image scroll spy', 1, '#ffffff', 0, NULL, 0, 0, 0, 0, '65505554369e2.jpg', '6550555436b4b.jpg', '', ''),
(21, 'RES000018', 6, 5, 2, 'P', '2023-11-16', '2023-11-16', '13:00', '14:00', 'TEST PDF IMAGE & PDF VIEWER', NULL, NULL, '1. Added new button for file preview\r\n2. Added PDF to accepted file ext.\r\n', 1, '#ffffff', 0, NULL, 0, 0, 0, 0, '655093284d456.pdf', '655093284d52f.png', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

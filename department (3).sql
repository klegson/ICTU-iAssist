-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2026 at 06:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happisa`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `logID` int(11) NOT NULL,
  `user` text NOT NULL,
  `actionDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logType` text NOT NULL,
  `actionDetails` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressID` int(11) NOT NULL,
  `provinceName` text NOT NULL,
  `municipality` text NOT NULL,
  `zipCode` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`addressID`, `provinceName`, `municipality`, `zipCode`) VALUES
(1, 'Albay', 'Bacacay', '4509'),
(2, 'Albay', 'Camalig', '4502'),
(3, 'Albay', 'Daraga (Locsin)', '4501'),
(4, 'Albay', 'Guinobatan', '4503'),
(5, 'Albay', 'Jovellar', '4515'),
(6, 'Albay', 'Legazpi City', '4500'),
(7, 'Albay', 'Libon', '4507'),
(8, 'Albay', 'Ligao', '4504'),
(9, 'Albay', 'Malilipot', '4510'),
(10, 'Albay', 'Malinao', '4512'),
(11, 'Albay', 'Manito', '4514'),
(12, 'Albay', 'Oas', '4505'),
(13, 'Albay', 'Pio Duran', '4516'),
(14, 'Albay', 'Polangui', '4506'),
(15, 'Albay', 'Rapu-Rapu', '4517'),
(16, 'Albay', 'Sto. Domingo', '4508'),
(17, 'Albay', 'Tabaco', '4511'),
(18, 'Albay', 'Tiwi', '4513'),
(19, 'Camarines Norte', 'Basud', '4608'),
(20, 'Camarines Norte', 'Capalonga', '4607'),
(21, 'Camarines Norte', 'Daet', '4600'),
(22, 'Camarines Norte', 'Jose Panganiban', '4606'),
(23, 'Camarines Norte', 'Labo', '4604'),
(24, 'Camarines Norte', 'Mercedes', '4601'),
(25, 'Camarines Norte', 'Paracale', '4605'),
(26, 'Camarines Norte', 'San Lorenzo Ruiz', '4610'),
(27, 'Camarines Norte', 'San Vicente', '4609'),
(28, 'Camarines Norte', 'Sta. Elena', '4611'),
(29, 'Camarines Norte', 'Talisay', '4602'),
(30, 'Camarines Norte', 'Vinzons', '4603'),
(31, 'Camarines Sur', 'Baao', '4432'),
(32, 'Camarines Sur', 'Balatan', '4436'),
(33, 'Camarines Sur', 'Bato', '4801'),
(34, 'Camarines Sur', 'Bombon', '4404'),
(35, 'Camarines Sur', 'Buhi', '4433'),
(36, 'Camarines Sur', 'Bula', '4430'),
(37, 'Camarines Sur', 'Cabusao', '4406'),
(38, 'Camarines Sur', 'Calabanga', '4405'),
(39, 'Camarines Sur', 'Camaligan', '4401'),
(40, 'Camarines Sur', 'Canaman', '4402'),
(41, 'Camarines Sur', 'Caramoan', '4429'),
(42, 'Camarines Sur', 'Del Gallego', '4411'),
(43, 'Camarines Sur', 'Gainza', '4412'),
(44, 'Camarines Sur', 'Garchitorena', '4428'),
(45, 'Camarines Sur', 'Goa', '4422'),
(46, 'Camarines Sur', 'Iriga City', '4431'),
(47, 'Camarines Sur', 'Lagonoy', '4425'),
(48, 'Camarines Sur', 'Libmanan', '4407'),
(49, 'Camarines Sur', 'Lupi', '4409'),
(50, 'Camarines Sur', 'Magarao', '4403'),
(51, 'Camarines Sur', 'Milaor', '4413'),
(52, 'Camarines Sur', 'Minalabac', '4414'),
(53, 'Camarines Sur', 'Nabua', '4434'),
(54, 'Camarines Sur', 'Naga City', '4400'),
(55, 'Camarines Sur', 'Ocampo', '4419'),
(56, 'Camarines Sur', 'Pamplona', '4416'),
(57, 'Camarines Sur', 'Pasacao', '4417'),
(58, 'Camarines Sur', 'Pili', '4418'),
(59, 'Camarines Sur', 'Presentacion', '4424'),
(60, 'Camarines Sur', 'Ragay', '4410'),
(61, 'Camarines Sur', 'Sagnay', '4421'),
(62, 'Camarines Sur', 'San Fernando', '5416'),
(63, 'Camarines Sur', 'San Jose', '4423'),
(64, 'Camarines Sur', 'Sipocot', '4408'),
(65, 'Camarines Sur', 'Siruma', '4427'),
(66, 'Camarines Sur', 'Tigaon', '4420'),
(67, 'Camarines Sur', 'Tinambac', '4426'),
(68, 'Catanduanes', 'Bagamanoc', '4807'),
(69, 'Catanduanes', 'Baras', '4803'),
(70, 'Catanduanes', 'Bato', '4801'),
(71, 'Catanduanes', 'Caramoran', '4808'),
(72, 'Catanduanes', 'Gigmoto', '4804'),
(73, 'Catanduanes', 'Pandan', '4809'),
(74, 'Catanduanes', 'Panganiban', '4806'),
(75, 'Catanduanes', 'San Andres', '4810'),
(76, 'Catanduanes', 'San Miguel', '4802'),
(77, 'Catanduanes', 'Viga', '4805'),
(78, 'Catanduanes', 'Virac', '4800'),
(79, 'Masbate', 'Aroroy', '5414'),
(80, 'Masbate', 'Baleno', '5413'),
(81, 'Masbate', 'Balud', '5412'),
(82, 'Masbate', 'Batuan', '5415'),
(83, 'Masbate', 'Cataingan', '5405'),
(84, 'Masbate', 'Cawayan', '5409'),
(85, 'Masbate', 'Claveria', '5419'),
(86, 'Masbate', 'Dimasalang', '5403'),
(87, 'Masbate', 'Esperanza', '5407'),
(88, 'Masbate', 'Mandaon', '5411'),
(89, 'Masbate', 'Masbate', '5400'),
(90, 'Masbate', 'Milagros', '5410'),
(91, 'Masbate', 'Mobo', '5401'),
(92, 'Masbate', 'Monreal', '5418'),
(93, 'Masbate', 'Palanas', '5404'),
(94, 'Masbate', 'Pio V. Corpuz', '5406'),
(95, 'Masbate', 'Placer', '5408'),
(96, 'Masbate', 'San Fernando', '5416'),
(97, 'Masbate', 'San Jacinto', '5417'),
(98, 'Masbate', 'San Pascual', '5420'),
(99, 'Masbate', 'Uson', '5402'),
(100, 'Sorsogon', 'Barcelona', '4712'),
(101, 'Sorsogon', 'Bulan', '4706'),
(102, 'Sorsogon', 'Bulusan', '4704'),
(103, 'Sorsogon', 'Casiguran', '4702'),
(104, 'Sorsogon', 'Castilla', '4713'),
(105, 'Sorsogon', 'Donsol', '4715'),
(106, 'Sorsogon', 'Gubat', '4710'),
(107, 'Sorsogon', 'Irosin', '4707'),
(108, 'Sorsogon', 'Juban', '4703'),
(109, 'Sorsogon', 'Magallanes', '4705'),
(110, 'Sorsogon', 'Matnog', '4708'),
(111, 'Sorsogon', 'Pilar', '4714'),
(112, 'Sorsogon', 'Prieto Diaz', '4711'),
(113, 'Sorsogon', 'Sta. Magdalena', '4709'),
(114, 'Sorsogon', 'Sorsogon City', '4700');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `logID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user` varchar(11) NOT NULL,
  `action` text NOT NULL,
  `tableAffected` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`logID`, `timestamp`, `user`, `action`, `tableAffected`, `status`) VALUES
(1, '2025-11-24 03:10:34', 'personnel.r', 'Added new rating to employee 4821253', 'performance', ''),
(2, '2025-11-24 03:13:22', 'personnel.r', 'performance', 'performance', ''),
(3, '2025-11-24 03:14:04', 'personnel.r', 'Added new rating to employee 4217547', 'performance', ''),
(4, '2025-11-25 03:08:01', 'personnel.r', 'Added 4 rating to employee 5818663', 'performance', ''),
(5, '2025-11-28 19:07:47', 'personnel.r', 'Uploaded IPCRF for employee 4128838 (Year: 2024)', 'performance', ''),
(6, '2025-11-28 19:10:00', 'personnel.r', 'Uploaded IPCRF for employee 4128838 (Year: 2024)', 'performance', ''),
(7, '2025-11-28 19:57:11', 'personnel.r', 'Added 4.5 rating to employee 4128838', 'performance', ''),
(8, '2025-11-28 20:28:25', 'personnel.r', 'Uploaded IPCRF for employee 5818663 (Year: 2024)', 'performance', ''),
(9, '2025-11-28 21:42:52', 'personnel.r', 'Uploaded IPCRF for employee 4128838 (Year: 2025)', 'performance', ''),
(10, '2025-11-28 21:54:41', 'personnel.r', 'Uploaded IPCRF for employee 4128838 (Year: 2025)', 'performance', ''),
(11, '2025-11-30 05:30:26', '58', 'LOGIN', 'users', ''),
(12, '2025-11-30 05:55:48', '58', 'LOGIN', 'users', 'success'),
(13, '2025-11-30 06:10:02', '58', 'LOGOUT', 'users', 'success'),
(14, '2025-11-30 06:10:09', '58', 'LOGIN', 'users', 'success'),
(15, '2025-11-30 06:28:00', '58', 'LOGOUT', 'users', 'success'),
(16, '2025-11-30 06:28:05', '2', 'LOGIN', 'users', 'success'),
(17, '2025-11-30 06:29:17', '2', 'LOGOUT', 'users', 'success'),
(18, '2025-11-30 06:29:23', '2', 'LOGIN', 'users', 'success'),
(19, '2025-12-01 00:58:20', '2', 'LOGIN', 'users', 'success'),
(20, '2025-12-01 01:49:41', '2', 'LOGOUT', 'users', 'success'),
(21, '2025-12-01 01:49:50', '120', 'LOGIN', 'users', 'success'),
(22, '2025-12-01 01:57:22', '120', 'LOGOUT', 'users', 'success'),
(23, '2025-12-01 01:57:28', '2', 'LOGIN', 'users', 'success'),
(24, '2025-12-01 01:59:34', '2', 'LOGOUT', 'users', 'success'),
(25, '2025-12-01 01:59:39', '120', 'LOGIN', 'users', 'success'),
(26, '2025-11-30 19:12:22', 'hrdd.rov@de', 'Uploaded IDP for employee 5818663 (Year: 2024)', 'performance', ''),
(27, '2025-12-01 02:51:32', '120', 'LOGOUT', 'users', 'success'),
(28, '2025-12-01 02:51:37', '2', 'LOGIN', 'users', 'success'),
(29, '2025-12-01 02:54:35', '2', 'LOGOUT', 'users', 'success'),
(30, '2025-12-01 02:54:45', '58', 'LOGIN', 'users', 'success'),
(31, '2025-12-01 03:04:59', '2', 'LOGIN', 'users', 'success'),
(32, '2025-12-01 03:05:42', '2', 'LOGOUT', 'users', 'success'),
(33, '2025-12-01 03:05:47', '120', 'LOGIN', 'users', 'success'),
(34, '2025-12-01 03:38:14', '58', 'LOGOUT', 'users', 'success'),
(35, '2025-12-01 03:38:19', '2', 'LOGIN', 'users', 'success'),
(36, '2025-11-30 20:41:06', 'personnel.r', 'Added 4.575 rating to employee 6432515', 'performance', ''),
(37, '2025-11-30 20:41:30', 'personnel.r', 'Added 4.500 rating to employee 6432515', 'performance', ''),
(38, '2025-12-01 05:33:13', '58', 'LOGIN', 'users', 'success'),
(39, '2025-11-30 23:00:59', 'personnel.r', 'Uploaded IPCRF for employee 6432515 (Year: 2024)', 'performance', ''),
(40, '2025-12-01 06:13:22', '2', 'LOGOUT', 'users', 'success'),
(41, '2025-12-01 06:13:49', '2', 'LOGIN', 'users', 'success'),
(42, '2025-12-01 06:23:51', '120', 'LOGIN', 'users', 'success'),
(43, '2025-12-01 07:45:32', '2', 'LOGOUT', 'users', 'success'),
(44, '2025-12-01 08:58:59', '120', 'LOGOUT', 'users', 'success'),
(45, '2025-12-02 01:08:03', '58', 'LOGIN', 'users', 'success'),
(46, '2025-12-02 02:24:33', '58', 'LOGIN', 'users', 'success'),
(47, '2025-12-02 02:32:44', '58', 'LOGOUT', 'users', 'success'),
(48, '2025-12-02 02:32:59', '2', 'LOGIN', 'users', 'success'),
(49, '2025-12-02 02:39:21', '2', 'LOGOUT', 'users', 'success'),
(50, '2025-12-02 02:45:33', '58', 'LOGIN', 'users', 'success'),
(51, '2025-12-02 08:40:07', '58', 'LOGIN', 'users', 'success'),
(52, '2025-12-10 05:19:04', '58', 'LOGIN', 'users', 'success'),
(53, '2025-12-11 01:15:31', '58', 'LOGIN', 'users', 'success'),
(54, '2025-12-11 08:44:51', '58', 'LOGOUT', 'users', 'success'),
(55, '2025-12-12 03:09:07', '1', 'LOGIN', 'users', 'success'),
(56, '2025-12-12 04:14:40', '1', 'LOGOUT', 'users', 'success'),
(57, '2025-12-12 04:15:03', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(58, '2025-12-12 04:22:28', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(59, '2025-12-12 04:24:17', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(60, '2025-12-12 04:25:22', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(61, '2025-12-12 04:26:27', '121', 'LOGIN', 'users', 'success'),
(62, '2025-12-12 04:27:12', '121', 'FAILED_LOGIN', 'users', 'failed'),
(63, '2025-12-12 04:27:21', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(64, '2025-12-12 04:28:04', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(65, '2025-12-12 04:28:32', '121', 'LOGIN', 'users', 'success'),
(66, '2025-12-12 04:56:01', '1', 'LOGIN', 'users', 'success'),
(67, '2025-12-12 05:34:50', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(68, '2025-12-12 07:21:57', '0', 'FAILED_LOGIN_INVALID_EMAIL', 'users', 'failed'),
(69, '2025-12-12 07:22:08', '1', 'LOGIN', 'users', 'success'),
(70, '2025-12-12 07:35:57', '1', 'LOGOUT', 'users', 'success'),
(71, '2025-12-12 07:36:02', '58', 'LOGIN', 'users', 'success'),
(72, '2025-12-12 08:11:58', '58', 'LOGOUT', 'users', 'success'),
(73, '2025-12-12 08:17:19', '54', 'LOGIN', 'users', 'success'),
(74, '2025-12-12 08:41:33', '58', 'LOGIN', 'users', 'success'),
(75, '2025-12-16 04:58:58', '120', 'LOGIN', 'users', 'success'),
(76, '2026-01-05 01:06:10', '58', 'FAILED_LOGIN', 'users', 'failed'),
(77, '2026-01-05 01:06:18', '58', 'LOGIN', 'users', 'success'),
(78, '2026-01-05 02:42:55', '58', 'LOGOUT', 'users', 'success'),
(79, '2026-01-05 02:43:06', '120', 'FAILED_LOGIN', 'users', 'failed'),
(80, '2026-01-05 02:43:14', '120', 'LOGIN', 'users', 'success'),
(81, '2026-01-05 02:58:01', '120', 'LOGIN', 'users', 'success'),
(82, '2026-01-06 02:10:24', '2', 'LOGIN', 'users', 'success'),
(83, '2026-01-07 08:46:50', '2', 'FAILED_LOGIN', 'users', 'failed'),
(84, '2026-01-07 08:46:55', '2', 'LOGIN', 'users', 'success'),
(85, '2026-01-08 06:15:36', '58', 'LOGIN', 'users', 'success'),
(86, '2026-01-08 06:32:16', '58', 'LOGOUT', 'users', 'success'),
(87, '2026-01-08 06:32:22', '120', 'LOGIN', 'users', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `awardlist`
--

CREATE TABLE `awardlist` (
  `awdID` int(11) NOT NULL,
  `awdTitle` text NOT NULL,
  `awdLevel` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awardlist`
--

INSERT INTO `awardlist` (`awdID`, `awdTitle`, `awdLevel`) VALUES
(1, 'Lingkod Bayan Award', 'National'),
(2, 'Dangal ng Bayan Award', 'National'),
(3, 'PAGASA Award', 'National'),
(4, 'Other Award', 'National'),
(5, 'Best Employee Award', 'Regional'),
(6, 'Gantimpala Agad Award', 'Regional'),
(7, 'Exemplary Behavior Award', 'Regional'),
(8, 'Best Organizational Unit', 'Regional'),
(9, 'Cost Economy Measure Award', 'Regional'),
(10, 'Service Award', 'Regional');

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `awardID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `awardTitle` text NOT NULL,
  `awardType` text NOT NULL COMMENT 'recognition, commendation',
  `organization` text NOT NULL,
  `level` text NOT NULL COMMENT 'regional, national',
  `dateReceived` date NOT NULL,
  `certificate` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`awardID`, `employeeID`, `awardTitle`, `awardType`, `organization`, `level`, `dateReceived`, `certificate`) VALUES
(2, 5818663, 'On the Spot Award', 'Recognition', 'DepEd ROV', 'Regional', '2025-06-27', 0x75706c6f6164732f61776172642f4c4547534f4e5f4163636f6d706c6973686d656e74732e706466),
(3, 5818663, 'National Digital Challenge', 'Recognition', 'DICT', 'National', '2025-06-25', 0x75706c6f6164732f61776172642f434f46202d204c4547534f4e2c204b4152454e20534f5249414e4f2e706466);

-- --------------------------------------------------------

--
-- Table structure for table `coc`
--

CREATE TABLE `coc` (
  `cocID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `overtimeDate` date NOT NULL,
  `overtimeType` text NOT NULL,
  `cocStartTime` time NOT NULL,
  `cocEndTime` time NOT NULL,
  `hours` float NOT NULL,
  `cocCreditsEarned` float NOT NULL,
  `overtimeReason` text NOT NULL,
  `cocApprovedBy` text NOT NULL,
  `cocApprovedDate` date NOT NULL,
  `status` text NOT NULL,
  `expiryDate` date DEFAULT NULL,
  `cocBalance` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coc`
--

INSERT INTO `coc` (`cocID`, `employeeID`, `overtimeDate`, `overtimeType`, `cocStartTime`, `cocEndTime`, `hours`, `cocCreditsEarned`, `overtimeReason`, `cocApprovedBy`, `cocApprovedDate`, `status`, `expiryDate`, `cocBalance`) VALUES
(1, 5818663, '2006-01-24', 'Special Holiday OT', '15:37:00', '09:31:00', 84, 47, 'ITO FGD', 'Gilbert Sadsad', '0000-00-00', 'active', NULL, 0),
(2, 5818663, '2025-12-06', 'Regular Day OT', '07:49:00', '19:02:00', 12, 1.5, 'Saturday overtime', 'Juan delaCruz', '0000-00-00', '', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `coc_tbl`
--

CREATE TABLE `coc_tbl` (
  `cocTypeID` int(11) NOT NULL,
  `cocType` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coc_tbl`
--

INSERT INTO `coc_tbl` (`cocTypeID`, `cocType`) VALUES
(1, 'Regular Day OT');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `officeID` int(11) NOT NULL,
  `officeCode` text NOT NULL,
  `officeName` text NOT NULL,
  `section_unit` text NOT NULL,
  `departmentHead` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`officeID`, `officeCode`, `officeName`, `section_unit`, `departmentHead`) VALUES
(1, 'AD-AMS', 'Administrative Division', 'Asset Management Section', '4217547'),
(2, 'AD-GSU', 'Administrative Division', 'General Services Unit', '4821305'),
(3, 'AD-PSU', 'Administrative Division', 'Payroll Services Unit', '4217547'),
(4, 'AD-RECORDS', 'Administrative Division', 'Records Section', '4821344'),
(5, 'AD-PERSONNEL', 'Administrative Division', 'Personnel Section', '4821307'),
(6, 'AD-CASH', 'Administrative Division', 'Cash Section', '4821330'),
(7, 'AD', 'Administrative Division', '', '4821278'),
(8, 'CLMD-LRMS', 'Curriculum and Learning Management Division', 'Learning Resource Management Section', '4821265'),
(9, 'CLMD', 'Curriculum and Learning Management Division', '', '4821265'),
(10, 'ESSD-HN', 'Education Support Services Division', 'Health and Nutrition', '4821259'),
(11, 'ESSD-PP', 'Education Support Services Division', 'Programs and Projects', '4821259'),
(12, 'ESSD-FAC', 'Education Support Services Division', 'Facilities', '4821259'),
(13, 'ESSD', 'Education Support Services Division', '', '4821259'),
(14, 'FTAD', 'Field Technical Assistance Division', '', '4821278'),
(15, 'FD-BUDGET', 'Finance Division', 'Budget Section', '4821345'),
(16, 'FD-ACCOUNTING', 'Finance Division', 'Accounting Section', '4821335'),
(17, 'FD', 'Finance Division', '', '4821346'),
(18, 'HRDD', 'Human Resource Development Division', '', '4821290'),
(19, 'HRDD-NEAP', 'Human Resource Development Division', 'NEAP', '4821290'),
(20, 'ARD', 'Office of the Assistant Regional Director', '', '5007850'),
(21, 'ORD-PROCUREMENT', 'Office of the Regional Director', 'Procurement Unit', '5812149'),
(22, 'ORD-ICT', 'Office of the Regional Director', 'Information and Communications Technology Unit', '4821247'),
(23, 'ORD-PAU', 'Office of the Regional Director', 'Public Affairs Unit', '6313713'),
(24, 'ORD-LEGAL', 'Office of the Regional Director', 'Legal Unit', '4819118'),
(25, 'ORD', 'Office of the Regional Director', '', '4510336'),
(26, 'PPRD', 'Policy Planning and Research Division', '', '4821294'),
(27, 'QAD', 'Quality Assurance Division', '', '4821284');

-- --------------------------------------------------------

--
-- Table structure for table `dependents`
--

CREATE TABLE `dependents` (
  `dependentID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `dependentName` text DEFAULT NULL,
  `dependentBdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dependents`
--

INSERT INTO `dependents` (`dependentID`, `employeeID`, `dependentName`, `dependentBdate`) VALUES
(2, 6432515, 'Antheia Zealene B. Buhat', '2018-07-09'),
(3, 6323343, 'Edric Luis L. Montas', '2014-09-30'),
(4, 6324399, 'Juan Dante B. Correo', '2023-07-20'),
(6, 4821313, 'APRIL KAE B. ENAJE', '1996-02-02'),
(7, 4821298, 'SHERRAINE M. LIM', '1992-11-19'),
(8, 4821298, 'BRENT DEAN M. LIM', '1999-10-23'),
(10, 4540747, 'Rey Allen R. Monreal', '2003-01-28'),
(11, 4540747, 'Hans Andrey R. Monreal', '2016-11-12'),
(12, 5818663, 'NA', '0001-01-01'),
(16, 4821290, 'Earl Jarvis M. Nacion (Deceased)', '1996-12-14'),
(17, 4821290, 'Cian Jiro M. Nacion', '2007-09-18'),
(18, 4821290, 'Alessa Akibe M. Nacion', '2011-01-19');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `educationID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `level` text NOT NULL,
  `unitsEarned` text NOT NULL,
  `postGradCourseName` text NOT NULL,
  `postGradSchool` text NOT NULL,
  `postGradYearAttended` text NOT NULL,
  `postGradAward` text NOT NULL,
  `tertiaryCourseName` text NOT NULL,
  `tertiarySchool` text NOT NULL,
  `tertiaryYearAttended` text NOT NULL,
  `tertiaryAward` text NOT NULL,
  `secSchool` text NOT NULL,
  `secYearAttended` text NOT NULL,
  `secAward` text NOT NULL,
  `elemSchool` text NOT NULL,
  `elemYearAttended` text NOT NULL,
  `elemAward` text NOT NULL,
  `postGradData` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`educationID`, `employeeID`, `level`, `unitsEarned`, `postGradCourseName`, `postGradSchool`, `postGradYearAttended`, `postGradAward`, `tertiaryCourseName`, `tertiarySchool`, `tertiaryYearAttended`, `tertiaryAward`, `secSchool`, `secYearAttended`, `secAward`, `elemSchool`, `elemYearAttended`, `elemAward`, `postGradData`) VALUES
(3, 11, '', '', '      ', '', '', '', 'BS Financial Management', 'dwdwwdwd', '54545-45455', '', 'dwd', '444-44', '', '', '', '', '0'),
(4, 6432515, '', '', '               ', '', '-', '', 'Bachelor of Science in Information Technology', 'Bicol University', '2010-2014', '', 'Daraga National High School', '2006-2010', '', 'San Vicente Elementary School', '2000-2006', '', '0'),
(5, 4722653, '', '', '         ', '', '-', '', 'BACHELOR OF SCIENCE IN INDUSTRIAL EDUCATION - MAJOR IN COMPUTER EDUCATION', 'TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES', '2008-2012', '', 'BRICCIO A. ANINANG SR. MEMORIAL HIGH SCHOOL', '2003-2007', '', '', '', '', '0'),
(6, 4821329, '', 'NA', '   NA                     ', 'NA', '-', '', 'Bachelor of Science in Tourism Management', 'University of Makati', '2008-2012', '', 'Benigno Ninoy Aquino High School', '2004-2008', '', '', '', '', '0'),
(7, 4590076, '', '', '   Doctor of Philosophy major in Human Development Management (PhD-HDM)         ', 'Universidad de Sta. Isabel, Naga City', '2016-2019', '', 'Bachelor of Secondary Education major in Biological Science', 'Baao Community College, Baao, Camarines Sur', '2005-2009', '', 'Zeferino Arroyo High School', '2001-2005', '', '', '', '', '0'),
(8, 4821350, '', 'Graduated', '   Ph. D. in Educational Development                        ', 'University of St. Anthony', '2000-2004', '', 'Bachelor of Science in Education', 'Bicol University', '1982-1986', '', 'Quezon Academy', '1979-1092', '', '', '', '', '0'),
(9, 4821290, '', 'Graduated', '   Doctor of Education                                 ', 'Bicol College', '2012-2018', 'None', 'Bachelor in Elementary Education', 'Bicol University College of Education', '1985-1989', 'Cum Laude', 'Lacag Barangay High School', '1981-1985', 'Valedictorian', 'Lacag Barangay High School', '1976-1981', 'Valedictorian', '0'),
(10, 4821368, '', '', '         ', '', '-', '', 'Bachelor of Science in Accountancy', 'Divine Word College of Legazpi', '2008-2013', '', 'Abucay National High School', '2004-2008', '', '', '', '', '0'),
(11, 4821245, '', '', '   Juris Doctor      ', 'University of Sto. Tomas - Legazpi Campus - College of Law', '2016-2021', '', 'Bachelor of Science in Information Technology', 'AMA Computer College - Legazpi Campus', '2007-2011', '', 'Pag-asa National High School', '2003-2007', '', '', '', '', '0'),
(12, 4821335, '', 'Graduated', '   Masters in Business Adminstration      ', 'Divine Word College of Legazpi', '2015-2018', '', 'BS Accountancy', 'Divine Word College of Legazpi', '2007-2012', '', 'Tabaco National High School', '2003-2007', '', '', '', '', '0'),
(13, 4214395, '', 'graduated', '   Master in Management major in  Public Administration         ', 'Bicol College', '2019-2020', '', 'Bachelor of Science in  Agricultural Business', 'Bicol University', '1994-1995', '', 'Dominican School (former St. John\'s Academy)', '1989-1990', '', '', '', '', '0'),
(14, 6430454, '', 'n/a', '   n/a            ', 'n/a', '-', '', 'n/a', 'n/a', '0-0', '', 'n/a', '0-0', '', '', '', '', '0'),
(15, 6433401, '', 'NONE', '   NONE         ', 'NONE', '-', '', 'BACHELOR OF ARTS IN PHILOSOPHY', 'BICOL UNIVERSITY - CSSP', '2012-2016', '', 'DARAGA NATIONAL HIGH SCHOOL', '2008-2012', '', '', '', '', '0'),
(16, 4540747, '', 'Diploma', '   Doctor of Educational Management               ', 'Bicol College', '2014-2016', '', 'Bachelor of Secondary Education Major in General Science', 'Bicol University College of Education (BUCE)', '1988-1992', '', 'Bicol University High School (BUHS)', '1984-1988', '', 'St. Agnes Academy', '1977-1984', '', '0'),
(17, 6429552, '', '', '    ', '', '-', '', 'BS in Information Technology', 'Camarines Sur Polytechnic Colleges', '2010-2013', '', 'Holy Trinity College', '2006-2009', '', '', '', '', '0'),
(18, 4821307, '', 'CAR', '   Master in Business Administration         ', 'Divine Word College of Legazpi Graduate School', '2003-2007', '', 'Bachelor of Science in Computer Science', 'Bicol University Computer Science Institute', '1996-2000', '', 'Divine Word College of Legazpi High School Department', '1992-1996', '', '', '', '', '0'),
(19, 6430260, '', '', '            ', '', '-', '', '   Bachelor of Science in Entrepreneurship', 'Bicol University, College of Business, Economics and Management', '2008-2012', '', 'Bogtong National High School', '2004-2008', '', '', '', '', '0'),
(20, 4821298, '', '', '                                                         ', '', '-', '', 'BSCS', 'CCP', '1987-1991', '', 'HOLY SPIRIT ACADEMY OF IROSIN', '1983-1987', '', 'HOLY SPIRIT ACADEMY OF IROSIN', '1982-1986', '', '0'),
(21, 4821277, '', '', ' Doctor of Philosophy,               ', 'University of St. Anthony, Iriga City', '1999-2004', '', 'Bachelor in Elementary Education (BEED)', 'University of Saint Anthony', '1986-1990', '', 'University of Saint Anthony', '1980-1985', '', '', '', '', '0'),
(22, 4821347, '', '', '      ', '', '-', '', 'Bachelor of Science in Accountancy', 'Divine Word College of Legazpi', '2008-2013', '', 'MABA Computer Oriented High School', '2003-2007', '', '', '', '', '0'),
(23, 4821291, '', 'Complete Academic Requirements', '   Ph.D  in Filipinp   ', 'Bicol University, Graduate School, Legazpi City', '2009-', '', 'Bachelor of Science in Integrated Teachers Education Program (BSITEP)', 'Bicol University College of Education', '1981-1985', '', 'Donsol National Comprehensive High School, Donsol Sorsogon', '1977-1981', '', '', '', '', '0'),
(24, 4821280, '', '', '', '', '-', '', 'Bachelor in Science in Computer Science', 'Dynamic Computer Centrum', '1990-1994', '', 'Catholix Central School', '1986-1990', '', '', '', '', '0'),
(25, 4821276, '', '', '   N/A         ', '', '-', '', 'BSBA', 'BICOL UNIVERSITY COLLEGE OF ARTS & SCIENCES', '1993-1997', '', 'DIVINE WORD COLLEGE HIGH SCHOOL DEPT. ', '1989-1993', '', '', '', '', '0'),
(26, 4821351, '', '18', '  Bachelor in Secondary Education      ', 'Catanduanes College', '2013-2014', '', 'Bachelor of Science in Nursing', 'Immaculate Conception College-Albay', '2005-2007', '', 'Catanduanes State Colleges Laboratory High School', '1998-2002', '', '', '', '', '0'),
(27, 6430453, '', '100', '   Juris Doctor', 'Bicol University', '2021-2024', '', 'BS Accountancy', 'Bicol University', '2013-2018', '', 'Sto. Domingo National High School', '2009-2013', '', '', '', '', '0'),
(28, 4821326, '', 'Graduate', 'Masters in Management   ', 'Bicol College', '-', '', 'B.S. Medical Technology', 'AgoMedical and Educational Center', '1990-1993', '', 'Bicol University High School', '1985-1989', '', '', '', '', '0'),
(29, 5019300, '', '', 'Doctor of Philosophy (Ph. D.) major in Educational Development         ', 'Annunciation College of Bacon Sorsogon Unit   ', '2010-2016', '', 'Bachelor of Secondary Education (BSEd) major in Mathematics', 'Aquinas University of Legazpi City', '1987-1992', '', 'Sorsogon National High School', '1983-1987', '', '', '', '', '0'),
(30, 4821339, '', 'GRADUATED', '   MASTER IN PUBLIC ADMINISTRATION         ', 'BICOL COLLEGE', '2016-2019', '', 'BACHELOR OF SCIENCE IN COMMERCE MAJOR IN ACCOUNTING', 'DIVINE WORD COLLEGE OF LEGAZPI', '1981-1985', '', 'BICOL UNIVERSITY HIGH SCHOOL', '1976-1980', '', '', '', '', '0'),
(31, 4821299, '', '', '      ', '', '-', '', '0', '0', '0-0', '', '0', '0-0', '', '', '', '', '0'),
(32, 4821330, '', 'Graduated', '   MASTER IN BUSINESS ADMINISTRATION-Major in Financial Management   ', 'Divine Word College of Legazpi', '2017-2019', '', 'BACHELOR OF SCIENCE IN COMMERCE-Major in Management', 'DIVINE WORD COLLEGE OF LEGAZPI', '1993-1998', '', 'ST. RAPHAEL ACADEMY', '1985-1989', '', '', '', '', '0'),
(33, 4821313, '', '', '                  ', '', '-', '', 'AB Liberal Arts (AB)', 'DWCL', '1988-1991', '', 'United Institute ', '1984-1987', '', '', '', '', '0'),
(34, 4818693, '', 'Graduated', '   Doctor of Education   ', 'Bicol University', '2006-2013', '', 'Bachelor of Science in Secondary Education', 'Bicol University College of Education', '1994-1998', '', 'Polangui General Comprehensive High School', '1990-1994', '', '', '', '', '0'),
(35, 4821247, '', 'GRADUATE', '   BS               ', 'DCC', '-', '', 'BS', 'DCC', '1994-1997', '', 'SCAT', '1983-1987', '', '', '', '', '0'),
(36, 4821337, '', '', '               ', '', '-', '', 'Bachelor of Science in Commerce', 'Divine Word College of Legazpi', '1988-1991', '', 'Divine Word College of Legazpi', '1984-1987', '', '', '', '', '0'),
(37, 4821305, '', 'Graduated', '   Bachelor of Law', 'Bicol College of Law', '1992-1996', '', 'Bachelor of Science Major in Political Science', 'Bicol College ', '1989-1992', '', 'Bicol College High School', '1986-1989', '', '', '', '', '0'),
(38, 5826994, '', '', '   ', '', '-', '', 'BS ACCOUNTANCY ', 'Bicol University ', '2013-2018', '', 'Legazpi City High School', '2009-2013', '', '', '', '', '0'),
(39, 4821243, '', '31', '   Masters in  Management          ', 'Bicol College, Daraga Albay', '1992-1994', '', 'Bachelor od Science in Nutrition and Dietetics', 'Aquinas University of Legazpi ( AUL)', '1980-1986', '', 'St. Agnes Academy, Legazpi City', '1976-1980', '', '', '', '', '0'),
(40, 5052280, '', ' Graduate', '   BICOL COLLEGE GRADUATE SCHOOL         ', 'DOCTOR OF EDUCATION', '2010-2013', '', 'Bachelor of Secondary Education', ' Arellano University, Malabon', '1986-1990', '', ' Malabon  Municipal High School', '1982-1986', '', '', '', '', '0'),
(41, 4821284, '', 'Doctorate Graduate', '   Doctor of Philosophy, Major in Educational Management         ', 'Univeristy of Saint Anthony', '2004-2007', '', 'Bachelor of Science in Agricultural Education', 'Bicol University College of Agriculture', '1986-1990', '', 'Malipo High School', '1982-1986', '', '', '', '', '0'),
(42, 4821292, '', '300', '   BACHELOR SECONDARY EDUCATION (BSED)', 'PLT COLLEGE GUINOBATAN, ALBAY', '2004-2008', '', 'GENERAL RADIO COMMUNICATION OPERATOR', 'REPUBLIC COLLEGES GUINOBATAN ALBAY', '1994-1996', '', 'MALIPO HIGH SCHOOL', '1989-1993', '', '', '', '', '0'),
(43, 4218819, '', '27', 'Master of Education in Guidance and Counseling and Master of Arts in Reading Education         ', 'Bicol University Graduate School', '2008-2013', '', 'Bachelor in Elementary Education', 'Bicol University College of Education', '1998-2002', '', 'Aquinas University High School ', '1994-1998', '', '', '', '', '0'),
(44, 4821261, '', 'Graduate', '   Masters in Educational Management', 'Bicol University', '2004-2008', '', 'Bachelor of Science in Secondary Education (Major in History)', 'Bicol University', '1990-1994', '', 'St. Stephen\'s Academy', '1986-1989', '', '', '', '', '0'),
(45, 4821265, '', 'Completed', '   Doctor of Philosophy Major in Educational Management   ', 'University of Saint Anthony', '2008-2014', '', 'Bachelor in Elementary Education', 'Bicol University College of Education', '1989-1993', '', 'University of Saint Anthony', '1986-1989', '', '', '', '', '0'),
(46, 6313753, '', '18', '   Methods of Teaching   ', 'Republic College ', '2021-', '', 'BS Architecture ', 'Bicol University College of Engineering ', '1994-1999', '', 'Aquinas University High School ', '1990-1994', '', '', '', '', '0'),
(47, 6323343, '', '', 'Bachelor of Laws', 'University of Santo Tomas - Legazpi', '2001-2005', '', 'AB Political Science', 'Bicol University College of Arts and Sciences', '1997-2001', '', 'St. Agnes Academy', '1993-1997', '', '', '', '', '0'),
(48, 4821278, '', 'Graduated of Doctor of Education', '   Doctor of Education    ', 'University of Northeastern Philippines', '2015-2017', '', 'Bachelor of Science in Practical Arts Education', 'School For Philippine Craftsmen (now Bicol University Polangui Campus )', '1990-1994', '', 'Mayon High School', '1984-1988', '', '', '', '', '0'),
(49, 4821259, '', 'Graduated', '   Doctor of Philosophy', 'Annunciation College of Bacon, Sorsogon Unit Inc.', '2012-2018', '', 'Bachelor of Secondary Education', 'Bicol University College of Education', '1988-1992', '', 'Sorsogon National High School', '1984-1988', '', '', '', '', '0'),
(50, 4588465, '', '', '   Master of Library and Information Science   ', 'University of Perpetual Help System', '-', '', 'Bachelor of Secondary Education', 'Bicol University College of Education', '1997-2004', '', 'Bicol College High Scool', '1992-1997', '', '', '', '', '0'),
(51, 5818663, '', '24', 'Master in Information Systems', 'Bicol University', '2017-2018', '', 'BS Information Technology', 'Bicol University', '2012-2016', '', 'Divine Word College of Legazpi', '2008-2012', '', 'Daraga North Central School', '2002-2008', '', '[{\"courseName\":\"Master in Information Systems\",\"school\":\"Bicol University\",\"yearAttended\":\"2017-2018\",\"unitsEarned\":\"24\",\"award\":\"\"},{\"courseName\":\"Docto\",\"school\":\"sfmfmkl\",\"yearAttended\":\"2025-2026\",\"unitsEarned\":\"kejfklejl\",\"award\":\"fefer\"}]'),
(52, 4821246, '', '', '   ', '', '-', '', 'N/A', 'N/A', '0000-000', '', 'Pili National High School', '1985-1989', '', '', '', '', '0'),
(53, 4821248, '', '', '                        ', '', '-', '', 'BS Electronics Technology', 'Bicol University College of Industrial Technology', '2003-2007', '', 'Bicol University College of Industrial Technology', '2007-2011', '', '', '', '', '0'),
(54, 4821312, '', '', '    None                       ', '', '-', '', 'BS - Computer Science', 'Dynamic Computer Centrum', '1995-1991', '', 'Aquinas University', '1991-1987', '', '', '', '', '0'),
(55, 4821269, '', 'Graduated', 'Master of Arts in Education, Major in Administration and Supervision', 'Partido College, Camarines Sur', '-1997', '', 'Bachelor of Science in Secondary Education, Major in Physical Education', 'Bicol University College of Education, Daraga, Albay', '1986-1990', '', 'Bicol University School of Arts and Trades, Legazpi City', '1982-1986', '', '', '', '', '0'),
(56, 4635458, '', '36', 'Doctor of Philosophy, Major in Education Management   ', 'University of St. Anthony', '2022-2024', '', 'Bachelor of Science in Education', 'Bicol University', '1987-1991', '', 'Bicol University', '1982-1986', '', '', '', '', '0'),
(57, 4821254, '', 'GRADUATED', '   MASTER OF ARTS MAJOR IN GUIDANCE AND COUNSELLING & HOME ECONOMICS            ', 'UNIVERSIDAD DE STA ISABEL & NAGA COLLEGE FOUNDATION', '1997-2012', '', 'BACHELOR OF SCIENCE IN INDUSTRIAL EDUCATION', 'BICOL COLLEGE OF ARTS AND TRADES', '1997-2012', '', 'SAN PASCUAL NATIONAL HIGH SCHOOL', '1978-1982', '', '', '', '', '0'),
(58, 4821251, '', '', '   none      ', '', '-', '', 'BCS-Management', 'Divine Word College of Legazpi', '1990-1996', '', 'Manuel L. Quezon National High School', '1988-1990', '', '', '', '', '0'),
(59, 4510336, '', 'MAED', '   Master of Arts in Education   ', 'Masbate Colleges', '1989-1996', '', 'Bachelor of Arts in Industrial Arts', 'Ovilla Technical College', '1982-1987', '', 'Ovilla Institute of Arts and Trades', '1978-1982', '', '', '', '', '0'),
(60, 4821320, '', '', '   master of none', 'harvard university', '-', '', 'engineering', 'bicol university', '1982-1983', '', 'St. Michael Academy', '1972-1978', '', '', '', '', '0'),
(61, 6431010, '', '30', '   Master in Management', 'Bicol University Open University', '2020-2023', '', 'AB Economics', 'Bicol University College of Business Economics and Management', '2002-2006', '', 'Polangui General Comprehensive High School', '1998-2002', '', '', '', '', '0'),
(62, 5812149, '', 'full pledge', '   Master of Science in Resource Management  Major in Cooperative Management', 'Central Bicol State University of Agriculture', '2009-2016', '', 'BSBA in Financial Accounting', 'Ateneo De Naga University', '2009-2014', '', 'Liong National High School', '2002-2006', '', '', '', '', '0'),
(63, 4821336, '', '36 UNITS (CAR)', '   MASTER IN BUSINEES ADMINISTRATION', 'UNIVERSITY OF SANTO TOMAS-LEGAZPI', '2019-2012', '', 'BACHELOR OF SCIENCE IN COMMERCE - ACCOUNTING', 'DIVINE WORD COLLEGE OF LEGAZPI', '1979-1983', '', 'BICOL UNIVERSITY SCHOOL OF ARTS AND TRADES', '1974-1979', '', '', '', '', '0'),
(64, 4821344, '', 'Graduated', '  Masters in Management   major in Public Administration      ', 'Bicol College ', '1994-2017', '', 'BSC Accounting', 'Divine Word College of Legazpi', '1979-1983', '', 'Carrascal Memorial Academy', '1975-1979', '', '', '', '', '0'),
(65, 4821323, '', '', '        ', '', '-', '', 'BSED', 'Aquinas University of legazpi', '1989-1993', '', 'St. Michael Academy', '1984-1988', '', '', '', '', '0'),
(66, 4177881, '', '', '                                       ', '', '-', '', 'AB Philosophy', 'Aquinas University', '1988-1992', '', 'Pagasa National High School', '1988-1984', '', '', '', '', '0'),
(67, 4821328, '', '', '                     ', '', '-', '', 'Bachelor of Science in Computer Engineering', 'Aquinas University of Legazpi City ', '1994-1999', '', 'Tabaco National High School ', '1990-1994', '', '', '', '', '0'),
(68, 4821302, '', 'Graduated', '   Master of Public Administration ', 'University of North Eastern Philippines ', '2015-2017', '', 'Bachelor of Arts in Political Science ', 'University of Nueva Caceres ', '1982-1986', '', 'Camarines Sur National College of Arts and Trades ', '1978-1982', '', '', '', '', '0'),
(69, 6313713, '', '30 Units', '   Master in Development Communication (MDC)', 'University of the Philippines Open University (UPOU)', '2019-2024', '', 'Bachelor in Communication Arts (BCA) - Journalism', 'Bicol University', '1995-1999', '', 'Sorsogon College of Arts and Trades', '1990-1994', '', '', '', '', '0');

-- --------------------------------------------------------

--
-- Table structure for table `eligibility`
--

CREATE TABLE `eligibility` (
  `eligibilityID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `eligibilityTitle` text NOT NULL,
  `eligibilityRating` text NOT NULL,
  `examinationDate` date NOT NULL,
  `examinationPlace` text NOT NULL,
  `licenseNumber` text NOT NULL,
  `expirationDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `eligibility`
--

INSERT INTO `eligibility` (`eligibilityID`, `employeeID`, `eligibilityTitle`, `eligibilityRating`, `examinationDate`, `examinationPlace`, `licenseNumber`, `expirationDate`) VALUES
(1, 5818663, 'Licensed Professional Teacher ', '89', '2022-02-02', 'Legazpi City', '8181812', '2025-02-02'),
(4, 4821350, 'PBET', '76.86', '1986-10-26', 'Legazpi City', '0304819', '2025-01-08'),
(5, 4821313, 'Carrer Service Professsional', '80.00%', '1994-11-27', 'Pag-Asa High School', '088478', '1994-11-27'),
(6, 4821276, 'Career Service Sub-Professional', '82.5', '1998-07-17', 'Civil Service Commission Regional Office V, Rawis, Legazpi City', '00', '0001-01-01'),
(7, 4821290, 'Career Executive Service Written Examination', '81.63', '2022-11-06', 'Legazpi City', '000000', '0001-01-01'),
(8, 4821290, 'Career Service Professional Examination', '84', '0001-01-01', 'Legazpi City', '00000', '0001-01-01'),
(9, 4821290, 'Professional Board Examination for Teachers', '77.02', '0001-01-01', 'Legazpi City', '00000', '0001-01-01'),
(15, 5818663, 'Lorem officia conseq', 'Exercitation volupta', '2017-12-07', 'Laborum Quia qui qu', '159', '1984-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employeeID` int(11) NOT NULL,
  `firstName` text NOT NULL,
  `middleName` text NOT NULL,
  `lastName` text NOT NULL,
  `extension` text DEFAULT NULL,
  `birthDate` date NOT NULL,
  `birthPlace` text DEFAULT NULL,
  `res_barangay` text NOT NULL,
  `res_municipality` text NOT NULL,
  `res_province` text NOT NULL,
  `res_zipcode` text NOT NULL,
  `res_region` text NOT NULL,
  `perm_barangay` text NOT NULL,
  `perm_municipality` text NOT NULL,
  `perm_province` text NOT NULL,
  `perm_zipcode` text NOT NULL,
  `perm_region` text NOT NULL,
  `citizenship` text NOT NULL,
  `sex` text NOT NULL,
  `civilStatus` text NOT NULL,
  `mobileNumber` text DEFAULT NULL,
  `emailAddress` varchar(299) NOT NULL,
  `height` text DEFAULT NULL,
  `weight` text DEFAULT NULL,
  `bloodType` text DEFAULT NULL,
  `GSIS` text DEFAULT NULL,
  `PAGIBIG` text DEFAULT NULL,
  `PHILHEALTH` text DEFAULT NULL,
  `TIN` text DEFAULT NULL,
  `SSS` text NOT NULL,
  `positionTitle` text NOT NULL,
  `appointmentStatus` text NOT NULL,
  `classification` text NOT NULL,
  `station` text NOT NULL,
  `originalAppointment` date NOT NULL,
  `telephoneNumber` text NOT NULL,
  `supervisorID` int(11) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employeeID`, `firstName`, `middleName`, `lastName`, `extension`, `birthDate`, `birthPlace`, `res_barangay`, `res_municipality`, `res_province`, `res_zipcode`, `res_region`, `perm_barangay`, `perm_municipality`, `perm_province`, `perm_zipcode`, `perm_region`, `citizenship`, `sex`, `civilStatus`, `mobileNumber`, `emailAddress`, `height`, `weight`, `bloodType`, `GSIS`, `PAGIBIG`, `PHILHEALTH`, `TIN`, `SSS`, `positionTitle`, `appointmentStatus`, `classification`, `station`, `originalAppointment`, `telephoneNumber`, `supervisorID`, `status`) VALUES
(4128838, 'Teresa', 'T', 'Buasan', 'none', '1978-05-01', 'Cavite City', 'San Ramon', 'Tabaco', 'Albay', '4511', 'V (Bicol)', 'San Ramon', 'Tabaco', 'Albay', '4511', 'V (Bicol)', 'Filipino', 'F', 'Single', '091641662621', 'teresa.buasan@deped.gov.ph', '1.55', '90', 'A', '2002233481', '152000164652', '100000598857', '922655031', 'none', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Curriculum and Learning Management Division-Learning Resource Management Section', '2024-08-15', '0', 4821265, ''),
(4177881, 'Joseph  ', 'A.', 'Sarza', '', '0000-00-00', 'Legazpi City', 'Arimbay', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '09755618758', 'joseph.sarza@deped.gov.ph', '', '', 'B+', '', '', '', '', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', 'Education Support Services Division-', '0000-00-00', '', 4821259, ''),
(4214395, 'Thelma  ', 'Naron', 'Navera', '', '0000-00-00', 'Camalig, Albay', 'Tagas', 'Daraga (Locsin)', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Single', '09150088430', 'thelma.navera@deped.gov.ph', '0', '52 kls', 'AB+', '0', '0', '0', '0', '', 'Administrative Officer IV', 'permanent', 'Non-Teaching', 'Administrative Division-Personnel Section', '0000-00-00', '', 4821284, ''),
(4217547, 'Santiago Jacky', 'D.', 'Villafuerte', 'II', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'santiagojacky.villafuerte@deped.gov.ph', '', '52 kg', 'AB', '20-0229-5091-6', '0501-2276-9910', '10-500000000-0', '922-836-614', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4218596, 'Ronaldo  ', 'B.', 'Buella', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', '', '', '', 'ronaldo.buella@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4218819, 'Melanie  ', 'Dayto', 'Encarnacion', '', '0000-00-00', 'Legazpi City', 'Arimbay', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09605950616', 'melanie.dayto@deped.gov.ph', '1.56', '75 kg', 'O+', '006-0121-3370-7', '1520-0018-3860', '10-000079626-8', '935-236-249', '', 'Education Program Specialist II', 'permanent', 'Teaching-Related', 'Human Resource Development Division-', '0000-00-00', '', 4821294, ''),
(4244600, 'Roy  ', 'G.', 'Rapsing', '', '0000-00-00', 'Irosin, Sorsogon', 'Sta. Teresita', 'Bulan', 'Sorsogon', '4706', 'V', '', '', '', '', '', 'Filipino', 'M', 'Married', '9087636818', 'roy.rapsing001@deped.gov.ph', '1.54  m', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4510336, 'Bebiano  ', 'Inhog', 'Sentillas', '', '0000-00-00', 'Cataingan, Masbate', 'Poblacion District 2', 'Mobo', 'Masbate', '5401', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09291478399', 'bebiano.sentillas@deped.gov.ph', '1.6', '73', 'B', '2003079822', '0501-1419-6703', '10-000054763-2', '130-007-433', '', 'Director III', 'permanent', 'Non-Teaching', 'Office of the Assistant Regional Director-', '0000-00-00', '', 5007850, ''),
(4515681, 'Lorenzo ', 'J.', 'Avenido', 'Jr.', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'lorenzo.avenido@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4516231, 'Leo  ', 'R.', 'Madriaga', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'leo.madriaga@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4520206, 'Israel  ', 'F.', 'Parra', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'israel.parra@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4537187, 'Joe-Bren  ', 'L.', 'Consuelo', '', '0000-00-00', 'Ligao City', 'Mahaba', 'Ligao', 'Albay', '4504', 'V', '', '', '', '', '', 'Filipino', 'M', 'Single', '9171447600', 'joe-bren.consuelo001@deped.gov.ph', '1.73  m', '77 kg', 'A+', '006-0165-7310-9', '1210-6317-4626', '10-0501000000', '286-887-047-000', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4540747, 'Hallen  ', 'Ragragio', 'Monreal', '', '1971-08-26', 'Daraga, Albay', 'Cruzada', 'Legazpi City', 'Albay', '4500', 'V', 'Cruzada', ' ', 'Albay', '4500', 'V', 'Filipino', 'F', 'Married', '9177048513', 'hallen.monreal001@deped.gov.ph', '1.6  m', '63kgs', 'O', '', '', '', '', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Policy Planning and Research Division-', '0000-00-00', '', 0, ''),
(4588465, 'Antonio  ', 'Loterte', 'Morada', '', '0000-00-00', 'Daraga', 'Ilawod', 'Camalig', 'Albay', '4502', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09176231761', 'antonio.morada@deped.gov.ph', '', '', 'n/a', 'n/a', 'n/a', 'n/a', '233766726', '', 'Librarian II', 'permanent', 'Non-Teaching', 'Curriculum and Learning Management Division-Learning Resource Management Section', '0000-00-00', '', 4821265, ''),
(4590076, 'Joy  ', 'C.', 'Chavez', '', '0000-00-00', 'Calalahan, San Jose, Camarines Sur', 'San Nicolas', 'Iriga City', 'Camarines Sur', '4431', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Single', '9985538526', 'joy.chavez1@deped.gov.ph', '1.67  m', '', '', '1', '1', '1', '285833690', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Field Technical Assistance Division-', '0000-00-00', '', 4821278, ''),
(4635458, 'Maria Ayrin ', 'B.', 'Adriano', '', '0000-00-00', 'Guinobatan, Albay', 'Poblacion', 'Guinobatan', 'Albay', '4503', 'V', 'Poblacion', 'Guinobatan', 'Albay', '4503', 'V', 'Filipino', 'M', 'Widowed', '09208187902', 'mariaayrin.adriano@deped.gov.ph', '1.499', '66', 'O+', '2002300575', '1470-0090-0746		 		', '09-050123262-8		', '911-170-571		', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Curriculum and Learning Management Division-Learning Resource Management Section', '0000-00-00', '', 4821265, ''),
(4658383, 'Mercy  ', 'S.', 'Castillo', '', '0000-00-00', 'Ombao, Bula, Camarines Sur', 'Caranan', 'Pasacao', 'Camarines Sur', '4417', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9288748631', 'mercy.castillo1@deped.gov.ph', '1.58  m', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4722419, 'Loyd  ', 'H.', 'Botor', '', '0000-00-00', 'Pgh, Manila', 'Palsong', 'Bula', 'Camarines Sur', '4430', 'V', '', '', '', '', '', 'Filipino', 'M', 'Single', '9182938311', 'lloyd.botor@deped.gov.ph', '1.67  m', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4722653, 'Jeremy  ', 'Arroyo', 'Atad', '', '0000-00-00', 'Balud, Masbate', 'Panguiranan ', 'Balud', 'Masbate', '5412', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9605950614', 'jeremy.atad@deped.gov.ph', '1.7  m', '85', '0', '2004359084', '0', '10-000107350-2', '438707918', '', 'Education Program Specialist II', 'permanent', 'Teaching-Related', 'Human Resource Development Division-NEAP', '0000-00-00', '', 4821294, ''),
(4818693, 'Michelle  ', 'P.', 'Pequeña', '', '0000-00-00', 'Mayao, Oas, Albay', 'Ubaliw', 'Polangui', 'Albay', '4506', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9159780585', 'michelle.pequena@deped.gov.ph', '1.52 m', '60', 'A', '006-0122-3503-2', '1520-0000-5490', '10-000049885-2', '919-685-481', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Quality Assurance Division-', '0000-00-00', '', 0, ''),
(4819118, 'Domilyn  ', 'G.', 'Silerio', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'domilyn.silerio@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 4510336, ''),
(4821242, 'Maria Rosalia Vivien', 'P.', 'Maninang', '', '0000-00-00', 'Naga City', 'Bagumbayan Sur', 'Naga City', 'Camarines Sur', '4400', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9209205041', 'marosaliavivien.maninang@deped.gov.ph', '1.52  m', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821243, 'Marites  ', 'O.', 'Rabulan', '', '0000-00-00', 'Quezon City', 'Sagpon', 'Daraga (Locsin)', 'Albay', '4501', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Widowed', '09952519062', 'marites.rabulan@deped.gov.ph', '1.64', '71 kgs', '#NAME?', '000-5030-6085-1', '1520-0017-4066', '1000003177-6', '136-207-82403', '', 'Nutritionist-Dietitian II', 'permanent', 'Non-Teaching', 'Education Support Services Division-Health and Nutrition', '0000-00-00', '', 4821259, ''),
(4821245, 'Julie Ann ', 'Azores', 'Azores-Mesias', '', '0000-00-00', 'Buyoan, Legazpi City', 'Buyoan', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '9178888052', 'julieann.azores@deped.gov.ph', '1.57 m', '47.5', 'O+', '02004288094', '1210-7284-1697', '10-000107721-4', '430-760-218', '', 'Legal Assistant II', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Legal Unit', '0000-00-00', '', 4819118, ''),
(4821246, 'Pedro  ', 'Salimpadi', 'Bolanos', '', '0000-00-00', 'Bacacay, Albay', 'Bascaran', 'Daraga (Locsin)', 'Albay', '4501', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09289786777', 'pedro.bolanos@deped.gov.ph', '160m', '82kg', 'A+', 'CM-5104390', '1.52E+11', '', '924400648', '', 'Administrative Aide IV', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Proper', '0000-00-00', '', 0, ''),
(4821247, 'Salvador', 'B.', 'Deyto', 'Jr.', '1970-10-29', 'Manila', 'Gubat-Iraya', 'Bacacay', 'Albay', '4509', 'V', 'Gubat-Iraya', 'Bacacay', 'Albay', '4509', 'V', 'Filipino', 'M', 'Married', '9285546398', 'salvador.deyto@deped.gov.ph', '1.67  m', '65', 'O', '0', '0', '0', '0', '', 'Information Technology Officer I', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Information and Communications Technology Unit', '0000-00-00', '', 4510336, ''),
(4821248, 'Basilisio', 'A', 'Lleno', 'Jr. ', '0000-00-00', 'Bascaran, Daraga, Albay', 'Bascaran', 'Daraga (Locsin)', 'Albay', '4501', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '09778379103', 'basilisio.lleno@deped.gov.ph', '1.65', '75', 'O+', '', '', '', '939539769', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', 'Office of the Assistant Regional Director-', '0000-00-00', '', 5007850, ''),
(4821251, 'Rodolfo  ', 'B.', 'Robles', '', '0000-00-00', 'Legazpi City', 'Maoyod', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '0916296772', 'rodolfo.robles@deped.gov.ph', '1.72  m', '67kg', 'B+', '2002270382', '1520-0017-4133', '1E+12', '199-559-675', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Legal Unit', '0000-00-00', '', 4819118, ''),
(4821253, 'Efren  ', 'L.', 'Alcera', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'efren.alcera@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821254, 'Christie  ', 'L.', 'Alvarez', '', '0000-00-00', 'San Pascual, Masbate', 'Del Rosario', 'Canaman', 'Camarines Sur', '4402', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Widowed', '9088738389', 'christie.alvarez@deped.gov.ph', '1.56 m', '86 kg', 'A+', '600-7507-56', '1520-0009-5679', '19-0002794903', '146-999-720', '', 'Education Program Supervisor', 'permanent', 'Teaching', 'Curriculum and Learning Management Division-Proper', '0000-00-00', '', 0, ''),
(4821256, 'Francisco', 'B.', 'Bulalacao', 'Jr.', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'francisco.bulalacao@deped.gov.ph', '', '94 kg', '', '006-0031-9904-8', '1530-0078-4833', '10-0000292669', '119-674-410', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821257, 'Chozara  ', 'P.', 'Duroy', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'chozara.duroy@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821258, 'Minerva  ', 'N.', 'Gayte', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'minerva.gayte@deped.gov.ph', '', '50kg', 'O', 'BP2002-2693-95', '1520-0017-3645', '19-0897235319', '196-212-168', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821259, 'Joan  ', 'L.', 'Lagata', '', '0000-00-00', 'Sorsogon, Sorsogon', 'Abuyog', 'Sorsogon City', 'Sorsogon', '4700', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Separated', '9092294972', 'joan.lagata@deped.gov.ph', '1.65  m', '86 kg', 'A+', '600-7507-56', '1520-0009-5679', '19-0002794903', '146-999-720', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Curriculum and Learning Management Division-Learning Resource Management Section', '0000-00-00', '', 4821265, ''),
(4821260, 'Nora  ', 'J.', 'Laguda', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'nora.laguda@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821261, 'Ma Leilani ', 'R.', 'Lorico', 'N/A', '0000-00-00', 'Legazpi City', 'N/A', 'Legazpi City', 'Albay', '4504', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '09175580007', 'ma.lorico@deped.gov.ph', '1.57 meters', '52 kg', '0', 'B72XSLFR014', '1520-0021-7103', '10-000048007-4', '183-262-476', '', 'Education Program Supervisor', 'permanent', 'Teaching', 'Curriculum and Learning Management Division-Proper', '0000-00-00', '', 0, ''),
(4821265, 'Grace', 'U.', 'Rabelas', '', '0000-00-00', 'Iriga City', 'Francia', 'Iriga City', 'Camarines Sur', '4431', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09175084549', 'grace.rabelas@deped.gov.ph', '0.127', '60 kg', 'B+', '006-0032-5476-3', '1530-0083-1770', 'N/A', '166-436-921', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Curriculum and Learning Management Division-Learning Resource Management Section', '0000-00-00', '', 4821265, ''),
(4821267, 'Ricardo  ', 'M.', 'Tejeresas', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'ricardo.tejeresas@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821268, 'Marilou  ', 'V.', 'Tobongbanua', '', '0000-00-00', 'Sogod, Bacacay, Albay', 'Sogod', 'Bacacay', 'Albay', '4509', 'V', '', '', '', '', '', 'Filipino', 'F', 'Widowed', '9219784000', 'marilou.tobongbanua@deped.gov.ph', '1.49  m', '86 kg', 'A+', '600-7507-56', '1520-0009-5679', '19-0002794903', '146-999-720', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821269, 'Ma Corazon ', 'Adille', 'Aler', 'NA', '0000-00-00', 'Sagpon, Albay, Legazpi City', 'Ems Barrio', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Widowed', '9465845999', 'ma.aler@deped.gov.ph', '1.68  m', '65 kg', 'O+', '600-7507-56', '1530-0078-4155', '10-000056292-5', '101-202-351', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Human Resource Development Division-', '0000-00-00', '', 4821294, ''),
(4821270, 'Ronald  ', 'C.', 'Asis', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'ronald.asis@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821273, 'Daisy  ', 'D.', 'Moratalla', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'daisy.moratalla@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821274, 'Deo  ', 'R.', 'Moreno', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'deo.moreno@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821275, 'Arnulfo  ', 'N.', 'Naag', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'arnulfo.naag@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821276, 'Angelica  ', 'A.', 'Moral', 'N/A', '1976-01-18', 'Sagpon, Albay, Legazpi City', 'Brgy. 4, Sagpon', 'Legazpi City', 'Albay', '4500', 'V', 'Brgy. 4, Sagpon', ' ', 'Albay', '4500', 'V', 'Filipino', 'F', 'Single', '09199990658', 'angelica.moral@deped.gov.ph', '1.53', '49 kg', 'O', 'B76BJAAM018', '05-0123095906', '19-089723533-5', '0920 471 488', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', 'Field Technical Assistance Division-', '2002-09-02', '', 4821278, ''),
(4821277, 'Evangeline  ', 'A.', 'Saculo', '', '0000-00-00', 'NAbua Camarines Sur', 'San Juan', 'Iriga City', 'Camarines Sur', '4431', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '09662068937', 'evangeline.saculo@deped.gov.ph', '1.3', '52', '0', '00', '00', '00', '00', '', 'Chief Education Supervisor', 'permanent', 'Teaching', 'Field Technical Assistance Division-', '0000-00-00', '', 4821278, ''),
(4821278, 'Casiano ', 'B.', 'Perdigones', 'Jr.', '0000-00-00', 'Pandan, Ligao City', 'Sta, Cruz', 'Ligao', 'Albay', '4504', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09688800391', 'casiano.perdigones@deped.gov.ph', '1.75', '80', '', '2002332122', '15000207300', '', '183871549', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', '', '0000-00-00', '', 0, ''),
(4821279, 'Jinky  ', 'A.', 'Villareal', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'jinky.villareal@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821280, 'Francia  ', 'D.', 'Borromeo', '', '0000-00-00', 'Tagoytoy, Malinao, Albay', 'Matagac', 'Tabaco', 'Albay', '4511', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '9669159413', 'francia.borromeo@deped.gov.ph', '1.7 m', '86 kg', 'A+', '600-7507-56', '1520-0009-5679', '19-0002794903', '146-999-720', '', 'Administrative Aide II', 'permanent', 'Non-Teaching', 'Quality Assurance Division-', '0000-00-00', '', 0, ''),
(4821283, 'Maria Asuncion ', 'A.', 'Longoria', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'mariaasuncion.longoria@deped.gov.ph', '', '58 kg', 'A', '63-0815-0227-9', '1520-0017-4309', '10-00003205-5', '132-735-512', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821284, 'Jocelyn  ', 'O.', 'Dy', '', '0000-00-00', 'Guinobatan, Albay', 'Bololo', 'Guinobatan', 'Albay', '4503', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '09175595160', 'jocelyn.dy@deped.gov.ph', '6.1', '61 kg', ' A+', '006-0076-8557-4', 'N/A', '10-0000508300', '153-027-423', '', 'Chief Education Supervisor', 'permanent', 'Teaching-Related', 'Quality Assurance Division-', '0000-00-00', '', 0, ''),
(4821290, 'Sancha  ', 'M.', 'Nacion', '', '1968-10-25', 'Daraga, Albay', '17 (Ilawod)', 'Legazpi City', 'Albay', '4500', 'V', '17', 'Legazpi City', 'Albay', '4500', 'V', 'Filipino', 'M', 'Married', '09495989454', 'sancha.nacion@deped.gov.ph', '1.62', ' 64  kg', 'O', '2002271614', '1520-0017-8027		 		', '10-0000187873', '175-451-610', '', 'Chief Education Supervisor', 'permanent', 'Teaching-Related', 'Human Resource Development Division-', '1991-12-06', '', 4821294, ''),
(4821291, 'Priscilla  ', 'J.', 'Ombao', '', '0000-00-00', 'Donsol, Sorsogon', 'Lapu-Lapu St.', 'Legazpi City', 'Albay', '4000', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Widowed', '9936870126', 'priscilla.ombao@deped.gov.ph', '1.64  m', '80kg', ' O ', '006-0052-6914-1', '1520-0014-0003', '10-00005219-4', '147-0031-678', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Quality Assurance Division-', '0000-00-00', '', 0, ''),
(4821292, 'Alaster  ', 'O.', 'Palacio', '', '0000-00-00', 'MALIPO GUINOBATAN ALBAY', 'Malipo', 'Guinobatan', 'Albay', '4503', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09633994081', 'alaster.palacio@deped.gov.ph', '163CM', '60 kg', 'B+', '2-0048-0781-0', '1211-5060-8669', '10-200945319-6', '948-644-012-000', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', '', '0000-00-00', '', 0, ''),
(4821293, 'Shannon', 'D.', 'Abogado', '', '1989-09-25', 'Pinamalayan, Oriental Mindoro', 'San Miguel', 'Magarao', 'Camarines Sur', '4403', 'V', 'San Miguel', 'Magarao', 'Camarines Sur', '4403', 'V', 'Filipino', 'F', 'Married', '9084834175', 'shannon.abogado@deped.gov.ph', '1.55  m', '45 kg', 'A+', '2-0049-6676-4', '1211-3630-1920', 'N/A', '446-387-153', '', 'Engineer III', 'permanent', 'Non-Teaching', 'Education Support Services Division-Facilities', '0000-00-00', '', 0, ''),
(4821294, 'Roy  ', 'T.', 'Bañas', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'roy.banas@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821295, 'Jasminena  ', 'H.', 'Bonito', '', '0000-00-00', 'Castilla, Sorsogon', 'Cruzada', 'Legazpi', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9209786899', 'jasminena.bonito@deped.gov.ph', '1.56  m', '86 kg', 'A+', '600-7507-56', '1520-0009-5679', '19-0002794903', '146-999-720', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821298, 'Rodel  ', 'Hernandez', 'Lim', '', '0000-00-00', 'Bulan, Sorsogon', 'Brgy. 37', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9354018236', 'rodel.lim@deped.gov.ph', '1.57  m', '86 kg', 'A+', '600-7507-56', '1520-0009-5679', '19-0002794903', '146-999-720', '', 'Administrative Assistant VI', 'permanent', 'Non-Teaching', 'Policy Planning and Research Division-', '0000-00-00', '', 0, ''),
(4821299, 'Jocelyn  ', 'Chua', 'Villanueva', '', '0000-00-00', 'Daraga, Albay', 'Pawa', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '9175367996', 'jocelyn.villanueva@deped.gov.ph', '1.54 m', '58 kg', 'AB+', '2002271215', '1520-0017-3556', '10-000003114-8', '196-213-579', '', 'Statistician I', 'permanent', 'Non-Teaching', 'Policy Planning and Research Division-', '0000-00-00', '', 0, ''),
(4821301, 'Gregor', 'O.', 'Abuid', '', '0000-00-00', NULL, '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'gregor.abuid@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821302, 'Roey Jose ', 'Clutario', 'Alferez', '', '0000-00-00', 'Garchitorena, Camarines Sur ', 'Poblacion ', 'Garchitorena', 'Camarines Sur', '4428', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09778302469', 'roey.alferez@deped.gov.ph', '1.68 meters', '77 kg', 'O', '006-0072-1945-8', '1530-0085-6569', '19-000755379-3', '136-095-650', '', 'Chief Administrative Officer', 'permanent', 'Non-Teaching', 'Administrative Division-Proper', '0000-00-00', '', 0, ''),
(4821305, 'Rowena  ', 'L.', 'Bacea', '', '0000-00-00', 'Daraga, Albay', 'Purok 6,Brgy.3', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '09454017204', 'rowena.bacea@deped.gov.ph', '5\'0', '65', 'O', '2002271584', '', '', '', '', 'Administrative Officer IV', 'permanent', 'Non-Teaching', 'Administrative Division-General Services Unit', '0000-00-00', '', 4821305, ''),
(4821306, 'Therese  ', 'C.', 'Bañadera', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'therese.banadera@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821307, 'Mary Ann ', 'Tablante', 'Bañas', '', '0000-00-00', 'Legazpi City', 'Gogon', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '09171401079', 'mary.banas@deped.gov.ph', '1.52', '73', 'A', '2002269358', '1520-00174211', '19-089723538-6', '929-603-967', '', 'Administrative Officer V', 'permanent', 'Non-Teaching', 'Administrative Division-Personnel Section', '0000-00-00', '', 4821284, ''),
(4821312, 'Pandora  ', 'Nogot', 'Cielo', '', '0000-00-00', 'Legazpi City', 'Oro Site', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9056921599', 'pandora.cielo@deped.gov.ph', '1.52  m', '47', 'B', '2000009000', '1250-0017-3568', '1E+11', '901-664-913', '', 'Administrative Assistant V', 'permanent', 'Non-Teaching', 'Finance Division-Accounting Section', '0000-00-00', '', 4821346, ''),
(4821313, 'Irma  ', 'B.', 'Enaje', '', '1968-06-27', 'Albay', 'Zone 5 ', 'Pandan', 'Albay', '4501', 'V', 'Pandan', 'Legazpi City', 'Albay', '4501', 'V', 'Filipino', 'F', 'Married', '09063536848', 'irma.enaje@deped.gov.ph', '5:04', '62 kg', 'B', '006-0025-1105-4', '1520-0017-3612', '10-0000031237', '196-215-848', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Payroll Services Unit', '0000-00-00', '', 4217547, ''),
(4821316, 'Ricardo  ', 'N.', 'Llantero', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'ricardo.llantero@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821317, 'Salvador  ', 'M.', 'Lopera', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'salvador.lopera@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821320, 'Jose Nonato ', 'Casimiro', 'Nepomuceno', '', '0000-00-00', 'Oas, Albay', 'Stanza', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09300555447', 'jose.nepomuceno@deped.gov.ph', '5\'8\"', '80', 'O+', '', '1.2101E+11', '10000056214-3', '453881663', '', 'Administrative Aide III', 'permanent', 'Teaching', 'Administrative Division-General Services Unit', '0000-00-00', '', 4821305, ''),
(4821323, 'Aldrin  ', 'Rogando', 'Rellama', '', '0000-00-00', 'Oas, Albay', 'Rawis', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09171783448', 'aldrin.rellama@deped.gov.ph', '1.6764', '80 kg', 'A', '000-5034-4739-3', '1520-0017-4109', '10-0000031814', '194-091-541', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Asset Management Section', '0000-00-00', '', 4217547, ''),
(4821326, 'Ma Elena ', 'A.', 'Torrentira', '', '0000-00-00', 'Legazpi City', 'Barangay 8', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09497116566', 'ma.torrentira@deped.gov.ph', '1.66  m', '55', 'A', '', '', '10-0000-66913-4', '182-746-766', '', 'Administrative Assistant III', 'permanent', 'Non-Teaching', 'Administrative Division-General Services Unit', '0000-00-00', '', 4821305, ''),
(4821328, 'Ernie  ', 'Malagueño', 'Caño', '', '0000-00-00', 'Tagoytoy, Malinao, Albay', 'Tagoytoy', 'Malinao', 'Albay', '4512', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09668032912', 'ernie.cano@deped.gov.ph', '1.67 meters', '60 kg', 'B', '021-1523-3389-9', '1520-0017-4690', '19-089723528-9', '927-189-308', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', 'Administrative Division-Proper', '0000-00-00', '', 0, ''),
(4821329, 'Ruth  ', 'B.', 'Bendita', '', '0000-00-00', 'Balatan, Camarines Sur', 'Brgy. 18 Cabangan', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9458074132', 'ruth.bendita@deped.gov.ph   ', '16.15  m', '56 kg', 'A+', '2004-8078-04', '1211-5194-7230', '10-2016000000', '446-459-972', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Personnel Section', '0000-00-00', '', 4821284, ''),
(4821330, 'Aily  ', 'A.', 'Alcera', '', '0000-00-00', 'LEGAZPI CITY', 'BARANGAY 56-TAYSAN LEG CITY', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '09456019447', 'aily.alcera@deped.gov.ph', '164', '70', 'A+', '0', '0', '0', '0', '', 'Administrative Officer V', 'permanent', 'Non-Teaching', 'Administrative Division-Cash Section', '0000-00-00', '', 4821330, ''),
(4821332, 'Ma. Theresa ', 'D.', 'Astor', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'matheresa.astor@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821335, 'Joy  ', 'B.', 'Margallo', '', '0000-00-00', 'Tabaco City', 'Igang', 'Bacacay', 'Albay', '4509', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '9988645987', 'joy.barrameda@deped.gov.ph', '1.63 m', '60 kg', 'A', '2004807803', '1210-7033-3662', '02-050961123-9', '429-218-262-000', '', 'Accountant III', 'permanent', 'Non-Teaching', 'Finance Division-Accounting Section', '0000-00-00', '', 4821346, ''),
(4821336, 'Sonia  ', 'A.', 'Bandola', '', '0000-00-00', 'DARAGA, ALBAY', '#976 LOT 10 BLK 21, FIRST STREET, OUR LADYS VILLAGE, BITANO', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '09173190589', 'sonia.bandola@deped.gov.ph', '5 FT.', '49 KLS.', 'AB', '2002271645', '1520-0017-3391', '10-000003097-4', '132-734-595', '', 'Administrative Officer V', 'permanent', 'Non-Teaching', 'Finance Division-Budget Section', '0000-00-00', '', 4821346, ''),
(4821337, 'Agnes  ', 'M.', 'Colasito', '', '0000-00-00', 'Pilar, Sorsogon', 'Cruzada', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '9450994376', 'agnes.colasito@deped.gov.ph', '1.524  m', '55 kg', 'O', '2002270655', '1520-0017-3580', '10-000003116-4', '171-165-156', '', 'Administrative Assistant V', 'permanent', 'Non-Teaching', 'Administrative Division-Payroll Services Unit', '0000-00-00', '', 4217547, ''),
(4821338, 'Leah Belle ', 'B.', 'De Padua', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'leahbelle.depadua@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821339, 'Rosary Ann ', 'Alpajaro', 'Gimenez', '', '0000-00-00', 'Legazpi City', 'Pinaric', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '9052950796', 'rosary.gimenez@deped.gov.ph', '1.63  m', '61.5', 'B+', '200', '1570', '10000', '132734248', '', 'Teacher Credentials Evaluator II', 'permanent', 'Teaching-Related', 'Administrative Division-Personnel Section', '0000-00-00', '', 4821284, ''),
(4821343, 'Freddirico  ', 'T.', 'Obo', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'freddirico.obo@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821344, 'Bernadette  ', 'Mortega', 'Robles', '', '0000-00-00', 'Magallanes, Sorsogon', 'Maoyod', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '9177093311', 'bernadette.robles002@deped.gov.ph', '1.548 m', '64kg', 'O', '2002-2707-77', '1520-0017-4144', '10-0000031857', '132-735-504', '', 'Administrative Officer V', 'permanent', 'Non-Teaching', 'Administrative Division-Records Section', '0000-00-00', '', 4821344, ''),
(4821345, 'Ilya  ', 'Oyardo', 'Vargas', '', '1981-07-31', 'Guinobatan, Albay', 'San Francisco', 'Guinobatan', 'Albay', '4503', 'V', 'San Francisco', 'Guinobatan', 'Albay', '4503', 'V', 'Filipino', 'F', 'Single', '09189471972', 'ilya.vargas@deped.gov.ph', '1.52', '60', 'B+', '', '1520-0017-4689', '10-050048852-7', '937-255-016', '', 'Administrative Officer IV', 'permanent', 'Non-Teaching', 'Finance Division-Budget Section', '2005-07-01', '', 4821346, ''),
(4821346, 'Rose Ann ', 'B.', 'Tubig', '', '0000-00-00', 'Legazpi City', 'San Roque', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9165157632', 'roseann.tubig@deped.gov.ph', '1.67  m', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821347, 'Zer Jethro Rodmell', 'A.', 'Roscuata', '', '0000-00-00', 'Legazpi City', 'Gogon', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9177065437', 'zerjethrorodmell.roscuata@deped.gov.ph', '1.676 m', '90', 'O+', '2004765972', '1.21109E+11', '1.00502E+11', '453910374', '', 'Accountant II', 'permanent', 'Non-Teaching', 'Finance Division-Accounting Section', '0000-00-00', '', 4821346, ''),
(4821350, 'Catalina  ', 'Posas', 'Garcia', '', '1966-01-08', 'Obrero, Bulan, Sorsogon', 'Sagpon', 'Legazpi City', 'Albay', '4500', 'V', 'Obrero', ' ', 'Sorsogon', '4706', 'V', 'Filipino', 'F', 'Single', '09206192628', 'catalina.garcia@deped.gov.ph ', '1.49', '57 kg', 'B+', '66-0108-0002-2', '0501-0457-1408', '10-0000-49584-5', '147-707-790', '', 'Education Program Supervisor', 'permanent', 'Teaching', 'Human Resource Development Division-', '1987-01-27', '', 4821294, ''),
(4821351, 'Mia Rhea ', 'Ang', 'Fuentebella', '', '0000-00-00', 'Virac, Catanduanes', 'Cavinitan', 'Virac', 'Catanduanes', '4800', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9282051794', 'miarhea.fuentebella@deped.gov.ph', '170m', '75kg', 'A+', '2004634192', '1.21162E+11', '1.00501E+11', '948419200', '', 'Administrative Assistant III', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Proper', '0000-00-00', '', 0, ''),
(4821365, 'Maria Cristina ', 'G.', 'Baroso', '', '0000-00-00', 'Irosin, Sorsogon', 'Concepcion Grande', 'Naga City ', 'Camarines Sur', '4400', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9478000000', 'mariacristina.baroso@deped.gov.ph', '1.5  m', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821367, 'Sherwin  ', 'B.', 'Torres', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'sherwin.torres001@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(4821368, 'Janela  ', 'Lovendino', 'Losito', '', '1992-10-06', 'Abucay, Pilar, Sorsogon', 'Rawis', 'Legazpi City', 'Albay', '4500', 'V', 'Abucay', ' ', 'Sorsogon', '4714', 'V', 'Filipino', 'F', 'Single', '09484505657', 'janela.losito@deped.gov.ph', '1.45  m', '60', 'B', '2005095050', '121186551575', '102524273257', '455-834-039', '', 'Administrative Officer II', 'permanent', 'Non-Teaching', 'Finance Division-Budget Section', '2016-11-10', '', 4821346, ''),
(5007850, 'Gilbert  ', 'T.', 'Sadsad', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'gilbert.sadsad@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(5019300, 'Manuel  ', 'F.', 'Babasa', '', '0000-00-00', 'Sorsogon', 'Almendras - Cogon', 'Sorsogon City', 'Sorsogon', '4700', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '9092168110', 'manuel.babasa@deped.gov.ph', ' 1.69  m', '78', '\"O\"', '2002952988', '00000000', '000000', '166-446-972', '', 'Education Program Supervisor', 'permanent', 'Teaching-Related', 'Quality Assurance Division-', '0000-00-00', '', 0, ''),
(5026390, 'Sheila  ', 'C.', 'Bulawan', '', '0000-00-00', 'Bulan, Sorsogon', 'Buraguis', 'Legazpi City,', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9954485573', 'sheila.bulawan@deped.gov.ph', '1.6  m', '70 kg', 'B', '2-0023-9554-5', '1520-0004-0749', '10-000054044-1', '183-260-440', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(5029900, 'Andrew  ', 'P.', 'Raguero', '', '1973-07-19', 'Legazpi City', '#40, Cruzada', 'Legazpi City', 'Albay', '4500', 'V', '#40, Cruzada', 'Legazpi City', 'Albay', '4500', '5', 'Filipino', 'M', 'Married', '9284696462', 'andrew.raguero@deped.gov.ph', '1.7  m', '73', 'B', '0', '0', '0', '159-207-622', '', 'Education Program Specialist II', 'permanent', 'Non-Teaching', 'Policy Planning and Research Division-', '2017-08-30', '', 0, ''),
(5052280, 'Paraluman  ', 'M.', 'Torregoza', 'NA', '0000-00-00', 'Manila', 'Brgy.16 East Washington Drive', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '9175482277', 'paraluman.torregoza@deped.gov.ph', '1.5 m', '65 kg', ' ', '45926', '050120144103', '10-000-59112-7', '184-124-598', '', 'Senior Education Program Specialist', 'permanent', 'Teaching-Related', 'Human Resource Development Division-NEAP', '0000-00-00', '', 4821294, ''),
(5812149, 'Jeffrey  ', 'B.', 'Pagatpat', '', '0000-00-00', 'Antipolo Palanas Masbate', 'Liong', 'Cataingan', 'Masbate', '5406', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '9778023554', 'jeffrey.pagatpat@deped.gov.ph', '1.7  m', '85', '0', '5157 1214 40', '9.14151E+11', '010256257681', '429-928-906', '', 'Administrative Officer IV', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Procurement Unit', '0000-00-00', '', 4510336, ''),
(5818663, 'Karen  ', 'Soriano', 'Legson', '', '1996-02-06', 'Camalig, Albay', 'Barangay', 'Daraga (Locsin)', 'Albay', '4501', 'V', 'Barangay', 'Daraga (Locsin)', 'Albay', '4501', 'V', 'Filipino', 'F', 'Single', '09123456789', 'karen.legson@deped.gov.ph', '1.524 m', '55kg', 'B+', '200-5001-853', '1211-7777-8020', '10-2500873415', '488-033-261', '', 'Computer Programmer II', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Information and Communications Technology Unit', '2016-07-18', '', 4821247, ''),
(5826994, 'Cathy Rose ', 'M.', 'Serrano', 'Serrano', '0000-00-00', 'Legazpi City', 'Rawis', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Single', '09665331512', 'cathyrose.serrano@deped.gov.ph', '1.58  m', '55 kg', 'B', '200-5907-476', '1212-3810-0488', '10-0253436497', '051-480-861-7', '', 'Accountant I', 'permanent', 'Non-Teaching', 'Finance Division-Accounting Section', '0000-00-00', '', 4821346, ''),
(6313713, 'Mayflor Marie ', 'L.', 'Jumamil', '', '0000-00-00', 'Sorsogon City', 'Bibincahan', 'Sorsogon City', 'Sorsogon', '4700', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Single', '0925800190', 'mayflormarie.jumamil@deped.gov.ph', '5\"3\'', '53', 'A+', '006-0113-1895-0', '1520-0032-0815', '1000-0073-9752', '190 808 862', '', 'Administrative Officer V', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Public Affairs Unit', '0000-00-00', '', 4510336, ''),
(6313714, 'Mark Kevin ', 'A.', 'Arroco', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'markkevin.arroco@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(6313753, 'Rodel', 'A.', 'Arena', '', '0000-00-00', 'Legazpi City', 'Libod', 'Camalig', 'Albay', '4502', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09300333648', 'rodel.arena@deped.gov.ph', '5\'4', '74', 'B+', '', '1.21193E+11', '', '930044556', '', 'Administrative Aide IV', 'permanent', 'Non-Teaching', 'Administrative Division-General Services Unit', '0000-00-00', '', 4821305, ''),
(6323343, 'Luisa Fe ', 'L.', 'Montas', '', '0000-00-00', 'Daet, Camarines Norte', 'Sagpon', 'Daraga (Locsin)', 'Albay', '4501', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Married', '9175440522', 'luisafe.montas@deped.gov.ph', '1.6  m', '', '', '', '', '', '', '', 'Special Investigator III', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Legal Unit', '0000-00-00', '', 4819118, ''),
(6324399, 'Bea Anne ', 'P.', 'Baroma', '', '1996-07-13', 'Matacla, Goa, Camarines Sur', 'Camarines Sur', 'Goa', 'Camarines Sur', '4422', 'V', 'Camarines Sur', 'Goa', 'Camarines Sur', '4422', 'V', 'Filipino', 'F', 'Married', '9688871396', 'beaanne.baroma@deped.gov.ph', '1.6  m', '', 'A+', '', '', '', '', '', 'Attorney III', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Legal Unit', '2023-07-13', '', 4819118, ''),
(6429552, 'Ma. Ana Mae', 'Barra', 'Bernardino', '', '0000-00-00', 'Bato, Camarines Sur', 'Agos', 'Bato', 'Camarines Sur', '4435', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Single', '9568666042', 'maanamae.bernardino@deped.gov.ph', '1.52  m', '43 kg', 'B+', '2005728844', '1.21102E+11', '0', '0', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Payroll Services Unit', '0000-00-00', '', 4217547, ''),
(6429932, 'Sheeva Marie ', 'Manlangit', 'Porciuncula', 'N/A', '1993-12-31', 'Legazpi City, Albay', '361 Pag-asa, Rawis', 'Legazpi City', 'Albay', '4500', 'V', '361 Pag-asa, Rawis', 'Legazpi City', 'Albay', '4500', 'V', 'Filipino', 'F', 'Single', '09162945865', 'sheevamarie.porciuncula@deped.gov.ph', '1.55', '48 kg', 'O+', '2-0057-6034-2', '1212-6967-1887', '10-000120086-5', '707-824-150', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Personnel Section', '2020-03-02', '', 4821284, ''),
(6430260, 'KRISTINE', 'B.', 'EBUENGA', '', '0000-00-00', 'Bogtong, Rapu-Rapu Albay', 'Dap-Dap', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09070237016', 'kristine.ebuenga@deped.gov.ph', '1.63', '62', 'O+', '2005805636', '', '1.00502E+11', '', '', 'Administrative Assistant III', 'permanent', 'Teaching-Related', 'Human Resource Development Division-NEAP', '0000-00-00', '', 4821294, ''),
(6430453, 'Cheenee  ', 'O.', 'Mendina', '', '0000-00-00', 'Sto.Domingo, Albay', 'Nagsiya', 'Sto. Domingo', 'Albay', '4508', 'V', '', '', '', '', '', 'Filipino', 'Female', 'Single', '9279989357', 'cheenee.mendina@deped.gov.ph', '1.50 m', '65', 'B+', '2005852493', '1212-3802-1468	', '10-250485764-3	', '357-071-443-000', '', 'Accountant I', 'permanent', 'Non-Teaching', 'Finance Division-Accounting Section', '0000-00-00', '', 4821346, ''),
(6430454, 'Leslyn  ', 'Orcine', 'Tubongbanua', '', '0000-00-00', 'Iriga City', 'Sta. Cruz Sur', 'Iriga City', 'Camarines Sur', '4431', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '09915618394', 'leslyn.orcine@deped.gov.ph', '1.6', '50', 'A+', '20-058454-75', '1212-7744-0107', '1.00001E+11', '732-949-292-000', '', 'Administrative Assistant I', 'permanent', 'Non-Teaching', 'Policy Planning and Research Division-', '0000-00-00', '', 0, ''),
(6430927, 'Mischel  ', 'N.', 'Ludovice', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'F', '', '', 'mischel.ludovice@deped.gov.ph', '', '47 kg', 'O+', '2005-916-843', '1212-3543-7135', '10-0000000000', '734-242-463-000', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(6431010, 'Allan Julian ', 'T.', 'Sapaula', '', '0000-00-00', 'Polangui, Albay', 'Lacag', 'Daraga (Locsin)', 'Albay', '4501', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Married', '9950556331', 'allanjulian.sapaula@deped.gov.ph', '1.6  m', '57', 'O', '2005512648', '1.21087E+11', '01-051200686-3', '260-564-302', '', 'Administrative Officer II', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Procurement Unit', '0000-00-00', '', 5812149, ''),
(6431458, 'Christilyn  ', 'P.', 'Ocbian', '', '0000-00-00', 'Maipon Guinobatan Albay', 'Maninila', 'Guinobatan', 'Albay', '4503', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9667122666', 'christilyn.ocbian@deped.gov.ph', '1.56 m', '55', 'O+', '2005986557', '1.21229E+11', '1.02025E+11', '741507441', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Payroll Services Unit', '0000-00-00', '', 4217547, ''),
(6432282, 'Raquel  ', 'Alpajaro', 'Supnet', '', '0000-00-00', 'Legazpi City', 'Barangay 9 -Pinaric', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'F', 'Married', '9668703496', 'raquel.supnet@deped.gov.ph', '1.57 m', '5', 'A+', '2004982130', '1.21151E+11', '1.00251E+11', '489013304', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Payroll Services Unit', '0000-00-00', '', 4217547, ''),
(6432283, 'Christian Gregory ', 'A.', 'Bandola', '', '0000-00-00', '', '', '', '', '', 'V', '', '', '', '', '', 'Filipino', 'M', '', '', 'christian.bandola@deped.gov.ph', '', '', '', '', '', '', '', '', '', 'permanent', '', '', '0000-00-00', '', 0, ''),
(6432515, 'Marvin  ', 'B.', 'Buhat', '', '1993-10-11', 'San Pedro, Laguna', 'Brgy 8, Bagumbayan', 'Legazpi City', 'Albay', '4500', 'V', 'Brgy 8, Bagumbayan', 'Legazpi City', 'Albay', '4500', 'V', 'Filipino', 'M', 'Married', '9503038908', 'marvin.buhat@deped.gov.ph', '1.75 m', '', 'O+', '2006149047', '1211-2020-6392', '0802-5829-6382', '470-200-630-0000', '', 'Computer Maintenance Technologist I', 'permanent', 'Non-Teaching', 'Office of the Regional Director-Information and Communications Technology Unit', '2022-10-05', '', 4821247, ''),
(6433401, 'Remerlyn  ', 'BARADO', 'Latigay', '', '0000-00-00', 'LEGAZPI CITY', 'BARANGAY 24, RIZAL STREET', 'Legazpi City', 'Albay', '4500', 'V', '', '', '', '', '', 'Filipino', 'Male', 'Single', '09569516417', 'remerlyn.latigay@deped.gov.ph', '164', '52 kg', 'A+', '2006300565', '1211-7874-4863', '10-0252125757', '493-532-445', '', 'Administrative Aide VI', 'permanent', 'Non-Teaching', 'Administrative Division-Cash Section', '0000-00-00', '', 4821330, ''),
(6491655, 'Veronica', '', 'Aguilar', '', '0000-00-00', '', '', 'Aroroy', 'Albay', '', '', '', '', '', '', '', '', 'Male', 'Single', '', 'veronica.aguilar002@deped.gov.ph', '', '', '', '', '', '', '', '', 'Accountant I', ' ', 'Teaching', '', '0000-00-00', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `family`
--

CREATE TABLE `family` (
  `familyID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `spouse_Firstname` text DEFAULT NULL,
  `spouse_Middlename` text DEFAULT NULL,
  `spouse_Surname` text DEFAULT NULL,
  `spouse_Extname` text DEFAULT NULL,
  `spouse_occupation` text NOT NULL,
  `employer_business` text NOT NULL,
  `employer_business_address` text NOT NULL,
  `spouse_contact` text NOT NULL,
  `father_Firstname` text NOT NULL,
  `father_Middlename` text NOT NULL,
  `father_Lastname` text NOT NULL,
  `father_Extname` text NOT NULL,
  `mother_Firstname` text NOT NULL,
  `mother_Middlename` text NOT NULL,
  `mother_Lastname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `family`
--

INSERT INTO `family` (`familyID`, `employeeID`, `spouse_Firstname`, `spouse_Middlename`, `spouse_Surname`, `spouse_Extname`, `spouse_occupation`, `employer_business`, `employer_business_address`, `spouse_contact`, `father_Firstname`, `father_Middlename`, `father_Lastname`, `father_Extname`, `mother_Firstname`, `mother_Middlename`, `mother_Lastname`) VALUES
(5, 11, '', '', '', '', '', '', '', '', 'wdw', 'ede', 'mkk', '', 'ededd', 'ded', 'ded'),
(6, 6432515, 'Eadian Sigrid', 'Barrun', 'Batac-Buhat', '', 'N/A', 'N/A', '', '', 'Antonio', 'Aragon', 'Buhat', '', 'Meriam', 'Alemania', 'Bitancur'),
(7, 4722653, 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', '', '', 'JOEL', 'BUENVENIDA', 'ATAD', '', 'ELSIE', 'ARROYO', 'ATAD'),
(8, 4821329, 'NA', 'NA', 'NA', '', 'NA', 'NA', '', '', 'MENARDO', 'FRANCISCO', 'BENDITA', '', 'NIMFA', 'BERMAS', 'BERCES'),
(9, 4590076, '', '', 'N/A', '', 'DEPED RO V- EDUCATION PROGRAM SUPERVISOR', 'DEPED REGIONAL OFFICE V (BICOL)', '', '', 'Nelson, Sr.', 'Perias', 'Chavez', '', 'Marietta', 'Cañeba', 'Chavez'),
(10, 4821350, 'n/a', 'n/a', 'n/a', '', 'n/a', 'Department of Education', '', '', 'Valeriano', 'Rayela', 'Garcia', '', 'Encarnacion', 'Posas', 'Garcia'),
(11, 4821290, 'Glen', 'Abejuela', 'Nacion', 'N/A', 'Retired Public Schools District Supervisor / Self-employed', 'N/A', '6307 Rizal Street, Barangay 17, Legazpi City', '09205876858', 'Simon', 'Lozano', 'Mediavillo', 'N/A', 'Salome', 'Cadag', 'Garcia'),
(12, 4821368, '', '', '', '', '', '', '', '', 'Ronald', 'Medollar', 'Losito', '', 'Leilanie', 'Lovendino', 'Losito'),
(13, 4821245, 'Joe Mari', '', 'Mesias', '', 'Data Analyst/ Level 12 - Engineering & Networks Associate / Network & Services Operation Associate', 'Accenture Inc.', '', '', 'Amado', 'Arimbay', 'Azores', '', 'Julia', 'Agarin', 'Azores'),
(14, 4821335, 'Franklin', 'Buemia', 'Margallo', '', 'Public Teacher', 'Department of Education', '', '', 'Sany', 'Camasis', 'Barrameda', '', 'Flordeliza', 'Clavo', 'Bon'),
(15, 4214395, '', '', 'NONE', '', 'NONE', 'Department of Education-Regional office', '', '', 'TELIMACO', 'NAPAY', 'NAVERA', '', 'VILMA', 'SANOSA', 'NARON'),
(16, 6430454, 'Philip Joshua', 'Escobal', 'Tubongbanua ', '', 'Government Employee', 'Phillippine Coast Guard', '', '', 'n/a', 'n/a', 'n/a', '', 'Norilyn', 'Novero', 'Orcine'),
(17, 6433401, 'NONE', 'NONE', 'NONE', '', 'NONE', 'NONE', '', '', 'RAMER', 'ALAMA', 'LATIGAY', '', 'EUFEMIA', 'ANTUERPIA', 'BARADO'),
(18, 4540747, 'Jose Rey ', 'Lopez', 'Monreal', '', 'Division Chief, Environmental Management Specialist', 'DENR-EMB V', '', '', 'Aristeo', 'Mirandilla', 'Ragragio', '', 'Violeta', 'Londono', 'Misolas'),
(19, 6429552, '', '', '', '', '', '', '', '', 'Eulogio', 'Alcovendaz', 'Bernardino', '', 'Leonor', 'De Leon', 'Barra'),
(20, 4821307, 'Roy', 'Torero', 'Banas', '', 'Government Employee', 'Department of Education Regional Office No. V', '', '', 'Mario ', 'Cruz', 'Tablante', '', 'Emelita', 'Bachiller', 'Mabelin'),
(21, 6430260, 'RYAN', 'GARAY', 'EBUENGA', '', 'SEAMAN', 'MSC', '', '', 'RODOLFO, SR.', 'ARAOJO', 'BALDRES', '', 'CARMEN', 'PAGOROGON', 'DIAMANTE'),
(22, 4821298, '', '', '', '', '', '', '', '', 'RAYMUNDO', 'ORTEZA', 'LIM', '', 'DELIA ', 'SENDON', 'HERNANDEZ'),
(23, 4821277, 'Ramon', 'Veras', 'Saculo', '', 'Nurse (Retired OFW)', 'N/A', '', '', 'Maximo', 'Sano', 'Alcantara', '', 'Estelita', 'Obrero', 'Gabarda'),
(24, 4821347, '', '', 'None', '', '', '', '', '', 'Unknown', 'Unknown', 'Unknown', '', 'Ma. Pamela', 'Adolfo', 'Roscuata'),
(25, 4821291, 'Catalino Jr.', 'Rabulan', 'Ombao', '', 'Government Employee (Deceased)', 'DepEd', '', '', 'Cerienico', 'Listana', 'Jadie', '', 'Espectacion', 'Herrera', 'Pasague'),
(26, 4821280, 'Benjamin', 'Serrano', 'Borromeo, Jr', '', 'Governmenr Employee', 'DepEd Regional Office V', '', '', 'Julito, Sr', 'Uy', 'Diaz', '', 'Salome', 'Bobier', 'Casais'),
(27, 4821276, '', '', 'N/A', '', '', '', '', '', 'ANDREW', 'NUNEZCA', 'MORAL', '', 'JANE', 'AREVALO', 'ARIOLA'),
(28, 4821351, '', '', '', '', '', '', '', '', 'Ruiz', 'Romero', 'Fuentebella', '', 'Mila', 'Ang', 'Fuentebella'),
(29, 6430453, '', '', '', '', 'self-employed', '', '', '', 'Arnel', 'Banalnal', 'Mendina', '', 'Welda', 'Osi', 'Mendina'),
(30, 4821326, 'Jilson', 'Cempron', 'Torrentira', '', 'Private employee', 'International pharmaceuticals Incorporated', '', '', 'Ernesto', 'Olarte', 'Arcos', '', 'Corazon', 'Jadie', 'Acabado'),
(31, 5019300, '', '', '', '', '', '', '', '', 'Irineo', 'Camposano', 'Babasa', '', 'Felicitas', 'Haz', 'Fortuno'),
(32, 4821339, 'JOSE', 'LUMBIS', 'GIMENEZ', '', 'NONE', 'NA', '', '', 'ELISEO', 'RENOLAYAN', 'ALPAJARO', '', 'PURITA', 'MOJICA', 'ALBANA'),
(33, 4821299, 'Nomer', 'Neptuno', 'Villanueva', '', 'self-employed', '', '', '', 'Edwin', 'Lotivio', 'Chua', '', 'Armario', 'Nunez', 'Lydia'),
(34, 4821330, 'EFREN', 'LLEVA', 'ALCERA', '', 'GOVT. EMPLOYEE', 'DEPARTMENT OF EDUCATION REGIONAL OFFICE V', '', '', 'JULITO SR', 'BAS', 'AYENDE', '', 'AIDA', 'ABREQUE', 'ALEJO'),
(35, 4821313, 'William ', 'Atento', 'Enaje', '', 'Associate Professor ', 'Bicol University Polangui', '', '', 'Francisco', 'Locanas', 'Buatis', '', 'Salvacion', 'Vibar', 'Antivola'),
(36, 4818693, 'N/A', 'N/A', 'N/A', '', 'N/A', '', '', '', 'JUAN', 'LOPEZ', 'PEQUENA', '', 'BELLA', 'SANTAYANA', 'PEPANIO'),
(37, 4821247, 'DD', 'D', 'D', '', 'D', 'D', '', '', 'D', 'D', 'D', '', 'D', 'D', 'D'),
(38, 4821337, 'Ely', 'Avila', 'Colasito', '', 'n/a', 'n/a', '', '', 'Salvador', 'Sallan', 'Marano', '', 'Pacita', 'Bania', 'Medes'),
(39, 4821305, 'DANTE', 'TABOR', 'BACEA', '', 'N/A', 'N/A', '', '', 'DEOGRACIAS', 'CONSULTA', 'LUCES', '', 'MARIA', 'ALONDRA', 'BALAORO'),
(40, 5826994, '', '', '', '', '', '', '', '', 'Dennis', 'Marbella', 'Serrano', '', 'Teodora', 'Mirafuentes', 'Montas'),
(41, 4821243, 'Vicente ', 'Malejana', 'Rabulan', '', 'Jail Guard  - ( deceased)', 'Albay Provincial Office, Capitol Annex ', '', '', 'Domingo', 'Opena', 'Olaguer ', '', 'Mary', 'Nidea', 'Mancera '),
(42, 5052280, 'LEMUEL', 'DAPROSA', 'TORREGOZA', '', 'BRANCH MANAGER', 'MANILA TEACHERS MUTUAL AID SYSTEM', '', '', 'ERNESTO', 'MELON', 'MENORCA', '', 'RIZALINA', 'DElEON', 'CRUZ'),
(43, 4821284, 'Jose Marlon', 'Bilon', 'Dy', '', 'Self-employed/Businessman', 'MayonTreats Pasalubong Center', '', '', 'Jose', 'Pavia', 'Olayta', '', 'Rosalina', 'Penaflor', 'Cardoso'),
(44, 4821292, 'PROCESA JOY', 'SAN PABLO', 'PALACIO', '', 'TEACHER III', 'DEPED CAMARINES SUR', '', '', 'ASTERIO', 'OSURMAN', 'PALACIO', '', 'EDERLINDA', 'OCCIDENTAL', 'OSIA'),
(45, 4218819, 'Jovic', 'Labajo', 'Encarnacion', '', 'Coast Guard', 'Philippine Coast Guard ', '', '', 'Melecio', 'Quintanilla', 'Dayto', '', 'Nelly', 'Sarza', 'Dayto'),
(46, 4821261, 'Romeo', 'Borero', 'Lorico', '', 'Master Teacher', 'Department of Education', '', '', 'Rogelio', 'Gamboa', 'Regalado', '', 'Erlinda', 'Sancha', 'Flores'),
(47, 4821265, 'Gabriel Michael', 'Llabres', 'Rabelas', '', 'OFW', 'Saudi ARAMCO', '', '', 'Januario', 'Uvero', 'Urlanda', '', 'Ester', 'Fortuna', 'Pantonial'),
(48, 6313753, 'Janylin', 'Nuyles', 'Arena', '', 'Teacher III', 'DepEd Albay', '', '', 'Rolando ', 'Acuña', 'Arena', '', 'Dionisia', 'Bobis', 'Almayda '),
(49, 6323343, 'Eric', 'Marbella', 'Montas', '', 'Private Employee', 'National Grid Corporation of the Philippines', '', '', 'Luis', 'Benguet', 'Loyola', '', 'Cleofe', 'Sentino', 'Pandi'),
(50, 4821278, 'Alona', 'Bermas', 'Perdigones', '', 'Master Teacher-II', 'Ligao East Central Elementary School- DepEd Ligao City Division', '', '', 'Casiano, Jr.', 'Bermas', 'Perdigones', '', 'Estelita', 'Bermas', 'Perdigones'),
(51, 4821259, '', '', '', '', '', '', '', '', 'Jose', 'Alapad', 'Latagan', '', 'Ester', 'Lagunilla', 'Delgado'),
(52, 4588465, 'Aisa', 'Arellano', 'Morada', '', 'Teacher', 'DepEd -Albay Division', '', '', 'Restituto', 'Nuleal', 'Morada', '', 'Luz', 'Loremia', 'Morada'),
(53, 5818663, 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', '0', 'Renee', 'Mangampo', 'Legson', '', 'Lilia', 'Soriano', 'Legson'),
(54, 4821246, 'Edna', 'Gordola', 'Bolanos', '', 'Master Teacher I', 'SDO Sorsogon Province', '', '', 'Gregorio', 'Romano', 'Bolanos', '', 'Verginia', 'Bernaldez', 'Salimpadi'),
(55, 4821248, '', '', '', '', '', '', '', '', 'Basilisio, Sr.', 'Misolas', 'Lleno', '', 'Preciosa', 'Alcantara', 'Lleno'),
(56, 4821312, '', '', 'N/A', '', '', '', '', '', 'Alberto', 'Arias', 'Cielo', '', 'Corazon', 'Nogasa', 'Nogot'),
(57, 4821269, 'Baylon Louel', 'Martinez', 'Aler', '', 'Soldier ', 'Armed Forces of the Philippines (AFP)', '', '', 'Antonio, Sr.', 'Ogao', 'Adille ', '', 'Elisa', 'Agudo', 'Toledo'),
(58, 4635458, 'Francis Wilcel (Deceased)', 'Dela Vega', 'Adriano', '', 'N/A', 'N/A', '', '', 'Maria Ayrin', 'Bartolome', 'Adriano', '', 'Heide', 'Neric', 'Novorra'),
(59, 4821254, 'ISIDRO', 'HUIT', 'ALVAREZ', '', 'EDUCATION PROGRAM SUPERVISOR', 'DEPED REGIONAL OFFICE V', '', '', 'ALBERTO', 'BARRIOS', 'LEGADA', '', 'DULCESIMA', 'CHAVEZ', 'BELARMINO'),
(60, 4821251, 'BERNADETTE', 'ESCUREL', 'ESCUREL', '', 'Gov\'t Employees', 'DEPED RO V', '', '', 'JOVENCIO', '(none)', 'ROBLES', '', 'SIMEONA ', 'MIRANDA', 'BONAGUA'),
(61, 4510336, 'Angelita', 'Columna', 'Sentillas', '', 'Retired Teacher', 'Not Applicable', '', '', 'Concordio', 'Guliman', 'Sentillas', '', 'Juana', 'Canete', 'Inhog'),
(62, 4821320, 'Imee', 'M.', 'Buncaras', '', '', '', '', '', 'Domingo', 'Armero', 'Nepomuceno', '', 'lelia', 'reniva', 'casimiro'),
(63, 6431010, 'Leizel', 'Loria', 'Sapaula', '', 'Government Employee', 'Department of Budget and Management Regional Office V ', '', '', 'Allan Julian', 'Machado', 'Sapaula', '', 'Rita', 'Tantiado', 'Sapaula'),
(64, 5812149, 'Sheila Marie', 'Tayong', 'Mollejon', '', 'Teacher', 'DepEd ROV Employee', '', '', 'Emmanuel', 'Capuras', 'Pagatpat', '', 'Loida', 'Benigay', 'Son'),
(65, 4821336, 'JOY', 'SAMBAJON', 'BANDOLA', '', 'GOVERNMENT EMPLOYEE', 'DEPARTMENT OF EDUCATION, DIVISION OF ALBAY', '', '', 'GODOFREDO SR.', 'AGUALLO', 'AZORES', '', 'MARIA SALOME', 'LISTANA', 'ESPINAS'),
(66, 4821344, 'Rodolfo', 'Bonagua', 'Robles', '', 'Government Employee', 'DepEd Regional Office', '', '', 'Cesar', 'Buising', 'Mortega', '', 'Adelaida', 'Escoto', 'Escurel'),
(67, 4821323, 'Pilar', 'Malaga', 'Arandia', '', 'Government Employee', 'BGY 42, Rawis legazpi City', '', '', 'Rogelio', 'Repaso', 'Rellama', '', 'Angeles', 'Regulacion', 'Rogando'),
(68, 4177881, 'Elmira', 'Cresencio', 'Sarza', '', 'Medical Technologist', 'BRTTH', '', '', 'Johny', 'Martinez', 'Sarza', '', 'Salvacion', 'Abejuela', 'Sarza'),
(69, 4821328, 'Medalla', 'Bonagua ', 'Caño', '', 'School Principal II', 'Department of Education', '', '', 'Jerry', 'Matocdo ', 'Caño', '', 'Amparo ', 'UY', 'Caño'),
(70, 4821302, 'Angelina ', 'Arcilla ', 'Alferez', '', 'Retired  Government Employee ', 'N/A', '', '', 'Jose ', 'Camacho ', 'Alferez', '', 'Sagrada ', 'Clutario', 'Alferez'),
(71, 6313713, 'Paul', 'Bausa', 'Jumamil', '', 'Government Employee', 'Philippine Health Insurance Company (PHIC)', '', '', 'Luis', 'Ras', 'Lanuza', '', 'Josefina', 'Diamante', 'Valenzuela');

-- --------------------------------------------------------

--
-- Table structure for table `govid`
--

CREATE TABLE `govid` (
  `govID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `idType` text NOT NULL,
  `idNumber` text NOT NULL,
  `dateIssued` text NOT NULL,
  `placeIssued` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `govid`
--

INSERT INTO `govid` (`govID`, `employeeID`, `idType`, `idNumber`, `dateIssued`, `placeIssued`) VALUES
(5, 4821276, 'GSIS - UMID', 'CRN-006-0121-3439-5', '2005-07-08', 'Legazpi City'),
(6, 5818663, 'ID', '12345', '2024-12-04', 'Legazpi City');

-- --------------------------------------------------------

--
-- Table structure for table `learning_development`
--

CREATE TABLE `learning_development` (
  `trainingID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `trainingTitle` text NOT NULL,
  `trainingDateFrom` date NOT NULL,
  `trainingDateTo` date NOT NULL,
  `trainingType` text NOT NULL,
  `trainingHours` int(11) NOT NULL,
  `trainingSponsor` text NOT NULL,
  `modality` text NOT NULL,
  `trainingCertificate` blob NOT NULL,
  `actionPlan` blob NOT NULL,
  `actionPlanType` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `learning_development`
--

INSERT INTO `learning_development` (`trainingID`, `employeeID`, `trainingTitle`, `trainingDateFrom`, `trainingDateTo`, `trainingType`, `trainingHours`, `trainingSponsor`, `modality`, `trainingCertificate`, `actionPlan`, `actionPlanType`) VALUES
(12, 5818663, 'DCP Adoption: National Training of Trainers', '2024-03-06', '2024-03-09', 'Technical', 24, 'DepEd ICTS', '', 0x75706c6f6164732f747261696e696e672f636572742f4365727469666963617465206f6620417474656e64616e63652e706466, 0x75706c6f6164732f747261696e696e672f7761702f3344324e20434f524f4e205041434b4147452050524f4d4f2e706466, ''),
(16, 5818663, 'LMS: Unlocked', '2025-06-18', '2025-06-20', 'Technical', 16, 'DepEd ROV', '', 0x75706c6f6164732f747261696e696e672f636572742f415554484f524954592d544f2d50415254494349504154452e706466, 0x75706c6f6164732f747261696e696e672f7761702f415554484f524954592d544f2d50415254494349504154452e706466, ''),
(17, 5818663, 'DCP Adoption: National Training of Trainers', '2024-03-06', '2024-03-09', 'Technical', 24, 'DepEd ICTS', '', '', '', ''),
(18, 5818663, 'DCP Adoption: National Training of Trainers', '2024-03-06', '2024-03-09', 'Technical', 24, 'DepEd ICTS', '', '', '', ''),
(19, 5818663, 'adsdsds', '2024-03-06', '2024-03-09', 'Technical', 24, 'DepEd ICTS', '', '', '', ''),
(20, 5818663, 'qwerc', '2024-03-06', '2024-03-09', 'Technical', 24, 'DepEd ICTS', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `leave_application`
--

CREATE TABLE `leave_application` (
  `leaveID` varchar(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `leaveType` text NOT NULL,
  `leaveDetails` text NOT NULL,
  `requestedDays` int(11) NOT NULL,
  `dateApplied` date NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `leaveStatus` text NOT NULL,
  `dateModified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reasonforEdit` text NOT NULL,
  `supervisorID` int(11) NOT NULL,
  `dateApprovedSupervisor` date DEFAULT NULL,
  `supervisorRemarks` text DEFAULT NULL,
  `dateApprovedPersonnel` date DEFAULT NULL,
  `personnelRemarks` text DEFAULT NULL,
  `sickDetails` text DEFAULT NULL,
  `illness` text DEFAULT NULL,
  `vacationDetails` text DEFAULT NULL,
  `commutation` text NOT NULL,
  `dateDeclinedSupervisor` date DEFAULT NULL,
  `dateDeclinedPersonnel` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leave_application`
--

INSERT INTO `leave_application` (`leaveID`, `employeeID`, `leaveType`, `leaveDetails`, `requestedDays`, `dateApplied`, `startDate`, `endDate`, `leaveStatus`, `dateModified`, `reasonforEdit`, `supervisorID`, `dateApprovedSupervisor`, `supervisorRemarks`, `dateApprovedPersonnel`, `personnelRemarks`, `sickDetails`, `illness`, `vacationDetails`, `commutation`, `dateDeclinedSupervisor`, `dateDeclinedPersonnel`) VALUES
('2025-0001', 5818663, 'VL', 'vacation', 6, '2025-10-17', '2025-10-20', '2025-10-27', 'Approved', '2025-11-25 10:00:30', '', 4821247, '2025-11-13', 'Okay', '2025-11-25', 'Approved by Personnel.', NULL, '', 'Within the Philippines', 'No', NULL, NULL),
('2025-0002', 5818663, 'CTO', 'Health Break', 4, '2025-11-13', '2025-11-18', '2025-11-21', 'Approved', '2025-11-25 10:00:30', '', 4821247, NULL, NULL, '2025-11-25', 'Approved by Personnel.', NULL, '', NULL, 'No', NULL, NULL),
('2025-0003', 5818663, 'CTO', 'Vacation', 3, '2025-11-13', '2025-11-26', '2025-11-28', 'Approved', '2025-11-25 10:00:30', '', 4821247, NULL, NULL, '2025-11-25', 'Approved by Personnel.', NULL, '', NULL, 'No', NULL, NULL),
('2025-0004', 5818663, 'SL', 'sick', 2, '2025-11-13', '2025-11-19', '2025-11-20', 'Approved', '2025-11-25 10:00:30', '', 4821247, '2025-11-13', '', '2025-11-25', 'Approved by Personnel.', 'Out Patient', '', NULL, 'No', NULL, NULL),
('2025-0005', 5818663, 'VL', 'vacation', 2, '2025-11-25', '2025-11-07', '2025-11-10', 'Approved', '2025-11-25 10:00:30', '', 4821247, '2025-11-25', 'approved', '2025-11-25', 'Approved by Personnel.', NULL, '', 'Within the Philippines', 'No', NULL, NULL),
('2025-0006', 5818663, 'VL', 'vacation', 2, '2025-12-01', '2025-12-05', '2025-12-08', 'Pending', NULL, '', 4821247, NULL, NULL, NULL, NULL, NULL, '', 'Within the Philippines', 'No', NULL, NULL),
('2025-0007', 5818663, 'VL', 'vacation', 3, '2025-12-02', '2025-12-04', '2025-12-08', 'Pending', NULL, '', 4821247, NULL, NULL, NULL, NULL, 'Out Patient', '', 'Within the Philippines', 'No', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_card`
--

CREATE TABLE `leave_card` (
  `leaveRecordID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `particular` text NOT NULL,
  `leaveType` text NOT NULL,
  `period_from` text NOT NULL,
  `period_to` text NOT NULL,
  `daysWorked` float NOT NULL,
  `earned` float NOT NULL,
  `abs_und_wp` decimal(5,3) DEFAULT 0.000,
  `abs_und_wop` decimal(5,3) DEFAULT 0.000,
  `balance` decimal(5,3) DEFAULT 0.000,
  `date_action` date DEFAULT NULL,
  `action_taken` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_card`
--

INSERT INTO `leave_card` (`leaveRecordID`, `employeeID`, `particular`, `leaveType`, `period_from`, `period_to`, `daysWorked`, `earned`, `abs_und_wp`, `abs_und_wop`, `balance`, `date_action`, `action_taken`) VALUES
(28, 5818663, 'Manual Adjustment', 'VL', '2025-10-01', '2025-10-17', 0, 11.899, 0.000, 0.000, 26.899, '2025-10-16', 'Manual Adjustment (+10.00) - Earned'),
(29, 5818663, 'Manual Adjustment', 'VL', '2025-10-01', '2025-10-17', 0, 2.509, 0.000, 0.000, 2.509, '2025-10-17', 'Manual Adjustment (+2.509) - Manual Adjustment'),
(30, 5818663, 'Leave for Oct 20-27', 'VL', '2025-10-01', '2025-10-31', 0, 1.25, 0.000, 0.000, 15.658, '2025-10-17', ''),
(31, 5818663, 'Approved Leave Application #2025-0001 (With Pay)', 'VL', '2025-10-20', '2025-10-27', 6, 0, 6.000, 0.000, 9.658, '2025-11-13', 'Approved by Personnel'),
(32, 5818663, 'Approved Leave Application #2025-0005 (With Pay)', 'VL', '2025-10-20', '2025-10-27', 2, 0, 2.000, 0.000, 7.658, '2025-11-25', 'Approved by Personnel'),
(33, 4821293, 'Vacation Leave Credits earned for November 2025', 'VL', '2025-11-01', '2025-11-30', 0, 1.25, 0.000, 0.000, 16.250, '2025-12-01', 'credited by Ms. Ruth');

-- --------------------------------------------------------

--
-- Table structure for table `leave_tbl`
--

CREATE TABLE `leave_tbl` (
  `leaveTypeID` int(11) NOT NULL,
  `leaveCode` text NOT NULL,
  `leaveType` text NOT NULL,
  `leaveCredits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_tbl`
--

INSERT INTO `leave_tbl` (`leaveTypeID`, `leaveCode`, `leaveType`, `leaveCredits`) VALUES
(1, 'VL', 'Vacation Leave', 15),
(2, 'FL', 'Forced Leave', 5),
(3, 'SL', 'Sick Leave', 15),
(4, 'ML', 'Maternity Leave', 105),
(5, 'PL', 'Paternity Leave', 7),
(6, 'SPL', 'Special Privilege Leave', 3),
(7, 'SOLOPL', 'Solo Parent Leave', 7),
(8, 'STUDYL', 'Study Leave', 180),
(9, 'VAWCL', '10-Day VAWC Leave', 10),
(10, 'REHABL', 'Rehabilitation Privilege', 180),
(11, 'WOMENL', 'Special Leave Benefits for Women', 60),
(12, 'CALAMITYL', 'Special Emergency (Calamity) Leave', 5),
(13, 'CTO', 'Compensatory Time-Off', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nosa`
--

CREATE TABLE `nosa` (
  `nosaID` int(11) NOT NULL,
  `employeeID` varchar(50) DEFAULT NULL,
  `positionTitle` text NOT NULL,
  `adjustmentYear` int(11) DEFAULT NULL,
  `salaryGradeBefore` varchar(10) DEFAULT NULL,
  `salaryGradeAfter` varchar(10) DEFAULT NULL,
  `salaryBefore` decimal(10,2) DEFAULT NULL,
  `salaryAfter` decimal(10,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `effectiveDate` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nosa`
--

INSERT INTO `nosa` (`nosaID`, `employeeID`, `positionTitle`, `adjustmentYear`, `salaryGradeBefore`, `salaryGradeAfter`, `salaryBefore`, `salaryAfter`, `percentage`, `effectiveDate`, `remarks`, `created_at`) VALUES
(1, '5818663', 'Computer Programmer II', 2025, NULL, NULL, 38160.00, 38541.60, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(2, '4821350', 'Education Program Supervisor', 2025, NULL, NULL, 75881.00, 76639.81, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(3, '4821276', 'Administrative Assistant I', 2025, NULL, NULL, 18907.00, 19096.07, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(4, '4821301', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(5, '4128838', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(6, '4177881', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(7, '4214395', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(8, '4217547', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(9, '4218596', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(10, '4218819', 'Education Program Specialist II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(11, '4244600', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(12, '4510336', 'Director III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(13, '4515681', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(14, '4516231', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(15, '4520206', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(16, '4537187', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(17, '4540747', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(18, '4588465', 'Librarian II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(19, '4590076', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(20, '4635458', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(21, '4658383', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(22, '4722419', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(23, '4722653', 'Education Program Specialist II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(24, '4818693', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(25, '4819118', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(26, '4821242', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(27, '4821243', 'Nutritionist-Dietitian II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(28, '4821245', 'Legal Assistant II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(29, '4821246', 'Administrative Aide IV', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(30, '4821247', 'Information Technology Officer I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(31, '4821248', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(32, '4821251', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(33, '4821253', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(34, '4821254', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(35, '4821256', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(36, '4821257', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(37, '4821258', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(38, '4821259', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(39, '4821260', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(40, '4821261', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(41, '4821265', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(42, '4821267', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(43, '4821268', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(44, '4821269', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(45, '4821270', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(46, '4821273', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(47, '4821274', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(48, '4821275', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(49, '4821277', 'Chief Education Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(50, '4821278', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(51, '4821279', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(52, '4821280', 'Administrative Aide II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(53, '4821283', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(54, '4821284', 'Chief Education Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(55, '4821290', 'Chief Education Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(56, '4821291', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(57, '4821292', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(58, '4821293', 'Engineer III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(59, '4821294', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(60, '4821295', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(61, '4821298', 'Administrative Assistant VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(62, '4821299', 'Statistician I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(63, '4821302', 'Chief Administrative Officer', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(64, '4821305', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(65, '4821306', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(66, '4821307', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(67, '4821312', 'Administrative Assistant V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(68, '4821313', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(69, '4821316', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(70, '4821317', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(71, '4821320', 'Administrative Aide III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(72, '4821323', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(73, '4821326', 'Administrative Assistant III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(74, '4821328', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(75, '4821329', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(76, '4821330', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(77, '4821332', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(78, '4821335', 'Accountant III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(79, '4821336', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(80, '4821337', 'Administrative Assistant V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(81, '4821338', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(82, '4821339', 'Teacher Credentials Evaluator II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(83, '4821343', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(84, '4821344', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(85, '4821345', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(86, '4821346', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(87, '4821347', 'Accountant II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(88, '4821351', 'Administrative Assistant III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(89, '4821365', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(90, '4821367', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(91, '4821368', 'Administrative Officer II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(92, '5007850', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(93, '5019300', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(94, '5026390', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(95, '5029900', 'Education Program Specialist II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(96, '5052280', 'Senior Education Program Specialist', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(97, '5812149', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(98, '5826994', 'Accountant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(99, '6313713', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(100, '6313714', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(101, '6313753', 'Administrative Aide IV', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(102, '6323343', 'Special Investigator III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(103, '6324399', 'Attorney III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(104, '6429552', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(105, '6429932', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(106, '6430260', 'Administrative Assistant III', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(107, '6430453', 'Accountant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(108, '6430454', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(109, '6430927', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(110, '6431010', 'Administrative Officer II', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(111, '6431458', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(112, '6432282', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(113, '6432283', '', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(114, '6432515', 'Computer Maintenance Technologist I', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(115, '6433401', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 1.00, '2025-12-01', 'SA for 2025', '2025-12-01 01:33:00'),
(116, '5818663', 'Computer Programmer II', 2025, NULL, NULL, 38160.00, 39304.80, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(117, '4821350', 'Education Program Supervisor', 2025, NULL, NULL, 75881.00, 78157.43, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(118, '4821276', 'Administrative Assistant I', 2025, NULL, NULL, 18907.00, 19474.21, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(119, '4821301', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(120, '4128838', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(121, '4177881', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(122, '4214395', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(123, '4217547', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(124, '4218596', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(125, '4218819', 'Education Program Specialist II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(126, '4244600', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(127, '4510336', 'Director III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(128, '4515681', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(129, '4516231', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(130, '4520206', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(131, '4537187', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(132, '4540747', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(133, '4588465', 'Librarian II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(134, '4590076', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(135, '4635458', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(136, '4658383', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(137, '4722419', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(138, '4722653', 'Education Program Specialist II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(139, '4818693', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(140, '4819118', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(141, '4821242', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(142, '4821243', 'Nutritionist-Dietitian II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(143, '4821245', 'Legal Assistant II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(144, '4821246', 'Administrative Aide IV', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(145, '4821247', 'Information Technology Officer I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(146, '4821248', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(147, '4821251', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(148, '4821253', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(149, '4821254', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(150, '4821256', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(151, '4821257', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(152, '4821258', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(153, '4821259', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(154, '4821260', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(155, '4821261', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(156, '4821265', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(157, '4821267', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(158, '4821268', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(159, '4821269', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(160, '4821270', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(161, '4821273', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(162, '4821274', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(163, '4821275', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(164, '4821277', 'Chief Education Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(165, '4821278', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(166, '4821279', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(167, '4821280', 'Administrative Aide II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(168, '4821283', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(169, '4821284', 'Chief Education Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(170, '4821290', 'Chief Education Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(171, '4821291', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(172, '4821292', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(173, '4821293', 'Engineer III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(174, '4821294', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(175, '4821295', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(176, '4821298', 'Administrative Assistant VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(177, '4821299', 'Statistician I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(178, '4821302', 'Chief Administrative Officer', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(179, '4821305', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(180, '4821306', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(181, '4821307', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(182, '4821312', 'Administrative Assistant V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(183, '4821313', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(184, '4821316', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(185, '4821317', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(186, '4821320', 'Administrative Aide III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(187, '4821323', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(188, '4821326', 'Administrative Assistant III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(189, '4821328', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(190, '4821329', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(191, '4821330', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(192, '4821332', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(193, '4821335', 'Accountant III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(194, '4821336', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(195, '4821337', 'Administrative Assistant V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(196, '4821338', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(197, '4821339', 'Teacher Credentials Evaluator II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(198, '4821343', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(199, '4821344', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(200, '4821345', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(201, '4821346', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(202, '4821347', 'Accountant II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(203, '4821351', 'Administrative Assistant III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(204, '4821365', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(205, '4821367', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(206, '4821368', 'Administrative Officer II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(207, '5007850', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(208, '5019300', 'Education Program Supervisor', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(209, '5026390', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(210, '5029900', 'Education Program Specialist II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(211, '5052280', 'Senior Education Program Specialist', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(212, '5812149', 'Administrative Officer IV', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(213, '5826994', 'Accountant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(214, '6313713', 'Administrative Officer V', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(215, '6313714', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(216, '6313753', 'Administrative Aide IV', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(217, '6323343', 'Special Investigator III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(218, '6324399', 'Attorney III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(219, '6429552', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(220, '6429932', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(221, '6430260', 'Administrative Assistant III', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(222, '6430453', 'Accountant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(223, '6430454', 'Administrative Assistant I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(224, '6430927', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(225, '6431010', 'Administrative Officer II', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(226, '6431458', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(227, '6432282', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(228, '6432283', '', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(229, '6432515', 'Computer Maintenance Technologist I', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03'),
(230, '6433401', 'Administrative Aide VI', 2025, NULL, NULL, 0.00, 0.00, 3.00, '2025-12-02', 'SA for 2025', '2025-12-01 05:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `nosi`
--

CREATE TABLE `nosi` (
  `nosiID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `yearsinService` text NOT NULL,
  `allowableSteps` text NOT NULL,
  `positionTitle` text NOT NULL,
  `salaryBefore` text NOT NULL,
  `salaryAfter` text NOT NULL,
  `remarks` text NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_info`
--

CREATE TABLE `other_info` (
  `otherInfoID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `skills` text NOT NULL,
  `nonAcademicRecog` text NOT NULL,
  `membership` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `other_info`
--

INSERT INTO `other_info` (`otherInfoID`, `employeeID`, `skills`, `nonAcademicRecog`, `membership`) VALUES
(3, 5818663, 'Programming', 'NA', 'NA'),
(5, 4821276, 'computer literate, filing, typing, cooking, reading', 'N/A', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

CREATE TABLE `performance` (
  `performanceID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `calendarYear` text NOT NULL,
  `rating` float NOT NULL,
  `adjectivalRating` text NOT NULL,
  `rater` text NOT NULL,
  `ipcrf` blob NOT NULL,
  `idp` blob NOT NULL,
  `pmcf` blob NOT NULL,
  `dateAdded` timestamp NULL DEFAULT NULL,
  `dateIPCRFUploaded` timestamp NULL DEFAULT NULL,
  `dateIDPUploaded` timestamp NULL DEFAULT NULL,
  `datePMCFUploaded` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `performance`
--

INSERT INTO `performance` (`performanceID`, `employeeID`, `calendarYear`, `rating`, `adjectivalRating`, `rater`, `ipcrf`, `idp`, `pmcf`, `dateAdded`, `dateIPCRFUploaded`, `dateIDPUploaded`, `datePMCFUploaded`) VALUES
(1, 4821293, '2025', 5, 'Outstanding', 'Joan L. Lagata', '', '', '', NULL, NULL, NULL, NULL),
(2, 4821301, '2025', 4, 'Very Satisfactory', 'Jen', '', '', '', NULL, NULL, NULL, NULL),
(3, 4635458, '2025', 4.5, 'Outstanding', 'Joan L. Lagata', '', '', '', NULL, NULL, NULL, NULL),
(4, 4821293, '2024', 3.5, 'Very Satisfactory', 'Joan L. Lagata', '', '', '', NULL, NULL, NULL, NULL),
(5, 4821293, '2023', 2.59, 'Satisfactory', 'YUha', '', '', '', NULL, NULL, NULL, NULL),
(6, 5818663, '2024', 4.6, 'Outstanding', 'Salvador B. Deyto Jr.', 0x75706c6f6164732f706572666f726d616e63652f5f36393261363835396462356334312e33383030393237312e706466, 0x75706c6f6164732f706572666f726d616e63652f5f36393263663938363032313238372e34363032353538322e706466, '', NULL, '2025-11-29 03:28:25', '2025-12-01 02:12:22', NULL),
(7, 5818663, '2025', 4.6, 'Outstanding', 'Salvador B. Deyto Jr.', '', '', '', NULL, NULL, NULL, NULL),
(9, 4128838, '2024', 4.8, 'Outstanding', 'Grace U. Rabelas ', 0x75706c6f6164732f706572666f726d616e63652f69706372665f36393261353566383235663738342e33353535333734302e706466, '', '', '2025-11-24 02:57:35', '2025-11-29 02:10:00', NULL, NULL),
(11, 4821330, '2024', 4.1, 'Very Satisfactory', 'Casiano  B. Perdigones Jr.', '', '', '', '2025-11-24 03:13:22', NULL, NULL, NULL),
(12, 4217547, '2023', 4.1, 'Very Satisfactory', 'Casiano  B. Perdigones Jr.', '', '', '', '2025-11-24 03:14:04', NULL, NULL, NULL),
(13, 5818663, '2025', 4, 'Very Satisfactory', 'Salvador B. Deyto Jr.', '', '', '', '2025-11-25 03:08:01', NULL, NULL, NULL),
(14, 4128838, '2025', 4.5, 'Outstanding', 'Mayflor Marie  L. Jumamil ', 0x75706c6f6164732f706572666f726d616e63652f5f36393261376339313334643630352e31323535303635302e706466, '', '', '2025-11-28 19:57:11', '2025-11-29 04:54:41', NULL, NULL),
(15, 6432515, '2024', 4.575, 'Outstanding', 'Salvador B. Deyto Jr.', 0x75706c6f6164732f706572666f726d616e63652f5f36393264326631626363323237302e39353033303835382e706466, '', '', '2025-11-30 20:41:06', '2025-12-01 06:00:59', NULL, NULL),
(16, 6432515, '2023', 4.5, 'Outstanding', 'Salvador B. Deyto Jr.', '', '', '', '2025-11-30 20:41:30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `positionID` int(11) NOT NULL,
  `positionTitle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`positionID`, `positionTitle`) VALUES
(1, 'Accountant I'),
(2, 'Accountant II'),
(3, 'Accountant III'),
(4, 'Accountant IV'),
(5, 'Accounting  Analyst'),
(6, 'Accounting Clerk II'),
(7, 'Administrative  Assistant VI'),
(8, 'Administrative Aide I'),
(9, 'Administrative Aide II'),
(10, 'Administrative Aide III'),
(11, 'Administrative Aide IV'),
(12, 'Administrative Aide V'),
(13, 'Administrative Aide VI'),
(14, 'Administrative Assistant I'),
(15, 'Administrative Assistant II'),
(16, 'Administrative assistant III'),
(17, 'Administrative Assistant V'),
(18, 'Administrative Officer I'),
(19, 'Administrative Officer II'),
(20, 'Administrative Officer III'),
(21, 'Administrative Officer IV'),
(22, 'Administrative Officer V'),
(23, 'Agriculturist I'),
(24, 'Agriculturist II'),
(25, 'Aquacultural Technician II'),
(26, 'Aquaculturist I'),
(27, 'Architect II'),
(28, 'Architect III'),
(29, 'Artist-Illustrator II'),
(30, 'Assistant Regional Director'),
(31, 'Assistant Schools Division Superintendent'),
(32, 'Assistant School Principal 1'),
(33, 'Assistant School Principal II'),
(34, 'Assistant School Principal III'),
(35, 'Assistant Secretary'),
(36, 'Assistant Special School Principal'),
(37, 'Assistant Teacher\'s Camp Superintendent'),
(38, 'Attorney  V'),
(39, 'Attorney I'),
(40, 'Attorney II'),
(41, 'Attorney III'),
(42, 'Attorney IV'),
(43, 'Bookkeeper'),
(44, 'Budget Officer I'),
(45, 'Bureau Director III'),
(46, 'Bureau Director IV'),
(47, 'Cashier I'),
(48, 'Cashier II'),
(49, 'Chief Accountant '),
(50, 'Chief Administrative Officer'),
(51, 'Chief Education Supervisor'),
(52, 'Chief Education Supervisor'),
(53, 'Chief Health Program Officer'),
(54, 'Cinematographer I'),
(55, 'Clerk II'),
(56, 'Clerk II'),
(57, 'Clerk III'),
(58, 'College Librarian I'),
(59, 'College Librarian II'),
(60, 'Communications Equipment Operator I'),
(61, 'Communications Equipment Operator II'),
(62, 'Communications Equipment Operator III'),
(63, 'Communications Equipment Operator IV'),
(64, 'Computer File Librarian II'),
(65, 'Computer File Librarian II'),
(66, 'Computer Maintenance Technologist I'),
(67, 'Computer Maintenance Technologist III'),
(68, 'Computer Programmer II'),
(69, 'Computer Programmer III'),
(70, 'Construction and Maintenance Man'),
(71, 'Cook I'),
(72, 'Copy Reader'),
(73, 'Coxswain'),
(74, 'Crafts Education Demonstrator I'),
(75, 'Crafts Education Demonstrator II'),
(76, 'Creative Arts Specialist I'),
(77, 'Creative Arts Specialist II'),
(78, 'Dental Aide'),
(79, 'Dentist I'),
(80, 'Dentist II'),
(81, 'Dentist III'),
(82, 'Department Legislative Liason Specialist'),
(83, 'Disbursing Officer I'),
(84, 'Disbursing Officer II'),
(85, 'Dormitory Manager I'),
(86, 'Dormitory Manager II'),
(87, 'Draftsman I'),
(88, 'Draftsman II'),
(89, 'Driver I'),
(90, 'Education Program Specialist I'),
(91, 'Education Program Specialist II'),
(92, 'Education Program Supervisor'),
(93, 'Education Research Assistant II'),
(94, 'Electronics and Communications Equipment Technician I'),
(95, 'Engineer II'),
(96, 'Engineer III'),
(97, 'Engineer IV'),
(98, 'Engineer V'),
(99, 'Farm Worker I'),
(100, 'Fiscal Clerk I'),
(101, 'Fiscal Examiner I'),
(102, 'Fisherman'),
(103, 'Guesthouse Caretaker'),
(104, 'Guidance Coordinator 1'),
(105, 'Guidance Coordinator II'),
(106, 'Guidance Coordinator III'),
(107, 'Guidance Counselor I'),
(108, 'Guidance Counselor II'),
(109, 'Guidance Counselor III'),
(110, 'Guidance Services Specialist I'),
(111, 'Guidance Services Specialist II'),
(112, 'Handicraft Worker I'),
(113, 'Handicraft Worker II'),
(114, 'Head Teacher I'),
(115, 'Head Teacher II'),
(116, 'Head Teacher III'),
(117, 'Head Teacher IV'),
(118, 'Head Teacher V'),
(119, 'Head Teacher VI'),
(120, 'Health Education and Promotion Officer II'),
(121, 'Health Education and Promotion Officer III'),
(122, 'Heavy Equipment Operator I'),
(123, 'Houseparent I'),
(124, 'Human Resource Management  Officer II'),
(125, 'Human Resource Management Officer I'),
(126, 'Information Systems Analyst II'),
(127, 'Information Systems Analyst III'),
(128, 'Information Systems Researcher III'),
(129, 'Information Technology Officer'),
(130, 'Information Technology Officer I'),
(131, 'Information Technology Officer II'),
(132, 'Internal Auditing Assistant '),
(133, 'Internal Auditor I'),
(134, 'Internal Auditor II'),
(135, 'Internal Auditor III'),
(136, 'Internal Auditor IV'),
(137, 'Internal Auditor V'),
(138, 'Laboratory Technician I'),
(139, 'Legal Aide '),
(140, 'Legal Assistant I'),
(141, 'Legal Assistant II'),
(142, 'Librarian I'),
(143, 'Librarian II'),
(144, 'Librarian III'),
(145, 'Light Equipment Operator '),
(146, 'Lineman I'),
(147, 'Marine Engineman I'),
(148, 'Master Fisherman '),
(149, 'Master Teacher I'),
(150, 'Master Teacher II'),
(151, 'Master Teacher III'),
(152, 'Master Teacher IV'),
(153, 'Mechanic I'),
(154, 'Mechanic II'),
(155, 'Mechanical Plant Operator  II'),
(156, 'Mechanical Plant Operator I'),
(157, 'Medical Officer II'),
(158, 'Medical Officer III'),
(159, 'Medical Officer IV'),
(160, 'Metal Worker I'),
(161, 'Nurse I'),
(162, 'Nurse II'),
(163, 'Nurse Maid I'),
(164, 'Nursing Attendant I'),
(165, 'Nutritionist-Dietitian I'),
(166, 'Nutritionist-Dietitian II'),
(167, 'Nutritionist-Dietitian III'),
(168, 'Photoengraver II'),
(169, 'Planning Officer I'),
(170, 'Planning Officer II'),
(171, 'Planning Officer III'),
(172, 'Planning Officer IV'),
(173, 'Planning Officer V'),
(174, 'Printing Foreman'),
(175, 'Project Development Assistant '),
(176, 'Project Development Officer I'),
(177, 'Project Development Officer II'),
(178, 'Project Development Officer III'),
(179, 'Project Development Officer IV'),
(180, 'Project Development Officer V'),
(181, 'Project Evaluation Officer IV'),
(182, 'Proofreader II'),
(183, 'Public Schools District Supervisor'),
(184, 'Publication Production Supervisor '),
(185, 'Pyschologist I'),
(186, 'Records Officer II'),
(187, 'Regional Director'),
(188, 'Registrar I'),
(189, 'Registrar II'),
(190, 'Reproduction Machine Operator I'),
(191, 'School Farm Demonstrator'),
(192, 'School Farming Coordinator I'),
(193, 'School Farming Coordinator II'),
(194, 'School Farming Coordinator III'),
(195, 'School Librarian I'),
(196, 'School Librarian II'),
(197, 'School Librarian III'),
(198, 'School Principal I'),
(199, 'School Principal II'),
(200, 'School Principal III'),
(201, 'School Principal IV'),
(202, 'Schools Division Superintendent'),
(203, 'Science Research Assistant '),
(204, 'Science Research Specialist II'),
(205, 'Science Research Technician I'),
(206, 'Science Research Technician II'),
(207, 'Science Research Technician III'),
(208, 'Science Research Technician IV'),
(209, 'Secretary'),
(210, 'Security Guard I'),
(211, 'Security Guard II'),
(212, 'Security Guard III'),
(213, 'Security Officer II'),
(214, 'Security Officer IV'),
(215, 'Senior Administrative Assistant I'),
(216, 'Senior Administrative Assistant II'),
(217, 'Senior Administrative Assistant III'),
(218, 'Senior Administrative Assistant V'),
(219, 'Senior Bookkeeper'),
(220, 'Senior Education Program Specialist'),
(221, 'Senior Science Research Specialist'),
(222, 'Social Welfare Officer I'),
(223, 'Special Investigator III'),
(224, 'Special Invetigator II'),
(225, 'Special School Principal I'),
(226, 'Special School Principal II'),
(227, 'Statistician Aide'),
(228, 'Statistician I'),
(229, 'Statistician II'),
(230, 'Statistician III'),
(231, 'Supervising Administrative Officer'),
(232, 'Supervising Education Program Specialist'),
(233, 'Supervising Health Program Officer'),
(234, 'Supply Officer I'),
(235, 'Supply Officer II'),
(236, 'Teacher Credentials Evaluator I'),
(237, 'Teacher Credentials Evaluator  II'),
(238, 'Teacher I'),
(239, 'Teacher II'),
(240, 'Teacher III'),
(241, 'Teacher\'s Camp Superintendent'),
(242, 'Teaching-Aids Specialist'),
(243, 'Telegram Carrier'),
(244, 'Typesetter II'),
(245, 'Undersecretary'),
(246, 'Utility Foreman'),
(247, 'Utility Worker I'),
(248, 'Vocational Instruction Supervisor I'),
(249, 'Vocational Instruction Supervisor II'),
(250, 'Vocational Instruction Supervisor III'),
(251, 'Vocational Placement Coordinator I'),
(252, 'Vocational School Administrator I'),
(253, 'Vocational School Administrator II'),
(254, 'Warehouseman III'),
(255, 'Watchman I'),
(256, 'Watchman II');

-- --------------------------------------------------------

--
-- Table structure for table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `questionnaireID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `34a` text NOT NULL,
  `34b` text NOT NULL,
  `35a` text NOT NULL,
  `35b` text NOT NULL,
  `status` text NOT NULL,
  `q36` text NOT NULL,
  `q37` text NOT NULL,
  `38a` text NOT NULL,
  `38b` text NOT NULL,
  `q39` text NOT NULL,
  `40a` text NOT NULL,
  `40b` text NOT NULL,
  `40c` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questionnaire`
--

INSERT INTO `questionnaire` (`questionnaireID`, `employeeID`, `34a`, `34b`, `35a`, `35b`, `status`, `q36`, `q37`, `38a`, `38b`, `q39`, `40a`, `40b`, `40c`) VALUES
(23, 5818663, 'No', 'No', 'No', 'No', ' ', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `reference_person`
--

CREATE TABLE `reference_person` (
  `refPersonID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `refName` text NOT NULL,
  `refAddress` text NOT NULL,
  `refNumber` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reference_person`
--

INSERT INTO `reference_person` (`refPersonID`, `employeeID`, `refName`, `refAddress`, `refNumber`) VALUES
(5, 4821276, 'Evangeline A. Saculo', 'Department of Education Regional Office 5', 'N/A'),
(6, 4821276, 'Sancha M. Nacion', 'Department of Education Regional Office 5', 'N/A'),
(7, 4821276, 'Gibsen De Leoz', 'Barangay 4, Sagpon, Albay, Legazpi City', 'N/A'),
(8, 5818663, 'Reference 1', 'Address', '0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `salary_grade`
--

CREATE TABLE `salary_grade` (
  `salaryGradeID` int(11) NOT NULL,
  `salaryGrade` int(11) NOT NULL,
  `salaryStep` int(11) NOT NULL,
  `salary` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_grade`
--

INSERT INTO `salary_grade` (`salaryGradeID`, `salaryGrade`, `salaryStep`, `salary`) VALUES
(1, 1, 1, ' 13,000 '),
(2, 2, 1, ' 13,819 '),
(3, 3, 1, ' 14,678 '),
(4, 4, 1, ' 15,586 '),
(5, 5, 1, ' 16,543 '),
(6, 6, 1, ' 17,553 '),
(7, 7, 1, ' 18,620 '),
(8, 8, 1, ' 19,744 '),
(9, 9, 1, ' 21,211 '),
(10, 10, 1, ' 23,176 '),
(11, 11, 1, ' 27,000 '),
(12, 12, 1, ' 29,165 '),
(13, 13, 1, ' 31,320 '),
(14, 14, 1, ' 33,843 '),
(15, 15, 1, ' 36,619 '),
(16, 16, 1, ' 39,672 '),
(17, 17, 1, ' 43,030 '),
(18, 18, 1, ' 46,725 '),
(19, 19, 1, ' 51,357 '),
(20, 20, 1, ' 57,347 '),
(21, 21, 1, ' 63,997 '),
(22, 22, 1, ' 71,511 '),
(23, 23, 1, ' 80,003 '),
(24, 24, 1, ' 90,078 '),
(25, 25, 1, ' 102,690 '),
(26, 26, 1, ' 116,040 '),
(27, 27, 1, ' 131,124 '),
(28, 28, 1, ' 148,171 '),
(29, 29, 1, ' 167,432 '),
(30, 30, 1, ' 189,199 '),
(31, 31, 1, ' 278,434 '),
(32, 32, 1, ' 331,954 '),
(33, 33, 1, ' 419,144 '),
(34, 1, 2, ' 13,109 '),
(35, 2, 2, ' 13,925 '),
(36, 3, 2, ' 14,792 '),
(37, 4, 2, ' 15,706 '),
(38, 5, 2, ' 16,671 '),
(39, 6, 2, ' 17,688 '),
(40, 7, 2, ' 18,763 '),
(41, 8, 2, ' 19,923 '),
(42, 9, 2, ' 21,388 '),
(43, 10, 2, ' 23,370 '),
(44, 11, 2, ' 27,284 '),
(45, 12, 2, ' 29,449 '),
(46, 13, 2, ' 31,633 '),
(47, 14, 2, ' 34 '),
(48, 15, 2, ' 36,997 '),
(49, 16, 2, ' 40,088 '),
(50, 17, 2, ' 43,488 '),
(51, 18, 2, ' 47,228 '),
(52, 19, 2, ' 52,096 '),
(53, 20, 2, ' 58,181 '),
(54, 21, 2, ' 64,940 '),
(55, 22, 2, ' 72,577 '),
(56, 23, 2, ' 81,207 '),
(57, 24, 2, ' 91,548 '),
(58, 25, 2, ' 104,366 '),
(59, 26, 2, ' 117,933 '),
(60, 27, 2, ' 133,264 '),
(61, 28, 2, ' 150,589 '),
(62, 29, 2, ' 170,166 '),
(63, 30, 2, ' 192,286 '),
(64, 31, 2, ' 283,872 '),
(65, 32, 2, ' 338,649 '),
(66, 33, 2, ' 431,718 '),
(67, 1, 3, ' 13,219 '),
(68, 2, 3, ' 14,032 '),
(69, 3, 3, ' 14,905 '),
(70, 4, 3, ' 15,827 '),
(71, 5, 3, ' 16,799 '),
(72, 6, 3, ' 17,824 '),
(73, 7, 3, ' 18,907 '),
(74, 8, 3, ' 20,104 '),
(75, 9, 3, ' 21,567 '),
(76, 10, 3, ' 23,565 '),
(77, 11, 3, ' 27,573 '),
(78, 12, 3, ' 29,737 '),
(79, 13, 3, ' 31,949 '),
(80, 14, 3, ' 34,535 '),
(81, 15, 3, ' 37,380 '),
(82, 16, 3, ' 40,509 '),
(83, 17, 3, ' 43,951 '),
(84, 18, 3, ' 47,738 '),
(85, 19, 3, ' 52,847 '),
(86, 20, 3, ' 59,030 '),
(87, 21, 3, ' 65,899 '),
(88, 22, 3, ' 73,661 '),
(89, 23, 3, ' 82,432 '),
(90, 24, 3, ' 93,043 '),
(91, 25, 3, ' 106,069 '),
(92, 26, 3, ' 119,858 '),
(93, 27, 3, ' 135,440 '),
(94, 28, 3, ' 153,047 '),
(95, 29, 3, ' 172,943 '),
(96, 30, 3, ' 195,425 '),
(97, 31, 3, ' 289,416 '),
(98, 32, 3, ' 345,478 '),
(99, 1, 4, ' 13,329 '),
(100, 2, 4, ' 14,140 '),
(101, 3, 4, ' 15,020 '),
(102, 4, 4, ' 15,948 '),
(103, 5, 4, ' 16,928 '),
(104, 6, 4, ' 17,962 '),
(105, 7, 4, ' 19,053 '),
(106, 8, 4, ' 20,285 '),
(107, 9, 4, ' 21,747 '),
(108, 10, 4, ' 23,762 '),
(109, 11, 4, ' 27,865 '),
(110, 12, 4, ' 30,028 '),
(111, 13, 4, ' 32,269 '),
(112, 14, 4, ' 34,888 '),
(113, 15, 4, ' 37,768 '),
(114, 16, 4, ' 40,935 '),
(115, 17, 4, ' 44,420 '),
(116, 18, 4, ' 48,253 '),
(117, 19, 4, ' 53,610 '),
(118, 20, 4, ' 59,892 '),
(119, 21, 4, ' 66,873 '),
(120, 22, 4, ' 74,762 '),
(121, 23, 4, ' 83,683 '),
(122, 24, 4, ' 94,562 '),
(123, 25, 4, ' 107,800 '),
(124, 26, 4, ' 121,814 '),
(125, 27, 4, ' 137,650 '),
(126, 28, 4, ' 155,545 '),
(127, 29, 4, ' 175,766 '),
(128, 30, 4, ' 198,615 '),
(129, 31, 4, ' 295,069 '),
(130, 32, 4, ' 352,445 '),
(131, 1, 5, ' 13,441 '),
(132, 2, 5, ' 14,248 '),
(133, 3, 5, ' 15,136 '),
(134, 4, 5, ' 16,071 '),
(135, 5, 5, ' 17,057 '),
(136, 6, 5, ' 18,100 '),
(137, 7, 5, ' 19,198 '),
(138, 8, 5, ' 20,468 '),
(139, 9, 5, ' 21,929 '),
(140, 10, 5, ' 23,961 '),
(141, 11, 5, ' 28,161 '),
(142, 12, 5, ' 30,323 '),
(143, 13, 5, ' 32,594 '),
(144, 14, 5, ' 35,244 '),
(145, 15, 5, ' 38,160 '),
(146, 16, 5, ' 41,367 '),
(147, 17, 5, ' 44,895 '),
(148, 18, 5, ' 48,776 '),
(149, 19, 5, ' 54,386 '),
(150, 20, 5, ' 60,769 '),
(151, 21, 5, ' 67,864 '),
(152, 22, 5, ' 75,881 '),
(153, 23, 5, ' 85,049 '),
(154, 24, 5, ' 96,105 '),
(155, 25, 5, ' 109,560 '),
(156, 26, 5, ' 123,803 '),
(157, 27, 5, ' 139,897 '),
(158, 28, 5, ' 158,083 '),
(159, 29, 5, ' 178,634 '),
(160, 30, 5, ' 201,856 '),
(161, 31, 5, ' 300,833 '),
(162, 32, 5, ' 359,553 '),
(163, 1, 6, ' 13,553 '),
(164, 2, 6, ' 14,357 '),
(165, 3, 6, ' 15,251 '),
(166, 4, 6, ' 16,193 '),
(167, 5, 6, ' 17,189 '),
(168, 6, 6, ' 18,238 '),
(169, 7, 6, ' 19,346 '),
(170, 8, 6, ' 20,653 '),
(171, 9, 6, ' 22,112 '),
(172, 10, 6, ' 24,161 '),
(173, 11, 6, ' 28,462 '),
(174, 12, 6, ' 30,622 '),
(175, 13, 6, ' 32,922 '),
(176, 14, 6, ' 35,605 '),
(177, 15, 6, ' 38,557 '),
(178, 16, 6, ' 41,804 '),
(179, 17, 6, ' 45,376 '),
(180, 18, 6, ' 49,305 '),
(181, 19, 6, ' 55,174 '),
(182, 20, 6, ' 61,660 '),
(183, 21, 6, ' 68,870 '),
(184, 22, 6, ' 77,019 '),
(185, 23, 6, ' 86,437 '),
(186, 24, 6, ' 97,674 '),
(187, 25, 6, ' 111,348 '),
(188, 26, 6, ' 125,823 '),
(189, 27, 6, ' 142,180 '),
(190, 28, 6, ' 160,664 '),
(191, 29, 6, ' 181,550 '),
(192, 30, 6, ' 205,151 '),
(193, 31, 6, ' 306,708 '),
(194, 32, 6, ' 366,804 '),
(195, 1, 7, ' 13,666 '),
(196, 2, 7, ' 14,468 '),
(197, 3, 7, ' 15,369 '),
(198, 4, 7, ' 16,318 '),
(199, 5, 7, ' 17,321 '),
(200, 6, 7, ' 18,379 '),
(201, 7, 7, ' 19,494 '),
(202, 8, 7, ' 20,840 '),
(203, 9, 7, ' 22,297 '),
(204, 10, 7, ' 24,363 '),
(205, 11, 7, ' 28,766 '),
(206, 12, 7, ' 30,924 '),
(207, 13, 7, ' 33,254 '),
(208, 14, 7, ' 35,971 '),
(209, 15, 7, ' 38,959 '),
(210, 16, 7, ' 42,247 '),
(211, 17, 7, ' 45,862 '),
(212, 18, 7, ' 49,840 '),
(213, 19, 7, ' 55,976 '),
(214, 20, 7, ' 62,565 '),
(215, 21, 7, ' 69,893 '),
(216, 22, 7, ' 78,175 '),
(217, 23, 7, ' 87,847 '),
(218, 24, 7, ' 99,268 '),
(219, 25, 7, ' 113,166 '),
(220, 26, 7, ' 127,876 '),
(221, 27, 7, ' 144,501 '),
(222, 28, 7, ' 163,286 '),
(223, 29, 7, ' 184,513 '),
(224, 30, 7, ' 208,499 '),
(225, 31, 7, ' 312,699 '),
(226, 32, 7, ' 374,202 '),
(227, 1, 8, ' 13,780 '),
(228, 2, 8, ' 14,578 '),
(229, 3, 8, ' 15,486 '),
(230, 4, 8, ' 16,443 '),
(231, 5, 8, ' 17,453 '),
(232, 6, 8, ' 18,520 '),
(233, 7, 8, ' 19,644 '),
(234, 8, 8, ' 21,029 '),
(235, 9, 8, ' 22,483 '),
(236, 10, 8, ' 24,567 '),
(237, 11, 8, ' 29,075 '),
(238, 12, 8, ' 31,230 '),
(239, 13, 8, ' 33,591 '),
(240, 14, 8, ' 36,341 '),
(241, 15, 8, ' 39,367 '),
(242, 16, 8, ' 42,694 '),
(243, 17, 8, ' 46,355 '),
(244, 18, 8, ' 50,382 '),
(245, 19, 8, ' 56,790 '),
(246, 20, 8, ' 63,485 '),
(247, 21, 8, ' 70,933 '),
(248, 22, 8, ' 79,349 '),
(249, 23, 8, ' 89,281 '),
(250, 24, 8, ' 100,888 '),
(251, 25, 8, ' 115,012 '),
(252, 26, 8, ' 129,964 '),
(253, 27, 8, ' 146,859 '),
(254, 28, 8, ' 165,951 '),
(255, 29, 8, ' 187,525 '),
(256, 30, 8, ' 211,902 '),
(257, 31, 8, ' 318,806 '),
(258, 32, 8, ' 381,748 ');

-- --------------------------------------------------------

--
-- Table structure for table `scholarship`
--

CREATE TABLE `scholarship` (
  `scholarshipID` int(11) NOT NULL,
  `scholarshipTitle` int(11) NOT NULL,
  `scholarshipGivingBody` int(11) NOT NULL,
  `dateAcquired` text NOT NULL,
  `dateExpired` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_record`
--

CREATE TABLE `service_record` (
  `workRecordID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `plantillaItem` text DEFAULT NULL,
  `appointmentStatus` text DEFAULT NULL,
  `inclusivedateFrom` text DEFAULT NULL,
  `inclusivedateTo` text DEFAULT NULL,
  `positionTitle` text DEFAULT NULL,
  `employer` text DEFAULT NULL,
  `employerAddress` text DEFAULT NULL,
  `office` text DEFAULT NULL,
  `salaryGrade` text DEFAULT NULL,
  `stepIncrement` text DEFAULT NULL,
  `salary` text NOT NULL,
  `annualSalary` text DEFAULT NULL,
  `governmentService` text DEFAULT NULL,
  `branch` text DEFAULT NULL,
  `lwop` text DEFAULT NULL,
  `separationDate` text DEFAULT NULL,
  `effectivityDate` text NOT NULL,
  `remarks` text DEFAULT NULL COMMENT 'ORIGINAL APPOINTMENT, RE-APPOINTMENT',
  `classification` text DEFAULT NULL COMMENT 'teaching, teaching-related, ntp'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `service_record`
--

INSERT INTO `service_record` (`workRecordID`, `employeeID`, `plantillaItem`, `appointmentStatus`, `inclusivedateFrom`, `inclusivedateTo`, `positionTitle`, `employer`, `employerAddress`, `office`, `salaryGrade`, `stepIncrement`, `salary`, `annualSalary`, `governmentService`, `branch`, `lwop`, `separationDate`, `effectivityDate`, `remarks`, `classification`) VALUES
(29, 5818663, 'OSEC-DECSB-COMPRO2-390061-2014', 'Permanent', '2023-08-25', 'present', 'Computer Programmer II', 'Department of Education Regional Office 5', '', 'Office of the Regional Director - Information and Communications Technology Unit', '15', '5', '', '', 'Yes', '', '', '', '', '', ''),
(30, 5818663, NULL, 'Permanent', '2016-07-18', '2023-08-24', 'Special Science Teacher I', 'Department of Education - SDO Albay - Marcial O Ranola Memorial School', 'San Francisco, Guinobatan, Albay', '', '13', '3', '', '', 'Yes', '', '', '', '', '', ''),
(31, 4821350, '', 'Permanent', '', 'present', 'Education Program Supervisor', 'Department of Education Regional Office 5', '', 'Human Resource Development Division - ', '22', '5', '', '', 'Yes', '', '', '', '', '', ''),
(32, 4821276, NULL, 'Contract of Service', '07/02/2000', '28/07/2001', 'Quality Assurance Auditor & SPECS Analyst', 'Content Online (INNODATA)', '', '', ' ', ' ', '3,000.00', '', 'No', '', '', '', '', NULL, ''),
(33, 4821276, NULL, 'Contract of Service', '10/01/2000', '07/04/2000', 'Secretary/Staff of Mon.s Noe Delos Santos', 'Mater Salutis Seminary', '', '', ' ', ' ', '1,000.00', '', 'No', '', '', '', '', NULL, ''),
(34, 4821276, '', 'Permanent', '01/01/2024', 'present', 'Administrative Assistant I', 'Department of Education Regional Office 5', '', 'Field Technical Assistance Division - ', '7', '3', '', '', 'Yes', '', '', '', '', NULL, ''),
(35, 4821276, '', 'Permanent', '01/01/2023', '31/12/2023', 'Administrative Assistant I', 'Department of Education Regional Office 5', '', '', '7', '3', '', '', 'Yes', '', '', '', '', NULL, ''),
(43, 4821293, 'Harum accusantium ir', 'Casual', '1990-11-22', '1993-08-07', 'Registrar II', 'Ipsa facere magnam ', NULL, NULL, '28', '5', '', '75', 'Yes', NULL, NULL, NULL, '', 'Repellendus Assumen', NULL),
(44, 4821301, NULL, 'Permanent', '2024-07-18', 'present', 'Administrative Officer II', 'Administrative Division-Personnel Section', NULL, NULL, NULL, NULL, '', '', 'Yes', '', '', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `signatory`
--

CREATE TABLE `signatory` (
  `signatoryID` int(11) NOT NULL,
  `signatory_name` text NOT NULL,
  `designation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signatory`
--

INSERT INTO `signatory` (`signatoryID`, `signatory_name`, `designation`) VALUES
(1, 'MARY ANN T. BAÑAS', 'Administrative Officer V');

-- --------------------------------------------------------

--
-- Table structure for table `sr_request`
--

CREATE TABLE `sr_request` (
  `srRequestID` varchar(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `srRequestPurpose` text NOT NULL,
  `srRequestDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `srRequestStatus` text NOT NULL,
  `srApprovedDate` timestamp NULL DEFAULT NULL,
  `srDeclinedDate` timestamp NULL DEFAULT NULL,
  `srRequestRemarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sr_request`
--

INSERT INTO `sr_request` (`srRequestID`, `employeeID`, `srRequestPurpose`, `srRequestDate`, `srRequestStatus`, `srApprovedDate`, `srDeclinedDate`, `srRequestRemarks`) VALUES
('2024-0001', 4821290, 'For uploading in my profile', '2024-12-11 09:35:46', 'processing', NULL, NULL, NULL),
('2025-0001', 4821290, 'For PDS', '2025-02-18 00:20:22', 'approved', '2025-12-01 05:55:27', NULL, NULL),
('2025-0005', 5818663, 'scholarship application', '2025-07-01 07:45:10', 'approved', '2025-10-20 10:51:35', NULL, NULL),
('2025-0006', 5818663, 'promotion', '2025-07-04 02:18:59', 'approved', '2025-07-04 02:22:55', NULL, NULL),
('2025-0007', 5818663, 'sample\r\n', '2025-11-13 04:52:13', 'approved', '2025-11-25 10:03:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `tID` int(11) NOT NULL,
  `trainingTitle` text NOT NULL,
  `trainingType` text NOT NULL,
  `trainingStart` date NOT NULL,
  `trainingEnd` date NOT NULL,
  `trainingVenue` text NOT NULL,
  `trainingFund` text NOT NULL,
  `trainingMemo` blob NOT NULL,
  `remarks` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`tID`, `trainingTitle`, `trainingType`, `trainingStart`, `trainingEnd`, `trainingVenue`, `trainingFund`, `trainingMemo`, `remarks`, `created_at`) VALUES
(1, 'Sample Training', 'Technical', '2025-12-01', '2025-12-02', 'NEAP Hall, DepEd Region V', 'ORD', 0x75706c6f6164732f313736343536343139375f3230343231202d204f6666696365204d656d6f20485244442d4e4541502d522d323032352d313239202d20416464656e64756d20436f72726967656e64756d20746f20746865204f6666696365204d656d6f72616e64756d204e6f2e203034362c2030383120616e64203130382c20732e20323032352072652048756d616e205265736f7572636520416374697669746965732c2050726f6772616d7320616e642050726f6a6563747320496e666f726d6174696f6e2053797374656d204164766f6361746573206f662044657045642e706466, '', '2025-12-01 04:43:17'),
(2, 'Itaque officia duis ', 'Technical', '1973-07-28', '1985-02-08', 'Dolor dolore impedit', 'Enim perferendis vol', '', '', '2026-01-05 02:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `userRole` text NOT NULL,
  `position` text NOT NULL,
  `department` text NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profilePicture` blob DEFAULT NULL,
  `accountStatus` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `employeeID`, `username`, `password`, `email`, `firstName`, `lastName`, `userRole`, `position`, `department`, `date_joined`, `profilePicture`, `accountStatus`) VALUES
(1, 1, 'ictu.rov', 'admin@123', 'ictu.rov@deped.gov.ph', 'ICT', 'Region V', 'Administrator', '', 'ORD-ICT', '0000-00-00 00:00:00', '', 'active'),
(2, 2, 'personnel.rov', 'admin@123', 'personnel.rov@deped.gov.ph', 'Personnel', 'Section', 'RSP', '', 'AD-Personnel', '2025-11-30 09:35:50', 0x2e2e2f75706c6f6164732f524f56207365616c2e706e67, 'active'),
(3, 4821293, 'shannon.abogado', 'DROV@1234', 'shannon.abogado@deped.gov.ph', 'Shannon', 'Abogado', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(4, 4821301, 'gregor.abuid', 'DROV@1234', 'gregor.abuid@deped.gov.ph', 'Gregor', 'Abuid', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(5, 4635458, 'mariaayrin.adriano', 'Jesus_MySAVIOR4002', 'mariaayrin.adriano@deped.gov.ph', 'Maria Ayrin ', 'Adriano', 'User', '', '', '2024-05-27 10:21:53', '', 'active'),
(6, 4821330, 'aily.alcera', 'DEPED@1972', 'aily.alcera@deped.gov.ph', 'Aily  ', 'Alcera', 'User', '', '', '2024-05-27 10:37:55', '', 'active'),
(7, 4821253, 'efren.alcera', 'DROV@1234', 'efren.alcera@deped.gov.ph', 'Efren  ', 'Alcera', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(8, 4821269, 'macorazon.aler', 'Minty@1443', 'ma.aler@deped.gov.ph', 'Ma Corazon ', 'Aler', 'User', '', '', '2024-05-27 11:17:48', '', 'active'),
(9, 4821302, 'roeyjose.alferez', 'P@ssDepEd05', 'roey.alferez@deped.gov.ph', 'Roey Jose ', 'Alferez', 'User', '', '', '2024-05-27 14:31:49', '', 'active'),
(10, 4821254, 'christie.alvarez', 'MILECOCTOBER2CLA_', 'christie.alvarez@deped.gov.ph', 'Christie  ', 'Alvarez', 'User', '', '', '2024-05-27 11:20:43', '', 'active'),
(11, 6313753, 'rodel.arena', 'rodel@0505', 'rodel.arena@deped.gov.ph', 'Rodel  ', 'Arena', 'User', '', '', '2024-05-27 10:21:32', '', 'active'),
(12, 6313714, 'markkevin.arroco', 'DROV@1234', 'markkevin.arroco@deped.gov.ph', 'Mark Kevin ', 'Arroco', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(13, 4821270, 'ronald.asis', 'DROV@1234', 'ronald.asis@deped.gov.ph', 'Ronald  ', 'Asis', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(14, 4821332, 'ma.theresa.astor', 'DROV@1234', 'matheresa.astor@deped.gov.ph', 'Ma. Theresa ', 'Astor', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(15, 4722653, 'jeremy.atad', 'jeremyatad', 'jeremy.atad@deped.gov.ph', 'Jeremy  ', 'Atad', 'User', '', '', '2024-05-27 10:17:11', '', 'active'),
(16, 4515681, 'lorenzo.avenido', 'DROV@1234', 'lorenzo.avenido@deped.gov.ph', 'Lorenzo ', 'Avenido', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(17, 4821245, 'julieann.azores-mesias', 'Aja_102391', 'julieann.azores@deped.gov.ph', 'Julie Ann ', 'Azores-Mesias', 'User', '', '', '2024-05-27 10:19:22', '', 'active'),
(18, 5019300, 'manuel.babasa', 'rov071019', 'manuel.babasa@deped.gov.ph', 'Manuel  ', 'Babasa', 'User', '', '', '2024-05-27 10:18:24', '', 'active'),
(19, 4821305, 'rowena.bacea', 'wena1970', 'rowena.bacea@deped.gov.ph', 'Rowena  ', 'Bacea', 'User', '', '', '2024-05-27 11:33:18', '', 'active'),
(20, 4821306, 'therese.banadera', 'DROV@1234', 'therese.banadera@deped.gov.ph', 'Therese  ', 'Banadera', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(21, 4821307, 'maryann.banas', 'DROV@1234', 'mary.banas@deped.gov.ph', 'Mary Ann ', 'Banas', 'User', '', '', '2024-09-17 16:37:52', '', 'active'),
(22, 4821294, 'roy.banas', 'DROV@1234', 'roy.banas@deped.gov.ph', 'Roy  ', 'Banas', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(23, 6432283, 'christiangregory.bandola', '055340807059', 'christian.bandola@deped.gov.ph', 'Christian Gregory ', 'Bandola', 'User', '', '', '2024-05-27 15:23:02', '', 'active'),
(24, 4821336, 'sonia.bandola', 'sbandola691961', 'sonia.bandola@deped.gov.ph', 'Sonia  ', 'Bandola', 'User', '', '', '2024-05-27 12:33:46', '', 'active'),
(25, 6324399, 'beaanne.baroma', 'beibei123', 'beaanne.baroma@deped.gov.ph', 'Bea Anne ', 'Baroma', 'User', '', '', '2024-08-26 23:22:06', '', 'active'),
(26, 4821365, 'mariacristina.baroso', 'DROV@1234', 'mariacristina.baroso@deped.gov.ph', 'Maria Cristina ', 'Baroso', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(27, 4821329, 'ruth.bendita', 'BELOVED13', 'ruth.bendita@deped.gov.ph   ', 'Ruth  ', 'Bendita', 'User', '', '', '2024-05-27 10:19:14', '', 'active'),
(28, 6429552, 'ma.anamae.bernardino', 'aNAaNDREW1225', 'maanamae.bernardino@deped.gov.ph', 'Ma. Ana Mae', 'Bernardino', 'User', '', '', '2024-05-27 10:20:03', '', 'active'),
(29, 4821246, 'pedro.bolanos', 'kabebepete', 'pedro.bolanos@deped.gov.ph', 'Pedro  ', 'Bolanos', 'User', '', '', '2024-05-27 10:56:21', '', 'active'),
(30, 4821295, 'jasminena.bonito', 'DROV@1234', 'jasminena.bonito@deped.gov.ph', 'Jasminena  ', 'Bonito', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(31, 4821280, 'francia.borromeo', 'chit0572', 'francia.borromeo@deped.gov.ph', 'Francia  ', 'Borromeo', 'User', '', '', '2024-05-27 10:23:38', '', 'active'),
(32, 4722419, 'loyd.botor', 'DROV@1234', 'lloyd.botor@deped.gov.ph', 'Loyd  ', 'Botor', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(33, 4218596, 'ronaldo.buella', 'DROV@1234', 'ronaldo.buella@deped.gov.ph', 'Ronaldo  ', 'Buella', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(34, 6432515, 'marvin.buhat', 'Az070918*', 'marvin.buhat@deped.gov.ph', 'Marvin  ', 'Buhat', 'User', '', '', '2024-09-18 23:17:48', 0x75706c6f6164732f64657065645f726f762e706e67, 'active'),
(35, 4821256, 'francisco.bulalacao', 'DROV@1234', 'francisco.bulalacao@deped.gov.ph', 'Francisco', 'Bulalacao', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(36, 5026390, 'sheila.bulawan', 'DROV@1234', 'sheila.bulawan@deped.gov.ph', 'Sheila  ', 'Bulawan', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(37, 4821328, 'ernie.cano', 'DepEdROV', 'ernie.cano@deped.gov.ph', 'Ernie  ', 'Cano', 'User', '', '', '2024-05-27 13:57:20', '', 'active'),
(38, 4658383, 'mercy.castillo', 'DROV@1234', 'mercy.castillo1@deped.gov.ph', 'Mercy  ', 'Castillo', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(39, 4590076, 'joy.chavez', '301926joychavez', 'joy.chavez1@deped.gov.ph', 'Joy  ', 'Chavez', 'User', '', '', '2024-05-27 10:19:18', '', 'active'),
(40, 4821312, 'pandora.cielo', '0106ying74', 'pandora.cielo@deped.gov.ph', 'Pandora  ', 'Cielo', 'User', '', '', '2024-05-27 11:23:37', '', 'active'),
(41, 4821337, 'agnes.colasito', 'neshy@01041967', 'agnes.colasito@deped.gov.ph', 'Agnes  ', 'Colasito', 'User', '', '', '2024-05-27 10:34:20', '', 'active'),
(42, 4537187, 'joe-bren.consuelo', 'DROV@1234', 'joe-bren.consuelo001@deped.gov.ph', 'Joe-Bren  ', 'Consuelo', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(43, 4821338, 'leahbelle.depadua', 'DROV@1234', 'leahbelle.depadua@deped.gov.ph', 'Leah Belle ', 'De Padua', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(44, 4821247, 'salvadorjr..deyto', 'Buddy1970', 'salvador.deyto@deped.gov.ph', 'Salvador Jr. ', 'Deyto', 'Head', '', '', '2025-10-02 08:05:27', '', 'active'),
(45, 4821257, 'chozara.duroy', 'DROV@1234', 'chozara.duroy@deped.gov.ph', 'Chozara  ', 'Duroy', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(46, 4821284, 'jocelyn.dy', 'Copper@1123', 'jocelyn.dy@deped.gov.ph', 'Jocelyn  ', 'Dy', 'User', '', '', '2024-05-27 10:50:47', '', 'active'),
(47, 6430260, 'kristine.ebuenga', 'krisadrian', 'kristine.ebuenga@deped.gov.ph', 'Kristine  ', 'Ebuenga', 'User', '', '', '2024-05-27 10:23:17', '', 'active'),
(48, 4821313, 'irma.enaje', 'IbE62768', 'irma.enaje@deped.gov.ph', 'Irma  ', 'Enaje', 'User', '', '', '2024-05-27 10:24:18', '', 'active'),
(49, 4218819, 'melanie.encarnacion', 'MIRACLE@012817', 'melanie.dayto@deped.gov.ph', 'Melanie  ', 'Encarnacion', 'User', '', '', '2024-05-27 10:37:28', '', 'active'),
(50, 4821351, 'miarhea.fuentebella', 'Mercurydrug@550', 'miarhea.fuentebella@deped.gov.ph', 'Mia Rhea ', 'Fuentebella', 'User', '', '', '2024-05-27 10:33:30', '', 'active'),
(51, 4821350, 'catalina.garcia', 'Cathy@66', 'catalina.garcia@deped.gov.ph ', 'Catalina  ', 'Garcia', 'User', '', '', '2024-05-27 10:18:44', '', 'active'),
(52, 4821258, 'minerva.gayte', 'DROV@1234', 'minerva.gayte@deped.gov.ph', 'Minerva  ', 'Gayte', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(53, 4821339, 'rosaryann.gimenez', 'rosaryann1163_gim', 'rosary.gimenez@deped.gov.ph', 'Rosary Ann ', 'Gimenez', 'User', '', '', '2024-05-27 10:19:46', '', 'active'),
(54, 6313713, 'mayflormarie.jumamil', 'Jumamil@1977', 'mayflormarie.jumamil@deped.gov.ph', 'Mayflor Marie ', 'Jumamil', 'User', '', '', '2024-08-27 00:26:21', 0x75706c6f6164732f3432383033383131305f3337343732393234353236363739385f353430383530343636373436323438343134325f6e2e6a7067, 'active'),
(55, 4821259, 'joan.lagata', 'HEAVENMINE', 'joan.lagata@deped.gov.ph', 'Joan  ', 'Lagata', 'User', '', '', '2024-05-27 11:06:00', '', 'active'),
(56, 4821260, 'nora.laguda', 'deped@123', 'nora.laguda@deped.gov.ph', 'Nora  ', 'Laguda', 'User', '', '', '2024-08-26 23:36:47', '', 'active'),
(57, 6433401, 'remerlyn.latigay', 'Ramzters95', 'remerlyn.latigay@deped.gov.ph', 'Remerlyn  ', 'Latigay', 'User', '', '', '2024-05-27 10:23:48', '', 'active'),
(58, 5818663, 'karen.legson', 'admin@123', 'karen.legson@deped.gov.ph', 'Karen  ', 'Legson', 'User', 'Computer Programmer II', '', '2025-12-12 07:52:46', 0x75706c6f6164732f70726f66696c655f35385f313736353532353936362e706e67, 'active'),
(59, 4821298, 'rodel.lim', 'bebonglim', 'rodel.lim@deped.gov.ph', 'Rodel  ', 'Lim', 'User', '', '', '2024-08-28 19:38:40', '', 'active'),
(60, 4821316, 'ricardo.llantero', 'DROV@1234', 'ricardo.llantero@deped.gov.ph', 'Ricardo  ', 'Llantero', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(61, 4821248, 'basilisiojr..lleno', 'bass2024', 'basilisio.lleno@deped.gov.ph', 'Basilisio Jr. ', 'Lleno', 'User', '', '', '2024-05-27 10:24:01', '', 'active'),
(62, 4821283, 'mariaasuncion.longoria', '08151963', 'mariaasuncion.longoria@deped.gov.ph', 'Maria Asuncion ', 'Longoria', 'User', '', '', '2024-05-27 15:25:11', '', 'active'),
(63, 4821317, 'salvador.lopera', '11R7632s', 'salvador.lopera@deped.gov.ph', 'Salvador  ', 'Lopera', 'User', '', '', '2024-05-27 10:27:20', '', 'active'),
(64, 4821261, 'maleilani.lorico', 'Ezekiel5', 'ma.lorico@deped.gov.ph', 'Ma Leilani ', 'Lorico', 'User', '', '', '2024-05-27 10:21:34', '', 'active'),
(65, 4821368, 'janela.losito', 'ella21praisingGOD', 'janela.losito@deped.gov.ph', 'Janela  ', 'Losito', 'User', '', '', '2024-05-27 10:28:03', '', 'active'),
(66, 6430927, 'mischel.ludovice', 'DROV@1234', 'mischel.ludovice@deped.gov.ph', 'Mischel  ', 'Ludovice', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(67, 4516231, 'leo.madriaga', 'DROV@1234', 'leo.madriaga@deped.gov.ph', 'Leo  ', 'Madriaga', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(68, 4821242, 'mariarosaliavivien.maninang', 'DROV@1234', 'marosaliavivien.maninang@deped.gov.ph', 'Maria Rosalia Vivien', 'Maninang', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(69, 4821335, 'joy.margallo', 'happisadeped5', 'joy.barrameda@deped.gov.ph', 'Joy  ', 'Margallo', 'User', '', '', '2024-05-27 10:18:45', '', 'active'),
(70, 6430453, 'cheenee.mendina', '2013901091mdsR', 'cheenee.mendina@deped.gov.ph', 'Cheenee  ', 'Mendina', 'User', '', '', '2024-05-27 10:34:52', '', 'active'),
(71, 4540747, 'hallen.monreal', 'hallen26@1971', 'hallen.monreal001@deped.gov.ph', 'Hallen  ', 'Monreal', 'User', '', '', '2024-05-27 10:21:11', '', 'active'),
(72, 6323343, 'luisafe.montas', '30sEPTEMBER2014', 'luisafe.montas@deped.gov.ph', 'Luisa Fe ', 'Montas', 'User', '', '', '2024-05-27 10:59:50', '', 'active'),
(73, 4588465, 'antonio.morada', 'TONETTESHEENAFRANCE', 'antonio.morada@deped.gov.ph', 'Antonio  ', 'Morada', 'User', '', '', '2024-05-27 10:51:43', '', 'active'),
(74, 4821276, 'angelica.moral', 'Am180274', 'angelica.moral@deped.gov.ph', 'Angelica  ', 'Moral', 'User', '', '', '2024-05-27 10:32:42', '', 'active'),
(75, 4821273, 'daisy.moratalla', 'DROV@1234', 'daisy.moratalla@deped.gov.ph', 'Daisy  ', 'Moratalla', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(76, 4821274, 'deo.moreno', 'guapongarki@8', 'deo.moreno@deped.gov.ph', 'Deo  ', 'Moreno', 'User', '', '', '2024-05-27 10:18:27', '', 'active'),
(77, 4821275, 'arnulfo.naag', 'DROV@1234', 'arnulfo.naag@deped.gov.ph', 'Arnulfo  ', 'Naag', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(78, 4821290, 'sancha.nacion', 'EarlCjAa54321#', 'sancha.nacion@deped.gov.ph', 'Sancha  ', 'Nacion', 'User', '', '', '2024-05-27 10:18:27', '', 'active'),
(79, 4214395, 'thelma.navera', 'DROV@1234', 'thelma.navera@deped.gov.ph', 'Thelma  ', 'Navera', 'User', '', '', '2024-09-17 16:37:40', '', 'active'),
(80, 4821320, 'josenonato.nepomuceno', 'Njose_1234', 'jose.nepomuceno@deped.gov.ph', 'Jose Nonato ', 'Nepomuceno', 'User', '', '', '2024-05-27 11:35:37', '', 'active'),
(81, 4821343, 'freddirico.obo', 'DROV@1234', 'freddirico.obo@deped.gov.ph', 'Freddirico  ', 'Obo', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(82, 6431458, 'christilyn.ocbian', '987654321.tin', 'christilyn.ocbian@deped.gov.ph', 'Christilyn  ', 'Ocbian', 'User', '', '', '2024-06-04 09:15:47', '', 'active'),
(83, 4821291, 'priscilla.ombao', 'Tuyi1999', 'priscilla.ombao@deped.gov.ph', 'Priscilla  ', 'Ombao', 'User', '', '', '2024-05-27 10:30:48', '', 'active'),
(84, 5812149, 'jeffrey.pagatpat', 'wave125s', 'jeffrey.pagatpat@deped.gov.ph', 'Jeffrey  ', 'Pagatpat', 'User', '', '', '2024-05-27 12:39:15', '', 'active'),
(85, 4821292, 'alaster.palacio', 'procesajoy9176', 'alaster.palacio@deped.gov.ph', 'Alaster  ', 'Palacio', 'User', '', '', '2024-05-27 10:48:55', '', 'active'),
(86, 4520206, 'israel.parra', 'DROV@1234', 'israel.parra@deped.gov.ph', 'Israel  ', 'Parra', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(87, 4818693, 'michelle.pequena', 'michelle77', 'michelle.pequena@deped.gov.ph', 'Michelle  ', 'Pequena', 'User', '', '', '2024-05-27 10:29:43', '', 'active'),
(88, 4821278, 'casianojr..perdigones', 'Casper@1971', 'casiano.perdigones@deped.gov.ph', 'Casiano Jr. ', 'Perdigones', 'User', '', '', '2024-12-11 11:40:38', '', 'active'),
(89, 6429932, 'sheevamarie.porciuncula', '3sheev@m1', 'sheevamarie.porciuncula@deped.gov.ph', 'Sheeva Marie ', 'Porciuncula', 'User', '', '', '2024-08-26 23:22:25', '', 'active'),
(90, 4821265, 'grace.rabelas', 'Ecarg1972', 'grace.rabelas@deped.gov.ph', 'Grace  ', 'Rabelas', 'User', '', '', '2024-05-27 10:57:13', '', 'active'),
(91, 4821243, 'marites.rabulan', 'P@ssDepEd05', 'marites.rabulan@deped.gov.ph', 'Marites  ', 'Rabulan', 'User', '', '', '2024-05-27 10:29:57', '', 'active'),
(92, 5029900, 'andrew.raguero', 'Anzel120897.R', 'andrew.raguero@deped.gov.ph', 'Andrew  ', 'Raguero', 'User', '', '', '2024-08-26 23:23:20', '', 'active'),
(93, 4244600, 'roy.rapsing', 'DROV@1234', 'roy.rapsing001@deped.gov.ph', 'Roy  ', 'Rapsing', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(94, 4821323, 'aldrin.rellama', 'mamapaling28', 'aldrin.rellama@deped.gov.ph', 'Aldrin  ', 'Rellama', 'User', '', '', '2024-05-27 10:58:58', '', 'active'),
(95, 4821344, 'bernadette.robles', 'BmR$8362', 'bernadette.robles002@deped.gov.ph', 'Bernadette  ', 'Robles', 'User', '', '', '2024-12-11 03:38:05', '', 'active'),
(96, 4821251, 'rodolfo.robles', 'rbrobles', 'rodolfo.robles@deped.gov.ph', 'Rodolfo  ', 'Robles', 'User', '', '', '2024-05-27 10:54:20', '', 'active'),
(97, 4821347, 'zerjethrorodmell.roscuata', 'B@bylove0023', 'zerjethrorodmell.roscuata@deped.gov.ph', 'Zer Jethro Rodmell', 'Roscuata', 'User', '', '', '2024-05-27 10:32:06', '', 'active'),
(98, 4821277, 'evangeline.saculo', '123ReginA', 'evangeline.saculo@deped.gov.ph', 'Evangeline  ', 'Saculo', 'User', '', '', '2024-05-27 10:22:23', '', 'active'),
(99, 5007850, 'gilbert.sadsad', 'DROV@1234', 'gilbert.sadsad@deped.gov.ph', 'Gilbert  ', 'Sadsad', 'User', 'Regional Director', 'ORD', '0000-00-00 00:00:00', '', 'active'),
(100, 6431010, 'allanjulian.sapaula', 'All@n1685', 'allanjulian.sapaula@deped.gov.ph', 'Allan Julian ', 'Sapaula', 'User', '', '', '2024-05-27 12:32:20', '', 'active'),
(101, 4177881, 'joseph.sarza', 'josar@123', 'joseph.sarza@deped.gov.ph', 'Joseph  ', 'Sarza', 'User', '', '', '2024-05-27 13:48:38', '', 'active'),
(102, 4510336, 'bebiano.sentillas', 'sallitnes12021966', 'bebiano.sentillas@deped.gov.ph', 'Bebiano  ', 'Sentillas', 'Head', 'Asst. Regional Director', 'ORD', '2025-10-03 04:27:05', '', 'active'),
(103, 5826994, 'cathyrose.serrano', 'october0196', 'cathyrose.serrano@deped.gov.ph', 'Cathy Rose ', 'Serrano', 'User', '', '', '2024-05-27 10:57:49', '', 'active'),
(104, 4819118, 'domilyn.silerio', 'DROV@1234', 'domilyn.silerio@deped.gov.ph', 'Domilyn  ', 'Silerio', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(105, 6432282, 'raquel.supnet', 'supnet@1982', 'raquel.supnet@deped.gov.ph', 'Raquel  ', 'Supnet', 'User', '', '', '2024-06-04 09:13:26', '', 'active'),
(106, 4821267, 'ricardo.tejeresas', 'DROV@1234', 'ricardo.tejeresas@deped.gov.ph', 'Ricardo  ', 'Tejeresas', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(107, 4821268, 'marilou.tobongbanua', 'MARILOUDEPED', 'marilou.tobongbanua@deped.gov.ph', 'Marilou  ', 'Tobongbanua', 'User', '', '', '2024-05-27 10:20:39', '', 'active'),
(108, 5052280, 'paraluman.torregoza', 'lemuel@1968!', 'paraluman.torregoza@deped.gov.ph', 'Paraluman  ', 'Torregoza', 'User', '', '', '2024-05-27 10:26:47', '', 'active'),
(109, 4821326, 'maelena.torrentira', 'torrentira72', 'ma.torrentira@deped.gov.ph', 'Ma Elena ', 'Torrentira', 'User', '', '', '2024-05-27 10:28:22', '', 'active'),
(110, 4821367, 'sherwin.torres', 'DROV@1234', 'sherwin.torres001@deped.gov.ph', 'Sherwin  ', 'Torres', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(111, 4821346, 'roseann.tubig', 'roseann@1977', 'roseann.tubig@deped.gov.ph', 'Rose Ann ', 'Tubig', 'User', '', '', '2024-05-27 10:36:09', '', 'active'),
(112, 6430454, 'leslyn.tubongbanua', 'lslynorcne', 'leslyn.orcine@deped.gov.ph', 'Leslyn  ', 'Tubongbanua', 'User', '', '', '2024-05-27 10:27:38', '', 'active'),
(113, 4821345, 'ilya.vargas', 'depedBBilya', 'ilya.vargas@deped.gov.ph', 'Ilya  ', 'Vargas', 'User', '', '', '2024-08-26 23:59:51', '', 'active'),
(114, 4217547, 'santiagojacky.villafuerte', 'DROV@1234', 'santiagojacky.villafuerte@deped.gov.ph', 'Santiago Jacky', 'Villafuerte', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(115, 4821299, 'jocelyn.villanueva', 'kyle111213', 'jocelyn.villanueva@deped.gov.ph', 'Jocelyn  ', 'Villanueva', 'User', '', '', '2024-05-27 10:26:28', '', 'active'),
(116, 4821279, 'jinky.villareal', 'DROV@1234', 'jinky.villareal@deped.gov.ph', 'Jinky  ', 'Villareal', 'User', '', '', '0000-00-00 00:00:00', '', 'active'),
(118, 4128838, 'teresa.buasan', 't3r3s4buasan', 'teresa.buasan@deped.gov.ph', 'Teresa', 'Buasan', 'User', 'Education Program Supervisor', 'Curriculum and Learning Management Division\r\n', '2024-12-15 00:52:51', 0x75706c6f6164732f3435343932353739315f3339353039393635393935303735315f343032323437313534353937323433383638315f6e2e706e67, 'active'),
(119, 0, 'charlie.tayas', 'DROV@1234', 'charlie.tayas@deped.gov.ph', 'Charlie', 'Tayas', 'User', 'Special Education Program Supervisor', 'PPRD', '2024-09-17 20:10:41', NULL, 'active'),
(120, 3, 'hrdd', 'admin@123', 'hrdd.rov@deped.gov.ph', 'HRDD', 'ROV', 'LD', '', 'HRDD', '2025-12-01 01:39:25', NULL, NULL),
(121, 6491655, 'veronica.aguilar', 'DROV@1234', 'veronica.aguilar002@deped.gov.ph', 'Veronica', 'Aguilar', 'User', 'Accountant I', '', '2025-12-11 21:14:36', 0x4e554c4c, 'Created');

-- --------------------------------------------------------

--
-- Table structure for table `voluntary_work`
--

CREATE TABLE `voluntary_work` (
  `voluntaryworkID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `organizationName` text NOT NULL,
  `organizationAddress` text NOT NULL,
  `inclusiveDate` text NOT NULL,
  `numberOfHours` int(11) NOT NULL,
  `natureOfWork` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `voluntary_work`
--

INSERT INTO `voluntary_work` (`voluntaryworkID`, `employeeID`, `organizationName`, `organizationAddress`, `inclusiveDate`, `numberOfHours`, `natureOfWork`) VALUES
(1, 5818663, 'Boy Scouts of the Philippines', 'Ligao, Albay', '2017-09-10 to present', 120, 'Scout Patrol'),
(4, 5818663, 'Simone Davis', 'Eaque quidem sunt in', '1984-10-30 to 1988-07-23', 88, 'Debitis anim laudant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressID`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `awardlist`
--
ALTER TABLE `awardlist`
  ADD PRIMARY KEY (`awdID`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`awardID`);

--
-- Indexes for table `coc`
--
ALTER TABLE `coc`
  ADD PRIMARY KEY (`cocID`);

--
-- Indexes for table `coc_tbl`
--
ALTER TABLE `coc_tbl`
  ADD PRIMARY KEY (`cocTypeID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`officeID`);

--
-- Indexes for table `dependents`
--
ALTER TABLE `dependents`
  ADD PRIMARY KEY (`dependentID`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`educationID`);

--
-- Indexes for table `eligibility`
--
ALTER TABLE `eligibility`
  ADD PRIMARY KEY (`eligibilityID`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employeeID`);

--
-- Indexes for table `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`familyID`);

--
-- Indexes for table `govid`
--
ALTER TABLE `govid`
  ADD PRIMARY KEY (`govID`);

--
-- Indexes for table `learning_development`
--
ALTER TABLE `learning_development`
  ADD PRIMARY KEY (`trainingID`);

--
-- Indexes for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD PRIMARY KEY (`leaveID`);

--
-- Indexes for table `leave_card`
--
ALTER TABLE `leave_card`
  ADD PRIMARY KEY (`leaveRecordID`);

--
-- Indexes for table `leave_tbl`
--
ALTER TABLE `leave_tbl`
  ADD PRIMARY KEY (`leaveTypeID`);

--
-- Indexes for table `nosa`
--
ALTER TABLE `nosa`
  ADD PRIMARY KEY (`nosaID`);

--
-- Indexes for table `nosi`
--
ALTER TABLE `nosi`
  ADD PRIMARY KEY (`nosiID`);

--
-- Indexes for table `other_info`
--
ALTER TABLE `other_info`
  ADD PRIMARY KEY (`otherInfoID`);

--
-- Indexes for table `performance`
--
ALTER TABLE `performance`
  ADD PRIMARY KEY (`performanceID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`positionID`);

--
-- Indexes for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`questionnaireID`);

--
-- Indexes for table `reference_person`
--
ALTER TABLE `reference_person`
  ADD PRIMARY KEY (`refPersonID`);

--
-- Indexes for table `salary_grade`
--
ALTER TABLE `salary_grade`
  ADD PRIMARY KEY (`salaryGradeID`) USING BTREE;

--
-- Indexes for table `scholarship`
--
ALTER TABLE `scholarship`
  ADD PRIMARY KEY (`scholarshipID`);

--
-- Indexes for table `service_record`
--
ALTER TABLE `service_record`
  ADD PRIMARY KEY (`workRecordID`);

--
-- Indexes for table `sr_request`
--
ALTER TABLE `sr_request`
  ADD PRIMARY KEY (`srRequestID`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`tID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `voluntary_work`
--
ALTER TABLE `voluntary_work`
  ADD PRIMARY KEY (`voluntaryworkID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `awardlist`
--
ALTER TABLE `awardlist`
  MODIFY `awdID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `awardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coc`
--
ALTER TABLE `coc`
  MODIFY `cocID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coc_tbl`
--
ALTER TABLE `coc_tbl`
  MODIFY `cocTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `officeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `dependents`
--
ALTER TABLE `dependents`
  MODIFY `dependentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `educationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `eligibility`
--
ALTER TABLE `eligibility`
  MODIFY `eligibilityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `family`
--
ALTER TABLE `family`
  MODIFY `familyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `govid`
--
ALTER TABLE `govid`
  MODIFY `govID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `learning_development`
--
ALTER TABLE `learning_development`
  MODIFY `trainingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `leave_card`
--
ALTER TABLE `leave_card`
  MODIFY `leaveRecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `leave_tbl`
--
ALTER TABLE `leave_tbl`
  MODIFY `leaveTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `nosa`
--
ALTER TABLE `nosa`
  MODIFY `nosaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `nosi`
--
ALTER TABLE `nosi`
  MODIFY `nosiID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_info`
--
ALTER TABLE `other_info`
  MODIFY `otherInfoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `performance`
--
ALTER TABLE `performance`
  MODIFY `performanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `positionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `questionnaireID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `reference_person`
--
ALTER TABLE `reference_person`
  MODIFY `refPersonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_record`
--
ALTER TABLE `service_record`
  MODIFY `workRecordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `voluntary_work`
--
ALTER TABLE `voluntary_work`
  MODIFY `voluntaryworkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

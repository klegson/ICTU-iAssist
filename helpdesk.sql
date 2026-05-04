-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 30, 2026 at 07:58 AM
-- Server version: 8.4.2
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `ticket`;
DROP TABLE IF EXISTS `notification`;
DROP TABLE IF EXISTS `starlink`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `department`;
DROP TABLE IF EXISTS `position`;
DROP TABLE IF EXISTS `category`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `categoryId` int NOT NULL,
  `categoryName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `categoryType` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`, `categoryType`) VALUES
(5, 'Computer Malfunction', 'Hardware Problems'),
(6, 'Hardware Problem', 'Hardware Problems'),
(7, 'Printer / Scanner Problem', 'Hardware Problems'),
(8, 'Software bugs or glitches', 'Software Problems'),
(9, 'Incompatibility issues', 'Software Problems'),
(10, 'Software Updates/Installations', 'Software Problems'),
(11, 'Network Installation', 'Network Problems'),
(12, 'Network outages or downtime', 'Network Problems'),
(13, 'Difficulty accessing network', 'Network Problems'),
(14, 'Graphic Design Solution', 'Others'),
(21, 'Creation', 'Account Services'),
(22, 'Retention', 'Account Services'),
(23, 'Reset', 'Account Services'),
(24, 'Transfer', 'Account Services'),
(25, 'Deletion', 'Account Services');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `departmentId` int NOT NULL,
  `departmentCode` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `departmentName` text NOT NULL,
  `section_unit` text NOT NULL,
  `departmentHead` text NOT NULL,
  `positionID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`departmentId`, `departmentCode`, `departmentName`, `section_unit`, `departmentHead`, `positionID`) VALUES
(1, 'AD-AMS', 'Administrative Division', 'Asset Management Section', '4217547', NULL),
(2, 'AD-GSU', 'Administrative Division', 'General Services Unit', '4821305', NULL),
(3, 'AD-PSU', 'Administrative Division', 'Payroll Services Unit', '4217547', NULL),
(4, 'AD-RECORDS', 'Administrative Division', 'Records Section', '4821344', NULL),
(5, 'AD-PERSONNEL', 'Administrative Division', 'Personnel Section', '4821307', NULL),
(6, 'AD-CASH', 'Administrative Division', 'Cash Section', '4821330', NULL),
(7, 'AD', 'Administrative Division', '', '4821278', NULL),
(8, 'CLMD-LRMS', 'Curriculum and Learning Management Division', 'Learning Resource Management Section', '4821265', NULL),
(9, 'CLMD', 'Curriculum and Learning Management Division', '', '4821265', NULL),
(10, 'ESSD-HN', 'Education Support Services Division', 'Health and Nutrition', '4821259', NULL),
(11, 'ESSD-PP', 'Education Support Services Division', 'Programs and Projects', '4821259', NULL),
(12, 'ESSD-FAC', 'Education Support Services Division', 'Facilities', '4821259', NULL),
(13, 'ESSD', 'Education Support Services Division', '', '4821259', NULL),
(14, 'FTAD', 'Field Technical Assistance Division', '', '4821278', NULL),
(15, 'FD-BUDGET', 'Finance Division', 'Budget Section', '4821345', NULL),
(16, 'FD-ACCOUNTING', 'Finance Division', 'Accounting Section', '4821335', NULL),
(17, 'FD', 'Finance Division', '', '4821346', NULL),
(18, 'HRDD', 'Human Resource Development Division', '', '4821290', NULL),
(19, 'HRDD-NEAP', 'Human Resource Development Division', 'NEAP', '4821290', NULL),
(20, 'ARD', 'Office of the Assistant Regional Director', '', '5007850', NULL),
(21, 'ORD-PROCUREMENT', 'Office of the Regional Director', 'Procurement Unit', '5812149', NULL),
(22, 'ORD-ICT', 'Office of the Regional Director', 'Information and Communications Technology Unit', '4821247', NULL),
(23, 'ORD-PAU', 'Office of the Regional Director', 'Public Affairs Unit', '6313713', NULL),
(24, 'ORD-LEGAL', 'Office of the Regional Director', 'Legal Unit', '4819118', NULL),
(25, 'ORD', 'Office of the Regional Director', '', '4510336', NULL),
(26, 'PPRD', 'Policy Planning and Research Division', '', '4821294', NULL),
(27, 'QAD', 'Quality Assurance Division', '', '4821284', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `positionID` int NOT NULL,
  `positionTitle` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notifId` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `userId` int NOT NULL,
  `isRead` tinyint(1) DEFAULT '0',
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notifId`, `message`, `userId`, `isRead`, `createdAt`) VALUES
(1, 'A new ticket (#21) has been submitted and requires review.', 2, 1, '2026-03-19 15:18:54'),
(2, 'You have been assigned to a new task: Ticket #21.', 3, 1, '2026-03-19 15:25:52'),
(3, 'Ticket #21 was resolved by a technician and awaits your final approval.', 2, 1, '2026-03-19 15:27:09'),
(4, 'Your Ticket #21 has been successfully completed and closed.', 1, 1, '2026-03-19 15:27:57'),
(5, 'A new ticket (#22) has been submitted and requires review.', 2, 0, '2026-03-23 00:48:15'),
(6, 'You have been assigned to a new task: Ticket #22.', 3, 0, '2026-03-23 00:48:51'),
(7, 'A new ticket (#23) has been submitted and requires review.', 2, 0, '2026-03-23 07:15:17'),
(8, 'You have been assigned to a new task: Ticket #23.', 3, 0, '2026-03-23 07:16:27');

-- --------------------------------------------------------

--
-- Table structure for table `starlink`
--

CREATE TABLE `starlink` (
  `eventId` int NOT NULL,
  `reference_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `userId` int NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Pending','Approved','Rejected','Returned') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticketId` int NOT NULL,
  `subject` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `priority` enum('Low','Medium','High') COLLATE utf8mb4_general_ci DEFAULT 'Medium',
  `status` enum('Pending','Processing','Resolved','Completed') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `remarks` text COLLATE utf8mb4_general_ci,
  `userId` int NOT NULL,
  `departmentId` int NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `assignedTo` int DEFAULT NULL,
  `categoryId` int DEFAULT NULL,
  `technician_signature` longtext COLLATE utf8mb4_general_ci,
  `resolvedBy` int DEFAULT NULL,
  `resolvedAt` datetime DEFAULT NULL,
  `completedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticketId`, `subject`, `description`, `priority`, `status`, `remarks`, `userId`, `departmentId`, `createdAt`, `updatedAt`, `assignedTo`, `categoryId`, `technician_signature`) VALUES
(19, 'Quis est dolor a li', 'Delectus aliquip in', 'Low', 'Completed', 'Do this later', 1, 1, '2026-03-19 13:47:49', '2026-03-19 22:05:08', 3, 11, 'data:image/png;base64,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'),
(20, 'Esse vel vel ut est ', 'Laborum In sunt lib', 'High', 'Resolved', 'hello', 1, 1, '2026-03-19 14:06:45', '2026-03-19 23:13:00', 3, 6, 'data:image/png;base64,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'),
(21, 'Qui officia cumque q', 'Mollit dolore eiusmo', 'Medium', 'Completed', 'ok na raw', 1, 1, '2026-03-19 15:18:54', '2026-03-19 23:27:57', 3, 11, 'data:image/png;base64,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'),
(22, 'Autem perferendis pr', 'Aut rem dolore cum a', 'High', 'Processing', 'pls take charge', 1, 1, '2026-03-23 00:48:15', '2026-03-23 15:14:36', 3, 6, NULL),
(23, 'Architecto quaerat d', 'Distinctio Iusto of', 'Medium', 'Processing', 'pls', 1, 1, '2026-03-23 07:15:17', '2026-03-23 15:20:25', 3, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int NOT NULL,
  `employeeId` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('User','Officer','Technician') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `positionID` int DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `firstName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `middleName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `suffix` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profilePicture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `departmentId` int NOT NULL,
  `divisionId` int DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `signature` longtext COLLATE utf8mb4_general_ci,
  `isActive` tinyint(1) DEFAULT '1',
  `activationToken` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tokenExpiresAt` datetime DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT '0',
  `googleId` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatarUrl` varchar(512) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `employeeId`, `email`, `role`, `positionID`, `password`, `firstName`, `middleName`, `lastName`, `suffix`, `phone`, `profilePicture`, `departmentId`, `divisionId`, `createdAt`, `signature`, `isActive`, `activationToken`, `tokenExpiresAt`, `isApproved`, `googleId`, `avatarUrl`) VALUES
(1, NULL, 'employee@deped.gov.ph', 'User', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gabriel', NULL, 'Reyes', NULL, NULL, NULL, 1, NULL, '2026-02-12 07:34:33', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAjsAAAEnCAYAAABPKoImAAAQAElEQVR4AezdX+h9X17f95NqyKR10kmj7QzMVIVchEJhpLQZy8iYi6YjtNSgIeOF1ZCU5M4ZcJjclHGgBQeF6FULbTBCwYEqOvSPKV4YScIYaImQC1uaYqyCAxmiSW0zrRq7Ht/5vr+/9Vnfvc/Z5//e57y+7Pd3/V/rvZ5r7/V+f/be55x/YZd/IRACIRACIRACIXDABOLsPPDiZmohEAIhEALHEEjdRyUQZ+dRVzbzCoEQCIEQCIEQeEUgzs4rDPkvBEIgBJYTSM0QCIEQWAuBODtbr2gbAiEQAiEQAiFwVQJxdq6KN52HQAgsJ5CaIRACIXAdAnF2rsM1vYZACIRACIRACKyEQJydlSxE1FhOIDVDIARCIARC4BgCcXaOoZW6IRACIRACIRACmyPwwM7O5tYiCodACIRACIRACFyBQJydK0BNlyEQAiEQAiGwKgJPrkycnSc/ATL9EAiBEAiBEHh0AnF2Hn2FM78QCIEQWE4gNUPgIQnE2XnIZc2kQiAEQiAEQiAEikCcnSKRMARCYDmB1AyBEAiBDRGIs7OhxYqqIRACIRACIRACxxOIs3M8s7RYTiA1QyAEQiAEQuDuBOLs3H0JokAIhEAIhEAIhcB1CcTZuRrWdBoCIZAcgTg7h7y8mTwEQiAEQiAEQmDrBOLE2TmG1jsEQuDpCcTZ6eulbQiEQAiEQAiEwHUJxNm5Gtp1j9Zi4uw8/PJm8hAIgRAIgRAIgU0TiBNn5xhW7xAIgaen8QIhyrNP0QAAAABJRU5ErkJggg==', 1, NULL, NULL, 1, NULL, NULL),
(2, NULL, 'boss@deped.gov.ph', 'Officer', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carl', NULL, 'Dolino', NULL, NULL, NULL, 2, NULL, '2026-02-12 07:34:33', NULL, 1, NULL, NULL, 1, NULL, NULL),
(3, NULL, 'tech@deped.gov.ph', 'Technician', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bill', NULL, 'Variacion', NULL, NULL, NULL, 4, NULL, '2026-02-12 07:34:33', NULL, 1, NULL, NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`departmentId`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`positionID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notifId`),
  ADD KEY `fk_notification_users` (`userId`);

--
-- Indexes for table `starlink`
--
ALTER TABLE `starlink`
  ADD PRIMARY KEY (`eventId`),
  ADD UNIQUE KEY `reference_number` (`reference_number`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticketId`),
  ADD KEY `fk_ticket_users` (`userId`),
  ADD KEY `assignedTo` (`assignedTo`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `fk_ticket_department` (`departmentId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_department` (`departmentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `departmentId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `positionID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notifId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `starlink`
--
ALTER TABLE `starlink`
  MODIFY `eventId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ticketId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_ticket_department` FOREIGN KEY (`departmentId`) REFERENCES `department` (`departmentId`),
  ADD CONSTRAINT `fk_ticket_users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`assignedTo`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_department` FOREIGN KEY (`departmentId`) REFERENCES `department` (`departmentId`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_position` FOREIGN KEY (`positionID`) REFERENCES `position` (`positionID`) ON DELETE RESTRICT ON UPDATE CASCADE;
--
-- Constraints for table `department`
--

ALTER TABLE `department`
  ADD CONSTRAINT `fk_department_position` FOREIGN KEY (`positionID`) REFERENCES `position` (`positionID`) ON DELETE RESTRICT ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Safe SQL script to add position table and update existing tables
-- Run this in phpMyAdmin or via: mysql -u root -p helpdesk < add_position_safe.sql

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS=0;

-- Create position table (drop if exists)
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `positionID` int NOT NULL AUTO_INCREMENT,
  `positionTitle` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`positionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert position data
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

-- Add positionID column to users table if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'positionID') > 0,
    'SELECT "positionID column already exists in users table"',
    'ALTER TABLE `users` ADD COLUMN `positionID` int DEFAULT NULL AFTER `role`'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add phone column to users table if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'phone') > 0,
    'SELECT "phone column already exists in users table"',
    'ALTER TABLE `users` ADD COLUMN `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL AFTER `suffix`'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add profilePicture column to users table if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'profilePicture') > 0,
    'SELECT "profilePicture column already exists in users table"',
    'ALTER TABLE `users` ADD COLUMN `profilePicture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL AFTER `phone`'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add positionID column to department table if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'department' AND COLUMN_NAME = 'positionID') > 0,
    'SELECT "positionID column already exists in department table"',
    'ALTER TABLE `department` ADD COLUMN `positionID` int DEFAULT NULL AFTER `departmentHead`'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key constraint to users table (if not exists)
SET @constraint_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND CONSTRAINT_NAME = 'fk_users_position');
SET @sql = IF(@constraint_exists = 0,
    'ALTER TABLE `users` ADD CONSTRAINT `fk_users_position` FOREIGN KEY (`positionID`) REFERENCES `position` (`positionID`) ON DELETE RESTRICT ON UPDATE CASCADE',
    'SELECT "fk_users_position constraint already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key constraint to department table (if not exists)
SET @constraint_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'department' AND CONSTRAINT_NAME = 'fk_department_position');
SET @sql = IF(@constraint_exists = 0,
    'ALTER TABLE `department` ADD CONSTRAINT `fk_department_position` FOREIGN KEY (`positionID`) REFERENCES `position` (`positionID`) ON DELETE RESTRICT ON UPDATE CASCADE',
    'SELECT "fk_department_position constraint already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;

SELECT 'Position table and columns added successfully!' AS message;

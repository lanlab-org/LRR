-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2019 at 05:08 AM
-- Server version: 5.7.17
-- PHP Version: 7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lrr`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `count_submissions` (OUT `s_count` DECIMAL)  BEGIN
    select count(Student_id) into s_count from lab_report_submissions;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllListings` ()  BEGIN
 SELECT nid, type, title  FROM node where type = 'lms_listing' order by nid desc;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courses_table`
--

CREATE TABLE `courses_table` (
  `Course_ID` int(11) NOT NULL,
  `Course_Name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Academic_Year` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Faculty` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Lecturer_User_ID` int(11) DEFAULT NULL,
  `TA_User_ID` int(11) DEFAULT NULL,
  `Course_Code` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `URL` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `Verify_New_Members` varchar(10) COLLATE utf8mb4_bin NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `courses_table`
--

INSERT INTO `courses_table` (`Course_ID`, `Course_Name`, `Academic_Year`, `Faculty`, `Lecturer_User_ID`, `TA_User_ID`, `Course_Code`, `URL`, `Verify_New_Members`) VALUES
(10, 'Software Engineering', '2018', 'Computing', 8, 0, 'CSC1234', 'CSC12342018', '1'),
(11, 'Project Management', '2019', 'Computing', 8, 0, 'P.M2019', 'P.M20192019', '0');

-- --------------------------------------------------------

--
-- Table structure for table `course_groups_table`
--

CREATE TABLE `course_groups_table` (
  `Course_Group_id` int(11) NOT NULL,
  `Group_Name` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Group_Leader` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `Course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `course_groups_table`
--

INSERT INTO `course_groups_table` (`Course_Group_id`, `Group_Name`, `Group_Leader`, `Course_id`) VALUES
(1, 'Group 1', '201825800050', 10);

-- --------------------------------------------------------

--
-- Table structure for table `course_group_members_table`
--

CREATE TABLE `course_group_members_table` (
  `ID` int(11) NOT NULL,
  `Course_Group_id` int(11) DEFAULT NULL,
  `Student_ID` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `course_group_members_table`
--

INSERT INTO `course_group_members_table` (`ID`, `Course_Group_id`, `Student_ID`, `Status`) VALUES
(1, 1, '201825800050', 'Created');

-- --------------------------------------------------------

--
-- Table structure for table `course_students_table`
--

CREATE TABLE `course_students_table` (
  `Course_ID` int(11) NOT NULL,
  `Student_ID` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `Status` varchar(100) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `course_students_table`
--

INSERT INTO `course_students_table` (`Course_ID`, `Student_ID`, `ID`, `Status`) VALUES
(9, '201825800050', 12, 'Joined'),
(10, '201825800050', 13, 'Joined'),
(10, '201825800054', 14, 'Joined');

-- --------------------------------------------------------

--
-- Table structure for table `course_ta`
--

CREATE TABLE `course_ta` (
  `Course_ID` int(11) NOT NULL,
  `TA` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `course_ta`
--

INSERT INTO `course_ta` (`Course_ID`, `TA`) VALUES
(10, 11),
(10, 10),
(11, 10);

-- --------------------------------------------------------

--
-- Table structure for table `extended_deadlines_table`
--

CREATE TABLE `extended_deadlines_table` (
  `ID` int(11) NOT NULL,
  `Student_ID` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `Lab_Report_ID` int(11) DEFAULT NULL,
  `Extended_Deadline_Date` date DEFAULT NULL,
  `ReasonsForExtension` longtext COLLATE utf8mb4_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `lab_reports_table`
--

CREATE TABLE `lab_reports_table` (
  `Lab_Report_ID` int(11) NOT NULL,
  `Course_ID` int(11) DEFAULT NULL,
  `Posted_Date` varchar(1000) COLLATE utf8mb4_bin DEFAULT NULL,
  `Deadline` varchar(1000) COLLATE utf8mb4_bin DEFAULT NULL,
  `Instructions` longtext COLLATE utf8mb4_bin,
  `Title` longtext COLLATE utf8mb4_bin,
  `Attachment_link_1` longtext COLLATE utf8mb4_bin,
  `Attachment_link_2` longtext COLLATE utf8mb4_bin,
  `Attachment_link_3` longtext COLLATE utf8mb4_bin,
  `Attachment_link_4` longtext COLLATE utf8mb4_bin,
  `Marks` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL,
  `Type` varchar(30) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `lab_reports_table`
--

INSERT INTO `lab_reports_table` (`Lab_Report_ID`, `Course_ID`, `Posted_Date`, `Deadline`, `Instructions`, `Title`, `Attachment_link_1`, `Attachment_link_2`, `Attachment_link_3`, `Attachment_link_4`, `Marks`, `Type`) VALUES
(1, 10, '2019-01-11 16:52', '2019-02-11 17:00', 'Description of the lab....', 'Reading 1', '700IMPORTANT WORDS.txt', '', '', '', '4', 'Individual'),
(2, 10, '2019-01-17 11:12', '2019-01-25 23:59', 'Read this paper http://sunnyday.mit.edu/16.355/budgen-david.pdf', 'Reading 2', '586LRR-Test-caseS.pdf', '', '', '', '6', 'Individual');

-- --------------------------------------------------------

--
-- Table structure for table `lab_report_submissions`
--

CREATE TABLE `lab_report_submissions` (
  `Submission_ID` int(11) NOT NULL,
  `Submission_Date` datetime DEFAULT NULL,
  `Lab_Report_ID` int(11) DEFAULT NULL,
  `Student_id` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `Course_Group_id` int(11) DEFAULT NULL,
  `Attachment1` longtext COLLATE utf8mb4_bin,
  `Notes` longtext COLLATE utf8mb4_bin,
  `Attachment2` varchar(1000) COLLATE utf8mb4_bin NOT NULL,
  `Attachment3` varchar(1000) COLLATE utf8mb4_bin NOT NULL,
  `Attachment4` varchar(1000) COLLATE utf8mb4_bin NOT NULL,
  `Marks` double DEFAULT NULL,
  `Status` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `Title` varchar(500) COLLATE utf8mb4_bin NOT NULL,
  `Visibility` varchar(30) COLLATE utf8mb4_bin NOT NULL DEFAULT 'Private'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `lab_report_submissions`
--

INSERT INTO `lab_report_submissions` (`Submission_ID`, `Submission_Date`, `Lab_Report_ID`, `Student_id`, `Course_Group_id`, `Attachment1`, `Notes`, `Attachment2`, `Attachment3`, `Attachment4`, `Marks`, `Status`, `Title`, `Visibility`) VALUES
(1, '2019-01-17 00:00:00', 1, '201825800050', 0, 'Reading list.txt', '-', '', '', '', 5, 'Marked', 'Reading 1 submission', 'Public'),
(5, '2019-01-21 08:31:00', 2, '201825800050', 0, 'Trial Balance.txt', ' - @2019-01-21 09:35 : Sorry I missed some details from your report', 'Boorka.jpg', '', '', 6, 'Marked', 'Submission x', 'Private'),
(6, '2019-01-21 09:31:00', 2, '201825800054', 0, 'Mohamed-201825800050-Backup & Recovery Report.pdf', '@2019-01-21 09:34 : Good work', 'Mohamed-201825800050-Database Replication Report.pdf', '', '', 4, 'Marked', 'My Submission for reading 2', 'Private');

-- --------------------------------------------------------

--
-- Table structure for table `students_data`
--

CREATE TABLE `students_data` (
  `ID` int(11) NOT NULL,
  `Student_ID` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Passport_Number` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `students_data`
--

INSERT INTO `students_data` (`ID`, `Student_ID`, `Passport_Number`) VALUES
(1, '201825800054', 'LJ7951632'),
(2, '201825800050', 'P00581929');

-- --------------------------------------------------------

--
-- Table structure for table `users_table`
--

CREATE TABLE `users_table` (
  `User_ID` int(11) NOT NULL,
  `Email` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Password` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Full_Name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `UserType` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Student_ID` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `Passport_Number` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `Status` varchar(30) COLLATE utf8mb4_bin NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users_table`
--

INSERT INTO `users_table` (`User_ID`, `Email`, `Password`, `Full_Name`, `UserType`, `Student_ID`, `Passport_Number`, `Status`) VALUES
(3, 'admin@qq.com', '123', 'Kamal', 'Admin', '0', NULL, 'Active'),
(8, 'lanhui@qq.com', '1234', 'Lanhui', 'Lecturer', NULL, '123', 'Active'),
(9, 'mohamed@qq.com', '123', 'Mohamed', 'Student', '201825800050', 'P00581929', 'Active'),
(10, 'mark@qq.com', '123', 'Mark ', 'TA', NULL, '123', 'Active'),
(11, 'john@qq.com', '123', 'John', 'TA', NULL, '123', 'Active'),
(12, 'mehdi@qq.com', '123', 'El-mehdi Houzi', 'Student', '201825800054', 'LJ7951632', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses_table`
--
ALTER TABLE `courses_table`
  ADD PRIMARY KEY (`Course_ID`);

--
-- Indexes for table `course_groups_table`
--
ALTER TABLE `course_groups_table`
  ADD PRIMARY KEY (`Course_Group_id`),
  ADD UNIQUE KEY `Group_Name` (`Group_Name`);

--
-- Indexes for table `course_group_members_table`
--
ALTER TABLE `course_group_members_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `course_students_table`
--
ALTER TABLE `course_students_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `extended_deadlines_table`
--
ALTER TABLE `extended_deadlines_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `lab_reports_table`
--
ALTER TABLE `lab_reports_table`
  ADD PRIMARY KEY (`Lab_Report_ID`);

--
-- Indexes for table `lab_report_submissions`
--
ALTER TABLE `lab_report_submissions`
  ADD PRIMARY KEY (`Submission_ID`);

--
-- Indexes for table `students_data`
--
ALTER TABLE `students_data`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses_table`
--
ALTER TABLE `courses_table`
  MODIFY `Course_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `course_groups_table`
--
ALTER TABLE `course_groups_table`
  MODIFY `Course_Group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `course_group_members_table`
--
ALTER TABLE `course_group_members_table`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `course_students_table`
--
ALTER TABLE `course_students_table`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `extended_deadlines_table`
--
ALTER TABLE `extended_deadlines_table`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lab_reports_table`
--
ALTER TABLE `lab_reports_table`
  MODIFY `Lab_Report_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lab_report_submissions`
--
ALTER TABLE `lab_report_submissions`
  MODIFY `Submission_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `students_data`
--
ALTER TABLE `students_data`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users_table`
--
ALTER TABLE `users_table`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

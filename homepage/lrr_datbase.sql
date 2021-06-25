/*
Navicat MySQL Data Transfer

Source Server         : centosMySQL
Source Server Version : 80015
Source Host           : 121.5.38.33:8255
Source Database       : lrr

Target Server Type    : MYSQL
Target Server Version : 80015
File Encoding         : 65001

Date: 2021-06-25 08:50:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for course_group_members_table
-- ----------------------------
DROP TABLE IF EXISTS `course_group_members_table`;
CREATE TABLE `course_group_members_table` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Course_Group_id` int(11) NOT NULL,
  `Student_ID` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `Status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of course_group_members_table
-- ----------------------------
INSERT INTO `course_group_members_table` VALUES ('1', '1', '201825800050', 'Created');

-- ----------------------------
-- Table structure for course_groups_table
-- ----------------------------
DROP TABLE IF EXISTS `course_groups_table`;
CREATE TABLE `course_groups_table` (
  `Course_Group_id` int(11) NOT NULL AUTO_INCREMENT,
  `Group_Name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Group_Leader` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Course_id` int(11) NOT NULL,
  PRIMARY KEY (`Course_Group_id`),
  UNIQUE KEY `Group_Name` (`Group_Name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of course_groups_table
-- ----------------------------
INSERT INTO `course_groups_table` VALUES ('1', 'Group 1', '201825800050', '10');

-- ----------------------------
-- Table structure for course_students_table
-- ----------------------------
DROP TABLE IF EXISTS `course_students_table`;
CREATE TABLE `course_students_table` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Course_ID` int(11) NOT NULL,
  `Student_ID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `Status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of course_students_table
-- ----------------------------
INSERT INTO `course_students_table` VALUES ('12', '9', '201825800050', 'Joined');
INSERT INTO `course_students_table` VALUES ('13', '10', '201825800050', 'Joined');
INSERT INTO `course_students_table` VALUES ('14', '10', '201825800054', 'Joined');
INSERT INTO `course_students_table` VALUES ('15', '11', '201831990236', 'Joined');

-- ----------------------------
-- Table structure for course_ta
-- ----------------------------
DROP TABLE IF EXISTS `course_ta`;
CREATE TABLE `course_ta` (
  `Course_ID` int(11) NOT NULL,
  `TA` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of course_ta
-- ----------------------------
INSERT INTO `course_ta` VALUES ('10', '11');
INSERT INTO `course_ta` VALUES ('10', '10');
INSERT INTO `course_ta` VALUES ('11', '10');

-- ----------------------------
-- Table structure for courses_table
-- ----------------------------
DROP TABLE IF EXISTS `courses_table`;
CREATE TABLE `courses_table` (
  `Course_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Course_Name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Academic_Year` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Faculty` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Lecturer_User_ID` int(11) DEFAULT NULL,
  `TA_User_ID` int(11) DEFAULT NULL,
  `Course_Code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `URL` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `Verify_New_Members` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'No',
  PRIMARY KEY (`Course_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of courses_table
-- ----------------------------
INSERT INTO `courses_table` VALUES ('10', 'Software Engineering', '2018', 'Computing', '8', '0', 'CSC1234', 'CSC12342018', '1');
INSERT INTO `courses_table` VALUES ('11', 'Project Management', '2019', 'Computing', '8', '0', 'P.M2019', 'P.M20192019', '0');

-- ----------------------------
-- Table structure for extended_deadlines_table
-- ----------------------------
DROP TABLE IF EXISTS `extended_deadlines_table`;
CREATE TABLE `extended_deadlines_table` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Student_ID` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Lab_Report_ID` int(11) DEFAULT NULL,
  `Extended_Deadline_Date` date DEFAULT NULL,
  `ReasonsForExtension` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of extended_deadlines_table
-- ----------------------------

-- ----------------------------
-- Table structure for lab_report_submissions
-- ----------------------------
DROP TABLE IF EXISTS `lab_report_submissions`;
CREATE TABLE `lab_report_submissions` (
  `Submission_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Submission_Date` datetime DEFAULT NULL,
  `Lab_Report_ID` int(11) DEFAULT NULL,
  `Student_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Course_Group_id` int(11) DEFAULT NULL,
  `Attachment1` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `Notes` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `Attachment2` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Attachment3` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Attachment4` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Marks` double DEFAULT NULL,
  `Status` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Visibility` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'Private',
  PRIMARY KEY (`Submission_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=373 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of lab_report_submissions
-- ----------------------------
INSERT INTO `lab_report_submissions` VALUES ('1', '2019-01-17 00:00:00', '1', '201825800050', '0', 'Reading list.txt', '-', '', '', '', '5', 'Marked', 'Reading 1 submission', 'Public');
INSERT INTO `lab_report_submissions` VALUES ('5', '2019-01-21 08:31:00', '2', '201825800050', '0', 'Trial Balance.txt', ' - @2019-01-21 09:35 : Sorry I missed some details from your report', 'Boorka.jpg', '', '', '6', 'Marked', 'Submission x', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('6', '2019-01-21 09:31:00', '2', '201825800054', '0', 'Mohamed-201825800050-Backup & Recovery Report.pdf', '@2019-01-21 09:34 : Good work', 'Mohamed-201825800050-Database Replication Report.pdf', '', '', '4', 'Marked', 'My Submission for reading 2', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('361', '2021-04-30 12:25:00', '13', '201825800050', '1', null, null, '[null,\"A\"]', '[null,[\"A\",\"B\",\"C\"]]', '[null,\"\\u9762\\u5411\\u5bf9\\u8c61\\u8bed\\u8a00\"]', '7', 'Marked', '第一次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('364', '2021-05-01 12:16:00', '17', '201831990236', '0', null, null, '[null,\"C\"]', '[null,[\"A\",\"B\",\"D\"]]', '[null,\"\\u673a\\u5668\\u8bed\\u8a00\"]', '5', 'Marked', '第3次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('365', '2021-06-21 22:40:00', '19', '201831990236', '0', null, null, '[null,\"C\"]', '[null,[\"A\",\"B\",\"C\"]]', '[null,\"\\u84dd\\u7070\"]', '7', 'Marked', '第三次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('366', '2021-06-22 20:25:00', '24', '201831990236', '0', null, null, '[null,\"B\"]', '[null,[\"A\",\"B\",\"C\"]]', '[null,\"\\u84dd\\u7070\"]', '10', 'Marked', '22号第一次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('367', '2021-06-22 20:42:00', '23', '201831990236', '0', null, null, '[null,\"B\"]', '[null,[\"A\",\"B\",\"C\"]]', '[null,\"\\u84dd\\u7070\"]', '8', 'Marked', '第四次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('368', '2021-06-22 23:02:00', '26', '201831990236', '0', null, null, '[null]', '[null]', '[null]', '0', 'Marked', '', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('369', '2021-06-23 20:58:00', '25', '201825800054', '0', null, null, '[null,\"B\"]', '[null]', '[null]', '0', 'Marked', '22号第二次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('370', '2021-06-24 10:30:00', '29', '201831990236', '0', null, null, '[null,\"C\"]', '[null,[\"A\",\"B\",\"D\"]]', '[null,\"\\u84dd\\u7070\"]', '10', 'Marked', '22号第三次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('371', '2021-06-24 11:02:00', '30', '201831990236', '0', null, null, '[null,\"C\"]', '[null,[\"A\",\"B\",\"D\"]]', '[null,\"\\u84dd\\u7070\"]', '29', 'Marked', '23号第三次测试', 'Private');
INSERT INTO `lab_report_submissions` VALUES ('372', '2021-06-24 21:12:00', '28', '201831990236', '0', null, null, '[null,\"A\"]', '[null,[\"A\"]]', '[null,\"$QmBCMiz(R\"]', '0', 'Marked', '23号第二次测试', 'Private');

-- ----------------------------
-- Table structure for lab_reports_table
-- ----------------------------
DROP TABLE IF EXISTS `lab_reports_table`;
CREATE TABLE `lab_reports_table` (
  `Lab_Report_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Course_ID` int(11) DEFAULT NULL,
  `Posted_Date` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Deadline` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Attachment_link_1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Attachment_link_2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Attachment_link_3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Attachment_link_4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Marks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`Lab_Report_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of lab_reports_table
-- ----------------------------
INSERT INTO `lab_reports_table` VALUES ('1', '10', '2019-01-11 16:52', '2019-02-11 17:00', 0x4465736372697074696F6E206F6620746865206C61622E2E2E2E, 0x52656164696E672031, 0x373030494D504F5254414E5420574F5244532E747874, '', '', '', '4', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('2', '10', '2019-01-17 11:12', '2019-01-25 23:59', 0x52656164207468697320706170657220687474703A2F2F73756E6E796461792E6D69742E6564752F31362E3335352F62756467656E2D64617669642E706466, 0x52656164696E672032, 0x3538364C52522D546573742D63617365532E706466, '', '', '', '6', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('13', '10', '2021-04-29 21:21', '2021-04-29 21:41', 0x7175697A, 0xE7ACACE4B880E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F435343313233345FE7ACACE4B880E6ACA1E6B58BE8AF955F313631393730323438342E747874, 0x2E2F66696C652F73636F72652F616E737765725F435343313233345FE7ACACE4B880E6ACA1E6B58BE8AF955F313631393730323438342E747874, null, null, '9', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('16', '11', '2021-04-30 21:19', '2021-04-30 21:25', 0x7175697A, 0xE7ACAC32E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F435343313233345FE7ACAC32E6ACA1E6B58BE8AF955F313631393738383736372E747874, 0x2E2F66696C652F73636F72652F616E737765725F435343313233345FE7ACAC32E6ACA1E6B58BE8AF955F313631393738383736372E747874, null, null, '6', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('17', '11', '2021-05-01 10:50', '2021-05-03 10:50', 0x7175697A, 0xE7ACAC33E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395FE7ACAC33E6ACA1E6B58BE8AF955F313631393833373435372E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395FE7ACAC33E6ACA1E6B58BE8AF955F313631393833373435372E747874, null, null, '8', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('19', '11', '2021-06-21 22:39', '2021-07-11 22:39', 0x7175697A, 0xE7ACACE4B889E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395FE7ACACE4B889E6ACA1E6B58BE8AF955F313632343238363339342E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395FE7ACACE4B889E6ACA1E6B58BE8AF955F313632343238363339342E747874, null, null, '10', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('23', '11', '2021-06-22 20:10', '2021-07-22 20:09', 0x7175697A, 0xE7ACACE59B9BE6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395FE7ACACE59B9BE6ACA1E6B58BE8AF955F313632343336333830342E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395FE7ACACE59B9BE6ACA1E6B58BE8AF955F313632343336333830342E747874, null, null, '10', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('24', '11', '2021-06-22 20:24', '2021-07-12 20:24', 0x7175697A, 0x3232E58FB7E7ACACE4B880E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395F3232E58FB7E7ACACE4B880E6ACA1E6B58BE8AF955F313632343336343639382E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395F3232E58FB7E7ACACE4B880E6ACA1E6B58BE8AF955F313632343336343639382E747874, null, null, '10', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('25', '10', '2021-06-22 20:50', '2021-06-27 20:50', 0x7175697A, 0x3232E58FB7E7ACACE4BA8CE6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F435343313233345F3232E58FB7E7ACACE4BA8CE6ACA1E6B58BE8AF955F313632343336363231372E747874, 0x2E2F66696C652F73636F72652F616E737765725F435343313233345F3232E58FB7E7ACACE4BA8CE6ACA1E6B58BE8AF955F313632343336363231382E747874, null, null, '2', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('26', '11', '2021-06-22 21:02', '2021-07-10 21:01', 0x7175697A, 0x3232E58FB7E4B8B4E697B6E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395F3232E58FB7E4B8B4E697B6E6B58BE8AF955F313632343336363932392E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395F3232E58FB7E4B8B4E697B6E6B58BE8AF955F313632343336363933302E747874, null, null, '10', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('27', '10', '2021-06-23 21:00', '2021-06-27 20:59', 0x7175697A, 0x3233E58FB7E7ACACE4B880E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F435343313233345F3233E58FB7E7ACACE4B880E6ACA1E6B58BE8AF955F313632343435333231302E747874, 0x2E2F66696C652F73636F72652F616E737765725F435343313233345F3233E58FB7E7ACACE4B880E6ACA1E6B58BE8AF955F313632343435333231302E747874, null, null, '3', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('28', '11', '2021-06-24 08:45', '2021-07-31 08:45', 0x7175697A, 0x3233E58FB7E7ACACE4BA8CE6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395F3233E58FB7E7ACACE4BA8CE6ACA1E6B58BE8AF955F313632343439353535322E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395F3233E58FB7E7ACACE4BA8CE6ACA1E6B58BE8AF955F313632343439353535322E747874, null, null, '10', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('29', '11', '2021-06-24 10:29', '2021-07-30 10:29', 0x7175697A, 0x3232E58FB7E7ACACE4B889E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395F3232E58FB7E7ACACE4B889E6ACA1E6B58BE8AF955F313632343530313738302E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395F3232E58FB7E7ACACE4B889E6ACA1E6B58BE8AF955F313632343530313738302E747874, null, null, '10', 'Individual');
INSERT INTO `lab_reports_table` VALUES ('30', '11', '2021-06-24 11:01', '2021-07-31 11:01', 0x7175697A, 0x3233E58FB7E7ACACE4B889E6ACA1E6B58BE8AF95, 0x2E2F66696C652F7175697A7A65732F502E4D323031395F3233E58FB7E7ACACE4B889E6ACA1E6B58BE8AF955F313632343530333638322E747874, 0x2E2F66696C652F73636F72652F616E737765725F502E4D323031395F3233E58FB7E7ACACE4B889E6ACA1E6B58BE8AF955F313632343530333638322E747874, null, null, '29', 'Individual');

-- ----------------------------
-- Table structure for students_data
-- ----------------------------
DROP TABLE IF EXISTS `students_data`;
CREATE TABLE `students_data` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Student_ID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Passport_Number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of students_data
-- ----------------------------
INSERT INTO `students_data` VALUES ('1', '201825800054', 'LJ7951632');
INSERT INTO `students_data` VALUES ('2', '201825800050', 'P00581929');

-- ----------------------------
-- Table structure for users_table
-- ----------------------------
DROP TABLE IF EXISTS `users_table`;
CREATE TABLE `users_table` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `Password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Full_Name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `UserType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Student_ID` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Passport_Number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of users_table
-- ----------------------------
INSERT INTO `users_table` VALUES ('3', 0x61646D696E4071712E636F6D, '123', 'Kamal', 'Admin', '0', null, 'Active');
INSERT INTO `users_table` VALUES ('8', 0x6C616E6875694071712E636F6D, '1234', 'Lanhui', 'Lecturer', null, '123', 'Active');
INSERT INTO `users_table` VALUES ('9', 0x6D6F68616D65644071712E636F6D, '123', 'Mohamed', 'Student', '201825800050', 'P00581929', 'Active');
INSERT INTO `users_table` VALUES ('10', 0x6D61726B4071712E636F6D, '123', 'Mark ', 'TA', null, '123', 'Active');
INSERT INTO `users_table` VALUES ('11', 0x6A6F686E4071712E636F6D, '123', 'John', 'TA', null, '123', 'Active');
INSERT INTO `users_table` VALUES ('12', 0x6D656864694071712E636F6D, '123', 'El-mehdi Houzi', 'Student', '201825800054', 'LJ7951632', 'Active');
INSERT INTO `users_table` VALUES ('13', 0x686568654071712E636F6D, '123', '肖灵星', 'Student', '201831990236', '123', 'Active');

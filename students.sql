-- Adminer 4.8.1 MySQL 10.4.32-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `attendance_status` enum('Present','Absent','Late') DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`),
  KEY `section` (`section`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`section`) REFERENCES `students` (`section`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `attendance` (`id`, `student_id`, `date`, `subject_id`, `time`, `attendance_status`, `section`) VALUES
(22,	15,	'2024-10-13',	1,	'14:45:57',	'Absent',	NULL),
(23,	15,	'2024-10-13',	2,	'14:45:57',	'Absent',	NULL),
(24,	15,	'2024-10-13',	3,	'14:45:57',	'Absent',	NULL),
(25,	15,	'2024-10-13',	4,	'14:45:57',	'Absent',	NULL);

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(100) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `guardian_email` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `students` (`id`, `student_name`, `section`, `guardian_email`, `guardian_phone`) VALUES
(15,	'Ced',	'1',	'nilda@gmail.com',	'091231762412');

DROP TABLE IF EXISTS `student_subjects`;
CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_id`) VALUES
(29,	15,	1),
(30,	15,	2),
(31,	15,	3),
(32,	15,	4);

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `subjects` (`id`, `subject_name`) VALUES
(1,	'English'),
(2,	'Math'),
(3,	'Science'),
(4,	'Filipino');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `institution` varchar(100) NOT NULL,
  `department` enum('CABECS','CHAP','CASE','COE','BED') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`, `institution`, `department`, `created_at`) VALUES
(1,	'jumeyla',	'$2y$10$vw92iG20TNky89V61tbhN.shmkEXJO1Y0WNPKrHpmaDem5p5Ee5cK',	'CSAB',	'CABECS',	'2024-10-09 16:38:42'),
(2,	'mama mo',	'$2y$10$EJiIvKIcubR4E14ahUcXIOfJznnm5411rXZ5LVjdVLi.gJGgpmqxO',	'CSAB',	NULL,	'2024-10-10 05:30:12');

-- 2024-10-13 14:25:23
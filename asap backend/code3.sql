CREATE TABLE `admin_tbl` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(25) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `adm` (`user_id`),
  CONSTRAINT `adm` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `class_tbl` (
  `class_id` int NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL,
  `sem` int NOT NULL,
  `additional_info` text,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `evaluation_tbl` (
  `evaluation_id` int NOT NULL AUTO_INCREMENT,
  `evaluation_name` varchar(100) NOT NULL,
  PRIMARY KEY (`evaluation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `login_table` (
  `user_id` int NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `mark_tbl` (
  `mark_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(25) NOT NULL,
  `subject_id` int NOT NULL,
  `topic_id` int NOT NULL,
  `evaluation_id` int NOT NULL,
  `mark` decimal(5,2) NOT NULL,
  `sem_id` int DEFAULT NULL,
  PRIMARY KEY (`mark_id`),
  KEY `subject_id` (`subject_id`),
  KEY `topic_id` (`topic_id`),
  KEY `evaluation_id` (`evaluation_id`),
  KEY `mark_tbl_ibfk_5` (`user_id`),
  CONSTRAINT `mark_tbl_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject_tbl` (`subject_id`),
  CONSTRAINT `mark_tbl_ibfk_3` FOREIGN KEY (`topic_id`) REFERENCES `topic_tbl` (`topic_id`),
  CONSTRAINT `mark_tbl_ibfk_4` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_tbl` (`evaluation_id`),
  CONSTRAINT `mark_tbl_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `students_tbl` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
  

  CREATE TABLE `students_tbl` (
  `std_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(25) NOT NULL,
  `year_of_joining` varchar(4) DEFAULT NULL,
  `batch` varchar(10) DEFAULT NULL,
  `sem` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  PRIMARY KEY (`std_id`),
  UNIQUE KEY `std_id` (`std_id`),
  KEY `std_idx` (`user_id`),
  KEY `cid_idx` (`class_id`),
  CONSTRAINT `cid` FOREIGN KEY (`class_id`) REFERENCES `class_tbl` (`class_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_student_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `std` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `subject_tbl` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `teacher_tbl` (
  `teacher_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(25) NOT NULL,
  `subject_id` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk_teacher_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `td` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `topic_tbl` (
  `topic_id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL,
  `topic_name` varchar(100) NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `topic_tbl_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subject_tbl` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `total_tbl` (
  `total_id` int NOT NULL AUTO_INCREMENT,
  `std_id` int NOT NULL,
  `topic_id` int NOT NULL,
  `total` decimal(5,2) DEFAULT NULL,
  `remark` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`total_id`),
  KEY `std_id` (`std_id`),
  KEY `topic_id` (`topic_id`),
  CONSTRAINT `total_tbl_ibfk_1` FOREIGN KEY (`std_id`) REFERENCES `students_tbl` (`std_id`),
  CONSTRAINT `total_tbl_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topic_tbl` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
 CREATE TABLE `users` (
  `user_id` varchar(25) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','teacher','admin') DEFAULT NULL,
  `signup_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `mark_tbl` (
  `mark_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(25) NOT NULL,
  `subject_id` int NOT NULL,
  `topic_id` int NOT NULL,
  `Mid term` int DEFAULT '0',
  `End Sem Mark` int DEFAULT '0',
  `Assignment 1` int DEFAULT '0',
  `Assignment 2` int DEFAULT '0',
  `Extra 1` int DEFAULT '0',
  `Extra 2` int DEFAULT '0',
  `sem` int DEFAULT NULL,
  `mark` decimal(5,2) DEFAULT NULL,
  `Remark` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`mark_id`),
  KEY `subject_id` (`subject_id`),
  KEY `topic_id` (`topic_id`),
  KEY `mark_tbl_ibfk_5` (`user_id`),
  CONSTRAINT `mark_tbl_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject_tbl` (`subject_id`),
  CONSTRAINT `mark_tbl_ibfk_3` FOREIGN KEY (`topic_id`) REFERENCES `topic_tbl` (`topic_id`),
  CONSTRAINT `mark_tbl_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `students_tbl` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

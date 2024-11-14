CREATE TABLE `students_tbl` (
  `user_id` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year_of_joining` varchar(4) NOT NULL,
  `batch` varchar(10) DEFAULT NULL,
  `std_id` int NOT NULL AUTO_INCREMENT,
  `sem` int DEFAULT NULL,
  PRIMARY KEY (`std_id`),
  UNIQUE KEY `std_id` (`std_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE asap.class_tbl (
    class_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    semester INT NOT NULL, -- Stores semester number
    additional_info TEXT -- Optional, for any other information
);



CREATE TABLE `teacher_tbl` (
  `teacher_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(25) NOT NULL,
  `subject_id` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `asap`.`teacher_tbl` 
ADD CONSTRAINT `td`
  FOREIGN KEY (`user_id`)
  REFERENCES `asap`.`users` (`user_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


CREATE TABLE `users` (
  `user_id` varchar(25) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','teacher','admin') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE subject_tbl (
             
    subject_id INT(15) AUTO_INCREMENT,   -- subject_id is a regular column
    subject_name VARCHAR(100) NOT NULL, -- subject_name cannot be NULL
    PRIMARY KEY (subject_id),          -- subject_id is the primary key
                          -- id is a unique key
);
DELIMITER //

CREATE TRIGGER before_insert_subject_tbl
BEFORE INSERT ON subject_tbl
FOR EACH ROW
BEGIN
    -- Set the subject_id to 'SUB' followed by the id (which will be auto-incremented)
    SET NEW.subject_id = CONCAT('SUB', NEW.id);
END //

DELIMITER ;







CREATE TABLE topic_tbl (
    topic_id VARCHAR(50) PRIMARY KEY,
    subject_id VARCHAR(50),
    topic_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES subject_tbl(subject_id)
);




CREATE TABLE `subject_tbl` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  `std_id` int DEFAULT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `topic_tbl` (
  `topic_id` int NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(100) NOT NULL,
  `subject_id` int DEFAULT NULL,
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `evaluation_tbl` (
  `eval_id` int NOT NULL AUTO_INCREMENT,
  `eval_name` varchar(100) NOT NULL,
  `topic_id` int DEFAULT NULL,
  PRIMARY KEY (`eval_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `mark_tbl` (
  `mark_id` int NOT NULL AUTO_INCREMENT,
  `topic_id` int DEFAULT NULL,
  `marks` int DEFAULT NULL,
  `remarks` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


1	aptitude	1
2	verbal	1
3	soft_skill	1
		

    CREATE TABLE `users` (
  `user_id` varchar(25) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','teacher','admin') DEFAULT NULL,
  `signup_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

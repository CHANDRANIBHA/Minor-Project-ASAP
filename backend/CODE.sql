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

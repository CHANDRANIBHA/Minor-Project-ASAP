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

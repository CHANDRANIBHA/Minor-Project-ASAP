CREATE TABLE user_tbl (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE class_tbl (
    class_id INT PRIMARY KEY AUTO_INCREMENT,
    class_name VARCHAR(50) NOT NULL,
    sem INT NOT NULL
);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE subject_tbl (
    subject_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_name VARCHAR(100) NOT NULL
);
INSERT INTO subject_tbl (subject_name)
VALUES 
    ('aptitude'),
    ('verbal'),
    ('soft skills');
CREATE TABLE teacher_tbl (
    teacher_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_tbl(user_id),
    FOREIGN KEY (subject_id) REFERENCES subject_tbl(subject_id)
);

CREATE TABLE topic_tbl (
    topic_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    topic_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES subject_tbl(subject_id)
);
INSERT INTO topic_tbl (subject_id, topic_name)
VALUES
    (1, ‘quantitative’),
    (1, ‘lr’),
    (2, ‘reading’),
    (2, ‘grammar’),
    (2, ‘bl_verbal’),
    (2, ‘pronunciation_verbal’),
    (2, ‘vocabulary_verbal’),
    (2, ‘grammar_verbal’),
    (2, ‘confidence_verbal’),
    (2, ‘add1’),
    (2, ‘add2’)
    (3, ‘writing’);
    (3, ‘bl_softskill’),
    (3, ‘pronunciation_softskill’),
    (3, ‘vocabulary_softskill’),
    (3, ‘grammar_softskill’),
    (3, ‘confidence_softskill’),
    (3, ‘extra1’),
    (3, ‘extra2’),


CREATE TABLE evaluation_tbl (
    evaluation_id INT PRIMARY KEY AUTO_INCREMENT,
    evaluation_name VARCHAR(100) NOT NULL
);
INSERT INTO evaluation_tbl (evaluation_name)
VALUES
    (‘midterm’),
    (‘endsem’),
    (‘ass1’),
    (‘ass2’),
    (‘extra1’),
    (‘extra2’),
    (‘test1’),
    (‘stest1’),
    (‘stest2’),
    (‘add1’),
    (‘add2’);

CREATE TABLE total_tbl (
    total_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    topic_id INT NOT NULL,
    evaluation_id INT NOT NULL,
    total DECIMAL(5, 2),
    remark VARCHAR(255),

    FOREIGN KEY (student_id) REFERENCES student_tbl(student_id),
    FOREIGN KEY (topic_id) REFERENCES topic_tbl(topic_id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluation_tbl(evaluation_id)
);

CREATE TABLE mark_tbl (
    mark_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    topic_id INT NOT NULL,
    evaluation_id INT NOT NULL,
    mark DECIMAL(5, 2) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES student_tbl(student_id),
    FOREIGN KEY (subject_id) REFERENCES subject_tbl(subject_id),
    FOREIGN KEY (topic_id) REFERENCES topic_tbl(topic_id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluation_tbl(evaluation_id)
);




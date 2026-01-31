-- =============================================
-- PHP Online Learning System - Database Modifications
-- =============================================

-- Example 1: Adding a new table for course categories
CREATE TABLE IF NOT EXISTS `course_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Example 2: Adding a new table for course reviews/ratings
CREATE TABLE IF NOT EXISTS `course_review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `review_text` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  FOREIGN KEY (`course_id`) REFERENCES `course`(`course_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_student_course_review` (`student_id`, `course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Example 3: Adding a new table for notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_type` enum('student','instructor','admin') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  INDEX `idx_user_notifications` (`user_id`, `user_type`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Example 4: Adding a new table for course discussions/forums
CREATE TABLE IF NOT EXISTS `course_discussion` (
  `discussion_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11),
  `parent_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_resolved` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`discussion_id`),
  FOREIGN KEY (`course_id`) REFERENCES `course`(`course_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`) ON DELETE CASCADE,
  FOREIGN KEY (`instructor_id`) REFERENCES `instructor`(`instructor_id`) ON DELETE SET NULL,
  FOREIGN KEY (`parent_id`) REFERENCES `course_discussion`(`discussion_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Example 5: Adding a new table for course assignments
CREATE TABLE IF NOT EXISTS `course_assignment` (
  `assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `chapter_id` int(11),
  `title` varchar(255) NOT NULL,
  `description` text,
  `due_date` datetime,
  `max_points` int(11) DEFAULT 100,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`assignment_id`),
  FOREIGN KEY (`course_id`) REFERENCES `course`(`course_id`) ON DELETE CASCADE,
  FOREIGN KEY (`chapter_id`) REFERENCES `chapter`(`chapter_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Example 6: Adding a new table for assignment submissions
CREATE TABLE IF NOT EXISTS `assignment_submission` (
  `submission_id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `submission_text` text,
  `attachment_url` varchar(500),
  `submitted_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `grade` decimal(5,2),
  `feedback` text,
  `graded_at` timestamp NULL,
  `graded_by` int(11),
  PRIMARY KEY (`submission_id`),
  FOREIGN KEY (`assignment_id`) REFERENCES `course_assignment`(`assignment_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`) ON DELETE CASCADE,
  FOREIGN KEY (`graded_by`) REFERENCES `instructor`(`instructor_id`) ON DELETE SET NULL,
  UNIQUE KEY `unique_student_assignment` (`student_id`, `assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- MODIFYING EXISTING TABLES
-- =============================================

-- Example 7: Adding new columns to existing tables

-- Add category_id to course table
ALTER TABLE `course` 
ADD COLUMN `category_id` int(11) DEFAULT NULL AFTER `cover`,
ADD FOREIGN KEY (`category_id`) REFERENCES `course_category`(`category_id`) ON DELETE SET NULL;

-- Add phone number to student table
ALTER TABLE `student` 
ADD COLUMN `phone` varchar(20) DEFAULT NULL AFTER `email`;

-- Add phone number to instructor table
ALTER TABLE `instructor` 
ADD COLUMN `phone` varchar(20) DEFAULT NULL AFTER `email`;

-- Add last login tracking to student table
ALTER TABLE `student` 
ADD COLUMN `last_login` timestamp NULL AFTER `date_of_joined`;

-- Add last login tracking to instructor table
ALTER TABLE `instructor` 
ADD COLUMN `last_login` timestamp NULL AFTER `date_of_joined`;

-- =============================================
-- SAMPLE DATA INSERTION
-- =============================================

-- Insert sample course categories
INSERT INTO `course_category` (`category_name`, `description`) VALUES
('Programming', 'Computer programming and software development courses'),
('Data Science', 'Data analysis, machine learning, and statistics'),
('Web Development', 'Frontend and backend web development'),
('Database', 'Database design and management'),
('Design', 'UI/UX design and graphic design');

-- Insert sample notifications
INSERT INTO `notifications` (`user_id`, `user_type`, `title`, `message`) VALUES
(1, 'student', 'Welcome!', 'Welcome to the learning platform. Start exploring courses!'),
(1, 'instructor', 'New Student', 'A new student has enrolled in your course.');

-- =============================================
-- USEFUL QUERIES FOR TESTING
-- =============================================

-- View all tables in the database
SHOW TABLES;

-- View table structure
DESCRIBE course_category;

-- View all courses with their categories
SELECT c.title, cat.category_name 
FROM course c 
LEFT JOIN course_category cat ON c.category_id = cat.category_id;

-- View course ratings
SELECT c.title, AVG(cr.rating) as average_rating, COUNT(cr.review_id) as review_count
FROM course c 
LEFT JOIN course_review cr ON c.course_id = cr.course_id
GROUP BY c.course_id, c.title;

-- Users table (admin + students)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','student') DEFAULT 'student',
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Course
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Students (profile info separate from login credentials if needed)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    student_number VARCHAR(20) UNIQUE,
    course_id INT NULL,
    year_level INT,
    status ENUM('active','inactive','graduated') NOT NULL DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
);

-- Subjects
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE,
    name VARCHAR(100),
    description TEXT NULL,
    units INT,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active'
);

-- Class Schedules
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    semester ENUM('1st','2nd','summer'),
    school_year VARCHAR(9), -- e.g. 2025-2026
    day VARCHAR(50), -- e.g. "Monday, Wednesday"
    time VARCHAR(50), -- e.g. "9:00 AM - 10:30 AM"
    room VARCHAR(50),
    capacity INT DEFAULT NULL,
    instructor VARCHAR(100) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);



-- Enrollments (which student takes which subject)
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    schedule_id INT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(id)
);

-- Grades
CREATE TABLE IF NOT EXISTS `grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) DEFAULT NULL,
  `grade` decimal(3,1) DEFAULT NULL COMMENT 'School grade: 1.0-3.0 (0.1 increments), 4.0 (INC), 5.0 (Failed), 7.0 (WD), 9.0 (DRP)',
  `remarks` varchar(20) DEFAULT NULL COMMENT 'Passed, Failed, Incomplete, Withdrawn, Dropped',
  PRIMARY KEY (`id`),
  KEY `enrollment_id` (`enrollment_id`),
  CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_school_grade` CHECK (
    `grade` IN (
      1.0, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9,
      2.0, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9,
      3.0, 4.0, 5.0, 7.0, 9.0
    ) OR `grade` IS NULL
  )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='School Grading System: 1.0 (99-100%) to 3.0 (75%), 4.0 (INC), 5.0 (Failed), 7.0 (WD), 9.0 (DRP)';


CREATE TABLE IF NOT EXISTS import_progress (
    id VARCHAR(50) PRIMARY KEY,
    user_id INT,
    total_rows INT DEFAULT 0,
    processed_rows INT DEFAULT 0,
    imported_rows INT DEFAULT 0,
    errors TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    current_row INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
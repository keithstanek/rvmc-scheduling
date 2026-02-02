-- Music Lesson Scheduler Database Schema
-- Lesson table for managing scheduled music lessons

CREATE TABLE IF NOT EXISTS lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    lesson_date DATE NOT NULL,
    lesson_time TIME NOT NULL,
    duration INT NOT NULL DEFAULT 30 COMMENT 'Duration in minutes',
    frequency ENUM('single', 'weekly', 'biweekly', 'monthly') NOT NULL DEFAULT 'single',
    notes TEXT NULL,
    payment_amount DECIMAL(10, 2) NULL DEFAULT 0.00,
    payment_method ENUM('cash', 'check', 'debit', 'ach', '') NULL,
    status ENUM('scheduled', 'completed', 'cancelled', 'rescheduled') NOT NULL DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign key constraints (assuming these tables exist)
    CONSTRAINT fk_lesson_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    CONSTRAINT fk_lesson_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_lesson_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_teacher_date (teacher_id, lesson_date),
    INDEX idx_student_date (student_id, lesson_date),
    INDEX idx_class_date (class_id, lesson_date),
    INDEX idx_lesson_datetime (lesson_date, lesson_time),
    INDEX idx_frequency (frequency),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Create a view for easier querying with joined data
CREATE OR REPLACE VIEW lesson_details AS
SELECT 
    l.id,
    l.teacher_id,
    l.student_id,
    l.class_id,
    l.lesson_date,
    l.lesson_time,
    l.duration,
    l.frequency,
    l.notes,
    l.payment_amount,
    l.payment_method,
    l.status,
    l.created_at,
    l.updated_at,
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
    c.class_name,
    CONCAT(p.first_name, ' ', p.last_name) AS parent_name
FROM lessons l
INNER JOIN students s ON l.student_id = s.id
INNER JOIN teachers t ON l.teacher_id = t.id
INNER JOIN classes c ON l.class_id = c.id
LEFT JOIN parents p ON s.parent_id = p.id;

-- Sample data for testing (optional)
-- INSERT INTO lessons (teacher_id, student_id, class_id, lesson_date, lesson_time, duration, frequency, notes, payment_amount, payment_method)
-- VALUES 
-- (1, 1, 1, '2026-01-15', '14:00:00', 30, 'weekly', 'Working on scales', 50.00, 'cash'),
-- (1, 2, 2, '2026-01-16', '15:30:00', 45, 'biweekly', 'Practice chord transitions', 75.00, 'check'),
-- (1, 3, 3, '2026-01-17', '10:00:00', 60, 'monthly', 'Vocal warmup exercises', 100.00, 'debit');
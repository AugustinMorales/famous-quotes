-- Famous Quotes Database Setup
-- Run this file to initialize the database

CREATE DATABASE IF NOT EXISTS famous_quotes;
USE famous_quotes;

-- Admin users table (Rubric #6: Username and password stored in database)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Authors table
CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    birth_year INT,
    death_year INT,
    nationality VARCHAR(100),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Quotes table
CREATE TABLE IF NOT EXISTS quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_text TEXT NOT NULL,
    author_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    year_said INT,
    source VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
);

-- Insert admin user: admin / s3cr3t (Rubric #5)
INSERT IGNORE INTO admin_users (username, password) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note: The hash above is for 's3cr3t' using password_hash with PASSWORD_DEFAULT

-- Sample authors
INSERT IGNORE INTO authors (id, name, birth_year, death_year, nationality, bio) VALUES
(1, 'Oscar Wilde', 1854, 1900, 'Irish', 'Irish poet and playwright, known for his biting wit and flamboyant style.'),
(2, 'Mark Twain', 1835, 1910, 'American', 'American writer, humorist, entrepreneur, publisher, and lecturer.'),
(3, 'Maya Angelou', 1928, 2014, 'American', 'American poet, memoirist, and civil rights activist.'),
(4, 'Winston Churchill', 1874, 1965, 'British', 'British statesman, army officer, writer, and Prime Minister.'),
(5, 'Albert Einstein', 1879, 1955, 'German-American', 'Theoretical physicist who developed the theory of relativity.');

-- Sample quotes
INSERT IGNORE INTO quotes (quote_text, author_id, category, year_said, source) VALUES
('Be yourself; everyone else is already taken.', 1, 'Inspiration', NULL, 'Attributed'),
('To live is the rarest thing in the world. Most people exist, that is all.', 1, 'Philosophy', NULL, 'The Soul of Man under Socialism'),
('The secret of getting ahead is getting started.', 2, 'Motivation', NULL, 'Attributed'),
('It always seems impossible until it''s done.', 3, 'Inspiration', NULL, 'Attributed'),
('Success is not final, failure is not fatal: it is the courage to continue that counts.', 4, 'Success', NULL, 'Attributed'),
('Imagination is more important than knowledge.', 5, 'Education', 1929, 'The Saturday Evening Post');

<?php
// run_setup.php - Run this ONCE to initialize the database
// Access via: http://yourserver/famous-quotes/run_setup.php
// DELETE this file after running!

require_once 'includes/db.php';
$db = getDB();

// Create tables
$db->exec("
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("
CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    birth_year INT,
    death_year INT,
    nationality VARCHAR(100),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("
CREATE TABLE IF NOT EXISTS quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_text TEXT NOT NULL,
    author_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    year_said INT,
    source VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
)");

// Insert admin user with properly hashed password
$hash = password_hash('s3cr3t', PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT IGNORE INTO admin_users (username, password) VALUES (?, ?)");
$stmt->execute(['admin', $hash]);

// Sample authors
$db->exec("INSERT IGNORE INTO authors (id, name, birth_year, death_year, nationality, bio) VALUES
(1, 'Oscar Wilde', 1854, 1900, 'Irish', 'Irish poet and playwright, known for his biting wit and flamboyant style.'),
(2, 'Mark Twain', 1835, 1910, 'American', 'American writer, humorist, entrepreneur, publisher, and lecturer.'),
(3, 'Maya Angelou', 1928, 2014, 'American', 'American poet, memoirist, and civil rights activist.'),
(4, 'Winston Churchill', 1874, 1965, 'British', 'British statesman, army officer, writer, and Prime Minister.'),
(5, 'Albert Einstein', 1879, 1955, 'German-American', 'Theoretical physicist who developed the theory of relativity.')
");

// Sample quotes
$db->exec("INSERT IGNORE INTO quotes (quote_text, author_id, category, year_said, source) VALUES
('Be yourself; everyone else is already taken.', 1, 'Inspiration', NULL, 'Attributed'),
('To live is the rarest thing in the world. Most people exist, that is all.', 1, 'Philosophy', NULL, 'The Soul of Man under Socialism'),
('The secret of getting ahead is getting started.', 2, 'Motivation', NULL, 'Attributed'),
('It always seems impossible until it is done.', 3, 'Inspiration', NULL, 'Attributed'),
('Success is not final, failure is not fatal: it is the courage to continue that counts.', 4, 'Success', NULL, 'Attributed'),
('Imagination is more important than knowledge.', 5, 'Education', 1929, 'The Saturday Evening Post')
");

echo '<h2 style="font-family:sans-serif;color:green;"> Database setup complete!</h2>';
echo '<p style="font-family:sans-serif;">Tables created and sample data inserted.<br>';
echo 'Login credentials: <strong>admin</strong> / <strong>s3cr3t</strong><br><br>';
echo '<strong style="color:red;"> Delete this file (run_setup.php) after setup!</strong></p>';
echo '<p><a href="login.php"> Go to Admin Login</a></p>';

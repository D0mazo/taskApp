
Copy

<?php
// ============================================================
//  CONFIGURE YOUR INFINITYFREE / MYSQL CREDENTIALS HERE
// ============================================================
define('DB_HOST', 'sql111.infinityfree.com');
define('DB_USER', 'if0_41004146');
define('DB_PASS', 'D0mukas1');
define('DB_NAME', 'if0_41004146_taskcal');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]));
}
$conn->set_charset('utf8mb4');

// Create tables if they don't exist
$conn->multi_query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    priority ENUM('low','medium','high') DEFAULT 'medium',
    progress INT DEFAULT 0,
    progress_note TEXT,
    done TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
");
// Flush multi_query
while ($conn->more_results()) $conn->next_result();
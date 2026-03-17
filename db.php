<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

define('DB_HOST', 'sql111.infinityfree.com');
define('DB_USER', 'if0_41004146');
define('DB_PASS', 'D0mukas1');
define('DB_NAME', 'if0_41004146_taskcal');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    ob_clean();
    die(json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]));
}
$conn->set_charset('utf8mb4');

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    email VARCHAR(180),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','member') DEFAULT 'member',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    color VARCHAR(20) DEFAULT '#378ADD',
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_by INT NOT NULL,
    assigned_to INT,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    priority ENUM('low','medium','high') DEFAULT 'medium',
    progress INT DEFAULT 0,
    progress_note TEXT,
    done TINYINT(1) DEFAULT 0,
    recurring ENUM('none','daily','weekly','monthly') DEFAULT 'none',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    body TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$res = $conn->query("SELECT COUNT(*) as c FROM categories");
if ($res && $res->fetch_assoc()['c'] == 0) {
    $conn->query("INSERT INTO categories (name,color,created_by) VALUES ('Work','#378ADD',NULL),('Personal','#63c55a',NULL),('Family','#f0a500',NULL),('Urgent','#E24B4A',NULL)");
}

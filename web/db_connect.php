<?php
function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", ""); // Update password and database name
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error() . " (Error Code: " . mysqli_connect_errno() . ")");
    }
    return $conn;
}
?>
/*
CREATE DATABASE project_db;
USE project_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT DEFAULT 0,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
ALTER TABLE users ADD COLUMN remember_token VARCHAR(64) DEFAULT NULL;
*/

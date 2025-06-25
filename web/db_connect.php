<?php
function db_connect() {
    $conn = mysqli_connect("localhost", "root", "#nirajshwkt987", "project_db"); // Update password and database name
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error() . " (Error Code: " . mysqli_connect_errno() . ")");
    }
    return $conn;
}
?>
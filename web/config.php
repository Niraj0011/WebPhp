<?php
session_start();

// Auto-login with Remember Me
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    require_once 'db_connect.php';
    $conn = db_connect();
    $remember_token = mysqli_real_escape_string($conn, $_COOKIE['remember_me']);
    $sql = "SELECT id, username, is_admin FROM users WHERE remember_token = '$remember_token' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['is_admin'] = $row['is_admin'];
    }
    mysqli_close($conn);
}

// Logout Logic
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    require_once 'db_connect.php';
    $conn = db_connect();
    session_unset();
    session_destroy();
    setcookie('remember_me', '', time() - 3600, "/"); // Clear remember me cookie
    mysqli_close($conn);
    header("Location: ?page=home");
    exit();
}
?>
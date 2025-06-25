<?php
require_once 'config.php';
require_once 'db_connect.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$pages = ['home', 'register', 'login', 'upload', 'contact', 'admin', 'about', 'services'];
$page = in_array($page, $pages) ? $page : 'home';

require_once "$page.php";
?>
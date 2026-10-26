<?php
require_once 'config/config.php';
$lang = $_SESSION['lang'] ?? 'id';
$_SESSION = [];
session_destroy();
session_start();
$_SESSION['lang'] = $lang;
header('Location: ' . LOGIN_PAGE);
exit;

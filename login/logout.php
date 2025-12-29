<?php
session_start();
$fav_grade = $_SESSION['favorite_grade'] ?? 'Nie podano';
$_SESSION = array();
$_SESSION['favorite_grade'] = $fav_grade;
require_once '../utils/strings.php';
header('Location: ' . INDEX_PATH);
exit();

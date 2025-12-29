<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); exit();
}

$query = "SELECT studentID, first_name, last_name FROM students ORDER BY last_name, first_name";
$result = mysqli_query($con, $query);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($students);
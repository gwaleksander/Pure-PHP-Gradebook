<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['userID'])) { http_response_code(403); exit(); }

$role = $_SESSION['role'];
$type = $_GET['type'] ?? '';

$data = [];

if ($type === 'class') {
    $q = "SELECT classID as id, name as label FROM classes ORDER BY name";
    $res = mysqli_query($con, $q);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);

} elseif ($type === 'teacher') {
    $q = "SELECT u.userID as id, CONCAT(t.first_name, ' ', t.last_name) as label 
          FROM users u JOIN teachers t ON u.userID = t.teacherID ORDER BY t.last_name";
    $res = mysqli_query($con, $q);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);

} elseif ($type === 'student') {
    $q = "SELECT u.userID as id, CONCAT(s.first_name, ' ', s.last_name, ' (', c.name, ')') as label 
          FROM users u 
          JOIN students s ON u.userID = s.studentID 
          JOIN classes c ON s.classID = c.classID 
          ORDER BY s.last_name";
    $res = mysqli_query($con, $q);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
    
} elseif ($type === 'parent') {
    $q = "SELECT u.userID as id, CONCAT(p.first_name, ' ', p.last_name) as label 
          FROM users u JOIN parents p ON u.userID = p.parentID ORDER BY p.last_name";
    $res = mysqli_query($con, $q);
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($data);
?>
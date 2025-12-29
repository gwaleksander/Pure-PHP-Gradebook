<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit();
}

$userID = filter_input(INPUT_GET, 'userID', FILTER_VALIDATE_INT);
if (!$userID) {
    http_response_code(400);
    echo json_encode(['error' => 'Brak ID użytkownika']);
    exit();
}

$query = "
    SELECT 
        u.userID, u.username, u.email, u.phone, u.role,
        s.first_name as student_first_name, s.last_name as student_last_name, s.PESEL as student_pesel, s.birth_date as student_birth_date, s.address as student_address, s.classID as student_classID,
        t.first_name as teacher_first_name, t.last_name as teacher_last_name, t.PESEL as teacher_pesel, t.birth_date as teacher_birth_date, t.address as teacher_address, t.hire_date as teacher_hire_date,
        p.first_name as parent_first_name, p.last_name as parent_last_name
    FROM users u
    LEFT JOIN students s ON u.userID = s.studentID
    LEFT JOIN teachers t ON u.userID = t.teacherID
    LEFT JOIN parents p ON u.userID = p.parentID
    WHERE u.userID = ?
";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

if ($user_data && $user_data['role'] === 'parent') {
    $children_query = "SELECT s.studentID, s.first_name, s.last_name 
                       FROM students s
                       JOIN student_parent sp ON s.studentID = sp.studentID
                       WHERE sp.parentID = ?";
    $stmt_children = mysqli_prepare($con, $children_query);
    mysqli_stmt_bind_param($stmt_children, "i", $userID);
    mysqli_stmt_execute($stmt_children);
    $children_result = mysqli_stmt_get_result($stmt_children);
    $user_data['assigned_children'] = mysqli_fetch_all($children_result, MYSQLI_ASSOC);
}

if ($user_data) {
    header('Content-Type: application/json');
    echo json_encode($user_data);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Nie znaleziono użytkownika']);
}

mysqli_stmt_close($stmt);
mysqli_close($con);

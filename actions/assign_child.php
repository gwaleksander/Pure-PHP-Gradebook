<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { http_response_code(403); exit(); }
require_post();

$parentID = filter_input(INPUT_POST, 'parentID', FILTER_VALIDATE_INT);
$studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);

if (!$parentID || !$studentID) { http_response_code(400); exit(); }

$stmt = mysqli_prepare($con, "INSERT INTO student_parent (parentID, studentID) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "ii", $parentID, $studentID);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'To dziecko jest już przypisane.']);
}
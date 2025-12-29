<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/app_settings.php';

require_role('teacher');

$input = json_decode(file_get_contents('php://input'), true);
$studentID = filter_var($input['studentID'] ?? null, FILTER_VALIDATE_INT);
$subjectID = filter_var($input['subjectID'] ?? null, FILTER_VALIDATE_INT);
$field = $input['field'] ?? '';
$value = filter_var($input['value'] ?? null, FILTER_VALIDATE_INT);

if (!$studentID || !$subjectID || !in_array($field, ['grade_term1', 'grade_final'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane wejściowe.']);
    exit();
}

$teacherID = $_SESSION['userID'];
$current_semester = get_current_semester();

if ($current_semester == 1 && $field == 'grade_final') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Nie można wystawić oceny rocznej w 1. semestrze.']);
    exit();
}

if ($value === false || $value === null) {
    $query = "INSERT INTO final_grades (studentID, subjectID, teacherID, $field) VALUES (?, ?, ?, NULL) 
              ON DUPLICATE KEY UPDATE $field = NULL, teacherID = VALUES(teacherID)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iii", $studentID, $subjectID, $teacherID);
} else {
    if ($value < 1 || $value > 6) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ocena poza skalą.']);
        exit();
    }
    
    $query = "INSERT INTO final_grades (studentID, subjectID, teacherID, $field) VALUES (?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE $field = VALUES($field), teacherID = VALUES(teacherID)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iiii", $studentID, $subjectID, $teacherID, $value);
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Błąd bazy danych.']);
}
exit();
?>
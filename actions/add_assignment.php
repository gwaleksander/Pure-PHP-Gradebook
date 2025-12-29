<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$classID = filter_input(INPUT_POST, 'classID', FILTER_VALIDATE_INT);
$subjectID = filter_input(INPUT_POST, 'subjectID', FILTER_VALIDATE_INT);
$teacherID = filter_input(INPUT_POST, 'teacherID', FILTER_VALIDATE_INT);
$redirect_url = '../gradebook/admin/dashboard.php?tab=assignments_manager&class_id_assignments=' . $classID;

if (!$classID || !$subjectID || !$teacherID) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Wszystkie pola są wymagane.'];
} else {
    $stmt = mysqli_prepare($con, "INSERT INTO class_subjects_teacher (classID, subjectID, teacherID) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iii", $classID, $subjectID, $teacherID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Przypisanie zostało dodane.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Ten przedmiot jest już przypisany do tej klasy.'];
    }
}
header('Location: ' . $redirect_url);
exit();
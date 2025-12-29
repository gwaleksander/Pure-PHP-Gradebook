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
    $stmt = mysqli_prepare($con, "UPDATE class_subjects_teacher SET teacherID = ? WHERE classID = ? AND subjectID = ?");
    mysqli_stmt_bind_param($stmt, "iii", $teacherID, $classID, $subjectID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Nauczyciel został zmieniony.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas aktualizacji.'];
    }
}
header('Location: ' . $redirect_url);
exit();

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
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nieprawidłowe dane. Brak pełnych informacji do usunięcia wpisu.'];
} else {
    $stmt = mysqli_prepare($con, "DELETE FROM class_subjects_teacher WHERE classID = ? AND subjectID = ? AND teacherID = ?");
    mysqli_stmt_bind_param($stmt, "iii", $classID, $subjectID, $teacherID);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Przypisanie zostało usunięte.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas usuwania.'];
    }
}
header('Location: ' . $redirect_url);
exit();

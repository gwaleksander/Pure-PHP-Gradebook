<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$subjectID = filter_input(INPUT_POST, 'subjectID', FILTER_VALIDATE_INT);

if (!$subjectID) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nieprawidłowe ID przedmiotu.'];
} else {
    $stmt = mysqli_prepare($con, "DELETE FROM subjects WHERE subjectID = ?");
    mysqli_stmt_bind_param($stmt, "i", $subjectID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Przedmiot został usunięty.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas usuwania przedmiotu.'];
    }
}
header('Location: ../gradebook/admin/dashboard.php?tab=subjects_manager');
exit();

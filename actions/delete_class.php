<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$classID = filter_input(INPUT_POST, 'classID', FILTER_VALIDATE_INT);

if (!$classID) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nieprawidłowe ID klasy.'];
} else {
    $stmt = mysqli_prepare($con, "DELETE FROM classes WHERE classID = ?");
    mysqli_stmt_bind_param($stmt, "i", $classID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Klasa została usunięta.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas usuwania klasy.'];
    }
}
header('Location: ../gradebook/admin/dashboard.php?tab=classes_manager');
exit();

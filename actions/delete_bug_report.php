<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$reportID = filter_input(INPUT_POST, 'reportID', FILTER_VALIDATE_INT);

if ($reportID) {
    $stmt = mysqli_prepare($con, "DELETE FROM bug_reports WHERE reportID = ?");
    mysqli_stmt_bind_param($stmt, "i", $reportID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Zgłoszenie zostało usunięte.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd bazy danych.'];
    }
}

header('Location: ../gradebook/admin/dashboard.php?tab=bug_reports');
exit();
?>
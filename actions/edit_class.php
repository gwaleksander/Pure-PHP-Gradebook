<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$classID = filter_input(INPUT_POST, 'classID', FILTER_VALIDATE_INT);
$name = trim($_POST['name']);
$teacherID = filter_input(INPUT_POST, 'teacherID', FILTER_VALIDATE_INT);

if (!$classID || empty($name) || !$teacherID) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Wszystkie pola są wymagane.'];
} else {
    $stmt = mysqli_prepare($con, "UPDATE classes SET name = ?, teacherID = ? WHERE classID = ?");
    mysqli_stmt_bind_param($stmt, "sii", $name, $teacherID, $classID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Dane klasy zostały zaktualizowane.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas aktualizacji danych.'];
    }
}
header('Location: ../gradebook/admin/dashboard.php?tab=classes_manager');
exit();

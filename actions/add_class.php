<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$name = trim($_POST['name']);
$teacherID = filter_input(INPUT_POST, 'teacherID', FILTER_VALIDATE_INT);

if (empty($name) || !$teacherID) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Wszystkie pola są wymagane.'];
} else {
    $stmt = mysqli_prepare($con, "INSERT INTO classes (name, teacherID) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "si", $name, $teacherID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Klasa została pomyślnie dodana.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas dodawania klasy.'];
    }
}
header('Location: ../gradebook/admin/dashboard.php?tab=classes_manager');
exit();
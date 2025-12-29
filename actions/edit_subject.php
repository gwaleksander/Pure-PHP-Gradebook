<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$subjectID = filter_input(INPUT_POST, 'subjectID', FILTER_VALIDATE_INT);
$name = trim($_POST['name']);

if (!$subjectID || empty($name)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Wszystkie pola są wymagane.'];
} else {
    $stmt = mysqli_prepare($con, "UPDATE subjects SET name = ? WHERE subjectID = ?");
    mysqli_stmt_bind_param($stmt, "si", $name, $subjectID);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Nazwa przedmiotu została zaktualizowana.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Przedmiot o tej nazwie już istnieje.'];
    }
}
header('Location: ../gradebook/admin/dashboard.php?tab=subjects_manager');
exit();
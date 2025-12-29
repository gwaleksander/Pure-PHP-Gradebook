<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$name = trim($_POST['name']);

if (empty($name)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nazwa przedmiotu jest wymagana.'];
} else {
    $stmt = mysqli_prepare($con, "INSERT INTO subjects (name) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $name);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Przedmiot został dodany.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Przedmiot o tej nazwie już istnieje.'];
    }
}
header('Location: ../gradebook/admin/dashboard.php?tab=subjects_manager');
exit();
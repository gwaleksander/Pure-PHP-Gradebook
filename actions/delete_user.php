<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$userID = filter_input(INPUT_POST, 'userID', FILTER_VALIDATE_INT);

if (!$userID) {
    die("Brak ID użytkownika.");
}

if ($userID === $_SESSION['userID']) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nie możesz usunąć własnego konta.'];
    header('Location: ../gradebook/admin/dashboard.php');
    exit();
}

$query = "DELETE FROM users WHERE userID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $userID);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Użytkownik został usunięty.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas usuwania użytkownika.'];
}

mysqli_stmt_close($stmt);
header('Location: ../gradebook/admin/dashboard.php');
exit();

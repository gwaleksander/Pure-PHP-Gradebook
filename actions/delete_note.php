<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/strings.php';

require_role('teacher');
require_post();

$note_id = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
$redirect_url = $_POST['redirect_url'] ?? '';

if (!$note_id) die("Brak ID uwagi.");

$check_query = "SELECT teacherID FROM notes WHERE noteID = ?";
$stmt_check = mysqli_prepare($con, $check_query);
mysqli_stmt_bind_param($stmt_check, "i", $note_id);
mysqli_stmt_execute($stmt_check);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));

if (!$result || $result['teacherID'] !== $_SESSION['userID']) {
    die("Błąd: Nie masz uprawnień do usunięcia tej uwagi.");
}

$query = "DELETE FROM notes WHERE noteID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $note_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Uwaga została usunięta.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas usuwania uwagi.'];
}

header('Location: ' . BASE_URL . '/gradebook/teacher/dashboard.php?' . $redirect_url);
exit();

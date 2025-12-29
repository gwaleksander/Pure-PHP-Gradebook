<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/strings.php';

require_role('teacher');
require_post();

$note_id = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
$note_content = trim($_POST['note_content']);
$note_type = in_array($_POST['note_type'], ['positive', 'negative', 'neutral']) ? $_POST['note_type'] : 'neutral';
$redirect_url = $_POST['redirect_url'] ?? '';

if (!$note_id || empty($note_content)) die("Brak wszystkich wymaganych danych.");

$check_query = "SELECT teacherID FROM notes WHERE noteID = ?";
$stmt_check = mysqli_prepare($con, $check_query);
mysqli_stmt_bind_param($stmt_check, "i", $note_id);
mysqli_stmt_execute($stmt_check);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));

if (!$result || $result['teacherID'] !== $_SESSION['userID']) {
    die("Błąd: Nie masz uprawnień do edycji tej uwagi.");
}

$query = "UPDATE notes SET note_content = ?, note_type = ? WHERE noteID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ssi", $note_content, $note_type, $note_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Uwaga została zaktualizowana.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas aktualizacji uwagi.'];
}

header('Location: ' . BASE_URL . '/gradebook/teacher/dashboard.php?' . $redirect_url);
exit();

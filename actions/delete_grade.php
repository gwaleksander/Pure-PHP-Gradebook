<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/strings.php';

require_role('teacher');
require_post();

$grade_id = filter_input(INPUT_POST, 'grade_id', FILTER_VALIDATE_INT);
$redirect_url = $_POST['redirect_url'] ?? '';

if (!$grade_id) die("Brak ID oceny.");

$check_query = "SELECT teacherID FROM grades WHERE gradeID = ?";
$stmt_check = mysqli_prepare($con, $check_query);
mysqli_stmt_bind_param($stmt_check, "i", $grade_id);
mysqli_stmt_execute($stmt_check);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));

/*
 * kwestia sporna - czy nauczyciel może usuwać oceny wystawione przez innych nauczycieli?
if ($result['teacherID'] !== $_SESSION['userID']) {
    die("Błąd: Nie możesz usunąć oceny wystawionej przez innego nauczyciela.");
}
*/

$query = "DELETE FROM grades WHERE gradeID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $grade_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Ocena została usunięta.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas usuwania oceny.'];
}

header('Location: ' . BASE_URL . '/gradebook/teacher/dashboard.php?' . $redirect_url);
exit();

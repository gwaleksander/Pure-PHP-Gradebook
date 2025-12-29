<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/strings.php';

require_role('teacher');
require_post();

$grade_id = filter_input(INPUT_POST, 'grade_id', FILTER_VALIDATE_INT);
$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_INT);
$grade = filter_input(INPUT_POST, 'grade', FILTER_VALIDATE_FLOAT);
$comment = trim($_POST['comment']);
$redirect_url = $_POST['redirect_url'] ?? '';

if (!$grade_id || !$category_id || !$weight || !$grade) die("Brak wszystkich wymaganych danych.");

$check_query = "SELECT teacherID FROM grades WHERE gradeID = ?";
$stmt_check = mysqli_prepare($con, $check_query);
mysqli_stmt_bind_param($stmt_check, "i", $grade_id);
mysqli_stmt_execute($stmt_check);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));

if ($result['teacherID'] !== $_SESSION['userID']) {
    die("Błąd: Nie możesz modyfikować oceny wystawionej przez innego nauczyciela.");
}

$query = "UPDATE grades SET grade = ?, weight = ?, categoryID = ?, comment = ? WHERE gradeID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "diisi", $grade, $weight, $category_id, $comment, $grade_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Ocena została zaktualizowana.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas aktualizacji oceny.'];
}

header('Location: ' . BASE_URL . '/gradebook/teacher/dashboard.php?' . $redirect_url);
exit();

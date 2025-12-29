<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/strings.php';

require_role('teacher');
require_post();

$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
$teacher_id = filter_input(INPUT_POST, 'teacher_id', FILTER_VALIDATE_INT);
$semester   = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
$note_content = trim($_POST['note_content']);
$note_type = in_array($_POST['note_type'], ['positive', 'negative', 'neutral']) ? $_POST['note_type'] : 'neutral';
$redirect_url = $_POST['redirect_url'] ?? '';

if (!$student_id || !$teacher_id || empty($note_content)) {
    die("Brak wszystkich wymaganych danych.");
}

if ($teacher_id !== $_SESSION['userID']) {
    die("Błąd autoryzacji.");
}

if (!$semester || ($semester != 1 && $semester != 2)) {
    $semester = 1;
}

$query = "INSERT INTO notes (studentID, teacherID, note_content, note_type, semester, date_added) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "iissi", $student_id, $teacher_id, $note_content, $note_type, $semester);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Uwaga została dodana.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd podczas dodawania uwagi.'];
}

header('Location: ' . BASE_URL . '/gradebook/teacher/dashboard.php?' . $redirect_url);
exit();
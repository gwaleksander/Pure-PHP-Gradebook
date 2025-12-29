<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/strings.php';

require_role('teacher');
require_post();

$student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
$subject_id = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
$teacher_id = filter_input(INPUT_POST, 'teacher_id', FILTER_VALIDATE_INT);
$semester   = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_INT);
$grade = filter_input(INPUT_POST, 'grade', FILTER_VALIDATE_FLOAT);
$comment = trim($_POST['comment']);
$redirect_url = $_POST['redirect_url'] ?? '';

if ($teacher_id !== $_SESSION['userID']) {
    die("Błąd autoryzacji: próba dodania oceny w imieniu innego nauczyciela.");
}

if (!$student_id || !$subject_id || !$teacher_id || !$category_id || !$weight || !$grade) {
    die("Błąd: brak wszystkich wymaganych danych.");
}

$query = "INSERT INTO grades (studentID, subjectID, teacherID, grade, weight, categoryID, comment, semester, date) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "iiiddisi", $student_id, $subject_id, $teacher_id, $grade, $weight, $category_id, $comment, $semester);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] = [
        'type' => 'success',
        'message' => 'Ocena została pomyślnie dodana!'
    ];
} else {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'message' => 'Wystąpił błąd podczas dodawania oceny: ' . mysqli_stmt_error($stmt)
    ];
}

mysqli_stmt_close($stmt);
mysqli_close($con);

$location = BASE_URL . '/gradebook/teacher/dashboard.php?' . $redirect_url;
header('Location: ' . $location);
exit();

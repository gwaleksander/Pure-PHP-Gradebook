<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$userID = filter_input(INPUT_POST, 'userID', FILTER_VALIDATE_INT);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = $_POST['password'];

if (!$userID || empty($username) || empty($email)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Podstawowe dane (login, email) są wymagane.'];
    header('Location: ../gradebook/admin/dashboard.php');
    exit();
}

mysqli_begin_transaction($con);
try {
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt_user = mysqli_prepare($con, "UPDATE users SET username = ?, email = ?, phone = ?, password = ? WHERE userID = ?");
        mysqli_stmt_bind_param($stmt_user, "ssssi", $username, $email, $phone, $hashed_password, $userID);
    } else {
        $stmt_user = mysqli_prepare($con, "UPDATE users SET username = ?, email = ?, phone = ? WHERE userID = ?");
        mysqli_stmt_bind_param($stmt_user, "sssi", $username, $email, $phone, $userID);
    }
    mysqli_stmt_execute($stmt_user);
    mysqli_stmt_close($stmt_user);

    $role_result = mysqli_query($con, "SELECT role FROM users WHERE userID = $userID");
    $role = mysqli_fetch_assoc($role_result)['role'];

    $stmt_role = null;
    if ($role === 'student') {
        $stmt_role = mysqli_prepare($con, "UPDATE students SET first_name = ?, last_name = ?, PESEL = ?, address = ?, birth_date = ?, classID = ? WHERE studentID = ?");
        mysqli_stmt_bind_param($stmt_role, "sssssii", $_POST['student_first_name'], $_POST['student_last_name'], $_POST['student_pesel'], $_POST['student_address'], $_POST['student_birth_date'], $_POST['student_classID'], $userID);
    } elseif ($role === 'teacher') {
        $stmt_role = mysqli_prepare($con, "UPDATE teachers SET first_name = ?, last_name = ?, PESEL = ?, address = ?, birth_date = ?, hire_date = ? WHERE teacherID = ?");
        mysqli_stmt_bind_param($stmt_role, "ssssssi", $_POST['teacher_first_name'], $_POST['teacher_last_name'], $_POST['teacher_pesel'], $_POST['teacher_address'], $_POST['teacher_birth_date'], $_POST['teacher_hire_date'], $userID);
    } elseif ($role === 'parent') {
        $stmt_role = mysqli_prepare($con, "UPDATE parents SET first_name = ?, last_name = ?, address = ? WHERE parentID = ?");
        mysqli_stmt_bind_param($stmt_role, "sssi", $_POST['parent_first_name'], $_POST['parent_last_name'], $_POST['parent_address'], $userID);
    }

    if ($stmt_role) {
        mysqli_stmt_execute($stmt_role);
        mysqli_stmt_close($stmt_role);
    }

    mysqli_commit($con);
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Dane użytkownika zostały zaktualizowane.'];
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($con);
    if (str_contains($e->getMessage(), 'Duplicate entry')) {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nazwa użytkownika lub email już istnieje.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd bazy danych: ' . $e->getMessage()];
    }
}

header('Location: ../gradebook/admin/dashboard.php');
exit();

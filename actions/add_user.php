<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');
require_post();

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = $_POST['password'];
$role = $_POST['role'];

if (empty($username) || empty($email) || empty($password) || empty($role)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Podstawowe dane (login, email, telefon, hasło, rola) są wymagane.'];
    header('Location: ../gradebook/admin/dashboard.php');
    exit();
}

mysqli_begin_transaction($con);
try {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt_user = mysqli_prepare($con, "INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_user, "sssss", $username, $email, $phone, $hashed_password, $role);
    mysqli_stmt_execute($stmt_user);
    $new_user_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt_user);

    $stmt_role = null;
    if ($role === 'student') {
        $stmt_role = mysqli_prepare($con, "INSERT INTO students (studentID, first_name, last_name, PESEL, address, birth_date, classID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_role, "isssssi", $new_user_id, $_POST['student_first_name'], $_POST['student_last_name'], $_POST['student_pesel'], $_POST['student_address'], $_POST['student_birth_date'], $_POST['student_classID']);
    } elseif ($role === 'teacher') {
        $stmt_role = mysqli_prepare($con, "INSERT INTO teachers (teacherID, first_name, last_name, PESEL, address, birth_date, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_role, "issssss", $new_user_id, $_POST['teacher_first_name'], $_POST['teacher_last_name'], $_POST['teacher_pesel'], $_POST['teacher_address'], $_POST['teacher_birth_date'], $_POST['teacher_hire_date']);
    } elseif ($role === 'parent') {
        $stmt_role = mysqli_prepare($con, "INSERT INTO parents (parentID, first_name, last_name, address) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_role, "isss", $new_user_id, $_POST['parent_first_name'], $_POST['parent_last_name'], $_POST['parent_address']);
    }

    if ($stmt_role) {
        mysqli_stmt_execute($stmt_role);
        mysqli_stmt_close($stmt_role);
    }

    mysqli_commit($con);
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Użytkownik został pomyślnie dodany.'];
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($con);
    $error_msg = $e->getMessage();

    if (str_contains($error_msg, 'Duplicate entry')) {
        if (str_contains($error_msg, 'PESEL')) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Taki numer PESEL już istnieje w systemie!'];
        } elseif (str_contains($error_msg, 'username')) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Taki login jest już zajęty.'];
        } elseif (str_contains($error_msg, 'email')) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Taki adres email jest już zajęty.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd: Podane dane (login, email lub PESEL) już istnieją.'];
        }
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd bazy danych: ' . $error_msg];
    }
}
header('Location: ../gradebook/admin/dashboard.php');
exit();

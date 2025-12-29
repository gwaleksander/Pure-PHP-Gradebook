<?php
session_start();
require_once '../utils/strings.php';
require_once '../utils/db_connect.php';

if (empty($_POST['username']) || empty($_POST['password'])) {
    $_SESSION['login_error'] = "Wszystkie pola są wymagane!";
    header('Location: ' . INDEX_PATH);
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $username);
mysqli_stmt_execute($stmt);
$query_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($query_result) === 1) {
    $result = mysqli_fetch_assoc($query_result);

    if (password_verify($password, $result['password']) || $result['password'] === $password) {
        session_regenerate_id(true);

        if(!empty($_POST['favorite_grade']) && in_array($_POST['favorite_grade'], ['1', '2', '3', '4', '5', '6']))
            $_SESSION['favorite_grade'] = $_POST['favorite_grade'] ?? '-'; 


        $_SESSION['userID'] = $result["userID"];
        $_SESSION['username'] = $result["username"];
        $_SESSION['email'] = $result["email"];
        $_SESSION['role'] = $result["role"];
        $_SESSION['phone'] = $result["phone"];
        $_SESSION['createdAt'] = $result["createdAt"];

        $id = $result["userID"];
        $role_table = '';
        $role_id_column = '';

        switch ($result['role']) {
            case 'student':
                $role_table = 'students';
                $role_id_column = 'studentID';
                break;
            case 'parent':
                $role_table = 'parents';
                $role_id_column = 'parentID';
                break;
            case 'teacher':
                $role_table = 'teachers';
                $role_id_column = 'teacherID';
                break;
        }

        if ($role_table) {
            $details_query = "SELECT * FROM $role_table WHERE $role_id_column = ?";
            $details_stmt = mysqli_prepare($con, $details_query);
            mysqli_stmt_bind_param($details_stmt, "i", $id);
            mysqli_stmt_execute($details_stmt);
            $details_result = mysqli_fetch_assoc(mysqli_stmt_get_result($details_stmt));

            if ($details_result) {
                foreach ($details_result as $key => $value) {
                    if ($key !== $role_id_column) {
                        $_SESSION[$key] = $value;
                    }
                }
            }
        }

        switch ($role_table) {
            case 'students':
                $role_id_column = 'studentID';
                $class_query = "SELECT c.name, t.first_name, t.last_name FROM `classes` c inner join `students` s on c.classID=s.classID inner join teachers t on t.teacherID=c.teacherID WHERE $role_id_column = ?";
                $class_stmt = mysqli_prepare($con, $class_query);
                mysqli_stmt_bind_param($class_stmt, "i", $id);
                mysqli_stmt_execute($class_stmt);
                $class_result = mysqli_fetch_assoc(mysqli_stmt_get_result($class_stmt));

                $_SESSION['class_name'] = $class_result['name'];
                $_SESSION['class_teacher_first_name'] = $class_result['first_name'];
                $_SESSION['class_teacher_last_name'] = $class_result['last_name'];

                break;
            case 'parents':
                $children_query = "SELECT s.studentID, s.first_name, s.last_name 
                                   FROM students s
                                   JOIN student_parent sp ON s.studentID = sp.studentID
                                   WHERE sp.parentID = ?";
                $children_stmt = mysqli_prepare($con, $children_query);
                mysqli_stmt_bind_param($children_stmt, "i", $id);
                mysqli_stmt_execute($children_stmt);
                $children_result = mysqli_stmt_get_result($children_stmt);
                $_SESSION['children'] = mysqli_fetch_all($children_result, MYSQLI_ASSOC);
                break;
            case 'teachers':
                break;
        }

        header('Location: ../gradebook/index.php');
        exit();
    } else {
        $_SESSION['login_error'] = "Nieprawidłowy login lub hasło.";
        header('Location: ' . INDEX_PATH);
        exit();
    }
} else {
    $_SESSION['login_error'] = "Nieprawidłowy login lub hasło.";
    header('Location: ' . INDEX_PATH);
    exit();
}

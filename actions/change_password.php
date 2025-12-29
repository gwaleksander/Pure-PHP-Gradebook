<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['userID'])) { die("Błąd sesji"); }
require_post();

$old_pass = $_POST['old_password'];
$new_pass = $_POST['new_password'];
$confirm_pass = $_POST['confirm_password'];

if ($new_pass !== $confirm_pass) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nowe hasła nie są identyczne.'];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if($old_pass === $new_pass) {
    $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Nowe hasło jest takie samo jak stare.'];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$stmt = mysqli_prepare($con, "SELECT password FROM users WHERE userID = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['userID']);
mysqli_stmt_execute($stmt);
$res = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$db_pass = $res['password'];
$is_valid = password_verify($old_pass, $db_pass);

if (!$is_valid) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Stare hasło jest nieprawidłowe.'];
} else {
    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
    $upd = mysqli_prepare($con, "UPDATE users SET password = ? WHERE userID = ?");
    mysqli_stmt_bind_param($upd, "si", $new_hash, $_SESSION['userID']);
    mysqli_stmt_execute($upd);
    
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Hasło zostało zmienione.'];
}

//var_dump($_SESSION['flash_message']) ;
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
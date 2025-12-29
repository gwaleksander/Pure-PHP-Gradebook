<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['userID'])) die("Błąd sesji.");
require_post();

$new_username = trim($_POST['new_username']);
$userID = $_SESSION['userID'];
$redirect_url = $_SERVER['HTTP_REFERER'] ?? '../index.php';

if (empty($new_username)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Wszystkie pola są wymagane.'];
    header("Location: $redirect_url");
    exit();
}

if($_SESSION["username"] === $new_username) {
    $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Nowy login jest taki sam jak obecny.'];
    header("Location: $redirect_url");
    exit();
}

$stmt_check = mysqli_prepare($con, "SELECT userID FROM users WHERE username = ? AND userID != ?");
mysqli_stmt_bind_param($stmt_check, "si", $new_username, $userID);
mysqli_stmt_execute($stmt_check);
if (mysqli_stmt_fetch($stmt_check)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Taki login jest już zajęty.'];
    header("Location: $redirect_url");
    exit();
}
mysqli_stmt_close($stmt_check);

$upd = mysqli_prepare($con, "UPDATE users SET username = ? WHERE userID = ?");
mysqli_stmt_bind_param($upd, "si", $new_username, $userID);
if (mysqli_stmt_execute($upd)) {
    $_SESSION['username'] = $new_username;
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Login został zmieniony.'];
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd bazy danych.'];
}

header("Location: $redirect_url");
exit();

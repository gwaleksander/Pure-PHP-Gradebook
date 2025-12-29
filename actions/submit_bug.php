<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['userID'])) { die("Błąd sesji"); }
require_post();

$content = trim($_POST['bug_content'] ?? '');
if (empty($content)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Opis błędu nie może być pusty.'];
} else {
    $stmt = mysqli_prepare($con, "INSERT INTO bug_reports (userID, content) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "is", $_SESSION['userID'], $content);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Zgłoszenie zostało wysłane do administratora.'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd bazy danych.'];
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
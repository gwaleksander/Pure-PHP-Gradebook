<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';
require_once '../utils/app_settings.php';

require_role('admin');
require_post();

$new_semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);

if ($new_semester === 1 || $new_semester === 2) {
    if (get_app_setting('current_semester') == $new_semester) {
        $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Aktualny semestr jest już ustawiony na: ' . $new_semester];
        header('Location: ../gradebook/admin/dashboard.php?tab=system_manager');
        exit();
    }
    if (set_app_setting('current_semester', $new_semester)) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Zmieniono aktualny semestr na: ' . $new_semester];
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Błąd bazy danych.'];
    }
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Nieprawidłowa wartość semestru.'];
}

header('Location: ../gradebook/admin/dashboard.php?tab=system_manager');
exit();

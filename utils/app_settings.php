<?php
require_once __DIR__ . '/db_connect.php';

function get_app_setting($key) {
    global $con;
    $query = "SELECT setting_value FROM app_settings WHERE setting_key = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $key);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['setting_value'] : null;
}

function set_app_setting($key, $value) {
    global $con;
    $query = "INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sss", $key, $value, $value);
    return mysqli_stmt_execute($stmt);
}

function get_current_semester() {
    $val = get_app_setting('current_semester');
    return $val ? (int)$val : 1;
}
?>
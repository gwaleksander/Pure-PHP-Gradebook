<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'school';

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8mb4");
?>
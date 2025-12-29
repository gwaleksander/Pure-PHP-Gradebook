<?php
session_start();
require_once '../utils/strings.php';

if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

$role = $_SESSION['role'];
$location = '';

switch ($role) {
    case 'student':
        $location = '/gradebook/student/dashboard.php';
        break;
    case 'parent':
        $location = '/gradebook/parent/dashboard.php';
        break;
    case 'teacher':
        $location = '/gradebook/teacher/dashboard.php';
        break;
    case 'admin':
        $location = '/gradebook/admin/dashboard.php';
        break;
    default:
        $location = '/login/logout.php';
        break;
}

header('Location: ' . BASE_URL . $location);
exit();
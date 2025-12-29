<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

require_role('admin');

$input = json_decode(file_get_contents('php://input'), true);
$reportID = filter_var($input['reportID'] ?? 0, FILTER_VALIDATE_INT);
$status = $input['status'] ?? '';

if (!$reportID || !in_array($status, ['new', 'read', 'resolved'])) {
    echo json_encode(['success' => false, 'message' => 'Błędne dane']);
    exit();
}

$stmt = mysqli_prepare($con, "UPDATE bug_reports SET status = ? WHERE reportID = ?");
mysqli_stmt_bind_param($stmt, "si", $status, $reportID);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Błąd bazy']);
}
exit();
?>
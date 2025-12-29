<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

$input = json_decode(file_get_contents('php://input'), true);
$messageID = $input['message_id'] ?? 0;
$userID = $_SESSION['userID'];

$stmt = mysqli_prepare($con, "UPDATE messages SET is_read = 1 WHERE messageID = ? AND receiverID = ?");
mysqli_stmt_bind_param($stmt, "ii", $messageID, $userID);
mysqli_stmt_execute($stmt);

echo json_encode(['success' => true]);
?>
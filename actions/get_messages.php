<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['userID'])) { http_response_code(403); exit(); }

$current_user_id = $_SESSION['userID'];
$folder = $_GET['folder'] ?? 'inbox';

$view_user_id = $current_user_id;
if ($_SESSION['role'] === 'parent' && isset($_GET['mailbox_user_id'])) {
    $requested_child_id = (int)$_GET['mailbox_user_id'];
    $check = mysqli_query($con, "SELECT 1 FROM student_parent WHERE parentID=$current_user_id AND studentID=$requested_child_id");
    if (mysqli_num_rows($check) > 0) {
        $view_user_id = $requested_child_id;
    }
}

$messages = [];

if ($folder === 'inbox') {
    $query = "
        SELECT m.*, 
               COALESCE(s.first_name, t.first_name, p.first_name, 'Admin') as other_first_name,
               COALESCE(s.last_name, t.last_name, p.last_name, '') as other_last_name,
               u.role as other_role
        FROM messages m
        JOIN users u ON m.senderID = u.userID
        LEFT JOIN students s ON u.userID = s.studentID
        LEFT JOIN teachers t ON u.userID = t.teacherID
        LEFT JOIN parents p ON u.userID = p.parentID
        WHERE m.receiverID = ?
        ORDER BY m.created_at DESC";
} else {
    $query = "
        SELECT m.*, 
               COALESCE(s.first_name, t.first_name, p.first_name, 'Admin') as other_first_name,
               COALESCE(s.last_name, t.last_name, p.last_name, '') as other_last_name,
               u.role as other_role
        FROM messages m
        JOIN users u ON m.receiverID = u.userID
        LEFT JOIN students s ON u.userID = s.studentID
        LEFT JOIN teachers t ON u.userID = t.teacherID
        LEFT JOIN parents p ON u.userID = p.parentID
        WHERE m.senderID = ?
        ORDER BY m.created_at DESC";
}

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $view_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = [
        'id' => $row['messageID'],
        'subject' => $row['subject'],
        'content' => $row['message_content'],
        'date' => date('d.m.Y H:i', strtotime($row['created_at'])),
        'is_read' => $row['is_read'],
        'other_name' => $row['other_first_name'] . ' ' . $row['other_last_name'] . ' (' . ucfirst($row['other_role']) . ')'
    ];
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
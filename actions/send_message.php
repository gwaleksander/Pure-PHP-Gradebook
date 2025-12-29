<?php
session_start();
require_once '../utils/db_connect.php';
require_once '../utils/auth.php';

if (!isset($_SESSION['userID'])) {
    http_response_code(403); exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$senderID = $_SESSION['userID'];
$recipientType = $input['recipient_type'] ?? ''; 
$recipientID = $input['recipient_id'] ?? 0;
$subject = trim($input['subject'] ?? '');
$content = trim($input['content'] ?? '');

if (empty($subject) || empty($content)) {
    echo json_encode(['success'=>false, 'message'=>'Temat i treść są wymagane.']); exit();
}

$recipients = [];

if (in_array($recipientType, ['user', 'student', 'teacher', 'parent'])) {
    if ($recipientID > 0) {
        $recipients[] = (int)$recipientID;
    }

} elseif ($recipientType === 'teachers') {
    $res = mysqli_query($con, "SELECT teacherID FROM teachers");
    while($row = mysqli_fetch_assoc($res)) $recipients[] = $row['teacherID'];

} elseif ($recipientType === 'class') {
    $classID = (int)$recipientID;
    if ($classID > 0) {
        $res = mysqli_query($con, "SELECT studentID FROM students WHERE classID = $classID");
        while($row = mysqli_fetch_assoc($res)) $recipients[] = $row['studentID'];
    }

} elseif ($recipientType === 'class_parents') {
    $classID = (int)$recipientID;
    if ($classID > 0) {
        $q = "SELECT sp.parentID FROM student_parent sp 
              JOIN students s ON sp.studentID = s.studentID 
              WHERE s.classID = $classID";
        $res = mysqli_query($con, $q);
        while($row = mysqli_fetch_assoc($res)) $recipients[] = $row['parentID'];
    }
}

$recipients = array_unique($recipients);

if (empty($recipients)) {
    echo json_encode(['success'=>false, 'message'=>'Nie znaleziono odbiorców (Błąd logiczny: typ=' . $recipientType . ', id=' . $recipientID . ')']); exit();
}

$stmt = mysqli_prepare($con, "INSERT INTO messages (senderID, receiverID, subject, message_content, created_at) VALUES (?, ?, ?, ?, NOW())");

$count = 0;
foreach ($recipients as $receiverID) {
    if ($receiverID == $senderID) continue;
    
    mysqli_stmt_bind_param($stmt, "iiss", $senderID, $receiverID, $subject, $content);
    mysqli_stmt_execute($stmt);
    $count++;
}

if ($count === 0) {
    echo json_encode(['success'=>false, 'message'=>'Nie wysłano żadnej wiadomości (możliwe, że próbowałeś wysłać tylko do siebie).']);
} else {
    echo json_encode(['success'=>true, 'message'=>"Wiadomośc została wysłana."]);
}
?>
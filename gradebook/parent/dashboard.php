<?php
session_start();
require_once '../../utils/db_connect.php';
require_once '../../utils/auth.php';
require_once '../../utils/app_settings.php';

require_role('parent');

if (isset($_GET['child_id'])) {
    $is_valid_child = false;
    foreach ($_SESSION['children'] as $child) {
        if ($child['studentID'] == $_GET['child_id']) {
            $is_valid_child = true;
            break;
        }
    }
    if ($is_valid_child) {
        $_SESSION['selected_child_id'] = (int)$_GET['child_id'];
    }
    header('Location: dashboard.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'switch_child') {
    unset($_SESSION['selected_child_id']);
    header('Location: dashboard.php');
    exit();
}

$selected_child_id = $_SESSION['selected_child_id'] ?? null;
$pageTitle = 'Panel Rodzica';

$allowed_tabs = ['select_child', 'parent_info', 'messages'];
$menuItems = [];

$menuItems[] = ['id' => 'parent_info', 'label' => 'Moje Informacje', 'onclick' => "openTab('parent_info', this)"];
$menuItems[] = ['id' => 'messages', 'label' => 'Moje Wiadomości', 'onclick' => "openTab('messages', this)"];
$menuItems[] = ['id' => 'select_child', 'label' => 'Lista Dzieci', 'onclick' => "window.location.href='dashboard.php?action=switch_child'"];

if ($selected_child_id) {
    $menuItems[] = ['id' => 'separator'];
    $allowed_tabs = array_merge($allowed_tabs, ['information', 'grades', 'statistics', 'notes', 'child_messages']);
    $activeTab = (isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)) ? $_GET['tab'] : 'information';

    $menuItems[] = ['id' => 'information', 'label' => 'Informacje (Uczeń)', 'onclick' => "openTab('information', this)"];
    $menuItems[] = ['id' => 'grades', 'label' => 'Oceny', 'onclick' => "openTab('grades', this)"];
    $menuItems[] = ['id' => 'statistics', 'label' => 'Statystyka', 'onclick' => "openTab('statistics', this)"];
    $menuItems[] = ['id' => 'notes', 'label' => 'Uwagi', 'onclick' => "openTab('notes', this)"];
    $menuItems[] = ['id' => 'child_messages', 'label' => 'Poczta Dziecka', 'onclick' => "openTab('child_messages', this)"];
} else {
    $activeTab = (isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)) ? $_GET['tab'] : 'select_child';
}

require_once '../../templates/header.php';
require_once '../../templates/sidebar.php';
?>

<div class="content-area">
    <?php
    if (isset($_SESSION['flash_message'])):
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    ?>
        <div class="flash-notification <?php echo htmlspecialchars($message['type']); ?>">
            <span><?php echo htmlspecialchars($message['message']); ?></span>
            <button class="close-flash-btn">&times;</button>
        </div>
    <?php endif; ?>

    <div id="js-flash-container"></div>

    <div id="parent_info" class="card <?php if ($activeTab === 'parent_info') echo 'active'; ?>">
        <?php include('views/parent_info_view.php'); ?>
    </div>

    <div id="messages" class="card <?php if ($activeTab === 'messages') echo 'active'; ?>">
        <?php
        $messages_view_mode = 'parent_own';
        $messages_target_user_id = $_SESSION['userID'];
        include '../../templates/messages_view.php';
        ?>
    </div>

    <?php if ($selected_child_id): ?>
        <?php
        if (!isset($child_details)) {
            $stmt = mysqli_prepare($con, "SELECT s.*, c.name as class_name, t.first_name as class_teacher_first_name, t.last_name as class_teacher_last_name, u.email, u.phone, u.username FROM students s JOIN users u ON s.studentID = u.userID JOIN classes c ON s.classID = c.classID JOIN teachers t ON c.teacherID = t.teacherID WHERE s.studentID = ?");
            mysqli_stmt_bind_param($stmt, "i", $selected_child_id);
            mysqli_stmt_execute($stmt);
            $child_details = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        }
        ?>

        <div id="information" class="card <?php if ($activeTab === 'information') echo 'active'; ?>">
            <h1 style="text-align:center;">Panel ucznia: <?php echo htmlspecialchars($child_details['first_name']); ?></h1>
            <?php $student_info = $child_details;
            include('views/info_view_wrapper.php'); ?>
        </div>
        <div id="grades" class="card <?php if ($activeTab === 'grades') echo 'active'; ?>">
            <?php $student_id_for_view = $child_details['studentID'];
            require __DIR__ . '/../student/views/grades_view.php'; ?>
        </div>
        <div id="statistics" class="card <?php if ($activeTab === 'statistics') echo 'active'; ?>">
            <?php $student_id_for_view = $child_details['studentID'];
            $class_id_for_view = $child_details['classID'];
            require __DIR__ . '/../student/views/statistics_view.php'; ?>
        </div>
        <div id="notes" class="card <?php if ($activeTab === 'notes') echo 'active'; ?>">
            <?php $student_id_for_view = $child_details['studentID'];
            require __DIR__ . '/../student/views/notes_view.php'; ?>
        </div>
        <div id="child_messages" class="card <?php if ($activeTab === 'child_messages') echo 'active'; ?>">
            <?php
            $messages_view_mode = 'child_read_only';
            $messages_target_user_id = $child_details['studentID'];
            include '../../templates/messages_view.php';
            ?>
        </div>

    <?php else: ?>
        <div id="select_child" class="card <?php if ($activeTab === 'select_child') echo 'active'; ?>">
            <?php include('views/select_child_view.php'); ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../templates/footer.php'; ?>
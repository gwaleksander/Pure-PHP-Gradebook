<?php
session_start();
require_once '../../utils/db_connect.php';
require_once '../../utils/auth.php';
require_once '../../utils/app_settings.php';

if ($_SESSION['role'] !== 'admin') {
    die('<h1>Brak uprawnień!</h1><p>Nie masz dostępu do tej strony.</p>');
}

$current_semester = get_current_semester();
$pageTitle = 'Panel Administratora';
$allowed_tabs = ['users_manager', 'classes_manager', 'subjects_manager', 'assignments_manager', 'system_manager', 'messages', 'bug_reports'];
$activeTab = (isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)) ? $_GET['tab'] : 'users_manager';

$menuItems = [
    ['id' => 'users_manager', 'label' => 'Zarządzaj Użytkownikami', 'onclick' => "openTab('users_manager', this)"],
    ['id' => 'classes_manager', 'label' => 'Zarządzaj Klasami', 'onclick' => "openTab('classes_manager', this)"],
    ['id' => 'subjects_manager', 'label' => 'Zarządzaj Przedmiotami', 'onclick' => "openTab('subjects_manager', this)"],
    ['id' => 'assignments_manager', 'label' => 'Zarządzaj Przypisaniami', 'onclick' => "openTab('assignments_manager', this)"],
    ['id' => 'system_manager', 'label' => 'Zarządzaj Systemem', 'onclick' => "openTab('system_manager', this)"],
    ['id' => 'messages', 'label' => 'Wiadomości', 'onclick' => "openTab('messages', this)"],
    ['id' => 'bug_reports', 'label' => 'Zgłoszenia Błędów', 'onclick' => "openTab('bug_reports', this)"]
];

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

    <div id="users_manager" class="card <?php if ($activeTab === 'users_manager') echo 'active'; ?>">
        <?php include('views/users_manager_view.php'); ?>
    </div>
    <div id="classes_manager" class="card <?php if ($activeTab === 'classes_manager') echo 'active'; ?>">
        <?php include('views/classes_manager_view.php'); ?>
    </div>
    <div id="subjects_manager" class="card <?php if ($activeTab === 'subjects_manager') echo 'active'; ?>">
        <?php include('views/subjects_manager_view.php'); ?>
    </div>
    <div id="assignments_manager" class="card <?php if ($activeTab === 'assignments_manager') echo 'active'; ?>">
        <?php include('views/assignments_manager_view.php'); ?>
    </div>
    <div id="system_manager" class="card <?php if ($activeTab === 'system_manager') echo 'active'; ?>">
        <?php include('views/system_manager_view.php'); ?>
    </div>
    <div id="messages" class="card <?php if ($activeTab === 'messages') echo 'active'; ?>">
        <?php include('../../templates/messages_view.php'); ?>
    </div>
    <div id="bug_reports" class="card <?php if ($activeTab === 'bug_reports') echo 'active'; ?>">
        <?php include('views/bug_reports_view.php'); ?>
    </div>

</div>

<?php require_once '../../templates/footer.php'; ?>
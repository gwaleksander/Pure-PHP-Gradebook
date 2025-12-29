<?php
session_start();
require_once '../../utils/strings.php';
require_once '../../utils/db_connect.php';
require_once '../../utils/auth.php';

require_role('teacher');

$pageTitle = 'Panel Nauczyciela';
$allowed_tabs = ['grades_manager', 'notes_manager', 'information', 'messages'];
$activeTab = (isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)) ? $_GET['tab'] : 'grades_manager';

$menuItems = [
    ['id' => 'grades_manager', 'label' => 'Zarządzaj Ocenami', 'onclick' => "openTab('grades_manager', this)"],
    ['id' => 'notes_manager', 'label' => 'Zarządzaj Uwagami', 'onclick' => "openTab('notes_manager', this)"],
    ['id' => 'information', 'label' => 'Moje Informacje', 'onclick' => "openTab('information', this)"],
    ['id' => 'messages', 'label' => 'Wiadomości', 'onclick' => "openTab('messages', this)"]
];

require_once '../../templates/header.php';
require_once '../../templates/sidebar.php';
require_once '../../utils/app_settings.php';

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

    <div id="grades_manager" class="card <?php if ($activeTab === 'grades_manager') echo 'active'; ?>">
        <?php include('views/grades_manager_view.php'); ?>
    </div>
    <div id="notes_manager" class="card <?php if ($activeTab === 'notes_manager') echo 'active'; ?>">
        <?php include('views/notes_manager_view.php'); ?>
    </div>
    <div id="information" class="card <?php if ($activeTab === 'information') echo 'active'; ?>">
        <?php include('views/info_view.php'); ?>
    </div>
    <div id="messages" class="card <?php if ($activeTab === 'messages') echo 'active'; ?>">
        <?php include('../../templates/messages_view.php'); ?>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>
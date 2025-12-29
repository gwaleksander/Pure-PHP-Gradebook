<?php
session_start();
require_once '../../utils/db_connect.php';
require_once '../../utils/auth.php';
require_once '../../utils/app_settings.php';

require_role('student');

$pageTitle = 'Panel Studenta';
$allowed_tabs = ['information', 'grades', 'statistics', 'notes', 'messages'];
$activeTab = (isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)) ? $_GET['tab'] : 'information';

$menuItems = [
    ['id' => 'information', 'label' => 'Informacje', 'onclick' => "openTab('information', this)"],
    ['id' => 'grades', 'label' => 'Oceny', 'onclick' => "openTab('grades', this)"],
    ['id' => 'statistics', 'label' => 'Statystyka', 'onclick' => "openTab('statistics', this)"],
    ['id' => 'notes', 'label' => 'Uwagi', 'onclick' => "openTab('notes', this)"],
    ['id' => 'messages', 'label' => 'Wiadomości', 'onclick' => "openTab('messages', this)"]
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
    <div id="js-flash-container"></div>

    <div id="information" class="card <?php if ($activeTab === 'information') echo 'active'; ?>">
        <?php include('views/info_view.php'); ?>
    </div>
    <div id="grades" class="card <?php if ($activeTab === 'grades') echo 'active'; ?>">
        <?php include('views/grades_view.php'); ?>
    </div>
    <div id="statistics" class="card <?php if ($activeTab === 'statistics') echo 'active'; ?>">
        <?php include('views/statistics_view.php'); ?>
    </div>
    <div id="notes" class="card <?php if ($activeTab === 'notes') echo 'active'; ?>">
        <?php include('views/notes_view.php'); ?>
    </div>
    <div id="messages" class="card <?php if ($activeTab === 'messages') echo 'active'; ?>">
        <?php include('../../templates/messages_view.php'); ?>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>
<?php
$allowed_tabs = ['information', 'grades', 'statistics', 'notes', 'messages'];
$activeTab = (isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs)) ? $_GET['tab'] : 'information';
?>

<h1 style="text-align: center;">Panel ucznia: <?php echo htmlspecialchars($child_details['first_name'] . ' ' . $child_details['last_name']); ?></h1>

<div id="information" class="card <?php if ($activeTab === 'information') echo 'active'; ?>">
    <?php 
    $student_info = $child_details;
    include('info_view_wrapper.php'); 
    ?>
</div>
<div id="grades" class="card <?php if ($activeTab === 'grades') echo 'active'; ?>">
    <?php
    $student_id_for_view = $child_details['studentID'];
    require __DIR__ . '/../../student/views/grades_view.php';
    ?>
</div>
<div id="statistics" class="card <?php if ($activeTab === 'statistics') echo 'active'; ?>">
    <?php 
    $student_id_for_view = $child_details['studentID'];
    $class_id_for_view = $child_details['classID'];
    require __DIR__ . '/../../student/views/statistics_view.php';
    ?>
</div>
<div id="notes" class="card <?php if ($activeTab === 'notes') echo 'active'; ?>">
    <?php
    $student_id_for_view = $child_details['studentID'];
    require __DIR__ . '/../../student/views/notes_view.php';
    ?>
</div>
<div id="messages" class="card <?php if ($activeTab === 'messages') echo 'active'; ?>">
    <?php include(__DIR__ . '/../../../templates/messages_view.php'); ?>
</div>
<?php
require_once '../../utils/app_settings.php';

$student_id = $student_id_for_view ?? $_SESSION['userID'];
$current_system_semester = get_current_semester();
$selected_semester = filter_input(INPUT_GET, 'semester', FILTER_VALIDATE_INT) ?? $current_system_semester;

$query = "SELECT 
            n.note_content, n.note_type, n.date_added,
            t.first_name, t.last_name
          FROM notes n
          JOIN teachers t ON n.teacherID = t.teacherID
          WHERE n.studentID = ? AND n.semester = ?
          ORDER BY n.date_added DESC";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ii", $student_id, $selected_semester);
mysqli_stmt_execute($stmt);
$notes_result = mysqli_stmt_get_result($stmt);
$notes = mysqli_fetch_all($notes_result, MYSQLI_ASSOC);
?>

<div class="view-header">
    <h1>Uwagi ucznia</h1>

    <form action="" method="GET">
        <?php if (isset($_GET['child_id'])): ?>
            <input type="hidden" name="child_id" value="<?php echo htmlspecialchars($_GET['child_id']); ?>">
        <?php endif; ?>
        <input type="hidden" name="tab" value="notes">

        <label for="note_semester_select" style="font-weight: 500;">Semestr:</label>
        <select name="semester" id="note_semester_select" onchange="this.form.submit()" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            <option value="1" <?php echo $selected_semester == 1 ? 'selected' : ''; ?>>Semestr 1</option>
            <option value="2" <?php echo $selected_semester == 2 ? 'selected' : ''; ?>>Semestr 2</option>
        </select>
    </form>
</div>

<div class="notes-list">
    <?php if (empty($notes)): ?>
        <p class="no-notes-info">Brak uwag w semestrze <?php echo $selected_semester; ?>.</p>
    <?php else: ?>
        <?php foreach ($notes as $note):
            $teacherName = htmlspecialchars($note['first_name'] . ' ' . $note['last_name']);
            $noteDate = date('d.m.Y H:i', strtotime($note['date_added']));
            $noteContent = htmlspecialchars($note['note_content']);
            $noteContentJson = json_encode($note['note_content']);
        ?>
            <div class="note-item <?php echo htmlspecialchars($note['note_type']); ?>"
                style="cursor: pointer;"
                onclick='openNoteDetails("<?php echo $teacherName; ?>", "<?php echo $noteDate; ?>", <?php echo $noteContentJson; ?>)'>

                <div class="note-header">
                    <span class="note-teacher"><?php echo $teacherName; ?></span>
                    <span class="note-date"><?php echo $noteDate; ?></span>
                </div>
                <div class="note-content">
                    <?php echo substr($note['note_content'], 0, 100) . (strlen($note['note_content']) > 100 ? '...' : ''); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="modal-overlay" id="noteDetailsModal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h2>Szczegóły uwagi</h2><button class="close-modal-btn">&times;</button>
        </div>
        <div style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <strong>Nauczyciel:</strong> <span id="modal-note-teacher"></span><br>
            <strong>Data:</strong> <span id="modal-note-date" style="color:#666;"></span>
        </div>
        <div id="modal-note-content" style="white-space: pre-wrap; line-height: 1.6;"></div>
        
        <div style="margin-top: 20px; text-align: right;">
            <button class="action-btn delete-btn" onclick="document.getElementById('noteDetailsModal').classList.remove('active')">Zamknij</button>
        </div>
    </div>
</div>

<script>
function openNoteDetails(teacher, date, content) {
    document.getElementById('modal-note-teacher').innerText = teacher;
    document.getElementById('modal-note-date').innerText = date;
    document.getElementById('modal-note-content').innerText = content;
    document.getElementById('noteDetailsModal').classList.add('active');
}
</script>
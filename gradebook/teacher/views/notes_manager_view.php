<?php
require_once '../../utils/app_settings.php';
require_role('teacher');

$teacher_id = $_SESSION['userID'];
$system_semester = get_current_semester();
$selected_semester = filter_input(INPUT_GET, 'semester', FILTER_VALIDATE_INT) ?? $system_semester;
$selected_class_id = filter_input(INPUT_GET, 'class_id_notes', FILTER_VALIDATE_INT);

$classes_query = "SELECT DISTINCT c.classID, c.name FROM classes c JOIN class_subjects_teacher cst ON c.classID = cst.classID WHERE cst.teacherID = ? ORDER BY c.name";
$stmt_classes = mysqli_prepare($con, $classes_query);
mysqli_stmt_bind_param($stmt_classes, "i", $teacher_id);
mysqli_stmt_execute($stmt_classes);
$classes = mysqli_fetch_all(mysqli_stmt_get_result($stmt_classes), MYSQLI_ASSOC);

$students_notes = [];
if ($selected_class_id) {
    $query = "SELECT 
                s.studentID, s.first_name, s.last_name,
                n.noteID, n.note_content, n.note_type, n.teacherID as note_author_id, n.date_added
              FROM students s
              LEFT JOIN notes n ON s.studentID = n.studentID AND n.semester = ?
              WHERE s.classID = ?
              ORDER BY s.last_name, s.first_name, n.date_added DESC";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $selected_semester, $selected_class_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $studentID = $row['studentID'];
        if (!isset($students_notes[$studentID])) {
            $students_notes[$studentID] = [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'notes' => []
            ];
        }
        if ($row['noteID']) {
            $students_notes[$studentID]['notes'][] = $row;
        }
    }
}

$redirect_params = "tab=notes_manager&class_id_notes=$selected_class_id&semester=$selected_semester";
?>

<h1>Zarządzanie Uwagami</h1>

<form action="" method="GET" class="selection-form">
    <input type="hidden" name="tab" value="notes_manager">
    
    <div class="form-group">
        <label for="class_id_notes">Klasa:</label>
        <select name="class_id_notes" id="class_id_notes" onchange="this.form.submit()">
            <option value="">-- Wybierz --</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['classID']; ?>" <?php echo ($selected_class_id == $class['classID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($class['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($selected_class_id): ?>
    <div class="form-group">
        <label for="semester">Semestr:</label>
        <select name="semester" id="semester" onchange="this.form.submit()" style="min-width: 120px;">
            <option value="1" <?php echo $selected_semester == 1 ? 'selected' : ''; ?>>Semestr 1</option>
            <option value="2" <?php echo $selected_semester == 2 ? 'selected' : ''; ?>>Semestr 2</option>
        </select>
    </div>
    <?php endif; ?>
</form>

<?php if (!empty($students_notes)): ?>
    <div class="table-actions">
        <button class="print-btn" onclick="window.print()">Drukuj Raport Uwag</button>
    </div>

    <div id="printable-teacher-notes-report" class="print-only">
        <div class="report-header">
            <h1>Raport Uwag - Semestr <?php echo $selected_semester; ?></h1>
            <p><?php echo SCHOOL_NAME; ?></p>
            <p>Data wygenerowania: <?php echo date("d.m.Y"); ?></p>
        </div>

        <div class="report-info-grid">
            <div>
                <strong>Generujący:</strong> <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
            </div>
            <div style="text-align: right;">
                <strong>Klasa:</strong> 
                <?php foreach($classes as $c) { if($c['classID'] == $selected_class_id) echo htmlspecialchars($c['name']); } ?>
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Uczeń</th>
                    <th style="width: 15%;">Data</th>
                    <th style="width: 10%;">Typ</th>
                    <th>Treść uwagi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $has_notes = false;
                foreach ($students_notes as $student): 
                    if (!empty($student['notes'])):
                        $has_notes = true;
                        foreach ($student['notes'] as $note):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($note['date_added'])); ?></td>
                    <td class="note-type-cell">
                        <?php 
                            $pl_types = ['positive' => 'Pozytywna', 'negative' => 'Negatywna', 'neutral' => 'Neutralna'];
                            echo $pl_types[$note['note_type']] ?? $note['note_type']; 
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($note['note_content']); ?></td>
                </tr>
                <?php 
                        endforeach;
                    endif;
                endforeach; 
                if (!$has_notes):
                ?>
                <tr><td colspan="4" style="text-align:center;">Brak uwag w wybranej klasie w tym semestrze.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="report-footer">
            <div class="signature-line">Podpis wychowawcy</div>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Imię i Nazwisko</th>
                    <th>Wystawione uwagi</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students_notes as $studentID => $student): 
                    $student_full_name = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
                ?>
                <tr>
                    <td><?php echo $student_full_name; ?></td>
                    <td>
                        <div class="notes-list-teacher">
                        <?php if (empty($student['notes'])): ?>
                            <span class="no-notes-info-small">Brak uwag</span>
                        <?php else: ?>
                            <?php foreach ($student['notes'] as $note): ?>
                                <?php if ($note['note_author_id'] == $teacher_id): ?>
                                    <div class="note-item-teacher-view <?php echo htmlspecialchars($note['note_type']); ?>"
                                         data-note-id="<?php echo $note['noteID']; ?>"
                                         data-note-content="<?php echo htmlspecialchars($note['note_content']); ?>"
                                         data-note-type="<?php echo htmlspecialchars($note['note_type']); ?>"
                                         data-student-name="<?php echo $student_full_name; ?>">
                                        <?php echo htmlspecialchars(substr($note['note_content'], 0, 80)) . (strlen($note['note_content']) > 80 ? '...' : ''); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="note-item-teacher-view readonly <?php echo htmlspecialchars($note['note_type']); ?>">
                                        <?php echo htmlspecialchars(substr($note['note_content'], 0, 80)) . (strlen($note['note_content']) > 80 ? '...' : ''); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </td>
                    <td class="action-cell">
                        <button class="action-btn add-note-btn" data-student-id="<?php echo $studentID; ?>" data-student-name="<?php echo $student_full_name; ?>">Dodaj Uwagę</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($selected_class_id): ?>
    <p style="text-align:center; margin-top:20px;">Brak uczniów w tej klasie.</p>
<?php endif; ?>

<div class="modal-overlay" id="addNoteModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Dodaj uwagę dla: <span id="note-modal-student-name"></span></h2>
            <button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/add_note.php" method="POST" class="modal-form">
            <input type="hidden" name="student_id" id="note_modal_student_id">
            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
            
            <input type="hidden" name="semester" value="<?php echo $selected_semester; ?>">
            
            <input type="hidden" name="redirect_url" value="<?php echo $redirect_params; ?>">

            <div class="form-group">
                <label for="note_content">Treść uwagi:</label>
                <textarea name="note_content" id="note_content" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Typ uwagi:</label>
                <div class="radio-group">
                    <label><input type="radio" name="note_type" value="positive" checked> Pozytywna</label>
                    <label><input type="radio" name="note_type" value="neutral"> Neutralna</label>
                    <label><input type="radio" name="note_type" value="negative"> Negatywna</label>
                </div>
            </div>
            <button type="submit">Zapisz Uwagę</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editNoteModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Edycja uwagi: <span id="edit-note-student-name"></span></h2>
            <button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/edit_note.php" method="POST" class="modal-form">
            <input type="hidden" name="note_id" id="edit_note_id">
            <input type="hidden" name="redirect_url" value="<?php echo $redirect_params; ?>">

            <div class="form-group">
                <label for="edit_note_content">Treść uwagi:</label>
                <textarea name="note_content" id="edit_note_content" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label>Typ uwagi:</label>
                <div class="radio-group" id="edit_note_type_group">
                    <label><input type="radio" name="note_type" value="positive"> Pozytywna</label>
                    <label><input type="radio" name="note_type" value="neutral"> Neutralna</label>
                    <label><input type="radio" name="note_type" value="negative"> Negatywna</label>
                </div>
            </div>
            <div class="modal-footer-buttons">
                <button type="submit" class="btn-update">Zapisz zmiany</button>
        </form>
        <form action="../../actions/delete_note.php" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć tę uwagę?');">
            <input type="hidden" name="note_id" id="delete_note_id">
            <input type="hidden" name="redirect_url" value="<?php echo $redirect_params; ?>">
            <button type="submit" class="btn-delete">Usuń uwagę</button>
        </form>
            </div>
    </div>
</div>
<?php
$query_classes = "SELECT classID, name FROM classes ORDER BY name";
$classes = mysqli_fetch_all(mysqli_query($con, $query_classes), MYSQLI_ASSOC);

$query_subjects = "SELECT subjectID, name FROM subjects ORDER BY name";
$subjects = mysqli_fetch_all(mysqli_query($con, $query_subjects), MYSQLI_ASSOC);

$query_teachers = "SELECT teacherID, first_name, last_name FROM teachers ORDER BY last_name, first_name";
$teachers = mysqli_fetch_all(mysqli_query($con, $query_teachers), MYSQLI_ASSOC);

$selected_class_id = filter_input(INPUT_GET, 'class_id_assignments', FILTER_VALIDATE_INT);
$assignments = [];
if ($selected_class_id) {
    $query_assignments = "
        SELECT cst.subjectID, cst.teacherID, s.name as subject_name, t.first_name, t.last_name
        FROM class_subjects_teacher cst
        JOIN subjects s ON cst.subjectID = s.subjectID
        JOIN teachers t ON cst.teacherID = t.teacherID
        WHERE cst.classID = ?
        ORDER BY s.name";
    $stmt = mysqli_prepare($con, $query_assignments);
    mysqli_stmt_bind_param($stmt, "i", $selected_class_id);
    mysqli_stmt_execute($stmt);
    $assignments = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}
?>

<h1>Zarządzanie Przypisaniami Nauczycieli do Przedmiotów</h1>

<div class="filters-toolbar">
    
    <form action="" method="GET" class="selection-form">
        <input type="hidden" name="tab" value="assignments_manager">
        <div class="form-group">
            <label for="class_id_assignments">Wybierz klasę:</label>
            <select name="class_id_assignments" id="class_id_assignments" onchange="this.form.submit()" class="searchable" style="min-width: 250px;">
                <option value="">-- Wybierz --</option>
                <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['classID']; ?>" <?php echo ($selected_class_id == $class['classID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($class['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($selected_class_id): ?>
    <div class="table-actions">
        <button class="action-btn add-btn" id="openAddAssignmentModalBtn">Dodaj przedmiot do tej klasy</button>
    </div>
    <?php endif; ?>

</div>

<?php if ($selected_class_id): ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Przedmiot</th>
                    <th>Nauczyciel prowadzący</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($assignments)): ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">Ta klasa nie ma jeszcze przypisanych żadnych przedmiotów.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($assignments as $assignment): ?>
                    <tr data-subject-id="<?php echo $assignment['subjectID']; ?>" data-teacher-id="<?php echo $assignment['teacherID']; ?>">
                        <td><?php echo htmlspecialchars($assignment['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($assignment['first_name'] . ' ' . $assignment['last_name']); ?></td>
                        <td class="action-cell">
                            <button class="action-btn edit-btn auto-edit-btn"
                                data-target="#editAssignmentModal"
                                data-fill-subjectID="<?php echo $assignment['subjectID']; ?>"
                                data-fill-teacherID="<?php echo $assignment['teacherID']; ?>">
                                Zmień nauczyciela
                            </button>
                            <?php
                            $delPayload = htmlspecialchars(json_encode([
                                'classID' => $selected_class_id,
                                'subjectID' => $assignment['subjectID'],
                                'teacherID' => $assignment['teacherID']
                            ]));
                            ?>
                            <button class="action-btn delete-btn auto-delete-btn"
                                data-action="../../actions/delete_assignment.php"
                                data-confirm="Usunąć to przypisanie?"
                                data-payload="<?php echo $delPayload; ?>">
                                Usuń
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal-overlay" id="addAssignmentModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Dodaj przedmiot do klasy</h2><button class="close-modal-btn">&times;</button>
            </div>
            <form action="../../actions/add_assignment.php" method="POST" class="modal-form">
                <input type="hidden" name="classID" value="<?php echo $selected_class_id; ?>">
                <div class="form-group"><label>Przedmiot:</label>
                    <select name="subjectID" class="searchable" required>
                        <option value="">-- Wybierz przedmiot --</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['subjectID']; ?>"><?php echo htmlspecialchars($subject['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Nauczyciel:</label>
                    <select name="teacherID" class="searchable" required>
                        <option value="">-- Wybierz nauczyciela --</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo $teacher['teacherID']; ?>"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Dodaj przypisanie</button>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="editAssignmentModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Zmień nauczyciela dla przedmiotu</h2><button class="close-modal-btn">&times;</button>
            </div>
            <form action="../../actions/edit_assignment.php" method="POST" class="modal-form">
                <input type="hidden" name="classID" value="<?php echo $selected_class_id; ?>">
                <input type="hidden" name="subjectID" id="edit_assignment_subjectID">
                <div class="form-group"><label>Nauczyciel:</label>
                    <select name="teacherID" id="edit_assignment_teacherID" class="searchable" required>
                        <option value="">-- Wybierz nauczyciela --</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo $teacher['teacherID']; ?>"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-update">Zapisz zmiany</button>
            </form>
        </div>
    </div>

<?php endif; ?>
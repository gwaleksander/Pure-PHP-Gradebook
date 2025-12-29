<?php
require_once '../../utils/app_settings.php';
require_role('teacher');

$teacher_id = $_SESSION['userID'];
$system_semester = get_current_semester();
$selected_semester = filter_input(INPUT_GET, 'semester', FILTER_VALIDATE_INT) ?? $system_semester;
$selected_class_id = filter_input(INPUT_GET, 'class_id', FILTER_VALIDATE_INT);
$selected_subject_id = filter_input(INPUT_GET, 'subject_id', FILTER_VALIDATE_INT);

$redirect_params = "tab=grades_manager&class_id=$selected_class_id&subject_id=$selected_subject_id&semester=$selected_semester";

$classes_query = "SELECT DISTINCT c.classID, c.name FROM classes c JOIN class_subjects_teacher cst ON c.classID = cst.classID WHERE cst.teacherID = ? ORDER BY c.name";
$stmt_classes = mysqli_prepare($con, $classes_query);
mysqli_stmt_bind_param($stmt_classes, "i", $teacher_id);
mysqli_stmt_execute($stmt_classes);
$classes = mysqli_fetch_all(mysqli_stmt_get_result($stmt_classes), MYSQLI_ASSOC);

$subjects = [];
if ($selected_class_id) {
    $subjects_query = "SELECT DISTINCT s.subjectID, s.name FROM subjects s JOIN class_subjects_teacher cst ON s.subjectID = cst.subjectID WHERE cst.teacherID = ? AND cst.classID = ? ORDER BY s.name";
    $stmt_subjects = mysqli_prepare($con, $subjects_query);
    mysqli_stmt_bind_param($stmt_subjects, "ii", $teacher_id, $selected_class_id);
    mysqli_stmt_execute($stmt_subjects);
    $res = mysqli_stmt_get_result($stmt_subjects);
    while($s = mysqli_fetch_assoc($res)) $subjects[] = $s;
    
    $subject_ids = array_column($subjects, 'subjectID');
    if (!in_array($selected_subject_id, $subject_ids)) $selected_subject_id = null;
}

$students_data = [];
if ($selected_class_id && $selected_subject_id) {
    $query = "SELECT 
                s.studentID, s.first_name, s.last_name,
                g.gradeID, g.grade, g.weight, g.comment, g.categoryID, g.date
              FROM students s
              LEFT JOIN grades g ON s.studentID = g.studentID 
                                AND g.subjectID = ? 
                                AND g.semester = ? 
              WHERE s.classID = ?
              ORDER BY s.last_name, s.first_name, g.date";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "iii", $selected_subject_id, $selected_semester, $selected_class_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $sid = $row['studentID'];
        if (!isset($students_data[$sid])) {
            $students_data[$sid] = [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'grades' => [],
                'grade_term1' => null,
                'grade_final' => null
            ];
        }
        if ($row['gradeID']) $students_data[$sid]['grades'][] = $row;
    }

    $final_q = "SELECT studentID, grade_term1, grade_final FROM final_grades WHERE subjectID = ? AND studentID IN (SELECT studentID FROM students WHERE classID = ?)";
    $stmt_f = mysqli_prepare($con, $final_q);
    mysqli_stmt_bind_param($stmt_f, "ii", $selected_subject_id, $selected_class_id);
    mysqli_stmt_execute($stmt_f);
    $res_f = mysqli_stmt_get_result($stmt_f);
    while($row_f = mysqli_fetch_assoc($res_f)) {
        if(isset($students_data[$row_f['studentID']])) {
            $students_data[$row_f['studentID']]['grade_term1'] = $row_f['grade_term1'];
            $students_data[$row_f['studentID']]['grade_final'] = $row_f['grade_final'];
        }
    }
}

$grade_categories = mysqli_fetch_all(mysqli_query($con, "SELECT * FROM grade_categories"), MYSQLI_ASSOC);
$cats_map = [];
foreach ($grade_categories as $cat) {
    $cats_map[$cat['categoryID']] = $cat['name'];
}
?>

<h1>Zarządzanie Ocenami</h1>

<form action="" method="GET" class="selection-form">
    <input type="hidden" name="tab" value="grades_manager">
    <div class="form-group">
        <label for="class_id">Klasa:</label>
        <select name="class_id" id="class_id" onchange="this.form.submit()">
            <option value="">-- Wybierz --</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?php echo $c['classID']; ?>" <?php echo ($selected_class_id == $c['classID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if ($selected_class_id): ?>
    <div class="form-group">
        <label for="subject_id">Przedmiot:</label>
        <select name="subject_id" id="subject_id" onchange="this.form.submit()">
            <option value="">-- Wybierz --</option>
            <?php foreach ($subjects as $s): ?>
                <option value="<?php echo $s['subjectID']; ?>" <?php echo ($selected_subject_id == $s['subjectID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($s['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <?php if ($selected_class_id && $selected_subject_id): ?>
    <div class="form-group">
        <label for="semester">Widok:</label>
        <select name="semester" id="semester" onchange="this.form.submit()" style="min-width: 120px;">
            <option value="1" <?php echo $selected_semester == 1 ? 'selected' : ''; ?>>Semestr 1</option>
            <option value="2" <?php echo $selected_semester == 2 ? 'selected' : ''; ?>>Semestr 2</option>
        </select>
    </div>
    <?php endif; ?>
</form>

<?php if (!empty($students_data)): ?>
    <div class="table-actions">
        <button class="print-btn" onclick="window.print()">Drukuj Arkusz</button>
    </div>

    <div class="table-wrapper">
        <table style="border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr>
                    <th style="width: 40px;">Lp.</th>
                    <th class="col-name" style="min-width: 200px;">Imię i Nazwisko</th>
                    <th class="col-stat">Średnia<br>ważona</th>
                    <th class="col-stat"><?php echo ($selected_semester == 1) ? 'Ocena<br>Sem. 1' : 'Ocena<br>Roczna'; ?></th>
                    <th class="col-grades" style="padding-left: 15px;">Oceny cząstkowe (Semestr <?php echo $selected_semester; ?>)</th>
                    <th style="width: 60px;">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($students_data as $sid => $student): 
                    $full_name = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
                    $sum = 0; $weight_sum = 0;
                    foreach ($student['grades'] as $g) {
                        $w = (int)$g['weight'] ?: 1;
                        $sum += ($g['grade'] * $w);
                        $weight_sum += $w;
                    }
                    $avg = ($weight_sum > 0) ? round($sum / $weight_sum, 2) : '-';
                ?>
                <tr style="vertical-align: middle;">
                    <td><?php echo ++$i; ?></td>
                    <td class="col-name"><?php echo $full_name; ?></td>
                    <td class="col-stat" style="font-weight: bold; color: #1748CE; font-size: 1.1em;"><?php echo $avg; ?></td>
                    <td class="col-stat">
                        <?php if ($selected_semester == 1): ?>
                            <input type="number" min="1" max="6" class="final-grade-input"
                                   data-student-id="<?php echo $sid; ?>"
                                   data-subject-id="<?php echo $selected_subject_id; ?>"
                                   data-field="grade_term1"
                                   value="<?php echo $student['grade_term1'] ?? ''; ?>" placeholder="-">
                        <?php else: ?>
                            <input type="number" min="1" max="6" class="final-grade-input"
                                   data-student-id="<?php echo $sid; ?>"
                                   data-subject-id="<?php echo $selected_subject_id; ?>"
                                   data-field="grade_final"
                                   value="<?php echo $student['grade_final'] ?? ''; ?>" placeholder="-"
                                   <?php echo ($system_semester == 1) ? 'disabled title="Dostępne w 2. semestrze"' : ''; ?>>
                        <?php endif; ?>
                    </td>
                    <td class="grades-container-cell" style="padding: 8px 15px;">
                        <div class="grades-grid">
                            <?php foreach ($student['grades'] as $g): 
                                $catName = $cats_map[$g['categoryID']] ?? 'Ocena';
                                $dateStr = date('d.m.Y', strtotime($g['date']));
                                $tooltip = "Typ: $catName\nWaga: {$g['weight']}\nData: $dateStr";
                                if (!empty($g['comment'])) $tooltip .= "\nOpis: " . $g['comment'];
                                $tooltip .= "\n\n(Kliknij, aby edytować)";
                            ?>
                                <span class="grade-pill grade-val-<?php echo floor($g['grade']); ?>" 
                                      data-tooltip="<?php echo htmlspecialchars($tooltip); ?>"
                                      data-grade-id="<?php echo $g['gradeID']; ?>"
                                      data-grade-value="<?php echo $g['grade']; ?>"
                                      data-grade-weight="<?php echo $g['weight']; ?>"
                                      data-category-id="<?php echo $g['categoryID']; ?>"
                                      data-comment="<?php echo htmlspecialchars($g['comment']); ?>"
                                      data-student-name="<?php echo $full_name; ?>">
                                    <?php echo (float)$g['grade']; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="action-cell">
                        <span class="add-grade-plus" data-student-id="<?php echo $sid; ?>" data-student-name="<?php echo $full_name; ?>">+</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div id="printable-teacher-grades-report" class="print-only">
        <div class="report-header">
            <h1>Arkusz Ocen - Semestr <?php echo $selected_semester; ?></h1>
            <p><?php echo SCHOOL_NAME; ?></p>
            <p>Data: <?php echo date("d.m.Y"); ?></p>
        </div>
        <div class="report-info-grid">
            <div>
                <strong>Nauczyciel:</strong> <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?><br>
                <strong>Przedmiot:</strong> <?php foreach($subjects as $s) { if($s['subjectID'] == $selected_subject_id) echo htmlspecialchars($s['name']); } ?>
            </div>
            <div style="text-align: right;">
                <strong>Klasa:</strong> <?php foreach($classes as $c) { if($c['classID'] == $selected_class_id) echo htmlspecialchars($c['name']); } ?>
            </div>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Lp.</th>
                    <th style="width: 30%;">Uczeń</th>
                    <th style="width: 10%; text-align:center;">Średnia</th>
                    <th style="width: 10%; text-align:center;"><?php echo ($selected_semester == 1) ? 'Sem. 1' : 'Roczna'; ?></th>
                    <th>Oceny cząstkowe</th>
                </tr>
            </thead>
            <tbody>
                <?php $lp = 1; foreach ($students_data as $sid => $s): ?>
                <tr>
                    <td><?php echo $lp++; ?></td>
                    <td><?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?></td>
                    <td style="text-align:center;">
                        <?php 
                        $sum = 0; $w_sum = 0;
                        foreach ($s['grades'] as $g) {
                            $w = (int)$g['weight'] ?: 1;
                            $sum += ($g['grade'] * $w);
                            $w_sum += $w;
                        }
                        echo ($w_sum > 0) ? round($sum / $w_sum, 2) : '-';
                        ?>
                    </td>
                    <td style="text-align:center; font-weight:bold;">
                        <span id="print_<?php echo ($selected_semester == 1 ? 'grade_term1' : 'grade_final'); ?>_<?php echo $sid; ?>">
                            <?php if ($selected_semester == 1) echo $s['grade_term1'] ?: '-'; else echo $s['grade_final'] ?: '-'; ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        $grades_str = [];
                        foreach ($s['grades'] as $g) {
                            $grades_str[] = '<span class="grade-print">' . (float)$g['grade'] . '<sub>' . $g['weight'] . '</sub></span>';
                        }
                        echo implode(" ", $grades_str);
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="report-footer"><div class="signature-line">Podpis nauczyciela</div></div>
    </div>

<?php elseif ($selected_class_id && $selected_subject_id): ?>
    <p style="text-align:center; margin-top: 20px;">Brak uczniów w tej klasie.</p>
<?php elseif ($selected_class_id): ?>
    <p style="text-align:center; margin-top: 20px;">Wybierz przedmiot, aby zobaczyć oceny.</p>
<?php endif; ?>

<div class="modal-overlay" id="addGradeModal">
    <div class="modal-container">
        <div class="modal-header"><h2>Dodaj ocenę: <span id="modal-title-student-name"></span></h2><button class="close-modal-btn">&times;</button></div>
        <form action="../../actions/add_grade.php" method="POST" class="modal-form">
            <input type="hidden" name="student_id" id="modal_student_id">
            <input type="hidden" name="subject_id" value="<?php echo $selected_subject_id; ?>">
            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
            <input type="hidden" name="semester" value="<?php echo $selected_semester; ?>">
            <input type="hidden" name="redirect_url" value="<?php echo $redirect_params; ?>">
            <div class="form-group"><label>Ocena:</label><input type="number" name="grade" step="0.5" min="1" max="6" required></div>
            <div class="form-group"><label>Kategoria:</label>
                <select name="category_id" class="searchable" required>
                    <option value="">-- Wybierz --</option>
                    <?php foreach ($grade_categories as $cat): ?>
                    <option value="<?php echo $cat['categoryID']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Waga:</label><input type="number" name="weight" min="1" max="10" value="1" required></div>
            <div class="form-group"><label>Komentarz:</label><textarea name="comment"></textarea></div>
            <button type="submit">Zapisz</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editGradeModal">
    <div class="modal-container">
        <div class="modal-header"><h2>Edycja: <span id="edit-modal-student-name"></span></h2><button class="close-modal-btn">&times;</button></div>
        <form action="../../actions/edit_grade.php" method="POST" class="modal-form">
            <input type="hidden" name="grade_id" id="edit_grade_id">
            <input type="hidden" name="redirect_url" value="<?php echo $redirect_params; ?>">
            <div class="form-group"><label>Ocena:</label><input type="number" name="grade" id="edit_grade" step="0.5" min="1" max="6" required></div>
            <div class="form-group"><label>Kategoria:</label>
                <select name="category_id" id="edit_category_id" class="searchable" required>
                    <?php foreach ($grade_categories as $cat): ?>
                    <option value="<?php echo $cat['categoryID']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Waga:</label><input type="number" name="weight" id="edit_weight" min="1" max="10" required></div>
            <div class="form-group"><label>Komentarz:</label><textarea name="comment" id="edit_comment"></textarea></div>
            <div class="modal-footer-buttons">
                <button type="submit" class="btn-update">Zapisz</button>
        </form>
        <form action="../../actions/delete_grade.php" method="POST" onsubmit="return confirm('Usunąć tę ocenę?');">
            <input type="hidden" name="grade_id" id="delete_grade_id">
            <input type="hidden" name="redirect_url" value="<?php echo $redirect_params; ?>">
            <button type="submit" class="btn-delete">Usuń</button>
        </form>
            </div>
    </div>
</div>
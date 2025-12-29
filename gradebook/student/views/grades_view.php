<?php
require_once '../../utils/app_settings.php';
$student_id = $student_id_for_view ?? $_SESSION['userID'];

$current_system_semester = get_current_semester();
$selected_semester = filter_input(INPUT_GET, 'semester', FILTER_VALIDATE_INT) ?? $current_system_semester;

$query_subjects = "SELECT DISTINCT s.subjectID, s.name FROM subjects s
                   JOIN class_subjects_teacher cst ON s.subjectID = cst.subjectID
                   JOIN students st ON st.classID = cst.classID
                   WHERE st.studentID = ?
                   ORDER BY s.name";
$stmt_subjects = mysqli_prepare($con, $query_subjects);
mysqli_stmt_bind_param($stmt_subjects, "i", $student_id);
mysqli_stmt_execute($stmt_subjects);
$subjects = mysqli_fetch_all(mysqli_stmt_get_result($stmt_subjects), MYSQLI_ASSOC);

function get_semester_grades_data($subject_id, $student_id, $semester, $connection)
{
    $html_parts = [];
    $weighted_sum = 0;
    $weight_total = 0;

    $query = "SELECT grade, weight, comment, categoryID, teacherID, date 
              FROM grades 
              WHERE subjectID = ? AND studentID = ? AND semester = ? 
              ORDER BY date";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "iii", $subject_id, $student_id, $semester);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $cats = [];
    $c_res = mysqli_query($connection, "SELECT categoryID, name FROM grade_categories");
    while ($r = mysqli_fetch_assoc($c_res)) $cats[$r['categoryID']] = $r['name'];

    $teachers = [];
    $t_res = mysqli_query($connection, "SELECT teacherID, CONCAT(first_name, ' ',last_name) as 'name' FROM teachers;");
    while ($r = mysqli_fetch_assoc($t_res)) $teachers[$r['teacherID']] = $r['name'];

    while ($row = mysqli_fetch_assoc($result)) {
        $g = $row['grade'];
        $w = (int)$row['weight'];
        if ($w < 1) $w = 1;

        $cat_name = $cats[$row['categoryID']] ?? 'Ocena';
        $teacher_name = $teachers[$row['teacherID']] ?? 'Nieznany nauczyciel';
        $tooltip = "Typ: $cat_name\n
                    Waga: $w\nData: " . date('d.m.Y', strtotime($row['date']))
                    . "\nNauczyciel: $teacher_name";
        if ($row['comment']) $tooltip .= "\nOpis: " . $row['comment'];

        $html_parts[] = '<span class="grade grade-val-' . floor($g) . '" data-tooltip="' . htmlspecialchars($tooltip) . '">' . $g . '</span>';


        $weighted_sum += ($g * $w);
        $weight_total += $w;
    }

    $average = ($weight_total > 0) ? round($weighted_sum / $weight_total, 2) : '-';

    $final_q = "SELECT grade_term1, grade_final FROM final_grades WHERE studentID=? AND subjectID=?";
    $stmt_f = mysqli_prepare($connection, $final_q);
    mysqli_stmt_bind_param($stmt_f, "ii", $student_id, $subject_id);
    mysqli_stmt_execute($stmt_f);
    $final_res = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_f));

    $term_grade = ($semester == 1) ? ($final_res['grade_term1'] ?? '-') : ($final_res['grade_final'] ?? '-');

    return [
        'html' => implode(" ", $html_parts),
        'average' => $average,
        'term_grade' => $term_grade
    ];
}

if (!isset($child_details)) {
    $report_student_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    $report_class = $_SESSION['class_name'];
} else {
    $report_student_name = $child_details['first_name'] . ' ' . $child_details['last_name'];
    $report_class = $child_details['class_name'];
}
?>

<div class="view-header">
    <h1>Oceny</h1>
    
    <form action="" method="GET">
        <?php if(isset($_GET['child_id'])): ?>
            <input type="hidden" name="child_id" value="<?php echo htmlspecialchars($_GET['child_id']); ?>">
        <?php endif; ?>
        <input type="hidden" name="tab" value="grades">
        
        <label for="semester_select" style="font-weight: 500;">Semestr:</label>
        <select name="semester" id="semester_select" onchange="this.form.submit()" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            <option value="1" <?php echo $selected_semester == 1 ? 'selected' : ''; ?>>Semestr 1</option>
            <option value="2" <?php echo $selected_semester == 2 ? 'selected' : ''; ?>>Semestr 2</option>
        </select>
        
        <button type="button" class="print-btn" onclick="window.print()" style="margin-left: 10px;">Drukuj</button>
    </form>
</div>

<div class="table-wrapper">
    <table style="border-collapse: separate; border-spacing: 0;">
        <thead>
            <tr>
                <th class="col-subject">Przedmiot</th>

                <th class="col-stat">Średnia<br>ważona</th>
                <th class="col-stat"><?php echo ($selected_semester == 1) ? 'Ocena<br>Sem. 1' : 'Ocena<br>Roczna'; ?></th>

                <th class="col-grades" style="padding-left: 15px;">Oceny cząstkowe</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($subjects)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Brak przedmiotów.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($subjects as $subject):
                    $data = get_semester_grades_data($subject['subjectID'], $student_id, $selected_semester, $con);
                ?>
                    <tr style="vertical-align: middle;">
                        <td class="col-subject"><?php echo htmlspecialchars($subject['name']); ?></td>

                        <td class="col-stat" style="font-weight: bold; color: #1748CE; font-size: 1.1em;">
                            <?php echo $data['average']; ?>
                        </td>

                        <td class="col-stat" style="font-weight: bold; background-color: #f8f9fa; border-left: 1px solid #eee; border-right: 1px solid #eee; font-size: 1.1em;">
                            <?php echo $data['term_grade']; ?>
                        </td>

                        <td class="grades-cell" style="padding: 11px 15px;">
                            <?php echo $data['html'] ?: '<span class="no-grades-info">Brak ocen</span>'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="printable-grades-report" class="print-only">
    <div class="report-header">
        <h1>Wykaz Ocen - Semestr <?php echo $selected_semester; ?></h1>
        <p><?php echo SCHOOL_NAME; ?></p>
        <p>Data: <?php echo date("d.m.Y"); ?></p>
    </div>

    <div class="report-info-grid">
        <div><strong>Uczeń:</strong> <?php echo htmlspecialchars($report_student_name); ?></div>
        <div><strong>Klasa:</strong> <?php echo htmlspecialchars($report_class); ?></div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 30%;">Przedmiot</th>
                <th>Oceny cząstkowe</th>
                <th style="width: 10%; text-align: center;">Średnia</th>
                <th style="width: 10%; text-align: center;"><?php echo ($selected_semester == 1) ? 'Sem. 1' : 'Roczna'; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject):
                $q = "SELECT grade, weight FROM grades WHERE subjectID=? AND studentID=? AND semester=?";
                $s = mysqli_prepare($con, $q);
                mysqli_stmt_bind_param($s, "iii", $subject['subjectID'], $student_id, $selected_semester);
                mysqli_stmt_execute($s);
                $res = mysqli_stmt_get_result($s);

                $html_p = [];
                $w_sum = 0;
                $sum = 0;
                while ($r = mysqli_fetch_assoc($res)) {
                    $html_p[] = '<span class="grade-print">' . $r['grade'] . '<sub>' . $r['weight'] . '</sub></span>';
                    $sum += ($r['grade'] * $r['weight']);
                    $w_sum += $r['weight'];
                }
                $avg = ($w_sum > 0) ? round($sum / $w_sum, 2) : '-';

                $fq = "SELECT grade_term1, grade_final FROM final_grades WHERE studentID=? AND subjectID=?";
                $fs = mysqli_prepare($con, $fq);
                mysqli_stmt_bind_param($fs, "ii", $student_id, $subject['subjectID']);
                mysqli_stmt_execute($fs);
                $fr = mysqli_fetch_assoc(mysqli_stmt_get_result($fs));
                $fin = ($selected_semester == 1) ? ($fr['grade_term1'] ?? '-') : ($fr['grade_final'] ?? '-');
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($subject['name']); ?></td>
                    <td><?php echo implode(" ", $html_p); ?></td>
                    <td style="text-align: center; font-weight: bold;"><?php echo $avg; ?></td>
                    <td style="text-align: center; font-weight: bold; background-color: #eee;"><?php echo $fin; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="report-legend">
        Legenda: <strong>Ocena<sub>Waga</sub></strong>
    </div>
    <div class="report-footer">
        <div class="signature-line">Podpis rodzica</div>
        <div class="signature-line">Podpis wychowawcy</div>
    </div>
</div>
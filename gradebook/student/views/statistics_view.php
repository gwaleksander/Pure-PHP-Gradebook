<?php
require_once '../../utils/app_settings.php';

$student_id = $student_id_for_view ?? $_SESSION['userID'];
$class_id = $class_id_for_view ?? $_SESSION['classID'];

$current_system_semester = get_current_semester();
$selected_semester = filter_input(INPUT_GET, 'semester', FILTER_VALIDATE_INT) ?? $current_system_semester;

if (!isset($student_id) || !isset($class_id)) {
    echo "<h1>Błąd</h1><p>Nie można załadować statystyk. Brak danych użytkownika lub klasy w sesji.</p>";
    return;
}

function get_statistics_data_for_view($student_id, $class_id, $semester, $connection)
{
    $data = [];
    $total_student_weighted_sum = 0;
    $total_student_weight_sum = 0;
    $all_student_grades_counts = [];

    $total_class_weighted_sum = 0;
    $total_class_weight_sum = 0;
    $all_class_grades_counts = [];

    $subjects_query = "SELECT p.subjectID, p.name FROM subjects p 
                       JOIN class_subjects_teacher cst ON p.subjectID = cst.subjectID 
                       WHERE cst.classID = ?";
    $stmt = mysqli_prepare($connection, $subjects_query);
    if (!$stmt) return ['per_subject' => [], 'overall' => []];
    mysqli_stmt_bind_param($stmt, "i", $class_id);
    mysqli_stmt_execute($stmt);
    $subjects_result = mysqli_stmt_get_result($stmt);
    
    while ($subject = mysqli_fetch_assoc($subjects_result)) {
        $data[$subject['subjectID']] = [
            'name' => $subject['name'], 
            'student_weighted_sum' => 0,
            'student_weight_sum' => 0,
            'student_grades_list' => [], 
            'class_weighted_sum' => 0,
            'class_weight_sum' => 0,
            'class_grades_list' => []
        ];
    }
    mysqli_stmt_close($stmt);

    $student_grades_query = "SELECT subjectID, grade, weight FROM grades WHERE studentID = ? AND semester = ?";
    $stmt = mysqli_prepare($connection, $student_grades_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $student_id, $semester);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($grade = mysqli_fetch_assoc($res)) {
            $val = (float)$grade['grade'];
            $w = (int)$grade['weight'];
            if ($w < 1) $w = 1;

            if (isset($data[$grade['subjectID']])) {
                $data[$grade['subjectID']]['student_weighted_sum'] += ($val * $w);
                $data[$grade['subjectID']]['student_weight_sum'] += $w;
                $data[$grade['subjectID']]['student_grades_list'][] = $val;
            }
            $total_student_weighted_sum += ($val * $w);
            $total_student_weight_sum += $w;
            $all_student_grades_counts[] = $val;
        }
        mysqli_stmt_close($stmt);
    }

    $class_grades_query = "SELECT g.subjectID, g.grade, g.weight FROM grades g 
                           JOIN students s ON g.studentID = s.studentID 
                           WHERE s.classID = ? AND g.semester = ?";
    $stmt = mysqli_prepare($connection, $class_grades_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $class_id, $semester);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($grade = mysqli_fetch_assoc($res)) {
            $val = (float)$grade['grade'];
            $w = (int)$grade['weight'];
            if ($w < 1) $w = 1;

            if (isset($data[$grade['subjectID']])) {
                $data[$grade['subjectID']]['class_weighted_sum'] += ($val * $w);
                $data[$grade['subjectID']]['class_weight_sum'] += $w;
                $data[$grade['subjectID']]['class_grades_list'][] = $val;
            }
            $total_class_weighted_sum += ($val * $w);
            $total_class_weight_sum += $w;
            $all_class_grades_counts[] = $val;
        }
        mysqli_stmt_close($stmt);
    }

    foreach ($data as &$subject_data) {
        $subject_data['student_avg'] = ($subject_data['student_weight_sum'] > 0) 
            ? round($subject_data['student_weighted_sum'] / $subject_data['student_weight_sum'], 2) : 0;
        
        $student_counts = array_fill(1, 6, 0);
        foreach ($subject_data['student_grades_list'] as $g) { if (isset($student_counts[floor($g)])) $student_counts[floor($g)]++; }
        $subject_data['student_grades_count'] = $student_counts;

        $subject_data['class_avg'] = ($subject_data['class_weight_sum'] > 0) 
            ? round($subject_data['class_weighted_sum'] / $subject_data['class_weight_sum'], 2) : 0;
            
        $class_counts = array_fill(1, 6, 0);
        foreach ($subject_data['class_grades_list'] as $g) { if (isset($class_counts[floor($g)])) $class_counts[floor($g)]++; }
        $subject_data['class_grades_count'] = $class_counts;
        
        unset($subject_data['student_grades_list']);
        unset($subject_data['class_grades_list']);
    }

    $overall_student_avg = ($total_student_weight_sum > 0) ? round($total_student_weighted_sum / $total_student_weight_sum, 2) : 0;
    $overall_class_avg = ($total_class_weight_sum > 0) ? round($total_class_weighted_sum / $total_class_weight_sum, 2) : 0;

    $overall_student_counts = array_fill(1, 6, 0);
    foreach ($all_student_grades_counts as $g) { if (isset($overall_student_counts[floor($g)])) $overall_student_counts[floor($g)]++; }
    
    $overall_class_counts = array_fill(1, 6, 0);
    foreach ($all_class_grades_counts as $g) { if (isset($overall_class_counts[floor($g)])) $overall_class_counts[floor($g)]++; }

    return [
        'per_subject' => $data,
        'overall' => [
            'student_avg' => $overall_student_avg,
            'class_avg' => $overall_class_avg,
            'student_grades_count' => $overall_student_counts,
            'class_grades_count' => $overall_class_counts,
        ]
    ];
}

$processed_data = get_statistics_data_for_view($student_id, $class_id, $selected_semester, $con);
$statistics_data = $processed_data['per_subject'];
$overall_stats = $processed_data['overall'];
?>

<div class="view-header">
    <h1>Statystyki Ocen</h1>
    
    <form action="" method="GET">
        <?php if(isset($_GET['child_id'])): ?>
            <input type="hidden" name="child_id" value="<?php echo htmlspecialchars($_GET['child_id']); ?>">
        <?php endif; ?>
        <input type="hidden" name="tab" value="statistics">
        
        <label for="stat_semester_select">Semestr:</label>
        <select name="semester" id="stat_semester_select" onchange="this.form.submit()"  style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            <option value="1" <?php echo $selected_semester == 1 ? 'selected' : ''; ?>>Semestr 1</option>
            <option value="2" <?php echo $selected_semester == 2 ? 'selected' : ''; ?>>Semestr 2</option>
        </select>
    </form>
</div>

<div class="stats-toolbar">
    <div class="search-bar-container">
        <input type="text" id="subject-search-input" class="stats-search-input" placeholder="Wyszukaj przedmiot...">
    </div>
</div>

<div id="stats-content-wrapper">
    <div class="overall-summary-box">
        <h2>Ogólne wyniki w nauce</h2>
        <div class="charts-wrapper">
            <div class="chart-container">
                <h3>Rozkład Twoich ocen</h3>
                <div class="chart-area-wrapper">
                    <?php if (array_sum($overall_stats['student_grades_count']) > 0): ?>
                        <div class="chart-canvas-wrapper"><canvas id="chart-overall-student"></canvas></div>
                    <?php else: ?>
                        <div class="no-data-placeholder">Brak ocen.</div>
                    <?php endif; ?>
                </div>
                <p class="average">Średnia ważona: <span class="summary-value"><?php echo $overall_stats['student_avg']; ?></span></p>
            </div>
            <div class="chart-container">
                <h3>Rozkład ocen w klasie</h3>
                <div class="chart-area-wrapper">
                    <?php if (array_sum($overall_stats['class_grades_count']) > 0): ?>
                        <div class="chart-canvas-wrapper"><canvas id="chart-overall-class"></canvas></div>
                    <?php else: ?>
                        <div class="no-data-placeholder">Brak ocen.</div>
                    <?php endif; ?>
                </div>
                <p class="average">Średnia klasy: <span class="summary-value"><?php echo $overall_stats['class_avg']; ?></span></p>
            </div>
        </div>
    </div>

    <?php if (empty($statistics_data)): ?>
        <p style="text-align: center;">Brak danych.</p>
    <?php else: ?>
        <div id="subjects-stats-list">
            <?php foreach ($statistics_data as $subject_id => $data): ?>
                <div class="subject-stats-container" data-subject-name="<?php echo htmlspecialchars(strtolower($data['name'])); ?>">
                    <h2 class="subject-title"><?php echo htmlspecialchars($data['name']); ?></h2>
                    <div class="charts-wrapper">
                        <div class="chart-container">
                            <h3>Oceny ucznia</h3>
                            <div class="chart-area-wrapper">
                                <?php if (array_sum($data['student_grades_count']) > 0): ?>
                                    <div class="chart-canvas-wrapper"><canvas id="chart-student-<?php echo $subject_id; ?>"></canvas></div>
                                <?php else: ?>
                                    <div class="no-data-placeholder">Brak ocen.</div>
                                <?php endif; ?>
                            </div>
                            <p class="average">Średnia: <span class="average-value"><?php echo $data['student_avg'] > 0 ? $data['student_avg'] : '-'; ?></span></p>
                        </div>
                        <div class="chart-container">
                            <h3>Oceny klasy</h3>
                            <div class="chart-area-wrapper">
                                <?php if (array_sum($data['class_grades_count']) > 0): ?>
                                    <div class="chart-canvas-wrapper"><canvas id="chart-class-<?php echo $subject_id; ?>"></canvas></div>
                                <?php else: ?>
                                    <div class="no-data-placeholder">Brak ocen.</div>
                                <?php endif; ?>
                            </div>
                            <p class="average">Średnia klasy: <span class="average-value"><?php echo $data['class_avg'] > 0 ? $data['class_avg'] : '-'; ?></span></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const overallStats = <?php echo json_encode($overall_stats); ?>;
    const statisticsData = <?php echo json_encode($statistics_data); ?>;

    if (typeof Chart !== 'undefined') {
        const barColors = ["#ff6464ff", "#ffa13cff", "#fdd81fff", "#b4fd35ff", "#3dfc83ff", "#3a96ffff"];
        const xLabels = ["Ocena 1", "Ocena 2", "Ocena 3", "Ocena 4", "Ocena 5", "Ocena 6"];
        
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            rotation: -Math.PI,
            circumference: Math.PI,
            cutoutPercentage: 40,
            legend: { position: 'bottom', labels: { padding: 10, boxWidth: 10, fontSize: 10 } }
        };

        function drawChart(elementId, dataValues) {
            const ctx = document.getElementById(elementId);

            const valuesArray = Object.values(dataValues);
            
            if (ctx && valuesArray.some(v => v > 0)) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: xLabels,
                        datasets: [{
                            backgroundColor: barColors,
                            data: valuesArray
                        }]
                    },
                    options: chartOptions
                });
            }
        }

        drawChart('chart-overall-student', overallStats.student_grades_count);
        drawChart('chart-overall-class', overallStats.class_grades_count);

        for (const [subjectId, data] of Object.entries(statisticsData)) {
            drawChart(`chart-student-${subjectId}`, data.student_grades_count);
            drawChart(`chart-class-${subjectId}`, data.class_grades_count);
        }
    }

    const searchInput = document.getElementById('subject-search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const subjectsList = document.querySelectorAll('#subjects-stats-list .subject-stats-container');
            subjectsList.forEach(subject => {
                const subjectName = subject.dataset.subjectName || '';
                if (subjectName.includes(filter)) subject.style.display = '';
                else subject.style.display = 'none';
            });
        });
    }
});
</script>
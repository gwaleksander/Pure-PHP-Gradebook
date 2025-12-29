<?php
$query_subjects = "SELECT subjectID, name FROM subjects ORDER BY name";
$result_subjects = mysqli_query($con, $query_subjects);
$subjects = mysqli_fetch_all($result_subjects, MYSQLI_ASSOC);
?>

<h1>Zarządzanie Przedmiotami</h1>
<div class="admin-toolbar">
    <div class="admin-search-container">
        <input type="text" class="admin-search-input table-search" data-table="subjects-table" placeholder="Szukaj przedmiotu...">
    </div>
    <div class="table-actions">
        <button class="action-btn add-btn" id="openAddSubjectModalBtn">Dodaj nowy przedmiot</button>
    </div>


</div>
<div class="table-wrapper">
    <table id="subjects-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa Przedmiotu</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
                <tr data-subject-id="<?php echo $subject['subjectID']; ?>"
                    data-subject-name="<?php echo htmlspecialchars($subject['name']); ?>">
                    <td><?php echo $subject['subjectID']; ?></td>
                    <td><?php echo htmlspecialchars($subject['name']); ?></td>
                    <td class="action-cell">
                        <button class="action-btn edit-btn auto-edit-btn"
                            data-target="#editSubjectModal"
                            data-fill-subjectID="<?php echo $subject['subjectID']; ?>"
                            data-fill-name="<?php echo htmlspecialchars($subject['name']); ?>">
                            Edytuj
                        </button>
                        <button class="action-btn delete-btn auto-delete-btn"
                            data-action="../../actions/delete_subject.php"
                            data-confirm="Usunąć przedmiot <?php echo htmlspecialchars($subject['name']); ?>?"
                            data-key="subjectID"
                            data-value="<?php echo $subject['subjectID']; ?>">
                            Usuń
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="addSubjectModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Dodaj nowy przedmiot</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/add_subject.php" method="POST" class="modal-form">
            <div class="form-group"><label>Nazwa przedmiotu:</label><input type="text" name="name" required></div>
            <button type="submit">Utwórz przedmiot</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editSubjectModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Edytuj przedmiot</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/edit_subject.php" method="POST" class="modal-form">
            <input type="hidden" name="subjectID" id="edit_subjectID">
            <div class="form-group"><label>Nazwa przedmiotu:</label><input type="text" name="name" id="edit_subjectName" required></div>
            <button type="submit" class="btn-update">Zapisz zmiany</button>
        </form>
    </div>
</div>
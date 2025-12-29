<?php
$query_classes = "SELECT c.classID, c.name, t.teacherID, t.first_name, t.last_name
                  FROM classes c
                  LEFT JOIN teachers t ON c.teacherID = t.teacherID
                  ORDER BY c.name";
$result_classes = mysqli_query($con, $query_classes);
$classes = mysqli_fetch_all($result_classes, MYSQLI_ASSOC);

$query_teachers = "SELECT teacherID, first_name, last_name FROM teachers ORDER BY last_name, first_name";
$result_teachers = mysqli_query($con, $query_teachers);
$teachers = mysqli_fetch_all($result_teachers, MYSQLI_ASSOC);
?>

<h1>Zarządzanie Klasami</h1>
<div class="admin-toolbar">
    <div class="admin-search-container">
        <input type="text" class="admin-search-input table-search" data-table="classes-table" placeholder="Szukaj klasy lub wychowawcy...">
    </div>
    <div class="table-actions">
        <button class="action-btn add-btn" id="openAddClassModalBtn">Dodaj nową klasę</button>
    </div>


</div>
<div class="table-wrapper">
    <table id="classes-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa Klasy</th>
                <th>Wychowawca</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
                <tr data-class-id="<?php echo $class['classID']; ?>"
                    data-class-name="<?php echo htmlspecialchars($class['name']); ?>"
                    data-teacher-id="<?php echo $class['teacherID']; ?>">
                    <td><?php echo $class['classID']; ?></td>
                    <td><?php echo htmlspecialchars($class['name']); ?></td>
                    <td><?php echo $class['teacherID'] ? htmlspecialchars($class['first_name'] . ' ' . $class['last_name']) : '<span style="color:#999;">Brak</span>'; ?></td>
                    <td class="action-cell">
                        <!-- <button class="action-btn edit-btn edit-class-btn">Edytuj</button> -->
                        <button class="action-btn edit-btn auto-edit-btn"
                            data-target="#editClassModal"
                            data-fill-classID="<?php echo $class['classID']; ?>"
                            data-fill-name="<?php echo htmlspecialchars($class['name']); ?>"
                            data-fill-teacherID="<?php echo $class['teacherID']; ?>">
                            Edytuj
                        </button>
                        <button class="action-btn delete-btn auto-delete-btn"
                            data-action="../../actions/delete_class.php"
                            data-confirm="Czy na pewno chcesz usunąć klasę <?php echo htmlspecialchars($class['name']); ?>? Usunie to również wszystkich uczniów tej klasy!"
                            data-key="classID"
                            data-value="<?php echo $class['classID']; ?>">
                            Usuń
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="addClassModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Dodaj nową klasę</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/add_class.php" method="POST" class="modal-form">
            <div class="form-group"><label>Nazwa klasy:</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Wychowawca:</label>
                <select name="teacherID" class="searchable" required>
                    <option value="">-- Wybierz nauczyciela --</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo $teacher['teacherID']; ?>"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Utwórz klasę</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editClassModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Edytuj klasę</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/edit_class.php" method="POST" class="modal-form">
            <input type="hidden" name="classID" id="edit_classID">
            <div class="form-group"><label>Nazwa klasy:</label><input type="text" name="name" id="edit_className" required></div>
            <div class="form-group"><label>Wychowawca:</label>
                <select name="teacherID" id="edit_classTeacherID" class="searchable" required>
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
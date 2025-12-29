<?php
$query_users = "
    SELECT 
        u.userID, u.username, u.email, u.role, u.createdAt,
        COALESCE(s.first_name, t.first_name, p.first_name) as first_name,
        COALESCE(s.last_name, t.last_name, p.last_name) as last_name
    FROM users u
    LEFT JOIN students s ON u.userID = s.studentID AND u.role = 'student'
    LEFT JOIN teachers t ON u.userID = t.teacherID AND u.role = 'teacher'
    LEFT JOIN parents p ON u.userID = p.parentID AND u.role = 'parent'
    ORDER BY u.role, u.userID";
$result_users = mysqli_query($con, $query_users);
$users = mysqli_fetch_all($result_users, MYSQLI_ASSOC);

$query_classes = "SELECT classID, name FROM classes ORDER BY name";
$result_classes = mysqli_query($con, $query_classes);
$classes = mysqli_fetch_all($result_classes, MYSQLI_ASSOC);

function translate_role($role)
{
    $translations = ['student' => 'Uczeń', 'teacher' => 'Nauczyciel', 'parent' => 'Rodzic', 'admin' => 'Administrator'];
    return $translations[$role] ?? ucfirst($role);
}
?>

<h1>Zarządzanie Użytkownikami</h1>

<div class="admin-toolbar">
    <div class="admin-search-container">
        <input type="text" class="admin-search-input table-search" data-table="users-table" placeholder="Szukaj użytkownika (Imię, Login, Email, Rola)...">
    </div>
    <div class="table-actions">
        <button class="action-btn add-btn" id="openAddUserModalBtn">Dodaj nowego użytkownika</button>
    </div>
    
</div>

<div class="table-wrapper">
    <table id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię i Nazwisko</th>
                <th>Login</th>
                <th>Email</th>
                <th>Rola</th>
                <th>Data utworzenia</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['userID']; ?></td>
                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo translate_role($user['role']); ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($user['createdAt'])); ?></td>
                    <td class="action-cell">
                        <button class="action-btn edit-btn edit-user-btn"
                            data-user-id="<?php echo $user['userID']; ?>"
                            data-username="<?php echo htmlspecialchars($user['username']); ?>">
                            Edytuj
                        </button>
                        <button class="action-btn delete-btn auto-delete-btn"
                            data-action="../../actions/delete_user.php"
                            data-confirm="Czy usunąć użytkownika <?php echo htmlspecialchars($user['username']); ?>?"
                            data-key="userID"
                            data-value="<?php echo $user['userID']; ?>">
                            Usuń
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="addUserModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Dodaj nowego użytkownika</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/add_user.php" method="POST" class="modal-form">
            <div class="form-group"><label>Nazwa użytkownika:</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Email:</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Telefon:</label><input type="text" name="phone"></div>
            <div class="form-group"><label>Hasło:</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Rola:</label>
                <select name="role" id="addUserRoleSelect" required>
                    <option value="">-- Wybierz rolę --</option>
                    <option value="student">Uczeń</option>
                    <option value="teacher">Nauczyciel</option>
                    <option value="parent">Rodzic</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="role-specific-fields" data-role="student">
                <h3>Dane Ucznia</h3>
                <div class="form-group"><label>Imię:</label><input type="text" name="student_first_name"></div>
                <div class="form-group"><label>Nazwisko:</label><input type="text" name="student_last_name"></div>
                <div class="form-group"><label>PESEL:</label><input type="text" name="student_pesel" pattern="\d{11}" title="PESEL musi składać się z 11 cyfr."></div>
                <div class="form-group"><label>Adres:</label><input type="text" name="student_address"></div>
                <div class="form-group"><label>Data urodzenia:</label><input type="date" name="student_birth_date"></div>
                <div class="form-group"><label>Klasa:</label>
                    <select name="student_classID">
                        <option value="">-- Wybierz klasę --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['classID']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="role-specific-fields" data-role="teacher">
                <h3>Dane Nauczyciela</h3>
                <div class="form-group"><label>Imię:</label><input type="text" name="teacher_first_name"></div>
                <div class="form-group"><label>Nazwisko:</label><input type="text" name="teacher_last_name"></div>
                <div class="form-group"><label>PESEL:</label><input type="text" name="teacher_pesel" pattern="\d{11}"></div>
                <div class="form-group"><label>Adres:</label><input type="text" name="teacher_address"></div>
                <div class="form-group"><label>Data urodzenia:</label><input type="date" name="teacher_birth_date"></div>
                <div class="form-group"><label>Data zatrudnienia:</label><input type="date" name="teacher_hire_date"></div>
            </div>

            <div class="role-specific-fields" data-role="parent">
                <h3>Dane Rodzica</h3>
                <div class="form-group"><label>Imię:</label><input type="text" name="parent_first_name"></div>
                <div class="form-group"><label>Nazwisko:</label><input type="text" name="parent_last_name"></div>
            </div>

            <button type="submit">Utwórz użytkownika</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editUserModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Edytuj użytkownika: <span id="edit-modal-username-title"></span></h2>
            <button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/edit_user.php" method="POST" class="modal-form">
            <input type="hidden" name="userID" id="edit_userID">
            <div class="form-group"><label>Nazwa użytkownika:</label><input type="text" name="username" id="edit_username" required></div>
            <div class="form-group"><label>Email:</label><input type="email" name="email" id="edit_email" required></div>
            <div class="form-group"><label>Telefon:</label><input type="tel" name="phone" id="edit_phone"></div>
            <div class="form-group"><label>Nowe hasło (pozostaw puste):</label><input type="password" name="password"></div>

            <div class="role-specific-fields" data-role="student">
                <h3>Dane Ucznia</h3>
                <div class="form-group"><label>Imię:</label><input type="text" name="student_first_name" id="edit_student_first_name"></div>
                <div class="form-group"><label>Nazwisko:</label><input type="text" name="student_last_name" id="edit_student_last_name"></div>
                <div class="form-group"><label>PESEL:</label><input type="text" name="student_pesel" id="edit_student_pesel" pattern="\d{11}"></div>
                <div class="form-group"><label>Data urodzenia:</label><input type="date" name="student_birth_date" id="edit_student_birth_date"></div>
                <div class="form-group"><label>Adres:</label><input type="text" name="student_address" id="edit_student_address"></div>
                <div class="form-group"><label>Klasa:</label>
                    <select name="student_classID" id="edit_student_classID">
                        <option value="">-- Wybierz klasę --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['classID']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="role-specific-fields" data-role="teacher">
                <h3>Dane Nauczyciela</h3>
                <div class="form-group"><label>Imię:</label><input type="text" name="teacher_first_name" id="edit_teacher_first_name"></div>
                <div class="form-group"><label>Nazwisko:</label><input type="text" name="teacher_last_name" id="edit_teacher_last_name"></div>
                <div class="form-group"><label>PESEL:</label><input type="text" name="teacher_pesel" id="edit_teacher_pesel" pattern="\d{11}"></div>
                <div class="form-group"><label>Data urodzenia:</label><input type="date" name="teacher_birth_date" id="edit_teacher_birth_date"></div>
                <div class="form-group"><label>Adres:</label><input type="text" name="teacher_address" id="edit_teacher_address"></div>
                <div class="form-group"><label>Data zatrudnienia:</label><input type="date" name="teacher_hire_date" id="edit_teacher_hire_date"></div>
            </div>

            <div class="role-specific-fields" data-role="parent">
                <h3>Dane Rodzica</h3>
                <div class="form-group"><label>Imię:</label><input type="text" name="parent_first_name" id="edit_parent_first_name"></div>
                <div class="form-group"><label>Nazwisko:</label><input type="text" name="parent_last_name" id="edit_parent_last_name"></div>
                <div class="form-group"><label>Adres:</label><input type="text" name="parent_address" id="edit_parent_address"></div>

                <div id="assigned-children-section">
                    <h3>Przypisane Dzieci</h3>
                    <div id="assigned-children-list">

                    </div>
                    <button type="button" class="action-btn add-btn" id="openAssignChildModalBtn" style="margin-top: 10px;">Przypisz Dziecko</button>
                </div>
            </div>

            <div class="modal-footer-buttons"><button type="submit" class="btn-update">Zapisz zmiany</button></div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="assignChildModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Wybierz dziecko do przypisania</h2><button class="close-modal-btn">&times;</button>
        </div>
        <div class="form-group">
            <input type="text" id="student-search-input" placeholder="Wyszukaj ucznia..." style="width: 100%; padding: 10px; font-size: 16px;">
        </div>
        <div id="all-students-list" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
        </div>
    </div>
</div>
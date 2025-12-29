<h1>Twoje dane</h1>
<div class="info-grid">
    <div class="info-item">
        <span class="info-label">Imię:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Nazwisko:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['last_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Data urodzenia:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['birth_date']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">PESEL:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['PESEL']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Nazwa użytkownika:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Email:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Numer telefonu:</span>
        <span class="info-value">
            <?php
            if ($_SESSION['phone'] != NULL)
                echo htmlspecialchars($_SESSION['phone']);
            else
                echo htmlspecialchars("Nie podano");
            ?>
        </span>
    </div>
    <div class="info-item">
        <span class="info-label">Adres:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['address']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Klasa:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['class_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Wychowawca:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['class_teacher_first_name'] . " " . $_SESSION['class_teacher_last_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Aktualny semestr:</span>
        <span class="info-value"><?php echo get_current_semester() === 1 ? 'Zimowy' : 'Letni'; ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Ulubiona ocena:</span>
        <span class="info-value">
            <?php
            if (isset($_SESSION['favorite_grade']) && in_array($_SESSION['favorite_grade'], ['1', '2', '3', '4', '5', '6'])) {
                echo htmlspecialchars($_SESSION['favorite_grade']);
            } else {
                echo "Nie podano";
            }
            ?>
        </span>
    </div>
</div>

<div class="profile-actions">
    <button class="btn-username" onclick="document.getElementById('changeUsernameModal').classList.add('active')">
        Zmień login
    </button>
    <button class="btn-password" onclick="document.getElementById('changePasswordModal').classList.add('active')">
        Zmień hasło
    </button>
</div>
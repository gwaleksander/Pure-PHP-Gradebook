<?php require_role('teacher'); ?>

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
            <?php echo !empty($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : 'Nie podano'; ?>
        </span>
    </div>
    <div class="info-item">
        <span class="info-label">Data zatrudnienia:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['hire_date']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Adres:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['address']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Aktualny semestr:</span>
        <span class="info-value"><?php echo get_current_semester() === 1 ? 'Zimowy' : 'Letni'; ?></span>
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

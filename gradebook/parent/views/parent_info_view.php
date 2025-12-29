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
        <span class="info-label">Email:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Telefon:</span>
        <span class="info-value"><?php echo htmlspecialchars($_SESSION['phone']); ?></span>
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
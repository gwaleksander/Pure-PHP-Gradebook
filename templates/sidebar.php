<div class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-header">
            <h2><?php echo SCHOOL_NAME ?></h2>
        </div>
        
        <div class="menu-items">
            <?php foreach ($menuItems as $item): ?>
                <?php if ($item['id'] === 'separator'): ?>
                    <div class="menu-separator"></div>
                <?php else: ?>
                    <?php $isActive = (isset($item['id']) && $item['id'] == ($activeTab ?? '')) ? 'active' : ''; ?>
                    <div class="menu-item <?php echo $isActive; ?>" onclick="<?php echo htmlspecialchars($item['onclick']); ?>">
                        <?php echo htmlspecialchars($item['label']); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <div class="menu-separator"></div>
            <div class="menu-item" onclick="window.location.href='<?php echo BASE_URL; ?>/login/logout.php'">Wyloguj</div>
        </div>
    </div>

    <div class="sidebar-footer">
        <button id="openBugReportModalBtn" class="bug-report-btn">Zgłoś błąd</button>
    </div>
</div>

<div class="modal-overlay" id="bugReportModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Zgłoś problem</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="<?php echo BASE_URL; ?>/actions/submit_bug.php" method="POST" class="modal-form">
            <div class="form-group">
                <label>Opisz dokładnie problem:</label>
                <textarea name="bug_content" rows="5" required placeholder="Np. Nie mogę dodać oceny w klasie 1A..."></textarea>
            </div>
            <button type="submit">Wyślij zgłoszenie</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="changePasswordModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Zmiana hasła</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="<?php echo BASE_URL; ?>/actions/change_password.php" method="POST" class="modal-form">
            <div class="form-group">
                <label>Stare hasło:</label>
                <input type="password" name="old_password" required>
            </div>
            <div class="form-group">
                <label>Nowe hasło:</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Powtórz nowe hasło:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-update">Zmień hasło</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="changeUsernameModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Zmień nazwę użytkownika</h2><button class="close-modal-btn">&times;</button>
        </div>
        <form action="../../actions/change_username.php" method="POST" class="modal-form">
            <div class="form-group">
                <label>Nowy login:</label>
                <input type="text" name="new_username" required value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn-update">Zapisz zmianę</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bugBtn = document.getElementById('openBugReportModalBtn');
        const bugModal = document.getElementById('bugReportModal');
        if(bugBtn && bugModal) bugBtn.addEventListener('click', () => bugModal.classList.add('active'));
    });
</script>
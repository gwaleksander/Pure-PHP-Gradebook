<h1>Dane ucznia</h1>
<div class="info-grid">
    <div class="info-item">
        <span class="info-label">Imię:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['first_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Nazwisko:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['last_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Data urodzenia:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['birth_date']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">PESEL:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['PESEL']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Nazwa użytkownika:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['username']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Email:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['email']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Numer telefonu:</span>
        <span class="info-value">
            <?php echo !empty($student_info['phone']) ? htmlspecialchars($student_info['phone']) : 'Nie podano'; ?>
        </span>
    </div>
    <div class="info-item">
        <span class="info-label">Adres:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['address']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Klasa:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['class_name']); ?></span>
    </div>
    <div class="info-item">
        <span class="info-label">Wychowawca:</span>
        <span class="info-value"><?php echo htmlspecialchars($student_info['class_teacher_first_name'] . " " . $student_info['class_teacher_last_name']); ?></span>
    </div>
    <div class="info-item full-width">
        <span class="info-label">Aktualny semestr:</span>
        <span class="info-value"><?php echo get_current_semester() === 1 ? 'Zimowy' : 'Letni'; ?></span>
    </div>
</div>
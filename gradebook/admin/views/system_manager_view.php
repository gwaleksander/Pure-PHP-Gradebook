<h1>Ustawienia Systemu</h1>

<div class="semester-settings-box">
    <div>
        <h2 style="margin: 0; font-size: 18px; color: #333;">Zarządzanie Semestrem</h2>
        <p style="margin: 5px 0 0 0; color: #666;">Aktualnie trwa: <strong>Semestr <?php echo $current_semester; ?></strong></p>
    </div>
    <form action="../../actions/update_semester.php" method="POST" style="display: flex; gap: 10px; align-items: center;">
        <label for="semester_select">Zmień na:</label>
        <select name="semester" id="semester_select" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
            <option value="1" <?php echo $current_semester == 1 ? 'selected' : ''; ?>>Semestr 1</option>
            <option value="2" <?php echo $current_semester == 2 ? 'selected' : ''; ?>>Semestr 2</option>
        </select>
        <button type="submit" class="action-btn edit-btn" style="font-size: 14px;">Zapisz</button>
    </form>
</div>
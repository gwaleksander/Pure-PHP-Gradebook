<?php
$q = "SELECT b.*, u.username, u.role, 
      COALESCE(s.first_name, t.first_name, p.first_name) as fname,
      COALESCE(s.last_name, t.last_name, p.last_name) as lname
      FROM bug_reports b
      JOIN users u ON b.userID = u.userID
      LEFT JOIN students s ON u.userID = s.studentID
      LEFT JOIN teachers t ON u.userID = t.teacherID
      LEFT JOIN parents p ON u.userID = p.parentID
      ORDER BY 
        FIELD(b.status, 'new', 'read', 'resolved'),
        b.created_at DESC";
$reports = mysqli_fetch_all(mysqli_query($con, $q), MYSQLI_ASSOC);
?>

<h1>Zgłoszenia Błędów</h1>

<div class="bug-list">
    <?php if(empty($reports)): ?>
        <div style="text-align:center; padding: 40px; color: #777;">
            <h3>Brak zgłoszeń</h3>
        </div>
    <?php else: ?>
        <?php foreach ($reports as $r): ?>
            
            <div class="bug-card status-<?php echo $r['status']; ?>" id="report-card-<?php echo $r['reportID']; ?>">
                
                <div class="bug-header">
                    <div>
                        <span class="bug-user-info"><?php echo htmlspecialchars($r['fname'] . ' ' . $r['lname']); ?></span>
                        <span class="bug-role"><?php echo ucfirst($r['role']); ?></span>
                        <span style="font-size:12px; color:#999; margin-left:5px;">(<?php echo htmlspecialchars($r['username']); ?>)</span>
                    </div>
                    <div class="bug-date">
                        <?php echo date('d.m.Y H:i', strtotime($r['created_at'])); ?>
                    </div>
                </div>

                <div class="bug-content"><?php echo htmlspecialchars($r['content']); ?></div>

                <div class="bug-footer">
                    <div>
                        <label style="font-size:12px; color:#777; margin-right:5px;">Status:</label>
                        <select class="bug-status-select val-<?php echo $r['status']; ?>" 
                                data-id="<?php echo $r['reportID']; ?>"
                                onchange="updateBugStatus(this)">
                            <option value="new" <?php echo $r['status']=='new'?'selected':''; ?>>🔴 Nowe</option>
                            <option value="read" <?php echo $r['status']=='read'?'selected':''; ?>>🟡 W trakcie</option>
                            <option value="resolved" <?php echo $r['status']=='resolved'?'selected':''; ?>>🟢 Rozwiązane</option>
                        </select>
                    </div>

                    <button class="action-btn delete-btn auto-delete-btn"
                            data-action="../../actions/delete_bug_report.php"
                            data-confirm="Czy na pewno usunąć to zgłoszenie?"
                            data-key="reportID"
                            data-value="<?php echo $r['reportID']; ?>"
                            style="padding: 8px 15px; font-size: 13px;">
                        Usuń zgłoszenie
                    </button>
                </div>

            </div>

        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function updateBugStatus(select) {
    const reportID = select.dataset.id;
    const newStatus = select.value;
    const card = document.getElementById('report-card-' + reportID);

    select.disabled = true;
    select.style.opacity = '0.5';

    fetch('../../actions/update_bug_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ reportID: reportID, status: newStatus })
    })
    .then(r => r.json())
    .then(data => {
        select.disabled = false;
        select.style.opacity = '1';

        if (data.success) {
            card.className = `bug-card status-${newStatus}`;
            
            select.className = `bug-status-select val-${newStatus}`;
        } else {
            alert('Błąd: ' + data.message);
        }
    })
    .catch(err => {
        select.disabled = false;
        alert('Błąd połączenia');
    });
}
</script>
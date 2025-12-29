<h1>Wybierz dziecko</h1>

<div class="child-select-container">
    <?php if (empty($_SESSION['children'])): ?>
        <p class="no-data-info">Do Twojego konta nie przypisano żadnych dzieci. Skontaktuj się z sekretariatem szkoły.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['children'] as $child): ?>
            <a href="dashboard.php?child_id=<?php echo $child['studentID']; ?>" class="child-select-card">
                <span class="child-name"><?php echo htmlspecialchars($child['first_name'] . ' ' . $child['last_name']); ?></span>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
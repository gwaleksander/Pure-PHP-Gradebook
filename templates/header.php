<?php require_once __DIR__ . '/../utils/strings.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . SCHOOL_NAME : SCHOOL_NAME; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/favicon.svg">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" defer></script>
</head>
<body>
    <div class="mobile-header">
        <div class="school-name"><?php echo SCHOOL_NAME ?></div>
        <div class="hamburger-btn" id="hamburger"><div class="bar"></div><div class="bar"></div><div class="bar"></div></div>
    </div>
    <div class="overlay" id="overlay"></div>

    <div class="main-container">
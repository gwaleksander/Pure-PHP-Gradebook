<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['userID']) && !empty($_SESSION['userID']);
}

function require_role($role) {
    if (!is_logged_in() || $_SESSION['role'] !== $role) {
        die('<h1>Brak uprawnień!</h1><p>Nie masz dostępu do tej strony.</p>');
    }
}

function require_post() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die("Nieprawidłowa metoda. Wymagane POST.");
    }
}
?>
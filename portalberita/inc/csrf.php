<?php
// Buat token CSRF jika belum ada
function createCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token acak
    }
}

// Validasi token CSRF
function validateCsrfToken($token) {
    return isset($token) && $token === $_SESSION['csrf_token'];
}
?>

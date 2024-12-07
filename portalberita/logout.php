<?php
session_start();

// Hapus semua data session
$_SESSION = array();

// Hapus cookie session jika ada
if (isset($_COOKIE[session_name()])) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?>

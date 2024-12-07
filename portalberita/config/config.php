<?php
// Konfigurasi dasar
define('BASE_URL', 'http://localhost/portalberita');
define('CACHE_TIME', 3600);
define('SESSION_TIME', 28800);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session settings
ini_set('session.gc_maxlifetime', SESSION_TIME);
session_set_cookie_params(SESSION_TIME); 
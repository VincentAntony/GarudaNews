<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portalberita');

function getConnection() {
    static $connect = null;
    if ($connect === null) {
        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($connect, "utf8");
    }
    return $connect;
} 
<?php
session_start();
include("inc/koneksi.php");

if(!isset($_SESSION['login']) || !isset($_GET['id']) || !isset($_GET['berita_id'])) {
    header("Location: index.php");
    exit();
}

$notif_id = (int)$_GET['id'];
$berita_id = (int)$_GET['berita_id'];
$user_id = $_SESSION['user_id'];

// Update status notifikasi menjadi sudah dibaca
$update_query = "UPDATE notifications SET is_read = 1 
                WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($connect, $update_query);
mysqli_stmt_bind_param($stmt, "ii", $notif_id, $user_id);
mysqli_stmt_execute($stmt);

// Redirect ke halaman berita
header("Location: ./?open=detail&id=" . $berita_id);
exit();
?> 
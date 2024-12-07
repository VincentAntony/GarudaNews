<?php
session_start();
include("inc/koneksi.php");

// Debug untuk melihat isi session
// var_dump($_SESSION);

// Cek login dan user_id
if(!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $berita_id = isset($_POST['berita_id']) ? (int)$_POST['berita_id'] : 0;
    $user_id = (int)$_SESSION['user_id'];
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    
    // Validasi input
    if($berita_id > 0 && $user_id > 0 && !empty($comment)) {
        $query = "INSERT INTO comments (berita_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iis", $berita_id, $user_id, $comment);
        
        if(mysqli_stmt_execute($stmt)) {
            header("Location: ./?open=detail&id=" . $berita_id);
            exit();
        } else {
            // Debug jika query gagal
            echo "Error: " . mysqli_error($connect);
        }
    } else {
        // Debug untuk nilai variabel
        echo "berita_id: $berita_id, user_id: $user_id, comment: $comment";
    }
}

header("Location: index.php");
exit();
?> 
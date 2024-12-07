<?php
session_start();
include("inc/koneksi.php");

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']));
}

$category_id = (int)$_POST['category_id'];
$action = $_POST['action']; // follow atau unfollow

if ($action === 'follow') {
    $query = "INSERT INTO user_category_follows (user_id, category_id) 
              VALUES (?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $category_id);
} else {
    $query = "DELETE FROM user_category_follows 
              WHERE user_id = ? AND category_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $category_id);
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($connect)]);
} 
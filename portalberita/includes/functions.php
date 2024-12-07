<?php

// Fungsi untuk mengecek apakah user sudah follow kategori
function is_following_category($user_id, $category_id) {
    global $connect;
    $stmt = $connect->prepare("SELECT id FROM user_category_follows 
                             WHERE user_id = ? AND category_id = ?");
    $stmt->bind_param("ii", $user_id, $category_id);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// Fungsi untuk follow kategori
function follow_category($user_id, $category_id) {
    global $connect;
    $stmt = $connect->prepare("INSERT INTO user_category_follows (user_id, category_id) 
                             VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $category_id);
    return $stmt->execute();
}

// Fungsi untuk unfollow kategori
function unfollow_category($user_id, $category_id) {
    global $connect;
    $stmt = $connect->prepare("DELETE FROM user_category_follows 
                             WHERE user_id = ? AND category_id = ?");
    $stmt->bind_param("ii", $user_id, $category_id);
    return $stmt->execute();
} 

function createNotification($berita_id, $kategori) {
    global $connect;
    
    // Ambil semua user yang mengikuti kategori ini
    $query = "SELECT DISTINCT ucf.user_id 
              FROM user_category_follows ucf 
              WHERE ucf.category_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "s", $kategori);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Ambil judul berita
    $berita_query = "SELECT judul FROM berita WHERE ID = ?";
    $stmt2 = mysqli_prepare($connect, $berita_query);
    mysqli_stmt_bind_param($stmt2, "i", $berita_id);
    mysqli_stmt_execute($stmt2);
    $berita = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
    
    // Buat notifikasi untuk setiap user
    while($user = mysqli_fetch_assoc($result)) {
        $message = "Berita baru di kategori yang Anda ikuti: " . $berita['judul'];
        
        $insert_query = "INSERT INTO notifications (user_id, berita_id, message) 
                        VALUES (?, ?, ?)";
        $stmt3 = mysqli_prepare($connect, $insert_query);
        mysqli_stmt_bind_param($stmt3, "iis", $user['user_id'], $berita_id, $message);
        mysqli_stmt_execute($stmt3);
    }
}

.dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}

.badge {
    position: absolute;
    top: 0;
    right: 0;
    padding: 3px 6px;
    border-radius: 50%;
    font-size: 12px;
}

.dropdown-item {
    white-space: normal;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.dropdown-item:last-child {
    border-bottom: none;
}

function clearBeritaCache($id = null) {
    $cache = new Cache();
    
    if ($id) {
        // Hapus cache untuk berita spesifik
        $cache->deleteCache('berita_detail_' . $id);
    }
    
    // Hapus cache halaman berita
    for ($i = 1; $i <= 10; $i++) {
        $cache->deleteCache('berita_page_' . $i);
    }
}
<?php
// Fungsi untuk menangani proses login
function loginUser($username, $password, $connect) {
    // Escape input untuk mencegah SQL Injection
    $username = mysqli_real_escape_string($connect, $username);

    // Query untuk mengambil data user dengan role 'user'
    $sql = "SELECT * FROM users WHERE username = '$username' AND role = 'user'";
    $result = mysqli_query($connect, $sql);

    // Cek apakah user ditemukan
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $row['password'])) {
            // Simpan data session yang diperlukan
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect ke halaman utama
            header("Location: index.php");
            exit;
        } else {
            return 'Password salah!';
        }
    } else {
        return 'Username tidak ditemukan atau bukan user!';
    }
}
?>

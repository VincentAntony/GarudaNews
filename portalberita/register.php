<?php
session_start();
include 'inc/koneksi.php';
include 'inc/fungsi.php';
include 'inc/csrf.php';  // Memasukkan file CSRF
include 'inc/auth.php';  // Memasukkan file autentikasi

global $connect;

// Buat token CSRF jika belum ada
createCsrfToken();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi token CSRF
    if (!validateCsrfToken($_POST['csrf_token'])) {
        echo "<script>alert('Invalid CSRF token');</script>";
        exit;
    }

    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password']; // Simpan password asli untuk validasi

    // Validasi Password
    if (strlen($password) < 8 || 
        !preg_match('/[A-Za-z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W_]/', $password)) { // Karakter spesial
        
        echo "<script>alert('Password harus memiliki minimal 8 karakter, termasuk setidaknya 1 angka, 1 huruf, dan 1 karakter spesial.');</script>";
    } else {
        // Hash password setelah validasi
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

        // Check if username or email exists
        $checkUser = "SELECT * FROM users WHERE email='$email' OR username='$username'";
        $result = mysqli_query($connect, $checkUser);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email atau Username sudah ada.');</script>";
        } else {
            // Proses pendaftaran user
            $sql = "INSERT INTO users (email, Nama, username, password) 
                    VALUES ('$email', '$nama', '$username', '$hashedPassword')";
            if (mysqli_query($connect, $sql)) {
                echo "<script>alert('Pendaftaran berhasil!');</script>";
                header('Location: login.php'); // Redirect to login.php after registration
                exit; // Pastikan untuk keluar setelah redirect
            } else {
                echo "Error: " . mysqli_error($connect);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="../icon.png" sizes="196x196" />
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Register</h5>
                        <form class="form-signin" action="register.php" method="POST">
                            <div class="form-label-group">
                                <input type="text" id="inputNama" class="form-control" name="nama" placeholder="Nama" required autofocus>
                                <label for="inputNama">Nama</label>
                            </div>
                            <div class="form-label-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-label-group">
                                <label for="inputUsername">Username</label>
                                <input type="text" id="inputUsername" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-label-group">
                                <label for="inputPassword">Password</label>
                                <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
                            </div>

                            <!-- Tambahkan input hidden untuk CSRF token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="submit">Register</button>
                        </form>

                        <!-- Tambahkan tombol Back dan Login -->
                        <div class="mt-3 text-center">
                            <a href="index.php" class="btn btn-secondary">Back</a>
                            <a href="login.php" class="btn btn-link">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

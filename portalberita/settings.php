<?php
// Set waktu session sebelum session_start
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();
include("inc/koneksi.php");

// Cek login
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit();
}

// Ambil data user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Proses update profil
if(isset($_POST['update_profile'])) {
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    
    $update_query = "UPDATE users SET nama = ?, email = ?, username = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $username, $user_id);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['nama'] = $nama;
        $success_msg = "Profil berhasil diperbarui!";
    } else {
        $error_msg = "Gagal memperbarui profil!";
    }
}

// Proses update password
if(isset($_POST['update_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password === $confirm_password) {
        // Verifikasi password lama
        if(password_verify($old_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $update_query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($connect, $update_query);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if(mysqli_stmt_execute($stmt)) {
                $success_msg = "Password berhasil diperbarui!";
            } else {
                $error_msg = "Gagal memperbarui password!";
            }
        } else {
            $error_msg = "Password lama tidak sesuai!";
        }
    } else {
        $error_msg = "Password baru tidak cocok!";
    }
}

include("header.php");
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Pengaturan Akun</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($success_msg)): ?>
                        <div class="alert alert-success"><?= $success_msg ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger"><?= $error_msg ?></div>
                    <?php endif; ?>

                    <!-- Update Profil -->
                    <form method="POST" class="mb-4">
                        <h5>Update Profil</h5>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" 
                                   value="<?= htmlspecialchars($user['nama']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            Update Profil
                        </button>
                    </form>

                    <hr>

                    <!-- Update Password -->
                    <form method="POST">
                        <h5>Ganti Password</h5>
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-warning">
                            Ganti Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?> 
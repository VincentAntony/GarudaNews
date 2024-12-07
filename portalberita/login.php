<?php
// Set waktu session sebelum session_start
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

// Mulai session
session_start();

include("inc/koneksi.php");

// Cek apakah session sudah expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 28800)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update last activity time stamp
$_SESSION['LAST_ACTIVITY'] = time();

// Jika sudah login, redirect ke index
if(isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($user = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['login'] = true;
            $_SESSION['CREATED'] = time();
            $_SESSION['LAST_ACTIVITY'] = time();
            
            header("Location: index.php");
            exit();
        }
    }
    $error = "Username atau password salah";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include 'inc/koneksi.php';
include 'inc/fungsi.php';
global $connect;

define('BASE_URL', 'http://localhost/portalberita'); // Sesuaikan dengan URL website Anda
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?php echo ambilprofilweb('meta_desc'); ?>">
  <meta name="keywords" content="<?php echo ambilprofilweb('meta_key'); ?>">
  <meta name="author" content="">
  <title><?php echo ambilprofilweb('title_site'); ?></title>
  <!-- Bootstrap core CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="icon.png" sizes="196x196" />
  <!-- Custom styles for this template -->
  <link href="assets/blog-home.css" rel="stylesheet">
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-transparent position-static" style="margin-top: -55px;">
    <div class="container">
      <img class="navbar-brand" src="image/logo.png">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="./">Home</a>
          </li>
          <!-- Ambil Kategori Dari Database -->
          <!-- konfigurasi pagination -->
          <?php 
              $jumlahDataPerhalaman = 3;
              $dataBerita = mysqli_query($connect, "SELECT * FROM berita");
              $jumlahData = mysqli_num_rows($dataBerita);
              $jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);

              if (isset($_GET['page'])) {
                $halamanAktif = $_GET['page'];
              } else {
                $halamanAktif = 1;
              }
              $awalData = ( $jumlahDataPerhalaman * $halamanAktif ) - $jumlahDataPerhalaman;
          ?>
          <?php 
            $query = "SELECT * FROM kategori WHERE terbit = 1 ORDER BY ID ASC LIMIT 0,10";
            $result = mysqli_query($connect, $query);
           ?>
          <?php while ( $row = mysqli_fetch_assoc($result) ) : ?>
          <li class="nav-item">
            <a class="nav-link" href="./?open=cat&id=<?= $row['ID']; ?>"><?= $row['kategori']; ?></a>
          </li>
          <?php endwhile; ?>
          <?php if(isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
              <li class="nav-item dropdown">
                  <?php
                  // Ambil notifikasi yang belum dibaca
                  $user_id = $_SESSION['user_id'];
                  $notif_query = "SELECT n.*, b.judul, b.ID as berita_id 
                                  FROM notifications n 
                                  JOIN berita b ON n.berita_id = b.ID 
                                  WHERE n.user_id = ? AND n.is_read = 0 
                                  ORDER BY n.created_at DESC";
                  $stmt = mysqli_prepare($connect, $notif_query);
                  mysqli_stmt_bind_param($stmt, "i", $user_id);
                  mysqli_stmt_execute($stmt);
                  $notifications = mysqli_stmt_get_result($stmt);
                  $notif_count = mysqli_num_rows($notifications);
                  ?>
                  
                  <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" 
                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-bell"></i>
                      <?php if($notif_count > 0): ?>
                          <span class="badge badge-danger"><?= $notif_count ?></span>
                      <?php endif; ?>
                  </a>
                  
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifDropdown">
                      <?php if($notif_count > 0): 
                          while($notif = mysqli_fetch_assoc($notifications)): ?>
                              <a class="dropdown-item" href="read_notification.php?id=<?= $notif['id'] ?>&berita_id=<?= $notif['berita_id'] ?>">
                                  <small class="text-muted"><?= date("d-M H:i", strtotime($notif['created_at'])) ?></small>
                                  <br>
                                  <?= $notif['message'] ?>
                              </a>
                          <?php endwhile;
                      else: ?>
                          <span class="dropdown-item">Tidak ada notifikasi baru</span>
                      <?php endif; ?>
                  </div>
              </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
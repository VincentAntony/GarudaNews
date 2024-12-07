<?php
// Set waktu session sebelum session_start
require_once('includes/cache.php');
$cache = new Cache();
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

// Mulai session
session_start();

include("inc/koneksi.php");
include("header.php");

// Cek session expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 28800)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update last activity
$_SESSION['LAST_ACTIVITY'] = time();

// Inisialisasi status login
$isLoggedIn = false;
if(isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $isLoggedIn = true;
}

// Fungsi untuk menampilkan tombol login atau logout

function showLoginOrRegisterButtons() {
  global $isLoggedIn; // Tambahkan global untuk mengakses variabel
  
  if (!$isLoggedIn) {
      echo '<div class="auth-buttons">
          <a href="login.php" class="btn btn-primary">Login</a>
          <a href="register.php" class="btn btn-success">Sign Up</a>
      </div>';
  } else {
      echo '<div class="user-menu">
          <span>Welcome, ' . htmlspecialchars($_SESSION['nama']) . '</span>
          <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>';
  }
}
?>

<!-- Page Content -->
<div class="container">

  <!-- Bagian Login/Sign Up -->
  <?php if (!isset($_SESSION['username'])): ?>
<div class="row">
  <div class="col-md-12">
    <?php showLoginOrRegisterButtons(); ?>
  </div>
</div>
<?php endif; ?>

  <!-- Konten Utama -->
  <div class="row">
    <div class="col-md-8">
      <!-- Switch Page -->
      <?php
      $open = isset($_GET['open']) ? $_GET['open'] : '';
      switch ($open) {
        case 'detail':
          include("detail.php");
          break;
        case 'cat':
          include("kategori.php");
          break;
        case 'cari':
          include("cari.php");
          break;
        default:
          include("depan.php");
          break;
      }
      ?>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 mt-2">
      <!-- Search Widget -->
      <form action="" method="GET">
        <div class="card my-4">
          <h5 class="card-header">Search</h5>
          <div class="card-body">
            <div class="input-group">
              <input type="text" class="form-control" name="key" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-secondary" name="open" type="submit" value="cari">Go!</button>
              </span>
            </div>
          </div>
        </div>
      </form>

      <!-- Categories Widget / Berita Terbaru -->
      <div class="card my-4">
        <h5 class="card-header">Berita Terbaru</h5>
        <?php 
        $query = "SELECT * FROM berita WHERE terbit = '1' ORDER BY ID DESC LIMIT 0,10";
        $result = mysqli_query($connect, $query);
        ?>
        <div class="card-body">
          <ul class="list-group">
          <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <li class="list-group-item d-flex justify-content-between align-items-left">
              <a href="./?open=detail&id=<?=$row['ID']; ?>" class="badge-light" style="text-decoration: none;"><b><?= $row['judul']; ?></b></a>
              <img src="<?= $row['gambar']; ?>" style="max-width: 5rem;">
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-left">
              <?php 
              $date = $row['tanggal'];
              $newDate = date("d-M-Y, H:i:s", strtotime($date)); ?>
              <?= $newDate; ?> |
              Dilihat: <?= $row['viewnum']; ?>
            </li>
          <?php endwhile; ?>
          </ul>
        </div>
      </div>

      <!-- Side Widget / Berita Populer -->
      <div class="card my-4">
        <h5 class="card-header">Berita Populer</h5>
        <?php 
        $query = "SELECT * FROM berita WHERE terbit = '1' AND tanggal >= '".date('Y-m-d H:i:s', strtotime('-7 days'))."' ORDER BY viewnum DESC LIMIT 0,10";
        $result = mysqli_query($connect, $query);
        ?>
        <div class="card-body">
          <ul class="list-group">
          <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <li class="list-group-item d-flex justify-content-between align-items-left">
              <a href="./?open=detail&id=<?=$row['ID']; ?>" class="badge-light" style="text-decoration: none;"><b><?= $row['judul']; ?></b></a>
              <img src="<?= $row['gambar']; ?>" style="max-width: 5rem;">
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-left">
              <?php 
              $date = $row['tanggal'];
              $newDate = date("d-M-Y, H:i:s", strtotime($date)); ?>
              <?= $newDate; ?> |
              Dilihat: <?= $row['viewnum']; ?>
            </li>
          <?php endwhile; ?>
          </ul>
        </div>
      </div>

      <?php if(isset($_SESSION['user_id'])): ?>
          <!-- Berita dari Kategori yang Diikuti -->
          <div class="card my-4">
              <h5 class="card-header">Berita dari Kategori yang Diikuti</h5>
              <div class="card-body">
                  <?php
                  $query = "SELECT DISTINCT b.* 
                            FROM berita b
                            JOIN user_category_follows ucf ON b.kategori_id = ucf.category_id
                            WHERE ucf.user_id = ? AND b.terbit = '1'
                            ORDER BY b.tanggal DESC LIMIT 5";
                  $stmt = mysqli_prepare($connect, $query);
                  mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
                  
                  if(mysqli_num_rows($result) > 0):
                      while($row = mysqli_fetch_assoc($result)): ?>
                          <div class="news-item">
                              <h6><a href="./?open=detail&id=<?=$row['ID']?>"><?=$row['judul']?></a></h6>
                              <small class="text-muted">
                                  <?=date("d-M-Y", strtotime($row['tanggal']))?>
                              </small>
                          </div>
                      <?php endwhile;
                  else: ?>
                      <p class="text-center">Belum ada berita dari kategori yang Anda ikuti</p>
                  <?php endif; ?>
              </div>
          </div>
      <?php endif; ?>

      <!-- Categories Widget -->
      <div class="card my-4">
        <h5 class="card-header">Kategori Berita</h5>
        <div class="card-body">
          <?php if(isset($_SESSION['user_id'])): ?>
            <form id="categoryFollowForm">
              <div class="row">
                <?php
                $query = "SELECT c.*, 
                          CASE WHEN ucf.id IS NOT NULL THEN 1 ELSE 0 END as is_followed
                          FROM techno_category c 
                          LEFT JOIN user_category_follows ucf 
                            ON c.categoryId = ucf.category_id 
                            AND ucf.user_id = ?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                while($category = mysqli_fetch_assoc($result)): ?>
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input category-checkbox" 
                               id="category_<?= $category['categoryId'] ?>"
                               value="<?= $category['categoryId'] ?>"
                               <?= $category['is_followed'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="category_<?= $category['categoryId'] ?>">
                            <?= htmlspecialchars($category['categoryName']) ?>
                        </label>
                    </div>
                <?php endwhile; ?>
              </div>
            </form>
          <?php else: ?>
            <p class="text-center">Silakan <a href="login.php">login</a> untuk mengikuti kategori</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>

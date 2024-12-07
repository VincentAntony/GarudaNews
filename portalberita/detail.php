<?php
require_once('includes/cache.php');
$cache = new Cache();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$cache_key = 'berita_detail_' . $id;

  $query = "SELECT * FROM berita WHERE terbit ='1' AND ID = '$id'";
  $result = mysqli_query($connect, $query);
  $updateviewnum = mysqli_query($connect, "UPDATE berita SET viewnum = viewnum+1 WHERE ID = '$id'");
 ?>
  <div class="container">

    <div class="row">

      <!-- Post Content Column -->
      <div class="col-lg-12">
        <?php while ( $row = mysqli_fetch_assoc($result) ) : ?>
        <!-- Title -->
        <h1 class="mt-4"><?= $row['judul']; ?></h1>

        <!-- Author -->
        <p class="lead">
          by
          <b><?= $row['updateby']; ?></b>
        </p>

        <hr>

        <!-- Date/Time -->
        <?php $date = $row['tanggal'];
        $newDate = date("d-F-Y , H:i:s", strtotime($date)); ?>
        <p>Posted on <?= $newDate; ?> WIB</p>

        <hr>

        <!-- Preview Image -->
        <img class="img-fluid rounded col-lg-12" src="<?= $row['gambar']; ?>" alt="<?= $row['judul']; ?>">

        <hr>

        <!-- Post Content -->
        <p><?= nl2br($row['isi']); ?></p>

        <hr>

        <!-- Social Share Buttons -->
        <div class="social-share mt-4 mb-4">
            <h5>Bagikan Berita:</h5>
            <div class="d-flex gap-2">
                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text=<?= urlencode($row['judul'] . ' - ' . BASE_URL . '/?open=detail&id=' . $row['ID']) ?>" 
                   class="btn btn-success btn-sm"
                   target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>

                <!-- Telegram -->
                <a href="https://t.me/share/url?url=<?= urlencode(BASE_URL . '/?open=detail&id=' . $row['ID']) ?>&text=<?= urlencode($row['judul']) ?>" 
                   class="btn btn-primary btn-sm"
                   target="_blank">
                    <i class="fab fa-telegram"></i> Telegram
                </a>

                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/?open=detail&id=' . $row['ID']) ?>" 
                   class="btn btn-primary btn-sm"
                   target="_blank">
                    <i class="fab fa-facebook"></i> Facebook
                </a>

                <!-- Twitter/X -->
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode($row['judul']) ?>&url=<?= urlencode(BASE_URL . '/?open=detail&id=' . $row['ID']) ?>" 
                   class="btn btn-dark btn-sm"
                   target="_blank">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
            </div>
        </div>
  </div>
  <?php
  // Ambil kategori dari berita saat ini
  $current_news_id = $_GET['id'];
  $current_category_query = "SELECT kategori FROM berita WHERE ID = ?";  // Menggunakan kolom 'kategori' bukan 'kategori_id'
  $stmt = mysqli_prepare($connect, $current_category_query);
  mysqli_stmt_bind_param($stmt, "i", $current_news_id);
  mysqli_stmt_execute($stmt);
  $category_result = mysqli_stmt_get_result($stmt);
  $category_data = mysqli_fetch_assoc($category_result);
  $current_category = $category_data['kategori']; // Menggunakan kolom 'kategori'
  
  // Query untuk mengambil berita terkait berdasarkan kategori yang sama
  $related_query = "SELECT * FROM berita 
                   WHERE kategori = ? 
                   AND ID != ? 
                   AND terbit = '1'
                   ORDER BY tanggal DESC 
                   LIMIT 5";
                   
  $stmt = mysqli_prepare($connect, $related_query);
  mysqli_stmt_bind_param($stmt, "si", $current_category, $current_news_id);
  mysqli_stmt_execute($stmt);
  $related_result = mysqli_stmt_get_result($stmt);

// Tampilkan berita terkait
if(mysqli_num_rows($related_result) > 0): ?>
    <div class="card my-4">
        <h5 class="card-header">Berita Terkait</h5>
        <div class="card-body">
            <div class="row">
                <?php while($related = mysqli_fetch_assoc($related_result)): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <img src="<?= $related['gambar'] ?>" class="card-img-top" alt="<?= $related['judul'] ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="./?open=detail&id=<?= $related['ID'] ?>" class="text-decoration-none">
                                        <?= $related['judul'] ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?= date("d-M-Y", strtotime($related['tanggal'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php endif; ?><?php endwhile; ?>
</div>
</div>

<!-- Bagian Komentar -->
<div class="card my-4">
    <h5 class="card-header">Komentar</h5>
    <div class="card-body">
        <?php if($isLoggedIn): ?>
            <!-- Form Komentar -->
            <form method="POST" action="process_comment.php">
                <input type="hidden" name="berita_id" value="<?= $id ?>">
                <div class="form-group">
                    <textarea class="form-control" name="comment" rows="3" required placeholder="Tulis komentar Anda..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Kirim Komentar</button>
            </form>
        <?php else: ?>
            <p class="text-center">Silakan <a href="login.php">login</a> untuk memberikan komentar</p>
        <?php endif; ?>

        <!-- Daftar Komentar -->
        <div class="comments-list mt-4">
            <?php
            $comment_query = "SELECT c.*, u.username, u.nama 
                            FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.berita_id = ? 
                            ORDER BY c.created_at DESC";
            $stmt = mysqli_prepare($connect, $comment_query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $comments = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($comments) > 0):
                while($comment = mysqli_fetch_assoc($comments)): ?>
                    <div class="comment-item border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1"><?= htmlspecialchars($comment['nama']) ?></h6>
                            <small class="text-muted">
                                <?= date("d-M-Y H:i", strtotime($comment['created_at'])) ?>
                            </small>
                        </div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    </div>
                <?php endwhile;
            else: ?>
                <p class="text-center text-muted">Belum ada komentar</p>
            <?php endif; ?>
        </div>
    </div>
</div>

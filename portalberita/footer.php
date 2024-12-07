 <!-- Footer -->
 <footer class="bg-dark text-light mt-5">
    <div class="container py-4">
        <div class="row">
            <!-- Contact Us -->
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2"></i> email@example.com</li>
                    <li><i class="fas fa-phone me-2"></i> +62 123 4567 890</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Jalan Example No. 123, Jakarta</li>
                    <li class="mt-3">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="about.php" class="text-light">About Us</a></li>
                    <li><a href="privacy.php" class="text-light">Privacy Policy</a></li>
                    <li><a href="terms.php" class="text-light">Terms of Service</a></li>
                    <li><a href="disclaimer.php" class="text-light">Disclaimer</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-md-4">
                <h5>Newsletter</h5>
                <p>Subscribe untuk mendapatkan berita terbaru</p>
                <form action="subscribe.php" method="POST" class="mt-3">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email address" required>
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center py-3 border-top border-secondary">
        <small>&copy; <?= date('Y') ?> Portal Berita. All rights reserved.</small>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php if(isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="settings.php">
                <i class="fas fa-cog"></i> Pengaturan Akun
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </li>
<?php endif; ?>
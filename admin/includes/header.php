<header class="top-header">
    <div class="header-left">
        <button class="toggle-sidebar" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <a href="../index.php" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
    </div>
    <div class="header-right">
        <div class="admin-profile">
            <div class="admin-info"><span><?php echo $_SESSION['admin_nama'] ?? 'Admin'; ?></span><small>Administrator</small></div>
            <img src="../assets/images/logo-sekolah.png" alt="Admin">
        </div>
    </div>
</header>
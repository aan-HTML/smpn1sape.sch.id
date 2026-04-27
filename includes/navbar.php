<?php
if(!isset($profil_nav)){$profil_nav=$conn->query("SELECT * FROM profil_sekolah WHERE id=1")->fetch_assoc();}
if(!isset($settings_nav)){$settings_nav=[];$rs=$conn->query("SELECT * FROM pengaturan");if($rs){while($r=$rs->fetch_assoc()){$settings_nav[$r['nama_key']]=$r['nilai'];}}}
$in_p=(strpos($_SERVER['PHP_SELF'],'/pages/')!==false);
$base=$in_p?'../':'';
$cur=basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="container">
        <div class="nav-container">
            <a href="<?php echo $base; ?>index.php" class="nav-brand">
                <img src="<?php echo $base; ?>assets/images/logo-sekolah.png" alt="Logo" class="nav-logo" loading="lazy">
                <div class="nav-title"><h1>SMP Negeri 1 Sape</h1><p>Kabupaten Bima, Nusa Tenggara Barat</p></div>
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Menu"><i class="fas fa-bars"></i></button>
            <ul class="nav-menu" id="navMenu">
                <!-- Mobile Menu Header (hanya tampil di mobile) -->
                <li class="mobile-menu-header">
                    <?php
                    $fb = $settings_nav['facebook'] ?? '#';
                    $ig = $settings_nav['instagram'] ?? '#';
                    $yt = $settings_nav['youtube'] ?? '#';
                    $jam_layanan = $settings_nav['jam_layanan'] ?? ($settings_nav['jam_operasional'] ?? '07:00 - 17:00 WITA');
                    ?>
                    <div class="mobile-menu-topbar">
                        <button class="nav-close" id="navClose" aria-label="Tutup menu">&times;</button>
                    </div>
                    <div class="mobile-menu-meta"><i class="far fa-clock"></i> <?php echo htmlspecialchars($jam_layanan); ?></div>
                    <div class="mobile-menu-socials">
                        <a href="<?php echo htmlspecialchars($fb); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo htmlspecialchars($ig); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo htmlspecialchars($yt); ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </li>
                <!-- Menu Items (sama seperti aslinya) -->
                <li><a href="<?php echo $base; ?>index.php" <?php echo($cur=='index.php')?'class="active"':'';?>><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="<?php echo $base; ?>pages/visi-misi.php" <?php echo($cur=='visi-misi.php')?'class="active"':'';?>><i class="fas fa-star"></i> Visi &amp; Misi</a></li>
                <li><a href="<?php echo $base; ?>pages/profil.php" <?php echo($cur=='profil.php')?'class="active"':'';?>><i class="fas fa-school"></i> Profil</a></li>
                <li><a href="<?php echo $base; ?>pages/postingan.php" <?php echo($cur=='postingan.php')?'class="active"':'';?>><i class="fas fa-newspaper"></i> Postingan</a></li>
                <li class="nav-search-li"><button class="nav-search-btn" id="searchToggleBtn" aria-label="Cari"><i class="fas fa-search"></i></button></li>
                <li class="nav-ppdb"><a href="<?php echo $base; ?>ppdb.php"><i class="fas fa-user-graduate"></i> PPDB</a></li>
                <!-- PPDB button khusus di mobile menu bagian bawah -->
                <li class="mobile-ppdb-bottom"><a href="<?php echo $base; ?>ppdb.php"><i class="fas fa-user-graduate"></i> PPDB</a></li>
            </ul>
        </div>
    </div>
    <div class="search-overlay" id="searchOverlay">
        <button class="search-close-btn" id="searchCloseBtn" aria-label="Tutup Pencarian">&times;</button>
        <form action="<?php echo $base; ?>pages/postingan.php" method="GET">
            <input type="text" name="q" placeholder="Ketik untuk mencari..." autocomplete="off">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="nav-overlay" id="navOverlay"></div>
</nav>

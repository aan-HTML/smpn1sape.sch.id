<?php
require_once '../includes/config.php';

// Ambil via slug (prioritas) atau id (fallback)
if (isset($_GET['slug']) && $_GET['slug'] !== '') {
    $slug = $_GET['slug'];
    $stmt = $conn->prepare("SELECT * FROM berita WHERE slug=? AND status='published'");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $res = $stmt->get_result();
} elseif (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM berita WHERE id=? AND status='published'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    header('Location: berita.php'); exit;
}

if (!$res || $res->num_rows == 0) { header('Location: berita.php'); exit; }
$berita = $res->fetch_assoc();
$id = $berita['id'];
$conn->query("UPDATE berita SET views = views + 1 WHERE id = $id");

$terkait = $conn->query("SELECT * FROM berita WHERE status='published' AND id!=$id ORDER BY created_at DESC LIMIT 5");
$tr=[]; if($terkait){while($r=$terkait->fetch_assoc()){$tr[]=$r;}}

// Hitung estimasi waktu baca
$word_count = str_word_count(strip_tags($berita['konten']));
$reading_time = max(1, ceil($word_count / 200));

// Canonical URL pakai slug
$canonical_url = 'https://smpn1sape.web.id/berita/' . htmlspecialchars($berita['slug']);

// Keywords
$kw_base = htmlspecialchars($berita['judul']) . ', SMP Negeri 1 Sape, SMPN 1 Sape, Sape, Bima, NTB';
if (!empty($berita['kategori'])) $kw_base = htmlspecialchars($berita['kategori']) . ', ' . $kw_base;

// Deskripsi bersih
$deskripsi = htmlspecialchars(substr(strip_tags($berita['konten']), 0, 160));

// Gambar OG — wajib absolut HTTPS agar muncul di WhatsApp
if (!empty($berita['gambar'])) {
    if (strpos($berita['gambar'], 'berita/') === 0) {
        $og_image = 'https://smpn1sape.web.id/assets/images/' . htmlspecialchars($berita['gambar']);
    } else {
        $og_image = 'https://smpn1sape.web.id/assets/images/berita/' . htmlspecialchars($berita['gambar']);
    }
} else {
    $og_image = 'https://smpn1sape.web.id/assets/images/logo-sekolah.png';
}

// Fungsi render konten: support plain text maupun HTML
function render_konten($konten) {
    // Cek apakah konten mengandung tag HTML
    if (strip_tags($konten) === $konten) {
        // Plain text: pisah per paragraf (baris kosong), wrap dengan <p>
        $paragraf = array_filter(array_map('trim', explode("\n\n", trim($konten))));
        $output = '';
        foreach ($paragraf as $p) {
            $output .= '<p>' . nl2br(htmlspecialchars($p)) . '</p>';
        }
        return $output ?: '<p>' . nl2br(htmlspecialchars(trim($konten))) . '</p>';
    } else {
        // Sudah HTML (rich text editor): tampilkan langsung
        return $konten;
    }
}
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?php echo htmlspecialchars($berita['judul']); ?> - SMP Negeri 1 Sape</title>
<meta name="description" content="<?php echo $deskripsi; ?>">
<meta name="keywords" content="<?php echo $kw_base; ?>">
<meta name="author" content="<?php echo !empty($berita['penulis']) ? htmlspecialchars($berita['penulis']) : 'SMP Negeri 1 Sape'; ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?php echo $canonical_url; ?>">

<!-- ===== Open Graph (Facebook, WhatsApp, Telegram) ===== -->
<meta property="og:type" content="article">
<meta property="og:title" content="<?php echo htmlspecialchars($berita['judul']); ?>">
<meta property="og:description" content="<?php echo $deskripsi; ?>">
<meta property="og:url" content="<?php echo $canonical_url; ?>">
<meta property="og:image" content="<?php echo $og_image; ?>">
<meta property="og:image:secure_url" content="<?php echo $og_image; ?>">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?php echo htmlspecialchars($berita['judul']); ?>">
<meta property="og:locale" content="id_ID">
<meta property="og:site_name" content="SMP Negeri 1 Sape">
<meta property="article:published_time" content="<?php echo $berita['created_at']; ?>">
<?php if(!empty($berita['penulis'])): ?>
<meta property="article:author" content="<?php echo htmlspecialchars($berita['penulis']); ?>">
<?php endif; ?>

<!-- ===== Twitter Card ===== -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo htmlspecialchars($berita['judul']); ?>">
<meta name="twitter:description" content="<?php echo $deskripsi; ?>">
<meta name="twitter:image" content="<?php echo $og_image; ?>">
<meta name="twitter:image:alt" content="<?php echo htmlspecialchars($berita['judul']); ?>">

<!-- ===== Schema.org ===== -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"NewsArticle","headline":"<?php echo addslashes($berita['judul']); ?>","image":["<?php echo $og_image; ?>"],"datePublished":"<?php echo $berita['created_at']; ?>","dateModified":"<?php echo $berita['updated_at']; ?>","author":{"@type":"<?php echo !empty($berita['penulis']) ? 'Person' : 'Organization'; ?>","name":"<?php echo !empty($berita['penulis']) ? addslashes($berita['penulis']) : 'SMP Negeri 1 Sape'; ?>"},"publisher":{"@type":"Organization","name":"SMP Negeri 1 Sape","logo":{"@type":"ImageObject","url":"https://smpn1sape.web.id/assets/images/logo-sekolah.png"}},"url":"<?php echo $canonical_url; ?>","description":"<?php echo addslashes(substr(strip_tags($berita['konten']),0,200)); ?>"}
</script>

<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><a href="../pages/berita.php">Berita</a><span class="sep">/</span><span><?php echo htmlspecialchars(substr($berita['judul'],0,40)).'...'; ?></span></div></div></div>
<div class="page-body"><div class="container">
<div class="post-layout">
  <!-- Main Content -->
  <article>
    <h1 class="article-title"><?php echo htmlspecialchars($berita['judul']); ?></h1>
    <div class="post-meta-chips">
      <span class="meta-chip meta-chip-date"><i class="fas fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($berita['created_at'])); ?></span>
      <span class="meta-chip meta-chip-views"><i class="fas fa-eye"></i> <?php echo number_format($berita['views']); ?> tayangan</span>
      <?php if(!empty($berita['penulis'])): ?>
      <span class="meta-chip meta-chip-author"><i class="fas fa-user"></i> <?php echo htmlspecialchars($berita['penulis']); ?></span>
      <?php endif; ?>
      <span class="meta-chip meta-chip-read"><i class="fas fa-clock"></i> <?php echo $reading_time; ?> menit baca</span>
    </div>
    <?php if(!empty($berita['gambar'])): ?>
    <img src="../assets/images/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="<?php echo htmlspecialchars($berita['judul']); ?>" class="article-hero-img" loading="lazy" width="800" height="450">
    <?php endif; ?>
    <div class="post-body"><?php echo render_konten($berita['konten']); ?></div>
    <div class="share-bar">
      <span class="share-label">Bagikan:</span>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($canonical_url); ?>" target="_blank" rel="noopener" class="share-btn share-btn-fb"><i class="fab fa-facebook-f"></i> Facebook</a>
      <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($berita['judul'].' - '.$canonical_url); ?>" target="_blank" rel="noopener" class="share-btn share-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
    </div>
    <div>
      <a href="../pages/berita.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Berita</a>
    </div>
  </article>
  <!-- Sidebar -->
  <aside>
    <!-- BERITA TERKAIT - redesign -->
    <div class="sidebar-widget sidebar-widget-news">
      <h4><i class="fas fa-newspaper"></i> Berita Terkait</h4>
      <?php if(!empty($tr)): foreach($tr as $t): 
        $url_t = !empty($t['slug']) ? '../berita/'.$t['slug'] : 'berita-detail.php?id='.$t['id'];
      ?>
      <a href="<?php echo $url_t; ?>" class="snews-card">
        <div class="snews-thumb">
          <img src="<?php echo get_thumb($t['gambar']); ?>" alt="<?php echo htmlspecialchars($t['judul']); ?>" loading="lazy">
          <span class="snews-badge"><?php echo htmlspecialchars($t['kategori'] ?? 'Berita'); ?></span>
        </div>
        <div class="snews-body">
          <p class="snews-title"><?php echo htmlspecialchars($t['judul']); ?></p>
          <span class="snews-date"><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y',strtotime($t['created_at'])); ?></span>
        </div>
      </a>
      <?php endforeach; else: ?>
      <p class="snews-empty">Belum ada berita lain.</p>
      <?php endif; ?>
    </div>

    <!-- TENTANG SEKOLAH - redesign -->
    <div class="sidebar-widget sidebar-widget-school">
      <h4><i class="fas fa-school"></i> Tentang Sekolah</h4>
      <!-- Mini stats -->
      <div class="school-stats-mini">
        <div class="school-stat-item">
          <span class="school-stat-num">A</span>
          <span class="school-stat-lbl">Akreditasi</span>
        </div>
        <div class="school-stat-item">
          <span class="school-stat-num">NTB</span>
          <span class="school-stat-lbl">Provinsi</span>
        </div>
        <div class="school-stat-item">
          <span class="school-stat-num">'86</span>
          <span class="school-stat-lbl">Berdiri</span>
        </div>
      </div>
      <!-- Link navigasi berisi deskripsi -->
      <div class="school-links-new">
        <a href="../pages/visi-misi.php" class="school-link-row">
          <span class="school-link-icon school-icon-blue"><i class="fas fa-eye"></i></span>
          <div class="school-link-text">
            <strong>Visi &amp; Misi</strong>
            <p>Tujuan &amp; arah pendidikan</p>
          </div>
          <i class="fas fa-chevron-right school-link-arrow"></i>
        </a>
        <a href="../pages/profil.php" class="school-link-row">
          <span class="school-link-icon school-icon-green"><i class="fas fa-id-card"></i></span>
          <div class="school-link-text">
            <strong>Profil Sekolah</strong>
            <p>Data &amp; identitas resmi</p>
          </div>
          <i class="fas fa-chevron-right school-link-arrow"></i>
        </a>
        <a href="../pages/prestasi.php" class="school-link-row">
          <span class="school-link-icon school-icon-gold"><i class="fas fa-trophy"></i></span>
          <div class="school-link-text">
            <strong>Prestasi</strong>
            <p>Raihan siswa &amp; sekolah</p>
          </div>
          <i class="fas fa-chevron-right school-link-arrow"></i>
        </a>
        <a href="../pages/ekstrakurikuler.php" class="school-link-row">
          <span class="school-link-icon school-icon-purple"><i class="fas fa-star"></i></span>
          <div class="school-link-text">
            <strong>Ekstrakurikuler</strong>
            <p>Kegiatan di luar kelas</p>
          </div>
          <i class="fas fa-chevron-right school-link-arrow"></i>
        </a>
      </div>
    </div>
  </aside>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>
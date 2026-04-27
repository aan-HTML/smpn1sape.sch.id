<?php
require_once '../includes/config.php';
$icons=['fa-hiking','fa-flag','fa-drum','fa-futbol','fa-music','fa-language','fa-palette','fa-camera','fa-chess','fa-running','fa-book','fa-theater-masks'];

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if($slug !== '') {
    $slug_safe = $conn->real_escape_string($slug);
    $res_detail = $conn->query("SELECT e.*, GROUP_CONCAT(p.nama_pembina SEPARATOR ', ') as pembina_list FROM ekstrakurikuler e LEFT JOIN pembina_ekstrakurikuler p ON e.id=p.ekstrakurikuler_id WHERE e.status='aktif' AND LOWER(REPLACE(e.nama,' ','-'))='$slug_safe' GROUP BY e.id LIMIT 1");
    $ekskul = $res_detail ? $res_detail->fetch_assoc() : null;

    if($ekskul) {
        $eid = (int)$ekskul['id'];
        $res_foto = $conn->query("SELECT * FROM galeri WHERE ekstrakurikuler_id=$eid ORDER BY tanggal DESC, id DESC");
        $foto_kegiatan = [];
        if($res_foto){ while($r=$res_foto->fetch_assoc()){ $foto_kegiatan[]=$r; } }

        $res_all = $conn->query("SELECT id FROM ekstrakurikuler WHERE status='aktif' ORDER BY nama ASC");
        $icon_idx = 0;
        if($res_all){ $ii=0; while($r=$res_all->fetch_assoc()){ if($r['id']==$eid){ $icon_idx=$ii; break; } $ii++; } }
    }
}
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?php echo ($slug&&isset($ekskul)&&$ekskul) ? htmlspecialchars($ekskul['nama']).' - ' : ''; ?>Ekstrakurikuler - SMP Negeri 1 Sape</title>
<meta name="description" content="Daftar kegiatan ekstrakurikuler pengembangan bakat dan minat siswa SMP Negeri 1 Sape.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>

<?php if($slug !== '' && isset($ekskul) && $ekskul): ?>

<!-- HALAMAN DETAIL -->
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span>
  <a href="ekstrakurikuler.php">Ekstrakurikuler</a><span class="sep">/</span>
  <span><?php echo htmlspecialchars($ekskul['nama']); ?></span>
</div></div></div>

<div class="ekskul-detail-hero">
  <div class="container">
    <div class="ekskul-detail-icon">
      <i class="fas <?php echo $icons[$icon_idx % count($icons)]; ?>"></i>
    </div>
    <div class="ekskul-detail-info">
      <h1><?php echo htmlspecialchars($ekskul['nama']); ?></h1>
      <div class="ekskul-detail-meta">
        <?php if(!empty($ekskul['jadwal'])): ?>
        <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($ekskul['jadwal']); ?></span>
        <?php endif; ?>
        <?php if(!empty($ekskul['pembina_list'])): ?>
        <span><i class="fas fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($ekskul['pembina_list']); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="ekskul-detail-body">
  <div class="container">
    <a href="ekstrakurikuler.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Ekstrakurikuler</a>

    <?php if(!empty($ekskul['deskripsi'])): ?>
    <div class="ekskul-detail-desc fade-in">
      <h3><i class="fas fa-info-circle"></i> Tentang <?php echo htmlspecialchars($ekskul['nama']); ?></h3>
      <p><?php echo nl2br(htmlspecialchars($ekskul['deskripsi'])); ?></p>
    </div>
    <?php endif; ?>

    <div class="ekskul-foto-section fade-in">
      <h3><i class="fas fa-images"></i> Foto Kegiatan</h3>
      <?php if(!empty($foto_kegiatan)): ?>
      <div class="ekskul-foto-grid">
        <?php foreach($foto_kegiatan as $f): ?>
        <div class="ekskul-foto-item">
          <img src="../assets/images/<?php echo htmlspecialchars($f['foto']); ?>" alt="<?php echo htmlspecialchars($f['judul']); ?>" loading="lazy">
          <div class="ekskul-foto-caption">
            <?php echo htmlspecialchars($f['judul']); ?>
            <?php if(!empty($f['tanggal'])): ?>
            <div class="tgl"><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($f['tanggal'])); ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="ekskul-empty-foto">
        <i class="fas fa-camera"></i>
        <p>Belum ada foto kegiatan untuk ekstrakurikuler ini.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php elseif($slug !== ''): ?>

<!-- EKSKUL TIDAK DITEMUKAN -->
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span>
  <a href="ekstrakurikuler.php">Ekstrakurikuler</a><span class="sep">/</span>
  <span>Tidak Ditemukan</span>
</div></div></div>
<div class="page-body"><div class="container">
  <div class="empty-state">
    <i class="fas fa-search"></i>
    <h2>Ekstrakurikuler tidak ditemukan</h2>
    <p>Data yang Anda cari tidak tersedia.</p>
    <p style="margin-top:16px"><a href="ekstrakurikuler.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a></p>
  </div>
</div></div>

<?php else: ?>

<!-- HALAMAN DAFTAR -->
<?php
$res = $conn->query("SELECT e.*, GROUP_CONCAT(p.nama_pembina SEPARATOR ', ') as pembina_list FROM ekstrakurikuler e LEFT JOIN pembina_ekstrakurikuler p ON e.id=p.ekstrakurikuler_id WHERE e.status='aktif' GROUP BY e.id ORDER BY e.nama ASC");
?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span><span>Ekstrakurikuler</span>
</div></div></div>
<div class="page-hero"><div class="container">
  <h1><i class="fas fa-star" style="margin-right:10px;opacity:.85"></i>Ekstrakurikuler</h1>
  <p>Kegiatan pengembangan bakat dan minat siswa SMP Negeri 1 Sape</p>
</div></div>
<div class="page-body"><div class="container">
<?php if($res && $res->num_rows > 0): ?>
<div class="card-grid">
<?php $i=0; while($row=$res->fetch_assoc()):
  $row_slug = urlencode(strtolower(str_replace(' ','-',$row['nama'])));
?>
<div class="feature-card fade-in">
  <div class="feature-card-icon">
    <i class="fas <?php echo $icons[$i%count($icons)]; ?>"></i>
  </div>
  <div class="feature-card-body">
    <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
    <p><?php echo htmlspecialchars(substr($row['deskripsi']??'',0,110)); ?></p>
    <div class="feature-meta">
      <?php if(!empty($row['jadwal'])): ?>
      <div class="feature-meta-row"><i class="fas fa-clock"></i><span><?php echo htmlspecialchars($row['jadwal']); ?></span></div>
      <?php endif; ?>
      <?php if(!empty($row['pembina_list'])): ?>
      <div class="feature-meta-row"><i class="fas fa-chalkboard-teacher"></i><span><?php echo htmlspecialchars($row['pembina_list']); ?></span></div>
      <?php endif; ?>
    </div>
    <a href="ekstrakurikuler.php?slug=<?php echo $row_slug; ?>" class="feature-link">
      <i class="fas fa-images"></i> Lihat Kegiatan
    </a>
  </div>
</div>
<?php $i++; endwhile; ?>
</div>
<?php else: ?>
<div class="empty-state"><i class="fas fa-star"></i><p>Belum ada data ekstrakurikuler.</p></div>
<?php endif; ?>
</div></div>

<?php endif; ?>

<?php include '../includes/footer.php'; ?>
</body></html>

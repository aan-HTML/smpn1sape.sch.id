<?php
require_once '../includes/config.php';
$tingkat  = isset($_GET['tingkat'])  ? $_GET['tingkat']  : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$where_parts = [];
if ($tingkat)  $where_parts[] = "tingkat='"  .$conn->real_escape_string($tingkat)."'";
if ($kategori) $where_parts[] = "kategori='" .$conn->real_escape_string($kategori)."'";
$where = $where_parts ? "WHERE ".implode(" AND ", $where_parts) : '';
$res = $conn->query("SELECT * FROM prestasi $where ORDER BY tahun DESC, tingkat DESC");
function has_foto($f){return !empty($f);}
$tingkats  = ['Internasional','Nasional','Provinsi','Kabupaten','Kecamatan'];
$kategoris = ['Siswa','Tim','Sekolah'];
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Kejuaraan - SMP Negeri 1 Sape</title>
<meta name="description" content="Daftar kejuaraan dan prestasi yang diraih siswa, tim, dan SMP Negeri 1 Sape di berbagai tingkat.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Kejuaraan</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-trophy" style="margin-right:10px;opacity:.85"></i>Kejuaraan &amp; Prestasi</h1><p>Raihan siswa, tim, dan sekolah di berbagai tingkat</p></div></div>
<div class="page-body"><div class="container">

<!-- Filter Kategori -->
<div class="filter-group">
  <span class="filter-label">Kategori</span>
  <div class="filter-pills">
    <a href="prestasi.php<?php echo $tingkat?'?tingkat='.urlencode($tingkat):''; ?>" class="filter-pill <?php echo !$kategori?'active':''; ?>">Semua</a>
    <?php foreach($kategoris as $k): $link='prestasi.php?kategori='.urlencode($k).($tingkat?'&tingkat='.urlencode($tingkat):''); ?>
    <a href="<?php echo $link; ?>" class="filter-pill <?php echo $kategori==$k?'active':''; ?>"><?php echo $k; ?></a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Filter Tingkat -->
<div class="filter-group">
  <span class="filter-label">Tingkat</span>
  <div class="filter-pills">
    <a href="prestasi.php<?php echo $kategori?'?kategori='.urlencode($kategori):''; ?>" class="filter-pill <?php echo !$tingkat?'active':''; ?>">Semua</a>
    <?php foreach($tingkats as $t): $link='prestasi.php?tingkat='.urlencode($t).($kategori?'&kategori='.urlencode($kategori):''); ?>
    <a href="<?php echo $link; ?>" class="filter-pill <?php echo $tingkat==$t?'active':''; ?>"><?php echo $t; ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div class="prestasi-cards">
<?php if($res&&$res->num_rows>0): while($row=$res->fetch_assoc()): ?>
<div class="prestasi-card fade-in">
  <div class="prestasi-card-thumb">
    <?php if(has_foto($row['foto'])): ?>
    <img src="../assets/images/<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>" loading="lazy" width="400" height="300">
    <?php else: ?>
    <div class="prestasi-thumb-fallback"><i class="fas fa-trophy"></i></div>
    <?php endif; ?>
    <div class="prestasi-badge"><i class="fas fa-medal"></i> <?php echo htmlspecialchars($row['juara']); ?></div>
  </div>
  <div class="prestasi-card-body">
    <div class="prestasi-tags">
      <span class="tag-primary"><?php echo htmlspecialchars($row['tingkat']); ?></span>
      <span class="tag-accent"><?php echo htmlspecialchars($row['kategori']); ?></span>
    </div>
    <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
    <p><?php echo htmlspecialchars($row['nama_lomba']); ?></p>
    <div class="prestasi-year"><i class="fas fa-calendar-check"></i> Tahun <?php echo (int)$row['tahun']; ?></div>
  </div>
</div>
<?php endwhile; else: ?>
<div class="empty-state full-row"><i class="fas fa-trophy"></i><p>Belum ada data kejuaraan dengan filter ini.</p></div>
<?php endif; ?>
</div>

</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

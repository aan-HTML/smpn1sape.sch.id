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
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Kejuaraan</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-trophy" style="margin-right:10px;opacity:.8"></i>Kejuaraan &amp; Prestasi</h1><p>Daftar kejuaraan dan prestasi yang diraih oleh siswa, tim, dan sekolah</p></div></div>
<div style="padding:32px 0"><div class="container">

<!-- Filter Kategori -->
<div style="margin-bottom:12px">
  <span style="font-size:12px;color:var(--text-light);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Kategori:</span>
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px">
    <a href="prestasi.php<?php echo $tingkat?'?tingkat='.urlencode($tingkat):''; ?>" style="padding:5px 14px;border:1.5px solid <?php echo !$kategori?'var(--primary)':'var(--border)'; ?>;border-radius:20px;font-size:13px;font-weight:600;background:<?php echo !$kategori?'var(--primary)':'var(--white)'; ?>;color:<?php echo !$kategori?'#fff':'var(--text)'; ?>">Semua</a>
    <?php foreach($kategoris as $k):
      $link = 'prestasi.php?kategori='.urlencode($k).($tingkat?'&tingkat='.urlencode($tingkat):'');
    ?>
    <a href="<?php echo $link; ?>" style="padding:5px 14px;border:1.5px solid <?php echo $kategori==$k?'var(--primary)':'var(--border)'; ?>;border-radius:20px;font-size:13px;font-weight:600;background:<?php echo $kategori==$k?'var(--primary)':'var(--white)'; ?>;color:<?php echo $kategori==$k?'#fff':'var(--text)'; ?>"><?php echo $k; ?></a>
    <?php endforeach; ?>
  </div>
</div>

<!-- Filter Tingkat -->
<div style="margin-bottom:24px">
  <span style="font-size:12px;color:var(--text-light);font-weight:600;text-transform:uppercase;letter-spacing:.5px">Tingkat:</span>
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px">
    <a href="prestasi.php<?php echo $kategori?'?kategori='.urlencode($kategori):''; ?>" style="padding:5px 14px;border:1.5px solid <?php echo !$tingkat?'var(--primary)':'var(--border)'; ?>;border-radius:20px;font-size:13px;font-weight:600;background:<?php echo !$tingkat?'var(--primary)':'var(--white)'; ?>;color:<?php echo !$tingkat?'#fff':'var(--text)'; ?>">Semua</a>
    <?php foreach($tingkats as $t):
      $link = 'prestasi.php?tingkat='.urlencode($t).($kategori?'&kategori='.urlencode($kategori):'');
    ?>
    <a href="<?php echo $link; ?>" style="padding:5px 14px;border:1.5px solid <?php echo $tingkat==$t?'var(--primary)':'var(--border)'; ?>;border-radius:20px;font-size:13px;font-weight:600;background:<?php echo $tingkat==$t?'var(--primary)':'var(--white)'; ?>;color:<?php echo $tingkat==$t?'#fff':'var(--text)'; ?>"><?php echo $t; ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div class="prestasi-cards" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr))">
<?php if($res&&$res->num_rows>0): while($row=$res->fetch_assoc()): ?>
<div class="prestasi-card">
  <div class="prestasi-card-thumb">
    <?php if(has_foto($row['foto'])): ?>
    <img src="../assets/images/<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;">
    <?php else: ?>
    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--primary-light)">
      <i class="fas fa-trophy" style="font-size:48px;color:var(--primary);opacity:.4"></i>
    </div>
    <?php endif; ?>
    <div class="prestasi-badge"><i class="fas fa-medal"></i> <?php echo htmlspecialchars($row['juara']); ?></div>
  </div>
  <div class="prestasi-card-body">
    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:6px">
      <span style="background:var(--primary-light);color:var(--primary);padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700"><?php echo htmlspecialchars($row['tingkat']); ?></span>
      <span style="background:#fef3c7;color:#92400e;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700"><?php echo htmlspecialchars($row['kategori']); ?></span>
    </div>
    <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
    <p><?php echo htmlspecialchars($row['nama_lomba']); ?></p>
    <div class="prestasi-year"><i class="fas fa-calendar-check"></i> Tahun <?php echo $row['tahun']; ?></div>
  </div>
</div>
<?php endwhile; else: ?>
<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-trophy"></i><p>Belum ada data kejuaraan.</p></div>
<?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>
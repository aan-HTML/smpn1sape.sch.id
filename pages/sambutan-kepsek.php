<?php
require_once '../includes/config.php';
$kepsek = $conn->query("SELECT * FROM pimpinan WHERE jabatan='Kepala Sekolah' AND status='aktif' LIMIT 1")->fetch_assoc();
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Sambutan Kepala Sekolah - SMP Negeri 1 Sape</title>
<meta name="description" content="Sambutan resmi dari Kepala SMP Negeri 1 Sape.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Kepala Sekolah</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-user-tie" style="margin-right:10px;opacity:.85"></i>Kepala Sekolah</h1><p>Sambutan Kepala SMP Negeri 1 Sape</p></div></div>
<div class="page-body"><div class="container">
<?php if($kepsek): ?>
<div class="content-block fade-in">
  <div class="sambutan-layout">
    <div class="sambutan-photo-wrap">
      <div class="sambutan-photo"><img src="<?php echo $kepsek['foto']?'../assets/images/'.$kepsek['foto']:'../assets/images/kepsek-foto.jpg'; ?>" alt="Kepala Sekolah" loading="lazy"></div>
      <div class="sambutan-label"><strong><?php echo htmlspecialchars($kepsek['nama']); ?></strong><?php echo htmlspecialchars($kepsek['jabatan']); ?></div>
    </div>
    <div class="sambutan-text">
      <h2>Sambutan Kepala Sekolah</h2>
      <div class="quote-mark">&ldquo;</div>
      <?php foreach(explode("\n", trim($kepsek['sambutan']??'')) as $p): if(trim($p)): ?><p><?php echo htmlspecialchars(trim($p)); ?></p><?php endif; endforeach; ?>
    </div>
  </div>
</div>
<?php else: ?><div class="empty-state"><i class="fas fa-user"></i><p>Data kepala sekolah belum tersedia.</p></div><?php endif; ?>
</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

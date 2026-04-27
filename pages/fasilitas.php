<?php
require_once '../includes/config.php';
$res = $conn->query("SELECT * FROM fasilitas ORDER BY nama ASC");
$fasilitas=[]; if($res){while($r=$res->fetch_assoc()){$fasilitas[]=$r;}}
$ficons=['Perpustakaan'=>'fa-book','Laboratorium'=>'fa-flask','Aula'=>'fa-building','Lapangan'=>'fa-running','Kantin'=>'fa-utensils','Kelas'=>'fa-door-open','Musholla'=>'fa-mosque','UKS'=>'fa-clinic-medical','Toilet'=>'fa-restroom','Komputer'=>'fa-desktop'];
function getIcon($nama,$ficons){foreach($ficons as $k=>$v){if(stripos($nama,$k)!==false)return $v;}return 'fa-school';}
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Fasilitas - SMP Negeri 1 Sape</title>
<meta name="description" content="Sarana dan prasarana SMP Negeri 1 Sape penunjang kegiatan belajar mengajar.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Fasilitas</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-building" style="margin-right:10px;opacity:.85"></i>Fasilitas Sekolah</h1><p>Sarana dan prasarana penunjang kegiatan belajar mengajar</p></div></div>
<div class="page-body"><div class="container">
<?php if(!empty($fasilitas)): ?>
<div class="card-grid">
<?php foreach($fasilitas as $f): ?>
<div class="feature-card fade-in">
  <?php if(!empty($f['foto'])): ?>
  <div class="feature-card-thumb">
    <img src="../assets/images/fasilitas/<?php echo htmlspecialchars($f['foto']); ?>" alt="<?php echo htmlspecialchars($f['nama']); ?>" loading="lazy" width="400" height="225">
  </div>
  <?php else: ?>
  <div class="feature-card-icon">
    <i class="fas <?php echo getIcon($f['nama'],$ficons); ?>"></i>
  </div>
  <?php endif; ?>
  <div class="feature-card-body">
    <h4><?php echo htmlspecialchars($f['nama']); ?></h4>
    <?php if(!empty($f['deskripsi'])): ?><p><?php echo htmlspecialchars($f['deskripsi']); ?></p><?php endif; ?>
    <?php if(!empty($f['jumlah'])): ?>
    <span class="feature-tag"><i class="fas fa-door-open"></i> <?php echo (int)$f['jumlah']; ?> unit</span>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<div class="empty-state"><i class="fas fa-building"></i><p>Belum ada data fasilitas.</p></div>
<?php endif; ?>
</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

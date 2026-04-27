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
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Fasilitas</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-building" style="margin-right:10px;opacity:.8"></i>Fasilitas Sekolah</h1><p>Sarana dan prasarana penunjang kegiatan belajar mengajar</p></div></div>
<div style="padding:32px 0"><div class="container">
<?php if(!empty($fasilitas)): ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px">
<?php foreach($fasilitas as $f): ?>
<div style="background:var(--white);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:all .25s" onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,.12)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
  <?php if(!empty($f['foto'])): ?>
  <div style="width:100%;aspect-ratio:16/9;overflow:hidden;background:var(--gray-200)">
    <img src="../assets/images/fasilitas/<?php echo htmlspecialchars($f['foto']); ?>" alt="<?php echo htmlspecialchars($f['nama']); ?>" style="width:100%;height:100%;object-fit:cover" loading="lazy" width="400" height="225">
  </div>
  <?php else: ?>
  <div style="width:100%;height:90px;background:var(--primary-light);display:flex;align-items:center;justify-content:center"><i class="fas <?php echo getIcon($f['nama'],$ficons); ?>" style="font-size:36px;color:var(--primary)"></i></div>
  <?php endif; ?>
  <div style="padding:14px">
    <h4 style="font-size:15px;font-weight:700;margin-bottom:6px"><?php echo htmlspecialchars($f['nama']); ?></h4>
    <?php if(!empty($f['deskripsi'])): ?><p style="font-size:13px;color:var(--text-light);line-height:1.6;margin-bottom:8px"><?php echo htmlspecialchars($f['deskripsi']); ?></p><?php endif; ?>
    <?php if(!empty($f['jumlah'])): ?><span style="font-size:12px;background:var(--primary-light);color:var(--primary);padding:3px 10px;border-radius:20px;font-weight:600"><i class="fas fa-door-open" style="margin-right:4px"></i><?php echo $f['jumlah']; ?> unit</span><?php endif; ?>
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

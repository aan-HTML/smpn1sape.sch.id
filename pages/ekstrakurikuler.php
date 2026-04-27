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
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.ekskul-detail-hero{background:var(--primary-light);padding:40px 0 32px;border-bottom:1px solid var(--border);}
.ekskul-detail-hero .container{display:flex;align-items:center;gap:24px;}
.ekskul-detail-icon{width:80px;height:80px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ekskul-detail-icon i{font-size:36px;color:#fff;}
.ekskul-detail-info h1{font-size:26px;font-weight:800;color:var(--primary-dark);margin-bottom:8px;}
.ekskul-detail-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:var(--text-light);}
.ekskul-detail-meta span{display:flex;align-items:center;gap:6px;}
.ekskul-detail-meta i{color:var(--primary);}
.ekskul-detail-body{padding:36px 0;}
.ekskul-detail-desc{background:var(--white);border:1px solid var(--border);border-radius:10px;padding:24px;margin-bottom:32px;line-height:1.8;color:var(--text);}
.ekskul-detail-desc h3{font-size:16px;font-weight:700;color:var(--primary-dark);margin-bottom:10px;display:flex;align-items:center;gap:8px;}
.ekskul-foto-section h3{font-size:18px;font-weight:700;color:var(--primary-dark);margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.ekskul-foto-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;}
.ekskul-foto-item{border-radius:8px;overflow:hidden;aspect-ratio:4/3;background:var(--gray-100);position:relative;}
.ekskul-foto-item img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
.ekskul-foto-item:hover img{transform:scale(1.06);}
.ekskul-foto-caption{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.75),transparent);padding:28px 12px 12px;color:#fff;font-size:12.5px;font-weight:600;}
.ekskul-foto-caption .tgl{font-size:11px;font-weight:400;opacity:.85;margin-top:3px;display:flex;align-items:center;gap:5px;}
.ekskul-empty-foto{text-align:center;padding:48px;background:var(--gray-100);border-radius:10px;color:var(--text-light);}
.ekskul-empty-foto i{font-size:42px;margin-bottom:12px;opacity:.35;display:block;}
.back-btn{display:inline-flex;align-items:center;gap:8px;color:var(--primary);font-weight:600;font-size:14px;margin-bottom:24px;text-decoration:none;}
.back-btn:hover{text-decoration:underline;}
@media(max-width:600px){
  .ekskul-detail-hero .container{flex-direction:column;align-items:flex-start;gap:16px;}
  .ekskul-detail-info h1{font-size:20px;}
  .ekskul-foto-grid{grid-template-columns:repeat(2,1fr);}
}
</style>
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
    <div class="ekskul-detail-desc">
      <h3><i class="fas fa-info-circle" style="color:var(--primary)"></i> Tentang <?php echo htmlspecialchars($ekskul['nama']); ?></h3>
      <p><?php echo nl2br(htmlspecialchars($ekskul['deskripsi'])); ?></p>
    </div>
    <?php endif; ?>

    <div class="ekskul-foto-section">
      <h3><i class="fas fa-images" style="color:var(--primary)"></i> Foto Kegiatan</h3>
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
<div style="padding:64px 0;text-align:center;">
  <div class="container">
    <i class="fas fa-search" style="font-size:48px;color:var(--primary);opacity:.4;display:block;margin-bottom:16px;"></i>
    <h2 style="margin-bottom:8px;">Ekstrakurikuler tidak ditemukan</h2>
    <p style="color:var(--text-light);margin-bottom:24px;">Data yang Anda cari tidak tersedia.</p>
    <a href="ekstrakurikuler.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
  </div>
</div>

<?php else: ?>

<!-- HALAMAN DAFTAR -->
<?php
$res = $conn->query("SELECT e.*, GROUP_CONCAT(p.nama_pembina SEPARATOR ', ') as pembina_list FROM ekstrakurikuler e LEFT JOIN pembina_ekstrakurikuler p ON e.id=p.ekstrakurikuler_id WHERE e.status='aktif' GROUP BY e.id ORDER BY e.nama ASC");
?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span><span>Ekstrakurikuler</span>
</div></div></div>
<div class="page-hero"><div class="container">
  <h1><i class="fas fa-star" style="margin-right:10px;opacity:.8"></i>Ekstrakurikuler</h1>
  <p>Kegiatan pengembangan bakat dan minat siswa SMP Negeri 1 Sape</p>
</div></div>
<div style="padding:32px 0"><div class="container">
<?php if($res && $res->num_rows > 0): ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
<?php $i=0; while($row=$res->fetch_assoc()):
  $row_slug = urlencode(strtolower(str_replace(' ','-',$row['nama'])));
?>
<div style="background:var(--white);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:all .25s" onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,.12)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
  <div style="background:var(--primary-light);padding:28px;text-align:center;border-bottom:1px solid var(--border)">
    <i class="fas <?php echo $icons[$i%count($icons)]; ?>" style="font-size:40px;color:var(--primary)"></i>
  </div>
  <div style="padding:16px">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:8px;color:var(--text)"><?php echo htmlspecialchars($row['nama']); ?></h3>
    <p style="font-size:13px;color:var(--text-light);margin-bottom:12px;line-height:1.7"><?php echo htmlspecialchars(substr($row['deskripsi']??'',0,90)); ?></p>
    <div style="display:flex;flex-direction:column;gap:6px;font-size:12.5px;margin-bottom:16px">
      <div style="display:flex;align-items:center;gap:8px"><i class="fas fa-clock" style="color:var(--primary);width:16px"></i><span><?php echo htmlspecialchars($row['jadwal']); ?></span></div>
      <?php if(!empty($row['pembina_list'])): ?>
      <div style="display:flex;align-items:flex-start;gap:8px"><i class="fas fa-chalkboard-teacher" style="color:var(--primary);width:16px;margin-top:2px"></i><span><?php echo htmlspecialchars($row['pembina_list']); ?></span></div>
      <?php endif; ?>
    </div>
    <a href="ekstrakurikuler.php?slug=<?php echo $row_slug; ?>" style="display:inline-flex;align-items:center;gap:6px;background:var(--primary);color:#fff;padding:7px 16px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s" onmouseover="this.style.background='var(--primary-dark)'" onmouseout="this.style.background='var(--primary)'">
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
<?php require_once '../includes/config.php';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$per_page = 12;
$offset = ($page - 1) * $per_page;

$where = "";
if($q){ $q_safe=$conn->real_escape_string($q); $where=" WHERE g.judul LIKE '%$q_safe%'"; }

$total_res = $conn->query("SELECT COUNT(*) as total FROM galeri g$where");
$total = $total_res ? $total_res->fetch_assoc()['total'] : 0;
$total_pages = ceil($total / $per_page);

$res = $conn->query("SELECT g.*, e.nama as ekskul_nama FROM galeri g LEFT JOIN ekstrakurikuler e ON g.ekstrakurikuler_id=e.id$where ORDER BY g.tanggal DESC, g.id DESC LIMIT $per_page OFFSET $offset");
$rows = []; if($res){ while($r=$res->fetch_assoc()){ $rows[]=$r; } }
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Postingan - SMP Negeri 1 Sape</title>
<meta name="description" content="Galeri foto dan dokumentasi kegiatan akademik dan non-akademik SMP Negeri 1 Sape.">
<link rel="canonical" href="http<?php echo isset($_SERVER['HTTPS'])?'s':''; ?>://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span><span>Postingan</span>
</div></div></div>
<div class="page-hero"><div class="container">
  <h1><i class="fas fa-images" style="margin-right:10px;opacity:.85"></i>Postingan</h1>
  <p>Foto dan dokumentasi kegiatan SMP Negeri 1 Sape</p>
</div></div>

<div class="page-body"><div class="container">

<!-- Search -->
<form action="" method="GET" class="search-form" role="search">
  <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari foto kegiatan..." aria-label="Cari foto">
  <button type="submit"><i class="fas fa-search"></i> Cari</button>
</form>
<?php if($q): ?>
<p class="search-meta">Hasil pencarian: <strong>&ldquo;<?php echo htmlspecialchars($q); ?>&rdquo;</strong> &mdash; <?php echo $total; ?> foto ditemukan <?php if($total>0): ?><a href="postingan.php"><i class="fas fa-times-circle"></i> Reset</a><?php endif; ?></p>
<?php endif; ?>

<?php if(!empty($rows)): ?>
<div class="galeri-page-grid">
  <?php foreach($rows as $r): ?>
  <div class="galeri-page-item fade-in">
    <div class="galeri-page-thumb">
      <img src="../assets/images/<?php echo htmlspecialchars($r['foto']); ?>" alt="<?php echo htmlspecialchars($r['judul']); ?>" loading="lazy" width="400" height="300">
    </div>
    <div class="galeri-page-info">
      <div class="judul"><?php echo htmlspecialchars($r['judul']); ?></div>
      <div class="meta">
        <?php if(!empty($r['tanggal'])): ?>
        <span><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($r['tanggal'])); ?></span>
        <?php endif; ?>
        <?php if(!empty($r['ekskul_nama'])): ?>
        <span><i class="fas fa-star"></i> <?php echo htmlspecialchars($r['ekskul_nama']); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if($total_pages > 1): ?>
<nav class="pagination" aria-label="Navigasi halaman">
  <?php if($page>1): ?><a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$page-1])); ?>" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
  <?php for($i=max(1,$page-2);$i<=min($total_pages,$page+2);$i++): ?>
    <a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$i])); ?>"<?php echo $i==$page?' class="active"':''; ?>><?php echo $i; ?></a>
  <?php endfor; ?>
  <?php if($page<$total_pages): ?><a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$page+1])); ?>" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></a><?php endif; ?>
</nav>
<?php endif; ?>

<?php else: ?>
<div class="empty-state"><i class="fas fa-images"></i><p><?php echo $q ? 'Tidak ada foto yang cocok.' : 'Belum ada foto kegiatan.'; ?></p></div>
<?php endif; ?>

</div></div>

<?php include '../includes/footer.php'; ?>
</body></html>

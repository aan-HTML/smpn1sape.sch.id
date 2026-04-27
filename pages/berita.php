<?php
require_once '../includes/config.php';
$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$per = 9; $offset = ($page-1)*$per;

$where = "WHERE status='published'";
if($q){ $q_safe=$conn->real_escape_string($q); $where.=" AND (judul LIKE '%$q_safe%' OR konten LIKE '%$q_safe%')"; }

$total = $conn->query("SELECT COUNT(*) as t FROM berita $where")->fetch_assoc()['t'];
$total_pages = ceil($total/$per);
$res = $conn->query("SELECT * FROM berita $where ORDER BY created_at DESC LIMIT $per OFFSET $offset");
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Berita - SMP Negeri 1 Sape</title>
<meta name="description" content="Berita terkini dan informasi terbaru seputar kegiatan akademik dan non-akademik SMP Negeri 1 Sape.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Berita</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-newspaper" style="margin-right:10px;opacity:.85"></i>Berita Terkini</h1><p>Informasi dan kabar terbaru dari SMP Negeri 1 Sape</p></div></div>
<div class="page-body"><div class="container">

<!-- Search -->
<form action="" method="GET" class="search-form" role="search">
  <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari berita..." aria-label="Cari berita">
  <button type="submit"><i class="fas fa-search"></i> Cari</button>
</form>
<?php if($q): ?>
<p class="search-meta">Hasil pencarian: <strong>&ldquo;<?php echo htmlspecialchars($q); ?>&rdquo;</strong> &mdash; <?php echo $total; ?> berita ditemukan <?php if($total>0): ?><a href="berita.php"><i class="fas fa-times-circle"></i> Reset</a><?php endif; ?></p>
<?php endif; ?>

<div class="berita-grid">
<?php if($res&&$res->num_rows>0): while($row=$res->fetch_assoc()):
  $url_b = !empty($row['slug']) ? '../berita/'.$row['slug'] : 'berita-detail.php?id='.$row['id'];
  $kat = !empty($row['kategori']) ? htmlspecialchars($row['kategori']) : 'Berita';
?>
<div class="berita-card fade-in">
  <a href="<?php echo $url_b; ?>" class="berita-card-thumb">
    <img src="<?php echo get_thumb($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>" loading="lazy" decoding="async" width="400" height="225">
    <span class="berita-card-badge"><?php echo $kat; ?></span>
  </a>
  <div class="berita-card-body">
    <h3><a href="<?php echo $url_b; ?>"><?php echo htmlspecialchars($row['judul']); ?></a></h3>
    <p><?php echo get_excerpt($row['konten'], 110); ?></p>
    <div class="berita-card-footer">
      <span class="berita-card-date"><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y',strtotime($row['created_at'])); ?></span>
      <a href="<?php echo $url_b; ?>" class="berita-read-btn">Baca <i class="fas fa-arrow-right"></i></a>
    </div>
  </div>
</div>
<?php endwhile; else: ?>
<div class="empty-state full-row"><i class="fas fa-newspaper"></i><p><?php echo $q ? 'Tidak ada berita yang cocok.' : 'Belum ada berita.'; ?></p></div>
<?php endif; ?>
</div>

<?php if($total_pages>1): ?>
<nav class="pagination" aria-label="Navigasi halaman">
  <?php if($page>1): ?><a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$page-1])); ?>" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></a><?php endif; ?>
  <?php for($i=max(1,$page-2);$i<=min($total_pages,$page+2);$i++): ?>
    <a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$i])); ?>"<?php echo $i==$page?' class="active"':''; ?>><?php echo $i; ?></a>
  <?php endfor; ?>
  <?php if($page<$total_pages): ?><a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$page+1])); ?>" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></a><?php endif; ?>
</nav>
<?php endif; ?>

</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

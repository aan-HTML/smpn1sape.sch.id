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
<link rel="canonical" href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.galeri-page-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;}
.galeri-page-item{border-radius:10px;overflow:hidden;background:var(--white);border:1px solid var(--border);transition:all .25s;}
.galeri-page-item:hover{box-shadow:0 6px 20px rgba(0,0,0,.12);transform:translateY(-3px);}
.galeri-page-thumb{width:100%;aspect-ratio:4/3;overflow:hidden;background:var(--gray-100);}
.galeri-page-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
.galeri-page-item:hover .galeri-page-thumb img{transform:scale(1.06);}
.galeri-page-info{padding:12px 14px;}
.galeri-page-info .judul{font-size:14px;font-weight:700;color:var(--text);margin-bottom:5px;line-height:1.4;}
.galeri-page-info .meta{font-size:12px;color:var(--text-light);display:flex;flex-wrap:wrap;gap:10px;}
.galeri-page-info .meta span{display:flex;align-items:center;gap:5px;}
.galeri-page-info .meta i{color:var(--primary);}
</style>
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span><span>Postingan</span>
</div></div></div>
<div class="page-hero"><div class="container">
  <h1><i class="fas fa-images" style="margin-right:10px;opacity:.8"></i>Postingan</h1>
  <p>Foto dan dokumentasi kegiatan SMP Negeri 1 Sape</p>
</div></div>

<div style="padding:28px 0"><div class="container">

<!-- Search -->
<form action="" method="GET" style="display:flex;max-width:480px;margin-bottom:28px;border:1.5px solid var(--border);border-radius:8px;overflow:hidden;background:var(--white)">
  <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari foto kegiatan..." style="flex:1;padding:11px 16px;border:none;font-size:14px;outline:none;font-family:inherit;color:var(--text);background:transparent">
  <button type="submit" style="padding:11px 18px;background:var(--primary);color:#fff;border:none;cursor:pointer;font-size:14px"><i class="fas fa-search"></i></button>
</form>
<?php if($q): ?>
<p style="font-size:13px;color:var(--text-light);margin-bottom:16px">Hasil pencarian: <strong>"<?php echo htmlspecialchars($q); ?>"</strong> — <?php echo $total; ?> foto ditemukan <?php if($total>0): ?><a href="postingan.php" style="color:var(--primary);margin-left:8px"><i class="fas fa-times-circle"></i> Reset</a><?php endif; ?></p>
<?php endif; ?>
  <?php if(!empty($rows)): ?>
  <div class="galeri-page-grid">
    <?php foreach($rows as $r): ?>
    <div class="galeri-page-item">
      <div class="galeri-page-thumb">
        <img src="../assets/images/<?php echo htmlspecialchars($r['foto']); ?>" alt="<?php echo htmlspecialchars($r['judul']); ?>" loading="lazy">
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
  <div style="display:flex;justify-content:center;gap:8px;margin-top:32px;flex-wrap:wrap">
    <?php if($page>1): ?>
    <a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$page-1])); ?>" style="padding:8px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:13px;color:var(--text)"><i class="fas fa-chevron-left"></i></a>
    <?php endif; ?>
    <?php for($i=max(1,$page-2);$i<=min($total_pages,$page+2);$i++): ?>
    <a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$i])); ?>" style="padding:8px 14px;border:1.5px solid <?php echo $i==$page?'var(--primary)':'var(--border)'; ?>;border-radius:6px;font-size:13px;background:<?php echo $i==$page?'var(--primary)':'var(--white)'; ?>;color:<?php echo $i==$page?'#fff':'var(--text)'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
    <?php if($page<$total_pages): ?>
    <a href="?<?php echo http_build_query(array_merge($_GET,['page'=>$page+1])); ?>" style="padding:8px 14px;border:1.5px solid var(--border);border-radius:6px;font-size:13px;color:var(--text)"><i class="fas fa-chevron-right"></i></a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <?php else: ?>
  <div class="empty-state"><i class="fas fa-images"></i><p>Belum ada foto kegiatan.</p></div>
  <?php endif; ?>
</div></div>

<?php include '../includes/footer.php'; ?>
<button class="scroll-top" id="scrollTop" aria-label="Scroll ke atas"><i class="fas fa-chevron-up"></i></button>
<script>
const scrollTopBtn=document.getElementById('scrollTop');
if(scrollTopBtn){
  window.addEventListener('scroll',()=>scrollTopBtn.classList.toggle('show',window.scrollY>300));
  scrollTopBtn.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
}
</script>
</body></html>
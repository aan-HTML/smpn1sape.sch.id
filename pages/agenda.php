<?php
require_once '../includes/config.php';
$filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = $filter ? "WHERE status='".$conn->real_escape_string($filter)."'" : '';
$res = $conn->query("SELECT * FROM agenda $where ORDER BY tanggal_mulai DESC");
$status_list = ['akan_datang'=>'Akan Datang','berlangsung'=>'Berlangsung','selesai'=>'Selesai'];
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Agenda - SMP Negeri 1 Sape</title>
<meta name="description" content="Jadwal agenda kegiatan akademik dan non-akademik yang akan berlangsung di SMP Negeri 1 Sape.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Agenda</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-calendar-check" style="margin-right:10px;opacity:.85"></i>Agenda Sekolah</h1><p>Jadwal kegiatan dan agenda SMP Negeri 1 Sape</p></div></div>
<div class="page-body"><div class="container">

<!-- Filter -->
<div class="filter-group">
  <span class="filter-label">Filter Status</span>
  <div class="filter-pills">
    <a href="agenda.php" class="filter-pill <?php echo !$filter?'active':''; ?>">Semua</a>
    <?php foreach($status_list as $k=>$v): ?>
    <a href="agenda.php?status=<?php echo $k; ?>" class="filter-pill <?php echo $filter==$k?'active':''; ?>"><?php echo $v; ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div class="agenda-list">
<?php if($res&&$res->num_rows>0): while($row=$res->fetch_assoc()):
  $sc=['akan_datang'=>['Akan Datang','agenda-status-upcoming'],'berlangsung'=>['Berlangsung','agenda-status-live'],'selesai'=>['Selesai','agenda-status-done']];
  $s=$sc[$row['status']]??$sc['selesai'];
?>
<div class="agenda-item fade-in">
  <div class="agenda-date">
    <div class="day"><?php echo date('d',strtotime($row['tanggal_mulai'])); ?></div>
    <div class="month"><?php echo date('M',strtotime($row['tanggal_mulai'])); ?></div>
  </div>
  <div class="agenda-body">
    <div class="agenda-head">
      <h4><?php echo htmlspecialchars($row['judul']); ?></h4>
      <span class="agenda-status <?php echo $s[1]; ?>"><?php echo $s[0]; ?></span>
    </div>
    <?php if(!empty($row['deskripsi'])): ?><p><?php echo htmlspecialchars($row['deskripsi']); ?></p><?php endif; ?>
    <div class="agenda-footer">
      <?php if(!empty($row['tempat'])): ?><span class="agenda-place"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['tempat']); ?></span><?php endif; ?>
      <?php if(!empty($row['tanggal_selesai']) && $row['tanggal_selesai']!=$row['tanggal_mulai']): ?>
      <span class="agenda-place"><i class="fas fa-calendar"></i> s/d <?php echo date('d M Y',strtotime($row['tanggal_selesai'])); ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endwhile; else: ?>
<div class="empty-state"><i class="fas fa-calendar"></i><p>Belum ada agenda.</p></div>
<?php endif; ?>
</div>

</div></div>
<?php include '../includes/footer.php'; ?>
<style>
.agenda-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:6px;}
.agenda-status{
  font-size:11px;font-weight:700;padding:4px 12px;border-radius:var(--radius-pill);
  white-space:nowrap;letter-spacing:.04em;text-transform:uppercase;
}
.agenda-status-upcoming{background:var(--primary-light);color:var(--primary);}
.agenda-status-live{background:#D5F5E3;color:#1E8449;}
.agenda-status-done{background:var(--gray-100);color:var(--text-muted);}
.agenda-footer{display:flex;flex-wrap:wrap;gap:14px;margin-top:8px;}
</style>
</body></html>

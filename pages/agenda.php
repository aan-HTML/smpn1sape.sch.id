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
<div class="page-hero"><div class="container"><h1><i class="fas fa-calendar-check" style="margin-right:10px;opacity:.8"></i>Agenda Sekolah</h1><p>Jadwal kegiatan dan agenda SMP Negeri 1 Sape</p></div></div>
<div style="padding:32px 0"><div class="container">
<!-- Filter -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px">
<a href="agenda.php" style="padding:7px 16px;border:1.5px solid <?php echo !$filter?'var(--primary)':'var(--border)'; ?>;border-radius:20px;font-size:13px;font-weight:600;background:<?php echo !$filter?'var(--primary)':'var(--white)'; ?>;color:<?php echo !$filter?'#fff':'var(--text)'; ?>">Semua</a>
<?php foreach($status_list as $k=>$v): ?>
<a href="agenda.php?status=<?php echo $k; ?>" style="padding:7px 16px;border:1.5px solid <?php echo $filter==$k?'var(--primary)':'var(--border)'; ?>;border-radius:20px;font-size:13px;font-weight:600;background:<?php echo $filter==$k?'var(--primary)':'var(--white)'; ?>;color:<?php echo $filter==$k?'#fff':'var(--text)'; ?>"><?php echo $v; ?></a>
<?php endforeach; ?>
</div>
<div class="agenda-list" style="border:1px solid var(--border);border-radius:8px;overflow:hidden;padding:0">
<?php if($res&&$res->num_rows>0): $first=true; while($row=$res->fetch_assoc()): ?>
<div class="agenda-item" style="padding:20px;<?php echo !$first?'border-top:1px solid var(--border)':''; ?>">
  <div class="agenda-date">
    <div class="day"><?php echo date('d',strtotime($row['tanggal_mulai'])); ?></div>
    <div class="month"><?php echo date('M',strtotime($row['tanggal_mulai'])); ?></div>
  </div>
  <div class="agenda-body" style="flex:1">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
      <h4 style="margin-bottom:4px"><?php echo htmlspecialchars($row['judul']); ?></h4>
      <?php $sc=['akan_datang'=>['var(--primary-light)','var(--primary)','Akan Datang'],'berlangsung'=>['#d5f5e3','#1e8449','Berlangsung'],'selesai'=>['var(--gray-100)','var(--gray-600)','Selesai']]; $s=$sc[$row['status']]??$sc['selesai']; ?>
      <span style="background:<?php echo $s[0]; ?>;color:<?php echo $s[1]; ?>;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;white-space:nowrap"><?php echo $s[2]; ?></span>
    </div>
    <?php if(!empty($row['deskripsi'])): ?><p style="font-size:13px;color:var(--text-light);margin-bottom:6px"><?php echo htmlspecialchars($row['deskripsi']); ?></p><?php endif; ?>
    <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:12px;color:var(--text-light)">
      <?php if(!empty($row['tempat'])): ?><span><i class="fas fa-map-marker-alt" style="color:var(--primary);margin-right:4px"></i><?php echo htmlspecialchars($row['tempat']); ?></span><?php endif; ?>
      <?php if(!empty($row['tanggal_selesai']) && $row['tanggal_selesai']!=$row['tanggal_mulai']): ?>
      <span><i class="fas fa-calendar" style="color:var(--primary);margin-right:4px"></i>s/d <?php echo date('d M Y',strtotime($row['tanggal_selesai'])); ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php $first=false; endwhile; else: ?>
<div class="empty-state" style="padding:40px 20px"><i class="fas fa-calendar"></i><p>Belum ada agenda.</p></div>
<?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

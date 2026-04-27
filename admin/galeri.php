<?php require_once 'auth.php';
$res = $conn->query("SELECT g.*, e.nama as ekskul_nama FROM galeri g LEFT JOIN ekstrakurikuler e ON g.ekstrakurikuler_id=e.id ORDER BY g.tanggal DESC, g.id DESC");
$rows = []; if($res){ while($r=$res->fetch_assoc()){ $rows[]=$r; } }
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Galeri - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head><body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
  <div class="page-header">
    <h1><i class="fas fa-images"></i> Galeri Kegiatan</h1>
    <a href="galeri-tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Foto</a>
  </div>
  <?php show_alert(); ?>
  <?php if(!empty($rows)): ?>
  <div class="card">
    <div class="card-body" style="padding:0">
      <table class="data-table" style="width:100%;border-collapse:collapse">
        <thead><tr>
          <th style="padding:12px 16px;text-align:left;border-bottom:1px solid var(--border-color)">Foto</th>
          <th style="padding:12px 16px;text-align:left;border-bottom:1px solid var(--border-color)">Judul</th>
          <th style="padding:12px 16px;text-align:left;border-bottom:1px solid var(--border-color)">Ekskul</th>
          <th style="padding:12px 16px;text-align:left;border-bottom:1px solid var(--border-color)">Tanggal</th>
          <th style="padding:12px 16px;text-align:center;border-bottom:1px solid var(--border-color)">Aksi</th>
        </tr></thead>
        <tbody>
        <?php foreach($rows as $r): ?>
        <tr style="border-bottom:1px solid var(--border-color)">
          <td style="padding:10px 16px"><img src="../assets/images/<?php echo htmlspecialchars($r['foto']); ?>" style="width:70px;height:50px;object-fit:cover;border-radius:4px"></td>
          <td style="padding:10px 16px;font-weight:600"><?php echo htmlspecialchars($r['judul']); ?></td>
          <td style="padding:10px 16px;color:#666"><?php echo $r['ekskul_nama'] ? htmlspecialchars($r['ekskul_nama']) : '<span style="color:#aaa">—</span>'; ?></td>
          <td style="padding:10px 16px;color:#666"><?php echo $r['tanggal'] ? date('d M Y', strtotime($r['tanggal'])) : '—'; ?></td>
          <td style="padding:10px 16px;text-align:center">
            <a href="galeri-edit.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
            <a href="galeri-hapus.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto ini?')"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php else: ?>
  <div class="empty-state"><i class="fas fa-images"></i><p>Belum ada foto galeri.</p><a href="galeri-tambah.php" class="btn btn-primary">Tambah Foto Pertama</a></div>
  <?php endif; ?>
</div>
</div>
</div>
<script>
const sidebar=document.getElementById("sidebar"),overlay=document.getElementById("overlay"),toggle=document.getElementById("sidebarToggle");
function toggleMenu(){sidebar.classList.toggle("active");overlay.classList.toggle("active");}
if(toggle)toggle.addEventListener("click",toggleMenu);
if(overlay)overlay.addEventListener("click",toggleMenu);
</script>
</body></html>
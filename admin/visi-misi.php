<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $visi    = trim($_POST['visi']);
    $misi    = trim($_POST['misi']);
    $tujuan  = trim($_POST['tujuan']);
    $stmt = $conn->prepare("UPDATE profil_sekolah SET visi=?, misi=?, tujuan=? WHERE id=1");
    $stmt->bind_param("sss", $visi, $misi, $tujuan);
    if ($stmt->execute()) {
        alert('Visi, Misi & Tujuan berhasil disimpan!', 'success');
    } else {
        alert('Gagal menyimpan!', 'error');
    }
    redirect('visi-misi.php');
}

$profil = $conn->query("SELECT visi,misi,tujuan FROM profil_sekolah WHERE id=1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Visi Misi - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
    <div class="page-title"><span><i class="fas fa-bullseye"></i> Visi, Misi & Tujuan Sekolah</span></div>
    <?php show_alert(); ?>
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-eye" style="color:var(--primary)"></i> Visi Sekolah <span style="color:#e74c3c">*</span></label>
                    <textarea name="visi" class="form-control" rows="3" required><?php echo htmlspecialchars($profil['visi']??''); ?></textarea>
                    <small style="color:var(--text-light)">Tampil di halaman Visi & Misi.</small>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-list-ul" style="color:var(--primary)"></i> Misi Sekolah <span style="color:#e74c3c">*</span></label>
                    <textarea name="misi" class="form-control" rows="8" required><?php echo htmlspecialchars($profil['misi']??''); ?></textarea>
                    <small style="color:var(--text-light)">Tulis setiap poin misi di baris baru (Enter untuk baris baru).</small>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-flag" style="color:var(--primary)"></i> Tujuan Sekolah</label>
                    <textarea name="tujuan" class="form-control" rows="5"><?php echo htmlspecialchars($profil['tujuan']??''); ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="../pages/visi-misi.php" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i> Lihat di Website</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
const sidebar=document.getElementById('sidebar'),overlay=document.getElementById('overlay'),toggle=document.getElementById('sidebarToggle');
function toggleMenu(){sidebar.classList.toggle('active');overlay.classList.toggle('active');}
if(toggle)toggle.addEventListener('click',toggleMenu);
if(overlay)overlay.addEventListener('click',toggleMenu);
</script>
</body></html>

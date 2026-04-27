<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrf_verify();
    $tahun_ajaran  = clean_input($_POST['tahun_ajaran'] ?? '');
    // BUG FIX: status & tanggal divalidasi whitelist / format, bukan langsung ke query
    $status        = in_array($_POST['status'] ?? '', ['buka', 'tutup']) ? $_POST['status'] : 'tutup';
    $tanggal_buka  = $conn->real_escape_string($_POST['tanggal_buka'] ?? '');
    $tanggal_tutup = $conn->real_escape_string($_POST['tanggal_tutup'] ?? '');
    $kuota         = (int)($_POST['kuota'] ?? 0);
    // BUG FIX: informasi disimpan dengan real_escape_string
    $informasi     = $conn->real_escape_string($_POST['informasi'] ?? '');

    if ($conn->query("UPDATE ppdb_setting SET tahun_ajaran='$tahun_ajaran', status='$status', tanggal_buka='$tanggal_buka', tanggal_tutup='$tanggal_tutup', kuota=$kuota, informasi='$informasi' WHERE id=1")) {
        alert('Pengaturan PPDB berhasil diupdate!', 'success');
    } else {
        alert('Gagal menyimpan pengaturan!', 'danger');
    }
}

$setting = $conn->query("SELECT * FROM ppdb_setting WHERE id=1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Pengaturan PPDB - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include 'includes/header.php'; ?>
        <div class="content-wrapper">
            <div class="page-title">
                <span><i class="fas fa-cog"></i> Pengaturan PPDB</span>
            </div>
            <?php show_alert(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group"><label>Tahun Ajaran</label><input type="text" name="tahun_ajaran" class="form-control" value="<?php echo htmlspecialchars($setting['tahun_ajaran'] ?? ''); ?>" required placeholder="2026/2027"></div>
                        <div class="form-group">
                            <label>Status Pendaftaran</label>
                            <select name="status" class="form-control" required>
                                <option value="buka" <?php echo ($setting['status']??'')==='buka'?'selected':''; ?>>Buka</option>
                                <option value="tutup" <?php echo ($setting['status']??'')==='tutup'?'selected':''; ?>>Tutup</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Tanggal Buka</label><input type="date" name="tanggal_buka" class="form-control" value="<?php echo htmlspecialchars($setting['tanggal_buka'] ?? ''); ?>" required></div>
                        <div class="form-group"><label>Tanggal Tutup</label><input type="date" name="tanggal_tutup" class="form-control" value="<?php echo htmlspecialchars($setting['tanggal_tutup'] ?? ''); ?>" required></div>
                        <div class="form-group"><label>Kuota Penerimaan</label><input type="number" name="kuota" class="form-control" value="<?php echo (int)($setting['kuota'] ?? 0); ?>" required min="0"></div>
                        <div class="form-group"><label>Informasi Pendaftaran</label><textarea name="informasi" class="form-control" rows="5"><?php echo htmlspecialchars($setting['informasi'] ?? ''); ?></textarea></div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
                    </form>
                </div>
            </div>
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

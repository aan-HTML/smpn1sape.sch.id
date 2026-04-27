<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $keys = ['jumlah_siswa','jumlah_guru','jumlah_prestasi','jumlah_ruang','facebook','instagram','youtube'];
    foreach ($keys as $k) {
        $v = $conn->real_escape_string(trim($_POST[$k] ?? ''));
        $conn->query("UPDATE pengaturan SET nilai='$v' WHERE nama_key='$k'");
    }
    alert('Pengaturan berhasil disimpan!', 'success');
    redirect('statistik.php');
}

$res = $conn->query("SELECT * FROM pengaturan");
$s = [];
while($r = $res->fetch_assoc()) $s[$r['nama_key']] = $r['nilai'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Statistik & Sosmed - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
    <div class="page-title"><span><i class="fas fa-chart-bar"></i> Statistik & Media Sosial</span></div>
    <?php show_alert(); ?>
    <form method="POST">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        <!-- Statistik -->
        <div class="card">
            <div class="card-header"><h4><i class="fas fa-chart-pie"></i> Angka Statistik Homepage</h4></div>
            <div class="card-body">
                <p style="color:var(--text-light);font-size:13px;margin-bottom:20px">Angka ini tampil di bagian counter/statistik di halaman utama website.</p>
                <div class="form-group">
                    <label><i class="fas fa-user-graduate" style="color:var(--primary)"></i> Jumlah Siswa Aktif</label>
                    <input type="number" name="jumlah_siswa" class="form-control" value="<?php echo $s['jumlah_siswa']??''; ?>" placeholder="Contoh: 1075">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chalkboard-teacher" style="color:var(--primary)"></i> Jumlah Tenaga Pendidik</label>
                    <input type="number" name="jumlah_guru" class="form-control" value="<?php echo $s['jumlah_guru']??''; ?>" placeholder="Contoh: 85">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-trophy" style="color:var(--primary)"></i> Jumlah Prestasi</label>
                    <input type="number" name="jumlah_prestasi" class="form-control" value="<?php echo $s['jumlah_prestasi']??''; ?>" placeholder="Contoh: 50">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-door-open" style="color:var(--primary)"></i> Jumlah Ruang Kelas</label>
                    <input type="number" name="jumlah_ruang" class="form-control" value="<?php echo $s['jumlah_ruang']??''; ?>" placeholder="Contoh: 30">
                </div>
            </div>
        </div>

        <!-- Sosmed -->
        <div class="card">
            <div class="card-header"><h4><i class="fas fa-share-alt"></i> Link Media Sosial</h4></div>
            <div class="card-body">
                <p style="color:var(--text-light);font-size:13px;margin-bottom:20px">Link ini tampil di footer website dan menu mobile.</p>
                <div class="form-group">
                    <label><i class="fab fa-facebook-f" style="color:#1877f2"></i> Link Facebook</label>
                    <input type="url" name="facebook" class="form-control" value="<?php echo htmlspecialchars($s['facebook']??''); ?>" placeholder="https://facebook.com/...">
                </div>
                <div class="form-group">
                    <label><i class="fab fa-instagram" style="color:#e1306c"></i> Link Instagram</label>
                    <input type="url" name="instagram" class="form-control" value="<?php echo htmlspecialchars($s['instagram']??''); ?>" placeholder="https://instagram.com/...">
                </div>
                <div class="form-group">
                    <label><i class="fab fa-youtube" style="color:#ff0000"></i> Link YouTube</label>
                    <input type="url" name="youtube" class="form-control" value="<?php echo htmlspecialchars($s['youtube']??''); ?>" placeholder="https://youtube.com/@...">
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top:8px">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Semua Perubahan</button>
    </div>
    </form>
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

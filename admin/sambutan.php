<?php
require_once 'auth.php';

$kepsek = $conn->query("SELECT * FROM pimpinan WHERE jabatan='Kepala Sekolah' AND status='aktif' ORDER BY urutan ASC LIMIT 1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $kepsek) {
    csrf_verify();
    $id       = (int)$kepsek['id'];
    $sambutan = $conn->real_escape_string($_POST['sambutan'] ?? '');

    $foto_query = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload = upload_file($_FILES['foto'], 'pimpinan/');
        if ($upload['success']) {
            $fn = $conn->real_escape_string($upload['filename']);
            $foto_query = ", foto='$fn'";
        } else {
            alert($upload['message'], 'danger');
            redirect('sambutan.php');
        }
    }

    if ($conn->query("UPDATE pimpinan SET sambutan='$sambutan' $foto_query WHERE id=$id")) {
        alert('Sambutan kepala sekolah berhasil diperbarui!', 'success');
    } else {
        alert('Gagal menyimpan!', 'danger');
    }
    redirect('sambutan.php');
}

$kepsek = $conn->query("SELECT * FROM pimpinan WHERE jabatan='Kepala Sekolah' AND status='aktif' LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Sambutan Kepala Sekolah - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Layout grid: foto di kiri, form di kanan. Di mobile tumpuk ke bawah */
.sambutan-grid {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 20px;
    align-items: start;
}
@media (max-width: 768px) {
    .sambutan-grid {
        grid-template-columns: 1fr;
    }
    .sambutan-foto-card {
        display: flex;
        align-items: center;
        gap: 16px;
        text-align: left !important;
        padding: 16px !important;
    }
    .sambutan-foto-card img {
        width: 80px !important;
        height: 100px !important;
        flex-shrink: 0;
    }
    .sambutan-foto-card .info-text {
        flex: 1;
    }
}
</style>
</head>
<body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
    <div class="page-title"><span><i class="fas fa-user-tie"></i> Sambutan Kepala Sekolah</span></div>
    <?php show_alert(); ?>

    <?php if (!$kepsek): ?>
    <div class="card"><div class="card-body" style="text-align:center;padding:40px;color:#666">
        <i class="fas fa-user-slash" style="font-size:48px;margin-bottom:16px;display:block;opacity:.3"></i>
        <p>Belum ada data Kepala Sekolah. Tambahkan terlebih dahulu di tabel pimpinan.</p>
    </div></div>
    <?php else: ?>

    <div class="sambutan-grid">
        <!-- Foto & Info Kepsek -->
        <div class="card">
            <div class="card-body sambutan-foto-card" style="text-align:center;padding:24px">
                <img src="../assets/images/<?php echo htmlspecialchars($kepsek['foto'] ?: 'logo-sekolah.png'); ?>"
                     alt="Foto Kepala Sekolah"
                     style="width:120px;height:150px;object-fit:cover;object-position:top;border-radius:10px;margin-bottom:12px;border:3px solid var(--border)">
                <div class="info-text">
                    <h4 style="font-size:15px;margin-bottom:4px"><?php echo htmlspecialchars($kepsek['nama']); ?></h4>
                    <p style="color:var(--text-light);font-size:13px;margin-bottom:4px">Kepala Sekolah</p>
                    <?php if($kepsek['nip']): ?>
                    <p style="color:var(--text-light);font-size:12px">NIP: <?php echo htmlspecialchars($kepsek['nip']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="card">
            <div class="card-header"><h4><i class="fas fa-edit"></i> Edit Sambutan</h4></div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label>Ganti Foto <span style="color:var(--text-light);font-weight:400">(opsional, biarkan kosong jika tidak ingin ganti)</span></label>
                        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <div class="form-group">
                        <label>Teks Sambutan <span style="color:#e74c3c">*</span></label>
                        <textarea name="sambutan" class="form-control" rows="10" required
                            placeholder="Tulis sambutan kepala sekolah di sini..."><?php echo htmlspecialchars($kepsek['sambutan'] ?? ''); ?></textarea>
                        <small style="color:var(--text-light)">Teks ini akan tampil di bagian "Sambutan Kepala Sekolah" di halaman utama website.</small>
                    </div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                        <a href="../pages/sambutan-kepsek.php" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i> Lihat di Website</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
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

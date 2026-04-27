<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = ['nama_sekolah','npsn','nss','akreditasi','status_sekolah','alamat','kecamatan','kabupaten','provinsi','kode_pos','telepon','email','website','sejarah'];
    $sets = [];
    foreach ($fields as $f) {
        $v = $conn->real_escape_string(trim($_POST[$f] ?? ''));
        $sets[] = "$f='$v'";
    }
    if ($conn->query("UPDATE profil_sekolah SET " . implode(',', $sets) . " WHERE id=1")) {
        alert('Profil sekolah berhasil disimpan!', 'success');
    } else {
        alert('Gagal menyimpan!', 'error');
    }
    redirect('profil-sekolah.php');
}

$profil = $conn->query("SELECT * FROM profil_sekolah WHERE id=1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Profil Sekolah - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
    <div class="page-title"><span><i class="fas fa-school"></i> Profil Sekolah</span></div>
    <?php show_alert(); ?>
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <h3 style="margin-bottom:20px;color:var(--primary);font-size:15px;border-bottom:2px solid var(--border);padding-bottom:10px"><i class="fas fa-id-card"></i> Identitas Sekolah</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label>Nama Sekolah *</label>
                        <input type="text" name="nama_sekolah" class="form-control" value="<?php echo htmlspecialchars($profil['nama_sekolah']??''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Status Sekolah</label>
                        <select name="status_sekolah" class="form-control">
                            <option value="Negeri" <?php echo ($profil['status_sekolah']??'')==='Negeri'?'selected':''; ?>>Negeri</option>
                            <option value="Swasta" <?php echo ($profil['status_sekolah']??'')==='Swasta'?'selected':''; ?>>Swasta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>NPSN</label>
                        <input type="text" name="npsn" class="form-control" value="<?php echo htmlspecialchars($profil['npsn']??''); ?>">
                    </div>
                    <div class="form-group">
                        <label>NSS</label>
                        <input type="text" name="nss" class="form-control" value="<?php echo htmlspecialchars($profil['nss']??''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Akreditasi</label>
                        <select name="akreditasi" class="form-control">
                            <option value="">- Pilih -</option>
                            <?php foreach(['A','B','C'] as $ak): ?>
                            <option value="<?php echo $ak; ?>" <?php echo ($profil['akreditasi']??'')===$ak?'selected':''; ?>><?php echo $ak; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control" value="<?php echo htmlspecialchars($profil['kode_pos']??''); ?>">
                    </div>
                </div>

                <h3 style="margin:24px 0 20px;color:var(--primary);font-size:15px;border-bottom:2px solid var(--border);padding-bottom:10px"><i class="fas fa-map-marker-alt"></i> Alamat</h3>
                <div class="form-group">
                    <label>Alamat Lengkap *</label>
                    <textarea name="alamat" class="form-control" rows="2" required><?php echo htmlspecialchars($profil['alamat']??''); ?></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="<?php echo htmlspecialchars($profil['kecamatan']??''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Kabupaten/Kota</label>
                        <input type="text" name="kabupaten" class="form-control" value="<?php echo htmlspecialchars($profil['kabupaten']??''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" value="<?php echo htmlspecialchars($profil['provinsi']??''); ?>">
                    </div>
                </div>

                <h3 style="margin:24px 0 20px;color:var(--primary);font-size:15px;border-bottom:2px solid var(--border);padding-bottom:10px"><i class="fas fa-phone"></i> Kontak</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?php echo htmlspecialchars($profil['telepon']??''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($profil['email']??''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" class="form-control" value="<?php echo htmlspecialchars($profil['website']??''); ?>">
                    </div>
                </div>

                <h3 style="margin:24px 0 20px;color:var(--primary);font-size:15px;border-bottom:2px solid var(--border);padding-bottom:10px"><i class="fas fa-history"></i> Sejarah Sekolah</h3>
                <div class="form-group">
                    <textarea name="sejarah" class="form-control" rows="6"><?php echo htmlspecialchars($profil['sejarah']??''); ?></textarea>
                    <small style="color:var(--text-light)">Tampil di halaman Sejarah (link di footer website).</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="../pages/profil.php" target="_blank" class="btn btn-secondary"><i class="fas fa-eye"></i> Lihat di Website</a>
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

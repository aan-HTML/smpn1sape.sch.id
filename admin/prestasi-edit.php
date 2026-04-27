<?php
require_once 'auth.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('prestasi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori   = $conn->real_escape_string($_POST['kategori']);
    $nama       = $conn->real_escape_string(trim($_POST['nama']));
    $kelas      = $conn->real_escape_string(trim($_POST['kelas'] ?? ''));
    $nama_lomba = $conn->real_escape_string(trim($_POST['nama_lomba']));
    $tingkat    = $conn->real_escape_string($_POST['tingkat']);
    $juara      = $conn->real_escape_string(trim($_POST['juara']));
    $tahun      = (int)$_POST['tahun'];

    $foto_q = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            if (!is_dir("../assets/images/prestasi/")) mkdir("../assets/images/prestasi/", 0755, true);
            $fn = time() . '_' . rand(1000,9999) . '.' . $ext;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/prestasi/$fn")) {
                $foto_q = ", foto='prestasi/$fn'";
            }
        }
    }

    if ($conn->query("UPDATE prestasi SET kategori='$kategori',nama='$nama',kelas='$kelas',nama_lomba='$nama_lomba',tingkat='$tingkat',juara='$juara',tahun=$tahun $foto_q WHERE id=$id")) {
        alert('Kejuaraan berhasil diperbarui!', 'success');
        redirect('prestasi.php');
    }
}

$row = $conn->query("SELECT * FROM prestasi WHERE id=$id")->fetch_assoc();
if (!$row) redirect('prestasi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Kejuaraan - Admin</title>
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
        <span><i class="fas fa-edit"></i> Edit Kejuaraan</span>
        <a href="prestasi.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label>Kategori <span style="color:#e74c3c">*</span></label>
                        <select name="kategori" class="form-control" required onchange="toggleKelas(this.value)">
                            <?php foreach(['Siswa','Tim','Sekolah'] as $k): ?>
                            <option value="<?php echo $k; ?>" <?php echo $row['kategori']===$k?'selected':''; ?>><?php echo $k === 'Siswa' ? 'Siswa (perorangan)' : $k; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" id="kelas-group" style="display:<?php echo $row['kategori']==='Siswa'?'block':'none'; ?>">
                        <label>Kelas</label>
                        <select name="kelas" class="form-control">
                            <option value="">- Pilih Kelas -</option>
                            <?php foreach(['VII','VIII','IX'] as $kl): ?>
                            <option value="<?php echo $kl; ?>" <?php echo $row['kelas']===$kl?'selected':''; ?>><?php echo $kl; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label>Nama Siswa / Tim / Sekolah <span style="color:#e74c3c">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label>Nama Lomba / Kejuaraan <span style="color:#e74c3c">*</span></label>
                        <input type="text" name="nama_lomba" class="form-control" value="<?php echo htmlspecialchars($row['nama_lomba']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tingkat <span style="color:#e74c3c">*</span></label>
                        <select name="tingkat" class="form-control" required>
                            <?php foreach(['Sekolah','Kecamatan','Kabupaten','Provinsi','Nasional','Internasional'] as $t): ?>
                            <option value="<?php echo $t; ?>" <?php echo $row['tingkat']===$t?'selected':''; ?>><?php echo $t; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Juara <span style="color:#e74c3c">*</span></label>
                        <input type="text" name="juara" class="form-control" value="<?php echo htmlspecialchars($row['juara']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tahun <span style="color:#e74c3c">*</span></label>
                        <input type="number" name="tahun" class="form-control" min="2000" max="2035" value="<?php echo $row['tahun']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ganti Foto <span style="color:var(--text-light);font-weight:400">(opsional)</span></label>
                        <?php if($row['foto']): ?><div style="margin-bottom:8px"><img src="../assets/images/<?php echo $row['foto']; ?>" style="max-width:120px;border-radius:6px"></div><?php endif; ?>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                    <a href="prestasi.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
function toggleKelas(val) {
    document.getElementById('kelas-group').style.display = val === 'Siswa' ? 'block' : 'none';
}
const sidebar=document.getElementById('sidebar'),overlay=document.getElementById('overlay'),toggle=document.getElementById('sidebarToggle');
function toggleMenu(){sidebar.classList.toggle('active');overlay.classList.toggle('active');}
if(toggle)toggle.addEventListener('click',toggleMenu);
if(overlay)overlay.addEventListener('click',toggleMenu);
</script>
</body></html>
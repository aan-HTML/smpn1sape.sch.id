<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori   = $conn->real_escape_string($_POST['kategori']);
    $nama       = $conn->real_escape_string(trim($_POST['nama']));
    $kelas      = $conn->real_escape_string(trim($_POST['kelas'] ?? ''));
    $nama_lomba = $conn->real_escape_string(trim($_POST['nama_lomba']));
    $tingkat    = $conn->real_escape_string($_POST['tingkat']);
    $juara      = $conn->real_escape_string(trim($_POST['juara']));
    $tahun      = (int)$_POST['tahun'];


    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            if (!is_dir("../assets/images/prestasi/")) mkdir("../assets/images/prestasi/", 0755, true);
            $fn = time() . '_' . rand(1000,9999) . '.' . $ext;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/images/prestasi/$fn")) {
                $foto = "prestasi/$fn";
            }
        }
    }

    if ($conn->query("INSERT INTO prestasi (kategori,nama,kelas,nama_lomba,tingkat,juara,tahun,foto) VALUES ('$kategori','$nama','$kelas','$nama_lomba','$tingkat','$juara',$tahun,'$foto')")) {
        alert('Kejuaraan berhasil ditambahkan!', 'success');
        redirect('prestasi.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Tambah Kejuaraan - Admin</title>
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
        <span><i class="fas fa-plus"></i> Tambah Kejuaraan</span>
        <a href="prestasi.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label>Kategori <span style="color:#e74c3c">*</span></label>
                        <select name="kategori" class="form-control" required onchange="toggleKelas(this.value)">
                            <option value="Siswa">Siswa (perorangan)</option>
                            <option value="Tim">Tim</option>
                            <option value="Sekolah">Sekolah</option>
                        </select>
                    </div>
                    <div class="form-group" id="kelas-group">
                        <label>Kelas <span style="color:var(--text-light);font-weight:400">(khusus kategori Siswa)</span></label>
                        <select name="kelas" class="form-control">
                            <option value="">- Pilih Kelas -</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                        </select>
                    </div>
                        <label>Nama Siswa / Tim / Sekolah <span style="color:#e74c3c">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Ahmad Fauzi / Tim Voli Putra / SMPN 1 Sape" required>
                    </div>
                        <label>Nama Lomba / Kejuaraan <span style="color:#e74c3c">*</span></label>
                        <input type="text" name="nama_lomba" class="form-control" placeholder="Contoh: Olimpiade Matematika Tingkat Kabupaten" required>
                    </div>
                    <div class="form-group">
                        <label>Tingkat <span style="color:#e74c3c">*</span></label>
                        <select name="tingkat" class="form-control" required>
                            <option value="Sekolah">Sekolah</option>
                            <option value="Kecamatan">Kecamatan</option>
                            <option value="Kabupaten">Kabupaten</option>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Juara <span style="color:#e74c3c">*</span></label>
                        <input type="text" name="juara" class="form-control" placeholder="Juara 1 / Juara 2 / Medali Emas" required>
                    </div>
                    <div class="form-group">
                        <label>Tahun <span style="color:#e74c3c">*</span></label>
                        <input type="number" name="tahun" class="form-control" min="2000" max="2035" value="<?php echo date('Y'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Foto <span style="color:var(--text-light);font-weight:400">(opsional)</span></label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
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
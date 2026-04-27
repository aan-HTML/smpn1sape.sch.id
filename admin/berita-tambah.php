<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrf_verify();
    $judul    = clean_input($_POST['judul'] ?? '');
    $slug     = generate_slug($judul);
    $konten   = $conn->real_escape_string($_POST['konten'] ?? '');
    $kategori = clean_input($_POST['kategori'] ?? '');
    $penulis  = clean_input($_POST['penulis'] ?? '');
    $status   = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'draft';

    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
        $upload = upload_file($_FILES['gambar'], 'berita/');
        if ($upload['success']) {
            $gambar = $conn->real_escape_string($upload['filename']);
        } else {
            alert($upload['message'], 'danger');
            redirect('berita-tambah.php');
        }
    }

    if ($conn->query("INSERT INTO berita (judul, slug, konten, gambar, kategori, penulis, status) VALUES ('$judul','$slug','$konten','$gambar','$kategori','$penulis','$status')")) {
        alert('Berita berhasil ditambahkan!', 'success');
        redirect('berita.php');
    } else {
        alert('Gagal menyimpan berita!', 'danger');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Tambah Berita - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include 'includes/header.php'; ?>
        <div class="content-wrapper">
            <div class="page-header">
                <h1>Tambah Berita</h1>
                <a href="berita.php" class="btn btn-secondary">← Kembali</a>
            </div>
            <?php show_alert(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group"><label>Judul Berita *</label><input type="text" name="judul" class="form-control" required></div>
                        <div class="form-group"><label>Penulis</label><input type="text" name="penulis" class="form-control" placeholder="Nama penulis (opsional)"></div>
                        <div class="form-group"><label>Konten *</label><textarea name="konten" class="form-control" rows="10" required></textarea></div>
                        <div class="form-group"><label>Gambar</label><input type="file" name="gambar" class="form-control" accept="image/jpeg,image/png,image/gif"></div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control">
                                <option value="">Pilih Kategori</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Prestasi">Prestasi</option>
                                <option value="Kegiatan">Kegiatan</option>
                                <option value="Pengumuman">Pengumuman</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
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
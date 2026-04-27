<?php
require_once 'auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrf_verify();
    $judul    = clean_input($_POST['judul'] ?? '');
    $slug     = generate_slug($judul);
    $konten   = $conn->real_escape_string($_POST['konten'] ?? '');
    $kategori = clean_input($_POST['kategori'] ?? '');
    $penulis  = clean_input($_POST['penulis'] ?? '');
    $status   = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'draft';

    $gambar_query = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $upload = upload_file($_FILES['gambar'], 'berita/');
        if ($upload['success']) {
            $fn = $conn->real_escape_string($upload['filename']);
            $gambar_query = ", gambar='$fn'";
        }
    }

    if ($conn->query("UPDATE berita SET judul='$judul', slug='$slug', konten='$konten', kategori='$kategori', penulis='$penulis', status='$status' $gambar_query WHERE id=$id")) {
        alert('Berita berhasil diupdate!', 'success');
        redirect('berita.php');
    } else {
        alert('Gagal mengupdate berita!', 'danger');
    }
}

$berita = $conn->query("SELECT * FROM berita WHERE id=$id")->fetch_assoc();
if (!$berita) redirect('berita.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Edit Berita - Admin</title>
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
                <h1>Edit Berita</h1>
                <a href="berita.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
            <?php show_alert(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group"><label>Judul Berita *</label><input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($berita['judul']); ?>" required></div>
                        <div class="form-group"><label>Penulis</label><input type="text" name="penulis" class="form-control" value="<?php echo htmlspecialchars($berita['penulis'] ?? ''); ?>" placeholder="Nama penulis (opsional)"></div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control">
                                <option value="">Pilih Kategori</option>
                                <?php foreach(['Akademik','Prestasi','Kegiatan','Pengumuman','Lainnya'] as $k): ?>
                                <option value="<?php echo $k; ?>" <?php echo $berita['kategori']==$k?'selected':''; ?>><?php echo $k; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Konten Berita *</label><textarea name="konten" class="form-control" rows="10" required><?php echo htmlspecialchars($berita['konten']); ?></textarea></div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <?php if($berita['gambar']): ?>
                            <div style="margin-bottom:10px"><img src="../assets/images/<?php echo htmlspecialchars($berita['gambar']); ?>" style="max-width:200px;border-radius:8px"></div>
                            <?php endif; ?>
                            <input type="file" name="gambar" class="form-control" accept="image/jpeg,image/png,image/gif">
                            <small>Kosongkan jika tidak ingin mengubah gambar</small>
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="draft" <?php echo $berita['status']=='draft'?'selected':''; ?>>Draft</option>
                                <option value="published" <?php echo $berita['status']=='published'?'selected':''; ?>>Published</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="berita.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
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
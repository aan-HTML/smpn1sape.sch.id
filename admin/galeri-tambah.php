<?php require_once 'auth.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    csrf_verify();
    $judul = clean_input($_POST['judul'] ?? '');
    $tanggal = clean_input($_POST['tanggal'] ?? '');
    $ekskul_id = !empty($_POST['ekstrakurikuler_id']) ? (int)$_POST['ekstrakurikuler_id'] : 'NULL';

    if(empty($judul)){ alert('Judul wajib diisi!','danger'); redirect('galeri-tambah.php'); }

    $foto = '';
    if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0){
        $upload = upload_file($_FILES['foto'], 'galeri/');
        if($upload['success']){
            $foto = $conn->real_escape_string($upload['filename']);
        } else {
            alert($upload['message'],'danger'); redirect('galeri-tambah.php');
        }
    } else {
        alert('Foto wajib diupload!','danger'); redirect('galeri-tambah.php');
    }

    $tanggal_val = $tanggal ? "'$tanggal'" : 'NULL';
    if($conn->query("INSERT INTO galeri (judul, foto, ekstrakurikuler_id, tanggal) VALUES ('$judul','$foto',$ekskul_id,$tanggal_val)")){
        alert('Foto berhasil ditambahkan!','success'); redirect('galeri.php');
    } else {
        alert('Gagal menyimpan!','danger');
    }
}

$ekskul_res = $conn->query("SELECT id, nama FROM ekstrakurikuler WHERE status='aktif' ORDER BY nama ASC");
$ekskul_list = []; if($ekskul_res){ while($r=$ekskul_res->fetch_assoc()){ $ekskul_list[]=$r; } }
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Tambah Foto Galeri - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head><body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
  <div class="page-header">
    <h1>Tambah Foto Galeri</h1>
    <a href="galeri.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
  </div>
  <?php show_alert(); ?>
  <div class="card"><div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="form-group">
        <label>Judul Foto *</label>
        <input type="text" name="judul" class="form-control" placeholder="Contoh: Latihan Pramuka, Pertandingan Voli" required>
      </div>
      <div class="form-group">
        <label>Foto *</label>
        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" required>
        <small style="color:#888">Format: JPG, PNG, GIF, WEBP</small>
      </div>
      <div class="form-group">
        <label>Tanggal Kegiatan</label>
        <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
      </div>
      <div class="form-group">
        <label>Ekstrakurikuler <small style="color:#888">(opsional — kosongkan jika foto kegiatan umum)</small></label>
        <select name="ekstrakurikuler_id" class="form-control">
          <option value="">— Galeri Umum —</option>
          <?php foreach($ekskul_list as $e): ?>
          <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nama']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="galeri.php" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div></div>
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
<?php require_once 'auth.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($_SERVER['REQUEST_METHOD']=='POST'){
    csrf_verify();
    $judul = clean_input($_POST['judul'] ?? '');
    $tanggal = clean_input($_POST['tanggal'] ?? '');
    $ekskul_id = !empty($_POST['ekstrakurikuler_id']) ? (int)$_POST['ekstrakurikuler_id'] : 'NULL';

    $foto_q = '';
    if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0){
        $upload = upload_file($_FILES['foto'], 'galeri/');
        if($upload['success']){
            $foto = $conn->real_escape_string($upload['filename']);
            $foto_q = ", foto='$foto'";
        }
    }

    $tanggal_val = $tanggal ? "'$tanggal'" : 'NULL';
    if($conn->query("UPDATE galeri SET judul='$judul', tanggal=$tanggal_val, ekstrakurikuler_id=$ekskul_id $foto_q WHERE id=$id")){
        alert('Foto berhasil diupdate!','success'); redirect('galeri.php');
    } else {
        alert('Gagal update!','danger');
    }
}

$row = $conn->query("SELECT * FROM galeri WHERE id=$id")->fetch_assoc();
if(!$row) redirect('galeri.php');

$ekskul_res = $conn->query("SELECT id, nama FROM ekstrakurikuler WHERE status='aktif' ORDER BY nama ASC");
$ekskul_list = []; if($ekskul_res){ while($r=$ekskul_res->fetch_assoc()){ $ekskul_list[]=$r; } }
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Foto Galeri - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head><body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
  <div class="page-header">
    <h1>Edit Foto Galeri</h1>
    <a href="galeri.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
  </div>
  <?php show_alert(); ?>
  <div class="card"><div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="form-group">
        <label>Judul Foto *</label>
        <input type="text" name="judul" class="form-control" value="<?php echo htmlspecialchars($row['judul']); ?>" required>
      </div>
      <div class="form-group">
        <label>Foto <small style="color:#888">(kosongkan jika tidak ingin mengganti)</small></label>
        <?php if($row['foto']): ?>
        <div style="margin-bottom:10px"><img src="../assets/images/<?php echo htmlspecialchars($row['foto']); ?>" style="max-width:200px;border-radius:6px"></div>
        <?php endif; ?>
        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
      </div>
      <div class="form-group">
        <label>Tanggal Kegiatan</label>
        <input type="date" name="tanggal" class="form-control" value="<?php echo $row['tanggal'] ?? ''; ?>">
      </div>
      <div class="form-group">
        <label>Ekstrakurikuler <small style="color:#888">(opsional)</small></label>
        <select name="ekstrakurikuler_id" class="form-control">
          <option value="">— Galeri Umum —</option>
          <?php foreach($ekskul_list as $e): ?>
          <option value="<?php echo $e['id']; ?>" <?php echo $row['ekstrakurikuler_id']==$e['id']?'selected':''; ?>><?php echo htmlspecialchars($e['nama']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
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
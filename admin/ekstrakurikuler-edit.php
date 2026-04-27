<?php
session_start();
require_once '../includes/config.php';
if (!isset($_SESSION['admin_logged_in'])) redirect('login.php');

$id = isset($_GET['id']) ? clean_input($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean_input($_POST['nama']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $jadwal = clean_input($_POST['jadwal']);
    
    $foto_query = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/images/ekskul/";
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_filename)) {
            $foto_query = ", foto = 'ekskul/$new_filename'";
        }
    }
    
    $query = "UPDATE ekstrakurikuler SET 
              nama = '$nama',
              deskripsi = '$deskripsi',
              jadwal = '$jadwal'
              $foto_query
              WHERE id = '$id'";
    
    if ($conn->query($query)) {
        // Update pembina - hapus dulu, insert ulang
        $conn->query("DELETE FROM pembina_ekstrakurikuler WHERE ekstrakurikuler_id = '$id'");
        
        if (isset($_POST['pembina']) && is_array($_POST['pembina'])) {
            foreach ($_POST['pembina'] as $pembina) {
                if (!empty(trim($pembina))) {
                    $pembina = clean_input($pembina);
                    $conn->query("INSERT INTO pembina_ekstrakurikuler (ekstrakurikuler_id, nama_pembina) VALUES ('$id', '$pembina')");
                }
            }
        }
        
        alert('Ekstrakurikuler berhasil diupdate!', 'success');
        redirect('ekstrakurikuler.php');
    }
}

$query = "SELECT * FROM ekstrakurikuler WHERE id = '$id'";
$ekskul = $conn->query($query)->fetch_assoc();
if (!$ekskul) redirect('ekstrakurikuler.php');

$query_pembina = "SELECT * FROM pembina_ekstrakurikuler WHERE ekstrakurikuler_id = '$id'";
$pembina_list = $conn->query($query_pembina);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Ekstrakurikuler - Admin</title>
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
                <h1>Edit Ekstrakurikuler</h1>
                <a href="ekstrakurikuler.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nama Ekstrakurikuler *</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $ekskul['nama']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?php echo $ekskul['deskripsi']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Jadwal</label>
                            <input type="text" name="jadwal" class="form-control" value="<?php echo $ekskul['jadwal']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Pembina</label>
                            <div id="pembina-container">
                                <?php 
                                if ($pembina_list->num_rows > 0) {
                                    while($p = $pembina_list->fetch_assoc()) {
                                        echo '<input type="text" name="pembina[]" class="form-control" style="margin-bottom:10px;" value="'.$p['nama_pembina'].'">';
                                    }
                                } else {
                                    echo '<input type="text" name="pembina[]" class="form-control" style="margin-bottom:10px;" placeholder="Nama Pembina 1">';
                                    echo '<input type="text" name="pembina[]" class="form-control" style="margin-bottom:10px;" placeholder="Nama Pembina 2">';
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addPembina()">
                                <i class="fas fa-plus"></i> Tambah Pembina
                            </button>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                            <a href="ekstrakurikuler.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    function addPembina() {
        const container = document.getElementById('pembina-container');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'pembina[]';
        input.className = 'form-control';
        input.style.marginBottom = '10px';
        input.placeholder = 'Nama Pembina';
        container.appendChild(input);
    }
    </script>
</div>
<script>
const sidebar=document.getElementById("sidebar"),overlay=document.getElementById("overlay"),toggle=document.getElementById("sidebarToggle");
function toggleMenu(){sidebar.classList.toggle("active");overlay.classList.toggle("active");}
if(toggle)toggle.addEventListener("click",toggleMenu);
if(overlay)overlay.addEventListener("click",toggleMenu);
</script>
</body>
</html>
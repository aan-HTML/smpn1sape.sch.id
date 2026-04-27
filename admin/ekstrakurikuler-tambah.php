<?php
session_start();
require_once '../includes/config.php';
if (!isset($_SESSION['admin_logged_in'])) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = clean_input($_POST['nama']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $jadwal = clean_input($_POST['jadwal']);
    
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/images/ekskul/";
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_filename)) {
            $foto = 'ekskul/' . $new_filename;
        }
    }
    
    $query = "INSERT INTO ekstrakurikuler (nama, deskripsi, jadwal, foto, status) 
              VALUES ('$nama', '$deskripsi', '$jadwal', '$foto', 'aktif')";
    
    if ($conn->query($query)) {
        $ekskul_id = $conn->insert_id;
        
        // Insert pembina (multiple)
        if (isset($_POST['pembina']) && is_array($_POST['pembina'])) {
            foreach ($_POST['pembina'] as $pembina) {
                if (!empty(trim($pembina))) {
                    $pembina = clean_input($pembina);
                    $conn->query("INSERT INTO pembina_ekstrakurikuler (ekstrakurikuler_id, nama_pembina) VALUES ('$ekskul_id', '$pembina')");
                }
            }
        }
        
        alert('Ekstrakurikuler berhasil ditambahkan!', 'success');
        redirect('ekstrakurikuler.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Ekstrakurikuler - Admin</title>
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
                <h1>Tambah Ekstrakurikuler</h1>
                <a href="ekstrakurikuler.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nama Ekstrakurikuler *</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Jadwal</label>
                            <input type="text" name="jadwal" class="form-control" placeholder="Senin, 14:00 - 16:00">
                        </div>
                        <div class="form-group">
                            <label>Pembina (2-5 orang)</label>
                            <div id="pembina-container">
                                <input type="text" name="pembina[]" class="form-control" style="margin-bottom:10px;" placeholder="Nama Pembina 1">
                                <input type="text" name="pembina[]" class="form-control" style="margin-bottom:10px;" placeholder="Nama Pembina 2">
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addPembina()">
                                <i class="fas fa-plus"></i> Tambah Pembina
                            </button>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
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
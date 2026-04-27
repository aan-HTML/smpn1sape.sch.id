<?php
session_start();
require_once '../includes/config.php';
if (!isset($_SESSION['admin_logged_in'])) redirect('login.php');

$id = isset($_GET['id']) ? clean_input($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = clean_input($_POST['judul']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $tanggal_mulai = clean_input($_POST['tanggal_mulai']);
    $tanggal_selesai = clean_input($_POST['tanggal_selesai']);
    $waktu = clean_input($_POST['waktu']);
    $tempat = clean_input($_POST['tempat']);
    $status = clean_input($_POST['status']);
    
    $query = "UPDATE agenda SET 
              judul = '$judul',
              deskripsi = '$deskripsi',
              tanggal_mulai = '$tanggal_mulai',
              tanggal_selesai = '$tanggal_selesai',
              waktu = '$waktu',
              tempat = '$tempat',
              status = '$status'
              WHERE id = '$id'";
    
    if ($conn->query($query)) {
        alert('Agenda berhasil diupdate!', 'success');
        redirect('agenda.php');
    }
}

$query = "SELECT * FROM agenda WHERE id = '$id'";
$agenda = $conn->query($query)->fetch_assoc();
if (!$agenda) redirect('agenda.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Agenda - Admin</title>
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
                <h1>Edit Agenda</h1>
                <a href="agenda.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Judul Agenda *</label>
                            <input type="text" name="judul" class="form-control" value="<?php echo $agenda['judul']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?php echo $agenda['deskripsi']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Mulai *</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="<?php echo $agenda['tanggal_mulai']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo $agenda['tanggal_selesai']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Waktu</label>
                            <input type="time" name="waktu" class="form-control" value="<?php echo $agenda['waktu']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Tempat</label>
                            <input type="text" name="tempat" class="form-control" value="<?php echo $agenda['tempat']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="akan_datang" <?php echo $agenda['status']=='akan_datang'?'selected':''; ?>>Akan Datang</option>
                                <option value="berlangsung" <?php echo $agenda['status']=='berlangsung'?'selected':''; ?>>Berlangsung</option>
                                <option value="selesai" <?php echo $agenda['status']=='selesai'?'selected':''; ?>>Selesai</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                            <a href="agenda.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                        </div>
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
</body>
</html>

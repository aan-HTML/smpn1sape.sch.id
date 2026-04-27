<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) redirect('login.php');

// BUG FIX: DELETE via POST + CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_verify();
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM agenda WHERE id=$id");
    alert('Agenda berhasil dihapus!', 'success');
    redirect('agenda.php');
}

$result = $conn->query("SELECT * FROM agenda ORDER BY tanggal_mulai DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Kelola Agenda - Admin SMPN 1 Sape</title>
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
                <h1>Agenda</h1>
                <a href="agenda-tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Agenda</a>
            </div>
            <?php show_alert(); ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>No</th><th>Judul</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php if($result && $result->num_rows > 0): $no=1; while($row=$result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['judul']); ?></td>
                                <td>
                                    <?php echo date('d M Y', strtotime($row['tanggal_mulai']));
                                    if($row['tanggal_selesai']) echo ' - '.date('d M Y', strtotime($row['tanggal_selesai'])); ?>
                                </td>
                                <td><span class="badge badge-<?php echo $row['status']=='akan_datang'?'info':($row['status']=='berlangsung'?'success':'secondary'); ?>"><?php echo ucfirst(str_replace('_',' ',$row['status'])); ?></span></td>
                                <td>
                                    <a href="agenda-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                    <!-- BUG FIX: POST + CSRF -->
                                    <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus agenda ini?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="5" class="text-center">Belum ada agenda</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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

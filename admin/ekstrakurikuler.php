<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) redirect('login.php');

// BUG FIX: DELETE via POST + CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_verify();
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM ekstrakurikuler WHERE id=$id");
    alert('Ekstrakurikuler berhasil dihapus!', 'success');
    redirect('ekstrakurikuler.php');
}

$result = $conn->query("SELECT e.*, GROUP_CONCAT(p.nama_pembina SEPARATOR ', ') as pembina_list 
                        FROM ekstrakurikuler e 
                        LEFT JOIN pembina_ekstrakurikuler p ON e.id = p.ekstrakurikuler_id 
                        GROUP BY e.id ORDER BY e.nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Kelola Ekstrakurikuler - Admin SMPN 1 Sape</title>
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
                <h1>Ekstrakurikuler</h1>
                <a href="ekstrakurikuler-tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Ekstrakurikuler</a>
            </div>
            <?php show_alert(); ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>No</th><th>Nama</th><th>Pembina</th><th>Jadwal</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php if($result && $result->num_rows > 0): $no=1; while($row=$result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['pembina_list'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['jadwal'] ?: '-'); ?></td>
                                <td>
                                    <a href="ekstrakurikuler-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                    <!-- BUG FIX: POST + CSRF -->
                                    <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus ekstrakurikuler ini?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="5" class="text-center">Belum ada ekstrakurikuler</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="overlay" class="overlay"></div>
<script>
const sidebar=document.querySelector(".sidebar"),overlay=document.getElementById("overlay"),toggle=document.getElementById("sidebarToggle");
function toggleMenu(){sidebar.classList.toggle("active");overlay.classList.toggle("active");document.body.classList.toggle("sidebar-open");}
if(toggle)toggle.addEventListener("click",toggleMenu);
if(overlay)overlay.addEventListener("click",toggleMenu);
</script>
</body></html>

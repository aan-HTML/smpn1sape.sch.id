<?php
require_once 'auth.php';

// BUG FIX: DELETE via POST + CSRF, dan gunakan (int) bukan clean_input untuk ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_verify();
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM ppdb_pendaftar WHERE id=$id");
    alert('Data pendaftar berhasil dihapus!', 'success');
    redirect('ppdb-pendaftar.php');
}

$result = $conn->query("SELECT * FROM ppdb_pendaftar ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Data Pendaftar PPDB - Admin</title>
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
                <span><i class="fas fa-user-graduate"></i> Data Pendaftar PPDB</span>
            </div>
            <?php show_alert(); ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>No</th><th>No. Pendaftaran</th><th>Nama Lengkap</th><th>NISN</th><th>Asal Sekolah</th><th>Status</th><th>Tanggal Daftar</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php if($result && $result->num_rows > 0): $no=1; while($row=$result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['no_pendaftaran']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($row['nisn']); ?></td>
                                <td><?php echo htmlspecialchars($row['asal_sekolah']); ?></td>
                                <td>
                                    <?php
                                    $badge = 'secondary';
                                    if($row['status_pendaftaran']=='diterima') $badge='success';
                                    elseif($row['status_pendaftaran']=='ditolak') $badge='danger';
                                    elseif($row['status_pendaftaran']=='diverifikasi') $badge='info';
                                    ?>
                                    <span class="badge badge-<?php echo $badge; ?>"><?php echo ucfirst(htmlspecialchars($row['status_pendaftaran'])); ?></span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="ppdb-detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Detail</a>
                                    <!-- BUG FIX: POST + CSRF -->
                                    <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus data pendaftar ini?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="8" class="text-center">Belum ada pendaftar</td></tr>
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

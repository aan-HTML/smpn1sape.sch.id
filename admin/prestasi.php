<?php
require_once 'auth.php';

// BUG FIX: DELETE via POST + CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_verify();
    $id  = (int)$_POST['delete_id'];
    $row = $conn->query("SELECT foto FROM prestasi WHERE id=$id")->fetch_assoc();
    if ($row && $row['foto']) @unlink("../assets/images/" . $row['foto']);
    $conn->query("DELETE FROM prestasi WHERE id=$id");
    alert('Data kejuaraan berhasil dihapus!', 'success');
    redirect('prestasi.php');
}

$result = $conn->query("SELECT * FROM prestasi ORDER BY tahun DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Kejuaraan - Admin</title>
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
        <span><i class="fas fa-trophy"></i> Kejuaraan</span>
        <a href="prestasi-tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kejuaraan</a>
    </div>
    <?php show_alert(); ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table">
                <thead><tr><th>No</th><th>Foto</th><th>Nama</th><th>Kategori</th><th>Nama Lomba</th><th>Tingkat</th><th>Juara</th><th>Tahun</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php if($result && $result->num_rows>0): $no=1; while($row=$result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php if($row['foto']): ?><img src="../assets/images/<?php echo htmlspecialchars($row['foto']); ?>" class="table-img"><?php else: ?><div class="no-image">-</div><?php endif; ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php $cc=['Siswa'=>'primary','Sekolah'=>'success','Tim'=>'info']; $c=$cc[$row['kategori']]?? 'secondary'; ?><span class="badge badge-<?php echo $c; ?>"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['nama_lomba']); ?></td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($row['tingkat']); ?></span></td>
                    <td><span class="badge badge-warning"><?php echo htmlspecialchars($row['juara']); ?></span></td>
                    <td><?php echo (int)$row['tahun']; ?></td>
                    <td>
                        <a href="prestasi-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <!-- BUG FIX: POST + CSRF -->
                        <form method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus?')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="9" class="text-center">Belum ada data kejuaraan</td></tr>
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
const sidebar=document.getElementById('sidebar'),overlay=document.getElementById('overlay'),toggle=document.getElementById('sidebarToggle');
function toggleMenu(){sidebar.classList.toggle('active');overlay.classList.toggle('active');}
if(toggle)toggle.addEventListener('click',toggleMenu);
if(overlay)overlay.addEventListener('click',toggleMenu);
</script>
</body></html>

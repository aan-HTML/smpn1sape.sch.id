<?php
require_once 'auth.php';

// BUG FIX: DELETE sekarang pakai POST + CSRF token, bukan GET link
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_verify();
    $id = (int)$_POST['delete_id'];
    $result = $conn->query("SELECT gambar FROM berita WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['gambar']) delete_file($row['gambar']);
    }
    if ($conn->query("DELETE FROM berita WHERE id=$id")) {
        alert('Berita berhasil dihapus!', 'success');
    } else {
        alert('Gagal menghapus berita!', 'danger');
    }
    redirect('berita.php');
}

// Pagination
$limit  = 10;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start  = ($page - 1) * $limit;
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';
$where  = $search ? "WHERE judul LIKE '%$search%' OR konten LIKE '%$search%'" : '';

$total       = $conn->query("SELECT COUNT(*) as total FROM berita $where")->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);
$result      = $conn->query("SELECT * FROM berita $where ORDER BY created_at DESC LIMIT $start, $limit");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Kelola Berita - Admin SMPN 1 Sape</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
    <?php include 'includes/header.php'; ?>
    <div class="content-wrapper">
        <div class="page-title">
            <span>Kelola Berita</span>
            <a href="berita-tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Berita</a>
        </div>
        <?php show_alert(); ?>
        <div class="card">
            <div class="card-header">
                <h4>Daftar Berita</h4>
                <div class="search-box">
                    <form method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Cari berita..." value="<?php echo htmlspecialchars($search); ?>" class="form-control">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                        <?php if($search): ?><a href="berita.php" class="btn btn-secondary"><i class="fas fa-times"></i> Reset</a><?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>No</th><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Views</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php if($result && $result->num_rows > 0): $no = $start+1; while($row=$result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php if($row['gambar']): ?><img src="../assets/images/<?php echo htmlspecialchars($row['gambar']); ?>" class="table-img"><?php else: ?><div class="no-image">-</div><?php endif; ?></td>
                            <td><?php echo htmlspecialchars($row['judul']); ?></td>
                            <td><?php echo htmlspecialchars($row['kategori'] ?: '-'); ?></td>
                            <td><?php echo (int)$row['views']; ?>x</td>
                            <td><span class="badge badge-<?php echo $row['status']=='published'?'success':'warning'; ?>"><?php echo ucfirst(htmlspecialchars($row['status'])); ?></span></td>
                            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="berita-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <!-- BUG FIX: Hapus via form POST + CSRF, bukan link GET -->
                                <form method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="8" class="text-center">Tidak ada data berita</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($total_pages > 1): ?>
                <div class="pagination">
                    <?php for($i=1;$i<=$total_pages;$i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search?'&search='.urlencode($search):''; ?>" class="page-link <?php echo $page==$i?'active':''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
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

<?php
require_once 'auth.php';

// Tandai dibaca (GET masih ok karena ini bukan destructive action)
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE pesan SET status='dibaca' WHERE id=$id");
}

// BUG FIX: DELETE via POST + CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_verify();
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM pesan WHERE id=$id");
    alert('Pesan berhasil dihapus!', 'success');
    redirect('pesan.php');
}

$result     = $conn->query("SELECT * FROM pesan ORDER BY created_at DESC");
$total_baru = $conn->query("SELECT COUNT(*) as t FROM pesan WHERE status='baru'")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Pesan Masuk - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.pesan-row-baru{background:#fffbeb;}
.pesan-row-baru td:first-child{border-left:3px solid #f59e0b;}
.reply-btn{background:var(--primary);color:#fff;padding:4px 10px;border-radius:4px;font-size:12px;display:inline-flex;align-items:center;gap:4px}
.reply-btn:hover{background:var(--primary-dark);color:#fff}
</style>
</head>
<body>
<div class="admin-container">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
<?php include 'includes/header.php'; ?>
<div class="content-wrapper">
    <div class="page-title">
        <span><i class="fas fa-envelope"></i> Pesan Masuk
        <?php if($total_baru>0): ?><span style="background:#ef4444;color:#fff;border-radius:50%;padding:2px 7px;font-size:12px;margin-left:6px"><?php echo $total_baru; ?></span><?php endif; ?>
        </span>
    </div>
    <?php show_alert(); ?>
    <div class="card">
        <div class="card-body">
            <p style="color:var(--text-light);font-size:13px;margin-bottom:16px"><i class="fas fa-info-circle"></i> Klik <strong>Balas</strong> untuk membalas pesan langsung via email pengirim.</p>
            <div class="table-responsive">
            <table class="table">
                <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Pesan</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php if($result && $result->num_rows>0): $no=1; while($row=$result->fetch_assoc()): ?>
                <tr class="<?php echo $row['status']==='baru'?'pesan-row-baru':''; ?>">
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td style="max-width:300px"><?php echo nl2br(htmlspecialchars(substr($row['pesan'],0,150))).(strlen($row['pesan'])>150?'...':''); ?></td>
                    <td style="white-space:nowrap"><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></td>
                    <td><?php echo $row['status']==='baru'?'<span class="badge badge-warning">Baru</span>':'<span class="badge badge-success">Dibaca</span>'; ?></td>
                    <td style="white-space:nowrap">
                        <?php
                        $subject = urlencode("Re: Pesan dari Website SMPN 1 Sape");
                        $body    = urlencode("Halo ".$row['nama'].",\n\nTerima kasih telah menghubungi kami.\n\nPesan Anda:\n".$row['pesan']."\n\n---\nHormat kami,\nAdmin SMPN 1 Sape");
                        ?>
                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>?subject=<?php echo $subject; ?>&body=<?php echo $body; ?>" class="reply-btn"><i class="fas fa-reply"></i> Balas</a>
                        <?php if($row['status']==='baru'): ?>
                        <a href="?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-secondary" title="Tandai dibaca"><i class="fas fa-check"></i></a>
                        <?php endif; ?>
                        <!-- BUG FIX: POST + CSRF -->
                        <form method="POST" style="display:inline" onsubmit="return confirm('Hapus pesan ini?')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" class="text-center" style="padding:40px;color:var(--text-light)"><i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3"></i>Belum ada pesan masuk.</td></tr>
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

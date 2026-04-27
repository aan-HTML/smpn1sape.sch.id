<?php
require_once 'auth.php';

$total_berita    = $conn->query("SELECT COUNT(*) as t FROM berita")->fetch_assoc()['t'];
$total_kejuaraan = $conn->query("SELECT COUNT(*) as t FROM prestasi")->fetch_assoc()['t'];
$total_agenda    = $conn->query("SELECT COUNT(*) as t FROM agenda WHERE status!='selesai'")->fetch_assoc()['t'];
$total_ppdb      = $conn->query("SELECT COUNT(*) as t FROM ppdb_pendaftar")->fetch_assoc()['t'];
$total_pesan_baru= $conn->query("SELECT COUNT(*) as t FROM pesan WHERE status='baru'")->fetch_assoc()['t'];
$total_ekskul    = $conn->query("SELECT COUNT(*) as t FROM ekstrakurikuler WHERE status='aktif'")->fetch_assoc()['t'];

$ppdb = $conn->query("SELECT * FROM ppdb_setting WHERE id=1")->fetch_assoc();
$berita_terbaru  = $conn->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT 5");
$pesan_terbaru   = $conn->query("SELECT * FROM pesan ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard Admin - SMPN 1 Sape</title>
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
        <span>Dashboard</span>
        <span style="font-size:13px;font-weight:400;color:var(--text-light)"><?php echo date('d F Y'); ?></span>
    </div>

    <!-- Status PPDB Banner -->
    <?php if($ppdb): ?>
    <div style="background:<?php echo $ppdb['status']==='buka'?'#d1fae5':'#fee2e2'; ?>;border:1px solid <?php echo $ppdb['status']==='buka'?'#a7f3d0':'#fecaca'; ?>;border-radius:8px;padding:14px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:10px">
            <i class="fas fa-<?php echo $ppdb['status']==='buka'?'door-open':'door-closed'; ?>" style="font-size:20px;color:<?php echo $ppdb['status']==='buka'?'#059669':'#dc2626'; ?>"></i>
            <div>
                <strong style="color:<?php echo $ppdb['status']==='buka'?'#065f46':'#991b1b'; ?>">PPDB <?php echo htmlspecialchars($ppdb['tahun_ajaran']); ?> — <?php echo strtoupper($ppdb['status']); ?></strong>
                <?php if($ppdb['tanggal_buka'] && $ppdb['tanggal_tutup']): ?>
                <div style="font-size:12px;color:#666"><?php echo date('d M Y',strtotime($ppdb['tanggal_buka'])); ?> s/d <?php echo date('d M Y',strtotime($ppdb['tanggal_tutup'])); ?> · Kuota: <?php echo $ppdb['kuota']; ?> siswa</div>
                <?php endif; ?>
            </div>
        </div>
        <a href="ppdb-setting.php" class="btn btn-sm btn-primary"><i class="fas fa-cog"></i> Atur PPDB</a>
    </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="stats-grid">
        <div class="stat-card bg-primary">
            <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
            <div class="stat-content"><h3><?php echo $total_berita; ?></h3><p>Total Berita</p></div>
            <a href="berita.php" class="stat-link">Kelola <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="stat-card bg-success">
            <div class="stat-icon"><i class="fas fa-trophy"></i></div>
            <div class="stat-content"><h3><?php echo $total_kejuaraan; ?></h3><p>Total Kejuaraan</p></div>
            <a href="prestasi.php" class="stat-link">Kelola <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="stat-card bg-warning">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-content"><h3><?php echo $total_agenda; ?></h3><p>Agenda Aktif</p></div>
            <a href="agenda.php" class="stat-link">Kelola <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="stat-card bg-info">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-content"><h3><?php echo $total_ppdb; ?></h3><p>Pendaftar PPDB</p></div>
            <a href="ppdb-pendaftar.php" class="stat-link">Lihat <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="stat-card bg-purple">
            <div class="stat-icon"><i class="fas fa-futbol"></i></div>
            <div class="stat-content"><h3><?php echo $total_ekskul; ?></h3><p>Ekstrakurikuler Aktif</p></div>
            <a href="ekstrakurikuler.php" class="stat-link">Kelola <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="stat-card bg-danger">
            <div class="stat-icon"><i class="fas fa-envelope"></i></div>
            <div class="stat-content"><h3><?php echo $total_pesan_baru; ?></h3><p>Pesan Baru</p></div>
            <a href="pesan.php" class="stat-link">Baca <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Berita Terbaru -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-newspaper"></i> Berita Terbaru</h3>
                <a href="berita.php" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Judul</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if($berita_terbaru->num_rows>0): while($row=$berita_terbaru->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(substr($row['judul'],0,45)).(strlen($row['judul'])>45?'...':''); ?></td>
                        <td><?php echo date('d/m/Y',strtotime($row['created_at'])); ?></td>
                        <td><span class="badge badge-<?php echo $row['status']==='published'?'success':'warning'; ?>"><?php echo $row['status']==='published'?'Tayang':'Draft'; ?></span></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="3" class="text-center">Belum ada berita</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <!-- Pesan Terbaru -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-envelope"></i> Pesan Masuk Terbaru</h3>
                <a href="pesan.php" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Nama</th><th>Pesan</th><th>Waktu</th></tr></thead>
                    <tbody>
                    <?php if($pesan_terbaru->num_rows>0): while($row=$pesan_terbaru->fetch_assoc()): ?>
                    <tr style="<?php echo $row['status']==='baru'?'background:#fffbeb':''; ?>">
                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong><?php if($row['status']==='baru'): ?> <span style="background:#ef4444;color:#fff;border-radius:50%;font-size:9px;padding:1px 5px">Baru</span><?php endif; ?></td>
                        <td><?php echo htmlspecialchars(substr($row['pesan'],0,50)).'...'; ?></td>
                        <td><?php echo date('d/m H:i',strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="3" class="text-center">Belum ada pesan</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="quick-actions">
        <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
        <div class="action-grid">
            <a href="berita-tambah.php" class="action-card">
                <div class="action-icon bg-primary"><i class="fas fa-plus-circle"></i></div>
                <div class="action-info"><strong>Tambah Berita</strong><small>Buat postingan baru</small></div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
            <a href="prestasi-tambah.php" class="action-card">
                <div class="action-icon bg-success"><i class="fas fa-trophy"></i></div>
                <div class="action-info"><strong>Tambah Kejuaraan</strong><small>Catat pencapaian terbaru</small></div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
            <a href="agenda-tambah.php" class="action-card">
                <div class="action-icon bg-warning"><i class="fas fa-calendar-plus"></i></div>
                <div class="action-info"><strong>Tambah Agenda</strong><small>Jadwalkan kegiatan</small></div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
            <a href="sambutan.php" class="action-card">
                <div class="action-icon bg-info"><i class="fas fa-user-tie"></i></div>
                <div class="action-info"><strong>Edit Sambutan</strong><small>Perbarui sambutan kepsek</small></div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
            <a href="ppdb-setting.php" class="action-card">
                <div class="action-icon bg-purple"><i class="fas fa-cog"></i></div>
                <div class="action-info"><strong>Pengaturan PPDB</strong><small>Buka/tutup pendaftaran</small></div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
            <a href="statistik.php" class="action-card">
                <div class="action-icon bg-danger"><i class="fas fa-chart-bar"></i></div>
                <div class="action-info"><strong>Statistik & Sosmed</strong><small>Update angka & link</small></div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
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

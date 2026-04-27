<?php
require_once 'includes/config.php';
$ppdb = $conn->query("SELECT * FROM ppdb_setting WHERE id = 1")->fetch_assoc();
$today = date('Y-m-d');
$is_open = ($ppdb['status'] == 'buka' && $today >= $ppdb['tanggal_buka'] && $today <= $ppdb['tanggal_tutup']);
$profil = $conn->query("SELECT * FROM profil_sekolah WHERE id=1")->fetch_assoc();
$settings=[]; $rs=$conn->query("SELECT * FROM pengaturan"); if($rs){while($r=$rs->fetch_assoc()){$settings[$r['nama_key']]=$r['nilai'];}}
?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="description" content="Penerimaan Peserta Didik Baru SMP Negeri 1 Sape">
<title>PPDB - SMP Negeri 1 Sape</title>
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="assets/css/style.css">
</head><body>
<?php include 'includes/navbar.php'; ?>

<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="index.php">Beranda</a><span class="sep">/</span><span>PPDB</span></div></div></div>

<!-- PPDB Hero -->
<div class="ppdb-hero">
  <div class="container">
    <h1><i class="fas fa-user-graduate" style="margin-right:10px;opacity:.85"></i>Penerimaan Peserta Didik Baru</h1>
    <p>Tahun Ajaran <?php echo htmlspecialchars($ppdb['tahun_ajaran']??date('Y').'/'.(date('Y')+1)); ?></p>
    <?php if($is_open): ?>
    <div class="ppdb-status-badge buka"><i class="fas fa-check-circle"></i> Pendaftaran DIBUKA</div>
    <?php else: ?>
    <div class="ppdb-status-badge tutup"><i class="fas fa-times-circle"></i> Pendaftaran DITUTUP</div>
    <?php endif; ?>
  </div>
</div>

<div class="ppdb-wrap"><div class="container">
  <div class="ppdb-grid">

    <!-- Main -->
    <div>
      <?php if($is_open): ?>
      <div class="ppdb-banner open">
        <i class="fas fa-check-circle"></i>
        <div>
          <div class="ppdb-banner-title">Pendaftaran Sedang Dibuka!</div>
          <div class="ppdb-banner-sub">Periode: <?php echo date('d F Y',strtotime($ppdb['tanggal_buka'])); ?> &ndash; <?php echo date('d F Y',strtotime($ppdb['tanggal_tutup'])); ?></div>
        </div>
      </div>
      <?php else: ?>
      <div class="ppdb-banner closed">
        <i class="fas fa-info-circle"></i>
        <div>
          <div class="ppdb-banner-title">Pendaftaran Belum Dibuka</div>
          <?php if(!empty($ppdb['tanggal_buka']) && $ppdb['tanggal_buka'] > $today): ?>
          <div class="ppdb-banner-sub">Akan dibuka pada: <strong><?php echo date('d F Y',strtotime($ppdb['tanggal_buka'])); ?></strong></div>
          <?php else: ?>
          <div class="ppdb-banner-sub">Pendaftaran telah ditutup. Pantau terus informasi terbaru.</div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Info Pendaftaran -->
      <?php if(!empty($ppdb['informasi'])): ?>
      <div class="ppdb-block">
        <h3>Informasi Pendaftaran</h3>
        <p><?php echo nl2br(htmlspecialchars($ppdb['informasi'])); ?></p>
      </div>
      <?php endif; ?>

      <!-- Persyaratan -->
      <div class="ppdb-block">
        <h3>Persyaratan Pendaftaran</h3>
        <ul>
          <li>Akta Kelahiran asli dan fotokopi</li>
          <li>Kartu Keluarga asli dan fotokopi</li>
          <li>Ijazah SD / Surat Keterangan Lulus (SKL)</li>
          <li>Pas Foto ukuran 3&times;4 berwarna (4 lembar)</li>
          <li>Surat Keterangan Sehat dari dokter</li>
          <li>Mengisi formulir pendaftaran yang disediakan</li>
        </ul>
      </div>

      <!-- Kuota -->
      <?php if(!empty($ppdb['kuota'])): ?>
      <div class="ppdb-block ppdb-kuota">
        <div class="ppdb-kuota-num"><?php echo number_format($ppdb['kuota']); ?></div>
        <div class="ppdb-kuota-label">siswa baru untuk tahun ajaran <?php echo htmlspecialchars($ppdb['tahun_ajaran']??''); ?></div>
      </div>
      <?php endif; ?>

      <!-- Daftar Button -->
      <?php if($is_open): ?>
      <div class="ppdb-actions">
        <a href="ppdb-form.php" class="ppdb-btn-primary"><i class="fas fa-edit"></i> Daftar Sekarang</a>
        <a href="ppdb-cek.php" class="ppdb-btn-secondary"><i class="fas fa-search"></i> Cek Status</a>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div>
      <div class="ppdb-sidebar-widget">
        <h4>Kontak &amp; Info</h4>
        <div class="ppdb-contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <span><?php echo htmlspecialchars($profil['alamat']??'Jl. Lintas Sape, Kab. Bima, NTB'); ?></span>
        </div>
        <div class="ppdb-contact-item">
          <i class="fas fa-phone"></i>
          <a href="tel:<?php echo str_replace([' ','-','(',')'],'', $profil['telepon']??''); ?>"><?php echo htmlspecialchars($profil['telepon']??''); ?></a>
        </div>
        <div class="ppdb-contact-item">
          <i class="fas fa-envelope"></i>
          <a href="mailto:<?php echo htmlspecialchars($profil['email']??''); ?>"><?php echo htmlspecialchars($profil['email']??''); ?></a>
        </div>
        <div class="ppdb-contact-item">
          <i class="fas fa-clock"></i>
          <span>Senin &ndash; Jumat, 07.30 &ndash; 14.00 WITA</span>
        </div>
      </div>
      <div class="ppdb-sidebar-widget">
        <h4>Jadwal PPDB</h4>
        <div class="ppdb-schedule-row"><span>Pembukaan</span><span><?php echo !empty($ppdb['tanggal_buka'])?date('d M Y',strtotime($ppdb['tanggal_buka'])):'-'; ?></span></div>
        <div class="ppdb-schedule-row"><span>Penutupan</span><span><?php echo !empty($ppdb['tanggal_tutup'])?date('d M Y',strtotime($ppdb['tanggal_tutup'])):'-'; ?></span></div>
        <div class="ppdb-schedule-row"><span>Tahun Ajaran</span><span><?php echo htmlspecialchars($ppdb['tahun_ajaran']??'-'); ?></span></div>
      </div>
      <div class="ppdb-sidebar-widget">
        <h4>Link Terkait</h4>
        <div class="ppdb-links">
          <a href="pages/profil.php"><i class="fas fa-chevron-right"></i> Profil Sekolah</a>
          <a href="pages/prestasi.php"><i class="fas fa-chevron-right"></i> Prestasi Siswa</a>
          <a href="pages/berita.php"><i class="fas fa-chevron-right"></i> Berita Terkini</a>
          <a href="pages/fasilitas.php"><i class="fas fa-chevron-right"></i> Fasilitas</a>
        </div>
      </div>
    </div>
  </div>
</div></div>

<?php include 'includes/footer.php'; ?>
</body></html>

<?php
require_once '../includes/config.php';
$profil = $conn->query("SELECT * FROM profil_sekolah WHERE id=1")->fetch_assoc();
$kepsek = $conn->query("SELECT * FROM pimpinan WHERE jabatan='Kepala Sekolah' AND status='aktif' LIMIT 1")->fetch_assoc();

?><!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Profil Sekolah - SMP Negeri 1 Sape</title>
<meta name="description" content="Profil lengkap SMP Negeri 1 Sape, identitas sekolah, sejarah, dan pimpinan sekolah.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Profil</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-school" style="margin-right:10px;opacity:.85"></i>Profil Sekolah</h1><p>SMP Negeri 1 Sape &mdash; Kabupaten Bima, Nusa Tenggara Barat</p></div></div>
<div class="page-body"><div class="container">

<!-- Info Sekolah -->
<div class="content-block fade-in">
  <div class="profil-grid">
    <div>
      <h3><i class="fas fa-id-card-alt"></i>Informasi Sekolah</h3>
      <table class="profil-table">
        <?php $rows=[['Nama Sekolah','nama_sekolah','SMP Negeri 1 Sape'],['NPSN','npsn',''],['NSS','nss',''],['Akreditasi','akreditasi',''],['Status','status_sekolah','Negeri'],['Alamat','alamat',''],['Kecamatan','kecamatan','Sape'],['Kabupaten','kabupaten','Bima'],['Provinsi','provinsi','Nusa Tenggara Barat'],['Kode Pos','kode_pos',''],['Telepon','telepon',''],['Email','email',''],['Website','website','']]; ?>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?php echo $r[0]; ?></td>
          <td><?php echo htmlspecialchars($profil[$r[1]]??$r[2]); ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <div>
      <img src="../assets/images/gedung-depan.jpg" alt="Gedung SMPN 1 Sape" loading="lazy" class="profil-cover" width="600" height="450">
    </div>
  </div>
</div>

<!-- Kepala Sekolah -->
<?php if($kepsek): ?>
<div class="content-block fade-in">
  <h3><i class="fas fa-user-tie"></i>Kepala Sekolah</h3>
  <div class="kepsek-layout">
    <div class="kepsek-photo">
      <img src="<?php echo $kepsek['foto']?'../assets/images/'.$kepsek['foto']:'../assets/images/kepsek-foto.jpg'; ?>" alt="<?php echo htmlspecialchars($kepsek['nama']); ?>" loading="lazy" width="140" height="170">
    </div>
    <div class="kepsek-info">
      <h4><?php echo htmlspecialchars($kepsek['nama']); ?></h4>
      <span class="jabatan"><?php echo htmlspecialchars($kepsek['jabatan']); ?></span>
      <p><?php echo nl2br(htmlspecialchars(substr($kepsek['sambutan']??'',0,300))); ?>...</p>
    </div>
  </div>
</div>
<?php endif; ?>

</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

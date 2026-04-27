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
<div class="page-hero"><div class="container"><h1><i class="fas fa-school" style="margin-right:10px;opacity:.8"></i>Profil Sekolah</h1><p>SMP Negeri 1 Sape - Kabupaten Bima, Nusa Tenggara Barat</p></div></div>
<div style="padding:32px 0"><div class="container">

<!-- Info Sekolah -->
<style>.profil-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;}@media(max-width:640px){.profil-grid{grid-template-columns:1fr;}}</style>
<div class="content-block profil-grid">
  <div>
    <h3 style="margin-bottom:16px">Informasi Sekolah</h3>
    <table style="width:100%;font-size:14px">
      <?php $rows=[['Nama Sekolah','nama_sekolah','SMP Negeri 1 Sape'],['NPSN','npsn',''],['NSS','nss',''],['Akreditasi','akreditasi',''],['Status','status_sekolah','Negeri'],['Alamat','alamat',''],['Kecamatan','kecamatan','Sape'],['Kabupaten','kabupaten','Bima'],['Provinsi','provinsi','Nusa Tenggara Barat'],['Kode Pos','kode_pos',''],['Telepon','telepon',''],['Email','email',''],['Website','website','']]; ?>
      <?php foreach($rows as $r): ?>
      <tr style="border-bottom:1px solid var(--gray-100)">
        <td style="padding:8px 0;color:var(--text-light);width:40%"><?php echo $r[0]; ?></td>
        <td style="padding:8px 0;font-weight:500"><?php echo htmlspecialchars($profil[$r[1]]??$r[2]); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <div>
    <img src="../assets/images/gedung-depan.jpg" alt="Gedung SMPN 1 Sape" loading="lazy" class="profil-cover" style="width:100%;border-radius:8px;aspect-ratio:4/3;object-fit:cover" width="600" height="450">
  </div>
</div>

<!-- Kepala Sekolah -->
<?php if($kepsek): ?>
<div class="content-block">
  <h3>Kepala Sekolah</h3>
  <style>
  .kepsek-layout{display:flex;gap:20px;align-items:flex-start;}
  @media(max-width:640px){.kepsek-layout{flex-direction:column;align-items:center;text-align:center;}}
  .kepsek-photo{width:120px;height:150px;border-radius:8px;overflow:hidden;flex-shrink:0;background:var(--gray-200);}
  @media(max-width:640px){.kepsek-photo{width:150px;height:180px;}}
  </style>
  <div class="kepsek-layout">
    <div class="kepsek-photo">
      <img src="<?php echo $kepsek['foto']?'../assets/images/'.$kepsek['foto']:'../assets/images/kepsek-foto.jpg'; ?>" alt="<?php echo htmlspecialchars($kepsek['nama']); ?>" style="width:100%;height:100%;object-fit:cover;object-position:top" loading="lazy" width="120" height="150">
    </div>
    <div>
      <h3 style="font-size:17px;margin-bottom:4px"><?php echo htmlspecialchars($kepsek['nama']); ?></h3>
      <p style="color:var(--primary);font-size:13px;font-weight:600;margin-bottom:8px"><?php echo htmlspecialchars($kepsek['jabatan']); ?></p>
      <p style="color:var(--text-light);font-size:13px;line-height:1.7"><?php echo nl2br(htmlspecialchars(substr($kepsek['sambutan']??'',0,300))); ?>...</p>
    </div>
  </div>
</div>
<?php endif; ?>


</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>
<?php require_once '../includes/config.php';
$query_profil = "SELECT * FROM profil_sekolah WHERE id = 1"; $profil = $conn->query($query_profil)->fetch_assoc();
?><!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Visi dan Misi - SMP Negeri 1 Sape</title>
<meta name="description" content="Visi, misi, dan tujuan SMP Negeri 1 Sape sebagai pedoman penyelenggaraan pendidikan.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css"></head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Visi &amp; Misi</span></div></div></div>
<div class="page-hero"><div class="container"><h1><i class="fas fa-bullseye" style="margin-right:10px;opacity:.85"></i>Visi dan Misi</h1><p>Pedoman penyelenggaraan pendidikan SMP Negeri 1 Sape</p></div></div>
<div class="page-body"><div class="container">

<div class="content-block fade-in">
  <h3><i class="fas fa-eye"></i>Visi Sekolah</h3>
  <p class="visi-text">&ldquo;<?php echo htmlspecialchars($profil['visi']??'Terwujudnya peserta didik yang beriman, bertaqwa, berkarakter, berprestasi, dan berwawasan lingkungan'); ?>&rdquo;</p>
</div>

<div class="content-block fade-in">
  <h3><i class="fas fa-tasks"></i>Misi Sekolah</h3>
  <?php $misi_text=$profil['misi']??''; $misi_lines=array_filter(array_map('trim', explode("\n", $misi_text))); ?>
  <?php if(!empty($misi_lines)): ?>
  <ul>
    <?php foreach($misi_lines as $m): ?>
      <li><?php echo htmlspecialchars($m); ?></li>
    <?php endforeach; ?>
  </ul>
  <?php else: ?>
  <p>Belum ada data misi sekolah.</p>
  <?php endif; ?>
</div>

<div class="content-block fade-in">
  <h3><i class="fas fa-flag"></i>Tujuan Sekolah</h3>
  <p>SMP Negeri 1 Sape bertujuan untuk mempersiapkan peserta didik agar menjadi manusia yang beriman dan bertakwa kepada Tuhan Yang Maha Esa, berakhlak mulia, sehat, berilmu, cakap, kreatif, mandiri, dan menjadi warga negara yang demokratis serta bertanggung jawab.</p>
</div>

</div></div>

<?php include '../includes/footer.php'; ?>
<style>
.visi-text{
  font-family:var(--font-heading);font-style:italic;
  font-size:clamp(18px,1.8vw,22px);line-height:1.6;
  color:var(--primary-dark);
  padding:18px 24px;border-left:3px solid var(--accent);
  background:var(--gray-50);border-radius:0 var(--radius) var(--radius) 0;
  margin:6px 0 0;
}
</style>
</body></html>

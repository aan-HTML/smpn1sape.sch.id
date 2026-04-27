<?php require_once '../includes/config.php';
$query_profil = "SELECT * FROM profil_sekolah WHERE id = 1"; $profil = $conn->query($query_profil)->fetch_assoc();
?><!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Visi dan Misi - SMP Negeri 1 Sape</title>
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css"></head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Visi &amp; Misi</span></div></div></div>
<div class="page-hero"><div class="container"><h1>Visi dan Misi</h1><p>SMP Negeri 1 Sape - Kabupaten Bima</p></div></div>
<div style="padding:32px 0"><div class="container">
<div class="content-block">
<h3><i class="fas fa-eye" style="color:var(--primary);margin-right:8px"></i>Visi Sekolah</h3>
<p style="font-size:16px;font-weight:600;color:var(--text);margin-bottom:16px">"<?php echo htmlspecialchars($profil['visi']??'Terwujudnya peserta didik yang beriman, bertaqwa, berkarakter, berprestasi, dan berwawasan lingkungan'); ?>"</p>
</div>
<div class="content-block">
<h3><i class="fas fa-bullseye" style="color:var(--primary);margin-right:8px"></i>Misi Sekolah</h3>
<?php $misi_text=$profil['misi']??''; $misi_lines=array_filter(explode("\n", $misi_text)); ?>
<ul><?php $no=1; foreach($misi_lines as $m): ?><li><?php echo htmlspecialchars(trim($m)); ?></li><?php $no++; endforeach; ?></ul>
</div>
<div class="content-block">
<h3><i class="fas fa-flag" style="color:var(--primary);margin-right:8px"></i>Tujuan Sekolah</h3>
<p style="color:var(--text-light);line-height:1.8">SMP Negeri 1 Sape bertujuan untuk mempersiapkan peserta didik agar menjadi manusia yang beriman dan bertakwa kepada Tuhan Yang Maha Esa, berakhlak mulia, sehat, berilmu, cakap, kreatif, mandiri, dan menjadi warga negara yang demokratis serta bertanggung jawab.</p>
</div>
</div></div>

<?php include '../includes/footer.php'; ?>

<button class="scroll-top" id="scrollTop" aria-label="Scroll ke atas"><i class="fas fa-chevron-up"></i></button>
<script>const scrollTopBtn=document.getElementById('scrollTop');if(scrollTopBtn){window.addEventListener('scroll',()=>scrollTopBtn.classList.toggle('show',window.scrollY>300));scrollTopBtn.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));}</script>
</body></html>

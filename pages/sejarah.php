<?php require_once '../includes/config.php'; $profil=$conn->query("SELECT * FROM profil_sekolah WHERE id=1")->fetch_assoc(); ?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Sejarah - SMP Negeri 1 Sape</title>
<meta name="description" content="Sejarah singkat berdirinya SMP Negeri 1 Sape dan perkembangannya hingga saat ini.">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include '../includes/navbar.php'; ?>
<div class="breadcrumb"><div class="container"><div class="breadcrumb-list"><a href="../index.php">Beranda</a><span class="sep">/</span><span>Sejarah</span></div></div></div>
<div class="page-hero"><div class="container"><h1>Sejarah Sekolah</h1><p>Perjalanan panjang SMP Negeri 1 Sape</p></div></div>
<div style="padding:32px 0"><div class="container">
<div class="content-block">
<h3><i class="fas fa-history" style="color:var(--primary);margin-right:8px"></i>Sejarah SMP Negeri 1 Sape</h3>
<?php if(!empty($profil['sejarah'])): ?>
<?php foreach(explode("\n",trim($profil['sejarah'])) as $p): if(trim($p)): ?><p style="color:var(--text-light);line-height:1.8;margin-bottom:12px"><?php echo htmlspecialchars(trim($p)); ?></p><?php endif; endforeach; ?>
<?php else: ?>
<p style="color:var(--text-light);line-height:1.8">SMP Negeri 1 Sape merupakan sekolah menengah pertama negeri yang berdiri di Kecamatan Sape, Kabupaten Bima, Nusa Tenggara Barat. Sekolah ini telah berdiri selama beberapa dekade dan telah melahirkan ribuan alumni yang tersebar di seluruh penjuru nusantara.</p>
<p style="color:var(--text-light);line-height:1.8">Dengan motto <em>"Berkarakter, Berprestasi, Berakhlak Mulia"</em>, SMPN 1 Sape terus berkomitmen untuk memberikan pendidikan terbaik bagi generasi penerus bangsa.</p>
<?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>

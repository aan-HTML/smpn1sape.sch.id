<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Pengembang Website - SMP Negeri 1 Sape</title>
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.tech-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;}
@media(max-width:640px){.tech-grid{grid-template-columns:1fr;}}
.tech-card{background:var(--white);border:1.5px solid var(--border);border-radius:10px;padding:24px 20px;text-align:center;transition:all .25s;}
.tech-card:hover{border-color:var(--primary);box-shadow:0 6px 20px rgba(26,82,118,.13);transform:translateY(-4px);}
.tech-icon{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:28px;}
.tech-icon.apache{background:#fef3e2;color:#d35400;}
.tech-icon.php{background:#eef0fb;color:#4F5B93;}
.tech-icon.mysql{background:#e8f4f8;color:#00758f;}
.tech-card h4{font-size:16px;font-weight:700;color:var(--primary-dark);margin-bottom:6px;}
.tech-version{display:inline-block;background:var(--primary-light);color:var(--primary);font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px;margin-bottom:10px;}
.tech-card p{font-size:12.5px;color:var(--text-light);line-height:1.7;}
.dev-card{background:var(--white);border:1.5px solid var(--border);border-radius:10px;padding:32px 28px;display:flex;gap:28px;align-items:flex-start;}
@media(max-width:640px){.dev-card{flex-direction:column;align-items:center;text-align:center;}}
.dev-avatar{width:100px;height:100px;border-radius:50%;background:var(--primary-light);border:3px solid var(--primary);display:flex;align-items:center;justify-content:center;font-size:40px;color:var(--primary);flex-shrink:0;}
.dev-info h3{font-size:20px;font-weight:800;color:var(--primary-dark);margin-bottom:4px;}
.dev-role{font-size:13px;color:var(--primary);font-weight:600;margin-bottom:12px;}
.dev-badges{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;}
.dev-badge{display:inline-flex;align-items:center;gap:5px;background:var(--gray-50);border:1.5px solid var(--border);color:var(--gray-800);font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;}
.dev-badge i{color:var(--primary);}
.dev-info p{font-size:13.5px;color:var(--text-light);line-height:1.8;margin-bottom:16px;}
.dev-link{display:inline-flex;align-items:center;gap:6px;background:var(--primary);color:#fff;padding:8px 20px;border-radius:var(--radius);font-size:13px;font-weight:700;transition:background .2s;}
.dev-link:hover{background:var(--primary-dark);}
.about-label{display:flex;align-items:center;gap:10px;font-size:18px;font-weight:800;color:var(--primary-dark);margin-bottom:16px;}
.about-label::before{content:'';display:block;width:4px;height:22px;background:var(--primary);border-radius:4px;flex-shrink:0;}
</style>
</head><body>
<?php include '../includes/navbar.php'; ?>

<div class="breadcrumb"><div class="container"><div class="breadcrumb-list">
  <a href="../index.php">Beranda</a><span class="sep">/</span><span>Pengembang</span>
</div></div></div>

<div class="page-hero"><div class="container">
  <h1><i class="fas fa-code" style="margin-right:10px;opacity:.8"></i>Pengembang Website</h1>
  <p>Informasi teknologi dan pengembang website SMP Negeri 1 Sape</p>
</div></div>

<div style="padding:32px 0"><div class="container">

  <!-- Info Website -->
  <div class="content-block" style="text-align:center;margin-bottom:28px">
    <img src="../assets/images/logo-sekolah.png" alt="Logo SMPN 1 Sape" style="width:72px;margin:0 auto 14px;display:block">
    <h3 style="font-size:20px;margin-bottom:8px">Website SMP Negeri 1 Sape</h3>
    <p style="max-width:560px;margin:0 auto;color:var(--text-light);font-size:14px;line-height:1.8">
      Sistem Informasi Manajemen Sekolah yang dikembangkan untuk menyebarkan informasi, berita, dan kegiatan seputar SMPN 1 Sape kepada seluruh warga sekolah dan masyarakat umum.
    </p>
  </div>

  <!-- Teknologi -->
  <div style="margin-bottom:28px">
    <div class="about-label"><i class="fas fa-layer-group"></i> Teknologi yang Digunakan</div>
    <div class="tech-grid">

      <div class="tech-card">
        <div class="tech-icon apache"><i class="fas fa-server"></i></div>
        <h4>Apache</h4>
        <span class="tech-version">Web Server</span>
        <p>Apache HTTP Server adalah perangkat lunak web server open-source paling populer di dunia. Website ini berjalan di atas Apache sebagai server utama yang menangani semua permintaan dari pengunjung.</p>
      </div>

      <div class="tech-card">
        <div class="tech-icon php"><i class="fab fa-php"></i></div>
        <h4>PHP</h4>
        <span class="tech-version">Bahasa Pemrograman</span>
        <p>PHP (Hypertext Preprocessor) adalah bahasa pemrograman server-side yang digunakan untuk membangun seluruh logika website ini, mulai dari pengelolaan data hingga sistem administrasi sekolah.</p>
      </div>

      <div class="tech-card">
        <div class="tech-icon mysql"><i class="fas fa-database"></i></div>
        <h4>MySQL</h4>
        <span class="tech-version">Database</span>
        <p>MySQL adalah sistem manajemen basis data relasional yang menyimpan seluruh data website seperti berita, prestasi, ekstrakurikuler, dan informasi sekolah secara terstruktur dan aman.</p>
      </div>

    </div>
  </div>

  <!-- Pengembang -->
  <div style="margin-bottom:28px">
    <div class="about-label"><i class="fas fa-user-circle"></i> Tentang Pengembang</div>
    <div class="dev-card">
      <div class="dev-avatar"><i class="fas fa-user-graduate"></i></div>
      <div class="dev-info">
        <h3>Annasirat <span style="color:var(--primary)">(Aan)</span></h3>
        <div class="dev-role"><i class="fas fa-code" style="margin-right:5px"></i>Front-End &amp; UI-UX Design</div>
        <div class="dev-badges">
          <span class="dev-badge"><i class="fas fa-graduation-cap"></i> Kelas IX-C</span>
          <span class="dev-badge"><i class="fas fa-school"></i> SMP Negeri 1 Sape</span>
          <span class="dev-badge"><i class="fas fa-map-marker-alt"></i> Sape, Bima, NTB</span>
        </div>
        <p>Hidup itu seperti programing, jika ada kesalahan maka perbaikilah untuk menjadi yang lebih baik lagi. Coding it's my live.</p>
        <a href="https://aan.my.id" target="_blank" rel="noopener" class="dev-link">
          <i class="fas fa-globe"></i> Kunjungi Situs Pribadi
        </a>
      </div>
    </div>
  </div>

  <!-- Copyright -->
  <div class="content-block" style="text-align:center;background:var(--gray-50)">
    <p style="font-size:13px;color:var(--text-light);margin:0">
      &copy; <?php echo date('Y'); ?> SMP Negeri 1 Sape &mdash; All Rights Reserved<br>
      Dibuat dengan <i class="fas fa-heart" style="color:#e74c3c"></i> oleh
      <a href="https://github.com/aan-HTML" target="_blank" rel="noopener" style="color:var(--primary);font-weight:700"> Aan</a>
    </p>
  </div>

</div></div>
<?php include '../includes/footer.php'; ?>
</body></html>
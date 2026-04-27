<?php
require_once 'includes/config.php';
header('Content-Type: application/xml; charset=utf-8');
$base = 'https://smpn1sape.web.id';
$today = date('Y-m-d');
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <!-- Halaman Utama -->
  <url><loc><?php echo $base; ?>/</loc><lastmod><?php echo $today; ?></lastmod><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc><?php echo $base; ?>/ppdb.php</loc><lastmod><?php echo $today; ?></lastmod><changefreq>monthly</changefreq><priority>0.9</priority></url>
  <url><loc><?php echo $base; ?>/pages/profil.php</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?php echo $base; ?>/pages/visi-misi.php</loc><changefreq>yearly</changefreq><priority>0.7</priority></url>
  <url><loc><?php echo $base; ?>/pages/postingan.php</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc><?php echo $base; ?>/pages/prestasi.php</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc><?php echo $base; ?>/pages/ekstrakurikuler.php</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc><?php echo $base; ?>/pages/fasilitas.php</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc><?php echo $base; ?>/pages/agenda.php</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>
  <!-- Postingan/Berita -->
  <?php
  $res = $conn->query("SELECT slug, updated_at FROM berita WHERE status='published' AND slug != '' ORDER BY updated_at DESC");
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $slug = htmlspecialchars($row['slug']);
      $lastmod = date('Y-m-d', strtotime($row['updated_at']));
      echo "  <url><loc>{$base}/berita/{$slug}</loc><lastmod>{$lastmod}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>\n";
    }
  }
  ?>
</urlset>

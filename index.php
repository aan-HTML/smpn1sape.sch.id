<?php
require_once 'includes/config.php';

// PPDB
$ppdb_r = $conn->query("SELECT status,tanggal_buka,tanggal_tutup FROM ppdb_setting WHERE id=1");
$ppdb_d = $ppdb_r ? $ppdb_r->fetch_assoc() : null;
$today = date('Y-m-d');
$ppdb_open = $ppdb_d && $ppdb_d['status']=='buka' && $today>=$ppdb_d['tanggal_buka'] && $today<=$ppdb_d['tanggal_tutup'];

// Data
$res_berita = $conn->query("SELECT * FROM berita WHERE status='published' ORDER BY created_at DESC LIMIT 6");
$berita_all=[]; if($res_berita){while($r=$res_berita->fetch_assoc()){$berita_all[]=$r;}}

$res_prestasi = $conn->query("SELECT * FROM prestasi ORDER BY tahun DESC LIMIT 8");
$prestasi_all=[]; if($res_prestasi){while($r=$res_prestasi->fetch_assoc()){$prestasi_all[]=$r;}}

$res_ekskul = $conn->query("SELECT e.*,GROUP_CONCAT(p.nama_pembina SEPARATOR ', ') as pembina_list FROM ekstrakurikuler e LEFT JOIN pembina_ekstrakurikuler p ON e.id=p.ekstrakurikuler_id WHERE e.status='aktif' GROUP BY e.id LIMIT 8");
$ekskul_all=[]; if($res_ekskul){while($r=$res_ekskul->fetch_assoc()){$ekskul_all[]=$r;}}

$res_agenda = $conn->query("SELECT * FROM agenda WHERE status!='selesai' ORDER BY id DESC LIMIT 4");
$agenda_all=[]; if($res_agenda){while($r=$res_agenda->fetch_assoc()){$agenda_all[]=$r;}}


$profil = $conn->query("SELECT * FROM profil_sekolah WHERE id=1")->fetch_assoc();
$kepsek = $conn->query("SELECT * FROM pimpinan WHERE jabatan='Kepala Sekolah' AND status='aktif' LIMIT 1")->fetch_assoc();
$res_s=$conn->query("SELECT * FROM pengaturan"); $settings=[];
if($res_s){while($r=$res_s->fetch_assoc()){$settings[$r['nama_key']]=$r['nilai'];}}

function th($p,$f='assets/images/mural.jpg'){return $p?'assets/images/'.$p:$f;}
function ex($h,$l=120){$t=strip_tags($h);return substr($t,0,$l).(strlen($t)>$l?'...':'');}
$ekskul_icons=['fa-hiking','fa-flag','fa-drum','fa-futbol','fa-music','fa-language','fa-palette','fa-camera'];
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="description" content="Website Resmi SMP Negeri 1 Sape - Penerimaan Peserta Didik Baru, Berita, Profil Sekolah, dan informasi akademik Kabupaten Bima, Nusa Tenggara Barat.">
<meta name="keywords" content="SMP Negeri 1 Sape, SMPN 1 Sape, sekolah Sape, Sekolah Rujukan, Website Sekolah, Kabupaten Bima, NTB, PPDB, pendaftaran siswa baru">
<meta name="author" content="SMP Negeri 1 Sape">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://smpn1sape.web.id/">
<!-- Open Graph (Facebook, WhatsApp, Telegram) -->
<meta property="og:type" content="website">
<meta property="og:title" content="SMP Negeri 1 Sape - Website Resmi">
<meta property="og:description" content="Website Resmi SMP Negeri 1 Sape, Kabupaten Bima, Nusa Tenggara Barat. Informasi sekolah, PPDB, berita, dan kegiatan.">
<meta property="og:url" content="https://smpn1sape.web.id/">
<meta property="og:image" content="https://smpn1sape.web.id/assets/images/gedung-depan.jpg">
<meta property="og:image:secure_url" content="https://smpn1sape.web.id/assets/images/gedung-depan.jpg">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="SMP Negeri 1 Sape - Kabupaten Bima, NTB">
<meta property="og:locale" content="id_ID">
<meta property="og:site_name" content="SMP Negeri 1 Sape">
<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="SMP Negeri 1 Sape - Website Resmi">
<meta name="twitter:description" content="Website Resmi SMP Negeri 1 Sape, Kabupaten Bima, Nusa Tenggara Barat. Informasi sekolah, PPDB, berita, dan kegiatan.">
<meta name="twitter:image" content="https://smpn1sape.web.id/assets/images/gedung-depan.jpg">
<meta name="twitter:image:alt" content="SMP Negeri 1 Sape - Kabupaten Bima, NTB">
<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"School",
  "name":"SMP Negeri 1 Sape",
  "url":"https://smpn1sape.web.id",
  "logo":"https://smpn1sape.web.id/assets/images/logo-sekolah.png",
  "image":"https://smpn1sape.web.id/assets/images/hero.webp",
  "description":"Sekolah Menengah Pertama Negeri 1 Sape, Kabupaten Bima, Nusa Tenggara Barat.",
  "address":{
    "@type":"PostalAddress",
    "streetAddress":"Jl. Soekarno-Hatta No.128",
    "addressLocality":"Sape",
    "addressRegion":"Nusa Tenggara Barat",
    "postalCode":"84172",
    "addressCountry":"ID"
  },
  "telephone":"+620374-1155",
  "email":"smpn1sape@gmail.com"
}
</script>

<title>SMP Negeri 1 Sape</title>
<!-- Preconnect CDN -->
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
<link rel="preload" as="image" href="assets/images/hero.webp" fetchpriority="high">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
<link rel="preload" href="assets/css/style.css" as="style">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- Hero Banner -->
<div class="hero-banner">
  <img src="assets/images/hero.webp" alt="SMP Negeri 1 Sape" loading="eager" fetchpriority="high">
</div>

<!-- Quick Nav -->
<section class="quick-nav-section fade-in">
  <div class="quick-nav-line"></div>
  <div class="container">
    <div class="quick-nav-grid">
      <a href="pages/prestasi.php" class="quick-nav-card">
        <i class="fas fa-trophy"></i>
        <span>Kejuaraan</span>
      </a>
      <a href="pages/ekstrakurikuler.php" class="quick-nav-card">
        <i class="fas fa-futbol"></i>
        <span>Ekstra</span>
      </a>
      <a href="pages/berita.php" class="quick-nav-card">
        <i class="fas fa-newspaper"></i>
        <span>Berita</span>
      </a>
      <a href="pages/agenda.php" class="quick-nav-card">
        <i class="fas fa-calendar-check"></i>
        <span>Agenda</span>
      </a>
    </div>
  </div>
</section>

<!-- Sambutan Kepsek -->
<?php if($kepsek): ?>
<section class="section-block fade-in" id="sambutan">
  <div class="container">
    <div class="section-title-wrap"><h2>Sambutan Kepala Sekolah</h2></div>
    <div class="sambutan-layout">
      <div class="sambutan-photo-wrap">
        <div class="sambutan-photo"><img src="<?php echo $kepsek['foto']?'assets/images/'.$kepsek['foto']:'assets/images/kepsek-foto.jpg'; ?>" alt="Kepala Sekolah" loading="lazy"></div>
        <div class="sambutan-label"><strong><?php echo htmlspecialchars($kepsek['nama']); ?></strong>Kepala Sekolah</div>
      </div>
      <div class="sambutan-text">
        <div class="quote-mark">&ldquo;</div>
        <?php foreach(explode("\n", trim($kepsek['sambutan']??'')) as $par): if(trim($par)): ?><p><?php echo htmlspecialchars(trim($par)); ?></p><?php endif; endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Kejuaraan -->
<section class="section-block bg-gray fade-in" id="kejuaraan">
  <div class="container">
    <div class="section-title-wrap">
      <h2>Kejuaraan</h2>
      <a href="pages/prestasi.php" class="view-all">Selengkapnya <i class="fas fa-arrow-right"></i></a>
    </div>
    <?php if(!empty($prestasi_all)): ?>
    <div class="carousel-wrap">
      <div class="carousel-track" id="kjTrack">
        <?php foreach($prestasi_all as $p): ?>
        <div class="kj-slide">
          <div class="prestasi-card">
            <div class="prestasi-card-thumb">
              <?php if($p['foto']): ?>
              <img src="assets/images/<?php echo htmlspecialchars($p['foto']); ?>" alt="<?php echo htmlspecialchars($p['nama']); ?>" loading="lazy">
              <?php else: ?>
              <div class="prestasi-thumb-fallback"><i class="fas fa-trophy"></i></div>
              <?php endif; ?>
              <div class="prestasi-badge"><i class="fas fa-medal"></i> <?php echo htmlspecialchars($p['juara']); ?></div>
            </div>
            <div class="prestasi-card-body">
              <div class="prestasi-tags">
                <span class="tag-primary"><?php echo htmlspecialchars($p['tingkat']); ?></span>
                <span class="tag-accent"><?php echo htmlspecialchars($p['kategori']); ?></span>
              </div>
              <h3><?php echo htmlspecialchars($p['nama']); ?></h3>
              <p><?php echo htmlspecialchars($p['nama_lomba']); ?></p>
              <div class="prestasi-year"><i class="fas fa-calendar-check"></i> Tahun <?php echo $p['tahun']; ?></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="carousel-arrows carousel-arrows-center">
      <button class="arrow-btn" id="kjPrev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
      <button class="arrow-btn" id="kjNext" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
    </div>
    <?php else: ?><div class="empty-state"><i class="fas fa-trophy"></i><p>Belum ada data kejuaraan.</p></div><?php endif; ?>
  </div>
</section>

<!-- Ekstrakurikuler -->
<section class="section-block fade-in" id="ekstra">
  <div class="container">
    <h2 class="section-center-title">Ekstrakurikuler</h2>
    <?php if(!empty($ekskul_all)): ?>
    <!-- Marquee Slider Wrapper -->
    <div class="ekskul-slider-wrapper">
      <div class="ekskul-track">
        <?php foreach($ekskul_all as $i=>$e): ?>
        <div class="ekskul-card-new">
          <div class="ekskul-icon-wrap">
            <i class="fas <?php echo $ekskul_icons[$i%count($ekskul_icons)]; ?>"></i>
          </div>
          <h3><?php echo htmlspecialchars($e['nama']); ?></h3>
          <p><?php echo htmlspecialchars(substr($e['deskripsi']??'',0,70)); ?></p>
          <div class="ekskul-time"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($e['jadwal']); ?></div>
          <a href="pages/ekstrakurikuler.php?slug=<?php echo urlencode(strtolower(str_replace(' ','-',$e['nama']))); ?>" class="ekskul-selengkapnya">SELENGKAPNYA <i class="fas fa-chevron-right"></i></a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="carousel-arrows carousel-arrows-center">
      <button class="arrow-btn ekskul-prev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
      <button class="arrow-btn ekskul-next" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
    </div>
    <?php else: ?><div class="empty-state"><i class="fas fa-star"></i><p>Belum ada ekstrakurikuler.</p></div><?php endif; ?>
  </div>
</section>

<!-- Berita -->
<section class="section-block bg-gray fade-in" id="berita">
  <div class="container">
    <div class="section-title-wrap">
      <h2>Berita</h2>
      <a href="pages/berita.php" class="view-all">Selengkapnya <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="berita-grid">
      <?php if(!empty($berita_all)): foreach(array_slice($berita_all,0,3) as $row): ?>
      <div class="berita-card">
        <a href="<?php echo !empty($row['slug']) ? 'berita/'.$row['slug'] : 'pages/berita-detail.php?id='.$row['id']; ?>" class="berita-card-thumb">
          <img src="<?php echo th($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>" loading="lazy">
        </a>
        <div class="berita-card-body">
          <h3><a href="<?php echo !empty($row['slug']) ? 'berita/'.$row['slug'] : 'pages/berita-detail.php?id='.$row['id']; ?>"><?php echo htmlspecialchars($row['judul']); ?></a></h3>
          <p><?php echo ex($row['konten'],100); ?></p>
          <div class="berita-meta">
            <span><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y',strtotime($row['created_at'])); ?></span>
            <a href="<?php echo !empty($row['slug']) ? 'berita/'.$row['slug'] : 'pages/berita-detail.php?id='.$row['id']; ?>" class="read-more-btn">Baca <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="empty-state full-row"><i class="fas fa-newspaper"></i><p>Belum ada berita.</p></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Agenda -->
<section class="section-block fade-in" id="agenda">
  <div class="container">
    <div class="section-title-wrap">
      <h2>Agenda</h2>
      <a href="pages/agenda.php" class="view-all">Selengkapnya <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="agenda-list">
      <?php if(!empty($agenda_all)): foreach($agenda_all as $ag): ?>
      <div class="agenda-item">
        <div class="agenda-date">
          <div class="day"><?php echo date('d',strtotime($ag['tanggal_mulai'])); ?></div>
          <div class="month"><?php echo date('M',strtotime($ag['tanggal_mulai'])); ?></div>
        </div>
        <div class="agenda-body">
          <h3><?php echo htmlspecialchars($ag['judul']); ?></h3>
          <?php if(!empty($ag['deskripsi'])): ?><p><?php echo htmlspecialchars(substr($ag['deskripsi'],0,120)).'...'; ?></p><?php endif; ?>
          <div class="agenda-place"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($ag['tempat']??'Sekolah'); ?></div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="empty-state"><i class="fas fa-calendar"></i><p>Belum ada agenda.</p></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Counter -->
<div class="counter-bar fade-in" id="counterSection">
  <div class="container">
    <div class="counter-grid">
      <div class="counter-item"><i class="fas fa-user-graduate"></i><div class="counter-num" data-target="<?php echo $settings['jumlah_siswa']??1075; ?>">0</div><div class="counter-label">Siswa Aktif</div></div>
      <div class="counter-item"><i class="fas fa-chalkboard-teacher"></i><div class="counter-num" data-target="<?php echo $settings['jumlah_guru']??85; ?>">0</div><div class="counter-label">Tenaga Pendidik</div></div>
      <div class="counter-item"><i class="fas fa-trophy"></i><div class="counter-num" data-target="<?php echo $settings['jumlah_prestasi']??50; ?>">0</div><div class="counter-label">Prestasi</div></div>
      <div class="counter-item"><i class="fas fa-door-open"></i><div class="counter-num" data-target="<?php echo $settings['jumlah_ruang']??30; ?>">0</div><div class="counter-label">Ruang Kelas</div></div>
    </div>
  </div>
</div>

<!-- Postingan -->
<?php
$res_galeri_post = $conn->query("SELECT * FROM galeri ORDER BY tanggal DESC, id DESC LIMIT 6");
$galeri_post = []; if($res_galeri_post){while($r=$res_galeri_post->fetch_assoc()){$galeri_post[]=$r;}}
?>
<section class="section-block bg-gray fade-in" id="postingan">
  <div class="container">
    <h2 class="section-center-title">Postingan</h2>
    <?php if(!empty($galeri_post)): ?>
    <div class="postingan-overlay-grid">
      <?php foreach($galeri_post as $g): ?>
      <div class="post-overlay-card" style="cursor:default;">
        <img src="assets/images/<?php echo htmlspecialchars($g['foto']); ?>" alt="<?php echo htmlspecialchars($g['judul']); ?>" loading="lazy">
        <div class="post-overlay-tag"><i class="fas fa-images"></i> <?php echo htmlspecialchars($g['judul']); ?></div>
        <?php if(!empty($g['tanggal'])): ?>
        <div class="post-overlay-date"><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($g['tanggal'])); ?></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="postingan-more">
      <a href="pages/postingan.php" class="lebih-banyak-btn">LEBIH BANYAK</a>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-images"></i><p>Belum ada foto kegiatan.</p></div>
    <?php endif; ?>
  </div>
</section>

<!-- Kontak -->
<section class="contact-section fade-in" id="contact">
  <div class="container">
    <div class="contact-layout">
      <div class="contact-form-wrap">
        <h2>Kontak</h2>
        <p class="subtitle">Kirimkan kritik dan saran Anda kepada kami</p>
        <form action="pages/kirim-pesan.php" method="POST">
          <div class="form-group"><label>Nama</label><input type="text" name="nama" placeholder="Nama Anda" required></div>
          <div class="form-group"><label>E-mail</label><input type="email" name="email" placeholder="email@example.com" required></div>
          <div class="form-group"><label>Pesan</label><textarea name="pesan" placeholder="Tulis pesan Anda..." required></textarea></div>
          <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Kirim</button>
          <p class="contact-note">*NB Kami apresiasi kritik dan saran Anda</p>
        </form>
      </div>
      <div class="contact-map-card">
        <div class="map-header">
          <h3><i class="fas fa-map-marked-alt"></i> Lokasi Sekolah</h3>
          <a href="https://maps.app.goo.gl/So1nJuiZrKukjTWX6" target="_blank" rel="noopener">Buka Peta <i class="fas fa-external-link-alt"></i></a>
        </div>
        <div class="map-body">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.2664120325485!2d118.98774597501355!3d-8.570362291473815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db59905af5a69f1%3A0xc80e2ab56eb3fd3e!2sSMPN%201%20SAPE!5e0!3m2!1sid!2sid!4v1774753841017!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi sekolah SMPN 1 Sape" alt="Lokasi sekolah SMPN 1 Sape"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const isMobile = () => window.innerWidth <= 600;

    // Infinite Carousel Factory
    function makeCarousel(track, btnNext, btnPrev) {
        if (!track || !btnNext || !btnPrev) return;
        let isAnim = false;

        const getGap = () => parseInt(getComputedStyle(track).gap) || 20;
        const getMoveAmt = () => {
            const card = track.firstElementChild;
            return card ? card.offsetWidth + getGap() : 280;
        };

        function goNext() {
            if (isAnim || isMobile()) return; isAnim = true;
            const mv = getMoveAmt();
            track.style.transition = 'transform 0.38s ease-in-out';
            track.style.transform = `translateX(-${mv}px)`;
            track.addEventListener('transitionend', () => {
                track.style.transition = 'none';
                track.appendChild(track.firstElementChild);
                track.style.transform = 'translateX(0)';
                isAnim = false;
            }, { once: true });
        }

        function goPrev() {
            if (isAnim || isMobile()) return; isAnim = true;
            const mv = getMoveAmt();
            track.style.transition = 'none';
            track.prepend(track.lastElementChild);
            track.style.transform = `translateX(-${mv}px)`;
            void track.offsetWidth;
            track.style.transition = 'transform 0.38s ease-in-out';
            track.style.transform = 'translateX(0)';
            track.addEventListener('transitionend', () => { isAnim = false; }, { once: true });
        }

        btnNext.addEventListener('click', goNext);
        btnPrev.addEventListener('click', goPrev);
    }

    // Ekskul carousel
    makeCarousel(
        document.querySelector('.ekskul-track'),
        document.querySelector('.ekskul-next'),
        document.querySelector('.ekskul-prev')
    );

    // Kejuaraan carousel
    makeCarousel(
        document.getElementById('kjTrack'),
        document.getElementById('kjNext'),
        document.getElementById('kjPrev')
    );

    // Scroll top button
    const scrollBtn = document.getElementById('scrollTop');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            scrollBtn.classList.toggle('show', window.scrollY > 300);
        }, { passive: true });
        scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // Fade In Animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

    // Counter Animation
    function animateCounter(element, target) {
        const start = 0;
        const duration = 2000; // 2 seconds
        const step = (target - start) / (duration / 16); // 60fps
        let current = start;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target.toLocaleString() + '+';
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString() + '+';
            }
        }, 16);
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.counter-num');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    animateCounter(counter, target);
                });
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const counterSection = document.getElementById('counterSection');
    if (counterSection) {
        counterObserver.observe(counterSection);
    }
});
</script>
</body>
</html>
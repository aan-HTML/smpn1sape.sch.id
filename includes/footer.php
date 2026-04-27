<?php
$in_p=(strpos($_SERVER['PHP_SELF'],'/pages/')!==false);
$base_f=$in_p?'../':'';
$pf=isset($profil_nav)?$profil_nav:(isset($profil)?$profil:[]);
$sf=isset($settings_nav)?$settings_nav:(isset($settings)?$settings:[]);
?>
<footer class="footer" role="contentinfo">
  <div class="footer-deco"></div>
  <div class="container">
    <div class="footer-3boxes">
      <div class="fbox">
        <div class="fbox-title"><i class="fas fa-info-circle"></i> Tentang</div>
        <div class="fbox-btns">
          <a href="<?php echo $base_f; ?>pages/sambutan-kepsek.php" class="fbox-btn"><i class="fas fa-user-tie"></i> Kepala Sekolah</a>
          <a href="<?php echo $base_f; ?>pages/visi-misi.php" class="fbox-btn"><i class="fas fa-trophy"></i> Visi dan Misi</a>
          <a href="<?php echo $base_f; ?>pages/sejarah.php" class="fbox-btn"><i class="fas fa-history"></i> Sejarah</a>
          <a href="<?php echo $base_f; ?>pages/about.php" class="fbox-btn"><i class="fas fa-users"></i> Pengembang Website</a>
        </div>
      </div>
      <div class="fbox">
        <div class="fbox-title"><i class="fas fa-link"></i> Website Terkait</div>
        <div class="fbox-btns">
          <a href="https://kemendikdasmen.go.id" target="_blank" rel="noopener" class="fbox-btn"><i class="fas fa-external-link-alt"></i> Kemdikbud</a>
          <a href="https://github.com/aan-HTML" target="_blank" rel="noopener" class="fbox-btn"><i class="fas fa-external-link-alt"></i> Github Pengembang</a>
          <a href="https://bimakab.go.id" target="_blank" rel="noopener" class="fbox-btn"><i class="fas fa-external-link-alt"></i> Pemkab Bima</a>
          <a href="<?php echo $base_f; ?>ppdb.php" class="fbox-btn"><i class="fas fa-user-graduate"></i> PPDB Online</a>
        </div>
      </div>
      <div class="fbox">
        <div class="fbox-school">
          <div class="fbox-school-head">
            <img src="<?php echo $base_f; ?>assets/images/logo-sekolah.png" alt="Logo SMP Negeri 1 Sape" loading="lazy" width="54" height="54">
            <h3>SMP Negeri 1 Sape</h3>
          </div>
          <div class="fbox-info-list">
            <div class="fbox-info-row"><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars($pf['alamat']??'Jl. Soekarno-Hatta Oi Maci, Sape'); ?></span></div>
            <div class="fbox-info-row"><i class="fas fa-phone"></i><a href="tel:<?php echo str_replace([' ','-','(',')'],'', $pf['telepon']??''); ?>"><?php echo htmlspecialchars($pf['telepon']??'(0374) 123456'); ?></a></div>
            <div class="fbox-info-row"><i class="fas fa-envelope"></i><a href="mailto:<?php echo htmlspecialchars($pf['email']??''); ?>"><?php echo htmlspecialchars($pf['email']??'smpn1sape@gmail.com'); ?></a></div>
          </div>
          <div class="fbox-socials">
            <a href="<?php echo htmlspecialchars($sf['facebook']??'#'); ?>" target="_blank" rel="noopener" class="fbox-social" aria-label="Facebook SMPN 1 Sape"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="fbox-social" aria-label="Twitter SMPN 1 Sape"><i class="fab fa-twitter"></i></a>
            <a href="#" class="fbox-social" aria-label="Informasi SMPN 1 Sape"><i class="fas fa-info"></i></a>
            <a href="<?php echo htmlspecialchars($sf['instagram']??'#'); ?>" target="_blank" rel="noopener" class="fbox-social" aria-label="Instagram SMPN 1 Sape"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-copy">
    <div class="container">
      &copy; 2025 &ndash; <?php echo date('Y'); ?> | <span>SISTEM INFORMASI MANAJEMEN SEKOLAH SMP Negeri 1 Sape</span><br>
      Powered by <span>Siswa SMPN 1 Sape</span> Created with <span style="color:#C9A84C">&#9829;</span>
    </div>
  </div>
</footer>
<div id="navOverlay" class="nav-overlay" aria-hidden="true"></div>
<button class="scroll-top" id="scrollTop" aria-label="Scroll ke atas"><i class="fas fa-chevron-up"></i></button>
<script src="<?php echo $base_f; ?>assets/js/mobile-menu.js" defer></script>
<script>
(function(){
  // Scroll top logic
  var s=document.getElementById('scrollTop');
  if(s){window.addEventListener('scroll',function(){s.classList.toggle('show',window.scrollY>300);},{passive:true});s.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});}
  // Fade-in observer (ensures all pages get reveal animations)
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(entries){
      entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('visible');io.unobserve(e.target);}});
    },{threshold:0.1,rootMargin:'0px 0px -40px 0px'});
    document.querySelectorAll('.fade-in').forEach(function(el){io.observe(el);});
  }else{
    document.querySelectorAll('.fade-in').forEach(function(el){el.classList.add('visible');});
  }
})();
</script>

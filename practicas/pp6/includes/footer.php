<!-- FOOTER -->
<footer class="footer section" style="background: #2C3E50;">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="footer-widget">
          <h4 class="text-white mb-3">TechSolutions Pro</h4>
          <p class="text-light">Transformamos ideas en soluciones digitales. Desarrollo web, apps móviles, consultoría IT y mantenimiento de sistemas.</p>
          <ul class="list-inline footer-socials mt-4">
            <li class="list-inline-item">
              <a href="#"><i class="ti-facebook" style="color: #E67E22;"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="#"><i class="ti-twitter" style="color: #E67E22;"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="#"><i class="ti-linkedin" style="color: #E67E22;"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="#"><i class="ti-github" style="color: #E67E22;"></i></a>
            </li>
          </ul>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6">
        <div class="footer-widget">
          <h5 class="text-white mb-3">Enlaces</h5>
          <ul class="list-unstyled footer-menu lh-35">
            <li><a href="index.php" class="text-light">Inicio</a></li>
            <li><a href="portfolio.php" class="text-light">Portfolio</a></li>
            <li><a href="blog.php" class="text-light">Blog</a></li>
            <li><a href="contact.php" class="text-light">Contacto</a></li>
          </ul>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="footer-widget">
          <h5 class="text-white mb-3">Servicios</h5>
          <ul class="list-unstyled footer-menu lh-35">
            <li><a href="#" class="text-light">Desarrollo Web</a></li>
            <li><a href="#" class="text-light">Apps Móviles</a></li>
            <li><a href="#" class="text-light">Consultoría IT</a></li>
            <li><a href="#" class="text-light">Mantenimiento</a></li>
          </ul>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="footer-widget">
          <h5 class="text-white mb-3">Contacto</h5>
          <ul class="list-unstyled footer-menu lh-35">
            <li class="text-light"><i class="ti-location-pin"></i> Barcelona, España</li>
            <li class="text-light"><i class="ti-mobile"></i> +34 123 456 789</li>
            <li class="text-light"><i class="ti-email"></i> info@techsolutions.com</li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="footer-btm py-4 mt-5">
      <div class="row align-items-center">
        <div class="col-lg-12">
          <div class="copyright text-center">
            <p class="text-light mb-0">
              &copy; <?php echo date('Y'); ?> TechSolutions Pro. Todos los derechos reservados.
              <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                | <a href="admin/index.php" style="color: #E67E22;">Panel Admin</a>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- 
Essential Scripts
=====================================-->

<!-- Main jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4.3.1 -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<!-- Slick Slider -->
<script src="plugins/slick/slick.min.js"></script>
<!--  Custom script -->
<script src="js/script.js"></script>

</body>
</html>
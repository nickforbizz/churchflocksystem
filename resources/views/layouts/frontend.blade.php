<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name') }} – Church Management System</title>

  <meta name="description" content="A complete church management system for managing members, groups, events, attendance, giving, and church communication.">
  <meta name="keywords" content="Church Management System, Church Software, Member Management, Tithes, Events, Attendance">

  <!-- Favicons -->
  <link href="{{ asset('assets/frontend/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/frontend/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Poppins:wght@100;300;400;500;600;700;800;900&family=Raleway:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">

    <a href="{{ route('wellcome') }}" class="logo d-flex align-items-center me-auto">
      <h1 class="sitename">{{ config('app.name') }}</h1>
      <span>.</span>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="#hero" class="active">Home</a></li>
        <li><a href="#about">About the System</a></li>
        <li><a href="#portfolio">Features</a></li>
        <li><a href="{{ url('blogs') }}">Resources</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <a class="btn-getstarted" href="{{ route('login') }}"> Login</a>

  </div>
</header>

<main class="main">
  @yield('content')
</main>

<footer id="footer" class="footer dark-background">

  <div class="container footer-top">
    <div class="row gy-4">

      <div class="col-lg-4 col-md-6 footer-about">
        <a href="{{ route('wellcome') }}" class="logo d-flex align-items-center">
          <span class="sitename">{{ config('app.name') }}</span>
        </a>
        <div class="footer-contact pt-3">
          <p>Church Management System</p>
          <p>Serving churches and ministries</p>
          <p class="mt-3"><strong>Phone:</strong> <span>+254 7XX XXX XXX</span></p>
          <p><strong>Email:</strong> <span>support@yourchurchsystem.com</span></p>
        </div>
        <div class="social-links d-flex mt-4">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-3 footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#hero">Home</a></li>
          <li><a href="#about">About System</a></li>
          <li><a href="#">Terms of Use</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-md-3 footer-links">
        <h4>System Modules</h4>
        <ul>
          <li><a href="#">Membership Management</a></li>
          <li><a href="#">Events & Attendance</a></li>
          <li><a href="#">Giving & Donations</a></li>
          <li><a href="#">Announcements</a></li>
          <li><a href="#">Reports & Analytics</a></li>
        </ul>
      </div>

      <div class="col-lg-4 col-md-12 footer-newsletter">
        <h4>Church Updates</h4>
        <p>Subscribe to receive product updates, feature releases, and church management tips.</p>
        <form action="#" method="post" class="php-email-form">
          <div class="newsletter-form">
            <input type="email" name="email" placeholder="Your email address">
            <input type="submit" value="Subscribe">
          </div>
          <div class="loading">Loading</div>
          <div class="error-message"></div>
          <div class="sent-message">Thank you for subscribing.</div>
        </form>
      </div>

    </div>
  </div>

  <div class="container copyright text-center mt-4">
    <p>
      © <span>Copyright</span>
      <strong class="px-1 sitename">{{ config('app.powered_by') }}</strong>
      <span>All Rights Reserved</span>
    </p>
    <div class="credits">
      Built on <a href="{{ config('app.powered_by_url') }}" target="blank">{{ config('app.powered_by') }} Labs</a>
    </div>
  </div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/frontend/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('assets/frontend/js/main.js') }}"></script>

</body>
</html>

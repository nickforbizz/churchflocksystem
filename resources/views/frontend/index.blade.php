@extends('layouts.frontend')

@section('content')



<!-- Hero Section -->
<section id="hero" class="hero section">

  <img src="{{ asset('assets/frontend/img/hero-bg.jpg') }}" alt="" data-aos="fade-in">

  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h2 data-aos="fade-up" data-aos-delay="100">Empower Your Church with a Smarter Management System</h2>
        <p data-aos="fade-up" data-aos-delay="200">Modern, Laravel-based platform that simplifies membership, events, donations, and communication—all from one powerful dashboard.</p>
        <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="#about" class="btn-get-started me-4">Who are we</a>
          <a href="#services" class="btn-get-started btn-outline"><span>See Features</span> <i class="bi bi-chevron-right"></i> </a>
        </div>

      </div>
    </div>
  </div>

</section><!-- /Hero Section -->

<!-- Clients Section -->
<section id="clients" class="clients section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="swiper init-swiper">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": {
            "delay": 5000
          },
          "slidesPerView": "auto",
          "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets",
            "clickable": true
          },
          "breakpoints": {
            "320": {
              "slidesPerView": 2,
              "spaceBetween": 40
            },
            "480": {
              "slidesPerView": 3,
              "spaceBetween": 60
            },
            "640": {
              "slidesPerView": 4,
              "spaceBetween": 80
            },
            "992": {
              "slidesPerView": 6,
              "spaceBetween": 120
            }
          }
        }
      </script>
      <div class="swiper-wrapper align-items-center">
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-1.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-2.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-3.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-4.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-5.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-6.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-7.png') }}" class="img-fluid" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('assets/frontend/img/clients/client-8.png') }}" class="img-fluid" alt=""></div>
      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>

</section><!-- /Clients Section -->

<!-- About Section -->
<section id="about" class="about section section-bg dark-background">

  <div class="container position-relative">

    <div class="row gy-5">

      <div class="content col-xl-5 d-flex flex-column" data-aos="fade-up" data-aos-delay="100">
        <h3>Built for Modern Churches – Simple, Secure, and Scalable</h3>
        <p>
          The Church Flock System is a comprehensive platform designed to support the administrative, financial, and communication needs of modern churches. Whether you're managing a small congregation or a large ministry, our system gives you the tools to stay organized and connected.
        </p>
        <a href="#" class="about-btn align-self-center align-self-xl-start"><span>About us</span> <i class="bi bi-chevron-right"></i></a>
      </div>

      <div class="col-xl-7" data-aos="fade-up" data-aos-delay="200">
        <div class="row gy-4">

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-briefcase"></i>
            <h4><a href="" class="stretched-link">🎯 Intuitive Dashboard </a></h4>
            <p>Gain insights at a glance with our user-friendly dashboard, designed for quick access to key metrics and information.</p>
          </div><!-- Icon-Box -->

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-gem"></i>
            <h4><a href="" class="stretched-link">🔐 Role-Based Access Control</a></h4>
            <p>Ensure that the right people have access to the right information with our role-based access control features.</p>
          </div><!-- Icon-Box -->

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-broadcast"></i>
            <h4><a href="" class="stretched-link">📊 Data-Driven Reports</a></h4>
            <p>Make informed decisions with our comprehensive reporting tools, providing insights into your church's activities and growth.</p>
          </div><!-- Icon-Box -->

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-easel"></i>
            <h4><a href="" class="stretched-link">📬 Smart Communication Tools</a></h4>
            <p>Streamline your church's communication with our integrated messaging and notification system.</p>
          </div><!-- Icon-Box -->

        </div>
      </div>

    </div>

  </div>

</section><!-- /About Section -->

<!-- Stats Section -->
<section id="stats" class="stats section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-emoji-smile"></i>
        <div class="stats-item">
          <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
          <p>Happy Clients</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-journal-richtext"></i>
        <div class="stats-item">
          <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
          <p>Projects</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-headset"></i>
        <div class="stats-item">
          <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1" class="purecounter"></span>
          <p>Hours Of Support</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-people"></i>
        <div class="stats-item">
          <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
          <p>Hard Workers</p>
        </div>
      </div><!-- End Stats Item -->

    </div>

  </div>

</section><!-- /Stats Section -->

<!-- Tabs Section -->
<section id="tabs" class="tabs section">

  <div class="container">

    <ul class="nav nav-tabs row  d-flex" data-aos="fade-up" data-aos-delay="100">
      <li class="nav-item col-3">
        <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#tabs-tab-1">
          <i class="bi bi-binoculars"></i>
          <h4 class="d-none d-lg-block">📊 Analytics</h4>
        </a>
      </li>
      <li class="nav-item col-3">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabs-tab-2">
          <i class="bi bi-box-seam"></i>
          <h4 class="d-none d-lg-block">📅 Event Management</h4>
        </a>
      </li>
      <li class="nav-item col-3">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabs-tab-3">
          <i class="bi bi-brightness-high"></i>
          <h4 class="d-none d-lg-block">📰 Content Management</h4>
        </a>
      </li>
      <li class="nav-item col-3">
        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabs-tab-4">
          <i class="bi bi-command"></i>
          <h4 class="d-none d-lg-block">⚙️ System Management</h4>
        </a>
      </li>
    </ul><!-- End Tab Nav -->

    <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

      <div class="tab-pane fade active show" id="tabs-tab-1">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3>Analytics</h3>
            <p class="fst-italic">Gain insights into your community's engagement and growth.</p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span>Track membership growth month by month to understand your congregation's expansion.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Visualize donation trends with clear, monthly financial summaries.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Monitor content creation to see how actively your team is engaging the community.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Download reports in CSV format for offline analysis and record-keeping.</span></li>
            </ul>
            <p>
              Our analytics dashboard transforms your church's data into actionable insights, helping you make informed decisions for ministry planning and outreach.
            </p>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="{{ asset('assets/frontend/img/working-1.jpg') }}" alt="" class="img-fluid">
          </div>
        </div>
      </div><!-- End Tab Content Item -->

      <div class="tab-pane fade" id="tabs-tab-2">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3>Event Management</h3>
            <p>
              Organize and manage church events with ease. Our event management system allows you to create, schedule, and promote events while keeping your congregation informed.
            </p>
            <p class="fst-italic">
              Simplify your event planning process and enhance participation with our user-friendly tools.
            </p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span>Plan and schedule events effortlessly with our intuitive interface.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Promote events through automated email notifications and reminders.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Track RSVPs and manage attendee lists with ease.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Gather feedback post-event to improve future gatherings.</span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="{{ asset('assets/frontend/img/working-2.jpg') }}" alt="" class="img-fluid">
          </div>
        </div>
      </div><!-- End Tab Content Item -->

      <div class="tab-pane fade" id="tabs-tab-3">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3>Analytics Dashboard</h3>
            <p>
              Gain valuable insights into your church's activities and engagement through our comprehensive analytics dashboard. Track key metrics, visualize trends, and make data-driven decisions to enhance your ministry.
            </p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span>Visualize donation trends with clear, monthly financial summaries.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Monitor content creation to see how actively your team is engaging the community.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Download reports in CSV format for offline analysis and record-keeping.</span></li>
            </ul>
            <p class="fst-italic">
              Make informed decisions to drive your ministry forward.
            </p>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="{{ asset('assets/frontend/img/working-3.jpg') }}" alt="" class="img-fluid">
          </div>
        </div>
      </div><!-- End Tab Content Item -->

      <div class="tab-pane fade" id="tabs-tab-4">
        <div class="row">
          <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
            <h3>Event Management</h3>
            <p>
              Simplify your event planning process and enhance participation with our user-friendly tools.
            </p>
            <p class="fst-italic">
              Make informed decisions to drive your ministry forward.
            </p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span>Plan and schedule events effortlessly with our intuitive interface.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Promote events through automated email notifications and reminders.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Track RSVPs and manage attendee lists with ease.</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Gather feedback post-event to improve future gatherings.</span></li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img src="{{ asset('assets/frontend/img/working-4.jpg') }}" alt="" class="img-fluid">
          </div>
        </div>
      </div><!-- End Tab Content Item -->

    </div>

  </div>

</section><!-- /Tabs Section -->

<!-- Services Section -->
<section id="services" class="services section section-bg dark-background">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Services</h2>
    <p>Everything You Need to Manage Your Church Efficiently</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-4">

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-briefcase icon flex-shrink-0"></i>
          <div>
            <h4 class="title"><a href="service-details.html" class="stretched-link">🧍 Member & Group Management</a></h4>
            <p class="description">Maintain detailed member records, organize into ministry groups, and easily communicate with each.</p>
          </div>
        </div>
      </div><!-- End Service Item -->

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-card-checklist icon flex-shrink-0"></i>
          <div>
            <h4 class="title"><a href="service-details.html" class="stretched-link">💰 Donations Tracking</a></h4>
            <p class="description">Log, categorize, and report on contributions linked to specific members.</p>
          </div>
        </div>
      </div><!-- End Service Item -->

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-bar-chart icon flex-shrink-0"></i>
          <div>
            <h4 class="title"><a href="service-details.html" class="stretched-link">📅 Event Management</a></h4>
            <p class="description">Schedule and manage church events using an interactive calendar.</p>
          </div>
        </div>
      </div><!-- End Service Item -->

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-binoculars icon flex-shrink-0"></i>
          <div>
            <h4 class="title"><a href="service-details.html" class="stretched-link">📢 Announcements</a></h4>
            <p class="description">Send targeted SMS and Email notifications to groups or the entire congregation.</p>
          </div>
        </div>
      </div><!-- End Service Item -->

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-brightness-high icon flex-shrink-0"></i>
          <div>
            <h4 class="title"><a href="service-details.html" class="stretched-link">📰 Content Management</a></h4>
            <p class="description">Share news, sermons, and blog posts directly from the system.</p>
          </div>
        </div>
      </div><!-- End Service Item -->

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-calendar4-week icon flex-shrink-0"></i>
          <div>
            <h4 class="title"><a href="service-details.html" class="stretched-link">📈 Reports & Insights</a></h4>
            <p class="description">Visualize growth trends, giving patterns, and content activity with exportable reports.</p>
          </div>
        </div>
      </div><!-- End Service Item -->

    </div>

  </div>

</section><!-- /Services Section -->

<!-- Portfolio Section -->
<section id="portfolio" class="portfolio section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Portfolio</h2>
    <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

      <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
        <li data-filter="*" class="filter-active">All</li>
        <li data-filter=".filter-app">App</li>
        <li data-filter=".filter-product">Product</li>
        <li data-filter=".filter-branding">Branding</li>
        <li data-filter=".filter-books">Books</li>
      </ul><!-- End Portfolio Filters -->

      <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/app-1.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>App 1</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/app-1.jpg') }}" title="App 1" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/product-1.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Product 1</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/product-1.jpg') }}" title="Product 1" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/branding-1.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Branding 1</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/branding-1.jpg') }}" title="Branding 1" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/books-1.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Books 1</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/books-1.jpg') }}" title="Branding 1" data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/app-2.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>App 2</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/app-2.jpg') }}" title="App 2" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/product-2.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Product 2</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/product-2.jpg') }}" title="Product 2" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/branding-2.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Branding 2</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/branding-2.jpg') }}" title="Branding 2" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/books-2.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Books 2</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/books-2.jpg') }}" title="Branding 2" data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/app-3.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>App 3</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/app-3.jpg') }}" title="App 3" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/product-3.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Product 3</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/product-3.jpg') }}" title="Product 3" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/branding-3.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Branding 3</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/branding-3.jpg') }}" title="Branding 2" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
          <div class="portfolio-content h-100">
            <img src="{{ asset('assets/frontend/img/portfolio/books-3.jpg') }}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4>Books 3</h4>
              <p>Lorem ipsum, dolor sit amet consectetur</p>
              <a href="{{ asset('assets/frontend/img/portfolio/books-3.jpg') }}" title="Branding 3" data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
              <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div><!-- End Portfolio Item -->

      </div><!-- End Portfolio Container -->

    </div>

  </div>

</section><!-- /Portfolio Section -->

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Testimonials</h2>
    <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="swiper init-swiper">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": {
            "delay": 5000
          },
          "slidesPerView": "auto",
          "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets",
            "clickable": true
          },
          "breakpoints": {
            "320": {
              "slidesPerView": 1,
              "spaceBetween": 40
            },
            "1200": {
              "slidesPerView": 3,
              "spaceBetween": 10
            }
          }
        }
      </script>
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="{{ asset('assets/frontend/img/testimonials/testimonials-1.jpg') }}" class="testimonial-img" alt="">
            <h3>Saul Goodman</h3>
            <h4>Ceo &amp; Founder</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="{{ asset('assets/frontend/img/testimonials/testimonials-2.jpg') }}" class="testimonial-img" alt="">
            <h3>Sara Wilsson</h3>
            <h4>Designer</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="{{ asset('assets/frontend/img/testimonials/testimonials-3.jpg') }}" class="testimonial-img" alt="">
            <h3>Jena Karlis</h3>
            <h4>Store Owner</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="{{ asset('assets/frontend/img/testimonials/testimonials-4.jpg') }}" class="testimonial-img" alt="">
            <h3>Matt Brandon</h3>
            <h4>Freelancer</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="{{ asset('assets/frontend/img/testimonials/testimonials-5.jpg') }}" class="testimonial-img" alt="">
            <h3>John Larson</h3>
            <h4>Entrepreneur</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>

</section><!-- /Testimonials Section -->



<!-- Faq Section -->
<section id="faq" class="faq section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Frequently Asked Questions</h2>
    <p> Here are some common questions about our platform.</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row justify-content-center">

      <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

        <div class="faq-container">

          <div class="faq-item faq-active">
            <h3>What is the purpose of this platform?</h3>
            <div class="faq-content">
              <p>This platform aims to provide a comprehensive solution for managing church activities, events, and community engagement.</p>
            </div>
            <i class="faq-toggle bi bi-chevron-right"></i>
          </div><!-- End Faq item-->

          <div class="faq-item">
            <h3>How can I get involved?</h3>
            <div class="faq-content">
              <p>You can get involved by signing up for our newsletter, attending events, and participating in community discussions.</p>
            </div>
            <i class="faq-toggle bi bi-chevron-right"></i>
          </div><!-- End Faq item-->

          <div class="faq-item">
            <h3>What features does this platform offer?</h3>
            <div class="faq-content">
              <p>Our platform offers a variety of features including event management, community engagement tools, and resource sharing. Users can create and manage events, communicate with members, and access a library of resources to support their ministry.</p>
            </div>
            <i class="faq-toggle bi bi-chevron-right"></i>
          </div><!-- End Faq item-->

          <div class="faq-item">
            <h3>What is the purpose of this platform?</h3>
            <div class="faq-content">
              <p>This platform aims to provide a comprehensive solution for managing church activities, events, and community engagement.</p>
            </div>
            <i class="faq-toggle bi bi-chevron-right"></i>
          </div><!-- End Faq item-->

          <div class="faq-item">
            <h3>How can I get support?</h3>
            <div class="faq-content">
              <p>If you need support, you can reach out to our support team via the contact form on our website or by emailing support@example.com. We are here to help you with any questions or issues you may have.</p>
            </div>
            <i class="faq-toggle bi bi-chevron-right"></i>
          </div><!-- End Faq item-->

          <div class="faq-item">
            <h3>What is the mission of this platform?</h3>
            <div class="faq-content">
              <p>Our mission is to empower churches and communities by providing a platform that facilitates communication, collaboration, and resource sharing. We aim to support the growth and engagement of church communities through innovative technology.</p>
            </div>
            <i class="faq-toggle bi bi-chevron-right"></i>
          </div><!-- End Faq item-->

        </div>

      </div><!-- End Faq Column-->

    </div>

  </div>

</section><!-- /Faq Section -->

<!-- Team Section -->
<section id="team" class="team section section-bg dark-background">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Team</h2>
    <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
        <div class="team-member">
          <div class="member-img">
            <img src="{{ asset('assets/frontend/img/team/team-1.jpg') }}" class="img-fluid" alt="">
            <div class="social">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
          <div class="member-info">
            <h4>Walter White</h4>
            <span>Chief Executive Officer</span>
          </div>
        </div>
      </div><!-- End Team Member -->

      <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
        <div class="team-member">
          <div class="member-img">
            <img src="{{ asset('assets/frontend/img/team/team-2.jpg') }}" class="img-fluid" alt="">
            <div class="social">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
          <div class="member-info">
            <h4>Sarah Jhonson</h4>
            <span>Product Manager</span>
          </div>
        </div>
      </div><!-- End Team Member -->

      <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
        <div class="team-member">
          <div class="member-img">
            <img src="{{ asset('assets/frontend/img/team/team-3.jpg') }}" class="img-fluid" alt="">
            <div class="social">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
          <div class="member-info">
            <h4>William Anderson</h4>
            <span>CTO</span>
          </div>
        </div>
      </div><!-- End Team Member -->

      <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="400">
        <div class="team-member">
          <div class="member-img">
            <img src="{{ asset('assets/frontend/img/team/team-4.jpg') }}" class="img-fluid" alt="">
            <div class="social">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
          <div class="member-info">
            <h4>Amanda Jepson</h4>
            <span>Accountant</span>
          </div>
        </div>
      </div><!-- End Team Member -->

    </div>

  </div>

</section><!-- /Team Section -->

<!-- Contact Section -->
<section id="contact" class="contact section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Contact</h2>
    <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">
      <div class="col-lg-6 ">
        <div class="row gy-4">

          <div class="col-lg-12">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt"></i>
              <h3>Address</h3>
              <p>A108 Adam Street, New York, NY 535022</p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone"></i>
              <h3>Call Us</h3>
              <p>+1 5589 55488 55</p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope"></i>
              <h3>Email Us</h3>
              <p>info@example.com</p>
            </div>
          </div><!-- End Info Item -->

        </div>
      </div>

      <div class="col-lg-6">
        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="500">
          <div class="row gy-4">

            <div class="col-md-6">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
            </div>

            <div class="col-md-6 ">
              <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
            </div>

            <div class="col-md-12">
              <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
            </div>

            <div class="col-md-12">
              <textarea class="form-control" name="message" rows="4" placeholder="Message" required=""></textarea>
            </div>

            <div class="col-md-12 text-center">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>

              <button type="submit">Send Message</button>
            </div>

          </div>
        </form>
      </div><!-- End Contact Form -->

    </div>

  </div>

</section><!-- /Contact Section -->
@endsection

@extends('layouts.frontend')

@section('content')

<!-- Hero Section -->
<section id="hero" class="hero section">

  <img src="{{ asset('assets/frontend/img/hero-bg.jpg') }}" alt="Church Management System" data-aos="fade-in">

  <div class="container"> 
    <div class="row">
      <div class="col-lg-6">
        <h2 data-aos="fade-up" data-aos-delay="100">
          A Complete Digital Church Management System
        </h2>
        <p data-aos="fade-up" data-aos-delay="200">
          Manage members, groups, events, attendance, giving, and communication — all in one secure platform.
        </p>
        <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="#about" class="btn-get-started">Request a Demo</a>
          <a href="#" class="glightbox btn-watch-video d-flex align-items-center">
            <i class="bi bi-play-circle"></i>
            <span>See How It Works</span>
          </a>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /Hero Section -->

<!-- Objectives Section -->
<section id="objectives" class="objectives section">

  <div class="container section-title" data-aos="fade-up">
    <h2>Our Mission</h2>
    <p>
      Helping churches streamline administration, strengthen member engagement, and improve accountability through digital tools.
    </p>
  </div>

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-4">

      <div class="col-md-6">
        <ul class="list-unstyled">
          <li><i class="bi bi-people-fill"></i> Member records, groups & attendance tracking</li>
          <li><i class="bi bi-calendar-event"></i> Church events and service scheduling</li>
          <li><i class="bi bi-megaphone"></i> Announcements and group communication</li>
        </ul>
      </div>

      <div class="col-md-6">
        <ul class="list-unstyled">
          <li><i class="bi bi-cash-stack"></i> Tithes, offerings & donations tracking</li>
          <li><i class="bi bi-file-earmark-bar-graph"></i> Reports for leadership and accountability</li>
          <li><i class="bi bi-shield-lock"></i> Secure role-based access control</li>
        </ul>
      </div>

    </div>
  </div>

</section>
<!-- /Objectives Section -->

<!-- Clients Section -->
<section id="clients" class="clients section">
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <p class="text-center mb-4">
      Trusted by churches, ministries, and faith-based organizations
    </p>
  </div>
</section>
<!-- /Clients Section -->

<!-- About Section -->
<section id="about" class="about section section-bg dark-background">

  <div class="container position-relative">
    <div class="row gy-5">

      <div class="content col-xl-5 d-flex flex-column" data-aos="fade-up" data-aos-delay="100">
        <h3>Built for Churches, Designed for Ministry</h3>
        <p>
          This system supports pastors, administrators, and ministry leaders by simplifying church operations and improving transparency.
        </p>
        <a href="#services" class="about-btn align-self-center align-self-xl-start">
          <span>Explore Features</span> <i class="bi bi-chevron-right"></i>
        </a>
      </div>

      <div class="col-xl-7" data-aos="fade-up" data-aos-delay="200">
        <div class="row gy-4">

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-people"></i>
            <h4>Membership Management</h4>
            <p>Centralized member profiles, families, and church groups.</p>
          </div>

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-calendar-check"></i>
            <h4>Events & Attendance</h4>
            <p>Track services, meetings, and member participation.</p>
          </div>

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-cash"></i>
            <h4>Giving & Finance</h4>
            <p>Record tithes, offerings, and donations with clear reports.</p>
          </div>

          <div class="col-md-6 icon-box position-relative">
            <i class="bi bi-megaphone"></i>
            <h4>Church Communication</h4>
            <p>Send announcements to members and groups instantly.</p>
          </div>

        </div>
      </div>

    </div>
  </div>

</section>
<!-- /About Section -->

<!-- Stats Section -->
<section id="stats" class="stats section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-4">

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-people"></i>
        <div class="stats-item">
          <span class="purecounter">0</span>
          <p>Registered Members</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-calendar-event"></i>
        <div class="stats-item">
          <span class="purecounter">0</span>
          <p>Church Events</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-cash-stack"></i>
        <div class="stats-item">
          <span class="purecounter">0</span>
          <p>Donations Recorded</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
        <i class="bi bi-diagram-3"></i>
        <div class="stats-item">
          <span class="purecounter">0</span>
          <p>Active Church Groups</p>
        </div>
      </div>

    </div>
  </div>

</section>
<!-- /Stats Section -->

<!-- Services Section -->
<section id="services" class="services section section-bg dark-background">

  <div class="container section-title" data-aos="fade-up">
    <h2>Core System Modules</h2>
    <p>
      Everything your church needs to manage people, events, giving, and communication.
    </p>
  </div>

  <div class="container">
    <div class="row gy-4">

      <div class="col-md-6">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-people icon"></i>
          <div>
            <h4 class="title">Members & Groups</h4>
            <p class="description">Manage members, families, ministries, and small groups.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-calendar4-week icon"></i>
          <div>
            <h4 class="title">Events & Attendance</h4>
            <p class="description">Schedule services and track attendance easily.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-cash-stack icon"></i>
          <div>
            <h4 class="title">Giving & Finance</h4>
            <p class="description">Record tithes, offerings, and donations securely.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="service-item d-flex position-relative h-100">
          <i class="bi bi-megaphone icon"></i>
          <div>
            <h4 class="title">Announcements</h4>
            <p class="description">Send announcements to all members or specific groups.</p>
          </div>
        </div>
      </div>

    </div>
  </div>

</section>
<!-- /Services Section -->

@endsection

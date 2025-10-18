<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LB Dairy - Smart Livestock Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", sans-serif;
  background-color: #f6f4e8;
}

/* Navbar */
nav {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: 95%;
  background-color: #fff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  border-radius: 20px;
  z-index: 1000;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
  height: 70px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Logo */
.logo {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo img {
  height: 45px;
  width: auto;
  object-fit: contain;
}

/* Nav Links */
.nav-links {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.nav-links .btns {
  text-decoration: none;
  color: #18375d;
  font-weight: 500;
  transition: all 0.2s ease;
}

.nav-links .btns:hover {
  border-bottom: 2px solid #fca700;
  padding-bottom: 2px;
}

/* Buttons */
.auth-buttons {
  display: flex;
  align-items: center;
  gap: 12px;
}

.nav-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 22px;
  font-size: 14px;
  font-weight: 500;
  color: #18375d;
  background: #ffffff;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
  white-space: nowrap;
}

.nav-btn:hover {
  background-color: #fca700;
  color: #ffffff;
  transform: translateY(-2px);
}

.nav-btn.highlight {
  background-color: #18375d;
  border: none;
  color: #ffffff;
  box-shadow: 0 3px 8px rgba(0, 50, 252, 0.2);
}

.nav-btn.highlight:hover {
  background-color: #fca700;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(252, 167, 0, 0.35);
}

/* Hamburger */
.hamburger {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
  cursor: pointer;
  z-index: 1100;
}

.hamburger span {
  width: 25px;
  height: 2px;
  background-color: #18375d;
  transition: all 0.3s ease;
}

/* Hamburger Animation */
.hamburger.active span:nth-child(1) {
  transform: translateY(7px) rotate(45deg);
}
.hamburger.active span:nth-child(2) {
  opacity: 0;
}
.hamburger.active span:nth-child(3) {
  transform: translateY(-7px) rotate(-45deg);
}

/* Mobile Menu */
.mobile-menu {
  display: none;
  flex-direction: column;
  align-items: center;
  overflow: hidden;
  max-height: 0;
  transition: max-height 0.4s ease, padding 0.3s ease;
  border-top: 1px solid #e5e7eb;
  background-color: white;
  border-radius: 0 0 20px 20px;
}

.mobile-menu.active {
  display: flex;
  padding: 1rem 0;
  max-height: 500px; /* Enough to fit all items */
}

.mobile-menu .btns {
  text-decoration: none;
  color: #18375d;
  margin: 8px 0;
  font-weight: 500;
}

/* Visibility Control */
.desktop-only {
  display: flex;
}

.mobile-only {
  display: none;
}

/* Responsive */
@media (max-width: 768px) {
  .nav-links,
  .desktop-only {
    display: none; /* Hide from top bar */
  }

  .hamburger {
    display: flex;
  }

  .mobile-only {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    gap: 5px;
  }

  .mobile-only .nav-btn {
    width: 50%;
    text-align: center;
      border: 2px solid #18375d;
    font-size: 16px;
  }
  .mobile-only .nav-btn:hover {
  background-color: #fca700;
  color: #ffffff;
  transform: translateY(-2px);
}
  .mobile-only .nav-btn.highlight {
    width: 50%;
    text-align: center;
    font-size: 16px;
  }
  .mobile-only .nav-btn.highlight:hover {
  background-color: #fca700;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(252, 167, 0, 0.35);
}
}
/* ===============================
   HERO SECTION - Modern Responsive Design
   =============================== */
.hero-section {
  position: relative;
  background: url('/img/cow.jpg') no-repeat center center / cover;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  overflow: hidden;
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom right,
    rgba(10, 25, 50, 0.55),
    rgba(15, 40, 75, 0.4)
  );
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 2rem;
}


.hero-content {
  text-align: left;
  max-width: 850px;
  width: 100%;
  z-index: 2;
  animation: fadeInUp 1.2s ease-in-out;
}

.hero-content h1 {
  font-size: clamp(2rem, 4vw, 3.5rem);
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 1.5rem;
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
}

.hero-content h1 span {
  display: block;
  color: #fca700;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

.hero-content p {
  font-size: clamp(1rem, 1.5vw, 1.25rem);
  color: #f8f9fa;
  margin-bottom: 2rem;
  line-height: 1.6;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
}

/* ===== Buttons ===== */
.hero-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.hero-buttons a {
  display: inline-block;
  text-decoration: none;
  padding: 0.9rem 2rem;
  border-radius: 10px;
  font-weight: 600;
  transition: all 0.3s ease;
  font-size: 1rem;
  text-align: center;
}

/* Primary Button */
.btn-primary {
  background-color: #ffffff;
  color: #18375d !important;
  box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
}

.btn-primary:hover {
  background-color: #f5f5f5;
  transform: translateY(-3px);
}

/* Outline Button */
.btn-outline {
  border: 2px solid #fff;
  color: #fff !important;
}

.btn-outline:hover {
  background-color: #fff;
  color: #18375d !important;
  transform: translateY(-3px);
}

/* Accent Button */
.btn-accent {
  background-color: #fca700;
  color: #fff !important;
  border: none;
}

.btn-accent:hover {
  background-color: #e59b00;
  transform: translateY(-3px);
}

/* ===== Animation ===== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(25px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===== Responsive Design ===== */

/* Tablets */
@media (max-width: 992px) {
  .hero-content {
    text-align: center;
    padding: 0 1.5rem;
  }

  .hero-content h1 {
    font-size: 2.5rem;
  }

  .hero-buttons {
    justify-content: center;
  }
}

/* Mobile Devices */
@media (max-width: 768px) {
  .hero-section {
    min-height: 90vh;
    padding: 4rem 1rem;
  }

  .hero-content {
    text-align: center;
  }

  .hero-content h1 {
    font-size: 2rem;
  }

  .hero-content p {
    font-size: 1rem;
  }

  .hero-buttons {
    flex-direction: column;
    gap: 0.75rem;
    align-items: center;
  }

  .hero-buttons a {
    width: 80%;
    max-width: 250px;
  }
}

/* Small Phones */
@media (max-width: 480px) {
  .hero-section {
    min-height: 100vh;
    padding: 3rem 1rem;
  }

  .hero-content h1 {
    font-size: 1.75rem;
  }

  .hero-content p {
    font-size: 0.95rem;
  }

  .hero-buttons a {
    padding: 0.8rem 1.5rem;
    font-size: 0.95rem;
  }
}
/* ==============================
   FEATURE SECTION - Custom Styling
   ============================== */
section {
  position: relative;
  z-index: 1;
}

/* Grid container refinement */
.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2.5rem; /* Space between cards */
  align-items: stretch;
}

/* Feature Card */
.feature-card {
  background: #fff;
  border-radius: 1.25rem; /* rounded-2xl */
  padding: 2rem;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
  transition: all 0.35s ease;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 35px rgba(24, 55, 93, 0.18);
}

/* Icon container */
.feature-card .icon {
  width: 3.5rem;
  height: 3.5rem;
  background: rgba(24, 55, 93, 0.1);
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #18375d;
  font-size: 1.8rem;
  margin-bottom: 1rem;
  transition: all 0.3s ease;
}

.feature-card:hover .icon {
  background: #18375d;
  color: #ffffff;
  transform: scale(1.1);
}
.feature-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem; /* space between cards */
  width: 100%;
  max-width: 1100px;
}
/* Headings & Text */
.feature-card h3 {
  font-weight: 600;
  font-size: 1.25rem;
  color: #1e293b;
  margin-bottom: 0.75rem;
}

.feature-card p {
  color: #475569;
  line-height: 1.6;
  font-size: 0.975rem;
}

/* Section Title */
.features-title {
  font-size: 2rem;
  font-weight: 700;
  text-align: center;
  color: #18375d;
  margin-bottom: 3rem;
  letter-spacing: 0.5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .feature-card {
    padding: 1.5rem;
  }

  .feature-card .icon {
    width: 3rem;
    height: 3rem;
    font-size: 1.5rem;
  }

  .features-title {
    font-size: 1.75rem;
    margin-bottom: 2rem;
  }
}
/* ==============================
   STATS SECTION - 3 IN A ROW
   ============================== */
.stats-section {
  padding: 2rem 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #f9fafb;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem; /* space between cards */
  width: 100%;
  max-width: 1100px;
}

/* Base Card Style */
.stats-card,
.stats-card-alt,
.stats-card-alt2 {
  border-radius: 1.25rem;
  text-align: center;
  padding: 2rem;
  min-height: 120px; /* taller cards */
  display: flex;
  flex-direction: column;
  justify-content: center;
  color: #fff;
  transition: all 0.4s ease;
}

/* Individual Color Variants */
.stats-card {
  background: linear-gradient(135deg, #4c1d95, #a78bfa);
  box-shadow: 0 8px 25px rgba(24, 55, 93, 0.3);
}

.stats-card-alt {
  background: linear-gradient(135deg, #2b9348, #55a630);
  box-shadow: 0 8px 25px rgba(43, 147, 72, 0.3);
}

.stats-card-alt2 {
  background: linear-gradient(135deg, #fca700, #fbbf24);
  box-shadow: 0 8px 25px rgba(252, 167, 0, 0.3);
}

/* Hover animation */
.stats-card:hover,
.stats-card-alt:hover,
.stats-card-alt2:hover {
  transform: translateY(-6px);
  filter: brightness(1.08);
}

/* Typography */
.stats-card h2,
.stats-card-alt h2,
.stats-card-alt2 h2 {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.stats-card p,
.stats-card-alt p,
.stats-card-alt2 p {
  font-size: 1.1rem;
  font-weight: 500;
}

/* Responsive: stack on mobile */
@media (max-width: 992px) {
  .stats-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 600px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .stats-card,
  .stats-card-alt,
  .stats-card-alt2 {
    min-height: 120px;
  }
}/* ==============================
   FEATURE SECTION - Custom Styling
   ============================== */
section {
  position: relative;
  z-index: 1;
}

/* Grid container refinement */
.feature-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 3 columns on desktop */
  gap: 2.5rem; /* space between cards */
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  align-items: stretch;
}

/* Feature Card */
.feature-card {
  background: #ffffff;
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: all 0.35s ease;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  height: 100%;
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 14px 40px rgba(24, 55, 93, 0.18);
}

/* Icon container */
.feature-card .icon {
  width: 4rem;
  height: 4rem;
  background: rgba(24, 55, 93, 0.1);
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fca700;
  font-size: 2rem;
  margin-bottom: 1.25rem;
  transition: all 0.3s ease;
}

.feature-card:hover .icon {
  background: #18375d;
  color: #ffffff;
  transform: scale(1.1);
}

/* Headings & Text */
.feature-card h3 {
  font-weight: 600;
  font-size: 1.35rem;
  color: #1e293b;
  margin-bottom: 0.75rem;
}

.feature-card p {
  color: #475569;
  line-height: 1.7;
  font-size: 1rem;
}

/* Section Title */
.features-title {
  font-size: 2.25rem;
  font-weight: 700;
  text-align: center;
  color: #18375d;
  margin-bottom: 3rem;
  letter-spacing: 0.5px;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .feature-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columns on tablets */
  }
}

@media (max-width: 640px) {
  .feature-grid {
    grid-template-columns: 1fr; /* 1 column on mobile */
  }

  .feature-card {
    padding: 1.5rem;
  }

  .feature-card .icon {
    width: 3.25rem;
    height: 3.25rem;
    font-size: 1.6rem;
  }

  .features-title {
    font-size: 1.85rem;
    margin-bottom: 2rem;
  }
}
/* Button Container */
.flex-btns {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 1rem;
}

@media (min-width: 640px) {
  .flex-btns {
    flex-direction: row;
  }
}

/* Common Button Styles */
.btn {
  display: inline-block;
  padding: 0.75rem 2rem;
  font-size: 1.125rem;
  font-weight: 600;
  border-radius: 0.75rem;
  text-align: center;
  text-decoration: none;
  transition: all 0.3s ease-out;
}

/* Dashboard Button */
.btn-dashboard {
  background-color: #ffffff;
  color: #18375d;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-dashboard:hover {
  background-color: #ebf4ff; /* similar to Tailwind's blue-50 */
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

/* Join Now Button */
.btn-join {
  background-color: #ffffff;
  color: #18375d;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-join:hover {
  background-color: #fca700;
  color: #ffffff;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

/* Sign In Button */
.btn-signin {
  border: 1px solid #ffffff;
  color: #ffffff;
  background: transparent;
}

.btn-signin:hover {
  background-color: #fca700;
  border: 1px solid #fca700;
  color: #ffffff;
}


.smart-modal {
  border-radius: 1rem;
  border: none;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
  background: #fff;
  transition: all 0.3s ease-in-out;
}

.btn-ok i {
  margin-right: 6px;
}

/* ===== Modal ===== */
.custom-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.custom-modal.active {
    display: flex;
}

.custom-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
}

.custom-modal-content {
    position: relative;
    z-index: 2;
    background: #ffffff;
    padding: 3rem;
    border-radius: 1.25rem;
    text-align: center;
    max-width: 700px;
    width: 90%;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    animation: popIn 0.3s ease;
    font-size: 1rem;
}

.custom-modal-content h3 {
    color: #18375d;
    font-weight: 600;
    font-size: 1.55rem;
    margin-bottom: 0.5rem;
}

.custom-modal-content p {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

@keyframes popIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* ===== Button ===== */
.btn-modern {
    background: #ffffff;
    color: #18375d;
    border: 1px solid #18375d;
    border-radius: 0.55rem;
    padding: 7px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-modern:hover {
    background: #fca700;
    color: #ffffff;
    border-color: #fca700;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .custom-modal-content {
        padding: 2rem;
        width: 95%;
        max-width: 500px;
    }

    .custom-modal-content h3 {
        font-size: 1.5rem;
    }

    .custom-modal-content p {
        font-size: 1rem;
    }

    .btn-modern {
        padding: 8px 16px; /* slightly smaller button on mobile */
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .custom-modal-content {
        padding: 1.5rem;
        width: 90%;
    }

    .custom-modal-content h3 {
        font-size: 1.25rem;
    }

    .custom-modal-content p {
        font-size: 0.95rem;
    }

    .btn-modern {
        padding: 6px 20px;
        font-size: 0.85rem;
    }
}

    </style>
</head>
<body class="bg-gray-50">
    <nav>
  <div class="nav-container">
    <!-- Logo -->
    <a href="/" class="logo">
      <img src="/img/LBDairy.png" alt="LB Dairy Logo">
      <img src="/img/LBDairy_Rectangle.png" alt="LB Dairy Text">
    </a>

<!-- ================== NAV BUTTON ================== -->
<div class="nav-links">
  <button type="button" class="btns" onclick="openModal('aboutModal')">
    About Us
  </button>
  <button type="button" class="btns" onclick="openModal('featureModal')">Features</button>
  <button type="button" class="btns" onclick="openModal('contactModal')">Contact</button>
</div>


    <!-- Auth Buttons (Visible on Desktop) -->
    <div class="auth-buttons desktop-only">
      @auth
        <a href="{{ url('/dashboard') }}" class="nav-btn">Dashboard</a>
      @else
        <a href="{{ url('/login') }}" class="nav-btn">Sign In</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="nav-btn highlight">Sign Up</a>
        @endif
      @endauth
    </div>

    <!-- Hamburger (Mobile Only) -->
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu">
    <button type="button" class="btns" onclick="openModal('aboutModal')">
    About Us
  </button>
  <button type="button" class="btns" onclick="openModal('featureModal')">Features</button>
  <button type="button" class="btns" onclick="openModal('contactModal')">Contact</button>

    <!-- Auth Buttons (Visible on Mobile Only) -->
    <div class="auth-buttons mobile-only">
      @auth
        <a href="{{ url('/dashboard') }}" class="nav-btn">Dashboard</a>
      @else
        <a href="{{ url('/login') }}" class="nav-btn">Sign In</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="nav-btn highlight">Sign Up</a>
        @endif
      @endauth
    </div>
  </div>
</nav>

<script>
  // Toggle mobile menu
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    mobileMenu.classList.toggle('active');
  });
</script>

<!-- Hero Section -->
<section class="hero-section">
  <div class="hero-overlay">
    <div class="hero-content">
      <h1>
        Smart Livestock Management
        <span>for Modern Dairy Farms</span>
      </h1>

      <p>
        Make managing your dairy farm easier. Keep track of your animals,
        monitor their health, and improve farm productivity.
      </p>

      <div class="hero-buttons">
        @auth
          <a href="{{ url('/dashboard') }}" class="btn-outline">Go to Dashboard</a>
        @else
          <a href="#features" class="btn-outline">Learn More</a>
          <a href="#contact" class="btn-accent">Join Us</a>
        @endauth
      </div>
    </div>
  </div>
</section>



<!-- Features Section -->
<section id="features" class="py-20" style="background-color: #18375d;">

  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">

  <div class="stats-grid grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

    <div class="stats-card text-white rounded-2xl shadow-lg flex flex-col justify-center items-center p-10">
      <h3 class="text-5xl font-extrabold mb-2">{{ $activeFarms ?? 0 }}+</h3>
      <p class="text-lg font-medium">Active Farms</p>
    </div>

    <div class="stats-card-alt text-white rounded-2xl shadow-lg flex flex-col justify-center items-center p-10">
      <h3 class="text-5xl font-extrabold mb-2">{{ $totalLivestock ?? 0 }}+</h3>
      <p class="text-lg font-medium">Livestock Tracked</p>
    </div>

    <div class="stats-card-alt2 text-white rounded-2xl shadow-lg flex flex-col justify-center items-center p-10">
      <h3 class="text-5xl font-extrabold mb-2">{{ $uptime ?? 99.5 }}%</h3>
      <p class="text-lg font-medium">Uptime</p>
    </div>
  </div>


<br>
    <!-- Section Title -->
    <section class="features-section">
  <div class="text-center mb-16 px-4">
    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
      Everything You Need to Manage Your Dairy Farm
    </h2>
    <p class="text-lg text-blue-100 max-w-3xl mx-auto">
      From livestock tracking to production monitoring, our comprehensive platform provides all the tools you need for efficient farm management.
    </p>
  </div>

  @php
    $features = [
      ['icon' => 'fa-cow', 'title' => 'Livestock Management', 'desc' => 'Manage animal records, monitor health, track breeding, and measure performance seamlessly.'],
      ['icon' => 'fa-industry', 'title' => 'Production Tracking', 'desc' => 'Record daily production, monitor output, and analyze trends for better farm efficiency.'],
      ['icon' => 'fa-heartbeat', 'title' => 'Health Monitoring', 'desc' => 'Track vaccinations, treatments, and health alerts to keep livestock in optimal condition.'],
      ['icon' => 'fa-coins', 'title' => 'Financial Management', 'desc' => 'Monitor sales, expenses, and profits to maintain transparency and increase productivity.'],
      ['icon' => 'fa-triangle-exclamation', 'title' => 'Issue Management', 'desc' => 'Identify and resolve farm issues efficiently through real-time alerts and smart reporting.'],
      ['icon' => 'fa-chart-line', 'title' => 'Analytics & Reports', 'desc' => 'Gain insights through analytics and generate automated reports for smarter decisions.'],
    ];
  @endphp

  <div class="feature-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 max-w-6xl mx-auto px-6">
    @foreach($features as $feature)
      <div class="feature-card bg-white p-8 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
        <div class="w-16 h-16 flex items-center justify-center mb-6 rounded-xl bg-[#18375d]/10 text-[#18375d]">
          <i class="fa-solid {{ $feature['icon'] }} text-3xl"></i>
        </div>
        <h3 class="text-2xl font-semibold color=#fca700 icon-900 mb-3">{{ $feature['title'] }}</h3>
        <p class="text-gray-600 text-base leading-relaxed">{{ $feature['desc'] }}</p>
      </div>
    @endforeach
  </div>
</section>

  </div>
</section>


<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- CTA Section -->
<section id="contact" class="relative text-white py-24 overflow-hidden" style="background-color: #18375d;" >

  <div class="relative max-w-6xl mx-auto px-6 text-center">
    <!-- Title -->
    <h2 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight drop-shadow-lg">
      Ready to Take Your Dairy Farm to the Next Level?
    </h2>

    <!-- Subtitle -->
    <p class="text-lg md:text-xl text-blue-100 mb-10 max-w-3xl mx-auto leading-relaxed">
      Make your dairy farm smarter, stronger, and more efficient.  
      Join hundreds of farmers already improving their operations with <strong>LB Dairy</strong>.
    </p>
<br>
    <!-- Buttons -->
    <div class="flex-btns">
  @auth
    <a href="{{ url('/dashboard') }}" class="btn btn-dashboard">Access Dashboard</a>
  @else
    <a href="{{ route('register') }}" class="btn btn-join">Join Now</a>
    <a href="{{ route('login') }}" class="btn btn-signin">Sign In</a>
  @endauth
</div>

  </div>
</section>

    
<!-- ================== ABOUT MODAL ================== -->
<div id="aboutModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('aboutModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-info-circle fa-2x"></i>
    </div>
    <h3>About LB Dairy</h3>
    <p class="text-muted text-align">
      LBDAIRY is a web-based platform designed to improve dairy livestock monitoring and management through QR code tagging. It enables effective tracking of animal records and integrates productivity analysis to support data-driven decision-making for the Lucban Agriculture Office.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('aboutModal')">
      Got It
    </button>
  </div>
</div>

<!-- ================== FEATURE MODAL ================== -->
<div id="featureModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('featureModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-star fa-2x"></i>
    </div>
    <h3>Features of LB Dairy</h3>
    <p class="text-muted">
      LB Dairy offers real-time livestock monitoring, production tracking, analytics dashboards, 
      and reporting tools — helping farmers make data-driven decisions for increased productivity 
      and sustainability.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('featureModal')">
      Got It
    </button>
  </div>
</div>

<!-- ================== CONTACT MODAL ================== -->
<div id="contactModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('contactModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-envelope fa-2x"></i>
    </div>
    <h3>Contact Us</h3>
    <p class="text-muted">
      Have questions or feedback? Reach out to LB Dairy via email at <a href="mailto:info@lbdairy.com">info@lbdairy.com</a> 
      or call us at +63 912 345 6789. We're here to help you optimize your farming operations.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('contactModal')">
      Got It
    </button>
  </div>
</div>


<!-- Documentation Modal -->
<div id="documentationModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('documentationModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-book fa-2x"></i>
    </div>
    <h3>Documentation</h3>
    <p class="text-muted">
      Access detailed guides and manuals to get the most out of LB Dairy features.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('documentationModal')">
      Got It
    </button>
  </div>
</div>

<!-- Help Center Modal -->
<div id="helpModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('helpModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-headset fa-2x"></i>
    </div>
    <h3>Help Center</h3>
    <p class="text-muted">
      Get support for technical issues or farm management questions from our expert team.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('helpModal')">
      Got It
    </button>
  </div>
</div>

<!-- Training Modal -->
<div id="trainingModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('trainingModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-chalkboard-teacher fa-2x"></i>
    </div>
    <h3>Training</h3>
    <p class="text-muted">
      Learn to use LB Dairy efficiently through interactive tutorials and training sessions.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('trainingModal')">
      Got It
    </button>
  </div>
</div>

<!-- Livestock Management Modal -->
<div id="livestockModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('livestockModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-dog fa-2x"></i>
    </div>
    <h3>Livestock Management</h3>
    <p class="text-muted">
      Monitor your animals in real-time, track feeding schedules, health, and location to optimize farm operations.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('livestockModal')">
      Got It
    </button>
  </div>
</div>

<!-- Production Tracking Modal -->
<div id="productionModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('productionModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-chart-line fa-2x"></i>
    </div>
    <h3>Production Tracking</h3>
    <p class="text-muted">
      Track milk, eggs, or meat production efficiently and get detailed analytics to improve output.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('productionModal')">
      Got It
    </button>
  </div>
</div>

<!-- Health Monitoring Modal -->
<div id="healthModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('healthModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-heartbeat fa-2x"></i>
    </div>
    <h3>Health Monitoring</h3>
    <p class="text-muted">
      Keep track of livestock health, vaccinations, and illnesses to prevent disease and losses.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('healthModal')">
      Got It
    </button>
  </div>
</div>

<!-- Financial Reports Modal -->
<div id="financialModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('financialModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-file-invoice-dollar fa-2x"></i>
    </div>
    <h3>Financial Reports</h3>
    <p class="text-muted">
      Analyze farm expenses, revenue, and profits with comprehensive reporting tools.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('financialModal')">
      Got It
    </button>
  </div>
</div>

<!-- About Us Modal -->
<div id="aboutUsModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('aboutUsModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-info-circle fa-2x"></i>
    </div>
    <h3>About Us</h3>
    <p class="text-muted">
      A team of BS Information Technology students, led by Mark James Bondoc, developed LBDAIRY for the Lucban Agriculture Office to address the operational constraints of traditional, paper-based livestock record-keeping. The system integrates QR code technology to optimize livestock monitoring, guarantee reliable data recording, and facilitate prompt, data-informed decision-making.

Throughout its development, LBDAIRY has reached key milestones, including the integration of QR code tagging, the development of an extensive data management interface, and the implementation of productivity analysis capabilities. These advancements underscore the system’s capacity to modernize and enhance livestock management operations.

Our mission is to deliver a reliable, data-driven solution that strengthens local livestock management and supports sustainable agricultural development in Lucban.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('aboutUsModal')">
      Got It
    </button>
  </div>
</div>

<!-- Privacy Policy Modal -->
<div id="privacyModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('privacyModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-user-shield fa-2x"></i>
    </div>
    <h3>Privacy Policy</h3>
    <p class="text-muted">
      Review our Privacy Policy to understand how we protect your data and ensure security.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('privacyModal')">
      Got It
    </button>
  </div>
</div>

<!-- Terms of Service Modal -->
<div id="termsModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('termsModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-file-contract fa-2x"></i>
    </div>
    <h3>Terms of Service</h3>
    <p class="text-muted">
      Read our Terms of Service to know the rules and regulations of using LB Dairy solutions.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('termsModal')">
      Got It
    </button>
  </div>
</div>

<!-- Careers Modal -->
<div id="careersModal" class="custom-modal">
  <div class="custom-modal-overlay" onclick="closeModal('careersModal')"></div>
  <div class="custom-modal-content">
    <div class="icon-wrapper mb-4 text-primary">
      <i class="fas fa-briefcase fa-2x"></i>
    </div>
    <h3>Careers</h3>
    <p class="text-muted">
      Explore job opportunities with LB Dairy and join our mission to innovate agriculture.
    </p>
    <button class="btn-modern mt-4" onclick="closeModal('careersModal')">
      Got It
    </button>
  </div>
</div>





    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="mb-4">
                        <img src="/img/Lucban.png" alt="LB Dairy Logo" class="h-10 w-auto" onerror="console.log('Footer logo failed to load');">
                    </div>
                    <p class="text-gray-400">
                        Empowering dairy farmers with smart technology for better livestock management and increased productivity.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Features</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('livestockModal')">Livestock Management</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('productionModal')">Production Tracking</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('healthModal')">Health Monitoring</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('financialModal')">Financial Reports</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('documentationModal')">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('helpModal')">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('contactModal')">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('trainingModal')">Training</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('aboutUsModal')">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('privacyModal')">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('termsModal')">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out" onclick="openModal('careersModal')">Careers</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} LB Dairy. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
<script>
  function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.add("active");
      document.body.style.overflow = "hidden";
    }
  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove("active");
      document.body.style.overflow = "";
    }
  }
</script>

</html>

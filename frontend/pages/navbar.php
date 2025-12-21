<?php
// Determine current path for active links
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$path = rtrim($path, '/'); // remove trailing slash
if ($path === '' || $path === '/index.php' || $path === '/index') $path = '/index';
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container-fluid px-3 px-lg-4">

    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="/index">
      <img src="/assets/images/logo.png" alt="GSO Logo" class="img-fluid me-2" style="max-height:70px; width:auto;">
      <div class="lh-sm">
        <strong class="text-primary" style="font-size: 20px;">GSO BUTARE</strong><br>
        <small class="text-muted" style="font-size: 10px;">S'INSTRUIRE POUR MIEUX SERVIR</small>
      </div>
    </a>

    <!-- Mobile Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-lg-center">
        <li class="nav-item"><a class="nav-link <?= ($path == '/index') ? 'active' : '' ?>" href="/index">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= ($path == '/academics') ? 'active' : '' ?>"
            href="/academics">Academics</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= in_array($path, ['/about', '/alumni', '/gallery']) ? 'active' : '' ?>"
            href="#" id="schoolDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Our School
          </a>
          <ul class="dropdown-menu" aria-labelledby="schoolDropdown">
            <li><a class="dropdown-item <?= ($path == '/about') ? 'active' : '' ?>" href="/about">About us</a></li>
            <li><a class="dropdown-item <?= ($path == '/alumni') ? 'active' : '' ?>" href="/alumni">Alumni</a></li>
            <li><a class="dropdown-item <?= ($path == '/gallery') ? 'active' : '' ?>" href="/gallery">Gallery</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link <?= ($path == '/extracurricular') ? 'active' : '' ?>"
            href="/extracurricular">Extracurricular</a></li>
        <li class="nav-item"><a class="nav-link <?= ($path == '/news') ? 'active' : '' ?>" href="/news">School
            Updates</a></li>
        <li class="nav-item"><a class="nav-link <?= ($path == '/contact') ? 'active' : '' ?>"
            href="/contact">Contacts</a></li>
      </ul>
    </div>
  </div>
</nav>

<style>
  /* Navbar Links */
  .navbar-nav .nav-link {
    color: steelblue !important;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .navbar-nav .nav-link.active {
    color: #09023F !important;
    border-bottom: 2px solid #09023F;
  }

  .navbar-nav .nav-link:hover {
    color: #001A66 !important;
  }

  /* Desktop dropdown hover */
  @media (min-width: 992px) {
    .navbar .dropdown:hover .dropdown-menu {
      display: block;
      margin-top: 0;
    }
  }
</style>
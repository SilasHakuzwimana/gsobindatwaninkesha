<?php
require_once __DIR__ . '/../../../../backend/vendor/autoload.php';
require_once __DIR__ . '/../../../../backend/Services/AuthService.php';

use Services\AuthService;

$token = $_COOKIE['auth_token'] ?? '';
$decoded = AuthService::verify($token);

if (!$decoded) {
  header('Location: /login');
  exit;
}

if (($decoded->role ?? '') !== 'admin') {
  header('Location: /403');
  exit;
}

$adminName = $decoded->full_name ?? 'Admin User';
$adminEmail = $decoded->email ?? 'admin@gsob.rw';
?>

<!-- Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="/assets/css/admin-dash-styles.css">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <!-- Sidebar toggle button (visible on small screens) -->
  <button class="btn btn-primary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar"
    aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
    <i class="fas fa-bars"></i>
  </button>

  <div class="collapse navbar-collapse justify-content-end">
    <div class="navbar-search me-3 p-50 d-none d-lg-flex">
      <input type="text" class="form-control" placeholder="Search...">
      <i class="fas fa-search position-absolute" style="right: 10px; top: 8px;"></i>
    </div>

    <ul class="navbar-nav align-items-center">
      <li class="nav-item me-3">
        <div class="notification-icon position-relative">
          <i class="fas fa-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            3
          </span>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
          <div class="user-avatar me-2">
            <i class="fas fa-user"></i>
          </div>
          <div class="d-none d-md-block text-start">
            <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($adminName); ?></div>
            <div style="font-size: 0.75rem; color: #64748b;"><?php echo htmlspecialchars($adminEmail); ?></div>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser();">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/assets/js/navigator.js" type="text/javascript"></script>
<script src="/assets/js/dash.js" type="text/javascript"></script>
<script src="/assets/js/dashboard.js" type="text/javascript"></script>
<script src="/assets/js/ajax-handler.js" type="text/javascript"></script>
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

$userIdHex = $decoded->sub ?? null;
if (!$userIdHex) {
  header('Location: /login');
  exit;
}
$userId = hex2bin($userIdHex);

// Fetch latest info from DB
$pdo = \Config\Database::getConnection();
$stmt = $pdo->prepare("SELECT fullName, email, role FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Role check
$roleCheck = $user['role'] ?? $decoded->role ?? 'admin';
if ($roleCheck !== 'admin') {
  header('Location: /403');
  exit;
}

//Capitalize first character
$role = ucfirst($roleCheck);

// Safe display values
$adminName  = htmlspecialchars($user['fullName'] ?? $decoded->fullName ?? 'Admin User');
$adminEmail = htmlspecialchars($user['email'] ?? $decoded->email ?? 'admin@gsob.rw');
$role       = htmlspecialchars($roleCheck);
?>

<!-- Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/admin-dash-styles.css">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 py-2">
  <!-- Sidebar toggle button (small screens) -->
  <button class="btn btn-primary d-lg-none me-2" type="button" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Navbar right items (always visible) -->
  <ul class="navbar-nav ms-auto align-items-center">

    <!-- User dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
        <div
          class="user-avatar bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2"
          style="width:35px; height:35px;">
          <i class="fas fa-user"></i>
        </div>
        <div class="d-none d-md-block text-start">
          <div class="fw-semibold" style="font-size:0.9rem;"><?php echo $adminName; ?></div>
          <div class="text-muted" style="font-size:0.75rem;"><?php echo $adminEmail; ?></div>
          <div class="text-muted" style="font-size:0.75rem;"><?php echo $role; ?></div>
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser();">Logout</a></li>
      </ul>
    </li>
  </ul>
</nav>


<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/navigator.js"></script>
<script src="/assets/js/dash.js"></script>
<script src="/assets/js/dashboard.js"></script>
<script src="/assets/js/ajax-handler.js"></script>

<!-- <style>
  /* Professional touch for top navbar */
  .navbar {
    border-bottom: 1px solid #e2e8f0;
  }

  .navbar-nav {
    flex-wrap: wrap;
  }

  .user-avatar i {
    font-size: 0.9rem;
  }

  .dropdown-menu {
    min-width: 200px;
  }
</style> -->
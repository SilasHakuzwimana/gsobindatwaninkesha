<?php
require_once __DIR__ . '/../../../backend/vendor/autoload.php';
require_once __DIR__ . '/../../../backend/Services/AuthService.php';

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

$adminName = htmlspecialchars($decoded->fullName ?? 'Admin User');
$adminEmail = htmlspecialchars($decoded->email ?? 'admin@gsob.rw');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GSOB Admin System</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">


  <!-- Custom CSS -->
  <link rel="stylesheet" type="text/css" href="/assets/css/admin-dash-styles.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/admin.css">

  <style>
  body {
    margin: 0;
    height: 100vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  /* ===== Dashboard Layout ===== */
  .dashboard-container {
    display: flex;
    flex: 1;
    min-height: 0;
    overflow: hidden;
    /* position: relative; */
  }

  /* ===== Sidebar ===== */
  #sidebar {
    width: 20%;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease-in-out;
    /* z-index: 1050; */
    overflow-y: auto;
  }

  #sidebar.collapsed {
    transform: translateX(-100%);
  }

  #sidebar .sidebar-header {
    font-weight: bold;
    padding: 16px;
  }

  .sidebar-menu {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    padding: 0;
  }

  .menu-item {
    padding: 12px 18px;
    display: flex;
    align-items: center;
    color: #333;
    text-decoration: none;
    font-size: 15px;
    transition: 0.3s;
  }

  .menu-item i {
    margin-right: 10px;
    font-size: 16px;
  }

  .menu-item:hover,
  .menu-item.active {
    color: #0d6efd;
  }

  .sidebar-footer {
    padding: 12px 18px;
  }

  /* ===== Main Content ===== */
  .main-content {
    flex-grow: 1;
    overflow-y: auto;
    width: 80%;
    padding: 20px;
    transition: margin-left 0.3s ease-in-out;
  }

  /* ===== Sidebar Toggle Button ===== */
  #sidebarToggle {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1100;
  }

  /* ===== Responsive ===== */
  @media (min-width: 992px) {
    #sidebarToggle {
      display: none;
    }

    #sidebar {
      transform: none !important;
    }
  }

  /* ===== Spinner ===== */
  /* #spinner {
    position: fixed;
    top: 60px;
    left: 240px;
    right: 0;
    bottom: 0;
    display: none;
    justify-content: center;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
  }

  .loading-dots {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .dot {
    width: 12px;
    height: 12px;
    background-color: #007bff;
    border-radius: 50%;
    margin: 0 5px;
    animation: bounce 1.2s infinite ease-in-out;
  }

  .dot:nth-child(2) {
    animation-delay: -0.4s;
  }

  .dot:nth-child(3) {
    animation-delay: -0.8s;
  }

  @keyframes bounce {

    0%,
    80%,
    100% {
      transform: scale(0);
      opacity: 0.5;
    }

    40% {
      transform: scale(1.0);
      opacity: 1;
    }
  } */
  </style>
</head>

<body>

  <!-- Top Navbar -->
  <?php include __DIR__ . '/partials/topnav.php'; ?>

  <!-- Sidebar Toggle (small screens) -->
  <button id="sidebarToggle" class="btn btn-primary d-lg-none">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Dashboard Container -->
  <div class="dashboard-container">

    <!-- Sidebar -->
    <aside id="sidebar">
      <div class="sidebar-header">
        <i class="fas fa-graduation-cap me-2"></i>GSOB Admin
      </div>

      <nav class="sidebar-menu">
        <a href="#" class="menu-item active" data-page="overview"><i class="fas fa-home"></i> Overview</a>
        <a href="#" class="menu-item" data-page="users"><i class="fas fa-users"></i> Users Management</a>
        <a href="#" class="menu-item" data-page="pathways"><i class="fas fa-route"></i> Pathways</a>
        <a href="#" class="menu-item" data-page="streams"><i class="fas fa-project-diagram"></i> Streams</a>
        <a href="#" class="menu-item" data-page="subjects"><i class="fas fa-book"></i> Subjects</a>
        <a href="#" class="menu-item" data-page="documents"><i class="fas fa-file-alt"></i> Documents</a>
        <a href="#" class="menu-item" data-page="gallery"><i class="fas fa-images"></i> Gallery</a>
        <a href="#" class="menu-item" data-page="alumni"><i class="fas fa-user-graduate"></i> Alumni</a>
        <a href="#" class="menu-item" data-page="extra-curricular"><i class="fas fa-running"></i> Extra Curricular</a>
        <a href="#" class="menu-item" data-page="updates"><i class="fas fa-newspaper"></i> School Updates</a>
        <a href="#" class="menu-item" data-page="subscribers"><i class="fas fa-envelope"></i> Subscribers</a>
        <a href="#" class="menu-item" data-page="messages"><i class="fas fa-message"></i> Messages</a>
        <a href="#" class="menu-item" data-page="logs"><i class="fas fa-history"></i> Activity Logs</a>
      </nav>

      <div class="sidebar-footer">
        <a href="#" class="menu-item text-danger" onclick="logoutUser();">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
      <div id="spinner">
        <div class="loading-dots">
          <!-- <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div> -->
        </div>
      </div>

      <div id="content-area" class="p-3">
        <!-- Dynamic content here -->
      </div>
    </main>
  </div>

  <!-- JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/ajax-handler.js"></script>
  <script src="/assets/js/dashboard.js"></script>
  <script src="/assets/js/users.js"></script>
  <script src="/assets/js/subscribers.js"></script>
  <script src="/assets/js/all_messages.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="/assets/js/app.js"></script>
  <script src="/assets/js/charts.js"></script>
  <script src="/assets/js/school_documents.js"></script>
  <script src="/assets/js/gallery.js"></script>
  <script src="/assets/js/alumni.js"></script>
  <script src="/assets/js/extra_activities.js"></script>
  <script src="/assets/js/updates.js"></script>
  <script src="/assets/js/helpers.js"></script>
  <script src="/assets/js/pathways.js"></script>
  <script src="/assets/js/streams.js"></script>
  <script src="/assets/js/subjects.js"></script>

  <script>
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');

  // Toggle sidebar visibility
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
  });

  // Auto-hide sidebar when clicking outside (on small screens)
  document.addEventListener('click', (e) => {
    if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.add('collapsed');
    }
  });

  // Ensure sidebar resets correctly when resizing
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
      sidebar.classList.remove('collapsed');
    }
  });
  </script>

</body>

</html>
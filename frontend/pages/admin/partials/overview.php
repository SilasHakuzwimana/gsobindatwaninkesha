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
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="/assets/css/admin-dash-styles.css">

<!--Dashboard Content -->
<div class="dashboard-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Dashboard Overview</h1>
      <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars(explode(' ', $adminName)[0]); ?>!
        <br />
        Here's
        what's happening today.
      </p>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-content">
        <div class="stat-info">
          <p>Total Users</p>
          <h3 id="usersCount"></h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i>
          </div>
        </div>
        <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
          <i class="fas fa-users"></i>
        </div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-content">
        <div class="stat-info">
          <p>Pathways</p>
          <h3 id="pathways"></h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i>
          </div>
        </div>
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
          <i class="fas fa-route"></i>
        </div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-content">
        <div class="stat-info">
          <p>Documents</p>
          <h3 id="documents"></h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i>
          </div>
        </div>
        <div class="stat-icon" style="background: #f3e8ff; color: #7c3aed;">
          <i class="fas fa-file-alt"></i>
        </div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-content">
        <div class="stat-info">
          <p>Gallery Items</p>
          <h3 id="gallery"></h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i>
          </div>
        </div>
        <div class="stat-icon" style="background: #ffedd5; color: #f59e0b;">
          <i class="fas fa-images"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activities -->
  <div class="card-custom">
    <div class="card-header-custom">
      <h3 class="card-title-custom">
        <i class="fas fa-clock me-2"></i> Recent Activities
      </h3>
      <a href="#" class="text-decoration-none">View All</a>
    </div>
    <div class="activity-items-container">
      <!-- JS will populate activity items here -->
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="page-header mt-4">
    <h3 class="card-title-custom">
      <i class="fas fa-bolt me-2"></i> Quick Actions
    </h3>
  </div>

  <div class="quick-actions">
    <div class="action-card" onclick="setActiveMenu(document.querySelector('[onclick*=Users]'))">
      <div class="action-icon" style="color: #2563eb;">
        <i class="fas fa-user-plus"></i>
      </div>
      <div class="action-title">Add New User</div>
    </div>

    <div class="action-card" onclick="setActiveMenu(document.querySelector('[onclick*=Pathways]'))">
      <div class="action-icon" style="color: #10b981;">
        <i class="fas fa-route"></i>
      </div>
      <div class="action-title">Create Pathway</div>
    </div>

    <div class="action-card" onclick="setActiveMenu(document.querySelector('[onclick*=Documents]'))">
      <div class="action-icon" style="color: #7c3aed;">
        <i class="fas fa-upload"></i>
      </div>
      <div class="action-title">Upload Document</div>
    </div>

    <div class="action-card" onclick="setActiveMenu(document.querySelector('[onclick*=Gallery]'))">
      <div class="action-icon" style="color: #f59e0b;">
        <i class="fas fa-camera"></i>
      </div>
      <div class="action-title">Add to Gallery</div>
    </div>

    <div class="action-card" onclick="setActiveMenu(document.querySelector('[onclick*=Updates]'))">
      <div class="action-icon" style="color: #ec4899;">
        <i class="fas fa-newspaper"></i>
      </div>
      <div class="action-title">Post Update</div>
    </div>

    <div class="action-card" onclick="setActiveMenu(document.querySelector('[onclick*=Activity]'))">
      <div class="action-icon" style="color: #06b6d4;">
        <i class="fas fa-chart-bar"></i>
      </div>
      <div class="action-title">View Reports</div>
    </div>
  </div>

  <!-- User Statistics -->
  <div class="mb-3">
    <div class="d-flex justify-content-between mb-2">
      <span>Admins</span>
      <span id="admins-count" class="fw-bold">0</span>
    </div>
    <div class="progress" style="height: 10px;">
      <div id="admins-bar" class="progress-bar" style="width: 0%; background: #2563eb;"></div>
    </div>
  </div>
  <div class="mb-3">
    <div class="d-flex justify-content-between mb-2">
      <span>Students</span>
      <span id="students-count" class="fw-bold">0</span>
    </div>
    <div class="progress" style="height: 10px;">
      <div id="students-bar" class="progress-bar" style="width: 0%; background: #2563eb;"></div>
    </div>
  </div>

  <div class="mb-3">
    <div class="d-flex justify-content-between mb-2">
      <span>Teachers</span>
      <span id="teachers-count" class="fw-bold">0</span>
    </div>
    <div class="progress" style="height: 10px;">
      <div id="teachers-bar" class="progress-bar" style="width: 0%; background: #10b981;"></div>
    </div>
  </div>

  <div class="mb-3">
    <div class="d-flex justify-content-between mb-2">
      <span>Alumni</span>
      <span id="alumni-count" class="fw-bold">0</span>
    </div>
    <div class="progress" style="height: 10px;">
      <div id="alumni-bar" class="progress-bar" style="width: 0%; background: #7c3aed;"></div>
    </div>
  </div>

  <div>
    <div class="d-flex justify-content-between mb-2">
      <span>Guests</span>
      <span id="guests-count" class="fw-bold">0</span>
    </div>
    <div class="progress" style="height: 10px;">
      <div id="guests-bar" class="progress-bar" style="width: 0%; background: #f59e0b;"></div>
    </div>
  </div>

  <!-- Content Overview -->
  <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
    <div>
      <i class="fas fa-book me-2" style="color: #2563eb;"></i>
      <span>Total Subjects</span>
    </div>
    <span id="total-subjects" class="fw-bold">0</span>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
    <div>
      <i class="fas fa-stream me-2" style="color: #10b981;"></i>
      <span>Active Streams</span>
    </div>
    <span id="active-streams" class="fw-bold">0</span>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
    <div>
      <i class="fas fa-file-pdf me-2" style="color: #7c3aed;"></i>
      <span>Published Documents</span>
    </div>
    <span id="published-docs" class="fw-bold">0</span>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
    <div>
      <i class="fas fa-envelope me-2" style="color: #7c3aed;"></i>
      <span>Messages</span>
    </div>
    <span id="messages" class="fw-bold">0</span>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-5"
    style="border-bottom: 1px solid #e2e8f0; padding-bottom: 2px;">

    <div>
      <i class="fas fa-envelope-open-text me-2" style="color: #f59e0b;"></i>
      <span>Newsletter Subscribers</span>
    </div>
    <span id="newsletter-subs" class="fw-bold">0</span>
  </div>
</div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<!-- Global Toast JS -->
<script src="/assets/js/navigator.js" type="text/javascript"></script>

<!-- Custom JS -->
<script src="/assets/js/dash.js" type="text/javascript"></script>
<script src="/assets/js/dashboard.js" type="text/javascript"></script>
<script src="/assets/js/ajax-handler.js" type="text/javascript"></script>
<!-- <script src="/assets/js/overview.js" type="text/javascript"></script> -->
<!-- <script>
document.addEventListener("DOMContentLoaded", initOverview);
</script> -->
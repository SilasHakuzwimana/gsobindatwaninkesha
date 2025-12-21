<?php
require_once __DIR__ . '/../../../../backend/vendor/autoload.php';
require_once __DIR__ . '/../../../../backend/Services/AuthService.php';

use Services\AuthService;

// Get JWT token (assuming stored in cookie)
$token = $_COOKIE['auth_token'] ?? '';

// Verify token
$decoded = AuthService::verify($token);

if (!$decoded) {
  header('Location: /login');
  exit;
}

if ($decoded->role !== 'admin') {
  header('HTTP/1.1 403 Forbidden');
  echo "Access denied: Admins only.";
  exit;
}

// Get admin user info
$adminName = $decoded->fullName ?? 'Admin User';
$adminEmail = $decoded->email ?? 'admin@gsob.rw';
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
          <h3 id="users">2,345</h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i> +12% from last month
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
          <h3>8</h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i> +2 new pathways
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
          <h3>156</h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i> +18 this week
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
          <h3>892</h3>
          <div class="stat-change text-success">
            <i class="fas fa-arrow-up"></i> +45 this month
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
    <div>
      <div class="activity-item">
        <div class="activity-icon" style="background: #dbeafe; color: #2563eb;">
          <i class="fas fa-file-upload"></i>
        </div>
        <div class="activity-details">
          <div>
            <span class="activity-user">John Doe</span>
            <span class="activity-action">uploaded a document</span>
          </div>
          <div class="activity-time">2 minutes ago</div>
        </div>
      </div>

      <div class="activity-item">
        <div class="activity-icon" style="background: #dcfce7; color: #10b981;">
          <i class="fas fa-user-plus"></i>
        </div>
        <div class="activity-details">
          <div>
            <span class="activity-user">Jane Smith</span>
            <span class="activity-action">registered as student</span>
          </div>
          <div class="activity-time">15 minutes ago</div>
        </div>
      </div>

      <div class="activity-item">
        <div class="activity-icon" style="background: #f3e8ff; color: #7c3aed;">
          <i class="fas fa-route"></i>
        </div>
        <div class="activity-details">
          <div>
            <span class="activity-user">Admin</span>
            <span class="activity-action">created new pathway</span>
          </div>
          <div class="activity-time">1 hour ago</div>
        </div>
      </div>

      <div class="activity-item">
        <div class="activity-icon" style="background: #ffedd5; color: #f59e0b;">
          <i class="fas fa-images"></i>
        </div>
        <div class="activity-details">
          <div>
            <span class="activity-user">Mike Johnson</span>
            <span class="activity-action">updated gallery</span>
          </div>
          <div class="activity-time">2 hours ago</div>
        </div>
      </div>

      <div class="activity-item">
        <div class="activity-icon" style="background: #fce7f3; color: #ec4899;">
          <i class="fas fa-user-graduate"></i>
        </div>
        <div class="activity-details">
          <div>
            <span class="activity-user">Sarah Williams</span>
            <span class="activity-action">joined as alumni</span>
          </div>
          <div class="activity-time">3 hours ago</div>
        </div>
      </div>
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

  <!-- System Stats -->
  <div class="row mt-4">
    <div class="col-lg-6 mb-4">
      <div class="card-custom">
        <div class="card-header-custom">
          <h3 class="card-title-custom">
            <i class="fas fa-users-cog me-2"></i> User Statistics
          </h3>
        </div>
        <div class="p-4">
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-2">
              <span>Students</span>
              <span class="fw-bold">1,856 (79%)</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar" style="width: 79%; background: #2563eb;"></div>
            </div>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between mb-2">
              <span>Teachers</span>
              <span class="fw-bold">245 (10%)</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar" style="width: 10%; background: #10b981;"></div>
            </div>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between mb-2">
              <span>Alumni</span>
              <span class="fw-bold">189 (8%)</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar" style="width: 8%; background: #7c3aed;"></div>
            </div>
          </div>

          <div>
            <div class="d-flex justify-content-between mb-2">
              <span>Guests</span>
              <span class="fw-bold">55 (3%)</span>
            </div>
            <div class="progress" style="height: 10px;">
              <div class="progress-bar" style="width: 3%; background: #f59e0b;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card-custom">
        <div class="card-header-custom">
          <h3 class="card-title-custom">
            <i class="fas fa-chart-line me-2"></i> Content Overview
          </h3>
        </div>
        <div class="p-4">
          <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
            style="border-bottom: 1px solid #e2e8f0;">
            <div>
              <i class="fas fa-book me-2" style="color: #2563eb;"></i>
              <span>Total Subjects</span>
            </div>
            <span class="fw-bold">45</span>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
            style="border-bottom: 1px solid #e2e8f0;">
            <div>
              <i class="fas fa-stream me-2" style="color: #10b981;"></i>
              <span>Active Streams</span>
            </div>
            <span class="fw-bold">12</span>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
            style="border-bottom: 1px solid #e2e8f0;">
            <div>
              <i class="fas fa-file-pdf me-2" style="color: #7c3aed;"></i>
              <span>Published Documents</span>
            </div>
            <span class="fw-bold">156</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <div>
              <i class="fas fa-envelope-open-text me-2" style="color: #f59e0b;"></i>
              <span>Newsletter Subscribers</span>
            </div>
            <span class="fw-bold">1,234</span>
          </div>
        </div>
      </div>
    </div>
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
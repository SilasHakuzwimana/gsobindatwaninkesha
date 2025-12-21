<?php
require_once __DIR__ . '/../../../../backend/vendor/autoload.php';
require_once __DIR__ . '/../../../../backend/Services/AuthService.php';

use Services\AuthService;

// --- Verify token and role
$token = $_COOKIE['auth_token'] ?? '';
$decoded = AuthService::verify($token);
if (!$decoded) {
  header('Location: /login');
  exit;
}
if (($decoded->role ?? '') !== 'admin') {
  header('Location: /unauthorized.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard | Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
      <h2>Users Management</h2>
      <div>
        <span class="badge bg-secondary me-3">Total: <span id="userCount">...</span></span>
        <button class="btn btn-primary" id="addUserBtn"><i class="fa fa-plus"></i> Add User</button>
      </div>
    </div>

    <!-- Search -->
    <input type="text" id="searchUser" class="form-control mb-3" placeholder="Search users..." />

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <!-- <th>ID</th> -->
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Role</th>
            <th>Status</th>
            <th>Create At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="usersTableBody">
          <tr>
            <td colspan="8" class="text-center">Loading users...</td>
          </tr>
        </tbody>
      </table>
      <div id="pagination" class="mt-3"></div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="userForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="userId" />
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Gender</label>
              <select class="form-select" id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" id="role" name="role" required>
                <option value="student">Student</option>
                <option value="alumni">Alumni</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select class="form-select" id="status" name="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    <div id="toast" class="toast align-items-center text-white bg-success border-0">
      <div class="d-flex">
        <div class="toast-body" id="toastMsg"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/ajax-handler.js"></script>
  <script src="/assets/js/users.js"></script>
  <script>
  // Set API_BASE to match your backend
  window.API_BASE = "http://localhost:8000/api";

  document.addEventListener("DOMContentLoaded", () => {
    if (typeof initUsersJs === "function") {
      initUsersJs();
    } else {
      console.error("❌ users.js not loaded");
    }
  });
  </script>
</body>

</html>
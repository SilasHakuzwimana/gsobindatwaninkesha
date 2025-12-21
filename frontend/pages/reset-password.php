<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Reset Password | GSOB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!--Custom css  -->
  <link rel="stylesheet" type="text/css" href="/assets/css/complete_styles.css">
</head>

<body class="bg-light">

  <!-- Reset Password Page -->
  <div id="resetPasswordPage" class="auth-wrapper hidden">
    <div class="auth-card">
      <div class="text-center">
        <div class="auth-icon">
          <i class="fas fa-lock-open"></i>
        </div>
        <h2 class="mb-2">Reset Password</h2>
        <p class="text-muted mb-4">Create a new strong password</p>
      </div>

      <form id="resetPasswordForm">
        <div class="mb-3">
          <label class="form-label">
            <i class="fas fa-lock"></i> New Password
          </label>
          <input type="password" class="form-control" placeholder="Enter new password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">
            <i class="fas fa-lock"></i> Confirm Password
          </label>
          <input type="password" class="form-control" placeholder="Confirm new password" required>
        </div>

        <div class="password-requirements">
          <p class="fw-bold mb-2">Password must contain:</p>
          <ul>
            <li><i class="fas fa-check"></i> At least 8 characters</li>
            <li><i class="fas fa-check"></i> One uppercase letter</li>
            <li><i class="fas fa-check"></i> One lowercase letter</li>
            <li><i class="fas fa-check"></i> One number</li>
          </ul>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 my-3">
          <i class="fas fa-save me-2"></i> Reset Password
        </button>

        <div class="text-center">
          <a href="/login" onclick="showPage('loginPage')" class="text-muted text-decoration-none">
            <i class="fas fa-arrow-left me-2"></i> Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="/assets/js/navigator.js" type="text/javascript"></script>

</body>

</html>
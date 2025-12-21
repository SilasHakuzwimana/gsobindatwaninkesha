<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Forgot Password | GSOB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!--Custom css  -->
  <link rel="stylesheet" type="text/css" href="/assets/css/complete_styles.css">
</head>

<body>

  <!-- Forgot Password Page -->
  <div id="forgotPasswordPage" class="auth-wrapper hidden">
    <div class="auth-card">
      <div class="text-center">
        <div class="auth-icon">
          <i class="fas fa-key"></i>
        </div>
        <h2 class="mb-2">Forgot Password?</h2>
        <p class="text-muted mb-4">No worries! Enter your email and we'll send you a reset link</p>
      </div>

      <form id="forgotPasswordForm">
        <div class="mb-4">
          <label class="form-label">
            <i class="fas fa-envelope"></i> Email Address
          </label>
          <input type="email" id="email" class="form-control" placeholder="kamana@gmail.com" required>
          <div class="invalid-feedback"> Please enter a valid email.
          </div>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 mb-3">
          <i class="fas fa-paper-plane me-2"></i> Send Reset Link
        </button>

        <div class="text-center">
          <a href="/login" class="text-muted text-decoration-none">
            <i class="fas fa-arrow-left me-2"></i> Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="/assets/js/forgot-pass.js"></script>
</body>

</html>
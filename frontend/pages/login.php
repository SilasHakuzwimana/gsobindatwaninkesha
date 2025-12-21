<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Login | GSOB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<!--Custom css  -->
<link rel="stylesheet" type="text/css" href="/assets/css/complete_styles.css">
<style>
.password-toggle {
  position: absolute;
  top: 70%;
  right: 2px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #6c757d;
}

.password-toggle:hover {
  color: var(--primary-color);
}
</style>
</head>

<body class="bg-light">

  <!-- Login Page -->
  <div id="loginPage" class="auth-wrapper hidden">
    <div class="auth-card">
      <div class="text-center">
        <div class="auth-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h2 class="mb-2">GSOB Login</h2>
        <p class="text-muted mb-4">Welcome back! Please login to your account</p>
      </div>

      <form id="loginForm">
        <div class="mb-3">
          <label class="form-label">
            <i class="fas fa-envelope"></i> Email Address
          </label>
          <input type="email" class="form-control" placeholder="kamana@gmail.com" id="email" required>
          <div class="invalid-feedback"> Please enter a valid email.
          </div>

          <div class="mb-3 position-relative">
            <label class="form-label">
              <i class="fas fa-lock"></i> Password
            </label>
            <input type="password" class="form-control pe-5" id="password" placeholder="********" required disabled>
            <div class="invalid-feedback">Please enter a valid password.</div>
            <span class="password-toggle pe-3" id="togglePassword">
              <i class="fas fa-eye"></i>
            </span>
          </div>

        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rememberMe">
            <label class="form-check-label" for="rememberMe">
              Remember me
            </label>
          </div>
          <a href="/forgot-password" class="text-decoration-none">Forgot Password?</a>
        </div>

        <button type="submit" id="submit" class="btn btn-primary-custom w-100 mb-3">
          <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>

        <div class="text-center">
          <p class="text-muted">
            Don't have an account?
            <a href="/register" class="text-decoration-none fw-bold">Register
              here</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!--Gloab Toast JS  -->
  <script src="/assets/js/navigator.js" type="text/javascript"></script>

  <!-- Custom JS -->
  <script src="/assets/js/login-pass.js"></script>
</body>

</html>
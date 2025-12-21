<!-- 401.php - Unauthorized -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>401 - Unauthorized | GSOB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  body::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      transform: scale(1);
      opacity: 0.5;
    }

    50% {
      transform: scale(1.1);
      opacity: 0.8;
    }
  }

  .error-container {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
    animation: slideIn 0.6s ease-out;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .error-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
    animation: bounce 2s ease-in-out infinite;
  }

  @keyframes bounce {

    0%,
    100% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-10px);
    }
  }

  .error-icon i {
    font-size: 60px;
    color: #ffffff;
  }

  .error-code {
    font-size: 72px;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -2px;
  }

  .btn-custom {
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .btn-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
  }

  .btn-custom:hover::before {
    left: 100%;
  }

  .btn-custom:hover {
    transform: translateY(-3px);
  }

  .btn-login {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
  }

  .btn-login:hover {
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
    color: #ffffff;
  }

  .btn-home {
    background: #ffffff;
    color: #667eea;
    border: 2px solid #667eea;
  }

  .btn-home:hover {
    background: #667eea;
    color: #ffffff;
    border-color: #667eea;
  }

  .error-details {
    background: linear-gradient(135deg, #fee 0%, #fdd 100%);
    border-left: 4px solid #ff6b6b;
  }

  .info-badge {
    background: linear-gradient(135deg, #e7f3ff 0%, #d6eaff 100%);
    color: #0c5460;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-6">
        <div class="error-container p-4 p-md-5">
          <div class="error-icon">
            <i class="bi bi-person-lock"></i>
          </div>

          <h1 class="error-code text-center mb-3">401</h1>
          <h2 class="h3 fw-bold text-center text-dark mb-3">Authentication Required</h2>
          <p class="text-center text-muted mb-4 px-2">
            You need to be logged in to access this page. Please sign in with your credentials to continue.
          </p>

          <div class="error-details rounded p-3 p-md-4 mb-4">
            <h4 class="h6 fw-semibold text-danger mb-3">
              <i class="bi bi-info-circle-fill me-2"></i>
              Why am I seeing this?
            </h4>
            <ul class="mb-0 text-danger small">
              <li class="mb-2">You are not currently logged in</li>
              <li class="mb-2">Your session may have expired</li>
              <li class="mb-2">Your authentication token is invalid or missing</li>
              <li class="mb-0">You were automatically logged out for security reasons</li>
            </ul>
          </div>

          <div class="d-grid gap-3 d-md-flex justify-content-md-center mb-4">
            <a href="/login" class="btn btn-custom btn-login">
              <i class="bi bi-box-arrow-in-right me-2"></i>
              <span>Sign In</span>
            </a>
            <a href="/" class="btn btn-custom btn-home">
              <i class="bi bi-house-fill me-2"></i>
              <span>Go Home</span>
            </a>
          </div>

          <div class="text-center my-4">
            <div class="d-inline-flex align-items-center">
              <hr class="flex-grow-1 border" style="width: 80px;">
              <span class="px-3 text-muted small fw-medium">OR</span>
              <hr class="flex-grow-1 border" style="width: 80px;">
            </div>
          </div>

          <div class="text-center mb-3">
            <p class="text-muted small mb-2">Don't have an account yet?</p>
            <a href="/register" class="text-decoration-none fw-semibold" style="color: #667eea;">
              Create a free account <i class="bi bi-arrow-right"></i>
            </a>
          </div>

          <div class="d-flex justify-content-center mb-4">
            <div class="info-badge d-inline-flex align-items-center gap-2 px-3 py-2">
              <i class="bi bi-shield-check"></i>
              <span class="small">Your data is protected with enterprise-grade security</span>
            </div>
          </div>

          <div class="border-top pt-4 text-center">
            <p class="text-muted small mb-0">
              Having trouble signing in?
              <a href="/forgot-password" class="text-decoration-none fw-semibold" style="color: #667eea;">Reset your
                password</a> or
              <a href="/contact" class="text-decoration-none fw-semibold" style="color: #667eea;">contact support</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<!-- ================================================================ -->
<!-- 403.php - Forbidden -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>403 - Access Forbidden | GSOB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  body::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      transform: scale(1);
      opacity: 0.5;
    }

    50% {
      transform: scale(1.1);
      opacity: 0.8;
    }
  }

  .error-container {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
    animation: slideIn 0.6s ease-out;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .error-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
    animation: bounce 2s ease-in-out infinite;
  }

  @keyframes bounce {

    0%,
    100% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-10px);
    }
  }

  .error-icon i {
    font-size: 60px;
    color: #ffffff;
  }

  .error-code {
    font-size: 72px;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -2px;
  }

  .btn-custom {
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .btn-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
  }

  .btn-custom:hover::before {
    left: 100%;
  }

  .btn-custom:hover {
    transform: translateY(-3px);
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
  }

  .btn-primary-custom:hover {
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
    color: #ffffff;
  }

  .alert-custom {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe9a6 100%);
    border-left: 4px solid #ffc107;
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-6">
        <div class="error-container p-4 p-md-5">
          <div class="error-icon">
            <i class="bi bi-shield-lock-fill"></i>
          </div>

          <h1 class="error-code text-center mb-3">403</h1>
          <h2 class="h3 fw-bold text-center text-dark mb-3">Access Forbidden</h2>
          <p class="text-center text-muted mb-4 px-2">
            Sorry, you don't have permission to access this resource. This area is restricted and requires proper
            authorization.
          </p>

          <div class="alert-custom rounded p-3 p-md-4 mb-4">
            <h4 class="h6 fw-semibold mb-3" style="color: #856404;">
              <i class="bi bi-exclamation-triangle-fill me-2" style="color: #ffc107;"></i>
              Why am I seeing this?
            </h4>
            <ul class="mb-0 small" style="color: #856404;">
              <li class="mb-2">You may not have the necessary permissions</li>
              <li class="mb-2">The resource might be restricted to certain users</li>
              <li class="mb-2">Your session may have expired</li>
              <li class="mb-0">Access may be limited to specific IP addresses</li>
            </ul>
          </div>

          <div class="d-grid d-md-block text-center mb-4">
            <a href="/index" class="btn btn-custom btn-primary-custom">
              <i class="bi bi-house-fill me-2"></i>
              <span>Back to Home</span>
            </a>
          </div>

          <div class="border-top pt-4 text-center">
            <p class="text-muted small mb-0">
              Need help? <a href="#" class="text-decoration-none fw-semibold" style="color: #667eea;">Contact our
                support team</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
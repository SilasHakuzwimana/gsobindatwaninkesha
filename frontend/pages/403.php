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
    min-height: 150vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  body::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    /* width: 200%; */
    height: 100%;
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Verify OTP | GSOB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!--Custom css  -->
  <link rel="stylesheet" type="text/css" href="/assets/css/complete_styles.css">
</head>

<body class="bg-light">

  <!-- Verify OTP Page -->
  <div id="verifyOtpPage" class="auth-wrapper hidden">
    <div class="auth-card">
      <div class="text-center">
        <div class="auth-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h2 class="mb-2">Verify OTP</h2>
        <p class="text-muted mb-4">Enter the 6-digit code sent to your email</p>
      </div>

      <form id="otpForm">
        <div class="otp-inputs">
          <input type="text" id="otp-1" class="otp-input" maxlength="1" pattern="[0-9]">
          <input type="text" id="otp-2" class="otp-input" maxlength="1" pattern="[0-9]" disabled>
          <input type="text" id="otp-3" class="otp-input" maxlength="1" pattern="[0-9]" disabled>
          <input type="text" id="otp-4" class="otp-input" maxlength="1" pattern="[0-9]" disabled>
          <input type="text" id="otp-5" class="otp-input" maxlength="1" pattern="[0-9]" disabled>
          <input type="text" id="otp-6" class="otp-input" maxlength="1" pattern="[0-9]" disabled>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 mb-3">
          <i class="fas fa-check-circle me-2"></i> Verify
        </button>

        <div class="text-center mt-3">
          <a href="/login" class="text-muted text-decoration-none">
            <i class="fas fa-arrow-left me-2"></i> Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!--Gloab Toast JS  -->
  <script src="/assets/js/navigator.js" type="text/javascript"></script>

  <!-- Custom JS -->
  <script src="/assets/js/verify.js" type="text/javascript"></script>
</body>

</html>
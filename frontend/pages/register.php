<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Register | GSOB</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <!-- Include intl-tel-input CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">

  <!-- Include intl-tel-input JS -->
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>

  <!-- Include utils.js for formatting and validation -->
  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.min.js"></script>

  <!--Custom css  -->
  <link rel="stylesheet" type="text/css" href="/assets/css/complete_styles.css">
</head>

<body class="bg-light">
  <!-- Register Page -->
  <div id="registerPage" class="auth-wrapper hidden">
    <div class="auth-card auth-card-wide">
      <div class="text-center">
        <div class="auth-icon">
          <i class="fas fa-user-plus"></i>
        </div>
        <h2 class="mb-2">Create Account</h2>
        <p class="text-muted mb-4">Join GSOB community today</p>
      </div>

      <form id="registerForm">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="fas fa-user"></i> Full Name
            </label>
            <input type="text" name="fullName" id="fullName" class="form-control" placeholder="Kamana Jean" required>
            <div class="invalid-feedback"> Please enter your valid full name.
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="fas fa-envelope"></i> Email
            </label>
            <input type="email" name="email" id="email" class="form-control" placeholder="kamana@gmail.com" required
              disabled>
            <div class="invalid-feedback"> Please enter a valid email.
            </div>
          </div>

          <div class="col-md-6 mb-3 position-relative">
            <label class="form-label">
              <i class="fas fa-lock"></i> Password
            </label>
            <input type="password" class="form-control" name="password" id="password" placeholder="********" required
              disabled>
            <div class="invalid-feedback"> Please enter a strong password.
            </div>
            <span class="password-toggle" id="togglePassword">
              <i class="fas fa-eye"></i>
            </span>
            <div class="mt-2">
              <small>Password Strength: <span id="passwordStrength">Weak</span></small>
              <div class="progress">
                <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-3 position-relative">
            <label class="form-label">
              <i class="fas fa-lock"></i> Confirm Password
            </label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="*********" required disabled>
            <span class="password-toggle-confirm" id="toggleConfirmPassword">
              <i class="fas fa-eye"></i>
            </span>
            <div class="mt-2">
              <small id="confirmPasswordMessage" class="text-danger d-none">Passwords do not match</small>
            </div>
            <div class="invalid-feedback"> Please enter a matching password.
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="fas fa-venus-mars"></i> Gender
            </label>
            <select class="form-select" id="gender" name="gender" disabled>
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <div class="invalid-feedback">Please select your gender.</div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="fas fa-phone"></i> Phone
            </label>
            <br />
            <input type="tel" name="phone" id="phone" class="form-control" placeholder="+250 XXX XXX XXX" disabled>
            <div class="invalid-feedback">Please enter valid phone.</div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="fas fa-calendar"></i> Date of Birth
            </label>
            <input type="date" name="dob" id="dob" class="form-control" disabled>
            <div class="invalid-feedback"> Please select a valid date.
            </div>
          </div>
        </div>

        <button type="submit" id="submit" class="btn btn-primary-custom w-100 mb-3">
          <i class="fas fa-user-plus me-2"></i> Register
        </button>

        <div class="text-center">
          <p class="text-muted">
            Already have an account?
            <a href="/login" class="text-decoration-none fw-bold">Login here</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!--Gloab Toast JS  -->
  <script src="/assets/js/navigator.js" type="text/javascript"></script>

  <!-- Custom JS -->
  <script src="/assets/js/phone.js" type="text/javascript"></script>
  <script src="/assets/js/register.js" type="text/javascript"></script>
  <script src="/assets/js/enable.js" type="text/javascript"></script>
</body>

</html>
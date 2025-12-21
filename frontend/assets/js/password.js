document.addEventListener('DOMContentLoaded', function () {
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');
  const togglePassword = document.getElementById('togglePassword');
  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
  const strengthBar = document.getElementById('strengthBar');
  const passwordStrengthText = document.getElementById('passwordStrength');
  const confirmMessage = document.getElementById('confirmPasswordMessage');
  const form = document.getElementById('registerForm');

  // Show/Hide Password
  if (password && togglePassword) {
    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });
  }

  if (confirmPassword && toggleConfirmPassword) {
    toggleConfirmPassword.addEventListener('click', function () {
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });
  }

  // Password strength & validation
  if (password && strengthBar && passwordStrengthText) {
    password.addEventListener('input', function () {
      const score = getPasswordScore(password.value);
      updateStrength(password.value);
      if (confirmPassword) validateConfirmPassword();
    });
  }

  if (confirmPassword && confirmMessage) {
    confirmPassword.addEventListener('input', validateConfirmPassword);
  }

  function getPasswordScore(val) {
    return [val.length >= 8, /[A-Z]/.test(val), /[a-z]/.test(val), /[0-9]/.test(val), /[^A-Za-z0-9]/.test(val)].filter(Boolean).length;
  }

  function updateStrength(val) {
    if (!strengthBar || !passwordStrengthText) return;
    const score = getPasswordScore(val);
    const percent = (score / 5) * 100;
    strengthBar.style.width = percent + '%';
    if (score <= 2) {
      passwordStrengthText.textContent = 'Weak';
      strengthBar.className = 'progress-bar bg-danger';
    } else if (score <= 4) {
      passwordStrengthText.textContent = 'Moderate';
      strengthBar.className = 'progress-bar bg-warning';
    } else {
      passwordStrengthText.textContent = 'Strong';
      strengthBar.className = 'progress-bar bg-success';
    }
    return score;
  }

  function validateConfirmPassword() {
    if (!confirmPassword || !password || !confirmMessage) return false;
    if (confirmPassword.value === password.value) {
      confirmMessage.classList.add('d-none');
      return true;
    } else {
      confirmMessage.classList.remove('d-none');
      return false;
    }
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      const score = password ? getPasswordScore(password.value) : 0;
      if (score < 5) {
        e.preventDefault();
        alert('Password is not strong enough. Please make it stronger!');
      } else if (!validateConfirmPassword()) {
        e.preventDefault();
        alert('Passwords do not match!');
      }
    });
  }
});

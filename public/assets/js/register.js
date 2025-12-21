document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registerForm');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');
  const togglePassword = document.getElementById('togglePassword');
  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
  const strengthBar = document.getElementById('strengthBar');
  const passwordStrengthText = document.getElementById('passwordStrength');
  const confirmMessage = document.getElementById('confirmPasswordMessage');
  const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

  if (!form || !password || !confirmPassword || !submitBtn) {
    console.error('Register form elements missing!');
    return;
  }

  // Disable submit initially
  submitBtn.disabled = true;

  // Toggle password visibility
  togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.querySelector('i').classList.toggle('fa-eye');
    togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
  });

  toggleConfirmPassword.addEventListener('click', () => {
    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPassword.setAttribute('type', type);
    toggleConfirmPassword.querySelector('i').classList.toggle('fa-eye');
    toggleConfirmPassword.querySelector('i').classList.toggle('fa-eye-slash');
  });

  // Password strength check
  function getPasswordScore(val) {
    return [val.length >= 8, /[A-Z]/.test(val), /[a-z]/.test(val), /[0-9]/.test(val), /[^A-Za-z0-9]/.test(val)].filter(Boolean).length;
  }

  function updateStrength(val) {
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
    if (confirmPassword.value === password.value) {
      confirmMessage.classList.add('d-none');
      return true;
    } else {
      confirmMessage.classList.remove('d-none');
      return false;
    }
  }

  // Enable submit if all fields valid
  function validateForm() {
    const score = getPasswordScore(password.value);
    const passwordsMatch = validateConfirmPassword();
    const allRequiredFilled = Array.from(form.querySelectorAll('input[required], select[required]')).every(input => input.value.trim() !== '');
    submitBtn.disabled = !(score === 5 && passwordsMatch && allRequiredFilled);
  }

  password.addEventListener('input', () => {
    updateStrength(password.value);
    validateForm();
  });

  confirmPassword.addEventListener('input', validateForm);
  form.querySelectorAll('input[required], select[required]').forEach(input => {
    input.addEventListener('input', validateForm);
  });

  // Form submission via AJAX
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Registering...`;

    // Collect all form fields dynamically
    const formData = new FormData(form);
    const payload = {};
    formData.forEach((value, key) => {
      payload[key] = value;
    });

    fetch('http://localhost:8000/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showSlidingToast(data.message || 'Registration successful!', 'success');
          form.reset();
          strengthBar.style.width = '0%';
          passwordStrengthText.textContent = '';
          confirmMessage.classList.add('d-none');
          submitBtn.disabled = true;

          if (data.redirect_url) {
            setTimeout(() => window.location.href = data.redirect_url, 1500);
          }
          return;
        } else {
          if (data.errors) {
            for (const key in data.errors) {
              showSlidingToast(data.errors[key], 'danger');
            }
          } else {
            showSlidingToast(data.message || 'Registration failed.', 'danger');
          }
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showSlidingToast('Network error. Please try again later.', 'danger');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-user-plus me-2"></i> Register`;
      });
  });
});

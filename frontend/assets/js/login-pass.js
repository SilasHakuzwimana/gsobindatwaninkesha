document.addEventListener('DOMContentLoaded', function () {
  // Get elements safely
  const form = document.getElementById('loginForm');
  const email = document.getElementById('email');
  const password = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

  if (!form || !email || !password || !submitBtn) {
    console.error('Login form elements missing!');
    return; // Stop if required elements are missing
  }

  // Disable submit initially
  submitBtn.disabled = true;

  // Toast creator
  function showToast(message, type = 'success') {
    const toastContainer = document.createElement('div');
    toastContainer.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toastContainer.setAttribute('role', 'alert');
    toastContainer.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
    document.body.appendChild(toastContainer);
    const toast = new bootstrap.Toast(toastContainer, { delay: 4000 });
    toast.show();
    toastContainer.addEventListener('hidden.bs.toast', () => toastContainer.remove());
  }

  // Form validation helper
  function validateForm() {
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    const passwordValid = password.value.length >= 8;
    submitBtn.disabled = !(emailValid && passwordValid);
  }

  // Toggle password visibility
  if (togglePassword) {
    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      const icon = this.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      }
    });
  }

  // Enable password after valid email
  email.addEventListener('input', function () {
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    if (emailValid) {
      password.removeAttribute('disabled');
      email.classList.remove('is-invalid');
      email.classList.add('is-valid');
    } else {
      password.setAttribute('disabled', true);
      email.classList.remove('is-valid');
      email.classList.add('is-invalid');
    }
    validateForm();
  });

  // Password validation
  password.addEventListener('input', function () {
    if (password.value.length >= 8) {
      password.classList.remove('is-invalid');
      password.classList.add('is-valid');
    } else {
      password.classList.remove('is-valid');
      password.classList.add('is-invalid');
    }
    validateForm();
  });

  // Form submit using AJAX
  form.addEventListener('submit', function (e) {
    e.preventDefault(); // Always prevent default
    e.stopPropagation(); // Stop bubbling to avoid any inline onsubmit alerts

    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    const passwordValid = password.value.length >= 8;

    if (!emailValid || !passwordValid) {
      showToast('Please fill in valid credentials.', 'danger');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Logging in...`;

    const payload = { email: email.value, password: password.value };

    fetch('http://localhost:7000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(response => response.json())
      .then(data => {
        if (data.message && data.user_id) {
          showToast(data.message, 'success');
          sessionStorage.setItem('user_id', data.user_id);

          setTimeout(() => {
            if (data.redirect_url) {
              window.location.href = data.redirect_url; // fixed reference
            }
          }, 1500);
        } else {
          showToast(data.message || 'Invalid response from server.', 'danger');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again later.', 'danger');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-sign-in-alt me-2"></i> Login`;
      });
  });
});

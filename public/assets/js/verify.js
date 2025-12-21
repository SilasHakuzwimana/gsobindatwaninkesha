document.addEventListener('DOMContentLoaded', function () {
  const otpInputs = document.querySelectorAll('.otp-input');
  const form = document.getElementById('otpForm');
  const submitBtn = form.querySelector('button[type="submit"]');

  // Disable submit initially
  submitBtn.disabled = true;

  // ✅ Auto-move and validation for OTP inputs
  otpInputs.forEach((input, index) => {
    input.addEventListener('input', function () {
      const value = input.value.trim();

      // Accept only digits
      if (!/^\d$/.test(value)) {
        input.value = '';
        input.classList.add('is-invalid');
      } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');

        // Move to next input automatically
        if (index < otpInputs.length - 1) {
          otpInputs[index + 1].removeAttribute('disabled');
          otpInputs[index + 1].focus();
        }
      }

      // Enable submit if all 6 are filled
      const allFilled = Array.from(otpInputs).every(i => i.value.length === 1);
      submitBtn.disabled = !allFilled;
    });

    // Move focus backward on Backspace
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && input.value === '' && index > 0) {
        otpInputs[index - 1].focus();
      }
    });
  });

  // ✅ Handle form submission
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const otpValue = Array.from(otpInputs).map(i => i.value).join('');
    if (otpValue.length !== 6) {
      showSlidingToast('Please complete all 6 digits.', 'danger');
      return;
    }

    // Get user_id from sessionStorage or cookie
    const user_id = sessionStorage.getItem('user_id') || getCookie('user_id');
    if (!user_id) {
      showSlidingToast('User session expired. Please log in again.', 'danger');
      setTimeout(() => (window.location.href = '/login'), 2000);
      return;
    }

    // Disable submit while loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Verifying...`;

    const payload = {
      user_id: user_id,
      otp_code: otpValue
    };

    fetch('http://localhost:8000/api/verify-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
      .then(res => res.json())
      .then(data => {
        if (data.message && data.message.toLowerCase().includes('successful')) {
          sessionStorage.setItem('showLoginToast', data.message);
          showSlidingToast(data.message, 'success');
          setTimeout(() => {
            if (data.redirect_url) {
              window.location.href = data.redirect_url;
            } else {
              showSlidingToast(data.message || 'Role not found. Please try again.', 'danger');
            }
          }, 1500);
        } else {
          showSlidingToast(data.message || 'Invalid OTP. Please try again.', 'danger');
        }
      })
      .catch(err => {
        console.error('Error verifying OTP:', err);
        showSlidingToast('Network error. Please try again.', 'danger');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-check-circle me-2"></i> Verify`;
      });
  });

  // ✅ Helper to read cookie if used
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
  }
});

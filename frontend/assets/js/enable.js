document.addEventListener('DOMContentLoaded', function () {
  const fullName = document.querySelector('fullName');
  const email = document.getElementById('email');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');
  const gender = document.getElementById('gender');
  const phone = document.getElementById('phone');
  const dob = document.getElementById('dob');
  const form = document.getElementById('registerForm');

  // Step 1: Enable email after full name is filled
  fullName.addEventListener('input', function () {
    if (fullName.value.trim().length > 0) {
      email.removeAttribute('disabled');
    } else {
      email.setAttribute('disabled', true);
      password.setAttribute('disabled', true);
    }
  });

  // Step 2: Enable password after valid email
  email.addEventListener('input', function () {
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    if (emailValid) {
      password.removeAttribute('disabled');
    } else {
      password.setAttribute('disabled', true);
      confirmPassword.setAttribute('disabled', true);
    }
  });

  // Step 3: Enable confirm password after strong password
  password.addEventListener('input', function () {
    const score = getPasswordScore(password.value);
    if (score >= 5) { // Assuming 5 = strongest
      confirmPassword.removeAttribute('disabled');
    } else {
      confirmPassword.setAttribute('disabled', true);
    }
    updateStrength(password.value); // Your existing password strength logic
  });

  // Step 4: Enable gender after passwords match
  confirmPassword.addEventListener('input', function () {
    if (password.value === confirmPassword.value) {
      gender.removeAttribute('disabled');
    } else {
      gender.setAttribute('disabled', true);
    }
  });

  // Step 5: Enable phone after gender is selected
  gender.addEventListener('change', function () {
    if (gender.value !== '') phone.removeAttribute('disabled');
    else phone.setAttribute('disabled', true);
  });

  // Step 6: Enable DOB after phone is valid
  phone.addEventListener('input', function () {
    const iti = window.intlTelInputGlobals.getInstance(phone);
    if (iti.isValidNumber()) dob.removeAttribute('disabled');
    else dob.setAttribute('disabled', true);
  });

  // Optional: Block form submission until all fields valid
  form.addEventListener('submit', function (e) {
    if (!fullName.value || !email.value || getPasswordScore(password.value) < 5 || password.value !== confirmPassword.value || !gender.value || !phone.value || !dob.value) {
      e.preventDefault();
      alert('Please complete all fields correctly before submitting.');
    }
  });
});

// Password strength helper function (reuse from your password.js)
function getPasswordScore(val) {
  return [val.length >= 8, /[A-Z]/.test(val), /[a-z]/.test(val), /[0-9]/.test(val), /[^A-Za-z0-9]/.test(val)].filter(Boolean).length;
}

function updateStrength(val) {
  const strengthBar = document.getElementById('strengthBar');
  const passwordStrengthText = document.getElementById('passwordStrength');
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
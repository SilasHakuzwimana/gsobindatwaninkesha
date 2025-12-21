document.addEventListener('DOMContentLoaded', function () {
  const fullName = document.getElementById('fullName');
  const email = document.getElementById('email');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');
  const gender = document.getElementById('gender');
  const phone = document.getElementById('phone');
  const dob = document.getElementById('dob');
  const submitBtn = document.querySelector('button[type="submit"]');
  const form = document.getElementById('registerForm');

  // Step 1: Enable email after full name is filled
  fullName.addEventListener('input', function () {
    if (fullName.value.trim().length > 5) {
      email.removeAttribute('disabled');
      fullName.classList.remove('is-invalid');
      fullName.classList.add('is-valid');
    } else {
      email.setAttribute('disabled', true);
      password.setAttribute('disabled', true);
      fullName.classList.remove('is-valid');
      fullName.classList.add('is-invalid');
    }
  });

  // Step 2: Enable password after valid email
  email.addEventListener('input', function () {
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    if (emailValid) {
      password.removeAttribute('disabled');
      email.classList.remove('is-invalid');
      email.classList.add('is-valid');
    } else {
      password.setAttribute('disabled', true);
      confirmPassword.setAttribute('disabled', true);
      email.classList.remove('is-valid');
      email.classList.add('is-invalid');
    }
  });

  // Step 3: Enable confirm password after strong password
  password.addEventListener('input', function () {
    const score = getPasswordScore(password.value);
    if (score >= 5) {
      confirmPassword.removeAttribute('disabled');
      password.classList.remove('is-invalid');
      password.classList.add('is-valid');
    } else {
      confirmPassword.setAttribute('disabled', true);
      password.classList.remove('is-valid');
      password.classList.add('is-invalid');
    }
    updateStrength(password.value); // Optional: show strength bar
  });

  // Step 4: Enable gender after passwords match
  confirmPassword.addEventListener('input', function () {
    if (password.value === confirmPassword.value) {
      gender.removeAttribute('disabled');
      confirmPassword.classList.remove('is-invalid');
      confirmPassword.classList.add('is-valid');
    } else {
      gender.setAttribute('disabled', true);
      confirmPassword.classList.remove('is-valid');
      confirmPassword.classList.add('is-invalid');
    }
  });

  // Step 5: Enable phone after gender is selected
  gender.addEventListener('change', function () {
    if (gender.value !== '') {
      phone.removeAttribute('disabled');
      gender.classList.remove('is-invalid');
      gender.classList.add('is-valid');
    } else {
      phone.setAttribute('disabled', true);
      gender.classList.remove('is-valid');
      gender.classList.add('is-invalid');
    }
  });

  // Step 6: Enable DOB after phone is valid
  phone.addEventListener('input', function () {
    const iti = window.intlTelInputGlobals.getInstance(phone);
    if (iti.isValidNumber()) {
      dob.removeAttribute('disabled');
      phone.classList.remove('is-invalid');
      phone.classList.add('is-valid');
    } else {
      dob.setAttribute('disabled', true);
      phone.classList.remove('is-valid');
      phone.classList.add('is-invalid');
    }
  });

  // Form submission
  form.addEventListener('submit', function (e) {
    const valid =
      fullName.value.trim() &&
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value) &&
      getPasswordScore(password.value) >= 5 &&
      password.value === confirmPassword.value &&
      gender.value &&
      phone.value &&
      dob.value;

    if (!valid) {
      e.preventDefault();
      alert('Please complete all fields correctly before submitting.');
    }
  });
});

// Password strength helper function
function getPasswordScore(val) {
  return [
    val.length >= 8,
    /[A-Z]/.test(val),
    /[a-z]/.test(val),
    /[0-9]/.test(val),
    /[^A-Za-z0-9]/.test(val),
  ].filter(Boolean).length;
}
function updateStrength(val) {
  const strengthBar = document.getElementById('strengthBar');
  const score = getPasswordScore(val);
  strengthBar.value = score;
}
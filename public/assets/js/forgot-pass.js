document.addEventListener('DOMContentLoaded', function () {
  const email = document.getElementById('email');
  const form = document.getElementById('forgotPasswordForm');


  // Enable password after valid email
  email.addEventListener('input', function () {
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    if (emailValid) {
      email.classList.remove('is-invalid');
      email.classList.add('is-valid');
    } else {
      email.classList.remove('is-valid');
      email.classList.add('is-invalid');
    }
  });

  // Form submit validation
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    let valid = true;

    // Validate Email
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    if (!emailValid) {
      email.classList.add('is-invalid');
      valid = false;
    } else {
      email.classList.remove('is-invalid');
      email.classList.add('is-valid');
    }

    if (valid) {
      alert('Form submitted successfully!');
      // Here you can call your actual login API
    }
  });
});

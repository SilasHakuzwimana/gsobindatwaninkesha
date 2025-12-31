<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | GSOB INDATWA</title>
  <link rel="icon" href="/assets/images/logo.jpg" type="image/x-icon">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/styles.css" />

  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding-top: 0;
      margin: 0;
      /* Reduced padding-top to give more room */
    }

    .contact-container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #007BFF;
      margin-bottom: 20px;
    }

    /* Message alert styles */
    .alert-slide-up {
      animation: slideUp 0.5s forwards;
    }

    @keyframes slideUp {
      0% {
        opacity: 1;
        transform: translateY(0);
      }

      100% {
        opacity: 0;
        transform: translateY(-20px);
      }
    }

    .contact-info a {
      color: #007BFF;
      text-decoration: none;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    .dropdown-item {
      color: steelblue;
    }

    /* Make dropdown menus visible and operable on large screens (>= 992px) */
    @media (min-width: 992px) {

      /* Keep dropdown open on hover */
      .dropdown:hover>.dropdown-menu {
        display: block !important;
        opacity: 1;
        visibility: visible;
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 0.5rem;
        z-index: 1050;
        /* Ensure on top */
      }

      /* Remove any conflicting display rule */
      .dropdown-menu {
        display: none;
      }

      /* Show on hover */
      .dropdown:hover>.dropdown-menu {
        display: block !important;
      }
    }

    /* Style your links with a pale blue for a professional look */
    .navbar-nav .nav-link,
    .dropdown-toggle {
      color: steelblue;
      /* Pale blue */
      transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover,
    .dropdown-toggle:hover,
    .navbar-nav .nav-link:focus,
    .dropdown-toggle:focus {
      color: steelblue;
      /* Lighter blue on hover/focus */
    }
  </style>
</head>

<body>
  <!-- Navbar Reference -->
  <?php include 'navbar.php' ?>

  <div class="contact-container">
    <h1 class="text-center"><i class="fas fa-headset me-2"></i>Contact Us</h1>
    <p class="text-center text-muted">If you have any questions, feedback, or inquiries, please use the form below or
      reach us via the contact info provided.</p>

    <form id="contactForm" class="needs-validation mt-4" novalidate>
      <div id="contactMessage" class="d-none" role="alert"></div>

      <div class="mb-3">
        <label for="contactName" class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="contactName" name="name" placeholder="Enter your full name"
          required>
        <div class="invalid-feedback">Please enter your full name.</div>
      </div>

      <div class="mb-3">
        <label for="contactEmail" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="contactEmail" name="email" placeholder="Enter your email" required>
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>

      <div class="mb-3">
        <label for="contactSubject" class="form-label">Subject <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="contactSubject" name="subject" placeholder="Brief subject" required>
        <div class="invalid-feedback">Please enter a subject.</div>
      </div>

      <div class="mb-3">
        <label for="contactMessageInput" class="form-label">Message <span class="text-danger">*</span></label>
        <textarea class="form-control" id="contactMessageInput" name="message" rows="5" placeholder="Write your message"
          required></textarea>
        <div class="invalid-feedback">Please write your message.</div>
      </div>

      <div class="mb-3">
        <label for="contactAttachment" class="form-label">Attachment (optional)</label>
        <input type="file" class="form-control" id="contactAttachment" name="attachment">
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="checkbox" id="contactCheckbox" required>
        <label class="form-check-label" for="contactCheckbox">I agree to be contacted regarding my message. <span
            class="text-danger">*</span></label>
        <div class="invalid-feedback">You must agree to be contacted.</div>
      </div>

      <button type="submit" id="contactButton" class="btn btn-primary w-100">
        <i class="fas fa-paper-plane me-2"></i> Send Message
      </button>
    </form>

    <div class="mt-5 contact-info text-center border-top pt-4">
      <h5><i class="fas fa-id-card-alt me-2"></i>Other ways to reach us:</h5>
      <p>
        <i class="fas fa-envelope me-2 text-primary"></i> Email:
        <a href="mailto:support@gso-b-indatwa.com">support@gso-b-indatwa.com</a>
      </p>
      <p>
        <i class="fab fa-instagram me-2 text-danger"></i> Instagram:
        <a href="https://www.instagram.com/c.a.s___t.r.o" target="_blank">@Castro</a>,
        <a href="https://www.instagram.com/kfa___brice1" target="_blank">@KFabrice</a>
      </p>
      <p>
        <i class="fas fa-phone me-2 text-success"></i> Phone:
        <a href="tel:+250783749019">+250 783 749 019</a>
      </p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="/assets/js/helpers.js"></script>
  <script>
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      e.stopPropagation();

      const form = this;
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }
      form.classList.add('was-validated'); // Ensure validation styling is shown

      const name = document.getElementById('contactName').value.trim();
      const email = document.getElementById('contactEmail').value.trim();
      const subject = document.getElementById('contactSubject').value.trim();
      const messageInput = document.getElementById('contactMessageInput').value.trim();
      const checkbox = document.getElementById('contactCheckbox').checked;
      const messageDiv = document.getElementById('contactMessage');
      const submitButton = document.getElementById('contactButton');

      messageDiv.innerHTML = '';
      messageDiv.className = 'd-none'; // Start hidden

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        document.getElementById('contactEmail').classList.add('is-invalid');
        return showMessage('Please enter a valid email address.', 'warning');
      }

      submitButton.disabled = true;
      const originalText = submitButton.innerHTML;
      submitButton.innerHTML =
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...`;

      try {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('subject', subject);
        formData.append('message', messageInput);
        formData.append('checkbox', checkbox ? '1' : '');
        const attachment = document.getElementById('contactAttachment').files[0];
        if (attachment) formData.append('attachment', attachment);

        const response = await fetch('http://localhost:8000/api/contact', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (response.ok) {
          showToast(data.message || 'Your message has been sent successfully!', 'success');
          form.reset();
          form.classList.remove('was-validated'); // Clear validation styling on success
        } else {
          showToast(data.message || 'Submission failed. Please check the form and try again.', 'danger');
        }
      } catch (err) {
        console.error('Fetch Error:', err);
        showToast('An unexpected error occurred. Please check your network connection.', 'danger');
      } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
      }

      function showMessage(text, type) {
        messageDiv.innerHTML = text;
        messageDiv.className = `alert alert-${type} mt-2`; // Use Bootstrap alert classes

        setTimeout(() => {
          messageDiv.classList.add('alert-slide-up');
          messageDiv.addEventListener(
            'animationend',
            () => {
              messageDiv.innerHTML = '';
              messageDiv.className = 'd-none';
              messageDiv.classList.remove('alert-slide-up');
            }, {
              once: true
            }
          );
        }, 5000); // 5 seconds display time
      }
    });
  </script>

</body>

</html>
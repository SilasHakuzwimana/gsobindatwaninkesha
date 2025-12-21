<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Request | GSOB INDATWA</title>
  <link rel="icon" href="./images/logo.jpg" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
      font-family: Arial, sans-serif;
    }
    .support-container {
      max-width: 700px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      color: #007BFF;
      margin-bottom: 20px;
    }
    .alert-slide-up {
      animation: slideUp 0.5s forwards;
    }
    @keyframes slideUp {
      0% { opacity: 1; transform: translateY(0); }
      100% { opacity: 0; transform: translateY(-20px); }
    }
  </style>
</head>
<body>
  <div class="support-container">
    <h2>Contact Support</h2>
    <p>If you have any issue, question, or feedback, please fill out the form below and we will get back to you as soon as possible.</p>
    
    <form id="supportForm" class="needs-validation" novalidate>
      <div id="supportMessage" role="alert"></div>

      <div class="mb-3">
        <label for="supportName" class="form-label">Full Name *</label>
        <input type="text" class="form-control" id="supportName" name="name" placeholder="Enter your full name" required>
      </div>

      <div class="mb-3">
        <label for="supportEmail" class="form-label">Email *</label>
        <input type="email" class="form-control" id="supportEmail" name="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="supportSubject" class="form-label">Subject *</label>
        <input type="text" class="form-control" id="supportSubject" name="subject" placeholder="Brief subject of your issue" required>
      </div>

      <div class="mb-3">
        <label for="supportMessageInput" class="form-label">Message *</label>
        <textarea class="form-control" id="supportMessageInput" name="message" rows="5" placeholder="Describe your issue" required></textarea>
      </div>

      <div class="mb-3">
        <label for="supportAttachment" class="form-label">Attachment (optional)</label>
        <input type="file" class="form-control" id="supportAttachment" name="attachment">
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="supportCheckbox" required>
        <label class="form-check-label" for="supportCheckbox">I agree to be contacted regarding my support request.</label>
      </div>

      <button type="submit" id="supportButton" class="btn btn-primary">Submit Request</button>
    </form>

    <div class="mt-4">
      <h5>Other ways to reach us:</h5>
      <p>Email: <a href="mailto:support@gso-b-indatwa.com">support@gso-b-indatwa.com</a></p>
      <p>Instagram: 
        <a href="https://www.instagram.com/c.a.s___t.r.o" target="_blank">@Castro</a>, 
        <a href="https://www.instagram.com/kfa___brice1" target="_blank">@KFabrice</a>
      </p>
    </div>
  </div>

  <script>
    document.getElementById('supportForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const name = document.getElementById('supportName').value.trim();
      const email = document.getElementById('supportEmail').value.trim();
      const subject = document.getElementById('supportSubject').value.trim();
      const messageInput = document.getElementById('supportMessageInput').value.trim();
      const checkbox = document.getElementById('supportCheckbox').checked;
      const messageDiv = document.getElementById('supportMessage');
      const submitButton = document.getElementById('supportButton');

      messageDiv.innerHTML = '';
      messageDiv.className = '';

      if (!name || !email || !subject || !messageInput) {
        return showMessage('Please fill in all required fields.', 'warning');
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        return showMessage('Please enter a valid email address.', 'warning');
      }

      if (!checkbox) {
        return showMessage('You must agree to be contacted.', 'warning');
      }

      submitButton.disabled = true;
      const originalText = submitButton.innerHTML;
      submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...`;

      try {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('subject', subject);
        formData.append('message', messageInput);
        const attachment = document.getElementById('supportAttachment').files[0];
        if (attachment) formData.append('attachment', attachment);

        const response = await fetch('http://localhost:8000/api/support-request', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (response.ok) {
          showMessage(data.message || 'Your request has been submitted!', 'success');
          document.getElementById('supportForm').reset();
        } else {
          showMessage(data.message || 'Submission failed.', 'danger');
        }
      } catch (err) {
        console.error(err);
        showMessage('Something went wrong. Please try again later.', 'danger');
      } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
      }

      function showMessage(text, type) {
        messageDiv.innerHTML = text;
        messageDiv.className = `alert alert-${type} mt-2`;

        setTimeout(() => {
          messageDiv.classList.add('alert-slide-up');
          messageDiv.addEventListener(
            'animationend',
            () => {
              messageDiv.innerHTML = '';
              messageDiv.className = '';
            },
            { once: true }
          );
        }, 5000);
      }
    });
  </script>
</body>
</html>

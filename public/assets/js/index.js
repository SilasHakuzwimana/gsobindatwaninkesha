document.getElementById('newsletterForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  e.stopPropagation(); // Stop propagation to prevent multiple submissions

  const form = this;
  const messageDiv = document.getElementById('newsletterMessage');
  // FIX 1: The submit button needs an ID (or be selected by its type)
  // Assuming you update the button in your HTML to have the ID 'newsletterSubmitButton'
  const submitButton = form.querySelector('button[type="submit"]');

  // Bootstrap 5 form validation check
  if (!form.checkValidity()) {
    form.classList.add('was-validated');
    return; // Stop if validation fails
  }
  form.classList.add('was-validated'); // Ensure validation styling is shown

  const fullName = document.getElementById('newsletterFullName').value.trim();
  const email = document.getElementById('newsletterEmail').value.trim();
  const checkbox = document.getElementById('newsletterCheckbox').checked;

  // Reset previous message and ensure it's hidden before new message
  messageDiv.innerHTML = '';
  messageDiv.className = 'd-none';

  // (Note: Email validation is mostly handled by HTML 'required' and 'email' type, 
  // but the regex check below is a good fallback.)

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    // Since we're using Bootstrap, we let the browser handle this validation
    // but this manual check remains a good safeguard.
    return showMessage('Please enter a valid email address.', 'warning');
  }

  if (!checkbox) {
    // This validation should be handled by the 'required' attribute on the checkbox.
    return showMessage('You must agree to receive newsletters.', 'warning');
  }


  // --- Submission Logic ---

  // Show spinner in button
  submitButton.disabled = true;
  const originalText = submitButton.innerHTML;
  submitButton.innerHTML =
    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Subscribing...`;

  try {
    const response = await fetch('http://localhost:8000/api/subscribe-newsletter', {
      method: 'POST',
      headers: {
        // Ensure the server expects JSON
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        full_name: fullName,
        email: email
      })
    });

    const data = await response.json();

    if (response.ok) {
      showMessage(data.message || 'Subscribed successfully! Check your inbox.', 'success');
      document.getElementById('newsletterForm').reset();
      form.classList.remove('was-validated'); // FIX 2: Clear validation styling on success
    } else {
      showMessage(data.message || 'Subscription failed. Please try again.', 'danger');
    }
  } catch (err) {
    console.error('Submission Error:', err);
    showMessage('A network error occurred. Please try again later.', 'danger');
  } finally {
    submitButton.disabled = false;
    submitButton.innerHTML = originalText;
  }

  // Helper function to display message
  function showMessage(text, type) {
    messageDiv.innerHTML = text;
    messageDiv.className = `alert alert-${type} mt-2`; // Use Bootstrap alert classes

    setTimeout(() => {
      messageDiv.classList.add('alert-slide-up');
      messageDiv.addEventListener(
        'animationend',
        () => {
          messageDiv.innerHTML = '';
          messageDiv.className = 'd-none'; // Hide the div after animation
          messageDiv.classList.remove('alert-slide-up');
        }, {
        once: true
      }
      );
    }, 5000); // Display for 5 seconds
  }
});
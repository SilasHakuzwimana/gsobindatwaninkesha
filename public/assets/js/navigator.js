 // Sliding Toast Notification
 window.showSlidingToast =  function(message, type = 'success') {
  const toastContainer = document.createElement('div');
  toastContainer.className = `toast align-items-center text-bg-${type} border-0`;
  toastContainer.setAttribute('role', 'alert');

  // Initial position (above viewport)
  toastContainer.style.position = 'fixed';
  toastContainer.style.top = '-100px';
  toastContainer.style.right = '20px';
  toastContainer.style.zIndex = 1055;
  toastContainer.style.transition = 'top 0.5s ease-out';

  toastContainer.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  `;

  document.body.appendChild(toastContainer);

  // Slide in
  setTimeout(() => toastContainer.style.top = '20px', 100);

  // Initialize Bootstrap toast
  const toast = new bootstrap.Toast(toastContainer, { delay: 4000 });
  toast.show();

  // Slide out and remove
  toastContainer.addEventListener('hidden.bs.toast', () => {
    toastContainer.style.top = '-100px';
    setTimeout(() => toastContainer.remove(), 500);
  });
}

document.addEventListener('DOMContentLoaded', function () {

  // Page Navigation
  window.showPage = function (pageId) {
    const pages = ['loginPage', 'registerPage', 'verifyOtpPage', 'forgotPasswordPage', 'resetPasswordPage', 'dashboardPage'];
    pages.forEach(page => {
      const el = document.getElementById(page);
      if (el) el.classList.add('hidden'); // only add class if element exists
    });
    const target = document.getElementById(pageId);
    if (target) target.classList.remove('hidden'); // only remove class if element exists
  }

  // Form Handlers
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      showPage('dashboardPage');
    });
  }

  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      showPage('verifyOtpPage');
    });
  }

  const otpForm = document.getElementById('otpForm');
  if (otpForm) {
    otpForm.addEventListener('submit', function (e) {
      e.preventDefault();
      showPage('dashboardPage');
    });
  }

  const forgotPasswordForm = document.getElementById('forgotPasswordForm');
  if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener('submit', function (e) {
      e.preventDefault();
      alert('OTP sent to your email!');
      const loginPage = document.getElementById('loginPage');
      if (loginPage) loginPage.classList.add('hidden'); // safe access
      showPage('resetPasswordPage');
    });
  }

  const resetPasswordForm = document.getElementById('resetPasswordForm');
  if (resetPasswordForm) {
    resetPasswordForm.addEventListener('submit', function (e) {
      e.preventDefault();
      alert('Password reset successful!');
      showPage('loginPage');
    });
  }

  // OTP Input Handler
  const otpInputs = document.querySelectorAll('.otp-input');
  if (otpInputs.length > 0) {
    otpInputs.forEach((input, index) => {
      input.addEventListener('input', function () {
        if (this.value.length === 1 && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      });
      input.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && this.value === '' && index > 0) {
          otpInputs[index - 1].focus();
        }
      });
    });
  }

  // Sidebar toggle
  window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    if (sidebar) sidebar.classList.toggle('collapsed');
    if (mainContent) mainContent.classList.toggle('expanded');
  };

  window.toggleMobileSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.toggle('mobile-show');
  };

  window.setActiveMenu = function (element) {
    const menuItems = document.querySelectorAll('.menu-item');
    if (menuItems.length > 0) {
      menuItems.forEach(item => item.classList.remove('active'));
    }
    if (element) element.classList.add('active');
  };

  // Close mobile sidebar when clicking outside
  document.addEventListener('click', function (e) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    if (window.innerWidth <= 768 && sidebar) {
      if (!sidebar.contains(e.target) && e.target !== menuToggle && (!menuToggle || !menuToggle.contains(e.target))) {
        sidebar.classList.remove('mobile-show');
      }
    }
  });

  // Handle window resize
  window.addEventListener('resize', function () {
    const sidebar = document.getElementById('sidebar');
    if (sidebar && window.innerWidth > 768) sidebar.classList.remove('mobile-show');
  });

});

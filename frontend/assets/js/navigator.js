  // Page Navigation
  function showPage(pageId) {
    // Hide all pages
    const pages = ['loginPage', 'registerPage', 'verifyOtpPage', 'forgotPasswordPage', 'resetPasswordPage',
      'dashboardPage'
    ];
    pages.forEach(page => {
      document.getElementById(page).classList.add('hidden');
    });

    // Show selected page
    document.getElementById(pageId).classList.remove('hidden');
  }

  // Form Handlers
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showPage('dashboardPage');
  });

  document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showPage('verifyOtpPage');
  });

  document.getElementById('otpForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showPage('dashboardPage');
  });

  document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showPage('resetPasswordPage');
  });

  document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Password reset successful!');
    showPage('loginPage');
  });

  // OTP Input Handler
  const otpInputs = document.querySelectorAll('.otp-input');
  otpInputs.forEach((input, index) => {
    input.addEventListener('input', function(e) {
      if (this.value.length === 1) {
        if (index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      }
    });

    input.addEventListener('keydown', function(e) {
      if (e.key === 'Backspace' && this.value === '') {
        if (index > 0) {
          otpInputs[index - 1].focus();
        }
      }
    });
  });

  // Sidebar Toggle
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }

  // Mobile Sidebar Toggle
  function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('mobile-show');
  }

  // Set Active Menu Item
  function setActiveMenu(element) {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => item.classList.remove('active'));
    element.classList.add('active');
  }

  // Close mobile sidebar when clicking outside
  document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.mobile-menu-toggle');

    if (window.innerWidth <= 768) {
      if (!sidebar.contains(e.target) && e.target !== menuToggle && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('mobile-show');
      }
    }
  });

  // Handle window resize
  window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth > 768) {
      sidebar.classList.remove('mobile-show');
    }
  });
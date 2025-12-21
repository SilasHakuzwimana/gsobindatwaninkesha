// ===========================
// dashboard.js
// Handles dashboard navigation & page-specific initialization
// ===========================

document.addEventListener('DOMContentLoaded', function () {

  // --- LOGIN SUCCESS TOAST ---
  const loginToastMessage = sessionStorage.getItem('showLoginToast');
  if (loginToastMessage) {
    showSlidingToast(loginToastMessage, 'success');
    sessionStorage.removeItem('showLoginToast');
  }

  // --- LOGOUT ---
  window.logoutUser = function () {
    fetch('http://localhost:8000/api/logout', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' }
    })
      .then(async res => {
        const text = await res.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch {
          throw new Error('Logout failed: server did not return valid JSON');
        }
        return data;
      })
      .then(data => {
        localStorage.removeItem('auth_token');
        sessionStorage.clear();
        sessionStorage.setItem('showLogoutToast', data.message || 'Logout successful');
        window.location.href = data.redirect || '/login';
      })
      .catch(err => {
        console.error('Logout error:', err);
        showSlidingToast('Error logging out. Please try again.', 'danger');
      });
  };

  // --- PAGE NAVIGATION ---
  const menuItems = document.querySelectorAll('.menu-item');
  menuItems.forEach(item => {
    item.addEventListener('click', async (e) => {
      e.preventDefault();

      // Remove active class from all
      menuItems.forEach(m => m.classList.remove('active'));
      item.classList.add('active');

      const page = item.dataset.page;
      const contentArea = document.getElementById('content-area');

      if (!page || !contentArea) return;

      try {
        // Show spinner
        document.getElementById('spinner').style.display = 'flex';

        // Fetch partial HTML
        const res = await fetch(`/admin/partials/${page}`);
        const html = await res.text();
        contentArea.innerHTML = html;

        window.API_BASE = 'http://localhost:8000/api';

        // Initialize page-specific JS
        switch (page) {
          case 'users':
            if (typeof initUsersJs === 'function') initUsersJs();
            break;
          case 'pathways':
            if (typeof initPathwaysJs === 'function') initPathwaysJs();
            break;
          case 'streams':
            if (typeof initStreamsJs === 'function') initStreamsJs();
            break;
          case 'subjects':
            if (typeof initSubjectsJs === 'function') initSubjectsJs();
            break;
          case 'documents':
            if (typeof initDocumentsJs === 'function') initDocumentsJs();
            break;

          case 'gallery':
            if (typeof initGalleryJs === 'function') initGalleryJs();
            break;
          case 'alumni':
            if (typeof initAlumniGallery === 'function') initAlumniGallery();
            break;
          case 'extra-curricular':
            if (typeof initActivitiesGallery === 'function') initActivitiesGallery();
            break;
          case 'updates':
            if (typeof initUpdatesGallery === 'function') initUpdatesGallery();
            break;
          case 'subscribers':
            if (typeof initSubscribersJs === 'function') initSubscribersJs();
            break;
          case 'messages':
            if (typeof initMessagesJs === 'function') initMessagesJs();
            break;
          case 'logs':
            if (typeof initActivitiesJs === 'function') initActivitiesJs();
            if (typeof initChartsJs === 'function') initChartsJs();
            break;

          // Add other pages here
          default:
            break;
        }

      } catch (err) {
        console.error(`Error loading page ${page}:`, err);
        contentArea.innerHTML = `<div class="text-danger">Failed to load page ${page}</div>`;
      } finally {
        // Hide spinner
        document.getElementById('spinner').style.display = 'none';
      }
    });
  });


  // Optionally, load the default page (overview)
  const defaultPage = document.querySelector('.menu-item.active');
  if (defaultPage) defaultPage.click();

});

// ===========================
// TOAST HELPER
// ===========================
function showSlidingToast(message, type = 'info') {
  const toastContainer = document.createElement('div');
  toastContainer.className = `toast align-items-center text-white bg-${type === 'danger' ? 'danger' : type} border-0`;
  toastContainer.setAttribute('role', 'alert');
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

  const bsToast = new bootstrap.Toast(toastContainer, { delay: 4000 });
  bsToast.show();

  toastContainer.addEventListener('hidden.bs.toast', () => {
    toastContainer.remove();
  });
}

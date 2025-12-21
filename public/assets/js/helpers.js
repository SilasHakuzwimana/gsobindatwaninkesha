// helpers.js
// ===============================
// Helper functions: AJAX + Toasts
// ===============================

/**
 * Make a fetch request to API
 * @param {string} url - Full API URL
 * @param {string} method - HTTP method (GET, POST, PUT, DELETE)
 * @param {object} data - Request body for POST/PUT
 * @returns {Promise<object>}
 */
async function makeRequest(url, method = 'GET', data = null) {
  const options = {
    method,
    headers: { 'Content-Type': 'application/json' }
  };

  if (data && (method === 'POST' || method === 'PUT')) {
    options.body = JSON.stringify(data);
  }

  const res = await fetch(url, options);
  if (!res.ok) {
    const errText = await res.text();
    throw new Error(errText || `Request failed with status ${res.status}`);
  }

  return res.json();
}

/**
 * Show a toast notification
 * @param {string} message - Toast text
 * @param {string} type - success | error | info
 */
function showToast(message, type = 'info') {
  // Use Bootstrap Toast component
  const toastContainerId = 'toastContainer';
  let container = document.getElementById(toastContainerId);

  if (!container) {
    container = document.createElement('div');
    container.id = toastContainerId;
    container.className = 'position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1055';
    document.body.appendChild(container);
  }

  const toastEl = document.createElement('div');
  toastEl.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} border-0`;
  toastEl.role = 'alert';
  toastEl.ariaLive = 'assertive';
  toastEl.ariaAtomic = 'true';
  toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  `;

  container.appendChild(toastEl);
  const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
  toast.show();

  toastEl.addEventListener('hidden.bs.toast', () => {
    toastEl.remove();
  });
}

/**
 * Capitalize first letter
 * @param {string} str
 * @returns {string}
 */
function capitalize(str) {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
}

/**
 * Format date string to readable format
 * @param {string} dateStr
 * @returns {string}
 */
function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

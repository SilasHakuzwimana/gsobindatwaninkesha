// ========================================================
// DASHBOARD AJAX PAGE LOADER
// ========================================================

// Base URL for PHP partials
var BASE_PARTIAL_URL = '/partials';

// Elements
const spinner = document.getElementById('spinner');
const contentArea = document.getElementById('content-area');

// Configuration for smoother UX
const MIN_SPINNER_TIME = 300; // Minimum time (in ms) the spinner must display

/*
// Show spinner
function showSpinner() {
  if (spinner) spinner.style.display = 'flex';
}

// Hide spinner
function hideSpinner() {
  if (spinner) spinner.style.display = 'none';
}
*/
// ========================================================
// Spinner Control
// ========================================================

var spinnerVisible = false;

function showSpinner() {
  // Prevent multiple overlays
  if (spinnerVisible) return;

  const overlay = document.createElement('div');
  overlay.id = 'spinnerOverlay';
  overlay.innerHTML = `
    <div class="spinner-container">
      <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
    </div>
  `;

  Object.assign(overlay.style, {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(255, 255, 255, 0.85)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: '9999',
    transition: 'opacity 0.2s ease'
  });

  document.body.appendChild(overlay);
  spinnerVisible = true;
}

function hideSpinner() {
  const overlay = document.getElementById('spinnerOverlay');
  if (overlay) {
    overlay.style.opacity = '0';
    setTimeout(() => {
      overlay.remove();
      spinnerVisible = false;
    }, 200);
  } else {
    spinnerVisible = false;
  }
}

// ========================================================
// Load Partial Page Content (FIXED for UX)
// ========================================================
async function loadPage(pagePath) {
  const startTime = Date.now();
  let response;

  try {
    showSpinner();

    // 1. Start the fetch request
    response = await fetch(`${BASE_PARTIAL_URL}/${pagePath}`, {
      method: 'GET',
      credentials: 'include'
    });

    // 2. Wait for the minimum display time if loading was too fast
    const elapsed = Date.now() - startTime;
    const remainingTime = MIN_SPINNER_TIME - elapsed;
    if (remainingTime > 0) {
      await new Promise(resolve => setTimeout(resolve, remainingTime));
    }

    if (!response.ok) {
      // If response failed, read the error message
      const errorText = await response.text();
      throw new Error(`Failed to load page (${response.status}): ${errorText.substring(0, 50)}...`);
    }

    // 3. Process success
    const html = await response.text();

    if (contentArea) {
      contentArea.innerHTML = html;
    } else {
      console.error("Error: content-area not found.");
    }

  } catch (error) {
    console.error("❌ Error loading page:", error);

    // Ensure spinner is hidden before showing error (since we're in the catch block)
    hideSpinner();

    if (contentArea) {
      contentArea.innerHTML = `
<div style="padding: 40px; text-align: center; border: 1px solid #ffdddd; background-color: #fff0f0; border-radius: 8px;">
<h3 style="color: #cc0000; margin-bottom: 10px;">🛑 Error Loading Page</h3>
<p style="font-style: italic;">${error.message}</p>
 <button id="retry-btn" onclick="loadPage('${pagePath}')" style="
 padding: 8px 16px;
background-color: #007bff;
 color: white;
 border: none;
 border-radius: 6px;
 margin-top: 15px;
cursor: pointer;" class="btn btn-danger">Retry</button>
</div>
 `;
    }
  } finally {
    // This ensures the spinner is hidden after successful load or after delay, 
    // but before showing a potential retry error message.
    hideSpinner();
  }
}

// ========================================================
// Sidebar Navigation Handler
// ========================================================
document.addEventListener('click', (event) => {
  const link = event.target.closest('[data-page]');
  if (!link) return;

  event.preventDefault();
  const pagePath = link.getAttribute('data-page');

  // Remove active class from all menu items
  document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));

  // Highlight current menu item
  // Use link.closest('.menu-item') to ensure you target the parent element holding the class
  const menuItem = link.closest('.menu-item');
  if (menuItem) menuItem.classList.add('active');

  // Load the selected page
  loadPage(pagePath);
});

// ========================================================
// Sidebar Toggle (for mobile view)
// ========================================================
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) {
    sidebar.classList.toggle('collapsed');
  }
}

// ========================================================
// Initial Page Load
// ========================================================
window.addEventListener('DOMContentLoaded', () => {
  const defaultPage = 'overview'; // Ensure this matches a partial file, e.g., overview.php
  loadPage(defaultPage);
});
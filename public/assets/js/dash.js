// ========================================================
// DASHBOARD PAGE HANDLER (Final Fixed Version)
// ========================================================

// Global state
var currentPageName = 'overview';
var spinnerVisible = false;

// Base URL for PHP partials
var BASE_PARTIAL_URL = '/partials';

// ========================================================
// Spinner Control (Reusable Overlay)
// ========================================================
function showSpinner() {
  if (spinnerVisible) return;
  spinnerVisible = true;

  const overlay = document.createElement('div');
  overlay.id = 'spinnerOverlay';
  // Ensure this uses your desired loading dot structure or the simple Font Awesome spinner
  overlay.innerHTML = `
    <div class="spinner-container">
      <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>
  `;
  Object.assign(overlay.style, {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    background: 'rgba(255,255,255,0.8)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 9999
  });

  document.body.appendChild(overlay);
}

function hideSpinner() {
  const overlay = document.getElementById('spinnerOverlay');
  if (overlay) overlay.remove();
  spinnerVisible = false;
}

// ========================================================
// Sidebar Toggle Functions
// ========================================================
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) sidebar.classList.toggle('collapsed');
}

function toggleMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar) sidebar.classList.toggle('mobile-open');
}

// ========================================================
// Set Active Menu + Load Page
// ========================================================
function setActiveMenu(element, event) {
  event.preventDefault();

  document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
  element.classList.add('active');

  const pageName = element.getAttribute('data-page');
  currentPageName = pageName;
  loadPage(pageName);

  // Close sidebar on mobile if open
  if (window.innerWidth <= 768) {
    document.getElementById('sidebar').classList.add('d-none');
  }
}

// ========================================================
// Main Page Loader (FIXED URL CONSTRUCTION)
// ========================================================
async function loadPage(pageName) {
  const contentArea = document.getElementById('content-area');
  if (!contentArea) return;

  // This map is still useful for input validation and mapping page names to scripts
  const fileMap = {
    overview: 'overview.php',
    users: 'users.php',
    pathways: 'pathways.php',
    subjects: 'subjects.php',
    documents: 'documents.php',
    gallery: 'gallery.php',
    alumni: 'alumni.php',
    extra_curricular: 'extra-curricular.php',
    school_updates: 'updates.php',
    subscribers: 'subscribers.php',
    messages: 'messages.php',
    logs: 'logs.php'
  };

  // 1. Validate pageName (slug)
  if (!fileMap[pageName]) {
    contentArea.innerHTML = `<div class="alert alert-danger">❌ Unknown page: ${pageName}</div>`;
    return;
  }

  // 🎯 THE FIX: Construct the URL using the clean slug (pageName), 
  // letting the PHP router append the .php extension internally.
  const pageUrl = `${BASE_PARTIAL_URL}/${pageName}`; // e.g., /partials/overview

  showSpinner();
  const start = Date.now();
  const minDelay = 400;

  try {
    const response = await fetch(pageUrl, { credentials: 'include' });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`Failed to load ${pageName} (${response.status}): ${errorText.slice(0, 80)}...`);
    }

    const html = await response.text();

    // keep spinner visible for at least 400ms
    const elapsed = Date.now() - start;
    if (elapsed < minDelay) await new Promise(r => setTimeout(r, minDelay - elapsed));

    contentArea.innerHTML = html;

    if (typeof initPageScripts === 'function') initPageScripts(pageName);

  } catch (err) {
    console.error('Page load error:', err);
    contentArea.innerHTML = `
      <div class="alert alert-danger p-4 text-center">
        <h4>🛑 Error loading ${pageName}</h4>
        <p>${err.message}</p>
        <button class="btn btn-warning mt-3" onclick="loadPage('${pageName}')">Retry</button>
      </div>
    `;
  } finally {
    hideSpinner();
  }
}

// ========================================================
// Page-Specific Initializations
// ========================================================
function initPageScripts(pageName) {
  switch (pageName) {
    case 'overview': /* fetchDashboardStats(); */ break;
    case 'users': if (typeof fetchUsers === 'function') fetchUsers(); break;
    case 'pathways': if (typeof fetchPathways === 'function') fetchPathways(); break;
    case 'subjects': if (typeof fetchSubjects === 'function') fetchSubjects(); break;
    case 'documents': if (typeof fetchDocuments === 'function') fetchDocuments(); break;
    case 'gallery': if (typeof fetchGalleryItems === 'function') fetchGalleryItems(); break;
    case 'alumni': if (typeof fetchAlumni === 'function') fetchAlumni(); break;
    case 'extra_curricular': if (typeof fetchExtraCurricular === 'function') fetchExtraCurricular(); break;
    case 'school_updates': if (typeof fetchSchoolUpdates === 'function') fetchSchoolUpdates(); break;
    case 'subscribers': if (typeof fetchSubscribers === 'function') fetchSubscribers(); break;
    case 'messages': if (typeof fetchMessages === 'function') fetchMessages(); break;
    case 'logs': if (typeof fetchActivityLogs === 'function') fetchActivityLogs(); break;
    default:
      console.warn(`No init scripts defined for: ${pageName}`);
  }
}

// ========================================================
// Initialize Dashboard
// ========================================================
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', e => setActiveMenu(item, e));
  });

  loadPage('overview'); // default
});
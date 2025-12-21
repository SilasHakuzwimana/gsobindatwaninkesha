window.showSlidingToast = function (message, type = 'success') {
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

// ==================== CONFIG ====================
var API_BASE = 'http://localhost:8000/api';
var currentData = [];

// ==================== AJAX HELPER ====================
async function makeRequest(url, method = 'GET', data = null) {
  const options = { method, headers: { 'Content-Type': 'application/json' }, credentials: 'include' };
  if (data && method !== 'GET') options.body = JSON.stringify(data);

  try {
    const res = await fetch(url, options);
    const result = await res.json();
    if (!res.ok) throw new Error(result.message || 'Request failed');
    return result;
  } catch (err) {
    console.error('Request error:', err);
    showToast(err.message || 'An error occurred', 'error');
    throw err;
  }
}

// ==================== TOAST ====================
window.showToast = function (message, type = 'info') {
  const existing = document.getElementById('mainToast');
  if (existing) existing.remove();
  const toastHTML = `
    <div id="mainToast" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'success'} border-0 position-fixed top-0 end-0 m-3" role="alert">
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  `;
  document.body.insertAdjacentHTML('beforeend', toastHTML);
  const toastEl = document.getElementById('mainToast');
  new bootstrap.Toast(toastEl, { delay: 3000 }).show();
}

// ==================== USERS ====================
async function fetchUsers() {
  const tbody = document.getElementById('usersTableBody');
  if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading users...</td></tr>';

  try {
    const res = await makeRequest(`${API_BASE}/users`);
    currentData = (res?.data || []).map(u => ({
      ...u,
      fullName: u.fullName || u.full_name || ''
    }));
    renderUsersTable(currentData);
    const userCount = document.getElementById('userCount');
    if (userCount) userCount.textContent = currentData.length;
  } catch {
    if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load users</td></tr>';
  }
}

function renderUsersTable(users) {
  const tbody = document.getElementById('usersTableBody');
  if (!tbody) return;

  if (!users || users.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
    return;
  }

  tbody.innerHTML = users.map(u => `
    <tr>
      <td>${u.user_id}</td>
      <td>${u.fullName}</td>
      <td>${u.email}</td>
      <td>${u.role}</td>
      <td>
        <button class="btn btn-sm btn-primary" onclick="editUser('${u.user_id}')"><i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-danger" onclick="deleteUser('${u.user_id}')"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
  `).join('');
}

// ==================== CRUD ====================
async function saveUser() {
  const form = document.getElementById('userForm');
  const data = Object.fromEntries(new FormData(form));
  const userId = data.userId;
  delete data.userId;

  try {
    if (userId) {
      await makeRequest(`${API_BASE}/users/${userId}`, 'PUT', data);
      showToast('User updated successfully', 'success');
    } else {
      await makeRequest(`${API_BASE}/users`, 'POST', data);
      showToast('User created successfully', 'success');
    }
    const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
    if (modal) modal.hide();
    fetchUsers();
  } catch {
    showToast('Failed to save user', 'error');
  }
}

async function deleteUser(userId) {
  if (!confirm('Are you sure you want to delete this user?')) return;
  try {
    await makeRequest(`${API_BASE}/users/${userId}`, 'DELETE');
    showToast('User deleted successfully', 'success');
    fetchUsers();
  } catch {
    showToast('Failed to delete user', 'error');
  }
}

// ==================== SEARCH ====================
function searchUsers() {
  const term = document.getElementById('searchUser')?.value.toLowerCase() || '';
  const filtered = currentData.filter(u => u.fullName.toLowerCase().includes(term) || u.email.toLowerCase().includes(term));
  renderUsersTable(filtered);
}

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', () => {
  fetchUsers();
  document.getElementById('searchUser')?.addEventListener('input', searchUsers);
  document.getElementById('userForm')?.addEventListener('submit', e => { e.preventDefault(); saveUser(); });
});

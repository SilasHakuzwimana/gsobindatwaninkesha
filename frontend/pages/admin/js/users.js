import { makeRequest } from '../ajax-handler.js';
import { showToast } from '../dashboard.js';

document.addEventListener('DOMContentLoaded', initUsers);

function initUsers() {
  loadUsers();

  document.getElementById('addUserBtn').addEventListener('click', openAddModal);
  document.getElementById('cancelBtn').addEventListener('click', closeModal);
  document.getElementById('userForm').addEventListener('submit', handleUserForm);
}

// Fetch and render all users
async function loadUsers() {
  const tbody = document.getElementById('usersBody');
  tbody.innerHTML = `<tr><td colspan="5" class="text-center">Loading...</td></tr>`;

  const response = await makeRequest('/api/users', 'GET');
  if (!response || !response.status) return;

  const users = response.data || [];
  if (users.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="text-center">No users found</td></tr>`;
    return;
  }

  tbody.innerHTML = users
    .map((u, i) => `
      <tr>
        <td>${i + 1}</td>
        <td>${u.full_name}</td>
        <td>${u.email}</td>
        <td>${u.role}</td>
        <td>
          <button class="btn-edit" data-id="${u.user_id}">Edit</button>
          <button class="btn-delete" data-id="${u.user_id}">Delete</button>
        </td>
      </tr>
    `)
    .join('');

  // Attach events
  document.querySelectorAll('.btn-edit').forEach(btn =>
    btn.addEventListener('click', () => openEditModal(btn.dataset.id))
  );
  document.querySelectorAll('.btn-delete').forEach(btn =>
    btn.addEventListener('click', () => deleteUser(btn.dataset.id))
  );
}

// Create or Update User
async function handleUserForm(e) {
  e.preventDefault();

  const id = document.getElementById('userId').value;
  const data = {
    full_name: document.getElementById('fullName').value,
    email: document.getElementById('email').value,
    password: document.getElementById('password').value,
    role: document.getElementById('role').value
  };

  const method = id ? 'PUT' : 'POST';
  const url = id ? `/api/users/${id}` : '/api/users';

  const response = await makeRequest(url, method, data);
  if (response && response.status) {
    showToast(id ? 'User updated successfully' : 'User created successfully');
    closeModal();
    loadUsers();
  }
}

// Delete User
async function deleteUser(id) {
  if (!confirm('Are you sure you want to delete this user?')) return;

  const response = await makeRequest(`/api/users/${id}`, 'DELETE');
  if (response && response.status) {
    showToast('User deleted');
    loadUsers();
  }
}

// Modal controls
function openAddModal() {
  document.getElementById('modalTitle').innerText = 'Add User';
  document.getElementById('userForm').reset();
  document.getElementById('userId').value = '';
  document.getElementById('userModal').classList.remove('hidden');
}

async function openEditModal(id) {
  const response = await makeRequest(`/api/users/${id}`, 'GET');
  if (!response || !response.status) return;

  const u = response.data;
  document.getElementById('modalTitle').innerText = 'Edit User';
  document.getElementById('userId').value = u.user_id;
  document.getElementById('fullName').value = u.full_name;
  document.getElementById('email').value = u.email;
  document.getElementById('role').value = u.role;
  document.getElementById('password').value = ''; // empty

  document.getElementById('userModal').classList.remove('hidden');
}

function closeModal() {
  document.getElementById('userModal').classList.add('hidden');
}

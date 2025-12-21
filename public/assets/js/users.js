// ===============================
// users.js
// ===============================
function initUsersJs() {
  // --- DOM Elements
  const usersBody = document.getElementById("usersTableBody");
  const userModalEl = document.getElementById("userModal");
  const userModal = userModalEl ? new bootstrap.Modal(userModalEl) : null;
  const userForm = document.getElementById("userForm");
  const modalTitle = document.getElementById("modalTitle");
  const userCountEl = document.getElementById("userCount");
  const searchInput = document.getElementById("searchUser");
  const addUserBtn = document.getElementById("addUserBtn");
  const paginationEl = document.getElementById("pagination");

  // --- State
  let allUsers = [];
  let filteredUsers = [];
  let currentPage = 1;
  const rowsPerPage = 10;
  let currentEditId = null;

  // -------------------------------
  // Render Table
  // -------------------------------
  const renderTable = () => {
    if (!usersBody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredUsers.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      usersBody.innerHTML = '<tr><td colspan="9" class="text-center">No users found</td></tr>';
      if (userCountEl) userCountEl.textContent = filteredUsers.length;
      renderPagination();
      return;
    }

    // Helper functions
    const capitalize = str => str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    const formatDate = d => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';

    // Role colors
    const roleClass = {
      student: 'bg-primary text-white',
      teacher: 'bg-success text-white',
      admin: 'bg-danger text-white',
      alumni: 'bg-warning text-dark',
      guest: 'bg-secondary text-white'
    };

    // Status colors
    const statusClass = {
      active: 'bg-success text-white',
      inactive: 'bg-secondary text-white',
      suspended: 'bg-danger text-white'
    };

    //<td>${u.user_id || ''}</td> Removed user_id

    usersBody.innerHTML = paginated.map(u => {
      const fullName = u.fullName || u.full_name || '—';
      const email = u.email || '—';
      const phone = u.phone || '—';
      const gender = u.gender ? capitalize(u.gender) : '—';
      const role = capitalize(u.role || 'Unknown');
      const status = capitalize(u.status || 'Active');
      const createdAt = u.created_at ? formatDate(u.created_at) : '—';

      const roleBadge = roleClass[u.role] || 'bg-secondary';
      const statusBadge = statusClass[u.status] || 'bg-secondary';

      return `
    <tr>
      <td>${fullName}</td>
      <td>${email}</td>
      <td>${phone}</td>
      <td>${gender}</td>
      <td><span class="badge ${roleBadge}">${role}</span></td>
      <td><span class="badge ${statusBadge}">${status}</span></td>
      <td>${createdAt}</td>
      <td>
        <button class="btn btn-sm btn-outline-primary editBtn" data-id="${u.user_id}">
          <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${u.user_id}">
          <i class="fas fa-trash"></i>
        </button>
      </td>
    </tr>
  `;
    }).join('');

    if (userCountEl) userCountEl.textContent = filteredUsers.length;

    // Attach events
    document.querySelectorAll(".editBtn").forEach(btn =>
      btn.addEventListener("click", () => openEditUserModal(btn.dataset.id))
    );
    document.querySelectorAll(".deleteBtn").forEach(btn =>
      btn.addEventListener("click", () => deleteUser(btn.dataset.id))
    );

    renderPagination();
  };

  // -------------------------------
  // Render Pagination
  // -------------------------------
  // -------------------------------
  // Render Pagination with Prev/Next
  // -------------------------------
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    const totalPages = Math.ceil(filteredUsers.length / rowsPerPage);
    if (totalPages <= 1) return;

    // Prev button
    const prevBtn = document.createElement("button");
    prevBtn.className = `btn btn-sm mx-1 ${currentPage === 1 ? "btn-secondary disabled" : "btn-outline-primary"}`;
    prevBtn.textContent = "Prev";
    prevBtn.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        renderTable();
      }
    });
    paginationEl.appendChild(prevBtn);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.className = `btn btn-sm mx-1 ${i === currentPage ? "btn-primary" : "btn-outline-primary"}`;
      btn.textContent = i;
      btn.addEventListener("click", () => {
        currentPage = i;
        renderTable();
      });
      paginationEl.appendChild(btn);
    }

    // Next button
    const nextBtn = document.createElement("button");
    nextBtn.className = `btn btn-sm mx-1 ${currentPage === totalPages ? "btn-secondary disabled" : "btn-outline-primary"}`;
    nextBtn.textContent = "Next";
    nextBtn.addEventListener("click", () => {
      if (currentPage < totalPages) {
        currentPage++;
        renderTable();
      }
    });
    paginationEl.appendChild(nextBtn);
  };

  // -------------------------------
  // Fetch Users
  // -------------------------------
  const fetchUsers = async () => {
    if (usersBody) usersBody.innerHTML = '<tr><td colspan="5" class="text-center">Loading users...</td></tr>';

    try {
      const res = await makeRequest(`${API_BASE}/users`);
      allUsers = (res?.data || []).map(u => ({ ...u, fullName: u.fullName || u.full_name || '' }));
      filteredUsers = [...allUsers];
      currentPage = 1;
      renderTable();
    } catch {
      if (usersBody) usersBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load users</td></tr>';
    }
  };

  // -------------------------------
  // Search / Filter
  // -------------------------------
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const term = e.target.value.toLowerCase();
      filteredUsers = allUsers.filter(u =>
        (u.fullName || '').toLowerCase().includes(term) ||
        (u.email || '').toLowerCase().includes(term)
      );
      currentPage = 1;
      renderTable();
    });
  }

  // -------------------------------
  // Add / Edit Modal
  // -------------------------------
  const openAddUserModal = () => {
    if (!userModal || !userForm || !modalTitle) return;
    modalTitle.textContent = "Add User";
    userForm.reset();
    currentEditId = null;
    userModal.show();
  };

  const openEditUserModal = async (id) => {
    if (!userModal || !userForm || !modalTitle) return;
    try {

      function capitalizeFirstLetter(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1).toLowerCase() : '';
      }

      const res = await makeRequest(`${API_BASE}/users/${id}`);
      const user = res?.data || res;

      modalTitle.textContent = "Edit User";
      currentEditId = user.user_id;
      document.getElementById("userId").value = user.user_id || '';
      document.getElementById("fullName").value = user.fullName || user.full_name || '';
      document.getElementById("email").value = user.email || '';
      document.getElementById("phone").value = user.phone || '';
      document.getElementById("gender").value = capitalizeFirstLetter(user.gender) || '';
      document.getElementById("role").value = user.role || '';
      document.getElementById("status").value = capitalizeFirstLetter(user.status) || '';
      document.getElementById("password").value = '';
      userModal.show();
    } catch (err) {
      showToast(`Error loading user: ${err.message}`, 'error');
    }
  };

  // -------------------------------
  // Delete User
  // -------------------------------
  const deleteUser = async (id) => {
    if (!confirm("Are you sure you want to delete this user?")) return;
    try {
      await makeRequest(`${API_BASE}/users/${id}`, 'DELETE');
      showToast("User deleted successfully", "success");
      fetchUsers();
    } catch (err) {
      showToast(`Error deleting user: ${err.message}`, "error");
    }
  };

  // -------------------------------
  // Save (Add/Edit)
  // -------------------------------
  if (userForm) {
    userForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const payload = {
        fullName: document.getElementById("fullName").value.trim(),
        email: document.getElementById("email").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        gender: document.getElementById("gender").value.trim(),
        role: document.getElementById("role").value.trim(),
        status: document.getElementById("status").value.trim(),
      };
      const password = document.getElementById("password").value.trim();
      if (password) payload.password = password;

      try {
        let message = '';
        if (currentEditId) {
          await makeRequest(`${API_BASE}/users/${currentEditId}`, "PUT", payload);
          message = "User updated successfully";
        } else {
          await makeRequest(`${API_BASE}/users`, "POST", payload);
          message = "User saved successfully";
        }

        showToast(message, "success");
        userModal.hide();
        fetchUsers();
      } catch (err) {
        showToast(`Error saving user: ${err.message}`, "error");
      }
    });
  }

  // -------------------------------
  // Init
  // -------------------------------
  if (addUserBtn) addUserBtn.addEventListener("click", openAddUserModal);
  fetchUsers();
}

// // --- Initialize on DOM ready
// document.addEventListener("DOMContentLoaded", () => initUsersJs());

//initUsersJs = window.initUsersJs();

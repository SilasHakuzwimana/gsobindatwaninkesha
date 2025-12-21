function initPathwaysJs() {
  // --- DOM Elements
  const pathwaysBody = document.getElementById("pathwaysTableBody");
  const pathwayModalEl = document.getElementById("pathwayModal");
  const pathwayModal = pathwayModalEl ? new bootstrap.Modal(pathwayModalEl) : null;
  const pathwayForm = document.getElementById("pathwayForm");
  const modalTitle = document.getElementById("pathwayModalTitle");
  const searchInput = document.getElementById("searchPathway");
  const addPathwayBtn = document.getElementById("addPathwayBtn");
  const paginationEl = document.getElementById("paginationPathways");
  const deleteModalEl = document.getElementById("deleteConfirmModal");
  const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  let deleteTargetId = null;

  // --- State
  let allPathways = [];
  let filteredPathways = [];
  let currentPage = 1;
  const rowsPerPage = 10;
  let currentEditId = null;

  // -------------------------------
  // Render Table
  // -------------------------------
  const renderTable = () => {
    if (!pathwaysBody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredPathways.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      pathwaysBody.innerHTML = '<tr><td colspan="4" class="text-center">No pathways found</td></tr>';
      renderPagination();
      return;
    }

    pathwaysBody.innerHTML = paginated.map(p => `
      <tr>
        <td>${p.pathway_name || '—'}</td>
        <td>${p.description || '—'}</td>
        <td>${p.created_at ? new Date(p.created_at).toLocaleString() : '—'}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary editBtn" data-id="${p.pathway_id}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${p.pathway_id}">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `).join('');

    document.querySelectorAll(".editBtn").forEach(btn => btn.addEventListener("click", () => openEditModal(btn.dataset.id)));
    document.querySelectorAll(".deleteBtn").forEach(btn => btn.addEventListener("click", () => deletePathway(btn.dataset.id)));

    renderPagination();
  };

  // -------------------------------
  // Pagination
  // -------------------------------
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    const totalPages = Math.ceil(filteredPathways.length / rowsPerPage);
    if (totalPages <= 1) return;

    const createBtn = (text, page, disabled = false, active = false) => {
      const btn = document.createElement("button");
      btn.className = `btn btn-sm mx-1 ${active ? 'btn-primary' : 'btn-outline-primary'} ${disabled ? 'disabled' : ''}`;
      btn.textContent = text;
      if (!disabled && !active) btn.addEventListener("click", () => { currentPage = page; renderTable(); });
      return btn;
    };

    paginationEl.appendChild(createBtn("Prev", currentPage - 1, currentPage === 1));
    for (let i = 1; i <= totalPages; i++) {
      paginationEl.appendChild(createBtn(i, i, false, i === currentPage));
    }
    paginationEl.appendChild(createBtn("Next", currentPage + 1, currentPage === totalPages));
  };

  // -------------------------------
  // Fetch Pathways
  // -------------------------------
  const fetchPathways = async () => {
    if (pathwaysBody) pathwaysBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
    try {
      const res = await makeRequest(`${API_BASE}/pathways`);
      allPathways = res?.data || [];
      filteredPathways = [...allPathways];
      currentPage = 1;
      renderTable();
    } catch (err) {
      pathwaysBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Failed to load pathways</td></tr>';
    }
  };

  // -------------------------------
  // Search
  // -------------------------------
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const term = e.target.value.toLowerCase();
      filteredPathways = allPathways.filter(p =>
        (p.pathway_name || '').toLowerCase().includes(term) ||
        (p.description || '').toLowerCase().includes(term)
      );
      currentPage = 1;
      renderTable();
    });
  }

  // -------------------------------
  // Add/Edit Modal
  // -------------------------------
  const openAddModal = () => {
    if (!pathwayModal || !pathwayForm || !modalTitle) return;
    modalTitle.textContent = "Add Pathway";
    pathwayForm.reset();
    currentEditId = null;
    pathwayModal.show();
  };

  const openEditModal = async (id) => {
    if (!pathwayModal || !pathwayForm || !modalTitle) return;
    try {
      const res = await makeRequest(`${API_BASE}/pathways/${id}`);
      const p = res?.data || {};
      modalTitle.textContent = "Edit Pathway";
      currentEditId = p.pathway_id;
      document.getElementById("pathwayName").value = p.pathway_name || '';
      document.getElementById("pathwayDescription").value = p.description || '';
      pathwayModal.show();
    } catch (err) {
      showToast(`Error loading pathway: ${err.message}`, 'error');
    }
  };

  // -------------------------------
  // Delete Pathway
  // -------------------------------
  const deletePathway = (id) => {
    deleteTargetId = id;
    if (deleteModal) deleteModal.show();
  };

  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", async () => {
      if (!deleteTargetId) return;
      try {
        await makeRequest(`${API_BASE}/pathways/${deleteTargetId}`, "DELETE");
        showToast("Pathway deleted successfully", "success");
        removeTableRow(deleteTargetId);
        deleteModal.hide();
      } catch (err) {
        showToast(`Error deleting pathway: ${err.message}`, "error");
        deleteModal.hide();
      } finally {
        deleteTargetId = null;
      }
    });
  }

  // -------------------------------
  // Save Pathway
  // -------------------------------
  if (pathwayForm) {
    pathwayForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const payload = {
        pathway_name: document.getElementById("pathwayName").value.trim(),
        description: document.getElementById("pathwayDescription").value.trim()
      };
      if (!payload.pathway_name) { showToast("Pathway name is required", "error"); return; }

      try {
        if (currentEditId) {
          const updatedPathway = await makeRequest(`${API_BASE}/pathways/${currentEditId}`, "PUT", payload);
          showToast("Pathway updated successfully", "success");
          updateTableRow(updatedPathway?.data || { pathway_id: currentEditId, ...payload });
        } else {
          const newPathway = await makeRequest(`${API_BASE}/pathways`, "POST", payload);
          showToast("Pathway created successfully", "success");
          allPathways.push(newPathway?.data);
          filteredPathways.push(newPathway?.data);
          renderTable();
        }
        pathwayModal.hide();
      } catch (err) {
        showToast(`Error saving pathway: ${err.message}`, "error");
      }
    });
  }

  // -------------------------------
  // Helpers: Update & Remove rows
  // -------------------------------
  const updateTableRow = (updatedPathway) => {
    const row = document.querySelector(`.editBtn[data-id="${updatedPathway.pathway_id}"]`)?.closest('tr');
    if (!row) return;
    row.children[0].textContent = updatedPathway.pathway_name || '—';
    row.children[1].textContent = updatedPathway.description || '—';
    row.children[2].textContent = updatedPathway.created_at ? new Date(updatedPathway.created_at).toLocaleString() : '—';

    // Update local arrays
    allPathways = allPathways.map(p => p.pathway_id === updatedPathway.pathway_id ? updatedPathway : p);
    filteredPathways = filteredPathways.map(p => p.pathway_id === updatedPathway.pathway_id ? updatedPathway : p);
  };

  const removeTableRow = (id) => {
    const row = document.querySelector(`.deleteBtn[data-id="${id}"]`)?.closest('tr');
    if (!row) return;

    // Add fade-out class
    row.classList.add('fade-out');

    // Wait for animation to complete before removing
    row.addEventListener('transitionend', () => {
      row.remove();

      // Remove from local arrays
      allPathways = allPathways.filter(p => p.pathway_id !== id);
      filteredPathways = filteredPathways.filter(p => p.pathway_id !== id);

      renderPagination();

      // Show a message if table is empty
      if (!filteredPathways.length && pathwaysBody) {
        pathwaysBody.innerHTML = '<tr><td colspan="4" class="text-center">No pathways found</td></tr>';
      }
    }, { once: true });
  };

  // -------------------------------
  // Init
  // -------------------------------
  if (addPathwayBtn) addPathwayBtn.addEventListener("click", openAddModal);
  fetchPathways();
}

// document.addEventListener("DOMContentLoaded", initPathwaysJs);

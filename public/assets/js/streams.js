function initStreamsJs() {
  const pathwaySelect = document.getElementById("selectPathwayForStreams");
  const streamsBody = document.getElementById("streamsTableBody");
  const streamModalEl = document.getElementById("streamModal");
  const streamModal = streamModalEl ? new bootstrap.Modal(streamModalEl) : null;
  const streamForm = document.getElementById("streamForm");
  const modalTitle = document.getElementById("streamModalTitle");
  const addStreamBtn = document.getElementById("addStreamBtn");
  const deleteModalEl = document.getElementById("deleteStreamModal");
  const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
  const confirmDeleteBtn = document.getElementById("confirmDeleteStreamBtn");
  const paginationEl = document.getElementById("paginationStreams");

  let allPathways = [];
  let allStreams = [];
  let filteredStreams = [];
  let currentEditId = null;
  let deleteTargetId = null;
  let currentPage = 1;
  const rowsPerPage = 10;

  let API_BASE = '/api'

  // -------------------------------
  // Fetch Pathways
  // -------------------------------
  const fetchPathways = async () => {
    try {
      const res = await makeRequest(`${API_BASE}/pathways`);
      allPathways = res?.data || [];

      // Fill both main page and modal dropdowns
      if (pathwaySelect) {
        pathwaySelect.innerHTML = '<option value="">Select Pathway</option>' +
          allPathways.map(p => `<option value="${p.pathway_id}">${p.pathway_name}</option>`).join('');
      }
      const modalSelect = document.getElementById("streamPathway");
      if (modalSelect) {
        modalSelect.innerHTML = '<option value="">Select Pathway</option>' +
          allPathways.map(p => `<option value="${p.pathway_id}">${p.pathway_name}</option>`).join('');
      }
    } catch (err) {
      console.error("Failed to fetch pathways:", err);
    }
  };

  const fetchAllStreams = async () => {
    streamsBody.innerHTML = '<tr><td colspan="3" class="text-center">Loading...</td></tr>';
    try {
      const res = await makeRequest(`${API_BASE}/streams`); // endpoint returning all streams
      allStreams = res?.data || [];
      filteredStreams = [...allStreams];
      currentPage = 1;
      renderTable();
    } catch (err) {
      streamsBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Failed to load streams</td></tr>';
    }
  };

  // -------------------------------
  // Fetch Streams
  // -------------------------------
  const fetchStreams = (pathwayId) => {
    if (!pathwayId) {
      filteredStreams = [...allStreams]; // show all streams
    } else {
      filteredStreams = allStreams.filter(s => s.pathway_id === pathwayId);
    }
    currentPage = 1;
    renderTable();
  };

  // -------------------------------
  // Render Table
  // -------------------------------
  const renderTable = () => {
    if (!streamsBody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredStreams.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      streamsBody.innerHTML = '<tr><td colspan="3" class="text-center">No streams found</td></tr>';
      renderPagination();
      return;
    }

    streamsBody.innerHTML = paginated.map(s => `
      <tr>
        <td>${s.stream_name}</td>
        <td>${s.description || '—'}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary editBtn" data-id="${s.stream_id}">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${s.stream_id}">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `).join('');

    document.querySelectorAll(".editBtn").forEach(btn => btn.addEventListener("click", () => openEditModal(btn.dataset.id)));
    document.querySelectorAll(".deleteBtn").forEach(btn => btn.addEventListener("click", () => openDeleteModal(btn.dataset.id)));

    renderPagination();
  };

  // -------------------------------
  // Pagination
  // -------------------------------
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    const totalPages = Math.ceil(filteredStreams.length / rowsPerPage);
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
  // Open Add/Edit Modal
  // -------------------------------
  const openAddModal = () => {
    if (!streamModal || !streamForm || !modalTitle) return;
    modalTitle.textContent = "Add Stream";
    streamForm.reset();
    currentEditId = null;
    // Pre-select pathway
    const modalPathwaySelect = document.getElementById("streamPathway");
    if (modalPathwaySelect) {
      modalPathwaySelect.value = pathwaySelect.value;
    }
    streamModal.show();
  };

  const openEditModal = (id) => {
    const stream = allStreams.find(s => s.stream_id === id);
    if (!stream) return;
    modalTitle.textContent = "Edit Stream";
    currentEditId = id;
    document.getElementById("streamName").value = stream.stream_name;
    document.getElementById("streamDescription").value = stream.description || '';
    document.getElementById("streamPathway").value = stream.pathway_id || '';
    streamModal.show();
  };

  // -------------------------------
  // Add/Edit Submit
  // -------------------------------
  if (streamForm) {
    streamForm.addEventListener("submit", async e => {
      e.preventDefault();
      const payload = {
        stream_name: document.getElementById("streamName").value.trim(),
        description: document.getElementById("streamDescription").value.trim(),
        pathway_id: document.getElementById("streamPathway").value  // from modal select
      };
      try {
        if (currentEditId) {
          await makeRequest(`${API_BASE}/streams/${currentEditId}`, "PUT", payload);
          showToast("Stream updated successfully", "success");
        } else {
          await makeRequest(`${API_BASE}/streams`, "POST", payload);
          showToast("Stream created successfully", "success");
        }
        streamModal.hide();
        fetchStreams(pathwaySelect.value);
      } catch (err) {
        showToast(`Error saving stream: ${err.message}`, "error");
      }
    });
  }

  // -------------------------------
  // Delete
  // -------------------------------
  const openDeleteModal = (id) => {
    deleteTargetId = id;
    deleteModal?.show();
  };

  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", async () => {
      if (!deleteTargetId) return;
      try {
        await makeRequest(`${API_BASE}/streams/${deleteTargetId}`, "DELETE");
        showToast("Stream deleted successfully", "success");
        deleteModal.hide();
        fetchStreams(pathwaySelect.value);
      } catch (err) {
        showToast(`Error deleting stream: ${err.message}`, "error");
        deleteModal.hide();
      } finally {
        deleteTargetId = null;
      }
    });
  }

  // -------------------------------
  // Pathway select change
  // -------------------------------
  pathwaySelect.addEventListener("change", e => fetchStreams(e.target.value));

  // -------------------------------
  // Init
  // -------------------------------
  if (addStreamBtn) addStreamBtn.addEventListener("click", openAddModal);
  fetchPathways();     // load pathway options
  fetchAllStreams();   // load all streams on page load
}

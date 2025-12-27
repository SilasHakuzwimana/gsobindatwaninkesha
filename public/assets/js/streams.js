function initStreamsJs() {
  const pathwaySelect = document.getElementById("streamSelectPathway");
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
  let filteredStreams = [];
  let currentEditId = null;
  let deleteTargetId = null;
  let currentPage = 1;
  const rowsPerPage = 10;
  const API_BASE = '/api';

  // -------------------------------
  // Fetch Pathways
  // -------------------------------
  const fetchPathways = async () => {
    try {
      const res = await makeRequest(`${API_BASE}/pathways`);
      allPathways = res?.data || [];

      // Fill dropdown
      if (pathwaySelect) {
        pathwaySelect.innerHTML = '<option value="">All Pathways</option>' +
          allPathways.map(p => `<option value="${p.pathway_id}">${p.pathway_name}</option>`).join('');
      }

      const modalPathwaySelect = document.getElementById("streamPathway");
      if (modalPathwaySelect) {
        modalPathwaySelect.innerHTML = '<option value="">Select Pathway</option>' +
          allPathways.map(p => `<option value="${p.pathway_id}">${p.pathway_name}</option>`).join('');
      }
    } catch (err) {
      console.error("Failed to fetch pathways:", err);
    }
  };

  // -------------------------------
  // Fetch Streams (with optional pathway filter)
  // -------------------------------
  const fetchStreams = async (pathwayId = "") => {
    if (!streamsBody) return;

    streamsBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';

    try {
      let res;
      if (pathwayId) {
        const normalizedId = pathwayId.replace(/-/g, '').toUpperCase();
        res = await makeRequest(`${API_BASE}/streams/${normalizedId}`); // filtered
        filteredStreams = res?.data || [];
      } else {
        res = await makeRequest(`${API_BASE}/streams`); // all streams
        filteredStreams = res?.data || [];
      }

      // Populate allStreams once
      const allRes = await makeRequest(`${API_BASE}/streams`);
      allStreams = allRes?.data || [];

      currentPage = 1;
      renderTable();
    } catch (err) {
      streamsBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Failed to load streams</td></tr>';
      console.error(err);
    }
  };

  // -------------------------------
  // Render Table
  // -------------------------------
  const renderTable = () => {
    if (!streamsBody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredStreams.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      streamsBody.innerHTML = '<tr><td colspan="4" class="text-center">No streams found</td></tr>';
      renderPagination();
      return;
    }

    streamsBody.innerHTML = paginated.map(s => `
    <tr>
      <td>${s.stream_name}</td>
      <td>${s.pathway_name}</td>
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

    // Bind buttons after rendering
    bindEditButtons();
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
    document.getElementById("streamPathway").value = "";
    currentEditId = null;

    // Pre-select pathway
    const modalPathwaySelect = document.getElementById("streamSelectPathway");
    if (modalPathwaySelect) modalPathwaySelect.value = pathwaySelect.value;

    streamModal.show();
  };

  const openEditModal = (id) => {
    const stream = filteredStreams.find(s => s.stream_id === id);
    if (!stream) return;

    modalTitle.textContent = "Edit Stream";
    currentEditId = stream.stream_id;

    document.getElementById("streamName").value = stream.stream_name;
    document.getElementById("streamDescription").value = stream.description || '';

    const modalPathwaySelect = document.getElementById("streamPathway");
    modalPathwaySelect.innerHTML =
      '<option value="">Select Pathway</option>' +
      allPathways.map(p =>
        `<option value="${p.pathway_id}">${p.pathway_name}</option>`
      ).join('');

    requestAnimationFrame(() => {
      modalPathwaySelect.value = stream.pathway_id;
    });

    streamModal.show();
  };


  // -------------------------------
  // Bind Edit Buttons Dynamically
  // -------------------------------
  const bindEditButtons = () => {
    document.querySelectorAll(".editBtn").forEach(btn => {
      btn.addEventListener("click", () => openEditModal(btn.dataset.id));
    });
  };

  // -------------------------------
  // Add/Edit Submit
  // -------------------------------
  if (streamForm) {
    streamForm.onsubmit = async (e) => {
      e.preventDefault();

      const payload = {
        stream_name: document.getElementById("streamName").value.trim(),
        description: document.getElementById("streamDescription").value.trim(),
        pathway_id: document.getElementById("streamPathway").value
      };

      //console.log("Submitting payload:", payload);

      try {
        if (currentEditId) {
          await makeRequest(`${API_BASE}/streams/${currentEditId}`, "PUT", payload);
          showToast("Stream updated successfully", "success");
        } else {
          await makeRequest(`${API_BASE}/streams`, "POST", payload);
          showToast("Stream created successfully", "success");
        }

        streamModal.hide();
        currentEditId = null;

        await fetchStreams(pathwaySelect.value);
      } catch (err) {
        showToast(`Error saving stream: ${err.message}`, "error");
      }
    };
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
        deleteTargetId = null;

        // Fetch updated streams (filtered by selected pathway)
        await fetchStreams(pathwaySelect.value);
      } catch (err) {
        showToast(`Error deleting stream: ${err.message}`, "error");
        deleteModal.hide();
      }
    });
  }

  // -------------------------------
  // Pathway select change
  // -------------------------------
  pathwaySelect.addEventListener("change", async e => {
    await fetchStreams(e.target.value);
  });

  // -------------------------------
  // Init
  // -------------------------------
  if (addStreamBtn) addStreamBtn.addEventListener("click", openAddModal);
  fetchPathways();        // Load pathways
  fetchStreams();         // Load all streams initially
}

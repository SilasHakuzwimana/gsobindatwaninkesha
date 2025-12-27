function initSubjectsJs() {
  const subjectsBody = document.getElementById("subjectsTableBody");
  const subjectModalEl = document.getElementById("subjectModal");
  const subjectModal = subjectModalEl ? new bootstrap.Modal(subjectModalEl) : null;
  const subjectForm = document.getElementById("subjectForm");
  const modalTitle = document.getElementById("subjectModalTitle");
  const searchInput = document.getElementById("searchSubject");
  const addSubjectBtn = document.getElementById("addSubjectBtn");
  const paginationEl = document.getElementById("paginationSubjects");
  const deleteModalEl = document.getElementById("deleteConfirmModal");
  const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
  let deleteTargetId = null;

  // State
  let allSubjects = [];
  let filteredSubjects = [];
  let allPathways = [];
  let allStreams = [];
  let currentPage = 1;
  const rowsPerPage = 10;
  let currentEditId = null;

  const pathwaySelect = document.getElementById("subjectPathway");
  const streamSelect = document.getElementById("subjectStream");


  //Helper functions

  const refreshView = () => {
    const term = searchInput?.value?.toLowerCase() || '';
    filteredSubjects = allSubjects.filter(s =>
      (s.subject_name || '').toLowerCase().includes(term) ||
      (s.subject_code || '').toLowerCase().includes(term)
    );
    renderTable();
  };

  const upsertSubject = (subject) => {
    const index = allSubjects.findIndex(s => s.subject_id === subject.subject_id);
    if (index !== -1) {
      allSubjects[index] = subject; // update
    } else {
      allSubjects.unshift(subject); // insert at top
    }
    refreshView();
  };

  // -------------------------------
  // Fetch Pathways for dropdown
  // -------------------------------
  const fetchPathways = async () => {
    try {
      const res = await makeRequest(`${API_BASE}/pathways`);
      allPathways = res?.data || [];

      if (pathwaySelect) {
        pathwaySelect.innerHTML = '<option value="">Select Pathway</option>' +
          allPathways.map(p => `<option value="${p.pathway_id}">${p.pathway_name}</option>`).join('');
      }
    } catch (err) {
      console.error("Failed to load pathways:", err);
    }
  };

  // -------------------------------
  // Fetch Streams for selected pathway
  // -------------------------------
  const fetchStreams = async (pathwayId) => {
    try {
      if (!pathwayId) {
        if (streamSelect) streamSelect.innerHTML = '<option value="">Select Stream</option>';
        return;
      }
      const normalizedId = pathwayId.replace(/-/g, '').toUpperCase();
      const res = await makeRequest(`${API_BASE}/streams/${normalizedId}`);
      allStreams = res?.data || [];
      //Debug lines
      //console.log("All Streams: ", allStreams);

      if (streamSelect) {
        streamSelect.innerHTML = '<option value="">Select Stream</option>' +
          allStreams.map(s => `<option value="${s.stream_id}">${s.stream_name}</option>`).join('');
      }
    } catch (err) {
      console.error("Failed to load streams:", err);
    }
  };

  if (pathwaySelect) {
    pathwaySelect.addEventListener('change', e => fetchStreams(e.target.value));
  }

  // -------------------------------
  // Render Subjects Table
  // -------------------------------
  const renderTable = () => {
    if (!subjectsBody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredSubjects.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      subjectsBody.innerHTML = '<tr><td colspan="5" class="text-center">No subjects found</td></tr>';
      renderPagination();
      return;
    }

    subjectsBody.innerHTML = paginated.map(s => {
      const name = s.subject_name || '—';
      const code = s.subject_code || '—';
      const desc = s.description || '—';
      const pathwayName = s.pathway_name || '—';
      const streamName = s.stream_name || '—';

      return `
        <tr>
          <td>${name}</td>
          <td>${code}</td>
          <td>${desc}</td>
          <td>${pathwayName}</td>
          <td>${streamName}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary editBtn" data-id="${s.subject_id}">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${s.subject_id}">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      `;
    }).join('');

    document.querySelectorAll(".editBtn").forEach(btn => btn.addEventListener("click", () => openEditModal(btn.dataset.id)));
    document.querySelectorAll(".deleteBtn").forEach(btn => btn.addEventListener("click", () => deleteSubject(btn.dataset.id)));

    renderPagination();
  };

  // -------------------------------
  // Pagination
  // -------------------------------
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    const totalPages = Math.ceil(filteredSubjects.length / rowsPerPage);
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
  // Fetch Subjects
  // -------------------------------
  const fetchSubjects = async () => {
    if (subjectsBody) subjectsBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
    try {
      const res = await makeRequest(`${API_BASE}/subjects`);
      allSubjects = res?.data || [];

      //console.log("Response: ", allSubjects);

      filteredSubjects = [...allSubjects];
      currentPage = 1;
      renderTable();
    } catch (err) {
      subjectsBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load subjects</td></tr>';
    }
  };

  // -------------------------------
  // Search
  // -------------------------------
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const term = e.target.value.toLowerCase();
      filteredSubjects = allSubjects.filter(s =>
        (s.subject_name || '').toLowerCase().includes(term) ||
        (s.subject_code || '').toLowerCase().includes(term)
      );
      currentPage = 1;
      renderTable();
    });
  }

  // -------------------------------
  // Modal Open
  // -------------------------------
  const openAddModal = () => {
    if (!subjectModal || !subjectForm || !modalTitle) return;
    modalTitle.textContent = "Add Subject";
    subjectForm.reset();
    currentEditId = null;
    fetchPathways();
    fetchStreams(null);
    subjectModal.show();
  };

  const openEditModal = async (id) => {
    if (!subjectModal || !subjectForm || !modalTitle) return;
    try {
      const res = await makeRequest(`${API_BASE}/subjects/${id}`);
      const s = res?.data || {};

      modalTitle.textContent = "Edit Subject";
      currentEditId = s.subject_id;
      document.getElementById("subjectName").value = s.subject_name || '';
      document.getElementById("subjectCode").value = s.subject_code || '';
      document.getElementById("subjectDescription").value = s.description || '';

      await fetchPathways();
      pathwaySelect.value = s.pathway_id || '';

      await fetchStreams(s.pathway_id);
      //ensure stream exists before setting
      setTimeout(() => {
        streamSelect.value = s.stream_id;
      }, 0);
      subjectModal.show();
    } catch (err) {
      showToast(`Error loading subject: ${err.message}`, 'error');
    }
  };

  // -------------------------------
  // Delete Subject
  // -------------------------------
  const deleteSubject = (id) => {
    deleteTargetId = id;
    if (deleteModal) deleteModal.show();
  };

  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", async () => {
      if (!deleteTargetId) return;
      try {
        await makeRequest(`${API_BASE}/stream-subjects/${deleteTargetId}`, "DELETE");
        showToast("Subject deleted successfully", "success");
        deleteModal.hide();
        removeTableRow(deleteTargetId);
      } catch (err) {
        showToast(`Error deleting subject: ${err.message}`, "error");
        deleteModal.hide();
      } finally {
        deleteTargetId = null;
      }
    });
  }

  // -------------------------------
  // Save Subject
  // -------------------------------
  if (subjectForm) {
    subjectForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const payload = {
        subject_name: document.getElementById("subjectName").value.trim(),
        subject_code: document.getElementById("subjectCode").value.trim(),
        description: document.getElementById("subjectDescription").value.trim(),
        stream_id: streamSelect.value
      };

      try {
        if (currentEditId) {
          await makeRequest(`${API_BASE}/stream-subjects/${currentEditId}`, "PUT", payload);
          showToast("Subject updated successfully", "success");
        } else {
          if (!payload.stream_id) {
            showToast("Please select a stream", "error");
            return;
          }

          await makeRequest(`${API_BASE}/stream-subjects`, "POST", payload);
          showToast("Subject created successfully", "success");
        }
        subjectModal.hide();
        fetchSubjects();
      } catch (err) {
        showToast(`Error saving subject: ${err.message}`, "error");
      }
    });
  }

  // -------------------------------
  // Animate row removal
  // -------------------------------
  const removeTableRow = (id) => {
    const row = document.querySelector(`.deleteBtn[data-id="${id}"]`)?.closest('tr');
    if (!row) return;

    row.classList.add('fade-out');
    row.addEventListener('transitionend', () => {
      row.remove();
      allSubjects = allSubjects.filter(s => s.subject_id !== id);
      filteredSubjects = filteredSubjects.filter(s => s.subject_id !== id);
      renderPagination();
      if (!filteredSubjects.length && subjectsBody) {
        subjectsBody.innerHTML = '<tr><td colspan="6" class="text-center">No subjects found</td></tr>';
      }
    }, { once: true });
  };

  // -------------------------------
  // Init
  // -------------------------------
  if (addSubjectBtn) addSubjectBtn.addEventListener("click", openAddModal);
  fetchSubjects();
  fetchPathways();
}

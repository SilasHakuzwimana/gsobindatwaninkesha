function initDocumentsJs() {
  const API_BASE = "/api/school-documents";

  const docsBody = document.getElementById("docsTableBody");
  const docModalEl = document.getElementById("docModal");
  const docModal = docModalEl ? new bootstrap.Modal(docModalEl) : null;
  const docForm = document.getElementById("docForm");
  const modalTitle = document.getElementById("modalTitle");
  const searchInput = document.getElementById("searchDoc");
  const addDocBtn = document.getElementById("addDocBtn");
  const paginationEl = document.getElementById("pagination");
  const docCountEl = document.getElementById("docCount");

  let allDocs = [];
  let filteredDocs = [];
  let currentPage = 1;
  const rowsPerPage = 10;
  let currentEditId = null;
  let uploadedFilePath = null;

  const makeRequest = async (url, method = "GET", data = null) => {
    const options = { method };

    if (data) {
      options.headers = { "Content-Type": "application/json" };
      options.body = JSON.stringify(data);
    }

    let res;
    try {
      res = await fetch(url, options);
    } catch (err) {
      throw new Error("Network error: " + err.message);
    }

    let json;
    try {
      json = await res.json();
      // console.log('Results: ', json.data);
    } catch (err) {
      throw new Error("Invalid JSON response from server");
    }

    if (!res.ok || !json.status) {
      throw new Error(json.message || `HTTP Error ${res.status}`);
    }

    return json;
  };

  // --------------------------
  // Render Table
  // --------------------------
  const renderTable = () => {
    if (!docsBody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredDocs.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      docsBody.innerHTML = `<tr><td colspan="5" class="text-center">No documents found</td></tr>`;
      docCountEl.textContent = filteredDocs.length;
      renderPagination();
      return;
    }

    docsBody.innerHTML = paginated.map(doc => {
      return `
        <tr>
          <td>${doc.title}</td>
          <td>${doc.category || 'Other'}</td>
          <td>${doc.uploaded_by_name || '-'}</td>
          <td>${new Date(doc.uploaded_at).toLocaleString()}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary editBtn" data-id="${doc.document_id}"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${doc.document_id}"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
      `;
    }).join('');

    docCountEl.textContent = filteredDocs.length;

    // Attach events
    document.querySelectorAll(".editBtn").forEach(btn => btn.addEventListener("click", () => openEditModal(btn.dataset.id)));
    document.querySelectorAll(".deleteBtn").forEach(btn => btn.addEventListener("click", () => deleteDoc(btn.dataset.id)));

    renderPagination();
  };

  // --------------------------
  // Pagination
  // --------------------------
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    const totalPages = Math.ceil(filteredDocs.length / rowsPerPage);
    if (totalPages <= 1) return;

    const createBtn = (text, page, disabled = false, active = false) => {
      const btn = document.createElement("button");
      btn.className = `btn btn-sm mx-1 ${active ? "btn-primary" : "btn-outline-primary"} ${disabled ? "disabled" : ""}`;
      btn.textContent = text;
      if (!disabled) btn.addEventListener("click", () => { currentPage = page; renderTable(); });
      return btn;
    };

    paginationEl.appendChild(createBtn("Prev", currentPage - 1, currentPage === 1));
    for (let i = 1; i <= totalPages; i++) paginationEl.appendChild(createBtn(i, i, false, i === currentPage));
    paginationEl.appendChild(createBtn("Next", currentPage + 1, currentPage === totalPages));
  };

  // --------------------------
  // Fetch documents
  // --------------------------
  const fetchDocs = async () => {
    if (docsBody) docsBody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';

    try {
      const res = await makeRequest(`${API_BASE}/all`);
      allDocs = res.data || [];       // always an array
      filteredDocs = [...allDocs];
      currentPage = 1;
      renderTable();
    } catch (err) {
      console.error(err);
      if (docsBody) {
        docsBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${err.message}</td></tr>`;
      }
      allDocs = [];
      filteredDocs = [];
    }
  };

  // --------------------------
  // Search / Filter
  // --------------------------
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const term = e.target.value.toLowerCase();
      filteredDocs = allDocs.filter(d =>
        (d.title || '').toLowerCase().includes(term) ||
        (d.category || '').toLowerCase().includes(term)
      );
      currentPage = 1;
      renderTable();
    });
  }

  // --------------------------
  // Add / Edit Modal
  // --------------------------
  const openAddModal = () => {
    if (!docModal || !docForm || !modalTitle) return;
    modalTitle.textContent = "Add Document";
    docForm.reset();
    currentEditId = null;
    uploadedFilePath = null;
    docModal.show();
  };

  const openEditModal = (id) => {
    const doc = allDocs.find(d => d.document_id === id);
    if (!doc) return;
    modalTitle.textContent = "Edit Document";
    currentEditId = id;
    document.getElementById("documentId").value = doc.document_id;
    document.getElementById("title").value = doc.title;
    document.getElementById("category").value = doc.category || 'other';
    uploadedFilePath = doc.file_path || null;
    docModal.show();
  };

  // --------------------------
  // Delete
  // --------------------------
  const deleteDoc = async (id) => {
    if (!confirm("Delete this document?")) return;
    try {
      await makeRequest(`${API_BASE}/${id}`, "DELETE");
      alert("Document deleted!");
      fetchDocs();
    } catch (err) {
      console.error(err);
      alert("Failed to delete document");
    }
  };

  // --------------------------
  // Handle file upload
  // --------------------------
  // Upload file to /api/upload-file
  const uploadFile = async (file) => {
    if (!file) return null;
    const formData = new FormData();
    formData.append("file", file);

    const res = await fetch("/api/upload-file", { method: "POST", body: formData });
    const data = await res.json();

    if (!data.status) throw new Error(data.message || "Upload failed");
    return data.data.url; // return Cloudinary URL
  };
  // Save document metadata to /api/school-documents
  const saveDocument = async (payload, isEdit = false, id = null) => {
    const url = isEdit ? `${API_BASE}/${id}` : API_BASE;
    const method = isEdit ? "PUT" : "POST";

    return makeRequest(url, method, payload); // sends JSON
  };

  // --------------------------
  // Save (Add/Edit)
  // --------------------------
  if (docForm) {
    // On form submit
    docForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      try {
        const fileInput = document.getElementById("fileInput");
        if (fileInput.files[0]) {
          uploadedFilePath = await uploadFile(fileInput.files[0]);
        }

        const payload = {
          title: document.getElementById("title").value.trim(),
          category: document.getElementById("category").value,
          file_path: uploadedFilePath
        };

        if (!payload.title) throw new Error("Title is required");

        if (currentEditId) {
          await saveDocument(payload, true, currentEditId);
          alert("Document updated!");
        } else {
          await saveDocument(payload);
          alert("Document added!");
        }

        docModal.hide();
        fetchDocs();
      } catch (err) {
        console.error(err);
        alert("Failed: " + err.message);
      }
    });
  }

  if (addDocBtn) addDocBtn.addEventListener("click", openAddModal);

  fetchDocs();
}

document.addEventListener("DOMContentLoaded", initDocumentsJs);

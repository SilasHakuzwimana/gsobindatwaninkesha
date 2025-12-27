function initUpdatesGallery() {
  const galleryEl = document.getElementById("updatesGallery");
  const galleryForm = document.getElementById("updatesForm");
  const paginationEl = document.getElementById("updatesPagination");
  const formMessage = document.getElementById("formMessage");
  const cancelEditBtn = document.getElementById("cancelEdit");
  const submitBtn = document.getElementById("submitBtn");

  let updates = [];
  let currentPage = 1;
  const pageSize = 6;

  const resetForm = () => {
    galleryForm.reset();
    document.getElementById("update_id").value = "";
    submitBtn.textContent = "Save";
    cancelEditBtn.classList.add("d-none");
    formMessage.innerHTML = "";
  };

  // -------------------------------
  // Fetch and Render Updates
  // -------------------------------
  const fetchUpdates = async () => {
    const res = await fetch("/api/school-updates");
    const data = await res.json();
    updates = data.data || [];
    currentPage = 1;
    renderGallery();
  };

  const renderGallery = () => {
    if (!updates.length) {
      galleryEl.innerHTML = `<div class="col-12 text-center">No updates found</div>`;
      paginationEl.innerHTML = "";
      return;
    }

    const start = (currentPage - 1) * pageSize;
    const items = updates.slice(start, start + pageSize);

    galleryEl.innerHTML = items.map(item => `
      <div class="col-md-4 mb-4">
        <div class="update-card p-3 h-100">
          ${item.image_path ? `<img src="${item.image_path}" class="update-image mb-2">` : ""}
          <h6>${item.title}</h6>
          ${item.description ? `<p>${item.description}</p>` : ""}
          <div class="btn-group">
            <button class="btn btn-sm btn-warning edit-btn" data-id="${item.uuid}">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${item.uuid}">Delete</button>
          </div>
        </div>
      </div>
    `).join("");

    attachEvents();
    renderPagination();
  };

  const renderPagination = () => {
    const totalPages = Math.ceil(updates.length / pageSize);
    if (totalPages <= 1) return paginationEl.innerHTML = "";

    paginationEl.innerHTML = `
      <button class="btn btn-sm btn-secondary me-2" ${currentPage === 1 ? "disabled" : ""} id="prevPage">Prev</button>
      <span>Page ${currentPage} of ${totalPages}</span>
      <button class="btn btn-sm btn-secondary ms-2" ${currentPage === totalPages ? "disabled" : ""} id="nextPage">Next</button>
    `;

    document.getElementById("prevPage").onclick = () => { currentPage--; renderGallery(); };
    document.getElementById("nextPage").onclick = () => { currentPage++; renderGallery(); };
  };

  const attachEvents = () => {
    document.querySelectorAll(".edit-btn").forEach(btn =>
      btn.onclick = () => loadForEdit(btn.dataset.id)
    );
    document.querySelectorAll(".delete-btn").forEach(btn =>
      btn.onclick = () => deleteUpdate(btn.dataset.id)
    );
  };

  // -------------------------------
  // Load for Edit
  // -------------------------------
  const loadForEdit = (id) => {
    const item = updates.find(u => u.uuid === id);
    if (!item) return;

    document.getElementById("update_id").value = id;
    document.getElementById("title").value = item.title;
    document.getElementById("description").value = item.description || "";

    submitBtn.textContent = "Update";
    cancelEditBtn.classList.remove("d-none");
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  cancelEditBtn.onclick = resetForm;

  // -------------------------------
  // Add New Update Handler
  // -------------------------------
  const addUpdate = async (formData) => {
    try {
      const res = await fetch("/api/school-updates", {
        method: "POST",
        body: formData
      });
      return await res.json();
    } catch (err) {
      return { status: "error", message: "Network error" };
    }
  };

  // -------------------------------
  // Edit Existing Update Handler
  // -------------------------------
  const editUpdate = async (id, formData) => {
    try {
      const res = await fetch(`/api/school-updates/${id}`, {
        method: "POST", // use POST + _method=PUT for FormData
        body: formData
      });
      return await res.json();
    } catch (err) {
      return { status: "error", message: "Network error" };
    }
  };

  // -------------------------------
  // Form Submit
  // -------------------------------
  galleryForm.onsubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData(galleryForm);
    // formData should include hidden update_id if editing

    const res = await fetch("/api/school-updates", {
      method: "POST",
      body: formData
    });

    const data = await res.json();
    showToast(data.message || "Operation failed");
    showToast(data.status === "success" ? "text-success" : "text-danger");

    if (data.status === "success") {
      resetForm();
      fetchUpdates();
    }
  };


  // -------------------------------
  // Delete
  // -------------------------------
  const deleteUpdate = async (uuid) => {
    if (!confirm("Delete this update?")) return;

    const res = await fetch(`/api/school-updates/${uuid}`, { method: "DELETE" });
    const data = await res.json();

    if (data.status === "success") fetchUpdates();
  };

  // Init
  fetchUpdates();
}

document.addEventListener("DOMContentLoaded", () => {
  const galleryBody = document.getElementById("gallery-list");
  const uploadForm = document.getElementById("uploadForm");
  const editModalEl = document.getElementById("editGalleryModal");
  const editModal = new bootstrap.Modal(editModalEl);

  const editForm = document.getElementById("editGalleryForm");
  const editId = document.getElementById("edit-id");
  const editTitle = document.getElementById("edit-title");
  const editDesc = document.getElementById("edit-description");

  const params = new URLSearchParams(window.location.search);
  let type = params.get("type") || "school_gallery";
  let allItems = [];

  // -------------------------------
  // AJAX helper
  // -------------------------------
  const ajax = (url, method = "GET", data = null) => {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open(method, url, true);

      if (!(data instanceof FormData)) {
        xhr.setRequestHeader("Content-Type", "application/json");
      }

      xhr.onload = () => {
        try {
          resolve(JSON.parse(xhr.responseText));
        } catch {
          reject("Invalid JSON response");
        }
      };

      xhr.onerror = () => reject("AJAX error");

      xhr.send(data instanceof FormData ? data : JSON.stringify(data));
    });
  };

  // -------------------------------
  // Render Gallery
  // -------------------------------
  const renderGallery = () => {
    if (!allItems.length) {
      galleryBody.innerHTML = `
        <div class="col-12 text-center py-5">No gallery items found</div>`;
      return;
    }

    galleryBody.innerHTML = allItems.map(item => `
      <div class="col-md-3 mb-4">
        <div class="gallery-item">
          <img src="${item.file_path}" class="img-fluid gallery-img">
          <div class="gallery-title">${item.title}</div>

          <div class="mt-2 text-center">
            <button class="btn btn-sm btn-warning edit-btn"
              data-id="${item.uuid}"
              data-title="${item.title}"
              data-desc="${item.description || ""}">
              Edit
            </button>

            <button class="btn btn-sm btn-danger delete-btn"
              data-id="${item.uuid}">
              Delete
            </button>
          </div>
        </div>
      </div>
    `).join("");
  };

  // -------------------------------
  // Fetch Gallery
  // -------------------------------
  const fetchGallery = async () => {
    galleryBody.innerHTML = `<div class="col-12 text-center py-5">Loading...</div>`;
    try {
      const res = await ajax(`/api/gallery/${type}`);
      allItems = res.data || [];
      renderGallery();
    } catch (err) {
      console.error(err);
      showToast("Failed to load gallery", "error");
    }
  };

  // -------------------------------
  // Upload
  // -------------------------------
  if (uploadForm) {
    uploadForm.addEventListener("submit", async e => {
      e.preventDefault();
      const formData = new FormData(uploadForm);
      type = formData.get("category");

      try {
        const res = await ajax(`/api/gallery/${type}`, "POST", formData);
        showToast(res.message || "Uploaded successfully", "success");
        uploadForm.reset();
        fetchGallery();
      } catch {
        showToast("Upload failed", "error");
      }
    });
  }

  // -------------------------------
  // Open Edit Modal
  // -------------------------------
  document.addEventListener("click", e => {
    if (!e.target.classList.contains("edit-btn")) return;

    editId.value = e.target.dataset.id;
    editTitle.value = e.target.dataset.title;
    editDesc.value = e.target.dataset.desc;

    editModal.show();
  });

  // -------------------------------
  // Save Edit
  // -------------------------------
  editForm.addEventListener("submit", async e => {
    e.preventDefault();

    try {
      const res = await ajax(
        `/api/gallery/${type}/${editId.value}`,
        "PUT",
        {
          title: editTitle.value,
          description: editDesc.value
        }
      );

      showToast(res.message || "Updated successfully", "success");
      editModal.hide();
      fetchGallery();
    } catch {
      showToast("Update failed", "error");
    }
  });

  // -------------------------------
  // Delete
  // -------------------------------
  document.addEventListener("click", async e => {
    if (!e.target.classList.contains("delete-btn")) return;

    if (!confirm("Delete this item?")) return;

    try {
      const res = await ajax(
        `/api/gallery/${type}/${e.target.dataset.id}`,
        "DELETE"
      );

      showToast(res.message || "Deleted successfully", "success");
      fetchGallery();
    } catch {
      showToast("Delete failed", "error");
    }
  });

  fetchGallery();
});

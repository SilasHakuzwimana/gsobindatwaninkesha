// ===============================
// gallery.js - School Gallery + Other Categories
// ===============================
function initGalleryJs() {
  const galleryBody = document.getElementById("gallery-list");
  const uploadForm = document.getElementById("uploadForm");

  // --- Get gallery type from dropdown or URL
  const params = new URLSearchParams(window.location.search);
  let type = params.get("type") || "school_gallery";

  let allItems = [];
  const API_BASE = `/api/gallery/${type}`;

  // -------------------------------
  // Render Gallery Grid
  // -------------------------------
  const renderGallery = () => {
    if (!galleryBody) return;

    if (!allItems.length) {
      galleryBody.innerHTML = `<div class="col-12 text-center py-5">No gallery items found</div>`;
      return;
    }

    galleryBody.innerHTML = paginated.map(item => {
      return `
    <div class="gallery-item">
      ${item.section ? `<div class="gallery-category">${item.section}</div>` : ''}
      <img src="${item.file_path || '#'}" class="gallery-img" alt="${item.title || 'Gallery Image'}">
      <div class="gallery-title">${item.title || 'Untitled'}</div>
      <div class="gallery-actions">
        <button class="gallery-btn edit" data-id="${item.uuid || ''}">Edit</button>
        <button class="gallery-btn delete" data-id="${item.uuid || ''}">Delete</button>
      </div>
    </div>
  `;
    }).join("");
  };

  // -------------------------------
  // Fetch Gallery Items
  // -------------------------------
  const fetchGallery = async () => {
    galleryBody.innerHTML = `<div class="col-12 text-center py-5">Loading...</div>`;
    try {
      const res = await fetch(`/api/gallery/${type}`);
      const data = await res.json();
      allItems = data.data || [];
      renderGallery();
    } catch (err) {
      console.error(err);
      galleryBody.innerHTML = `<div class="col-12 text-center text-danger py-5">Failed to load gallery</div>`;
    }
  };

  // -------------------------------
  // Upload Form
  // -------------------------------
  if (uploadForm) {
    uploadForm.addEventListener("submit", async e => {
      e.preventDefault();
      const formData = new FormData(uploadForm);
      type = formData.get("category") || "school_gallery";

      try {
        const res = await fetch(`/api/gallery/${type}`, {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message || 'Upload complete');
        uploadForm.reset();
        fetchGallery();
      } catch (err) {
        console.error(err);
        alert("Upload failed");
      }
    });
  }

  // -------------------------------
  // Delete Item
  // -------------------------------
  document.addEventListener("click", async e => {
    if (!e.target.classList.contains("delete-btn")) return;
    const id = e.target.dataset.id;
    if (!confirm("Delete this file?")) return;

    try {
      const res = await fetch(`/api/gallery/${type}/${id}`, { method: "DELETE" });
      const data = await res.json();
      alert(data.message || (data.status ? 'Deleted successfully' : 'Delete failed'));
      fetchGallery();
    } catch (err) {
      console.error(err);
      alert("Delete failed");
    }
  });

  // -------------------------------
  // Edit Item
  // -------------------------------
  document.addEventListener("click", async e => {
    if (!e.target.classList.contains("edit-btn")) return;
    const id = e.target.dataset.id;

    const newTitle = prompt("Enter new title:");
    if (!newTitle) return;
    const newDesc = prompt("Enter new description (optional):");

    try {
      const res = await fetch(`/api/gallery/${type}/${id}`, {
        method: "PUT",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: newTitle, description: newDesc })
      });
      const data = await res.json();
      alert(data.message || (data.status ? 'Updated successfully' : 'Update failed'));
      fetchGallery();
    } catch (err) {
      console.error(err);
      alert("Update failed");
    }
  });

  fetchGallery();
}

document.addEventListener("DOMContentLoaded", () => initGalleryJs());

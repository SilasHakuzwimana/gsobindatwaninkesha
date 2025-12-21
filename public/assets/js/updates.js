// =========================
// School Updates Gallery JS
// =========================

function initUpdatesGallery() {
  const galleryEl = document.getElementById("updatesGallery");
  const galleryForm = document.getElementById("updatesForm");

  let updates = [];

  // -------------------------------
  // Render Gallery
  // -------------------------------
  const renderGallery = () => {
    if (!galleryEl) return;

    if (!updates.length) {
      galleryEl.innerHTML = `<div class="col-12 text-center">No updates found</div>`;
      return;
    }

    galleryEl.innerHTML = updates.map(item => `
      <div class="col-md-4">
        <div class="update-card">
          ${item.file_path ? `<img src="${item.file_path}" class="update-image" alt="${item.title}">` : ''}
          <h6>${item.title}</h6>
          ${item.description ? `<p>${item.description}</p>` : ''}
          <div class="btn-group">
            <button class="btn btn-sm btn-warning edit-btn" data-id="${item.uuid}" data-title="${item.title}" data-description="${item.description || ''}">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${item.uuid}">Delete</button>
          </div>
        </div>
      </div>
    `).join('');

    // Attach events
    document.querySelectorAll(".edit-btn").forEach(btn =>
      btn.addEventListener("click", () => editUpdate(btn.dataset.id, btn.dataset.title, btn.dataset.description))
    );
    document.querySelectorAll(".delete-btn").forEach(btn =>
      btn.addEventListener("click", () => deleteUpdate(btn.dataset.id))
    );
  };

  // -------------------------------
  // Fetch Updates
  // -------------------------------
  const fetchUpdates = async () => {
    if (galleryEl) galleryEl.innerHTML = `<div class="col-12 text-center">Loading...</div>`;
    try {
      const res = await fetch('/api/gallery/school_updates_gallery');
      const data = await res.json();
      if (data.status === 'success') {
        updates = data.data || [];
      } else {
        updates = [];
        console.error('Failed to load updates');
      }
      renderGallery();
    } catch (err) {
      galleryEl.innerHTML = `<div class="col-12 text-center text-danger">Error loading updates</div>`;
      console.error(err);
    }
  };

  // -------------------------------
  // Add / Upload Update
  // -------------------------------
  if (galleryForm) {
    galleryForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(galleryForm);

      try {
        const res = await fetch('/api/gallery/school_updates_gallery', {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        alert(data.status === 'success' ? 'Uploaded successfully!' : 'Upload failed!');
        galleryForm.reset();
        fetchUpdates();
      } catch (err) {
        console.error(err);
        alert('Upload failed!');
      }
    });
  }

  // -------------------------------
  // Delete Update
  // -------------------------------
  const deleteUpdate = async (id) => {
    if (!confirm("Are you sure you want to delete this update?")) return;
    try {
      const res = await fetch(`/api/gallery/school_updates_gallery/${id}`, { method: 'DELETE' });
      const data = await res.json();
      alert(data.status === 'success' ? 'Deleted!' : 'Delete failed!');
      fetchUpdates();
    } catch (err) {
      console.error(err);
      alert('Delete failed!');
    }
  };

  // -------------------------------
  // Edit Update
  // -------------------------------
  const editUpdate = async (id, currentTitle, currentDesc) => {
    const newTitle = prompt('Enter new title:', currentTitle);
    if (!newTitle) return;

    const newDesc = prompt('Enter new description (optional):', currentDesc);

    try {
      const res = await fetch(`/api/gallery/school_updates_gallery/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: newTitle, description: newDesc })
      });
      const data = await res.json();
      alert(data.status === 'success' ? 'Updated!' : 'Update failed!');
      fetchUpdates();
    } catch (err) {
      console.error(err);
      alert('Update failed!');
    }
  };

  // -------------------------------
  // Init
  // -------------------------------
  fetchUpdates();
}

// Initialize on DOM ready
document.addEventListener("DOMContentLoaded", () => initUpdatesGallery());

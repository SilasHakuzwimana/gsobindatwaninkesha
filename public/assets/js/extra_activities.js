// =========================
// Extra Curricular Activities Gallery JS
// =========================

function initActivitiesGallery() {
  const galleryListEl = document.getElementById("activitiesGallery");
  const galleryForm = document.getElementById("activitiesForm");

  let galleryItems = [];

  // -------------------------------
  // Render Gallery
  // -------------------------------
  const renderGallery = () => {
    if (!galleryListEl) return;

    if (!galleryItems.length) {
      galleryListEl.innerHTML = `<div class="col-12 text-center">No photos found</div>`;
      return;
    }

    galleryListEl.innerHTML = galleryItems.map(item => `
      <div class="col-md-4">
        <div class="gallery-card">
          <img src="${item.file_path}" class="gallery-image" alt="${item.title}">
          <h6>${item.title}</h6>
          <div class="btn-group">
            <button class="btn btn-sm btn-warning edit-btn" data-id="${item.uuid}" data-title="${item.title}">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${item.uuid}">Delete</button>
          </div>
        </div>
      </div>
    `).join('');

    // Attach events
    document.querySelectorAll(".edit-btn").forEach(btn =>
      btn.addEventListener("click", () => editGalleryItem(btn.dataset.id, btn.dataset.title))
    );
    document.querySelectorAll(".delete-btn").forEach(btn =>
      btn.addEventListener("click", () => deleteGalleryItem(btn.dataset.id))
    );
  };

  // -------------------------------
  // Fetch Gallery Items
  // -------------------------------
  const fetchGallery = async () => {
    if (galleryListEl) galleryListEl.innerHTML = `<div class="col-12 text-center">Loading...</div>`;
    try {
      const res = await fetch('/api/gallery/extra_curricular_activities_gallery');
      const data = await res.json();
      if (data.status === 'success') {
        galleryItems = data.data || [];
      } else {
        galleryItems = [];
        console.error('Failed to load gallery');
      }
      renderGallery();
    } catch (err) {
      galleryItems = [];
      galleryListEl.innerHTML = `<div class="col-12 text-center text-danger">Error loading gallery</div>`;
      console.error(err);
    }
  };

  // -------------------------------
  // Add / Upload Item
  // -------------------------------
  if (galleryForm) {
    galleryForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(galleryForm);

      try {
        const res = await fetch('/api/gallery/extra_curricular_activities_gallery', {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        alert(data.status === 'success' ? 'Uploaded successfully!' : 'Upload failed!');
        galleryForm.reset();
        fetchGallery();
      } catch (err) {
        console.error(err);
        alert('Upload failed!');
      }
    });
  }

  // -------------------------------
  // Delete Item
  // -------------------------------
  const deleteGalleryItem = async (id) => {
    if (!confirm("Are you sure you want to delete this photo?")) return;
    try {
      const res = await fetch(`/api/gallery/extra_curricular_activities_gallery/${id}`, { method: 'DELETE' });
      const data = await res.json();
      alert(data.status === 'success' ? 'Deleted!' : 'Delete failed!');
      fetchGallery();
    } catch (err) {
      console.error(err);
      alert('Delete failed!');
    }
  };

  // -------------------------------
  // Edit Item
  // -------------------------------
  const editGalleryItem = async (id, currentTitle) => {
    const newTitle = prompt('Enter new title:', currentTitle);
    if (!newTitle) return;

    try {
      const res = await fetch(`/api/gallery/extra_curricular_activities_gallery/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: newTitle })
      });
      const data = await res.json();
      alert(data.status === 'success' ? 'Updated!' : 'Update failed!');
      fetchGallery();
    } catch (err) {
      console.error(err);
      alert('Update failed!');
    }
  };

  // -------------------------------
  // Init
  // -------------------------------
  fetchGallery();
}

// Initialize on DOM ready
document.addEventListener("DOMContentLoaded", () => initActivitiesGallery());

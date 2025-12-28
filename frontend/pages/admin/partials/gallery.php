<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>School Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/gallery.css">
</head>

<body>
  <div class="gallery-wrapper container my-4">
    <h3 class="mb-4">School Gallery</h3>

    <!-- Upload Form -->
    <div class="card upload-card p-3 mb-4">
      <form id="uploadForm" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-5">
            <label class="form-label">Title</label>
            <input id="title" name="title" class="form-control" placeholder="Enter title" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
              <option value="school_gallery" selected>School Gallery</option>
              <option value="extra_curricular_activities_gallery">Extra Curricular Activities</option>
              <option value="school_alumni_gallery">School Alumni</option>
              <option value="school_updates_gallery">School Updates</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">File</label>
            <input type="file" id="file" name="file" class="form-control" required>
          </div>
        </div>
        <button class="btn btn-primary mt-3">Upload File</button>
      </form>
    </div>


    <!-- Gallery Grid -->
    <div class="row gallery-grid" id="gallery-list"></div>
  </div>


  <!-- Edit Gallery Modal -->
  <div class="modal fade" id="editGalleryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Gallery Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form id="editGalleryForm">
          <div class="modal-body">
            <input type="hidden" id="edit-id">

            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" id="edit-title" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea id="edit-description" class="form-control"></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>
  <script src="/assets/js/helpers.js"></script>
  <script src="/assets/js/gallery.js"></script>
</body>

</html>
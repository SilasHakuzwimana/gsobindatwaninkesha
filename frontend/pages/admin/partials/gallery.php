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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>
  <script src="/assets/js/gallery.js"></script>
</body>

</html>
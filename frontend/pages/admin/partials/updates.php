<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>School Updates Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/updates.css">
</head>

<body>
  <div class="container my-4">
    <h2 class="mb-4">School Updates Gallery</h2>

    <!-- Upload Form -->
    <div class="card mb-4 p-3">
      <h5>Add New Update</h5>
      <form id="updatesForm">
        <input type="hidden" id="update_id" name="update_id">

        <div id="formMessage" class="mb-3"></div>

        <div class="mb-3">
          <input type="text" name="title" id="title" class="form-control" placeholder="Update name">
        </div>

        <div class="mb-3">
          <textarea name="description" id="description" class="form-control"
            placeholder="Description (optional)"></textarea>
        </div>

        <div class="mb-3">
          <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Save</button>
        <button type="button" class="btn btn-secondary d-none" id="cancelEdit">Cancel</button>
      </form>
    </div>

    <!-- Gallery List -->
    <div class="mb-5 mt-5">
      <div id="updatesGallery" class="row"></div>
      <div id="updatesPagination" class="text-center mt-3"></div>
    </div>
  </div>

  <script src=" https://code.jquery.com/jquery-3.7.0.min.js">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>
  <script src="/assets/js/helpers.js"></script>
  <script src="/assets/js/updates.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", initUpdatesGallery);
  </script>
</body>

</html>
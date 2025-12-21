<?php
// alumni_gallery.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>School Alumni Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/alumni.css">
</head>

<body>

  <div class="container">
    <h2>School Alumni Gallery</h2>

    <!-- Upload Form -->
    <div class="card mb-4 p-3">
      <h5>Add New Photo</h5>
      <form id="galleryForm">
        <div class="mb-3">
          <input type="text" name="title" class="form-control" placeholder="Title" required>
        </div>
        <div class="mb-3">
          <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
      </form>
    </div>

    <!-- Gallery List -->
    <div id="galleryList" class="row"></div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>
  <script src="/assets/js/alumni.js"></script>
</body>

</html>
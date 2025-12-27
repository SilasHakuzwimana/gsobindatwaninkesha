<?php
// extra_activities.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Extra Curricular Activities Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/extra_activities.css">
</head>

<body>
  <div class="container my-4">
    <h2 class="mb-4">Extra Curricular Activities Gallery</h2>

    <!-- Upload Form -->
    <div class="card mb-4 p-3">
      <h5>Add New Activity Photo</h5>
      <form id="activitiesForm">
        <div class="mb-3">
          <input type="text" name="title" class="form-control" placeholder="Title" required>
        </div>
        <div class="mb-3">
          <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
      </form>
    </div>

    <!-- Gallery List -->
    <div id="activitiesGallery" class="row"></div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/helpers.js"></script>
  <script src="/assets/js/extra_activities.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", initActivitiesGallery);
  </script>
</body>

</html>
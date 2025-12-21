<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Pathway Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="/assets/css/pathway_subject.css" rel="stylesheet">
  <style>
  /* Optional custom styling */
  .table-wrapper {
    overflow-x: auto;
  }

  .modal-label {
    font-weight: bold;
  }
  </style>
</head>

<body>

</body>
<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Streams</h2>
    <button class="btn btn-primary" id="addStreamBtn"><i class="fas fa-plus"></i> Add Stream</button>
  </div>

  <div class="mb-3 row">
    <div class="col-md-6">
      <select id="selectPathwayForStreams" class="form-select">
        <option value="">Select Pathway</option>
      </select>
    </div>
  </div>

  <div class="table-wrapper">
    <table class="table table-hover table-bordered">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Pathway</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="streamsTableBody"></tbody>
    </table>
  </div>

  <div class="d-flex justify-content-center my-3" id="paginationStreams"></div>
</div>

<!-- Add/Edit Stream Modal -->
<div class="modal fade" id="streamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="streamForm">
        <div class="modal-header">
          <h5 class="modal-title" id="streamModalTitle">Add Stream</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="streamName" class="form-label">Stream Name</label>
            <input type="text" id="streamName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="streamPathway" class="form-label">Pathway</label>
            <select id="streamPathway" class="form-select" required>
              <option value="">Select Pathway</option>
              <!-- Filled dynamically -->
            </select>
          </div>

          <div class="mb-3">
            <label for="streamDescription" class="form-label">Description</label>
            <textarea id="streamDescription" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Stream</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteStreamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this stream? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteStreamBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
const API_BASE = '/api'; // adjust according to your backend
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/helpers.js"></script> <!-- makeRequest & showToast -->
<script src="/assets/js/streams.js"></script>
<script>
document.addEventListener("DOMContentLoaded", initStreamsJs);
</script>
</body>

</html>
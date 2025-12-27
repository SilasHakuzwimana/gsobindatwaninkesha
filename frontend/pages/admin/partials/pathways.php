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
  <div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Pathways</h2>
      <button class="btn btn-primary" id="addPathwayBtn"><i class="fas fa-plus"></i> Add Pathway</button>
    </div>

    <div class="mb-3 row">
      <div class="col-sm-6">
        <input type="text" id="searchPathway" class="form-control" placeholder="Search pathways...">
      </div>
    </div>

    <div class="table-wrapper mb-5">
      <table class="table table-hover table-bordered">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="pathwaysTableBody">
          <!-- Filled by JS -->
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-center my-3" id="paginationPathways"></div>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="pathwayModal" tabindex="-1" aria-labelledby="pathwayModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="pathwayForm">
          <div class="modal-header">
            <h5 class="modal-title" id="pathwayModalTitle">Add Pathway</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="pathwayName" class="form-label modal-label">Pathway Name</label>
              <input type="text" name="pathway_name" class="form-control" id="pathwayName" required>
            </div>
            <div class="mb-3">
              <label for="pathwayDescription" class="form-label modal-label">Description</label>
              <textarea class="form-control" name="description" id="pathwayDescription" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Pathway</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this pathway? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const API_BASE = '/api'; // adjust according to your backend
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/helpers.js"></script> <!-- makeRequest & showToast -->
  <script src="/assets/js/pathways.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", initPathwaysJs);
  </script>
</body>

</html>
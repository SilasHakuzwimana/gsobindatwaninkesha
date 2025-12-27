<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Subjects Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="/assets/css/pathway_subject.css" rel="stylesheet">
  <style>
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
      <h2>Subjects</h2>
      <button class="btn btn-primary" id="addSubjectBtn"><i class="fas fa-plus"></i> Add Subject</button>
    </div>

    <div class="mb-3 row">
      <div class="col-sm-6">
        <input type="text" id="searchSubject" class="form-control" placeholder="Search subjects...">
      </div>
    </div>

    <div class="table-wrapper">
      <table class="table table-hover table-bordered">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Pathway</th>
            <th>Stream Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="subjectsTableBody">
          <!-- Filled by JS -->
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-center my-3" id="paginationSubjects"></div>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form id="subjectForm">
          <div class="modal-header">
            <h5 class="modal-title" id="subjectModalTitle">Add Subject</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="subjectName" class="form-label modal-label">Subject Name</label>
                <input type="text" class="form-control" name="subjectName" id="subjectName" required>
              </div>
              <div class="col-md-6">
                <label for="subjectCode" class="form-label modal-label">Subject Code</label>
                <input type="text" class="form-control" name="subjectCode" id="subjectCode" required>
              </div>
              <div class="col-md-6">
                <label for="subjectPathway" class="form-label modal-label">Pathway</label>
                <select name="subjectPathway" id="subjectPathway" name="subjectPathway" class="form-select"
                  required></select>
              </div>
              <div class="col-md-6">
                <label for="subjectStream" class="form-label modal-label">Stream</label>
                <select id="subjectStream" name="subjectStream" class="form-select" required></select>
              </div>
              <div class="col-12">
                <label for="subjectDescription" class="form-label modal-label">Description</label>
                <textarea class="form-control" id="subjectDescription" name="subjectDescription" rows="3"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer mt-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Subject</button>
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
          Are you sure you want to delete this subject? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>
  <script>
  const API_BASE = '/api';
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/helpers.js"></script>
  <script src="/assets/js/subjects.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", initSubjectsJs);
  </script>
</body>

</html>
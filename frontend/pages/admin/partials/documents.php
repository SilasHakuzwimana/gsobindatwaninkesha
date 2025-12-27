<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>School Documents</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="/assets/css/logs_styles.css" rel="stylesheet">
</head>

<body class="bg-light">


  <div class="container mt-4">
    <h2 class="mb-2 mb-md-0">Files</h2>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
      <br />
      <div class="d-flex gap-2 w-100">
        <input type="text" id="searchDoc" class="form-control" placeholder="Search documents...">
        <button id="addDocBtn" class="btn btn-success text-center">
          <i class="fas fa-plus me-1 w-100 px-5"></i> Add
        </button>
      </div>
    </div>

    <div class="table-responsive mb-5">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Uploaded By</th>
            <th>Uploaded At</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody id="docsTableBody">
          <tr>
            <td colspan="5" class="text-center">Loading documents...</td>
          </tr>
        </tbody>
      </table>
    </div>

    <nav aria-label="Page navigation">
      <ul id="pagination" class="pagination justify-content-center"></ul>
    </nav>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="docModal" tabindex="-1" aria-labelledby="docModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="docForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="documentId">

          <div class="mb-3">
            <label for="title" class="form-label">Title <i class="fas fa-file-alt"></i></label>
            <input type="text" id="title" name="title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="category" class="form-label">Category <i class="fas fa-tags"></i></label>
            <select id="category" name="category" class="form-select" required>
              <option value="newsletter">Newsletter</option>
              <option value="report">Report</option>
              <option value="policy">Policy</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="fileInput" class="form-label">File <i class="fas fa-upload"></i></label>
            <input type="file" id="fileInput" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
            <small id="fileHelp" class="form-text text-muted">PDF, DOC, DOCX, JPG, PNG only.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>
            Cancel</button>
        </div>
      </form>
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
          Are you sure you want to delete this document? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>

  <!-- Custom JS -->
  <script src="/assets/js/helpers.js"></script>
  <script src="/assets/js/school_documents.js"></script>

</body>

</html>
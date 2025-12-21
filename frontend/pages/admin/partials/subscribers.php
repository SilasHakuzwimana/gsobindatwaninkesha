<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Subscribers</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="/assets/css/subscribers.css" rel="stylesheet">
</head>

<body class="bg-light"></body>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Subscribers <span class="badge bg-secondary" id="subscriberCount">0</span></h3>
  <button class="btn btn-primary" id="sendEmailBtn">
    <i class="fas fa-envelope"></i> Send Email to All
  </button>
</div>

<div class="mb-3">
  <input type="text" id="searchSubscriber" class="form-control" placeholder="Search subscribers by name or email">
</div>

<table class="table table-striped table-hover">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody id="subscribersTableBody">
    <tr>
      <td colspan="3" class="text-center">Loading subscribers...</td>
    </tr>
  </tbody>
</table>

<!-- Pagination -->
<div id="subscribersPagination" class="mt-3 d-flex justify-content-center"></div>

<!-- Modal for sending email -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="emailForm">
        <div class="modal-header">
          <h5 class="modal-title" id="emailModalLabel">Send Email to Subscribers</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="emailSubject" class="form-label">Subject</label>
            <input type="text" id="emailSubject" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="emailMessage" class="form-label">Message <small>(use {{name}} for
                personalization)</small></label>
            <textarea id="emailMessage" class="form-control" rows="6" required></textarea>
          </div>
          <div class="mb-3" id="emailProgressContainer" style="display:none;">
            <label class="form-label">Progress</label>
            <div class="progress">
              <div id="emailProgressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                role="progressbar" style="width: 0%">0%</div>
            </div>
            <small id="emailProgressText" class="text-muted"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="sendEmailSubmit">Send Emails</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
</script>

</body>

</html>
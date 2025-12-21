<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>User Activities</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="/assets/css/logs_styles.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-4">

    <h2 class="mb-4 fw-bold">User Activity Logs</h2>

    <!-- Search + Filters Row -->
    <div class="row g-3 mb-3">

      <div class="col-md-4">
        <input type="text" id="search" class="form-control" placeholder="Search activities...">
      </div>

      <div class="col-md-3">
        <select id="filterType" class="form-select">
          <option value="">Filter by Activity Type</option>
          <option value="login">Login</option>
          <option value="logout">Logout</option>
          <option value="create">Create</option>
          <option value="update">Update</option>
          <option value="delete">Delete</option>
        </select>
      </div>

      <div class="col-md-2">
        <input type="date" id="startDate" class="form-control">
      </div>

      <div class="col-md-2">
        <input type="date" id="endDate" class="form-control">
      </div>

      <div class="col-md-1">
        <button id="resetFilters" class="btn btn-secondary w-100">Reset</button>
      </div>

    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered bg-white">
        <thead class="table-dark">
          <tr>
            <th data-col="activity_type" class="sortable">Activity Type</th>
            <th>Description</th>
            <th data-col="ip_address" class="sortable">IP</th>
            <th>User Agent</th>
            <th data-col="created_at" class="sortable">Date</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody id="activityTable"></tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
      <button id="prevPage" class="btn btn-primary">Previous</button>
      <span id="pageInfo" class="fw-bold"></span>
      <button id="nextPage" class="btn btn-primary">Next</button>
    </div>

    <hr class="my-4">

    <!-- Charts -->
    <h4 class="mb-3">Activity Analytics</h4>

    <!-- <div class="card p-3 shadow-sm">
      <canvas id="activityChart" height="120"></canvas>
    </div>
     -->

    <div class="chart-container" style="height: 300px;">
      <canvas id="activityChart"></canvas>
    </div>

    <div class="chart-container" style="height: 300px;">
      <canvas id="activityChart2"></canvas>
    </div>

    <div class="chart-container" style="height: 300px;">
      <canvas id="activityChartType"></canvas>
    </div>

    <div class="chart-container" style="height: 300px;">
      <canvas id="activityHourlyChart">
      </canvas>
    </div>

    <div class="chart-container" style="height: 300px;">
      <canvas id="deviceChart"></canvas>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Activity Details</h5>
          <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div id="modalContent"></div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="/assets/js/app.js"></script>
  <script src="/assets/js/charts.js"></script>

</body>

</html>
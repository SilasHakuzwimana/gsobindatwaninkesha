<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>All Messages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/css/messages.css">
</head>

<body>

  <div class="container">
    <h2>All Messages </h2>

    <input type="text" id="searchMessage" class="form-control" placeholder="Search messages...">
    <div id="messages-list">
      <p>Loading messages...</p>
    </div>
    <nav aria-label="Messages Pagination" class="mb-5">
      <ul id=" pagination" class="pagination justify-content-center mt-3">
      </ul>
    </nav>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>
  <script src="/assets/js/all_messages.js"></script>

</body>

</html>
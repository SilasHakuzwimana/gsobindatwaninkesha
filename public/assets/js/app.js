// ===============================
// app.js
// Handles User Activities Table
// ===============================
function initActivitiesJs() {
  // --- DOM Elements
  const activityTableBody = document.getElementById("activityTable");
  const detailsModalEl = document.getElementById("detailsModal");
  const detailsModal = detailsModalEl ? new bootstrap.Modal(detailsModalEl) : null;
  const modalContent = document.getElementById("modalContent");

  const searchInput = document.getElementById("search");
  const typeFilter = document.getElementById("filterType");
  const startDate = document.getElementById("startDate");
  const endDate = document.getElementById("endDate");
  const prevPageBtn = document.getElementById("prevPage");
  const nextPageBtn = document.getElementById("nextPage");
  const pageInfo = document.getElementById("pageInfo");
  const sortableCols = document.querySelectorAll(".sortable");
  const resetFiltersBtn = document.getElementById("resetFilters");

  // --- State
  let page = 1;
  const limit = 10;
  let sortCol = "created_at";
  let sortDir = "DESC";

  const API_BASE = "/api/user-activities";

  const makeRequest = async (url, method = "GET", data = null) => {
    const options = { method, headers: { "Content-Type": "application/json" } };
    if (data) options.body = JSON.stringify(data);

    const res = await fetch(url, options);

    if (!res.ok) {
      console.error("HTTP Error", res.status, await res.text());
      throw new Error(`HTTP error ${res.status}`);
    }

    try {
      return await res.json();
    } catch (e) {
      console.error("Failed to parse JSON:", e);
      return { success: false, data: [] }; // fallback
    }
  };


  const formatDate = d => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';

  // --- Render Table
  const renderTable = (rows) => {
    if (!activityTableBody) return;
    activityTableBody.innerHTML = "";

    if (!rows.length) {
      activityTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No activities found</td></tr>';
      return;
    }

    activityTableBody.innerHTML = rows.map(row => `
      <tr>
        <td>${row.activity_type}</td>
        <td>${row.activity_description || ""}</td>
        <td>${row.ip_address || ""}</td>
        <td>${row.user_agent || ""}</td>
        <td>${formatDate(row.created_at)}</td>
        <td>
          <button class="btn btn-sm btn-info" onclick="viewActivityDetails('${row.activity_id}')">View</button>
        </td>
      </tr>
    `).join('');
  };

  // --- Fetch & Load Data
  const loadData = async () => {
    if (!activityTableBody) return;

    const params = new URLSearchParams({
      page,
      limit,
      sort_col: sortCol,
      sort_dir: sortDir,
      search: searchInput?.value || "",
      type: typeFilter?.value || "",
      start: startDate?.value || "",
      end: endDate?.value || ""
    });

    activityTableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';

    try {
      const res = await makeRequest(`${API_BASE}/list?${params}`);
      const data = Array.isArray(res.data) ? res.data : [];
      renderTable(data);
      if (pageInfo) pageInfo.textContent = `Page ${page}`;
    } catch (err) {
      activityTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load activities</td></tr>';
      console.error(err);
    }
  };

  // --- View Activity Details
  window.viewActivityDetails = async (id) => {
    try {
      const res = await makeRequest(`${API_BASE}/show?id=${id}`);
      const row = res.data || res;
      if (!modalContent || !detailsModal) return;

      modalContent.innerHTML = `
        <p><strong>Activity Type:</strong> ${row.activity_type}</p>
        <p><strong>Description:</strong> ${row.activity_description}</p>
        <p><strong>IP Address:</strong> ${row.ip_address}</p>
        <p><strong>User Agent:</strong> ${row.user_agent}</p>
        <p><strong>Date:</strong> ${formatDate(row.created_at)}</p>
      `;
      detailsModal.show();
    } catch (err) {
      alert(`Failed to fetch activity details: ${err.message}`);
    }
  };

  // --- Pagination
  if (prevPageBtn) prevPageBtn.onclick = () => { if (page > 1) { page--; loadData(); } };
  if (nextPageBtn) nextPageBtn.onclick = () => { page++; loadData(); };

  // --- Sorting
  sortableCols.forEach(col => col.addEventListener("click", () => {
    sortCol = col.dataset.col;
    sortDir = sortDir === "ASC" ? "DESC" : "ASC";
    loadData();
  }));

  // --- Reset Filters
  if (resetFiltersBtn) resetFiltersBtn.onclick = () => {
    if (searchInput) searchInput.value = "";
    if (typeFilter) typeFilter.value = "";
    if (startDate) startDate.value = "";
    if (endDate) endDate.value = "";
    loadData();
  };

  // --- Auto-update on typing/filter change
  if (searchInput) searchInput.addEventListener("keyup", loadData);
  if (typeFilter) typeFilter.addEventListener("change", loadData);
  if (startDate) startDate.addEventListener("change", loadData);
  if (endDate) endDate.addEventListener("change", loadData);

  // --- Init
  loadData();
}

document.addEventListener("DOMContentLoaded", () => initActivitiesJs());

// ===============================
// subscribers.js
// ===============================
function initSubscribersJs() {
  // --- DOM Elements
  const tbody = document.getElementById("subscribersTableBody");
  const searchInput = document.getElementById("searchSubscriber");
  const sendEmailBtn = document.getElementById("sendEmailBtn");
  const emailModalEl = document.getElementById("emailModal");
  const emailModal = emailModalEl ? new bootstrap.Modal(emailModalEl) : null;
  const emailForm = document.getElementById("emailForm");
  const progressContainer = document.getElementById("emailProgressContainer");
  const progressBar = document.getElementById("emailProgressBar");
  const progressText = document.getElementById("emailProgressText");
  const sendEmailSubmit = document.getElementById("sendEmailSubmit");
  const subscriberCountEl = document.getElementById("subscriberCount");
  const paginationEl = document.getElementById("subscribersPagination"); // ✅ Corrected

  // --- State
  let allSubscribers = [];
  let filteredSubscribers = [];
  let currentPage = 1;
  const rowsPerPage = 10;

  // -------------------------------
  // Render Table
  // -------------------------------
  const renderTable = () => {
    if (!tbody) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredSubscribers.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      tbody.innerHTML = '<tr><td colspan="3" class="text-center">No subscribers found</td></tr>';
      if (subscriberCountEl) subscriberCountEl.textContent = filteredSubscribers.length;
      renderPagination();
      return;
    }

    tbody.innerHTML = paginated.map((s, index) => `
      <tr>
        <td>${start + index + 1}</td>
        <td>${s.name}</td>
        <td>${s.email}</td>
      </tr>
    `).join('');

    if (subscriberCountEl) subscriberCountEl.textContent = filteredSubscribers.length;
    renderPagination();
  };

  // -------------------------------
  // Render Pagination
  // -------------------------------
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    const totalPages = Math.ceil(filteredSubscribers.length / rowsPerPage);
    if (totalPages <= 1) return;

    const createBtn = (text, disabled, onClick, active = false) => {
      const btn = document.createElement("button");
      btn.className = `btn btn-sm mx-1 ${active ? 'btn-primary' : 'btn-outline-primary'} ${disabled ? 'disabled btn-secondary' : ''}`;
      btn.textContent = text;
      if (!disabled) btn.addEventListener("click", onClick);
      return btn;
    };

    // Prev
    paginationEl.appendChild(createBtn('Prev', currentPage === 1, () => { currentPage--; renderTable(); }));

    // Pages
    for (let i = 1; i <= totalPages; i++) {
      paginationEl.appendChild(createBtn(i, false, () => { currentPage = i; renderTable(); }, currentPage === i));
    }

    // Next
    paginationEl.appendChild(createBtn('Next', currentPage === totalPages, () => { currentPage++; renderTable(); }));
  };

  // -------------------------------
  // Fetch Subscribers
  // -------------------------------
  const fetchSubscribers = async () => {
    if (tbody) tbody.innerHTML = '<tr><td colspan="3" class="text-center">Loading subscribers...</td></tr>';

    try {
      const res = await makeRequest(`${API_BASE}/subscribers`);
      // Normalize name field
      allSubscribers = (res?.data || []).map(s => ({
        ...s,
        email: s.email,
        name: s.full_name // ✅ Consistent naming
      }));

      console.log("All Subscribers:", allSubscribers);

      filteredSubscribers = [...allSubscribers];
      currentPage = 1;
      renderTable();
    } catch {
      if (tbody) tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Failed to load subscribers</td></tr>';
    }
  };

  // -------------------------------
  // Search / Filter
  // -------------------------------
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const term = e.target.value.toLowerCase();
      filteredSubscribers = allSubscribers.filter(s =>
        (s.name || '').toLowerCase().includes(term) ||
        (s.email || '').toLowerCase().includes(term)
      );
      currentPage = 1;
      renderTable();
    });
  }

  // -------------------------------
  // Email Modal Show
  // -------------------------------
  if (sendEmailBtn) {
    sendEmailBtn.addEventListener("click", () => emailModal?.show());
  }

  // -------------------------------
  // Send Emails With Progress
  // -------------------------------
  if (emailForm) {
    emailForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const subject = document.getElementById("emailSubject").value.trim();
      const message = document.getElementById("emailMessage").value.trim();

      if (!subject || !message) return showToast("Subject and message are required", "error");

      progressContainer.style.display = 'block';
      progressBar.style.width = '0%';
      progressBar.textContent = '0%';
      progressText.textContent = '';
      sendEmailSubmit.disabled = true;

      try {
        let sentCount = 0;
        const total = allSubscribers.length;

        if (total === 0) {
          showToast("No subscribers to send emails", "error");
          return;
        }

        for (const sub of allSubscribers) {
          const personalizedMessage = message.replace('{{name}}', sub.name);

          try {
            await makeRequest(`${API_BASE}/send-emails`, 'POST', {
              subject,
              message: personalizedMessage,
              email: sub.email
            });
            sentCount++;
          } catch (err) {
            console.error(`Failed to send email to ${sub.email}:`, err);
          }

          const percent = Math.round((sentCount / total) * 100);
          progressBar.style.width = percent + '%';
          progressBar.textContent = percent + '%';
          progressText.textContent = `Sent ${sentCount} of ${total} emails`;
        }

        showToast(`Emails sent: ${sentCount} of ${total}`, "success");
      } catch {
        showToast("Error sending emails", "error");
      } finally {
        sendEmailSubmit.disabled = false;
        setTimeout(() => { progressContainer.style.display = 'none'; }, 3000);
      }
    });
  }

  // -------------------------------
  // Init
  // -------------------------------
  fetchSubscribers();
}

// ✅ Correct assignment (do NOT call the function here)
window.initSubscribersJs = initSubscribersJs;

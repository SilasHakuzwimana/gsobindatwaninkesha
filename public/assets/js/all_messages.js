function initMessagesJs() {
  const messagesContainer = document.getElementById("messages-list");
  const searchInput = document.getElementById("searchMessage");
  const paginationEl = document.getElementById("pagination");

  let allMessages = [];
  let filteredMessages = [];
  let currentPage = 1;
  const rowsPerPage = 3;

  const formatDate = (d) =>
    d ? new Date(d).toLocaleString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit"
    }) : "";

  const renderMessages = () => {
    if (!messagesContainer) return;

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filteredMessages.slice(start, start + rowsPerPage);

    if (!paginated.length) {
      messagesContainer.innerHTML =
        '<p class="text-center">No messages found</p>';
      renderPagination();
      return;
    }

    messagesContainer.innerHTML = paginated.map(msg => `
      <div class="msg-card">
        <div class="header">
          <strong>${msg.full_name || msg.name}</strong>
          <span class="tag ${msg.source}">${msg.source}</span>
        </div>
        <small>${msg.email}</small><br>
        <small>${formatDate(msg.created_at)}</small>
        <h3>${msg.subject || "(No subject)"}</h3>
        <p>${msg.message}</p>
        ${msg.attachment_url || msg.attachment
        ? `<a href="${msg.attachment_url || msg.attachment}" class="attach" target="_blank">View Attachment</a>`
        : ""
      }
      </div>
    `).join('');

    renderPagination();
  };

  //Render pagination
  const renderPagination = () => {
    if (!paginationEl) return;
    paginationEl.innerHTML = "";

    const totalPages = Math.ceil(filteredMessages.length / rowsPerPage);
    if (totalPages <= 1) return; // No pagination needed

    const createBtn = (text, page, disabled = false, active = false) => {
      const li = document.createElement("li");
      li.className = `page-item ${disabled ? "disabled" : ""} ${active ? "active" : ""}`;
      const a = document.createElement("a");
      a.className = "page-link";
      a.href = "#";
      a.textContent = text;
      if (!disabled && !active) {
        a.addEventListener("click", e => {
          e.preventDefault();
          currentPage = page;
          renderMessages();
        });
      }
      li.appendChild(a);
      return li;
    };

    // Prev button
    paginationEl.appendChild(createBtn("Prev", currentPage - 1, currentPage === 1));

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      paginationEl.appendChild(createBtn(i, i, false, i === currentPage));
    }

    // Next button
    paginationEl.appendChild(createBtn("Next", currentPage + 1, currentPage === totalPages));
  };


  const fetchMessages = async () => {
    if (messagesContainer) messagesContainer.innerHTML = '<p class="text-center">Loading messages...</p>';
    try {
      const res = await fetch("/api/messages");
      const json = await res.json();
      allMessages = (json.data || []).map(m => ({
        ...m,
        full_name: m.full_name || m.name,
        attachment_url: m.attachment_url || m.attachment,
        source: m.source || 'messages'
      }));
      filteredMessages = [...allMessages];
      currentPage = 1;
      renderMessages();
    } catch (err) {
      if (messagesContainer) messagesContainer.innerHTML =
        '<p class="text-center text-danger">Failed to load messages</p>';
    }
  };

  // Search / Filter dynamically
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const term = e.target.value.toLowerCase();
      filteredMessages = allMessages.filter(m =>
        (m.full_name || '').toLowerCase().includes(term) ||
        (m.email || '').toLowerCase().includes(term) ||
        (m.subject || '').toLowerCase().includes(term) ||
        (m.message || '').toLowerCase().includes(term)
      );
      currentPage = 1; // Reset to first page on search
      renderMessages();
    });
  }

  fetchMessages();
}

// Initialize
document.addEventListener("DOMContentLoaded", () => initMessagesJs());

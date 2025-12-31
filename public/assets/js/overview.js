function initOverview() {
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  const fetchOverview = async () => {
    try {
      const res = await fetch('/api/dashboard/overview', {
        headers: {
          'Authorization': 'Bearer ' + getCookie('auth_token')
        }
      });
      const data = await res.json();

      // console.log("Dashboard data: ", data);

      // Use API response directly
      updateTotals(data.totals);
      updateUserStatistics(data.user_statistics);
      updateRecentActivities(data.recent_activities);

    } catch (err) {
      console.error('Dashboard fetch error:', err);
      alert('Failed to load dashboard overview');
    }
  };

  const updateTotals = (totals) => {
    document.getElementById('usersCount').textContent = totals.users || 0;
    document.getElementById('pathways').textContent = totals.pathways || 0;
    document.getElementById('documents').textContent = totals.documents || 0;
    document.getElementById('gallery').textContent = totals.gallery || 0;

    document.getElementById('published-docs').textContent = totals.documents || 0;
    document.getElementById('newsletter-subs').textContent = totals.subscribers || 0;
    document.getElementById('messages').textContent = totals.messages || 0;
  };

  const updateUserStatistics = (stats) => {
    const totalUsers = stats.admins + stats.students + stats.teachers + stats.alumni + stats.guests || 1;

    const adminsPct = Math.round((stats.admins / totalUsers) * 100);
    const studentsPct = Math.round((stats.students / totalUsers) * 100);
    const teachersPct = Math.round((stats.teachers / totalUsers) * 100);
    const alumniPct = Math.round((stats.alumni / totalUsers) * 100);
    const guestsPct = Math.round((stats.guests / totalUsers) * 100);

    document.getElementById('admins-bar').style.width = adminsPct + '%';
    document.getElementById('admins-count').textContent = `${stats.admins} (${adminsPct}%)`;

    document.getElementById('students-bar').style.width = studentsPct + '%';
    document.getElementById('students-count').textContent = `${stats.students} (${studentsPct}%)`;

    document.getElementById('teachers-bar').style.width = teachersPct + '%';
    document.getElementById('teachers-count').textContent = `${stats.teachers} (${teachersPct}%)`;

    document.getElementById('alumni-bar').style.width = alumniPct + '%';
    document.getElementById('alumni-count').textContent = `${stats.alumni} (${alumniPct}%)`;

    document.getElementById('guests-bar').style.width = guestsPct + '%';
    document.getElementById('guests-count').textContent = `${stats.guests} (${guestsPct}%)`;
  };

  const updateRecentActivities = (activities) => {
    const container = document.querySelector('.activity-items-container');
    if (!container) return;
    container.innerHTML = '';

    activities.forEach(act => {
      const div = document.createElement('div');
      div.classList.add('activity-item');
      div.innerHTML = `
        <div class="activity-icon" style="background: #dbeafe; color: #2563eb;">
          <i class="fas fa-info-circle"></i>
        </div>
        <div class="activity-details">
          <div>
            <span class="activity-user">${act.activity_type}</span>
            <span class="activity-action">${act.activity_description}</span>
          </div>
          <div class="activity-time">${act.created_at}</div>
        </div>
      `;
      container.appendChild(div);
    });
  };

  fetchOverview();

}

// document.addEventListener('DOMContentLoaded', () => initOverview());

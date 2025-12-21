function initChartsJs() {
  const API_BASE = "/api/user-activities";

  const chart1El = document.getElementById("activityChart");
  const chart2El = document.getElementById("activityChart2");
  const chart3El = document.getElementById("activityChartType");
  const chart4El = document.getElementById("activityHourlyChart");
  const chart5El = document.getElementById("deviceChart");

  const makeRequest = async (url) => {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
    return res.json();
  };

  // ------------------------------
  // 1. Daily Line Chart
  // ------------------------------
  const buildDailyLineChart = (labels, values) => {
    if (!chart1El) return;

    new Chart(chart1El, {
      type: "line",
      data: {
        labels,
        datasets: [{
          label: "Activities per Day",
          data: values,
          borderWidth: 2,
          borderColor: "#007bff",
          backgroundColor: "rgba(0,123,255,0.1)"
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  };

  // ------------------------------
  // 2. Daily Bar Chart
  // ------------------------------
  const buildDailyBarChart = (labels, values) => {
    if (!chart2El) return;

    new Chart(chart2El, {
      type: "bar",
      data: {
        labels,
        datasets: [{
          label: "Daily Activity Count",
          data: values,
          borderWidth: 2,
          backgroundColor: "rgba(255,99,132,0.4)",
          borderColor: "rgba(255,99,132,1)"
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  };

  // ------------------------------
  // 3. Doughnut Chart (Activity Types)
  // ------------------------------
  const buildActivityTypeChart = (list) => {
    if (!chart3El) return;

    const typeCounts = {};

    list.forEach(item => {
      typeCounts[item.activity_type] = (typeCounts[item.activity_type] || 0) + 1;
    });

    new Chart(chart3El, {
      type: "doughnut",
      data: {
        labels: Object.keys(typeCounts),
        datasets: [{
          data: Object.values(typeCounts)
        }]
      },
      options: { responsive: true }
    });
  };

  // ------------------------------
  // 4. Hourly Activity Chart
  // ------------------------------
  const buildHourlyChart = (list) => {
    if (!chart4El) return;

    const hours = Array(24).fill(0);

    list.forEach(item => {
      const hour = new Date(item.created_at).getHours();
      hours[hour]++;
    });

    new Chart(chart4El, {
      type: "bar",
      data: {
        labels: hours.map((_, i) => `${i}:00`),
        datasets: [{
          label: "Activities per Hour",
          data: hours
        }]
      },
      options: { responsive: true }
    });
  };

  // ------------------------------
  // 5. Device Chart
  // ------------------------------
  const buildDeviceChart = (list) => {
    if (!chart5El) return;

    let mobile = 0;
    let desktop = 0;

    list.forEach(item => {
      const agent = item.user_agent.toLowerCase();
      if (agent.includes("mobile")) mobile++;
      else desktop++;
    });

    new Chart(chart5El, {
      type: "pie",
      data: {
        labels: ["Desktop", "Mobile"],
        datasets: [{
          data: [desktop, mobile]
        }]
      }
    });
  };

  // ------------------------------
  // Load All Charts
  // ------------------------------
  const loadCharts = async () => {
    try {
      const analyticsRes = await makeRequest(`${API_BASE}/analytics`);
      const listRes = await makeRequest(`${API_BASE}/list`);

      const analytics = analyticsRes.data || [];
      const list = listRes.data || [];

      const labels = analytics.map(d => d.day);
      const values = analytics.map(d => d.count);

      buildDailyLineChart(labels, values);
      buildDailyBarChart(labels, values);
      buildActivityTypeChart(list);
      buildHourlyChart(list);
      buildDeviceChart(list);

    } catch (err) {
      console.error("Charts error:", err);
    }
  };

  loadCharts();
}

document.addEventListener("DOMContentLoaded", initChartsJs);

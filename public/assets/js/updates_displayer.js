document.addEventListener("DOMContentLoaded", () => {
  const apiURL = "http://localhost:8000/api/school-updates";
  const container = document.getElementById("updatesContainer");

  // Fetch updates from API
  fetch(apiURL)
    .then(response => response.json())
    .then(data => {
      if (data.status === "success" && data.count > 0) {
        data.data.forEach(update => {
          const column = document.createElement("div");
          column.classList.add("column");
          column.style.backgroundImage = `url('${update.image_path}')`;

          const title = document.createElement("h3");
          title.textContent = update.title;

          const desc = document.createElement("p");
          desc.textContent = update.description;

          column.appendChild(title);
          column.appendChild(desc);

          container.appendChild(column);
        });
      } else {
        container.innerHTML = "<p>No updates found.</p>";
      }
    })
    .catch(err => {
      console.error("Error fetching updates:", err);
      container.innerHTML = "<p>Failed to load updates.</p>";
    });
});


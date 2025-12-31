function animateCounter(id, target, suffix = "", duration = 1500) {
  const element = document.getElementById(id);
  let start = 0;
  const increment = target / (duration / 20);

  const counterInterval = setInterval(() => {
    start += increment;
    if (start >= target) {
      element.textContent = target + suffix;
      clearInterval(counterInterval);
    } else {
      element.textContent = Math.floor(start);
    }
  }, 20);
}

// Run counters after page loads
window.addEventListener("DOMContentLoaded", () => {
  animateCounter("counter1Value", 1350);
  animateCounter("counter2Value", 84);
  animateCounter("counter3Value", 42);

  // Legacy in time (95+)
  setTimeout(() => {
    document.getElementById("counter4Value").textContent = "95+";
  }, 1200);
});


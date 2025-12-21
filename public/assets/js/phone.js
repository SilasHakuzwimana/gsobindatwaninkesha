document.addEventListener('DOMContentLoaded', function () {
  const input = document.querySelector("#phone");
  const iti = window.intlTelInput(input, {
    initialCountry: "auto", // Automatically detect the user's country
    geoIpLookup: function (success, failure) {
      fetch("https://ipinfo.io/json?token=45227cdc30b251")
        .then(response => response.json())
        .then(data => success(data.country))
        .catch(() => success("RW")); // Default to US if lookup fails
    },
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.min.js", // For formatting and validation
  });

  // Optional: Validate the number on form submit
  document.querySelector("form").addEventListener("submit", function (e) {
    if (!iti.isValidNumber()) {
      e.preventDefault();
      alert("Please enter a valid phone number.");
    }
  });
});


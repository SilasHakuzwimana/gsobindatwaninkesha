// assets/js/script.js (defensive version)
document.addEventListener("DOMContentLoaded", function () {
  // helper
  const safe = (fn) => { try { fn(); } catch (e) { console.warn("safe() caught:", e); } };

  // Prevent dummy # links from jumping to top
  safe(() => {
    document.querySelectorAll('a[href="#"]').forEach((a) => {
      a.addEventListener("click", (e) => e.preventDefault());
    });
  });

  // EMAILJS CONTACT FORM (only if present)
  safe(() => {
    const contactForm = document.getElementById("contact-form");
    if (!contactForm) {
      console.info("contact-form not found (ok if page doesn't have it)");
    } else {
      contactForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const name = document.getElementById("names")?.value || "";
        const email = document.getElementById("email")?.value || "";
        const message = document.getElementById("suggestion")?.value || "";

        if (typeof emailjs === "undefined" || !emailjs.send) {
          alert("Email service not initialized. Please try again later.");
          console.warn("emailjs not available");
          return;
        }

        emailjs
          .send("service_5pz7f74", "template_xgi10po", {
            from_name: name,
            from_email: email,
            message: message,
          })
          .then(() => {
            alert("✅ Message Sent Successfully!");
            contactForm.reset();
          })
          .catch((err) => {
            console.error("emailjs error:", err);
            alert("❌ Failed to send message. Try again later.");
          });
      });
    }
  });

  // CUSTOM POPUP (only if elements exist)
  safe(() => {
    const popup = document.getElementById("custom-popup");
    const popupTrigger = document.getElementById("custom-popup-trigger");
    const popupClose = document.getElementById("custom-popup-close");

    if (!popup || !popupTrigger || !popupClose) {
      // Not every page needs popup — just report
      console.info("Popup elements missing (ok if not used on this page)");
    } else {
      popupTrigger.addEventListener("click", () => (popup.style.display = "block"));
      popupClose.addEventListener("click", () => (popup.style.display = "none"));
      window.addEventListener("click", (event) => {
        if (event.target === popup) popup.style.display = "none";
      });
    }
  });

  // BOOTSTRAP DROPDOWNS (hover on desktop)
  safe(() => {
    const dropdowns = document.querySelectorAll(".navbar .dropdown");
    if (!dropdowns.length) {
      console.info("No .navbar .dropdown elements found");
    }
    dropdowns.forEach((dropdown) => {
      const toggle = dropdown.querySelector(".dropdown-toggle");
      if (!toggle) return;
      // Only attempt hover behavior if bootstrap is present
      if (window.bootstrap && window.bootstrap.Dropdown && window.innerWidth >= 992) {
        dropdown.addEventListener("mouseenter", () => {
          safe(() => window.bootstrap.Dropdown.getOrCreateInstance(toggle).show());
        });
        dropdown.addEventListener("mouseleave", () => {
          safe(() => window.bootstrap.Dropdown.getOrCreateInstance(toggle).hide());
        });
      }
    });
  });

  // AUTO COLLAPSE NAVBAR ON LINK CLICK (mobile) - only if bootstrap present
  safe(() => {
    const navLinks = document.querySelectorAll(".navbar-collapse .nav-link");
    const navbarCollapse = document.querySelector(".navbar-collapse");
    if (navLinks.length && navbarCollapse) {
      navLinks.forEach((link) => {
        link.addEventListener("click", () => {
          if (navbarCollapse.classList.contains("show") && window.bootstrap && window.bootstrap.Collapse) {
            safe(() => new bootstrap.Collapse(navbarCollapse).hide());
          }
        });
      });
    }
  });

  // SMOOTH SCROLLING FOR VALID INTERNAL LINKS
  safe(() => {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        const href = this.getAttribute("href");
        if (!href || href === "#" || href === "#!" || href.trim().length === 0) {
          e.preventDefault();
          return;
        }
        let target = null;
        try {
          target = document.querySelector(href);
        } catch (err) {
          // invalid selector — ignore
          return;
        }
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: "smooth" });
        }
      });
    });
  });

});

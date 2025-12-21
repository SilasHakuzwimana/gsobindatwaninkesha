function animateOnScroll() {
    const element = document.getElementById("backdrop");
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                element.classList.add("animate-me");
            }
        });
    });
    observer.observe(element);
}

// Call the function to set up the scroll-triggered animation
animateOnScroll();
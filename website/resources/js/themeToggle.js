document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById("theme-toggle");

    // Load saved theme from localStorage
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
        document.body.setAttribute("data-theme", savedTheme);
    }

    // Toggle theme on button click
    toggleBtn.addEventListener("click", () => {
        const currentTheme = document.body.getAttribute("data-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        document.body.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
    });
});
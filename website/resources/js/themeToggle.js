document.addEventListener('DOMContentLoaded', function () {
  // Main Nav Elements
  const toggleBtn = document.getElementById("theme-toggle");
  const themeIcon = document.getElementById("theme-icon");
  const logo = document.getElementById("logo");

  // Side Nav Elements
  const toggleBtnSideNav = document.getElementById("theme-toggle-side-nav");
  const themeIconSideNav = document.getElementById("theme-icon-side-nav");
  const labelSideNav = document.getElementById("label-side-nav");

  // Asset Mappings
  const icons = {
    light: "url('/images/icon_sun.svg')",
    dark: "url('/images/icon_moon.svg')"
  };
  const labels = {
    light: "Light",
    dark: "Dark"
  };
  const logos = {
    light: "/images/Light_logo.svg",
    dark: "/images/Dark_logo.svg"
  };

  function applyTheme(theme) {
    // global theme attribute
    document.body.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);

    // main toggle button
    if (themeIcon) themeIcon.style.setProperty('--icon-url', icons[theme]);
    if (logo) logo.src = logos[theme];

    // Side Nav
    if (themeIconSideNav) {
      themeIconSideNav.style.setProperty('--icon-url', icons[theme]);
    }
    if (labelSideNav) {
      labelSideNav.textContent = labels[theme];
    }
  }

  const savedTheme = localStorage.getItem("theme") || "dark";
  applyTheme(savedTheme);

  if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      const current = document.body.getAttribute("data-theme");
      applyTheme(current === "dark" ? "light" : "dark");
    });
  }

  if (toggleBtnSideNav) {
    toggleBtnSideNav.addEventListener("click", () => {
      const current = document.body.getAttribute("data-theme");
      applyTheme(current === "dark" ? "light" : "dark");
    });
  }
});
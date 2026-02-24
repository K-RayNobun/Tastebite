// Responsive navigation menu
const navToggle = document.getElementById("nav-toggle");
const navMenu = document.getElementById("nav-menu");

navToggle.addEventListener("click", function () {
  navMenu.classList.toggle("show");
});

// Close nav menu when window is resized above breakpoint
window.addEventListener("resize", function () {
  if(window.innerWidth > 900) {
    navMenu.classList.remove("show");
  }
});
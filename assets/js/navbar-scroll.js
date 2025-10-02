document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.getElementById("navbar");
  window.addEventListener("scroll", function () {
    if (window.scrollY > 10) {
      navbar.classList.remove("bg-transparent");
      navbar.classList.add("bg-white", "shadow");
    } else {
      navbar.classList.add("bg-transparent");
      navbar.classList.remove("bg-white", "shadow");
    }
  });
});

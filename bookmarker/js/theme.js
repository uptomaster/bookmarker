document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("themeToggle");
  if (!toggle) return;

  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    document.body.classList.add("dark");
  }

  toggle.addEventListener("click", function () {
    document.body.classList.toggle("dark");
    const isDark = document.body.classList.contains("dark");
    localStorage.setItem("theme", isDark ? "dark" : "light");
  });
});

// Mobile menu + submenu toggles for header.php.
export default function initHeader() {
  const toggle = document.querySelector(".site-header__toggle");
  const nav = document.getElementById("site-nav");

  if (toggle && nav) {
    toggle.addEventListener("click", () => {
      const open = toggle.getAttribute("aria-expanded") !== "true";
      toggle.setAttribute("aria-expanded", String(open));
      nav.classList.toggle("is-open", open);
      document.body.classList.toggle("menu-open", open);
    });
  }

  // Buttons are added by the walker_nav_menu_start_el filter in inc/setup.php.
  document.querySelectorAll(".nav-submenu-toggle").forEach((btn) => {
    btn.addEventListener("click", () => {
      const open = btn.closest("li").classList.toggle("submenu-open");
      btn.setAttribute("aria-expanded", String(open));
    });
  });
}

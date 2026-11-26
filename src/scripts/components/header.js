/**
 * Site header (header.php): mobile expanded menu, submenu toggles,
 * shadow after scrolling.
 */
const MOBILE_QUERY = "(max-width: 1200px)"; // Same as breakpoint-max(xl).

export default function initHeader() {
  const header = document.querySelector("[data-site-header]");
  if (!header) return;

  const toggle = header.querySelector(".site-header__toggle");
  const panel = document.getElementById("site-mobile-nav");
  const mobile = window.matchMedia(MOBILE_QUERY);

  // ---------- Mobile panel ----------

  const setOpen = (open) => {
    if (!toggle || !panel) return;

    // Panel starts right under the header (accounts for the admin bar).
    panel.style.setProperty("--header-bottom", `${Math.max(0, header.getBoundingClientRect().bottom)}px`);

    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? toggle.dataset.labelClose : toggle.dataset.labelOpen);
    panel.classList.toggle("is-open", open);
    document.body.classList.toggle("menu-open", open);
  };

  if (toggle && panel) {
    toggle.addEventListener("click", () => {
      setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && panel.classList.contains("is-open")) {
        setOpen(false);
        toggle.focus();
      }
    });

    // Close when the screen grows to desktop.
    mobile.addEventListener("change", (event) => {
      if (!event.matches) setOpen(false);
    });
  }

  // ---------- Submenus ----------
  // Buttons come from niyom_submenu_toggle() (inc/template-tags.php).

  const closeAll = (except) => {
    header.querySelectorAll(".submenu-open").forEach((li) => {
      if (li === except) return;
      li.classList.remove("submenu-open");
      const btn = li.querySelector(":scope > .nav-submenu-toggle");
      if (btn) btn.setAttribute("aria-expanded", "false");
    });
  };

  header.querySelectorAll(".nav-submenu-toggle").forEach((btn) => {
    btn.addEventListener("click", (event) => {
      event.stopPropagation();
      const li = btn.closest("li");
      const open = !li.classList.contains("submenu-open");

      // Desktop: one dropdown at a time.
      if (li.closest(".site-header__menu")) closeAll(li);

      li.classList.toggle("submenu-open", open);
      btn.setAttribute("aria-expanded", String(open));
    });
  });

  // Current page inside a submenu: open its parent in the mobile menu.
  header.querySelectorAll(".site-header__mobile-menu .current-menu-ancestor, .site-header__mobile-menu .current-menu-parent").forEach((li) => {
    li.classList.add("submenu-open");
    const btn = li.querySelector(":scope > .nav-submenu-toggle");
    if (btn) btn.setAttribute("aria-expanded", "true");
  });

  // Close desktop dropdowns on outside click / Escape.
  document.addEventListener("click", (event) => {
    if (!event.target.closest(".site-header__menu")) {
      header.querySelectorAll(".site-header__menu .submenu-open").forEach((li) => {
        li.classList.remove("submenu-open");
        const btn = li.querySelector(":scope > .nav-submenu-toggle");
        if (btn) btn.setAttribute("aria-expanded", "false");
      });
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;
    const open = header.querySelector(".site-header__menu .submenu-open");
    if (open) {
      open.classList.remove("submenu-open");
      const btn = open.querySelector(":scope > .nav-submenu-toggle");
      if (btn) {
        btn.setAttribute("aria-expanded", "false");
        btn.focus();
      }
    }
  });

  // ---------- Shadow after scroll ----------

  const onScroll = () => header.classList.toggle("is-scrolled", window.scrollY > 4);
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });
}

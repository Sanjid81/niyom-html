/**
 * Footer link columns (footer.php): accordion on mobile.
 * On desktop the toggle buttons are disabled by CSS, so lists stay open.
 */
export default function initFooter() {
  document.querySelectorAll("[data-footer-col]").forEach((col) => {
    const btn = col.querySelector(".site-footer__col-toggle");
    if (!btn) return;

    btn.addEventListener("click", () => {
      const open = !col.classList.contains("is-open");
      col.classList.toggle("is-open", open);
      btn.setAttribute("aria-expanded", String(open));
    });
  });
}

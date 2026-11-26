// =====================================================================
// Shared FAQ accordion (components/Shared/faq.php).
// Adds .is-ready so answers collapse (CSS animates grid rows), then
// toggles .is-open + aria-expanded on click. Items open independently.
// No-op when the page has no FAQ.
// =====================================================================

export default function initSharedFaq() {
  const sections = document.querySelectorAll("[data-shared-faq]");

  sections.forEach((section) => {
    section.classList.add("is-ready");

    section.querySelectorAll("button.shared-faq-toggle").forEach((button) => {
      const item = button.closest(".shared-faq-item");
      if (!item) return;

      button.addEventListener("click", () => {
        const open = !item.classList.contains("is-open");
        item.classList.toggle("is-open", open);
        button.setAttribute("aria-expanded", open ? "true" : "false");
      });
    });
  });
}

// =====================================================================
// Home › What We Do — markup: components/Home/what-we-do.php
//
// Desktop/tablet (> 768px): the stacking itself is pure CSS
// (position: sticky). This script only:
//   - sets --wwd-cover (0 → 1) on each card while the next card slides
//     over it, so the covered card shrinks/dims a little;
//   - adds .is-unstacked when a card is taller than the space under the
//     header (sticky would hide its bottom), turning stacking off.
// Mobile (≤ 768px): accordion. The first item starts open; opening one
// closes the others. Without JS every item stays open.
// Does nothing when the section is not on the page.
// =====================================================================

const MOBILE_QUERY = "(max-width: 768px)";
const REDUCED_QUERY = "(prefers-reduced-motion: reduce)";

function setupSection(section, mobileMq, reducedMq) {
  const items = Array.from(section.querySelectorAll("[data-wwd-item]"));
  if (!items.length) {
    return;
  }

  // ---------------- Stacking polish (desktop/tablet) ----------------

  let ticking = false;

  const clearCover = () => {
    items.forEach((item) => item.style.removeProperty("--wwd-cover"));
  };

  const stickyTop = () => parseFloat(getComputedStyle(items[0]).top) || 0;

  // Stacking only makes sense if each card fits under the header.
  const checkFit = () => {
    if (mobileMq.matches) {
      section.classList.remove("is-unstacked");
      return;
    }
    const available = window.innerHeight - stickyTop();
    const tooTall = items.some((item) => item.offsetHeight > available);
    section.classList.toggle("is-unstacked", tooTall);
  };

  const updateCover = () => {
    ticking = false;

    if (mobileMq.matches || reducedMq.matches || section.classList.contains("is-unstacked")) {
      clearCover();
      return;
    }

    const top = stickyTop();
    items.forEach((item, i) => {
      const next = items[i + 1];
      if (!next) {
        item.style.setProperty("--wwd-cover", "0");
        return;
      }
      const height = item.offsetHeight || 1;
      const covered = (top + height - next.getBoundingClientRect().top) / height;
      const value = Math.min(1, Math.max(0, covered));
      item.style.setProperty("--wwd-cover", value.toFixed(3));
    });
  };

  const requestCover = () => {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(updateCover);
    }
  };

  // ---------------- Accordion (mobile) ----------------

  const parts = items
    .map((item) => ({
      item,
      head: item.querySelector("[data-wwd-toggle]"),
      media: item.querySelector("[data-wwd-media]"),
      panel: item.querySelector("[data-wwd-panel]"),
    }))
    .filter((part) => part.head && part.panel);

  const setOpen = (part, open) => {
    part.item.classList.toggle("is-open", open);
    if (section.classList.contains("is-accordion")) {
      part.head.setAttribute("aria-expanded", open ? "true" : "false");
    }
  };

  const toggle = (part) => {
    if (!section.classList.contains("is-accordion")) {
      return;
    }
    const open = !part.item.classList.contains("is-open");
    parts.forEach((other) => setOpen(other, other === part ? open : false));
  };

  const enableAccordion = () => {
    section.classList.add("is-accordion");
    parts.forEach((part) => {
      part.head.setAttribute("role", "button");
      part.head.setAttribute("tabindex", "0");
      part.head.setAttribute("aria-expanded", part.item.classList.contains("is-open") ? "true" : "false");
    });
  };

  const disableAccordion = () => {
    section.classList.remove("is-accordion");
    parts.forEach((part) => {
      part.head.removeAttribute("role");
      part.head.removeAttribute("tabindex");
      part.head.removeAttribute("aria-expanded");
    });
  };

  parts.forEach((part) => {
    part.head.addEventListener("click", (event) => {
      // Let links inside the title work normally.
      if (event.target.closest("a")) {
        return;
      }
      toggle(part);
    });
    part.head.addEventListener("keydown", (event) => {
      if (event.key === "Enter" || event.key === " ") {
        if (!section.classList.contains("is-accordion")) {
          return;
        }
        event.preventDefault();
        toggle(part);
      }
    });
    if (part.media) {
      part.media.addEventListener("click", () => toggle(part));
    }
  });

  // ---------------- Mode switching ----------------

  const applyMode = () => {
    if (mobileMq.matches) {
      enableAccordion();
    } else {
      disableAccordion();
    }
    checkFit();
    updateCover();
  };

  applyMode();

  mobileMq.addEventListener("change", applyMode);
  reducedMq.addEventListener("change", updateCover);
  window.addEventListener("scroll", requestCover, { passive: true });
  window.addEventListener("resize", () => {
    checkFit();
    requestCover();
  });
  // Images change card heights once loaded.
  window.addEventListener("load", () => {
    checkFit();
    requestCover();
  });
}

export default function initHomeWhatWeDo() {
  const sections = document.querySelectorAll("[data-home-wwd]");
  if (!sections.length) {
    return;
  }

  const mobileMq = window.matchMedia(MOBILE_QUERY);
  const reducedMq = window.matchMedia(REDUCED_QUERY);

  sections.forEach((section) => setupSection(section, mobileMq, reducedMq));
}

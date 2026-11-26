/**
 * Home Expertise card slider (components/Home/expertise.php).
 *
 * Swiper with free-width cards that run off the right edge. The pager under
 * the cards ("‹ 01 / 03 ›") counts slider positions, not cards: the last
 * position is reached when the last card is fully in view. The pager is
 * hidden when every card already fits.
 */
import Swiper from "swiper";
import { A11y, Keyboard, Navigation } from "swiper/modules";
// Swiper CSS is imported in src/scripts/index.js, before the theme styles.

const reduceMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const pad = (n) => String(n).padStart(2, "0");

function setupExpertise(root) {
  const sliderEl = root.querySelector(".home-expertise__slider");
  const pager = root.querySelector(".home-expertise__pager");
  if (!sliderEl) return;

  const prev = root.querySelector(".home-expertise__nav--prev");
  const next = root.querySelector(".home-expertise__nav--next");
  const current = root.querySelector(".home-expertise__current");
  const total = root.querySelector(".home-expertise__total");

  // Position count + arrow states.
  const updatePager = (s) => {
    const positions = Math.max(1, s.snapGrid.length);
    const index = Math.min(s.snapIndex, positions - 1);

    if (current) current.textContent = pad(index + 1);
    if (total) total.textContent = ` / ${pad(positions)}`;
    if (pager) pager.hidden = positions < 2;
  };

  // eslint-disable-next-line no-new
  new Swiper(sliderEl, {
    modules: [A11y, Keyboard, Navigation],
    slidesPerView: "auto",
    spaceBetween: 16,
    slidesOffsetAfter: 16,
    speed: reduceMotion() ? 0 : 600,
    grabCursor: true,
    watchOverflow: true,
    keyboard: { enabled: true, onlyInViewport: true },
    a11y: { enabled: true, slideRole: "group" },
    navigation: {
      prevEl: prev,
      nextEl: next,
      disabledClass: "is-disabled",
    },
    breakpoints: {
      // Desktop / tablet (Figma desktop: 30px gap, 80px after the last card).
      769: { spaceBetween: 30, slidesOffsetAfter: 80 },
    },
    on: {
      init: (s) => {
        root.classList.add("is-ready");
        updatePager(s);
      },
      slideChange: updatePager,
      snapIndexChange: updatePager,
      snapGridLengthChange: updatePager,
      resize: updatePager,
    },
  });
}

export default function initHomeExpertise() {
  document.querySelectorAll("[data-home-expertise]").forEach(setupExpertise);
}

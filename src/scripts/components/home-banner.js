/**
 * Home Banner slider (components/Home/banner.php).
 *
 * Swiper with a cross-fade. The labels along the bottom are tabs: click one
 * to jump to its slide. While autoplay runs, the active tab's top line fills
 * up to show the time left on that slide.
 */
import Swiper from "swiper";
import { A11y, Autoplay, EffectFade, Keyboard } from "swiper/modules";
// Swiper CSS is imported in src/scripts/index.js, before the theme styles.

const reduceMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;

function setupBanner(root) {
  const sliderEl = root.querySelector(".home-banner-slider");
  const tabs = Array.from(root.querySelectorAll(".home-banner-tab"));
  const slideCount = root.querySelectorAll(".home-banner-slide").length;

  if (!sliderEl || slideCount < 2) {
    return;
  }

  const reduced = reduceMotion();
  const wantsAutoplay = root.dataset.autoplay === "true" && !reduced;
  const delay = parseInt(root.dataset.delay, 10) || 6000;

  const tabsRow = root.querySelector(".home-banner-tabs");
  const visible = Math.min(3, tabs.length);

  // Show 3 tabs at a time; keep the active one in the middle when possible.
  const moveTabWindow = (index) => {
    const start = Math.max(0, Math.min(index - 1, tabs.length - visible));
    if (tabsRow) tabsRow.style.setProperty("--tab-start", String(start));

    tabs.forEach((tab, i) => {
      const pos = i - start;
      tab.classList.toggle("is-pos-first", pos === 0);
      tab.classList.toggle("is-pos-last", pos === visible - 1);
    });
  };

  const setActiveTab = (index) => {
    moveTabWindow(index);

    tabs.forEach((tab, i) => {
      const active = i === index;
      tab.classList.toggle("is-active", active);
      tab.setAttribute("aria-selected", active ? "true" : "false");
      tab.setAttribute("tabindex", active ? "0" : "-1");
      tab.style.setProperty("--progress", active && !wantsAutoplay ? "1" : "0");
    });
  };

  const swiper = new Swiper(sliderEl, {
    modules: [A11y, Autoplay, EffectFade, Keyboard],
    effect: "fade",
    fadeEffect: { crossFade: true },
    speed: reduced ? 0 : 1200,
    rewind: true,
    grabCursor: true,
    keyboard: { enabled: true, onlyInViewport: true },
    a11y: { enabled: true, slideRole: "tabpanel" },
    autoplay: wantsAutoplay
      ? { delay, disableOnInteraction: false, pauseOnMouseEnter: true }
      : false,
    on: {
      init(s) {
        root.classList.add("is-ready");
        setActiveTab(s.realIndex);
      },
      slideChange(s) {
        setActiveTab(s.realIndex);
      },
      autoplayTimeLeft(s, timeLeft, progress) {
        const tab = tabs[s.realIndex];
        if (tab) {
          tab.style.setProperty("--progress", String(1 - progress));
        }
      },
    },
  });

  // Tabs: click, and arrow / Home / End keys (WAI-ARIA tabs pattern).
  tabs.forEach((tab, i) => {
    tab.addEventListener("click", () => swiper.slideTo(i));

    tab.addEventListener("keydown", (event) => {
      let next = null;
      if (event.key === "ArrowRight") next = (i + 1) % tabs.length;
      if (event.key === "ArrowLeft") next = (i - 1 + tabs.length) % tabs.length;
      if (event.key === "Home") next = 0;
      if (event.key === "End") next = tabs.length - 1;
      if (next === null) return;

      event.preventDefault();
      tabs[next].focus();
      swiper.slideTo(next);
    });
  });

  // Pause while a keyboard user is inside the banner.
  if (wantsAutoplay) {
    root.addEventListener("focusin", () => swiper.autoplay.pause());
    root.addEventListener("focusout", (event) => {
      if (!root.contains(event.relatedTarget)) swiper.autoplay.resume();
    });
  }
}

export default function initHomeBanner() {
  document.querySelectorAll("[data-home-banner]").forEach(setupBanner);
}

/**
 * Events & Partnerships Hub (components/EventsHub/events.php + pilots.php).
 *
 * Events: Upcoming / Past / All tabs and type pills filter the event cards.
 * Each card's (end) date is compared with the visitor's today, so the split
 * stays right on cached pages. The tab shown first falls back to "All" when
 * it has no events. Without JS the controls stay hidden and all cards show.
 *
 * Pilots: Swiper slider with a "‹ 01 / 03 ›" pager that counts slider
 * positions (same behaviour as the Home Expertise slider).
 */
import Swiper from "swiper";
import { A11y, Keyboard, Navigation } from "swiper/modules";
// Swiper CSS is imported in src/scripts/index.js, before the theme styles.

const pad = (n) => String(n).padStart(2, "0");
const reduceMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;

// Visitor's local date as Y-m-d (same format as the data attributes).
function todayString() {
  const d = new Date();
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

// ---------- Events: tabs + type filters ----------

function setupEvents(root) {
  const controls = root.querySelector(".eh-events-controls");
  const list = root.querySelector(".eh-events-list");
  if (!list) return;

  const tabs = Array.from(root.querySelectorAll(".eh-events-tab"));
  const pills = Array.from(root.querySelectorAll(".eh-events-filter"));
  const count = root.querySelector(".eh-events-tab-count");
  const empty = root.querySelector(".eh-events-empty");
  const status = root.querySelector(".eh-events-status");
  const today = todayString();

  const cards = Array.from(list.querySelectorAll(".eh-events-card")).map((el) => {
    const last = el.dataset.end || el.dataset.start || "";
    let state = "";
    if (last) state = last >= today ? "upcoming" : "past";
    el.dataset.status = state;
    return { el, state, category: el.dataset.category || "" };
  });

  const matchesTab = (card, tab) => tab === "all" || card.state === tab;
  const matchesCategory = (card, category) => !category || card.category === category;

  let tab = tabs.length ? root.dataset.defaultTab || "upcoming" : "all";
  let category = "";

  // First tab falls back to "All" when it has no events.
  if (tabs.length && !cards.some((card) => matchesTab(card, tab))) tab = "all";

  const render = (announce) => {
    let shown = 0;
    cards.forEach((card) => {
      const visible = matchesTab(card, tab) && matchesCategory(card, category);
      card.el.hidden = !visible;
      if (visible) shown += 1;
    });

    tabs.forEach((button) => {
      const active = button.dataset.tab === tab;
      button.setAttribute("aria-selected", active ? "true" : "false");
      button.tabIndex = active ? 0 : -1;
      if (active) list.setAttribute("aria-labelledby", button.id);
    });

    pills.forEach((pill) => {
      pill.setAttribute("aria-pressed", (pill.dataset.category || "") === category ? "true" : "false");
    });

    if (count) {
      const upcoming = cards.filter((card) => card.state === "upcoming" && matchesCategory(card, category)).length;
      count.textContent = ` (${upcoming})`;
    }

    if (empty) empty.hidden = shown > 0;

    if (announce && status) {
      const one = root.dataset.countOne || "Showing 1 event";
      const many = root.dataset.countMany || "Showing %d events";
      status.textContent = shown === 1 ? one : many.replace("%d", shown);
    }
  };

  tabs.forEach((button, index) => {
    button.addEventListener("click", () => {
      tab = button.dataset.tab;
      render(true);
    });

    // Arrow keys / Home / End move between tabs (WAI-ARIA tabs pattern).
    button.addEventListener("keydown", (event) => {
      let next = null;
      if (event.key === "ArrowRight") next = (index + 1) % tabs.length;
      if (event.key === "ArrowLeft") next = (index - 1 + tabs.length) % tabs.length;
      if (event.key === "Home") next = 0;
      if (event.key === "End") next = tabs.length - 1;
      if (next === null) return;
      event.preventDefault();
      tab = tabs[next].dataset.tab;
      render(true);
      tabs[next].focus();
    });
  });

  pills.forEach((pill) => {
    pill.addEventListener("click", () => {
      category = pill.dataset.category || "";
      render(true);
    });
  });

  if (controls) controls.hidden = false;
  root.classList.add("is-ready");
  render(false);
}

// ---------- Pilots slider ----------

function setupPilots(root) {
  const sliderEl = root.querySelector(".eh-pilots-slider");
  if (!sliderEl) return;

  const pager = root.querySelector(".eh-pilots-pager");
  const prev = root.querySelector(".eh-pilots-nav--prev");
  const next = root.querySelector(".eh-pilots-nav--next");
  const current = root.querySelector(".eh-pilots-current");
  const total = root.querySelector(".eh-pilots-total");

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
    spaceBetween: 24,
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
      0: { spaceBetween: 16 },
      769: { spaceBetween: 24 },
    },
    on: {
      beforeInit: () => root.classList.add("is-ready"),
      init: updatePager,
      slideChange: updatePager,
      snapIndexChange: updatePager,
      snapGridLengthChange: updatePager,
      resize: updatePager,
    },
  });
}

export default function initEventsHub() {
  document.querySelectorAll("[data-eh-events]").forEach(setupEvents);
  document.querySelectorAll("[data-eh-pilots]").forEach(setupPilots);
}

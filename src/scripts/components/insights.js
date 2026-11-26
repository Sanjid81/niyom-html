/**
 * Insights listing page + single article.
 *
 * Grid (components/Insights/grid.php): category tabs and "Load more" fetch
 * card HTML from /wp-json/niyom/v1/insights (inc/insights-meta.php)
 * instead of reloading the page. Without JS (or if a request fails) the
 * tabs and button are plain links (?category=slug, ?show=2).
 *
 * Article (components/Insights/single-body.php):
 *   - "On this page": highlights the section in view (IntersectionObserver);
 *     on tablet/mobile the list collapses behind a toggle button.
 *   - Copy-link share button (shown only when the Clipboard API exists).
 *
 * Does nothing on pages without these sections.
 */

const format = (template, shown, total) =>
  template.replace("%1$d", shown).replace("%2$d", total);

// ---------- Listing: tabs + load more ----------

function setupGrid(root) {
  const endpoint = root.dataset.endpoint;
  const panel = root.querySelector(".in-grid-panel");
  const list = root.querySelector(".in-cards");
  if (!endpoint || !panel || !list || !window.fetch) return;

  const tabs = Array.from(root.querySelectorAll(".in-grid-tab"));
  const empty = root.querySelector(".in-grid-empty");
  const status = root.querySelector(".in-grid-status");
  const more = root.querySelector(".in-grid-more");
  const moreButton = root.querySelector(".in-grid-more-button");

  const state = {
    category: root.dataset.category || "",
    page: Number(root.dataset.page) || 1,
    pages: Number(root.dataset.pages) || 0,
  };
  let controller = null;

  const fetchPage = async (category, page) => {
    const url = new URL(endpoint, window.location.href);
    url.searchParams.set("category", category);
    url.searchParams.set("page", page);
    url.searchParams.set("per_page", root.dataset.perPage || "8");
    url.searchParams.set("exclude", root.dataset.exclude || "0");
    url.searchParams.set("link_text", root.dataset.linkText || "");

    if (controller) controller.abort();
    controller = new AbortController();

    const response = await fetch(url.toString(), {
      signal: controller.signal,
      headers: { Accept: "application/json" },
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response.json();
  };

  const announce = (text) => {
    if (status) status.textContent = text;
  };

  const update = (data) => {
    state.page = data.page;
    state.pages = data.pages;

    const hasCards = data.total > 0;
    list.hidden = !hasCards;
    if (empty) empty.hidden = hasCards;
    if (more) more.hidden = state.page >= state.pages;

    if (moreButton) {
      const url = new URL(moreButton.href, window.location.href);
      if (state.category) url.searchParams.set("category", state.category);
      else url.searchParams.delete("category");
      url.searchParams.set("show", String(state.page + 1));
      moreButton.href = url.toString();
    }

    const template = data.shown === 1 ? root.dataset.countOne : root.dataset.countMany;
    if (template) announce(format(template, data.shown, data.total));
  };

  const setBusy = (busy, el) => {
    panel.setAttribute("aria-busy", busy ? "true" : "false");
    if (el) el.setAttribute("aria-busy", busy ? "true" : "false");
  };

  // Tabs: show the first page of a category.
  const selectTab = async (tab, moveFocus) => {
    tabs.forEach((item) => {
      const active = item === tab;
      item.setAttribute("aria-selected", active ? "true" : "false");
      item.tabIndex = active ? 0 : -1;
    });
    panel.setAttribute("aria-labelledby", tab.id);
    if (moveFocus) tab.focus();

    state.category = tab.dataset.category || "";
    if (root.dataset.loadingText) announce(root.dataset.loadingText);
    setBusy(true);

    try {
      const data = await fetchPage(state.category, 1);
      list.innerHTML = data.html;
      update(data);
      setBusy(false);

      // Keep the address shareable (?category=slug), without reloading.
      const address = new URL(tab.href, window.location.href);
      address.hash = "";
      window.history.replaceState(null, "", address.toString());
    } catch (error) {
      if (error.name === "AbortError") return;
      window.location.href = tab.href; // Fall back to the plain link.
    }
  };

  tabs.forEach((tab, index) => {
    tab.addEventListener("click", (event) => {
      event.preventDefault();
      if (tab.getAttribute("aria-selected") === "true") return;
      selectTab(tab, false);
    });

    // Arrow keys / Home / End move between tabs (WAI-ARIA tabs pattern).
    tab.addEventListener("keydown", (event) => {
      let next = null;
      if (event.key === "ArrowRight") next = (index + 1) % tabs.length;
      if (event.key === "ArrowLeft") next = (index - 1 + tabs.length) % tabs.length;
      if (event.key === "Home") next = 0;
      if (event.key === "End") next = tabs.length - 1;
      if (event.key === " ") {
        event.preventDefault();
        tab.click();
        return;
      }
      if (next === null) return;
      event.preventDefault();
      selectTab(tabs[next], true);
    });
  });

  // Load more: append the next page and move focus to its first card.
  if (moreButton) {
    moreButton.addEventListener("click", async (event) => {
      event.preventDefault();
      if (moreButton.getAttribute("aria-busy") === "true") return;

      const fallback = moreButton.href;
      setBusy(true, moreButton);
      if (root.dataset.loadingText) announce(root.dataset.loadingText);

      try {
        const data = await fetchPage(state.category, state.page + 1);
        const before = list.children.length;
        list.insertAdjacentHTML("beforeend", data.html);
        update(data);
        setBusy(false, moreButton);

        const first = list.children[before];
        const link = first && first.querySelector(".in-card-title a");
        if (link) link.focus({ preventScroll: false });
      } catch (error) {
        if (error.name === "AbortError") return;
        window.location.href = fallback;
      }
    });
  }

  root.classList.add("is-ready");
}

// ---------- Article: "On this page" ----------

function setupToc(nav) {
  const links = Array.from(nav.querySelectorAll(".article-toc-link"));
  const list = nav.querySelector(".article-toc-list");
  const toggle = nav.querySelector(".article-toc-toggle");
  if (!links.length || !list) return;

  // Collapsible on tablet/mobile (CSS shows the toggle below 992px).
  const compact = window.matchMedia("(max-width: 992px)");

  const setOpen = (open) => {
    if (!toggle) return;
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    list.hidden = compact.matches && !open;
  };

  if (toggle) {
    toggle.hidden = false;
    toggle.addEventListener("click", () => {
      setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });

    const onChange = () => setOpen(!compact.matches);
    if (compact.addEventListener) compact.addEventListener("change", onChange);
    else compact.addListener(onChange);
    setOpen(!compact.matches);
  }

  nav.classList.add("is-ready");

  // Close the list after choosing a section on small screens.
  links.forEach((link) => {
    link.addEventListener("click", () => {
      if (compact.matches) setOpen(false);
    });
  });

  // Highlight the section in view.
  const sections = links
    .map((link) => {
      const id = decodeURIComponent(link.hash.slice(1));
      const target = id ? document.getElementById(id) : null;
      return target ? { link, target } : null;
    })
    .filter(Boolean);

  if (!sections.length || !("IntersectionObserver" in window)) return;

  let current = null;
  const setActive = (item) => {
    if (item === current) return;
    current = item;
    sections.forEach(({ link }) => {
      const active = item && link === item.link;
      link.classList.toggle("is-active", Boolean(active));
      if (active) link.setAttribute("aria-current", "true");
      else link.removeAttribute("aria-current");
    });
  };

  // Active = the last heading above 40% of the viewport (the first one
  // until the reader reaches it).
  const refresh = () => {
    const line = window.innerHeight * 0.4;
    let active = sections[0];
    sections.forEach((item) => {
      if (item.target.getBoundingClientRect().top <= line) active = item;
    });
    setActive(active);
  };

  const observer = new IntersectionObserver(refresh, {
    rootMargin: "0px 0px -60% 0px",
    threshold: [0, 1],
  });
  sections.forEach(({ target }) => observer.observe(target));
  refresh();
}

// ---------- Article: copy link ----------

// Clipboard API on HTTPS; older copy command elsewhere (e.g. local http sites).
async function copyText(text) {
  if (navigator.clipboard && window.isSecureContext) {
    await navigator.clipboard.writeText(text);
    return;
  }
  const field = document.createElement("textarea");
  field.value = text;
  field.setAttribute("readonly", "");
  field.style.position = "fixed";
  field.style.opacity = "0";
  document.body.appendChild(field);
  field.select();
  const ok = document.execCommand("copy");
  field.remove();
  if (!ok) throw new Error("Copy failed");
}

function setupCopy(button) {
  const status = button.closest(".article-share")?.querySelector(".article-share-status");
  let timer = null;

  button.hidden = false;
  button.addEventListener("click", async () => {
    try {
      await copyText(button.dataset.copyUrl || window.location.href);
      button.classList.add("is-copied");
      if (status) status.textContent = button.dataset.copiedText || "Link copied";
      window.clearTimeout(timer);
      timer = window.setTimeout(() => {
        button.classList.remove("is-copied");
        if (status) status.textContent = "";
      }, 2500);
    } catch (error) {
      // Clipboard blocked: nothing to do, the address bar still works.
    }
  });
}

export default function initInsights() {
  document.querySelectorAll("[data-in-grid]").forEach(setupGrid);
  document.querySelectorAll("[data-article-toc]").forEach(setupToc);
  document.querySelectorAll(".article-share-copy").forEach(setupCopy);
}

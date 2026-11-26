// Vendor CSS first, so theme styles in index.scss can override it.
import "swiper/css";
import "swiper/css/effect-fade";
import "../styles/index.scss";
import initHeader from "./components/header";
import initFooter from "./components/footer";
import initHomeBanner from "./components/home-banner";
import initHomeWhatWeDo from "./components/home-what-we-do";
import initHomeExpertise from "./components/home-expertise";
import initHomeInsights from "./components/home-insights";

document.addEventListener("DOMContentLoaded", () => {
  initHeader();
  initFooter();
  initHomeBanner();
  initHomeWhatWeDo();
  initHomeExpertise();
  initHomeInsights();
});


document.addEventListener('DOMContentLoaded', () => {
    const filters = document.querySelectorAll('.insights-filter');
    const cards = document.querySelectorAll('.insight-card');
    if (filters.length > 0) {
        filters.forEach(filter => {
            filter.addEventListener('click', () => {
                filters.forEach(f => f.classList.remove('is-active'));
                filter.classList.add('is-active');
                const category = filter.dataset.filter;
                cards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
});
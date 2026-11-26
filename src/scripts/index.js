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
import initSharedFaq from "./components/shared-faq";
import initEventsHub from "./components/events-hub";
import initInsights from "./components/insights";

document.addEventListener("DOMContentLoaded", () => {
  initHeader();
  initFooter();
  initHomeBanner();
  initHomeWhatWeDo();
  initHomeExpertise();
  initHomeInsights();
  initSharedFaq();
  initEventsHub();
  initInsights();
});

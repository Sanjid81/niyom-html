# Niyom — Design Reference (from Figma)

Source: Figma file "Niyom - Website Design" (`e4nqPi1XDPGIVERTqmD71g`).
Read on 2026-11-26. Desktop frames are 1920px wide; mobile frames are 360px.

---

## 1. Colors

| Token | Hex | Use |
|---|---|---|
| Research Teal | `#087A80` | Primary buttons, links, labels, CTA band background |
| Signal Cyan | `#36C4CC` | Accent (big numbers on dark, highlights) |
| Deep Ink | `#0B3A53` | Headings, dark sections, secondary-on-dark |
| Body text | `#0B3A53` at 80% (`#0B3A53CC`) | Body copy |
| Dark 60 | `#0B3A53` at 60% (`#0B3A5399`) | Muted text, placeholders |
| border | `#0B3A53` at 10% (`#0B3A531A`) | Dividers, card borders |
| Mist | `#EAF5F5` | Light teal panels, tags, date badges |
| subtle bg | `#FCFBF7` | Warm off-white section background, footer |
| surface 2 | `#FAFAFA` | Alternate light section |
| Surface | `#F6F6F6` | Alternate light section |
| White 100 / 80 / 60 | `#FFFFFF` / 80% / 60% | Text on dark |
| Form error | `#C2410C` | Field error text and underline |

Pattern: teal CTA bands and Deep Ink sections use a faint molecular-network image overlay.

---

## 2. Typography

Fonts: **Inter** (headings + body), **JetBrains Mono** (buttons, nav, labels — always UPPERCASE).

### Desktop

| Style | Font | Size / line-height / letter-spacing |
|---|---|---|
| D1 | Inter Regular | 90 / 100% / -4% |
| H1 | Inter Regular | 64 / 100% / -4% |
| H2 | Inter Regular | 56 / 100% / -2% |
| H3 | Inter Regular | 42 / 120% / -2% |
| H4 | Inter Regular | 32 / 120% / -2% |
| H5 | Inter Regular | 28 / 120% / -2% |
| Lead Text | Inter Regular | 24 / 120% / -2% |
| Body L | Inter Regular | 18 / 140% / 0 |
| Body M | Inter Regular | 16 / 140% / 0 |
| Body S | Inter Medium | 14 / 160% / 0 |
| Button | JetBrains Mono Regular | 16 / 24px / 0, upper |
| Link | Inter Regular | 16 / 140%, underlined |
| Navigation | JetBrains Mono Regular | 14 / 22px / 0, upper |
| Label L | JetBrains Mono Medium | 16 / 100% / 0, upper |
| Label S | JetBrains Mono Regular | 14 (12 in variables) / 140% / 1%, upper |

### Mobile (360)

| Style | Font | Size / line-height / letter-spacing |
|---|---|---|
| D1 | Inter Regular | 48 / 102% / -1% |
| H2 | Inter Regular | 34 / 115% / -1% |
| H4 | Inter Regular | 24 / 125% / -0.5% |
| Lead Text | Inter Medium | 20 / 120% / 0 |
| Body L | Inter Regular | 16 / 140% |
| Body S | Inter Regular | 14 / 140% |
| Button | JetBrains Mono Regular | 14 / 24px, upper |

Headings are Regular weight (400), never bold. Colour Deep Ink.

---

## 3. Layout

- Desktop canvas 1920, side padding **120px** (content width 1680).
- Header height **88px**, white, logo left, nav centre, "WORK WITH US →" button right.
- Sections: generous vertical padding (~120–160px desktop). Backgrounds alternate white / `#FCFBF7` / `#FAFAFA` / Mist / Deep Ink / Teal.
- Section eyebrow label: `( LABEL )` in JetBrains Mono, small, uppercase, teal or muted, above the heading.
- Common section intro: heading left (≈ half width), short paragraph right-aligned to the right column.
- Corners: **square** (no radius) on buttons, cards, images, inputs. Tags/pills are fully rounded.

---

## 4. Buttons (square corners, JetBrains Mono uppercase, one arrow "⟶")

Sizes: **L 56px** (desktop), **M 48px**, **S 40px** (mobile, full width).

| Type | Default | Hover | Pressed | Focus | Disabled |
|---|---|---|---|---|---|
| Primary | Teal bg, white text | Deep Ink bg | darker ink | teal + cyan focus ring | grey bg, grey text |
| Secondary | White bg, teal 1px border, teal text | Mist bg | darker mist | cyan ring | grey border/text |
| Inverse (on dark) | White bg, Deep Ink text | Mist bg | — | cyan ring | muted |
| Inverse outline (on dark) | Transparent, white 1px border, white text | white 10% fill | — | cyan ring | muted |

Text link: "KNOW MORE ⟶" — JetBrains Mono uppercase, teal; hover underlines text.

---

## 5. Form fields

- Label above (Inter 14, Deep Ink, `*` for required).
- Control is an **underline only** (no box), 50px tall, 16px text, placeholder Dark 60.
- Focus: underline turns teal. Error: underline + message in `#C2410C`. Disabled: faded.
- Select has a chevron at right. Textarea same underline style.
- Choice chips (Work With Us): square bordered boxes with checkbox/radio; selected = Mist bg + teal border.
- File upload: bordered box, file icon, underlined "Drag and drop files here or browse files", hint text below.

---

## 6. Global components

| Component | Notes |
|---|---|
| **Header (desktop)** | Logo "N" mark + NIYOM wordmark. Nav: ABOUT NIYOM · SERVICES ↓ · EXPERTISE · EVENTS & PARTNERSHIPS · INSIGHTS. Primary button WORK WITH US ⟶. |
| **Header (mobile 360)** | 64px bar, logo + square hamburger (bordered box). Expanded: full-screen list, mono uppercase items separated by borders, active item teal with a dot, SERVICES expands with a "—" to show 3 sub-links (Inter, indented with left border), full-width WORK WITH US ⟶ at bottom. Items: Home, About NIYOM, Services, Expertise, Events & Partnerships, Insights, Contact Us. |
| **Footer** | Bg `#FCFBF7`. Logo left. Columns: SERVICES (Computational Biosciences, Research Support, Scientific Engagement), QUICK LINKS (About NIYOM, Expertise, Events & Partnerships, Insights, Contact Us), LEGAL (Privacy Policy, Cookie Policy, Terms & Conditions). Bottom bar: "© 2026 NIYOM. All rights reserved. · Site by Notionhive" left; social icons Facebook, LinkedIn, X, YouTube right. |
| **CTA band** | Teal bg with network pattern. `( LABEL )`, large white heading left, Inverse button(s) right. Variant with email input + SUBSCRIBE (newsletter). |
| **Link / Arrow** | KNOW MORE ⟶ |
| **Tag** | Rounded pill, Mist bg, teal text, small. |
| **FAQ item** | Question (Inter ~20) + "+" right, answer below, border-bottom. |
| **Step card** | Number (01), line icon, title, body; 4 in a row with thin borders. |
| **Insight card** | Image, meta "20 Jan, 2026 · CATEGORY" (mono teal), title, KNOW MORE ⟶. |
| **Event row** | Date badge (big teal number + "Jan 2027" on Mist), type label, title, venue · format, VIEW DETAILS ⟶, thumbnail right. |
| **Service card** | Image top, Mist body, title, text, tags. |

---

## 7. Pages

| Page | Sections |
|---|---|
| **Home** | Hero (full-bleed dark DNA image, D1 "Biology. Computation. Insight.", text + DISCUSS YOUR RESEARCH right, bottom strip 01/02/03) · About intro centred + DISCOVER NIYOM · "Three connected areas" (3 alternating text/image rows) · Expertise slider on Deep Ink with service cards · "A clear path" 4 step cards · Events (intro + 3 event rows) · "Advancing science. Beyond borders." with globe · Insights (4 cards) · Contact form with address block · Footer |
| **About NIYOM** | Intro + wide image · Mission (numbered list) · Founder profile · 4 values cards · Integrity (Deep Ink, 6 items) · Selected work table with downloads · "Rooted in Bangladesh" image + facts · CTA centred with 2 buttons |
| **Services Overview** | Intro + image · 3 service rows with bullet lists · 4 step cards · "Not sure which service fits?" table (Mist bg) · CTA centred |
| **Computational Biosciences** | Intro + 2 buttons + image · Overview · 5 numbered areas with tags · 6 deliverable cards (Mist) · 4-step process · FAQ · Teal CTA |
| **Research Support** | Intro + image · Why · 6 areas on Deep Ink · 4 step cards · Deliverables list + "Support, not authorship" Mist box · FAQ · Teal CTA |
| **Scientific Engagement** | Intro + image · Why · 3 format cards with tags · 4 steps · Calendar (3 event rows) · Resources (3 cards) · Teal CTA |
| **Expertise** | Intro + image · Methods table (Deep Ink header) · Domains tag groups · Tech stack pills · Practices 01–04 on Deep Ink (cyan numbers) · 4 publication cards · 4 resource cards · Teal CTA with 2 buttons |
| **Events & Partnerships** | Intro + 2 action links + image · Upcoming/Past/All tabs + type filter pills + 2-col event cards · Partner logo grid · Pilots slider · Teal CTA with 2 buttons |
| **Event Details (template)** | Breadcrumb, tags, title, meta row (date/time/format/venue/status), image · About + takeaways + speakers + schedule · sidebar registration box (Deep Ink), actions, host box · Related resources · Recap · More events · Footer |
| **Insights** | Intro · Featured article (image + text) · Category tabs + 4-col grid + LOAD MORE · Teal newsletter band |
| **Insight Article (template)** | Meta + title + lead · Image + caption · Sticky "On this page" TOC + share icons left, article centre, "Related service" Deep Ink box right · Quote, figure, key-point box, checklist, tags, references · Author box · Continue reading (4 cards) · Teal CTA |
| **Work With Us** | Intro · Enquiry form (3 numbered groups, choice chips, upload, consents, SUBMIT ENQUIRY) + "It helps to have" sidebar · FAQ |
| **Work With Us — Success** | Check icon, "Thank you. Your enquiry is with us.", 3 next steps, BACK TO HOME + READ INSIGHTS, image right |

---

## 8. Figma node IDs (for exact specs later)

| Item | Node |
|---|---|
| Home – Desktop | `182:466` |
| Home – Mobile 360 | `276:1785` |
| Mobile expanded menu | `313:1945` |
| About / Services / Sci. Engagement / Comp. Bio | `290:4851` / `291:5194` / `294:6841` / `294:8068` |
| Research Support / Expertise / Insights | `296:8932` / `303:3593` / `303:7339` |
| Events Hub / Insight Article / Work With Us | `303:6379` / `303:8325` / `305:9391` |
| Event Details template / Success state | `287:1210` / `289:1974` |
| Typography / Colors | `312:10420` / `312:10761` |
| Buttons & Forms | `446:5795` |
| Header symbol / Footer symbol / CTA | `281:27` / `270:1387` / `303:8294` |

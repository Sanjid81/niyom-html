<?php
/**
 * Events & Partnerships Hub page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/<Folder>/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/<folder>/_<name>.scss.
 *
 * Page-specific sections (prefix "eh-"):
 *   Hero      → components/EventsHub/hero.php      (label, H1, text, quick links, image)
 *   Events    → components/EventsHub/events.php    (Upcoming / Past / All tabs, type pills, event cards)
 *               JS: src/scripts/components/events-hub.js
 *   Partners  → components/EventsHub/partners.php  (intro + partner logo grid)
 *   Pilots    → components/EventsHub/pilots.php    (Swiper slider, same JS file)
 * Shared:
 *   CTA       → components/Shared/cta-band.php
 *
 * Figma: "Events & Partnerships Hub" (303:6379, 1920 wide). No mobile frame
 * exists in Figma; mobile follows the Home mobile patterns (276:1785).
 *
 * Defaults fill a new block with the Figma content. Any field left empty
 * is not shown on the page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

// =====================================================================
// Events & Partnerships › Hero
// =====================================================================
/**
 * Figma: 02 — Hero (303:6381). Label; H1 + paragraph left, two "quick
 * enquiry" links right; wide image below. The hidden "Hero facts" row in
 * Figma is not built.
 * Markup: components/EventsHub/hero.php · Styles: src/styles/components/events-hub/_hero.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_eh_hero', __('Events Hub Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading and text left, quick links right, wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('events', 'niyom'), __('hero', 'niyom'), __('partnerships', 'niyom')])
        ->set_category('niyom-events-hub', __('Niyom — Events & Partnerships', 'niyom'), 'calendar-alt')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Events & partnerships')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Where research communities meet.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('Seminars, research-club sessions, institutional collaborations and capacity-building initiatives — bringing researchers, students and institutions together around practical, reproducible science.')
                ->set_help_text(__('Shown under the heading. Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('complex', 'hero_links', __('Quick Links (right)', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Links', 'niyom'), 'singular_name' => __('Link', 'niyom')])
                ->add_fields([
                    Field::make('text', 'title', __('Title', 'niyom')),
                    Field::make('text', 'text', __('Small text', 'niyom')),
                    Field::make('text', 'link', __('Link', 'niyom'))->set_width(70),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))->set_width(30),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'eh-hero-link-seminar',
                        '_type' => '_',
                        'title' => 'Request a seminar',
                        'text'  => 'For universities, departments and research clubs',
                        'link'  => '/work-with-us/',
                    ],
                    [
                        '_id'   => 'eh-hero-link-partnership',
                        '_type' => '_',
                        'title' => 'Propose a partnership',
                        'text'  => 'For institutions, laboratories and programmes',
                        'link'  => '/work-with-us/',
                    ],
                ])
                ->set_help_text(__('Each tab is one row with an arrow. A row without a link is shown as plain text.', 'niyom')),

            Field::make('separator', 'hero_sep_image', __('Image', 'niyom')),

            Field::make('image', 'hero_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/events-hub/hero-lecture-hall.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('A speaker presenting to a full lecture hall with tall windows onto trees')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/EventsHub/hero', null, $fields);
        });
});


// =====================================================================
// Events & Partnerships › Events (tabs + filters + cards)
// =====================================================================
/**
 * Figma: Content (303:6773). Label + H2; Upcoming / Past / All tabs with
 * type pills on the right; event cards in two columns.
 *
 * Events are data: each has a date (and optional end date) and a filter
 * category. In the browser the tabs compare each event's (end) date with
 * today, and the pills filter by category. Pills are built from the
 * categories used by the events. Without JavaScript every event is shown.
 * Markup: components/EventsHub/events.php · Styles: src/styles/components/events-hub/_events.scss
 * JS: src/scripts/components/events-hub.js
 */
add_action('carbon_fields_register_fields', function () {

    $uri = NIYOM_URI . '/assets/images/home/';

    Block::make('niyom_eh_events', __('Events Hub Events', 'niyom'))
        ->set_description(__('Upcoming / past events with tabs and type filters, shown as cards in two columns.', 'niyom'))
        ->set_icon('calendar-alt')
        ->set_keywords([__('events', 'niyom'), __('seminars', 'niyom'), __('calendar', 'niyom')])
        ->set_category('niyom-events-hub', __('Niyom — Events & Partnerships', 'niyom'), 'calendar-alt')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'events_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Seminars & events')
                ->set_help_text(__('Brackets are added automatically: "( SEMINARS & EVENTS )".', 'niyom')),

            Field::make('textarea', 'events_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Upcoming and past events.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            // ---------- Tabs & filters ----------
            Field::make('separator', 'events_sep_tabs', __('Tabs & Filters', 'niyom')),

            Field::make('text', 'events_tab_upcoming', __('"Upcoming" tab', 'niyom'))
                ->set_width(33)
                ->set_default_value('Upcoming')
                ->set_help_text(__('The number of upcoming events is added: "Upcoming (3)".', 'niyom')),
            Field::make('text', 'events_tab_past', __('"Past" tab', 'niyom'))
                ->set_width(33)
                ->set_default_value('Past'),
            Field::make('text', 'events_tab_all', __('"All" tab', 'niyom'))
                ->set_width(34)
                ->set_default_value('All'),

            Field::make('select', 'events_default_tab', __('Tab shown first', 'niyom'))
                ->set_width(33)
                ->set_options([
                    'upcoming' => __('Upcoming', 'niyom'),
                    'past'     => __('Past', 'niyom'),
                    'all'      => __('All', 'niyom'),
                ])
                ->set_default_value('upcoming')
                ->set_help_text(__('If this tab has no events, "All" is shown instead.', 'niyom')),
            Field::make('text', 'events_all_types', __('"All types" pill', 'niyom'))
                ->set_width(33)
                ->set_default_value('All types')
                ->set_help_text(__('Leave empty to hide the type filters.', 'niyom')),
            Field::make('text', 'events_empty', __('Text when nothing matches', 'niyom'))
                ->set_width(34)
                ->set_default_value('No events match this selection yet.'),

            // ---------- Events ----------
            Field::make('complex', 'events_items', __('Events', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Events', 'niyom'), 'singular_name' => __('Event', 'niyom')])
                ->add_fields([
                    Field::make('date', 'date', __('Date', 'niyom'))
                        ->set_width(50)
                        ->set_storage_format('Y-m-d')
                        ->set_help_text(__('Shown in the date box. Decides Upcoming / Past.', 'niyom')),
                    Field::make('date', 'end_date', __('End date (optional)', 'niyom'))
                        ->set_width(50)
                        ->set_storage_format('Y-m-d')
                        ->set_help_text(__('For events over several days: stays "Upcoming" until this date.', 'niyom')),
                    Field::make('text', 'type', __('Type label', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('Small teal label, e.g. "University seminar".', 'niyom')),
                    Field::make('text', 'category', __('Filter category', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('Name of the filter pill, e.g. "Seminars". Events with the same name share a pill. Empty = the type label is used.', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))->set_rows(2),
                    Field::make('text', 'meta', __('Venue · format', 'niyom'))
                        ->set_help_text(__('E.g. "University of Dhaka · In person".', 'niyom')),
                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Shown 260 × 170 (cropped to fit).', 'niyom')),
                    Field::make('text', 'link', __('Link', 'niyom'))->set_width(40),
                    Field::make('text', 'link_text', __('Link Text', 'niyom'))->set_width(40),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))->set_width(20),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id' => 'eh-event-1', '_type' => '_',
                        'date' => '2027-01-14', 'end_date' => '',
                        'type' => 'University seminar', 'category' => 'Seminars',
                        'title' => 'Reproducible analysis in practice: from raw data to figure',
                        'meta' => '[Host institution] · In person',
                        'image' => $uri . 'events-university-seminar.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-2', '_type' => '_',
                        'date' => '2027-01-14', 'end_date' => '',
                        'type' => 'University seminar', 'category' => 'Seminars',
                        'title' => 'Reproducible analysis in practice: from raw data to figure',
                        'meta' => '[Host institution] · In person',
                        'image' => $uri . 'events-university-seminar.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-3', '_type' => '_',
                        'date' => '2027-01-22', 'end_date' => '',
                        'type' => 'Research-club session', 'category' => 'Research clubs',
                        'title' => 'Reading a genomics paper critically: methods first',
                        'meta' => '[Student research society] · Online',
                        'image' => $uri . 'events-research-club.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-4', '_type' => '_',
                        'date' => '2027-01-22', 'end_date' => '',
                        'type' => 'Research-club session', 'category' => 'Research clubs',
                        'title' => 'Reading a genomics paper critically: methods first',
                        'meta' => '[Student research society] · Online',
                        'image' => $uri . 'events-research-club.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-5', '_type' => '_',
                        'date' => '2027-02-13', 'end_date' => '',
                        'type' => 'Community workshop', 'category' => 'Workshops',
                        'title' => 'Asking answerable questions of biological data',
                        'meta' => '[Partner organisation] · Hybrid',
                        'image' => $uri . 'events-community-workshop.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-6', '_type' => '_',
                        'date' => '2027-02-13', 'end_date' => '',
                        'type' => 'Community workshop', 'category' => 'Workshops',
                        'title' => 'Asking answerable questions of biological data',
                        'meta' => '[Partner organisation] · Hybrid',
                        'image' => $uri . 'events-community-workshop.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-7', '_type' => '_',
                        'date' => '2026-10-08', 'end_date' => '',
                        'type' => 'University seminar', 'category' => 'Seminars',
                        'title' => 'Designing a study the analysis can support',
                        'meta' => '[Host institution] · In person',
                        'image' => $uri . 'events-university-seminar.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                    [
                        '_id' => 'eh-event-8', '_type' => '_',
                        'date' => '2026-09-17', 'end_date' => '',
                        'type' => 'Research-club session', 'category' => 'Research clubs',
                        'title' => 'Version control for analysis projects: a first session',
                        'meta' => '[Student research society] · Online',
                        'image' => $uri . 'events-research-club.jpg',
                        'link' => '#', 'link_text' => 'View details',
                    ],
                ])
                ->set_help_text(__('Each tab is one event card, shown in this order. Upcoming / Past is worked out from the date. A card with a link is clickable as a whole.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/EventsHub/events', null, $fields);
        });
});


// =====================================================================
// Events & Partnerships › Institutional collaborations (partner logos)
// =====================================================================
/**
 * Figma: 04 — Institutional Collaborations (303:6894). Intro left; 3-column
 * grid of partner logo tiles right. A partner without a logo shows its
 * name in the tile (the Figma "[Partner logo]" placeholder).
 * Markup: components/EventsHub/partners.php · Styles: src/styles/components/events-hub/_partners.scss
 */
add_action('carbon_fields_register_fields', function () {

    $placeholder = static function ($n) {
        return ['_id' => 'eh-partner-' . $n, '_type' => '_', 'logo' => '', 'name' => '[Partner logo]', 'link' => ''];
    };

    Block::make('niyom_eh_partners', __('Events Hub Partners', 'niyom'))
        ->set_description(__('Intro left; grid of partner logos right.', 'niyom'))
        ->set_icon('groups')
        ->set_keywords([__('partners', 'niyom'), __('logos', 'niyom'), __('collaborations', 'niyom')])
        ->set_category('niyom-events-hub', __('Niyom — Events & Partnerships', 'niyom'), 'calendar-alt')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'partners_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Institutional collaborations')
                ->set_help_text(__('Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'partners_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Working with institutions.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'partners_text', __('Text', 'niyom'))
                ->set_default_value('Collaborations are listed only with each institution’s written agreement.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('complex', 'partners_items', __('Partners', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Partners', 'niyom'), 'singular_name' => __('Partner', 'niyom')])
                ->add_fields([
                    Field::make('image', 'logo', __('Logo', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('SVG or PNG with a transparent background. Fits inside the tile.', 'niyom')),
                    Field::make('text', 'name', __('Name', 'niyom'))
                        ->set_help_text(__('Used as the logo alt text. Shown in the tile when there is no logo.', 'niyom')),
                    Field::make('text', 'link', __('Link (optional)', 'niyom'))->set_width(70),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))->set_width(30),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value(array_map($placeholder, range(1, 6)))
                ->set_help_text(__('Each tab is one logo tile, three per row on desktop.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/EventsHub/partners', null, $fields);
        });
});


// =====================================================================
// Events & Partnerships › Pilots slider
// =====================================================================
/**
 * Figma: 05 — University & Laboratory Pilots (303:6921). Label + H2;
 * slider of Mist cards (image left, text right), "‹ 01 / 03 ›" pager.
 * Two cards are in view on desktop; the pager counts slider positions.
 * Markup: components/EventsHub/pilots.php · Styles: src/styles/components/events-hub/_pilots.scss
 * JS: src/scripts/components/events-hub.js
 */
add_action('carbon_fields_register_fields', function () {

    $note = 'Pilot model — open to expressions of interest';

    Block::make('niyom_eh_pilots', __('Events Hub Pilots', 'niyom'))
        ->set_description(__('Heading and a slider of pilot programme cards.', 'niyom'))
        ->set_icon('slides')
        ->set_keywords([__('pilots', 'niyom'), __('slider', 'niyom'), __('programmes', 'niyom')])
        ->set_category('niyom-events-hub', __('Niyom — Events & Partnerships', 'niyom'), 'calendar-alt')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'pilots_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Pilots')
                ->set_help_text(__('Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'pilots_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('University and laboratory pilots.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('complex', 'pilots_items', __('Pilots', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Pilots', 'niyom'), 'singular_name' => __('Pilot', 'niyom')])
                ->add_fields([
                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_width(50)
                        ->set_help_text(__('Shown 300 × 420 on desktop (cropped to fit).', 'niyom')),
                    Field::make('text', 'image_alt', __('Image Alt Text', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('Leave empty if the image is decorative.', 'niyom')),
                    Field::make('text', 'label', __('Label', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))->set_rows(2),
                    Field::make('rich_text', 'text', __('Text', 'niyom')),
                    Field::make('text', 'note', __('Note (small, under the text)', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id' => 'eh-pilot-university', '_type' => '_',
                        'image' => NIYOM_URI . '/assets/images/services/service-research-support.jpg',
                        'image_alt' => '',
                        'label' => 'University pilot',
                        'title' => 'A semester-length programme of seminars and hands-on sessions',
                        'text' => 'Designed with a department to introduce reproducible analysis to graduate researchers, with materials the department can keep using.',
                        'note' => $note,
                    ],
                    [
                        '_id' => 'eh-pilot-laboratory', '_type' => '_',
                        'image' => NIYOM_URI . '/assets/images/services/service-computational-biosciences.jpg',
                        'image_alt' => '',
                        'label' => 'Laboratory pilot',
                        'title' => 'Embedded analysis support for a single research group',
                        'text' => 'A defined period of scoped computational and methods support for one lab, ending with a documented handover of workflows.',
                        'note' => $note,
                    ],
                    [
                        '_id' => 'eh-pilot-research-club', '_type' => '_',
                        'image' => NIYOM_URI . '/assets/images/home/wwd-scientific-engagement.jpg',
                        'image_alt' => '',
                        'label' => 'Research-club pilot',
                        'title' => 'A term of guided research-club sessions for students',
                        'text' => 'Regular sessions with a student society on reading methods critically and working with real data, with reading lists the club can reuse.',
                        'note' => $note,
                    ],
                    [
                        '_id' => 'eh-pilot-institution', '_type' => '_',
                        'image' => NIYOM_URI . '/assets/images/home/expertise-research-design.jpg',
                        'image_alt' => '',
                        'label' => 'Institutional pilot',
                        'title' => 'Capacity building for a research programme',
                        'text' => 'A scoped collaboration with an institute or programme to set up shared analysis practices, training and documentation for its teams.',
                        'note' => $note,
                    ],
                ])
                ->set_help_text(__('Each tab is one slider card. Two cards are in view on large screens, one on smaller screens.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/EventsHub/pilots', null, $fields);
        });
});


// =====================================================================
// Events & Partnerships › CTA
// =====================================================================
/**
 * Figma: 08 — CTA (303:6603). Teal band with network image, label, white
 * heading and two white buttons (in Figma both are the white "inverse"
 * style, so the second button can be switched from outline to white).
 * Markup: components/Shared/cta-band.php · Styles: src/styles/components/shared/_cta-band.scss
 * (+ white second button: src/styles/components/events-hub/_cta.scss)
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_eh_cta', __('Events Hub CTA', 'niyom'))
        ->set_description(__('Teal call-to-action band: label, heading and up to two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('partnership', 'niyom')])
        ->set_category('niyom-events-hub', __('Niyom — Events & Partnerships', 'niyom'), 'calendar-alt')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'cta_band_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Seminars & partnerships')
                ->set_help_text(__('Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'cta_band_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Let\'s collaborate to advance scientific excellence.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('image', 'cta_band_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Faint pattern under the teal colour. Use about 2000 px wide. Leave empty for plain teal.', 'niyom')),

            // ---------- Primary button (white) ----------
            Field::make('separator', 'cta_band_sep_primary', __('Primary Button (white)', 'niyom')),

            Field::make('text', 'cta_band_primary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Request a seminar'),
            Field::make('text', 'cta_band_primary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'cta_band_primary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Secondary button ----------
            Field::make('separator', 'cta_band_sep_secondary', __('Secondary Button', 'niyom')),

            Field::make('text', 'cta_band_secondary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Propose a partnership'),
            Field::make('text', 'cta_band_secondary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'cta_band_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
            Field::make('checkbox', 'cta_band_secondary_white', __('White button (unticked = white outline)', 'niyom'))
                ->set_option_value('yes')
                ->set_default_value(true),
        ])
        ->set_render_callback(function ($fields) {
            if (!empty($fields['cta_band_secondary_white'])) {
                echo '<div class="eh-cta eh-cta--white-buttons">';
                get_template_part('components/Shared/cta-band', null, $fields);
                echo '</div>';
                return;
            }
            get_template_part('components/Shared/cta-band', null, $fields);
        });
});

<?php
/**
 * Home page blocks (Carbon Fields).
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/Home/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/home/<name>.scss, imported in index.scss.
 *   4. Behaviour (if any) in src/scripts/components/home-<name>.js.
 *
 * Defaults fill a new block with the Figma content. Any field left empty
 * is not shown on the page.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {

    // -----------------------------------------------------------------
    // Home Banner slider (Figma: Home — Desktop › Banner, Home — Mobile › Hero)
    // Each slide is one tab. The tab label shows along the bottom of the
    // banner and switches slides when clicked.
    // -----------------------------------------------------------------
    Block::make('niyom_home_banner', __('Home Banner', 'niyom'))
        ->set_description(__('Full-width hero slider: each slide has a tab label, image, title, text and button.', 'niyom'))
        ->set_icon('slides')
        ->set_keywords([__('hero', 'niyom'), __('banner', 'niyom'), __('slider', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('complex', 'banner_slides', __('Slides', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Slides', 'niyom'),
                    'singular_name' => __('Slide', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'tab_label', __('Tab Label', 'niyom'))
                        ->set_help_text(__('Shown along the bottom of the banner, e.g. "01 — Biological Structure".', 'niyom')),

                    Field::make('image', 'image', __('Background Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Wide image, about 2000 × 800 px.', 'niyom')),
                    Field::make('image', 'image_mobile', __('Mobile Image (optional)', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Used up to 768px wide. If empty, the background image is used.', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(3)
                        ->set_help_text(__('Press Enter for a new line.', 'niyom')),
                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links, lists and images all work.', 'niyom')),

                    Field::make('text', 'button_text', __('Button Text', 'niyom')),
                    Field::make('text', 'button_link', __('Button Link', 'niyom')),
                    Field::make('checkbox', 'button_new_tab', __('Open in new tab', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'            => 'home-banner-slide-1',
                        '_type'          => '_',
                        'tab_label'      => '01 — Biological Structure',
                        'image'          => NIYOM_URI . '/assets/images/home/hero-biological-structure.jpg',
                        'image_mobile'   => '',
                        'title'          => "Biology.\nComputation.\nInsight.",
                        'text'           => 'NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.',
                        'button_text'    => 'Discuss Your Research',
                        'button_link'    => '/work-with-us/',
                        'button_new_tab' => false,
                    ],
                    [
                        '_id'            => 'home-banner-slide-2',
                        '_type'          => '_',
                        'tab_label'      => '02 — Computational Data',
                        'image'          => NIYOM_URI . '/assets/images/home/hero-computational-data.jpg',
                        'image_mobile'   => '',
                        'title'          => "Biology.\nComputation.\nInsight.",
                        'text'           => 'NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.',
                        'button_text'    => 'Discuss Your Research',
                        'button_link'    => '/work-with-us/',
                        'button_new_tab' => false,
                    ],
                    [
                        '_id'            => 'home-banner-slide-3',
                        '_type'          => '_',
                        'tab_label'      => '03 — Scientific Insight',
                        'image'          => NIYOM_URI . '/assets/images/home/hero-scientific-insight.jpg',
                        'image_mobile'   => '',
                        'title'          => "Biology.\nComputation.\nInsight.",
                        'text'           => 'NIYOM connects biological research with computational approaches and scientific expertise to support rigorous, reproducible outcomes.',
                        'button_text'    => 'Discuss Your Research',
                        'button_link'    => '/work-with-us/',
                        'button_new_tab' => false,
                    ],
                ])
                ->set_help_text(__('Each tab is one slide. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),

            Field::make('separator', 'banner_sep_settings', __('Slider Settings', 'niyom')),

            Field::make('checkbox', 'banner_autoplay', __('Autoplay', 'niyom'))
                ->set_option_value('yes')
                ->set_default_value(true),
            Field::make('text', 'banner_delay', __('Seconds per slide', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '2')
                ->set_attribute('step', '0.5')
                ->set_default_value('6')
                ->set_conditional_logic([['field' => 'banner_autoplay', 'value' => true]]),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/banner', null, $fields);
        });
});

// =====================================================================
// From: home-about.php
// =====================================================================
/**
 * Home › About NIYOM block (Carbon Fields).
 *
 * Figma: Home — Desktop › Content (182:505), Home — Mobile › About NIYOM (276:1809).
 * Markup: components/Home/about.php · Styles: src/styles/components/home/about.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_about', __('Home About', 'niyom'))
        ->set_description(__('Centred intro: label, large statement and button over a faint background image.', 'niyom'))
        ->set_icon('info-outline')
        ->set_keywords([__('about', 'niyom'), __('intro', 'niyom'), __('statement', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('image', 'about_bg_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/about-bg.jpg')
                ->set_help_text(__('Shown very faintly (6%) behind the text and faded to white at the bottom. About 2000 × 1333 px. Hidden on mobile (Figma).', 'niyom')),

            Field::make('text', 'about_eyebrow', __('Label', 'niyom'))
                ->set_default_value('About NIYOM')
                ->set_help_text(__('Small label above the text. Brackets are added automatically: ( ABOUT NIYOM ).', 'niyom')),

            Field::make('textarea', 'about_text', __('Statement', 'niyom'))
                ->set_rows(4)
                ->set_default_value('NIYOM connects scientific expertise with researchers worldwide through computational biosciences, research support, and meaningful collaboration.')
                ->set_help_text(__('Large centred text. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('textarea', 'about_text_mobile', __('Statement on Mobile (optional)', 'niyom'))
                ->set_rows(4)
                ->set_default_value('Rooted in Bangladesh with an international outlook, NIYOM brings computational biosciences, research support and scientific engagement together.')
                ->set_help_text(__('Used up to 768px wide. If empty, the statement above is used.', 'niyom')),

            Field::make('text', 'about_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Discover NIYOM'),
            Field::make('text', 'about_button_link', __('Button Link', 'niyom'))
                ->set_default_value('/about-niyom/'),
            Field::make('checkbox', 'about_button_new_tab', __('Open in new tab', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/about', null, $fields);
        });
});


// =====================================================================
// From: home-what-we-do.php
// =====================================================================
/**
 * Home › What We Do block (Carbon Fields).
 *
 * Figma: Home — Desktop › Content (206:2914), Home — Mobile › Three Disciplines (276:1813).
 * Markup: components/Home/what-we-do.php
 * Styles: src/styles/components/home/what-we-do.scss
 * JS:     src/scripts/components/home-what-we-do.js (stacking polish + mobile accordion)
 *
 * Desktop/tablet: the area cards stack on scroll (sticky). Mobile: accordion.
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    $img = NIYOM_URI . '/assets/images/home/';

    Block::make('niyom_home_what_we_do', __('Home What We Do', 'niyom'))
        ->set_description(__('Section intro and service areas (text + image). Cards stack while scrolling on desktop; accordion on mobile.', 'niyom'))
        ->set_icon('excerpt-view')
        ->set_keywords([__('services', 'niyom'), __('what we do', 'niyom'), __('areas', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'wwd_eyebrow', __('Label', 'niyom'))
                ->set_default_value('What We Do')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically: ( WHAT WE DO ).', 'niyom')),

            Field::make('textarea', 'wwd_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Three connected areas.\nOne scientific purpose.")
                ->set_help_text(__('Press Enter for a new line (desktop keeps the line breaks).', 'niyom')),

            Field::make('rich_text', 'wwd_intro', __('Intro Text', 'niyom'))
                ->set_default_value('NIYOM’s public expertise catalogue should reflect only methods, tools and domains that can be supported by verified experience and evidence.')
                ->set_help_text(__('Shown to the right of the heading. Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('rich_text', 'wwd_intro_mobile', __('Intro Text on Mobile (optional)', 'niyom'))
                ->set_default_value('Three connected areas of practice, each held to the same standard of rigor, transparency and reproducibility.')
                ->set_help_text(__('Used up to 768px wide. If empty, the intro text above is used.', 'niyom')),

            Field::make('complex', 'wwd_items', __('Areas', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Areas', 'niyom'),
                    'singular_name' => __('Area', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number', 'niyom'))
                        ->set_help_text(__('e.g. 01', 'niyom')),
                    Field::make('text', 'label', __('Label', 'niyom'))
                        ->set_help_text(__('Small teal label next to the number, e.g. Computational Biosciences.', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line.', 'niyom')),
                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links, lists and images all work. On mobile it shows when the item is opened.', 'niyom')),
                    Field::make('text', 'link_text', __('Link Text', 'niyom')),
                    Field::make('text', 'link_url', __('Link URL', 'niyom')),
                    Field::make('checkbox', 'link_new_tab', __('Open in new tab', 'niyom')),
                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('About 1800 × 1056 px (shown 893 × 524 on desktop).', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'          => 'home-wwd-item-1',
                        '_type'        => '_',
                        'number'       => '01',
                        'label'        => 'Computational Biosciences',
                        'title'        => 'Turning complex data into scientific understanding',
                        'text'         => 'Analysis planning, data-readiness review and documented computational workflows that support clear scientific interpretation.',
                        'link_text'    => 'Know more',
                        'link_url'     => '/services/computational-biosciences/',
                        'link_new_tab' => false,
                        'image'        => $img . 'wwd-computational-biosciences.jpg',
                    ],
                    [
                        '_id'          => 'home-wwd-item-2',
                        '_type'        => '_',
                        'number'       => '02',
                        'label'        => 'Research Support',
                        'title'        => 'Strengthening research through every stage',
                        'text'         => 'Modern, responsive, and user-friendly websites designed to engage visitors and drive conversions.',
                        'link_text'    => 'Know more',
                        'link_url'     => '/services/research-support/',
                        'link_new_tab' => false,
                        'image'        => $img . 'wwd-research-support.jpg',
                    ],
                    [
                        '_id'          => 'home-wwd-item-3',
                        '_type'        => '_',
                        'number'       => '03',
                        'label'        => 'Scientific Engagement',
                        'title'        => 'Connecting knowledge, people, and possibilities',
                        'text'         => 'Seminars, knowledge-sharing and institutional partnerships — including curriculum mapping and computational integration for academic programs.',
                        'link_text'    => 'Know more',
                        'link_url'     => '/services/scientific-engagement/',
                        'link_new_tab' => false,
                        'image'        => $img . 'wwd-scientific-engagement.jpg',
                    ],
                ])
                ->set_help_text(__('Each tab is one area. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/what-we-do', null, $fields);
        });
});


// =====================================================================
// From: home-expertise.php
// =====================================================================
/**
 * Home Expertise block (Carbon Fields).
 *
 * Figma: Home — Desktop › Expertise (182:541), Home — Mobile › Expertise (276:1839).
 * Intro (eyebrow, heading, text, button) on Deep Ink with a faint background
 * image, plus a card slider filled from the "Expertise" post type
 * (title, excerpt, featured image, Expertise Tags as chips).
 *
 * Markup: components/Home/expertise.php
 * Styles: src/styles/components/home/expertise.scss
 * Slider: src/scripts/components/home-expertise.js
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_expertise', __('Home Expertise', 'niyom'))
        ->set_description(__('Dark section with intro text and a slider of Expertise posts.', 'niyom'))
        ->set_icon('welcome-learn-more')
        ->set_keywords([__('expertise', 'niyom'), __('slider', 'niyom'), __('cards', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Background ----------
            Field::make('image', 'expertise_bg', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Shown very faintly (7%) over the Deep Ink colour. Wide image, about 2000 × 1150 px.', 'niyom')),

            // ---------- Intro ----------
            Field::make('separator', 'expertise_sep_intro', __('Intro', 'niyom')),

            Field::make('text', 'expertise_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Expertise')
                ->set_help_text(__('Brackets are added automatically: "( EXPERTISE )".', 'niyom')),

            Field::make('textarea', 'expertise_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Methods matter. So does knowing their limits.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'expertise_text', __('Text', 'niyom'))
                ->set_default_value('A transparent view of the methods, tools and domains we work with — including where each approach applies, and where it does not.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('text', 'expertise_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Explore Our Expertise')
                ->set_width(40),
            Field::make('text', 'expertise_button_link', __('Button Link', 'niyom'))
                ->set_default_value('/expertise/')
                ->set_width(40),
            Field::make('checkbox', 'expertise_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Cards ----------
            Field::make('separator', 'expertise_sep_cards', __('Expertise Cards', 'niyom')),

            Field::make('html', 'expertise_cards_info')
                ->set_html('<p>' . esc_html__('Cards come from Expertise posts (title, excerpt, featured image and Expertise Tags). With no published posts the slider is hidden.', 'niyom') . '</p>'),

            Field::make('text', 'expertise_count', __('Number of cards to show', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('max', '24')
                ->set_default_value('6')
                ->set_width(50),

            Field::make('select', 'expertise_order', __('Order', 'niyom'))
                ->set_options([
                    'menu_order' => __('Manual order (Page Attributes › Order)', 'niyom'),
                    'date'       => __('Newest first', 'niyom'),
                ])
                ->set_default_value('menu_order')
                ->set_width(50),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/expertise', null, $fields);
        });
});


// =====================================================================
// From: home-how-it-works.php
// =====================================================================
/**
 * Home "How NIYOM works" block (Carbon Fields).
 *
 * Figma: Home — Desktop › How NIYOM works (182:568), Home — Mobile › How We Work (276:1847).
 * Intro (eyebrow, heading, button) and a row of step cards: number, line icon,
 * title, text. Step numbers come from the order (01, 02 …) unless a number
 * is typed in.
 *
 * Markup: components/Home/how-it-works.php
 * Styles: src/styles/components/home/how-it-works.scss
 *
 */
add_action('carbon_fields_register_fields', function () {

    $icons = NIYOM_URI . '/assets/icons/';

    Block::make('niyom_home_how_it_works', __('Home How It Works', 'niyom'))
        ->set_description(__('Intro with a button and numbered step cards (icon, title, text).', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('steps', 'niyom'), __('process', 'niyom'), __('how it works', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'steps_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('How NIYOM works')
                ->set_help_text(__('Brackets are added automatically: "( HOW NIYOM WORKS )".', 'niyom')),

            Field::make('textarea', 'steps_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('A clear path from research questions to scientific outcomes.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('text', 'steps_button_text', __('Button Text', 'niyom'))
                ->set_default_value('Start a Project Inquiry')
                ->set_width(40),
            Field::make('text', 'steps_button_link', __('Button Link', 'niyom'))
                ->set_default_value('/work-with-us/')
                ->set_width(40),
            Field::make('checkbox', 'steps_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Steps ----------
            Field::make('separator', 'steps_sep_items', __('Steps', 'niyom')),

            Field::make('complex', 'steps_items', __('Steps', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Steps', 'niyom'),
                    'singular_name' => __('Step', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number steps automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('image', 'icon', __('Icon', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Line icon, 80 × 80. SVG recommended: it is shown in Research Teal.', 'niyom')),

                    Field::make('text', 'title', __('Title', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'home-step-1',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-share-question.svg',
                        'title'  => 'Share the research question',
                        'text'   => 'Tell us about your objective, research stage, data type and the decision or deliverable you need.',
                    ],
                    [
                        '_id'    => 'home-step-2',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-review-fit.svg',
                        'title'  => 'Review fit and feasibility',
                        'text'   => 'We clarify requirements, limitations, timeline, data readiness and any ethical or governance considerations.',
                    ],
                    [
                        '_id'    => 'home-step-3',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-agree-scope.svg',
                        'title'  => 'Agree scope and deliverables',
                        'text'   => 'Define the work plan, responsibilities, milestones, outputs and communication expectations.',
                    ],
                    [
                        '_id'    => 'home-step-4',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-deliver-handover.svg',
                        'title'  => 'Deliver, explain and hand over',
                        'text'   => 'Receive documented outputs, interpretation support and an agreed handover for continued use.',
                    ],
                ])
                ->set_help_text(__('Each tab is one step. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/how-it-works', null, $fields);
        });
});


// =====================================================================
// From: home-events.php
// =====================================================================
/**
 * Home › Events & Partnerships block (Carbon Fields).
 *
 * Figma: Home — Desktop › Events (294:6685), Home — Mobile › Events (439:2100).
 * Markup: components/Home/events.php · Styles: src/styles/components/home/events.scss
 *
 * Left: eyebrow, heading, text, button. Right: a list of event rows, each
 * entered here (there is no Events post type). Empty fields are not shown.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_events', __('Home Events', 'niyom'))
        ->set_description(__('Intro text with a button, and a list of event rows (date, type, title, venue, link, image).', 'niyom'))
        ->set_icon('calendar-alt')
        ->set_keywords([__('events', 'niyom'), __('partnerships', 'niyom'), __('seminar', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([

            // ---------- Intro (left column) ----------
            Field::make('text', 'events_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Events & partnerships')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'events_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Creating space for scientific exchange.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'events_text', __('Text', 'niyom'))
                ->set_default_value('Connecting researchers, academic institutions, and scientific communities through knowledge-sharing initiatives, seminars, and collaborative opportunities.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('text', 'events_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('View all events'),
            Field::make('text', 'events_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/events-partnerships/'),
            Field::make('checkbox', 'events_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Event rows (right column) ----------
            Field::make('separator', 'events_sep_items', __('Events', 'niyom')),

            Field::make('complex', 'events_items', __('Events', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Events', 'niyom'),
                    'singular_name' => __('Event', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'day', __('Day', 'niyom'))
                        ->set_width(30)
                        ->set_help_text(__('Big number in the date badge, e.g. "14".', 'niyom')),
                    Field::make('text', 'month', __('Month & Year', 'niyom'))
                        ->set_width(70)
                        ->set_help_text(__('Under the number, e.g. "Jan 2027".', 'niyom')),

                    Field::make('text', 'type', __('Type Label', 'niyom'))
                        ->set_help_text(__('e.g. "University seminar". Shown in capitals.', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2),
                    Field::make('text', 'meta', __('Venue · Format', 'niyom'))
                        ->set_help_text(__('e.g. "[Host institution] · In person".', 'niyom')),

                    Field::make('text', 'link', __('Link', 'niyom'))
                        ->set_width(40)
                        ->set_help_text(__('The whole row links here. If empty, the row is not a link.', 'niyom')),
                    Field::make('text', 'link_text', __('Link Text', 'niyom'))
                        ->set_width(40)
                        ->set_help_text(__('Desktop only (hidden on mobile, as in the design).', 'niyom')),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))
                        ->set_width(20),

                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Landscape, about 800 × 520 px.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'       => 'home-events-item-1',
                        '_type'     => '_',
                        'day'       => '14',
                        'month'     => 'Jan 2027',
                        'type'      => 'University seminar',
                        'title'     => 'Reproducible analysis in practice: from raw data to figure',
                        'meta'      => '[Host institution] · In person',
                        'link'      => '/events-partnerships/',
                        'link_text' => 'View details',
                        'new_tab'   => false,
                        'image'     => NIYOM_URI . '/assets/images/home/events-university-seminar.jpg',
                    ],
                    [
                        '_id'       => 'home-events-item-2',
                        '_type'     => '_',
                        'day'       => '22',
                        'month'     => 'Jan 2027',
                        'type'      => 'Research-club session',
                        'title'     => 'Reading a genomics paper critically: methods first',
                        'meta'      => '[Student research society] · Online',
                        'link'      => '/events-partnerships/',
                        'link_text' => 'View details',
                        'new_tab'   => false,
                        'image'     => NIYOM_URI . '/assets/images/home/events-research-club.jpg',
                    ],
                    [
                        '_id'       => 'home-events-item-3',
                        '_type'     => '_',
                        'day'       => '13',
                        'month'     => 'Feb 2027',
                        'type'      => 'Community workshop',
                        'title'     => 'Asking answerable questions of biological data',
                        'meta'      => '[Partner organisation] · Hybrid',
                        'link'      => '/events-partnerships/',
                        'link_text' => 'View details',
                        'new_tab'   => false,
                        'image'     => NIYOM_URI . '/assets/images/home/events-community-workshop.jpg',
                    ],
                ])
                ->set_help_text(__('Each tab is one event. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/events', null, $fields);
        });
});


// =====================================================================
// From: home-global.php
// =====================================================================
/**
 * Home › Global Perspective block (Carbon Fields).
 *
 * Figma: Home — Desktop › Global (213:3073), Home — Mobile › Global (290:4840).
 * Markup: components/Home/global.php · Styles: src/styles/components/home/global.scss
 *
 * Left: eyebrow, heading, text, button. Right: globe image, or an optional
 * looping video (the image is then its poster and the reduced-motion fallback).
 * Empty fields are not shown.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_global', __('Home Global Perspective', 'niyom'))
        ->set_description(__('Text with a button on the left, globe image or looping video on the right.', 'niyom'))
        ->set_icon('admin-site-alt3')
        ->set_keywords([__('global', 'niyom'), __('globe', 'niyom'), __('collaboration', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([

            // ---------- Text (left column) ----------
            Field::make('text', 'global_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Global perspective')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'global_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Advancing science.\nBeyond borders.")
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'global_text', __('Text', 'niyom'))
                ->set_default_value('NIYOM brings together computational expertise, research guidance, and scientific engagement with an international perspective, creating opportunities for knowledge exchange and collaboration.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('text', 'global_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Discuss a Collaboration'),
            Field::make('text', 'global_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'global_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Media (right column) ----------
            Field::make('separator', 'global_sep_media', __('Media', 'niyom')),

            Field::make('image', 'global_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/global-globe.jpg')
                ->set_help_text(__('About 1244 × 738 px on a white background. Also the video poster.', 'niyom')),

            Field::make('file', 'global_video', __('Video (optional)', 'niyom'))
                ->set_type(['video'])
                ->set_value_type('url')
                ->set_help_text(__('MP4, same shape as the image. Plays muted on a loop. Visitors who prefer reduced motion see the image instead.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/global', null, $fields);
        });
});


// =====================================================================
// From: home-insights.php
// =====================================================================
/**
 * Home Insights block (Carbon Fields).
 *
 * Figma: Home — Desktop › Insights (182:677), Home — Mobile › Insights (276:1898).
 * Intro (eyebrow, heading, button) is edited here; the cards are the latest
 * posts of the "insights" post type (title, featured image, date, category).
 *
 * Markup: components/Home/insights.php
 * Styles: src/styles/components/home/insights.scss
 *
 */
/**
 * Options for the category filter: "All" + every Insight Category.
 *
 * @return array slug => name
 */
function niyom_home_insights_category_options()
{
    $options = ['' => __('All categories', 'niyom')];
    $terms   = get_terms([
        'taxonomy'   => 'insight_category',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }
    }

    return $options;
}

add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_insights', __('Home Insights', 'niyom'))
        ->set_description(__('Section intro + the latest Insights posts as cards.', 'niyom'))
        ->set_icon('lightbulb')
        ->set_keywords([__('insights', 'niyom'), __('posts', 'niyom'), __('articles', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'insights_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Insights')
                ->set_help_text(__('Shown as "( INSIGHTS )". Leave empty to hide.', 'niyom')),
            Field::make('textarea', 'insights_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Ideas, Insights, and Scientific perspectives.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('text', 'insights_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('View All Insights'),
            Field::make('text', 'insights_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/insights/'),
            Field::make('checkbox', 'insights_button_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Cards (from Insights posts) ----------
            Field::make('separator', 'insights_sep_posts', __('Cards — latest Insights posts', 'niyom')),

            Field::make('text', 'insights_count', __('Number of posts', 'niyom'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('max', '12')
                ->set_width(33)
                ->set_default_value('4'),
            Field::make('select', 'insights_category', __('Category', 'niyom'))
                ->set_width(33)
                ->add_options('niyom_home_insights_category_options')
                ->set_help_text(__('Show posts from one Insight Category only, or all.', 'niyom')),
            Field::make('text', 'insights_link_text', __('Card Link Text', 'niyom'))
                ->set_width(33)
                ->set_default_value('Know more')
                ->set_help_text(__('Leave empty to hide the link under each card.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/insights', null, $fields);
        });
});


// =====================================================================
// From: home-contact.php
// =====================================================================
/**
 * Home Contact block (Carbon Fields) — "Warm gallery — Research contact".
 *
 * Figma: Home — Desktop (270:1606), Home — Mobile (278:2702).
 * Left: eyebrow, heading, text, portrait image + contact blocks.
 * Right: enquiry form from a Contact Form 7 shortcode.
 * The section has id="contact" so menu links to /#contact land here.
 *
 * Markup: components/Home/contact.php
 * Styles: src/styles/components/home/contact.scss
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_home_contact', __('Home Contact', 'niyom'))
        ->set_description(__('Research invitation with contact details and the enquiry form.', 'niyom'))
        ->set_icon('email-alt')
        ->set_keywords([__('contact', 'niyom'), __('form', 'niyom'), __('enquiry', 'niyom')])
        ->set_category('niyom-home', __('Niyom — Home', 'niyom'), 'admin-home')
        ->set_mode('both')
        ->add_fields([
            // ---------- Invitation ----------
            Field::make('text', 'contact_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('A conversation starts here')
                ->set_help_text(__('Shown as "( A CONVERSATION STARTS HERE )". Leave empty to hide.', 'niyom')),
            Field::make('textarea', 'contact_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Every discovery begins\nwith a question.")
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),
            Field::make('rich_text', 'contact_text', __('Text', 'niyom'))
                ->set_default_value('Tell us where your research is heading, and what stands in the way. Together, we’ll explore the right support for your next step.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('image', 'contact_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/contact-study-in-light.jpg')
                ->set_help_text(__('Portrait image, about 560 × 620 px. Hidden on mobile (as in the design).', 'niyom')),

            // ---------- Contact blocks ----------
            Field::make('complex', 'contact_blocks', __('Contact Blocks', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Contact Blocks', 'niyom'),
                    'singular_name' => __('Contact Block', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'kicker', __('Label', 'niyom'))
                        ->set_help_text(__('Small uppercase label, e.g. "Office".', 'niyom')),
                    Field::make('rich_text', 'details', __('Details', 'niyom'))
                        ->set_help_text(__('Address, email, phone… Line breaks and links work (e.g. mailto: / tel: links).', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'     => 'home-contact-office',
                        '_type'   => '_',
                        'kicker'  => 'Office',
                        'details' => '<p>270, Tejgaon Industrial<br>Area, Dhaka</p>',
                    ],
                    [
                        '_id'     => 'home-contact-info',
                        '_type'   => '_',
                        'kicker'  => 'Contact info',
                        'details' => '<p><a href="mailto:info@niyom11.com">info@niyom11.com</a><br><a href="tel:+8801930107368">+8801 93 010 73 68</a></p>',
                    ],
                ]),

            // ---------- Form ----------
            Field::make('separator', 'contact_sep_form', __('Enquiry Form', 'niyom')),
            Field::make('text', 'contact_form', __('Form Shortcode', 'niyom'))
                ->set_default_value('[contact-form-7 title="Home – Research Enquiry"]')
                ->set_help_text(__('Contact Form 7 shortcode (Contact › Contact Forms). Leave empty to hide the form panel.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/contact', null, $fields);
        });
});

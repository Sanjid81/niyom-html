<?php
/**
 * Scientific Engagement page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/<Folder>/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/<folder>/_<name>.scss.
 *
 * Sections that look the same as on other pages reuse those components:
 *   Hero     → components/About/hero.php (with optional buttons)
 *   Why      → components/Shared/intro.php
 *   Steps    → components/Home/how-it-works.php
 *   Calendar → components/Home/events.php
 *   CTA      → components/Shared/cta-band.php
 * Page-specific sections live in components/ScientificEngagement/
 * (formats.php, resources.php).
 *
 * Figma: "Scientific Engagement" (294:6841, 1920 wide). No mobile frame
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
// Scientific Engagement › Hero
// =====================================================================
/**
 * Figma: 02 — Hero (294:6843). Label, H1 left; text and two buttons right;
 * wide image below. Same markup as the About hero plus its optional buttons.
 * The hidden "Hero facts" row in Figma is not built.
 * Markup: components/About/hero.php · Styles: src/styles/components/about/hero.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_se_hero', __('Scientific Engagement Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left, text and buttons right, wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('engagement', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Scientific Engagement')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Science grows when it is shared.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('Seminars, research-club sessions and community engagement that make computational and methodological thinking accessible to students, early-career researchers and research teams.')
                ->set_help_text(__('Shown to the right of the heading (below it on smaller screens). Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Primary button (teal) ----------
            Field::make('separator', 'hero_sep_primary', __('Primary Button', 'niyom')),

            Field::make('text', 'hero_primary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Discuss your project'),
            Field::make('text', 'hero_primary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'hero_primary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Secondary button (outline) ----------
            Field::make('separator', 'hero_sep_secondary', __('Secondary Button', 'niyom')),

            Field::make('text', 'hero_secondary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Book a consultation'),
            Field::make('text', 'hero_secondary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'hero_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Image ----------
            Field::make('separator', 'hero_sep_image', __('Image', 'niyom')),

            Field::make('image', 'hero_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/scientific-engagement/hero-dna-laboratory.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('DNA double helix beside a pipette and a monitor showing sequencing data')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            // The "Why engagement" section below brings its own 140px top spacing (Figma).
            $fields['hero_flush_bottom'] = true;
            get_template_part('components/About/hero', null, $fields);
        });
});


// =====================================================================
// Scientific Engagement › Why engagement
// =====================================================================
/**
 * Figma: 03 — Introduction (294:7342). Label + large statement left,
 * paragraph right.
 * Markup: components/Shared/intro.php · Styles: src/styles/components/shared/_intro.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_se_intro', __('Scientific Engagement Why', 'niyom'))
        ->set_description(__('Label and large statement left, paragraph right.', 'niyom'))
        ->set_icon('text')
        ->set_keywords([__('why', 'niyom'), __('intro', 'niyom'), __('statement', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'intro_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Why engagement')
                ->set_help_text(__('Brackets are added automatically: "( WHY ENGAGEMENT )".', 'niyom')),

            Field::make('textarea', 'intro_heading', __('Statement', 'niyom'))
                ->set_rows(3)
                ->set_default_value('Building research capacity means more than delivering analyses. It means helping people learn to ask better questions of their own data.')
                ->set_help_text(__('Large text on the left. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'intro_text', __('Text (right)', 'niyom'))
                ->set_default_value('Scientific engagement is how NIYOM contributes beyond individual projects — sharing practical methods, reproducible habits and an honest view of what computational approaches can and cannot do.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/intro', null, $fields);
        });
});


// =====================================================================
// Scientific Engagement › Engagement formats
// =====================================================================
/**
 * Figma: 04 — Engagement Formats (294:7348). Warm off-white section: label,
 * heading and a row of white cards (image, number, title, text, tags).
 * Markup: components/ScientificEngagement/formats.php
 * Styles: src/styles/components/scientific-engagement/_formats.scss
 */
add_action('carbon_fields_register_fields', function () {

    $images = NIYOM_URI . '/assets/images/scientific-engagement/';

    Block::make('niyom_se_formats', __('Scientific Engagement Formats', 'niyom'))
        ->set_description(__('Label, heading and format cards: image, number, title, text and "Suitable for" tags.', 'niyom'))
        ->set_icon('grid-view')
        ->set_keywords([__('formats', 'niyom'), __('seminars', 'niyom'), __('cards', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'formats_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Formats')
                ->set_help_text(__('Brackets are added automatically: "( FORMATS )".', 'niyom')),

            Field::make('textarea', 'formats_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Ways to engage.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('text', 'formats_tags_label', __('Tags Label', 'niyom'))
                ->set_default_value('Suitable for:')
                ->set_help_text(__('Small label before the tags on every card. Shown in capitals.', 'niyom')),

            // ---------- Cards ----------
            Field::make('separator', 'formats_sep_items', __('Formats', 'niyom')),

            Field::make('complex', 'formats_items', __('Formats', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Formats', 'niyom'),
                    'singular_name' => __('Format', 'niyom'),
                ])
                ->add_fields([
                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_width(50)
                        ->set_help_text(__('Shown 544 × 420 on desktop (cropped to fit). Use about 1200 × 900 px.', 'niyom')),
                    Field::make('text', 'image_alt', __('Image Alt Text', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),

                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number cards automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

                    Field::make('textarea', 'tags', __('Tags', 'niyom'))
                        ->set_rows(3)
                        ->set_help_text(__('One tag per line. Shown as pills after the tags label.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'       => 'se-format-university-seminars',
                        '_type'     => '_',
                        'image'     => $images . 'format-university-seminars.jpg',
                        'image_alt' => 'Audience listening to a talk in a bright lecture hall',
                        'number'    => '',
                        'title'     => 'University seminars',
                        'text'      => 'Talks and interactive seminars for departments, graduate programmes and research centres — tailored to the audience’s field and level.',
                        'tags'      => "Departments\nGraduate programmes\nResearch centres",
                    ],
                    [
                        '_id'       => 'se-format-research-club',
                        '_type'     => '_',
                        'image'     => $images . 'format-research-club.jpg',
                        'image_alt' => 'Microscope beside a tablet showing plant cell imagery',
                        'number'    => '',
                        'title'     => 'Research-club sessions',
                        'text'      => 'Smaller, hands-on sessions for journal clubs and student research societies, built around discussion and worked examples.',
                        'tags'      => "Journal clubs\nStudent societies\nLab groups",
                    ],
                    [
                        '_id'       => 'se-format-community-engagement',
                        '_type'     => '_',
                        'image'     => $images . 'format-community-engagement.jpg',
                        'image_alt' => 'Molecular model with red and dark atoms',
                        'number'    => '',
                        'title'     => 'Scientific community engagement',
                        'text'      => 'Contributions to workshops, panels and open discussions that strengthen the wider research community in Bangladesh and beyond.',
                        'tags'      => "Journal clubs\nStudent societies\nLab groups",
                    ],
                ])
                ->set_help_text(__('Each tab is one card. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/ScientificEngagement/formats', null, $fields);
        });
});


// =====================================================================
// Scientific Engagement › Seminar request process
// =====================================================================
/**
 * Figma: Content (294:7006). Same design as the Home "How NIYOM works"
 * section (label, heading, button, 4 step cards with icons), so it uses the
 * same fields and markup. The hidden older step row (294:7013) is not built.
 * Markup: components/Home/how-it-works.php · Styles: src/styles/components/home/how-it-works.scss
 */
add_action('carbon_fields_register_fields', function () {

    $icons = NIYOM_URI . '/assets/icons/';

    Block::make('niyom_se_process', __('Scientific Engagement Process', 'niyom'))
        ->set_description(__('Intro with a button and numbered step cards (icon, title, text).', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('steps', 'niyom'), __('process', 'niyom'), __('seminar request', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'steps_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Seminar request process')
                ->set_help_text(__('Brackets are added automatically: "( SEMINAR REQUEST PROCESS )".', 'niyom')),

            Field::make('textarea', 'steps_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('From request to session.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

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
                        '_id'    => 'se-step-submit-request',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-share-question.svg',
                        'title'  => 'Submit a request',
                        'text'   => 'Tell us about your institution, audience, preferred topic and possible dates.',
                    ],
                    [
                        '_id'    => 'se-step-confirm-fit',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-review-fit.svg',
                        'title'  => 'Review and confirm fit',
                        'text'   => 'We confirm whether the topic falls within verified expertise and suggest a format.',
                    ],
                    [
                        '_id'    => 'se-step-plan-session',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-agree-scope.svg',
                        'title'  => 'Plan the session',
                        'text'   => 'We agree content, duration, delivery mode and any materials needed in advance.',
                    ],
                    [
                        '_id'    => 'se-step-deliver-share',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-deliver-handover.svg',
                        'title'  => 'Deliver and share',
                        'text'   => 'The session takes place; approved resources and a recap are shared afterwards.',
                    ],
                ])
                ->set_help_text(__('Each tab is one step. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/how-it-works', null, $fields);
        });
});


// =====================================================================
// Scientific Engagement › Upcoming engagements (calendar)
// =====================================================================
/**
 * Figma: Content (294:7406). Same design as the Home "Events &
 * partnerships" section (intro left, event rows right), so it uses the same
 * fields and markup. The hidden background image (294:7414) is not built.
 * Markup: components/Home/events.php · Styles: src/styles/components/home/events.scss
 */
add_action('carbon_fields_register_fields', function () {

    $images = NIYOM_URI . '/assets/images/home/';

    Block::make('niyom_se_events', __('Scientific Engagement Calendar', 'niyom'))
        ->set_description(__('Intro text with a button, and a list of event rows (date, type, title, venue, link, image).', 'niyom'))
        ->set_icon('calendar-alt')
        ->set_keywords([__('events', 'niyom'), __('calendar', 'niyom'), __('seminar', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([

            // ---------- Intro (left column) ----------
            Field::make('text', 'events_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Upcoming engagements')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'events_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('On the calendar.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'events_text', __('Text', 'niyom'))
                ->set_default_value('Connecting researchers, academic institutions, and scientific communities through knowledge-sharing initiatives, seminars, and collaborative opportunities.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('text', 'events_button_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('View all events'),
            Field::make('text', 'events_button_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/events-and-partnershiphubs/'),
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
                        '_id'       => 'se-event-university-seminar',
                        '_type'     => '_',
                        'day'       => '14',
                        'month'     => 'Jan 2027',
                        'type'      => 'University seminar',
                        'title'     => 'Reproducible analysis in practice: from raw data to figure',
                        'meta'      => '[Host institution] · In person',
                        'link'      => '/events-and-partnershiphubs/',
                        'link_text' => 'View details',
                        'new_tab'   => false,
                        'image'     => $images . 'events-university-seminar.jpg',
                    ],
                    [
                        '_id'       => 'se-event-research-club',
                        '_type'     => '_',
                        'day'       => '22',
                        'month'     => 'Jan 2027',
                        'type'      => 'Research-club session',
                        'title'     => 'Reading a genomics paper critically: methods first',
                        'meta'      => '[Student research society] · Online',
                        'link'      => '/events-and-partnershiphubs/',
                        'link_text' => 'View details',
                        'new_tab'   => false,
                        'image'     => $images . 'events-research-club.jpg',
                    ],
                    [
                        '_id'       => 'se-event-community-workshop',
                        '_type'     => '_',
                        'day'       => '13',
                        'month'     => 'Feb 2027',
                        'type'      => 'Community workshop',
                        'title'     => 'Asking answerable questions of biological data',
                        'meta'      => '[Partner organisation] · Hybrid',
                        'link'      => '/events-and-partnershiphubs/',
                        'link_text' => 'View details',
                        'new_tab'   => false,
                        'image'     => $images . 'events-community-workshop.jpg',
                    ],
                ])
                ->set_help_text(__('Each tab is one event. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Home/events', null, $fields);
        });
});


// =====================================================================
// Scientific Engagement › Resources & event recaps
// =====================================================================
/**
 * Figma: 07 — Resources & Event Recaps (294:7575). Intro (label + heading
 * left, note right) and resource cards: image, type · date, title, text, link.
 * Markup: components/ScientificEngagement/resources.php
 * Styles: src/styles/components/scientific-engagement/_resources.scss
 */
add_action('carbon_fields_register_fields', function () {

    $images = NIYOM_URI . '/assets/images/scientific-engagement/';

    Block::make('niyom_se_resources', __('Scientific Engagement Resources', 'niyom'))
        ->set_description(__('Intro and resource cards: image, type and date, title, text and a link.', 'niyom'))
        ->set_icon('media-document')
        ->set_keywords([__('resources', 'niyom'), __('recaps', 'niyom'), __('slides', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'resources_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Resources & recaps')
                ->set_help_text(__('Brackets are added automatically: "( RESOURCES & RECAPS )".', 'niyom')),

            Field::make('textarea', 'resources_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Approved materials from past sessions.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'resources_text', __('Text (right)', 'niyom'))
                ->set_default_value('Only resources cleared for public sharing by NIYOM and the host are published.')
                ->set_help_text(__('Short note to the right of the heading. Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Cards ----------
            Field::make('separator', 'resources_sep_items', __('Resources', 'niyom')),

            Field::make('complex', 'resources_items', __('Resources', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Resources', 'niyom'),
                    'singular_name' => __('Resource', 'niyom'),
                ])
                ->add_fields([
                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_width(50)
                        ->set_help_text(__('Shown 544 × 340 on desktop (cropped to fit). Use about 1200 × 750 px.', 'niyom')),
                    Field::make('text', 'image_alt', __('Image Alt Text', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),

                    Field::make('text', 'type', __('Type Label', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('e.g. "Event recap", "Slides". Shown in capitals, teal.', 'niyom')),
                    Field::make('text', 'date', __('Date', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('e.g. "12 Oct 2026". Shown in capitals.', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

                    Field::make('text', 'link_text', __('Link Text', 'niyom'))
                        ->set_width(40),
                    Field::make('text', 'link', __('Link', 'niyom'))
                        ->set_width(40)
                        ->set_help_text(__('The whole card links here. If empty, the card is not a link.', 'niyom')),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))
                        ->set_width(20),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'       => 'se-resource-event-recap',
                        '_type'     => '_',
                        'image'     => $images . 'resource-event-recap.jpg',
                        'image_alt' => 'Two researchers discussing notes on a whiteboard',
                        'type'      => 'Event recap',
                        'date'      => '12 Oct 2026',
                        'title'     => '[Recap title — past university seminar]',
                        'text'      => 'A short summary of the session, key discussion points and follow-up resources.',
                        'link_text' => 'Read more',
                        'link'      => '/insights/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'se-resource-slides',
                        '_type'     => '_',
                        'image'     => $images . 'resource-slides.jpg',
                        'image_alt' => 'Glowing blue DNA double helix',
                        'type'      => 'Slides',
                        'date'      => '12 Oct 2026',
                        'title'     => '[Approved slide deck — session title]',
                        'text'      => 'Presentation slides shared with the host’s permission.',
                        'link_text' => 'Read more',
                        'link'      => '/insights/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'se-resource-reading-list',
                        '_type'     => '_',
                        'image'     => $images . 'resource-reading-list.jpg',
                        'image_alt' => 'Gloved hand holding a petri dish with leaf skeletons',
                        'type'      => 'Resource',
                        'date'      => '12 Oct 2026',
                        'title'     => '[Worked example or reading list]',
                        'text'      => 'Supporting material prepared for participants.',
                        'link_text' => 'Read more',
                        'link'      => '/insights/',
                        'new_tab'   => false,
                    ],
                ])
                ->set_help_text(__('Each tab is one card. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/ScientificEngagement/resources', null, $fields);
        });
});


// =====================================================================
// Scientific Engagement › CTA
// =====================================================================
/**
 * Figma: 08 — CTA (303:8270). Teal band with network image, label, white
 * heading and a white button.
 * Markup: components/Shared/cta-band.php · Styles: src/styles/components/shared/_cta-band.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_se_cta', __('Scientific Engagement CTA', 'niyom'))
        ->set_description(__('Teal call-to-action band: label, heading and up to two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('seminar', 'niyom')])
        ->set_category('niyom-scientific-engagement', __('Niyom — Scientific Engagement', 'niyom'), 'groups')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'cta_band_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Request a seminar')
                ->set_help_text(__('Brackets are added automatically: "( REQUEST A SEMINAR )".', 'niyom')),

            Field::make('textarea', 'cta_band_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Bring practical, honest computational thinking to your institution.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('image', 'cta_band_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Faint pattern under the teal colour (shown at 10%). Use about 2000 px wide. Leave empty for plain teal.', 'niyom')),

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

            // ---------- Secondary button (outline) ----------
            Field::make('separator', 'cta_band_sep_secondary', __('Secondary Button (outline)', 'niyom')),

            Field::make('text', 'cta_band_secondary_text', __('Button Text', 'niyom'))
                ->set_width(40),
            Field::make('text', 'cta_band_secondary_link', __('Button Link', 'niyom'))
                ->set_width(40),
            Field::make('checkbox', 'cta_band_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/cta-band', null, $fields);
        });
});

<?php
/**
 * About page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/About/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/about/<name>.scss, imported in index.scss.
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
// From: about-hero.php
// =====================================================================
/**
 * About › Hero block (Carbon Fields).
 *
 * Figma: About NIYOM — Desktop › 02 — Hero (290:4853).
 * No mobile About frame exists in Figma; mobile follows the Home mobile
 * patterns (Home — Mobile 276:1785).
 * Markup: components/About/hero.php · Styles: src/styles/components/about/hero.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_hero', __('About Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left, paragraph right and a wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('about', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('About NIYOM')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically: ( ABOUT NIYOM ).', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('A scientific practice built on clarity, care and evidence.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('NIYOM is a Bangladesh-rooted scientific practice that helps researchers plan studies, analyse biological data and communicate findings with confidence — working alongside teams in Bangladesh and internationally.')
                ->set_help_text(__('Shown to the right of the heading (below it on smaller screens). Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('image', 'hero_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/about/hero-molecular-network.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('Abstract molecular network')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/hero', null, $fields);
        });
});


// =====================================================================
// From: about-mission.php
// =====================================================================
/**
 * About › Brand & Mission block (Carbon Fields).
 *
 * Figma: About NIYOM — Desktop › 03 — Brand & Mission (290:4870).
 * No mobile About frame exists in Figma; mobile follows the Home mobile
 * patterns (Home — Mobile 276:1785).
 * Markup: components/About/mission.php · Styles: src/styles/components/about/mission.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_mission', __('About Mission', 'niyom'))
        ->set_description(__('Label and large heading left; intro text and a numbered list right.', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('about', 'niyom'), __('mission', 'niyom'), __('list', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'mission_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Our mission')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically: ( OUR MISSION ).', 'niyom')),

            Field::make('textarea', 'mission_title', __('Heading', 'niyom'))
                ->set_rows(3)
                ->set_default_value('Helping researchers move from data to defensible insight.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'mission_intro', __('Intro Text', 'niyom'))
                ->set_default_value('Good science depends on asking the right question, choosing methods that fit it, and being honest about what the results can and cannot show. NIYOM exists to make that discipline easier to practise — especially for teams with limited access to specialist computational or methodological support.')
                ->set_help_text(__('Shown above the list. Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('complex', 'mission_items', __('List', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Items', 'niyom'),
                    'singular_name' => __('Item', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number', 'niyom'))
                        ->set_help_text(__('e.g. 01', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line.', 'niyom')),
                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'about-mission-item-1',
                        '_type'  => '_',
                        'number' => '01',
                        'title'  => 'Clarify the question',
                        'text'   => 'We begin with the research question and study design before any analysis is planned.',
                    ],
                    [
                        '_id'    => 'about-mission-item-2',
                        '_type'  => '_',
                        'number' => '02',
                        'title'  => 'Choose appropriate methods',
                        'text'   => 'Methods are selected for fit and transparency, not novelty, and their assumptions are documented.',
                    ],
                    [
                        '_id'    => 'about-mission-item-3',
                        '_type'  => '_',
                        'number' => '03',
                        'title'  => 'Communicate findings honestly',
                        'text'   => 'Results are presented with their uncertainty, limitations and the evidence behind them.',
                    ],
                ])
                ->set_help_text(__('Each tab is one numbered item. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/mission', null, $fields);
        });
});


// =====================================================================
// From: about-founder.php
// =====================================================================
/**
 * About › Founder Profile block (Carbon Fields).
 *
 * Figma: About NIYOM — Desktop › 04 — Founder Profile (290:4891).
 * The portrait is an empty Mist placeholder frame in Figma, so the default
 * image is a plain Mist (#EAF5F5) placeholder until a real photo is added.
 * Hidden Figma layers ("Placeholder Note", "Link / Arrow") are not built.
 * No mobile About frame exists in Figma; mobile follows the Home mobile
 * patterns (Home — Mobile 276:1785).
 * Markup: components/About/founder.php · Styles: src/styles/components/about/founder.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_founder', __('About Founder', 'niyom'))
        ->set_description(__('Portrait left; name, role, biography and a table of profile details right.', 'niyom'))
        ->set_icon('admin-users')
        ->set_keywords([__('about', 'niyom'), __('founder', 'niyom'), __('profile', 'niyom'), __('team', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([
            Field::make('image', 'founder_image', __('Portrait', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/about/founder-portrait.jpg')
                ->set_help_text(__('Shown 680 × 820 on desktop (cropped to fit). Use about 1360 × 1640 px. The default is a plain placeholder.', 'niyom')),

            Field::make('text', 'founder_image_alt', __('Portrait Alt Text', 'niyom'))
                ->set_help_text(__('e.g. "Portrait of Dr Jane Doe". Leave empty while the placeholder is used.', 'niyom')),

            Field::make('text', 'founder_name', __('Name', 'niyom'))
                ->set_default_value('[Founder name]'),

            Field::make('text', 'founder_role', __('Role', 'niyom'))
                ->set_default_value('Founder & Principal Scientific Consultant')
                ->set_help_text(__('Small text under the name.', 'niyom')),

            Field::make('textarea', 'founder_lead', __('Intro (large text)', 'niyom'))
                ->set_rows(3)
                ->set_default_value('NIYOM is led by its founder, who serves as Principal Scientific Consultant and is directly involved in every engagement — from the first scoping conversation to the final handover.')
                ->set_help_text(__('Larger text under the name. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'founder_bio', __('Biography', 'niyom'))
                ->set_default_value('[Verified biography to be supplied: academic training, research background, areas of specialisation and selected professional experience. Only details that can be evidenced will be published.]')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('complex', 'founder_details', __('Profile Details', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Rows', 'niyom'),
                    'singular_name' => __('Row', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'label', __('Label', 'niyom'))
                        ->set_help_text(__('Small uppercase label on the left, e.g. Based in.', 'niyom')),
                    Field::make('rich_text', 'value', __('Value', 'niyom'))
                        ->set_help_text(__('Links work here (e.g. ORCID, Google Scholar, LinkedIn).', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'about-founder-detail-1',
                        '_type' => '_',
                        'label' => 'Role',
                        'value' => 'Founder & Principal Scientific Consultant',
                    ],
                    [
                        '_id'   => 'about-founder-detail-2',
                        '_type' => '_',
                        'label' => 'Based in',
                        'value' => 'Bangladesh',
                    ],
                    [
                        '_id'   => 'about-founder-detail-3',
                        '_type' => '_',
                        'label' => 'Focus areas',
                        'value' => 'Computational biosciences · Research support · Scientific engagement',
                    ],
                    [
                        '_id'   => 'about-founder-detail-4',
                        '_type' => '_',
                        'label' => 'Research profiles',
                        'value' => '[ORCID · Google Scholar · LinkedIn — links to be supplied]',
                    ],
                ])
                ->set_help_text(__('Each tab is one row of the table. Drag tabs to reorder. Rows with no label and no value are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/founder', null, $fields);
        });
});


// =====================================================================
// From: about-values.php
// =====================================================================
/**
 * About "Values & Philosophy" block (Carbon Fields).
 *
 * Figma: About NIYOM — Desktop › 05 — Values & Philosophy (290:4915).
 * No mobile About frame exists in Figma; mobile follows the Home mobile patterns.
 * Intro (eyebrow, heading, text on the right) and a row of value cards:
 * number, title, text. Numbers come from the order (01, 02 …) unless typed in.
 *
 * Markup: components/About/values.php
 * Styles: src/styles/components/about/values.scss
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_values', __('About Values', 'niyom'))
        ->set_description(__('Values & philosophy: intro and numbered value cards (title, text).', 'niyom'))
        ->set_icon('awards')
        ->set_keywords([__('values', 'niyom'), __('philosophy', 'niyom'), __('principles', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'values_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Scientific values')
                ->set_help_text(__('Brackets are added automatically: "( SCIENTIFIC VALUES )".', 'niyom')),

            Field::make('textarea', 'values_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Principles that shape how we think and work.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'values_text', __('Text (right)', 'niyom'))
                ->set_default_value('Our research philosophy is simple: a method is only as useful as the question it answers and the honesty with which its limits are stated.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Value cards ----------
            Field::make('separator', 'values_sep_items', __('Value Cards', 'niyom')),

            Field::make('complex', 'values_items', __('Value Cards', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Cards', 'niyom'),
                    'singular_name' => __('Card', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number cards automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('text', 'title', __('Title', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'about-value-1',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Rigour before speed',
                        'text'   => 'We take the time to understand data, design and context before drawing conclusions.',
                    ],
                    [
                        '_id'    => 'about-value-2',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Transparency about limits',
                        'text'   => 'Every method has assumptions. We state them plainly and explain what they mean for your results.',
                    ],
                    [
                        '_id'    => 'about-value-3',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Reproducible by default',
                        'text'   => 'Analyses are scripted, versioned and documented so that others can follow and repeat them.',
                    ],
                    [
                        '_id'    => 'about-value-4',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Your question comes first',
                        'text'   => 'We support the researcher’s scientific intent rather than steering work toward our preferred tools.',
                    ],
                ])
                ->set_help_text(__('Each tab is one card. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/values', null, $fields);
        });
});


// =====================================================================
// From: about-integrity.php
// =====================================================================
/**
 * About "Research Integrity & Ethics" block (Carbon Fields).
 *
 * Figma: About NIYOM — Desktop › 06 — Research Integrity & Ethics (290:4927).
 * No mobile About frame exists in Figma; mobile follows the Home mobile patterns.
 * Deep Ink section with a faint background image. Left: eyebrow, heading,
 * text. Right: 2-column grid of numbered commitments (number, title, text).
 *
 * Markup: components/About/integrity.php
 * Styles: src/styles/components/about/integrity.scss
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_integrity', __('About Integrity', 'niyom'))
        ->set_description(__('Dark section: intro left, numbered commitments right.', 'niyom'))
        ->set_icon('shield')
        ->set_keywords([__('integrity', 'niyom'), __('ethics', 'niyom'), __('commitments', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([
            // ---------- Background ----------
            Field::make('image', 'integrity_bg', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/about/integrity-bg.jpg')
                ->set_help_text(__('Shown very faintly (4%) over the Deep Ink colour. Wide image, about 2000 × 1150 px.', 'niyom')),

            // ---------- Intro ----------
            Field::make('separator', 'integrity_sep_intro', __('Intro', 'niyom')),

            Field::make('text', 'integrity_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Integrity & ethics')
                ->set_help_text(__('Brackets are added automatically: "( INTEGRITY & ETHICS )".', 'niyom')),

            Field::make('textarea', 'integrity_heading', __('Heading', 'niyom'))
                ->set_rows(3)
                ->set_default_value('Integrity is not a clause in a contract. It is how the work is done.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'integrity_text', __('Text', 'niyom'))
                ->set_default_value('These commitments apply to every engagement, regardless of scope or timeline.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Commitments ----------
            Field::make('separator', 'integrity_sep_items', __('Commitments', 'niyom')),

            Field::make('complex', 'integrity_items', __('Commitments', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Commitments', 'niyom'),
                    'singular_name' => __('Commitment', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('text', 'title', __('Title', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'about-integrity-1',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Your data remains yours',
                        'text'   => 'We do not reuse, share or retain research data beyond the agreed scope of work.',
                    ],
                    [
                        '_id'    => 'about-integrity-2',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Work within verified expertise',
                        'text'   => 'If a question falls outside our competence, we say so and help you find the right support.',
                    ],
                    [
                        '_id'    => 'about-integrity-3',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Fair authorship and acknowledgement',
                        'text'   => 'Contributions are recognised according to accepted authorship norms agreed at the outset.',
                    ],
                    [
                        '_id'    => 'about-integrity-4',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Confidentiality by agreement',
                        'text'   => 'Unpublished findings and project details are treated as confidential.',
                    ],
                    [
                        '_id'    => 'about-integrity-5',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'No promised outcomes',
                        'text'   => 'We cannot guarantee significance, acceptance or funding — only careful, honest work.',
                    ],
                    [
                        '_id'    => 'about-integrity-6',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Declared conflicts of interest',
                        'text'   => 'Any potential conflict is disclosed before work begins.',
                    ],
                ])
                ->set_help_text(__('Each tab is one commitment. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/integrity', null, $fields);
        });
});


// =====================================================================
// From: about-work.php
// =====================================================================
/**
 * About "Publications, Tools & Contributions" block (Carbon Fields).
 *
 * Figma: About NIYOM — Desktop › 07 — Publications, Tools & Contributions (290:4960).
 * No mobile About frame exists in Figma; on mobile the table becomes stacked rows.
 * Intro (eyebrow, heading) and a table: Type / Title / Venue / year, plus a
 * "Download" link per row when a file is chosen. (The hidden "View full
 * record" button in Figma is not built.)
 *
 * Markup: components/About/work.php
 * Styles: src/styles/components/about/work.scss
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_work', __('About Work', 'niyom'))
        ->set_description(__('Selected work: table of publications, tools and talks with download links.', 'niyom'))
        ->set_icon('media-document')
        ->set_keywords([__('publications', 'niyom'), __('work', 'niyom'), __('downloads', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'work_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Publications & contributions')
                ->set_help_text(__('Brackets are added automatically: "( PUBLICATIONS & CONTRIBUTIONS )".', 'niyom')),

            Field::make('textarea', 'work_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Selected work.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            // ---------- Table ----------
            Field::make('separator', 'work_sep_table', __('Table', 'niyom')),

            Field::make('text', 'work_col_type', __('Column Heading: Type', 'niyom'))
                ->set_default_value('Type')
                ->set_width(33),
            Field::make('text', 'work_col_title', __('Column Heading: Title', 'niyom'))
                ->set_default_value('Title')
                ->set_width(33),
            Field::make('text', 'work_col_venue', __('Column Heading: Venue / year', 'niyom'))
                ->set_default_value('Venue / year')
                ->set_width(33),

            Field::make('image', 'work_download_icon', __('Download Icon', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/icons/work-download.svg')
                ->set_help_text(__('24 × 24. SVG recommended: it takes the link colour.', 'niyom')),

            Field::make('complex', 'work_rows', __('Rows', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Rows', 'niyom'),
                    'singular_name' => __('Row', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'type', __('Type', 'niyom'))
                        ->set_width(50),
                    Field::make('text', 'venue', __('Venue / year', 'niyom'))
                        ->set_width(50),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2),

                    Field::make('file', 'file', __('Download File', 'niyom'))
                        ->set_value_type('url')
                        ->set_width(50)
                        ->set_help_text(__('No file = no download link.', 'niyom')),
                    Field::make('text', 'download_label', __('Download Label', 'niyom'))
                        ->set_default_value('Download')
                        ->set_width(50),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'            => 'about-work-1',
                        '_type'          => '_',
                        'type'           => 'Peer-reviewed article',
                        'title'          => '[Title of verified publication]',
                        'venue'          => '[Journal, year]',
                        'file'           => '',
                        'download_label' => 'Download',
                    ],
                    [
                        '_id'            => 'about-work-2',
                        '_type'          => '_',
                        'type'           => 'Preprint',
                        'title'          => '[Title of verified preprint]',
                        'venue'          => '[Server, year]',
                        'file'           => '',
                        'download_label' => 'Download',
                    ],
                    [
                        '_id'            => 'about-work-3',
                        '_type'          => '_',
                        'type'           => 'Open tool / R package',
                        'title'          => '[Name of verified tool or workflow]',
                        'venue'          => '[Repository link]',
                        'file'           => '',
                        'download_label' => 'Download',
                    ],
                    [
                        '_id'            => 'about-work-4',
                        '_type'          => '_',
                        'type'           => 'Talk or seminar',
                        'title'          => '[Title of verified talk]',
                        'venue'          => '[Institution, year]',
                        'file'           => '',
                        'download_label' => 'Download',
                    ],
                ])
                ->set_help_text(__('Each tab is one row. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/work', null, $fields);
        });
});


// =====================================================================
// From: about-roots.php
// =====================================================================
/**
 * About › Bangladesh Roots & International Positioning block (Carbon Fields).
 *
 * Figma: About NIYOM › "08 — Bangladesh Roots & International Positioning" (290:4995).
 * No mobile About frame exists in Figma; mobile follows the Home — Mobile 360 patterns.
 * Markup: components/About/roots.php · Styles: src/styles/components/about/roots.scss
 *
 * Left: image. Right: eyebrow, heading, text and a row of facts
 * (label + value, e.g. "Base" / "Bangladesh"). Empty fields are not shown.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_roots', __('About Roots', 'niyom'))
        ->set_description(__('Image on the left; heading, text and key facts on the right.', 'niyom'))
        ->set_icon('location-alt')
        ->set_keywords([__('bangladesh', 'niyom'), __('roots', 'niyom'), __('global', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([

            // ---------- Image (left column) ----------
            Field::make('image', 'roots_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/about/roots-global-network.jpg')
                ->set_help_text(__('Shown at 800 × 640 (cropped to fit). Use at least 1600 px wide.', 'niyom')),

            Field::make('text', 'roots_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('Global research network')
                ->set_help_text(__('Describes the image for screen readers.', 'niyom')),

            // ---------- Text (right column) ----------
            Field::make('separator', 'roots_sep_text', __('Text', 'niyom')),

            Field::make('text', 'roots_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Global perspective')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'roots_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Rooted in Bangladesh. Working across borders.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'roots_text', __('Text', 'niyom'))
                ->set_default_value('NIYOM began in Bangladesh, where access to specialist computational and methodological support is still developing. That context shapes how we work: practical, resource-aware and focused on building lasting capability. The same approach serves researchers and institutions internationally.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Facts (row under the text) ----------
            Field::make('complex', 'roots_facts', __('Facts', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Facts', 'niyom'),
                    'singular_name' => __('Fact', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'label', __('Label', 'niyom'))
                        ->set_help_text(__('Small uppercase label, e.g. "Base".', 'niyom')),

                    Field::make('textarea', 'value', __('Value', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Bengali text is supported.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'about-roots-fact-base',
                        '_type' => '_',
                        'label' => 'Base',
                        'value' => 'Bangladesh',
                    ],
                    [
                        '_id'   => 'about-roots-fact-reach',
                        '_type' => '_',
                        'label' => 'Reach',
                        'value' => 'Researchers and institutions worldwide',
                    ],
                    [
                        '_id'   => 'about-roots-fact-language',
                        '_type' => '_',
                        'label' => 'Working language',
                        'value' => 'English · বাংলা',
                    ],
                ])
                ->set_help_text(__('Shown in a row of up to 3 columns (stacked on mobile).', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/roots', null, $fields);
        });
});


// =====================================================================
// From: about-cta.php
// =====================================================================
/**
 * About › CTA block (Carbon Fields).
 *
 * Figma: About NIYOM › "06 — CTA" (291:5183): white section, centred eyebrow,
 * large heading and two buttons (primary + secondary).
 * No mobile About frame exists in Figma; mobile follows the Home — Mobile 360 patterns.
 * Markup: components/About/cta.php · Styles: src/styles/components/about/cta.scss
 *
 * Empty fields are not shown. A button needs both text and a link.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_about_cta', __('About CTA', 'niyom'))
        ->set_description(__('Centred call to action: eyebrow, heading and two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('contact', 'niyom')])
        ->set_category('niyom-about', __('Niyom — About', 'niyom'), 'id')
        ->set_mode('both')
        ->add_fields([

            // ---------- Text ----------
            Field::make('text', 'cta_eyebrow', __('Eyebrow', 'niyom'))
                ->set_default_value('Discuss your project')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'cta_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Tell us about your research question. We’ll suggest where to begin.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            // ---------- Primary button (teal) ----------
            Field::make('separator', 'cta_sep_primary', __('Primary Button', 'niyom')),

            Field::make('text', 'cta_primary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Discuss your project'),
            Field::make('text', 'cta_primary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'cta_primary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Secondary button (outline) ----------
            Field::make('separator', 'cta_sep_secondary', __('Secondary Button', 'niyom')),

            Field::make('text', 'cta_secondary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Book a consultation'),
            Field::make('text', 'cta_secondary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'cta_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/cta', null, $fields);
        });
});

<?php
/**
 * Computational Biosciences page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/<Folder>/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/<folder>/_<name>.scss.
 *
 * Sections shared with the other service pages use the shared components
 * (field names documented in components/Shared/README.md):
 *   Hero         → components/About/hero.php (with optional buttons)
 *   Overview     → components/Shared/intro.php
 *   Deliverables → components/Shared/cards.php
 *   Process      → components/Shared/process.php
 *   FAQ          → components/Shared/faq.php
 *   CTA          → components/Shared/cta-band.php
 * The numbered areas list is page-specific: components/ComputationalBiosciences/areas.php.
 *
 * Figma: "Computational Biosciences" (294:8068, 1920 wide). No mobile frame
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
// Computational Biosciences › Hero
// =====================================================================
/**
 * Figma: 02 — Hero (294:8070). Label, H1 left; text and two buttons right;
 * wide image below. Same markup as the About hero plus its optional buttons.
 * The hidden "Hero facts" row in Figma is not built.
 * Markup: components/About/hero.php · Styles: src/styles/components/about/hero.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_cb_hero', __('Computational Biosciences Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left, text and buttons right, wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('computational', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Computational Biosciences')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Computational biosciences, done verifiably.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('From study planning to reproducible R workflows, we help research teams analyse biological data with methods they can explain, repeat and defend.')
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
                ->set_default_value(NIYOM_URI . '/assets/images/computational-biosciences/hero-molecular-strand.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('A golden strand passing through three rings among floating particles')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            // The Overview section below brings its own 140px top spacing (Figma).
            $fields['hero_flush_bottom'] = true;
            get_template_part('components/About/hero', null, $fields);
        });
});


// =====================================================================
// Computational Biosciences › Overview
// =====================================================================
/**
 * Figma: 03 — Introduction (294:8091). Label + large statement left,
 * paragraph right.
 * Markup: components/Shared/intro.php · Styles: src/styles/components/shared/_intro.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_cb_intro', __('Computational Biosciences Overview', 'niyom'))
        ->set_description(__('Label and large statement left, paragraph right.', 'niyom'))
        ->set_icon('text')
        ->set_keywords([__('overview', 'niyom'), __('intro', 'niyom'), __('statement', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'intro_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Overview')
                ->set_help_text(__('Brackets are added automatically: "( OVERVIEW )".', 'niyom')),

            Field::make('textarea', 'intro_heading', __('Statement', 'niyom'))
                ->set_rows(3)
                ->set_default_value('Most analysis problems begin long before analysis. We start where the question, the design and the data meet.')
                ->set_help_text(__('Large text on the left. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'intro_text', __('Text (right)', 'niyom'))
                ->set_default_value('Computational support is most valuable when it is planned with the study rather than added at the end. We work with your team to make sure the data you collect can answer the question you are asking — and that every step from raw files to final figures is documented.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/intro', null, $fields);
        });
});


// =====================================================================
// Computational Biosciences › Areas of support
// =====================================================================
/**
 * Figma: 04 — Capabilities (294:8423). Intro column left; numbered rows
 * with title, text and tags right.
 * Markup: components/ComputationalBiosciences/areas.php
 * Styles: src/styles/components/computational-biosciences/_areas.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_cb_areas', __('Computational Biosciences Areas', 'niyom'))
        ->set_description(__('Intro left; numbered list of support areas with tags, right.', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('areas', 'niyom'), __('capabilities', 'niyom'), __('services', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'areas_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('What we support')
                ->set_help_text(__('Brackets are added automatically: "( WHAT WE SUPPORT )".', 'niyom')),

            Field::make('textarea', 'areas_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Five areas of computational support.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'areas_text', __('Text', 'niyom'))
                ->set_default_value('Engage one area or combine several into a single scoped project.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Areas ----------
            Field::make('separator', 'areas_sep_items', __('Areas', 'niyom')),

            Field::make('complex', 'areas_items', __('Areas', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Areas', 'niyom'),
                    'singular_name' => __('Area', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number rows automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

                    Field::make('textarea', 'tags', __('Tags', 'niyom'))
                        ->set_rows(3)
                        ->set_help_text(__('One tag per line. Shown as small teal pills.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'cb-area-planning',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Study and analysis planning',
                        'text'   => 'Translate your research question into an analysis plan: design considerations, sample size reasoning, appropriate models and pre-specified outputs.',
                        'tags'   => "Study design\nAnalysis plan\nPower reasoning",
                    ],
                    [
                        '_id'    => 'cb-area-data-readiness',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Data readiness and quality assessment',
                        'text'   => 'Review raw and processed data for completeness, structure, batch effects and quality issues before analysis begins.',
                        'tags'   => "Data audit\nQC report\nMetadata review",
                    ],
                    [
                        '_id'    => 'cb-area-genomics',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Verified genomics and NGS analysis',
                        'text'   => 'Processing and analysis of sequencing data using established, documented pipelines within verified experience.',
                        'tags'   => "Genomics / NGS\nPipelines\nAnnotation",
                    ],
                    [
                        '_id'    => 'cb-area-statistics',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Statistical analysis and scientific visualisation',
                        'text'   => 'Appropriate statistical modelling with clear, honest figures that show the data and its uncertainty.',
                        'tags'   => "Statistics\nVisualisation\nInterpretation",
                    ],
                    [
                        '_id'    => 'cb-area-r-workflows',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Reproducible R workflows and custom scripts',
                        'text'   => 'Scripted, version-controlled R workflows and custom tools that your team can rerun, adapt and maintain.',
                        'tags'   => "R Workflow\nQuarto / R Markdown\nVersion control",
                    ],
                ])
                ->set_help_text(__('Each tab is one numbered row. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/ComputationalBiosciences/areas', null, $fields);
        });
});


// =====================================================================
// Computational Biosciences › Deliverables
// =====================================================================
/**
 * Figma: 05 — Deliverables, Documentation & Handover (296:8492). Intro and
 * six Mist cards (icon, title, text) in three columns.
 * Markup: components/Shared/cards.php · Styles: src/styles/components/shared/_cards.scss
 */
add_action('carbon_fields_register_fields', function () {

    $icon = NIYOM_URI . '/assets/icons/deliverable-document.svg';

    Block::make('niyom_cb_deliverables', __('Computational Biosciences Deliverables', 'niyom'))
        ->set_description(__('Intro and a grid of cards: icon, title and text.', 'niyom'))
        ->set_icon('grid-view')
        ->set_keywords([__('deliverables', 'niyom'), __('cards', 'niyom'), __('handover', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'cards_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Deliverables')
                ->set_help_text(__('Brackets are added automatically: "( DELIVERABLES )".', 'niyom')),

            Field::make('textarea', 'cards_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('What you receive at handover.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'cards_text', __('Text (right)', 'niyom'))
                ->set_default_value('Deliverables are agreed in writing at scoping. A typical engagement includes some or all of the following.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Cards ----------
            Field::make('separator', 'cards_sep_items', __('Cards', 'niyom')),

            Field::make('complex', 'cards_items', __('Cards', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Cards', 'niyom'),
                    'singular_name' => __('Card', 'niyom'),
                ])
                ->add_fields([
                    Field::make('image', 'icon', __('Icon', 'niyom'))
                        ->set_value_type('url')
                        ->set_help_text(__('Line icon, 32 × 32. SVG recommended: it is shown in Research Teal.', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'cb-deliverable-analysis-plan',
                        '_type' => '_',
                        'icon'  => $icon,
                        'title' => 'Analysis plan',
                        'text'  => 'A written plan describing questions, methods, assumptions and planned outputs.',
                    ],
                    [
                        '_id'   => 'cb-deliverable-quality-report',
                        '_type' => '_',
                        'icon'  => $icon,
                        'title' => 'Data quality report',
                        'text'  => 'A summary of data readiness, issues found and how they were handled.',
                    ],
                    [
                        '_id'   => 'cb-deliverable-scripts',
                        '_type' => '_',
                        'icon'  => $icon,
                        'title' => 'Annotated scripts',
                        'text'  => 'Commented R scripts or Quarto documents that reproduce every result.',
                    ],
                    [
                        '_id'   => 'cb-deliverable-figures',
                        '_type' => '_',
                        'icon'  => $icon,
                        'title' => 'Figures and tables',
                        'text'  => 'Publication-ready visualisations with the code used to generate them.',
                    ],
                    [
                        '_id'   => 'cb-deliverable-methods',
                        '_type' => '_',
                        'icon'  => $icon,
                        'title' => 'Methods description',
                        'text'  => 'A draft methods section your team can adapt for a manuscript or report.',
                    ],
                    [
                        '_id'   => 'cb-deliverable-handover',
                        '_type' => '_',
                        'icon'  => $icon,
                        'title' => 'Handover session',
                        'text'  => 'A walkthrough so your team can rerun, extend and maintain the work.',
                    ],
                ])
                ->set_help_text(__('Each tab is one card. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/cards', null, $fields);
        });
});


// =====================================================================
// Computational Biosciences › Engagement process
// =====================================================================
/**
 * Figma: "Engagement process" (296:8550). Intro and four numbered step cards.
 * Markup: components/Shared/process.php · Styles: src/styles/components/shared/_process.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_cb_process', __('Computational Biosciences Process', 'niyom'))
        ->set_description(__('Intro and numbered step cards (number, title, text).', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('process', 'niyom'), __('steps', 'niyom'), __('engagement', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'process_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Engagement process')
                ->set_help_text(__('Brackets are added automatically: "( ENGAGEMENT PROCESS )".', 'niyom')),

            Field::make('textarea', 'process_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('From enquiry to handover in four steps.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'process_text', __('Text (right)', 'niyom'))
                ->set_default_value('Our research philosophy is simple: a method is only as useful as the question it answers and the honesty with which its limits are stated.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Steps ----------
            Field::make('separator', 'process_sep_items', __('Steps', 'niyom')),

            Field::make('complex', 'process_items', __('Steps', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Steps', 'niyom'),
                    'singular_name' => __('Step', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'number', __('Number (optional)', 'niyom'))
                        ->set_help_text(__('Leave empty to number steps automatically: 01, 02, 03 …', 'niyom')),

                    Field::make('text', 'title', __('Title', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'    => 'cb-process-enquiry',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Enquiry',
                        'text'   => 'Share your question and the kind of data involved. No data is needed at this stage.',
                    ],
                    [
                        '_id'    => 'cb-process-scoping',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Scoping call',
                        'text'   => 'We agree objectives, methods, deliverables, timing and how data will be shared securely.',
                    ],
                    [
                        '_id'    => 'cb-process-analysis',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Analysis',
                        'text'   => 'Work proceeds in documented stages with review points you can follow.',
                    ],
                    [
                        '_id'    => 'cb-process-handover',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Handover',
                        'text'   => 'Results, code and documentation, plus a session to walk your team through them.',
                    ],
                ])
                ->set_help_text(__('Each tab is one step. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/process', null, $fields);
        });
});


// =====================================================================
// Computational Biosciences › FAQ
// =====================================================================
/**
 * Figma: 07 — FAQs (296:8579). Heading left, accordion right.
 * Markup: components/Shared/faq.php · Styles: src/styles/components/shared/_faq.scss
 * Script: src/scripts/components/shared-faq.js
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_cb_faq', __('Computational Biosciences FAQ', 'niyom'))
        ->set_description(__('Heading left; questions that open to show their answers, right.', 'niyom'))
        ->set_icon('editor-help')
        ->set_keywords([__('faq', 'niyom'), __('questions', 'niyom'), __('accordion', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'faq_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('FAQs')
                ->set_help_text(__('Brackets are added automatically: "( FAQS )".', 'niyom')),

            Field::make('textarea', 'faq_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Common questions about analysis support.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('checkbox', 'faq_open_first', __('Open the first question', 'niyom'))
                ->set_option_value('yes')
                ->set_default_value(true),

            Field::make('complex', 'faq_items', __('Questions', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Questions', 'niyom'),
                    'singular_name' => __('Question', 'niyom'),
                ])
                ->add_fields([
                    Field::make('textarea', 'question', __('Question', 'niyom'))
                        ->set_rows(2),

                    Field::make('rich_text', 'answer', __('Answer', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'      => 'cb-faq-scoping-data',
                        '_type'    => '_',
                        'question' => 'Do I need to send data before the scoping call?',
                        'answer'   => 'No. We only need a description of your question, study design and the type of data involved. Data is shared later, through a method agreed in the scope.',
                    ],
                    [
                        '_id'      => 'cb-faq-data-types',
                        '_type'    => '_',
                        'question' => 'Which data types do you work with?',
                        'answer'   => 'We work with data types covered by verified experience. The current list is shown on the Expertise page; if your data type is not listed, ask and we will tell you honestly whether we can help.',
                    ],
                    [
                        '_id'      => 'cb-faq-rerun',
                        '_type'    => '_',
                        'question' => 'Will I be able to rerun the analysis myself?',
                        'answer'   => 'Yes. Reproducibility is a core deliverable — scripts, environment details and documentation are handed over with the results.',
                    ],
                    [
                        '_id'      => 'cb-faq-significance',
                        '_type'    => '_',
                        'question' => 'Can you guarantee significant results?',
                        'answer'   => 'No. We commit to appropriate, transparent methods and honest reporting, not to particular outcomes.',
                    ],
                    [
                        '_id'      => 'cb-faq-pricing',
                        '_type'    => '_',
                        'question' => 'How is pricing decided?',
                        'answer'   => 'Pricing is based on the agreed scope and shared in writing before any work begins.',
                    ],
                ])
                ->set_help_text(__('Each tab is one question. Drag tabs to reorder. A question without an answer shows without the + icon.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/faq', null, $fields);
        });
});


// =====================================================================
// Computational Biosciences › CTA
// =====================================================================
/**
 * Figma: 08 — CTA (296:8627). Teal band with network image, label, white
 * heading and a white button.
 * Markup: components/Shared/cta-band.php · Styles: src/styles/components/shared/_cta-band.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_cb_cta', __('Computational Biosciences CTA', 'niyom'))
        ->set_description(__('Teal call-to-action band: label, heading and up to two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('contact', 'niyom')])
        ->set_category('niyom-computational-biosciences', __('Niyom — Computational Biosciences', 'niyom'), 'chart-area')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'cta_band_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Start here')
                ->set_help_text(__('Brackets are added automatically: "( START HERE )".', 'niyom')),

            Field::make('textarea', 'cta_band_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Have data, or a study in planning? Let’s scope it together.')
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

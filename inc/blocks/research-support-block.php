<?php
/**
 * Research Support page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/<Folder>/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/<folder>/_<name>.scss.
 *
 * Sections that match existing components reuse them (field names
 * documented in components/Shared/README.md):
 *   Hero          → components/About/hero.php (with optional buttons)
 *   Why           → components/Shared/intro.php
 *   Support areas → components/About/integrity.php (same Deep Ink design)
 *   Workflow      → components/Home/how-it-works.php (on warm off-white)
 *   FAQ           → components/Shared/faq.php (on warm off-white)
 *   CTA           → components/Shared/cta-band.php
 * The Deliverables list + "Support, not authorship" box is page-specific:
 * components/ResearchSupport/deliverables.php.
 *
 * Two reused sections sit on the warm off-white (#FCFBF7) in this Figma
 * page while their components are white. The render callbacks wrap them
 * in <div class="rs-tone-subtle">, styled in
 * src/styles/components/research-support/_tone.scss.
 *
 * Figma: "Research Support" (296:8932, 1920 wide). No mobile frame exists
 * in Figma; mobile follows the Home mobile patterns (276:1785).
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
// Research Support › Hero
// =====================================================================
/**
 * Figma: 02 — Hero (296:8934). Label, H1 left; text and two buttons right;
 * wide image below. The hidden "Hero facts" row in Figma is not built.
 * Markup: components/About/hero.php · Styles: src/styles/components/about/hero.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_rs_hero', __('Research Support Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left, text and buttons right, wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('research support', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Research Support')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Stronger research, from question to publication.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('Independent, constructive scientific review for researchers preparing studies, manuscripts and reviewer responses — focused on clarity, logic and methodological soundness.')
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
                ->set_default_value(NIYOM_URI . '/assets/images/research-support/hero-fungal-filaments.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('Close-up of fine filaments with small dark spore heads against a warm amber background')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            // The section below brings its own 140px top spacing (Figma).
            $fields['hero_flush_bottom'] = true;
            get_template_part('components/About/hero', null, $fields);
        });
});


// =====================================================================
// Research Support › Why research support
// =====================================================================
/**
 * Figma: 03 — Introduction (296:8955). Label + large statement left,
 * paragraph right.
 * Markup: components/Shared/intro.php · Styles: src/styles/components/shared/_intro.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_rs_intro', __('Research Support Why', 'niyom'))
        ->set_description(__('Label and large statement left, paragraph right.', 'niyom'))
        ->set_icon('text')
        ->set_keywords([__('why', 'niyom'), __('intro', 'niyom'), __('statement', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'intro_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Why research support')
                ->set_help_text(__('Brackets are added automatically: "( WHY RESEARCH SUPPORT )".', 'niyom')),

            Field::make('textarea', 'intro_heading', __('Statement', 'niyom'))
                ->set_rows(3)
                ->set_default_value('Good studies are sometimes held back not by their science, but by how clearly that science is framed, shown and argued.')
                ->set_help_text(__('Large text on the left. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'intro_text', __('Text (right)', 'niyom'))
                ->set_default_value('We act as a careful second reader — reviewing logic, methods and presentation so that reviewers and readers can engage with your findings rather than your formatting. Your work, your voice and your authorship remain your own.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Shared/intro', null, $fields);
        });
});


// =====================================================================
// Research Support › Support areas
// =====================================================================
/**
 * Figma: "06 — Research Integrity & Ethics" frame (302:1191), content
 * "Six ways we strengthen your work.". Deep Ink section with a faint
 * background image; intro left, six numbered areas right in 2 columns.
 * Same design as the About integrity section, so it reuses its markup.
 * Markup: components/About/integrity.php · Styles: src/styles/components/about/integrity.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_rs_areas', __('Research Support Areas', 'niyom'))
        ->set_description(__('Dark section: intro left, numbered support areas right.', 'niyom'))
        ->set_icon('screenoptions')
        ->set_keywords([__('areas', 'niyom'), __('support', 'niyom'), __('services', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Background ----------
            Field::make('image', 'integrity_bg', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/research-support/areas-bg.jpg')
                ->set_help_text(__('Shown very faintly (4%) over the Deep Ink colour. Wide image, about 2000 × 1150 px. Leave empty for plain Deep Ink.', 'niyom')),

            // ---------- Intro ----------
            Field::make('separator', 'integrity_sep_intro', __('Intro', 'niyom')),

            Field::make('text', 'integrity_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Support areas')
                ->set_help_text(__('Brackets are added automatically: "( SUPPORT AREAS )".', 'niyom')),

            Field::make('textarea', 'integrity_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Six ways we strengthen your work.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('rich_text', 'integrity_text', __('Text', 'niyom'))
                ->set_default_value('Deliverables are agreed in writing at scoping. A typical engagement includes some or all of the following.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Areas ----------
            Field::make('separator', 'integrity_sep_items', __('Areas', 'niyom')),

            Field::make('complex', 'integrity_items', __('Areas', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Areas', 'niyom'),
                    'singular_name' => __('Area', 'niyom'),
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
                        '_id'    => 'rs-area-rigour',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Rigour before speed',
                        'text'   => 'We take the time to understand data, design and context before drawing conclusions.',
                    ],
                    [
                        '_id'    => 'rs-area-limits',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Transparency about limits',
                        'text'   => 'Every method has assumptions. We state them plainly and explain what they mean for your results.',
                    ],
                    [
                        '_id'    => 'rs-area-reproducible',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Reproducible by default',
                        'text'   => 'Analyses are scripted, versioned and documented so that others can follow and repeat them.',
                    ],
                    [
                        '_id'    => 'rs-area-limits-2',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Transparency about limits',
                        'text'   => 'Every method has assumptions. We state them plainly and explain what they mean for your results.',
                    ],
                    [
                        '_id'    => 'rs-area-reproducible-2',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Reproducible by default',
                        'text'   => 'Analyses are scripted, versioned and documented so that others can follow and repeat them.',
                    ],
                    [
                        '_id'    => 'rs-area-question-first',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Your question comes first',
                        'text'   => 'We support the researcher’s scientific intent rather than steering work toward our preferred tools.',
                    ],
                ])
                ->set_help_text(__('Each tab is one area. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/integrity', null, $fields);
        });
});


// =====================================================================
// Research Support › Workflow
// =====================================================================
/**
 * Figma: "Content" (303:1257), "How a support request moves forward.".
 * Intro and four step cards with 80px icons, on warm off-white. The hidden
 * timeline frame and button in Figma are not built (button left empty).
 * Markup: components/Home/how-it-works.php · Styles: src/styles/components/home/how-it-works.scss
 * Background: src/styles/components/research-support/_tone.scss
 */
add_action('carbon_fields_register_fields', function () {

    $icons = NIYOM_URI . '/assets/icons/';

    Block::make('niyom_rs_workflow', __('Research Support Workflow', 'niyom'))
        ->set_description(__('Intro and numbered step cards (icon, title, text) on warm off-white.', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('workflow', 'niyom'), __('steps', 'niyom'), __('process', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'steps_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Workflow')
                ->set_help_text(__('Brackets are added automatically: "( WORKFLOW )".', 'niyom')),

            Field::make('textarea', 'steps_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('How a support request moves forward.')
                ->set_help_text(__('Press Enter for a new line.', 'niyom')),

            Field::make('text', 'steps_button_text', __('Button Text (optional)', 'niyom'))
                ->set_width(40),
            Field::make('text', 'steps_button_link', __('Button Link', 'niyom'))
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
                        '_id'    => 'rs-step-share',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-share-question.svg',
                        'title'  => 'Share the research question',
                        'text'   => 'Tell us about your objective, research stage, data type and the decision or deliverable you need.',
                    ],
                    [
                        '_id'    => 'rs-step-review',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-review-fit.svg',
                        'title'  => 'Review fit and feasibility',
                        'text'   => 'We clarify requirements, limitations, timeline, data readiness and any ethical or governance considerations.',
                    ],
                    [
                        '_id'    => 'rs-step-scope',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-agree-scope.svg',
                        'title'  => 'Agree scope and deliverables',
                        'text'   => 'Define the work plan, responsibilities, milestones, outputs and communication expectations.',
                    ],
                    [
                        '_id'    => 'rs-step-deliver',
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
            echo '<div class="rs-tone-subtle">';
            get_template_part('components/Home/how-it-works', null, $fields);
            echo '</div>';
        });
});


// =====================================================================
// Research Support › Deliverables and boundaries
// =====================================================================
/**
 * Figma: "Content" (303:1387). Left: label, H2 and a list of deliverables
 * (title, text). Right: Mist box "Support, not authorship." with a list
 * of what research support never includes. The hidden timeline frame and
 * button in Figma are not built.
 * Markup: components/ResearchSupport/deliverables.php
 * Styles: src/styles/components/research-support/_deliverables.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_rs_deliverables', __('Research Support Deliverables', 'niyom'))
        ->set_description(__('List of deliverables left; boundaries box right.', 'niyom'))
        ->set_icon('list-view')
        ->set_keywords([__('deliverables', 'niyom'), __('boundaries', 'niyom'), __('authorship', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'deliverables_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Deliverables')
                ->set_help_text(__('Brackets are added automatically: "( DELIVERABLES )".', 'niyom')),

            Field::make('textarea', 'deliverables_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('What you can expect to receive.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            // ---------- Deliverables ----------
            Field::make('separator', 'deliverables_sep_items', __('Deliverables', 'niyom')),

            Field::make('complex', 'deliverables_items', __('Deliverables', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Deliverables', 'niyom'),
                    'singular_name' => __('Deliverable', 'niyom'),
                ])
                ->add_fields([
                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'rs-deliverable-memo',
                        '_type' => '_',
                        'title' => 'Methods review memo',
                        'text'  => 'A structured summary of strengths, risks and suggested changes.',
                    ],
                    [
                        '_id'   => 'rs-deliverable-manuscript',
                        '_type' => '_',
                        'title' => 'Annotated manuscript',
                        'text'  => 'In-line comments on structure, clarity and consistency.',
                    ],
                    [
                        '_id'   => 'rs-deliverable-figures',
                        '_type' => '_',
                        'title' => 'Figure recommendations',
                        'text'  => 'Notes on each figure’s purpose, design and accuracy.',
                    ],
                    [
                        '_id'   => 'rs-deliverable-checklist',
                        '_type' => '_',
                        'title' => 'Submission checklist',
                        'text'  => 'Journal requirements and reporting guidelines to confirm.',
                    ],
                    [
                        '_id'   => 'rs-deliverable-response',
                        '_type' => '_',
                        'title' => 'Response framework',
                        'text'  => 'A structured plan for addressing each reviewer comment.',
                    ],
                ])
                ->set_help_text(__('Each tab is one row. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),

            // ---------- Boundaries box ----------
            Field::make('separator', 'boundaries_sep', __('Boundaries Box (right)', 'niyom')),

            Field::make('text', 'boundaries_label', __('Label', 'niyom'))
                ->set_default_value('Our boundaries')
                ->set_help_text(__('Small teal label, shown without brackets.', 'niyom')),

            Field::make('textarea', 'boundaries_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Support, not authorship.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'boundaries_text', __('Text', 'niyom'))
                ->set_default_value('To protect your work and its integrity, research support never includes:')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('complex', 'boundaries_items', __('List', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Items', 'niyom'),
                    'singular_name' => __('Item', 'niyom'),
                ])
                ->add_fields([
                    Field::make('textarea', 'text', __('Text', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Shown after a teal dash. Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'rs-boundary-writing',
                        '_type' => '_',
                        'text'  => 'Writing manuscripts on your behalf',
                    ],
                    [
                        '_id'   => 'rs-boundary-data',
                        '_type' => '_',
                        'text'  => 'Generating, altering or selectively reporting data',
                    ],
                    [
                        '_id'   => 'rs-boundary-acceptance',
                        '_type' => '_',
                        'text'  => 'Guaranteeing acceptance by any journal',
                    ],
                    [
                        '_id'   => 'rs-boundary-submitting',
                        '_type' => '_',
                        'text'  => 'Submitting to journals or contacting editors for you',
                    ],
                ])
                ->set_help_text(__('Each tab is one line. Drag tabs to reorder. Empty lines are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/ResearchSupport/deliverables', null, $fields);
        });
});


// =====================================================================
// Research Support › FAQ
// =====================================================================
/**
 * Figma: 07 — FAQs (296:9081). Heading left, accordion right, on warm
 * off-white.
 * Markup: components/Shared/faq.php · Styles: src/styles/components/shared/_faq.scss
 * Script: src/scripts/components/shared-faq.js
 * Background: src/styles/components/research-support/_tone.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_rs_faq', __('Research Support FAQ', 'niyom'))
        ->set_description(__('Heading left; questions that open to show their answers, right.', 'niyom'))
        ->set_icon('editor-help')
        ->set_keywords([__('faq', 'niyom'), __('questions', 'niyom'), __('accordion', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'faq_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('FAQs')
                ->set_help_text(__('Brackets are added automatically: "( FAQS )".', 'niyom')),

            Field::make('textarea', 'faq_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Questions about research support.')
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
                        '_id'      => 'rs-faq-other-field',
                        '_type'    => '_',
                        'question' => 'Can you help if my manuscript is in another field?',
                        'answer'   => 'We support work within areas of verified expertise. If your field falls outside them, we will tell you and, where possible, suggest alternatives.',
                    ],
                    [
                        '_id'      => 'rs-faq-author',
                        '_type'    => '_',
                        'question' => 'Will you be listed as an author?',
                        'answer'   => 'Authorship and acknowledgement are agreed at the outset using accepted authorship criteria. Most research support is acknowledged rather than authored.',
                    ],
                    [
                        '_id'      => 'rs-faq-timing',
                        '_type'    => '_',
                        'question' => 'How far ahead should I get in touch?',
                        'answer'   => 'As early as possible — ideally before a deadline. Timing is confirmed in writing during scoping.',
                    ],
                    [
                        '_id'      => 'rs-faq-confidential',
                        '_type'    => '_',
                        'question' => 'Is my manuscript kept confidential?',
                        'answer'   => 'Yes. Unpublished work is treated as confidential and is not shared or reused.',
                    ],
                    [
                        '_id'      => 'rs-faq-online',
                        '_type'    => '_',
                        'question' => 'Can a consultation be held online?',
                        'answer'   => 'Yes. Consultations can take place online; in-person sessions can be discussed where practical.',
                    ],
                ])
                ->set_help_text(__('Each tab is one question. Drag tabs to reorder. A question without an answer shows without the + icon.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            echo '<div class="rs-tone-subtle">';
            get_template_part('components/Shared/faq', null, $fields);
            echo '</div>';
        });
});


// =====================================================================
// Research Support › CTA
// =====================================================================
/**
 * Figma: 08 — CTA (296:9091). Teal band with network image, label, white
 * heading and a white button.
 * Markup: components/Shared/cta-band.php · Styles: src/styles/components/shared/_cta-band.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_rs_cta', __('Research Support CTA', 'niyom'))
        ->set_description(__('Teal call-to-action band: label, heading and up to two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('contact', 'niyom')])
        ->set_category('niyom-research-support', __('Niyom — Research Support', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'cta_band_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Request research support')
                ->set_help_text(__('Brackets are added automatically: "( REQUEST RESEARCH SUPPORT )".', 'niyom')),

            Field::make('textarea', 'cta_band_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Preparing a study, manuscript or response? Let’s look at it together.')
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

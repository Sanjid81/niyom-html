<?php
/**
 * Services Overview page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/<Page>/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/<page>/<name>.scss, imported in index.scss.
 *
 * Sections that look the same as on other pages reuse those components:
 *   Hero  → components/About/hero.php
 *   Steps → components/Home/how-it-works.php
 *   CTA   → components/About/cta.php
 * New sections live in components/Services/.
 *
 * Figma: "Services Overview" (291:5194, 1920 wide). No mobile frame exists in
 * Figma; mobile follows the Home mobile patterns (Home — Mobile 276:1785).
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
// Services › Hero
// =====================================================================
/**
 * Services › Hero block (Carbon Fields).
 *
 * Figma: Services Overview › 02 — Hero (291:5196). Same layout as the About
 * hero, so it uses the same fields and markup. The hidden "Hero facts" row
 * in Figma is not built.
 * Markup: components/About/hero.php · Styles: src/styles/components/about/hero.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_services_hero', __('Services Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left, paragraph right and a wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('services', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-services', __('Niyom — Services', 'niyom'), 'portfolio')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Services')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically: ( SERVICES ).', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value("Three connected areas.\nOne careful way of working.")
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('From the first analysis plan to the final seminar, NIYOM supports researchers at the points where scientific work most often stalls — with methods that are transparent, documented and matched to the question.')
                ->set_help_text(__('Shown to the right of the heading (below it on smaller screens). Paragraphs, line breaks, links and images all work.', 'niyom')),

            Field::make('image', 'hero_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/services/hero-research.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('Close-up of a frosted plant structure in blue light')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/About/hero', null, $fields);
        });
});


// =====================================================================
// Services › Services list
// =====================================================================
/**
 * Services › Services list block (Carbon Fields).
 *
 * Figma: Services Overview › 03 (291:5213). Warm off-white section: intro
 * (label + heading left, text right) and one row per service: label, title,
 * text, a 2-column list of points, a "Know more" link and an image on the right.
 * Markup: components/Services/list.php · Styles: src/styles/components/services/list.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    $images = NIYOM_URI . '/assets/images/services/';

    Block::make('niyom_services_list', __('Services List', 'niyom'))
        ->set_description(__('Intro and one row per service: label, title, text, list of points, link and image.', 'niyom'))
        ->set_icon('list-view')
        ->set_keywords([__('services', 'niyom'), __('list', 'niyom'), __('what we do', 'niyom')])
        ->set_category('niyom-services', __('Niyom — Services', 'niyom'), 'portfolio')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'list_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('What we do')
                ->set_help_text(__('Brackets are added automatically: "( WHAT WE DO )".', 'niyom')),

            Field::make('textarea', 'list_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Choose the support your research needs now.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'list_text', __('Text (right)', 'niyom'))
                ->set_default_value('Each discipline can be engaged on its own or combined into a single, scoped project.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Services ----------
            Field::make('separator', 'list_sep_items', __('Services', 'niyom')),

            Field::make('complex', 'list_items', __('Services', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Services', 'niyom'),
                    'singular_name' => __('Service', 'niyom'),
                ])
                ->add_fields([
                    Field::make('text', 'label', __('Label', 'niyom'))
                        ->set_help_text(__('Small teal label above the title, e.g. "01 Computational Biosciences". Shown in capitals.', 'niyom')),

                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('rich_text', 'text', __('Text', 'niyom'))
                        ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

                    Field::make('textarea', 'points', __('List of Points', 'niyom'))
                        ->set_rows(6)
                        ->set_help_text(__('One point per line. Shown in two columns with an arrow before each. Links, <strong> and <em> are allowed.', 'niyom')),

                    Field::make('text', 'link_text', __('Link Text', 'niyom'))
                        ->set_width(40),
                    Field::make('text', 'link', __('Link', 'niyom'))
                        ->set_width(40),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))
                        ->set_width(20),

                    Field::make('image', 'image', __('Image', 'niyom'))
                        ->set_value_type('url')
                        ->set_width(50)
                        ->set_help_text(__('Shown 640 × 560 on desktop (cropped to fit). Use about 1300 × 1140 px.', 'niyom')),
                    Field::make('text', 'image_alt', __('Image Alt Text', 'niyom'))
                        ->set_width(50)
                        ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'       => 'services-list-computational-biosciences',
                        '_type'     => '_',
                        'label'     => '01 Computational Biosciences',
                        'title'     => 'Turning complex biological data into understanding you can defend.',
                        'text'      => 'Study and analysis planning, data-readiness review, verified genomics and NGS analysis, statistics and reproducible R workflows.',
                        'points'    => "Study planning\nData quality\nGenomics / NGS\nStatistics\nVisualisation\nR workflows",
                        'link_text' => 'Know more',
                        'link'      => '/computational-biosciences/',
                        'new_tab'   => false,
                        'image'     => $images . 'service-computational-biosciences.jpg',
                        'image_alt' => 'DNA double helix in a laboratory',
                    ],
                    [
                        '_id'       => 'services-list-research-support',
                        '_type'     => '_',
                        'label'     => '02 Research Support',
                        'title'     => 'Strengthening research at every stage, from question to publication.',
                        'text'      => 'Independent, constructive review of the parts of a study that reviewers scrutinise most.',
                        'points'    => "Research question & methods review\nManuscript structure & clarity\nFigure logic & results presentation\nJournal preparation\nReviewer response support\nScheduled scientific consultation",
                        'link_text' => 'Know more',
                        'link'      => '/research-support/',
                        'new_tab'   => false,
                        'image'     => $images . 'service-research-support.jpg',
                        'image_alt' => 'Microscope beside a tablet showing plant cell imagery',
                    ],
                    [
                        '_id'       => 'services-list-scientific-engagement',
                        '_type'     => '_',
                        'label'     => '03 — Scientific Engagement',
                        'title'     => 'Connecting knowledge, people and possibilities.',
                        'text'      => 'University seminars, research-club sessions and community engagement that make computational thinking accessible and practical.',
                        'points'    => "Study planning\nData quality\nGenomics / NGS\nStatistics\nVisualisation\nR workflows",
                        'link_text' => 'Know more',
                        'link'      => '/scientific-engagement/',
                        'new_tab'   => false,
                        'image'     => $images . 'service-scientific-engagement.jpg',
                        'image_alt' => 'Abstract molecular network',
                    ],
                ])
                ->set_help_text(__('Each tab is one service row. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Services/list', null, $fields);
        });
});


// =====================================================================
// Services › How NIYOM works
// =====================================================================
/**
 * Services "How NIYOM works" block (Carbon Fields).
 *
 * Figma: Services Overview › Content (291:6101). Same design as the Home
 * "How NIYOM works" section, so it uses the same fields and markup.
 * The hidden "04 — How NIYOM Works" timeline frame (291:6022) is not built.
 * Markup: components/Home/how-it-works.php · Styles: src/styles/components/home/how-it-works.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    $icons = NIYOM_URI . '/assets/icons/';

    Block::make('niyom_services_how_it_works', __('Services How It Works', 'niyom'))
        ->set_description(__('Intro with a button and numbered step cards (icon, title, text).', 'niyom'))
        ->set_icon('editor-ol')
        ->set_keywords([__('steps', 'niyom'), __('process', 'niyom'), __('how it works', 'niyom')])
        ->set_category('niyom-services', __('Niyom — Services', 'niyom'), 'portfolio')
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
                        '_id'    => 'services-step-1',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-share-question.svg',
                        'title'  => 'Share the research question',
                        'text'   => 'Tell us about your objective, research stage, data type and the decision or deliverable you need.',
                    ],
                    [
                        '_id'    => 'services-step-2',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-review-fit.svg',
                        'title'  => 'Review fit and feasibility',
                        'text'   => 'We clarify requirements, limitations, timeline, data readiness and any ethical or governance considerations.',
                    ],
                    [
                        '_id'    => 'services-step-3',
                        '_type'  => '_',
                        'number' => '',
                        'icon'   => $icons . 'steps-agree-scope.svg',
                        'title'  => 'Agree scope and deliverables',
                        'text'   => 'Define the work plan, responsibilities, milestones, outputs and communication expectations.',
                    ],
                    [
                        '_id'    => 'services-step-4',
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
// Services › Service selection guidance
// =====================================================================
/**
 * Services › Service Selection Guidance block (Carbon Fields).
 *
 * Figma: Services Overview › 05 — Service Selection Guidance (291:6186).
 * Mist section: label, heading and text on the left; a white table on the
 * right ("If you are…" / "Start with"). A row with a link is clickable as a
 * whole. On mobile each row stacks.
 * Markup: components/Services/guidance.php · Styles: src/styles/components/services/guidance.scss
 *
 * Any field left empty is not shown on the page.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_services_guidance', __('Services Guidance', 'niyom'))
        ->set_description(__('Intro left; table of situations and the service to start with, right.', 'niyom'))
        ->set_icon('editor-table')
        ->set_keywords([__('services', 'niyom'), __('guidance', 'niyom'), __('where to start', 'niyom')])
        ->set_category('niyom-services', __('Niyom — Services', 'niyom'), 'portfolio')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'guidance_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Where to start')
                ->set_help_text(__('Brackets are added automatically: "( WHERE TO START )".', 'niyom')),

            Field::make('textarea', 'guidance_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Not sure which service fits?')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'guidance_text', __('Text', 'niyom'))
                ->set_default_value('Find the situation closest to yours. If none fits, a short scoping call is the right first step.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Table ----------
            Field::make('separator', 'guidance_sep_table', __('Table', 'niyom')),

            Field::make('text', 'guidance_col_situation', __('Column Heading: Situation', 'niyom'))
                ->set_default_value('If you are…')
                ->set_width(50),
            Field::make('text', 'guidance_col_service', __('Column Heading: Service', 'niyom'))
                ->set_default_value('Start with')
                ->set_width(50),

            Field::make('complex', 'guidance_rows', __('Rows', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels([
                    'plural_name'   => __('Rows', 'niyom'),
                    'singular_name' => __('Row', 'niyom'),
                ])
                ->add_fields([
                    Field::make('textarea', 'situation', __('Situation', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Left column, e.g. "Planning a study…".', 'niyom')),

                    Field::make('textarea', 'service', __('Start With', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Right column (teal), e.g. "Research Support — methods review".', 'niyom')),

                    Field::make('text', 'link', __('Link', 'niyom'))
                        ->set_width(80)
                        ->set_help_text(__('Makes the whole row clickable and shows the arrow. Leave empty for a plain row.', 'niyom')),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))
                        ->set_width(20),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'       => 'services-guidance-study-planning',
                        '_type'     => '_',
                        'situation' => 'Planning a study, or unsure what analysis your data needs',
                        'service'   => 'Computational Biosciences — study & analysis planning',
                        'link'      => '/computational-biosciences/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'services-guidance-sequencing',
                        '_type'     => '_',
                        'situation' => 'Holding sequencing data that needs verified analysis',
                        'service'   => 'Computational Biosciences — genomics & NGS',
                        'link'      => '/computational-biosciences/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'services-guidance-methods',
                        '_type'     => '_',
                        'situation' => 'Refining a research question or reviewing methods',
                        'service'   => 'Research Support — methods review',
                        'link'      => '/research-support/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'services-guidance-manuscript',
                        '_type'     => '_',
                        'situation' => 'Writing a manuscript or responding to reviewers',
                        'service'   => 'Research Support — manuscript & reviewer support',
                        'link'      => '/research-support/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'services-guidance-seminar',
                        '_type'     => '_',
                        'situation' => 'Organising a seminar or research-club session',
                        'service'   => 'Scientific Engagement — seminar request',
                        'link'      => '/scientific-engagement/',
                        'new_tab'   => false,
                    ],
                    [
                        '_id'       => 'services-guidance-scoping',
                        '_type'     => '_',
                        'situation' => 'Unsure where your project fits',
                        'service'   => 'Work With Us — scoping conversation',
                        'link'      => '/work-with-us/',
                        'new_tab'   => false,
                    ],
                ])
                ->set_help_text(__('Each tab is one table row. Drag tabs to reorder. Empty fields are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Services/guidance', null, $fields);
        });
});


// =====================================================================
// Services › CTA
// =====================================================================
/**
 * Services › CTA block (Carbon Fields).
 *
 * Figma: Services Overview › 06 — CTA (291:5371). Same design as the About
 * CTA, so it uses the same fields and markup.
 * Markup: components/About/cta.php · Styles: src/styles/components/about/cta.scss
 *
 * Empty fields are not shown. A button needs both text and a link.
 *
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_services_cta', __('Services CTA', 'niyom'))
        ->set_description(__('Centred call to action: eyebrow, heading and two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('contact', 'niyom')])
        ->set_category('niyom-services', __('Niyom — Services', 'niyom'), 'portfolio')
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

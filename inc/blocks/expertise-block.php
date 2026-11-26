<?php
/**
 * Expertise page blocks (Carbon Fields), in page order.
 *
 * Pattern for each block:
 *   1. Block::make() here with its fields and default content.
 *   2. Markup in components/<Folder>/<name>.php, receiving fields as $args.
 *   3. Styles in src/styles/components/<folder>/_<name>.scss.
 *
 * Sections that match existing components reuse them:
 *   Hero      → components/About/hero.php (wrapped in .ex-hero: 570px text column)
 *   Resources → components/Home/insights.php (latest Insights posts; wrapped in
 *               .ex-resources: 120px sides, "All insights" text link)
 *   CTA       → components/Shared/cta-band.php (wrapped for two white buttons)
 * Page-specific sections (prefix "ex-"), components/Expertise/:
 *   Methods      → methods.php      (intro left, methods / data types table right)
 *   Domains      → domains.php      (intro left, labelled groups of tag pills right)
 *   Tech stack   → stack.php        (heading left, tool pills right)
 *   Practices    → practices.php    (Deep Ink, four numbered practices)
 *   Publications → publications.php (2 × 2 bordered cards)
 *
 * Figma: "Expertise" (303:3593, 1920 wide). No mobile frame exists in Figma;
 * mobile follows the Home mobile patterns (276:1785). Hidden Figma layers
 * (hero buttons, hero facts, the hidden "Research Integrity" copy and the
 * hidden tool cards) are not built.
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
// Expertise › Hero
// =====================================================================
/**
 * Figma: 02 — Hero (303:3595). Label, H1 left; paragraph right; wide image
 * below. The Figma buttons are hidden, so the button fields start empty.
 * Markup: components/About/hero.php · Styles: src/styles/components/about/hero.scss
 * (+ src/styles/components/expertise/_hero.scss)
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_ex_hero', __('Expertise Hero', 'niyom'))
        ->set_description(__('Page intro: label, large heading left, text (and optional buttons) right, wide image below.', 'niyom'))
        ->set_icon('cover-image')
        ->set_keywords([__('expertise', 'niyom'), __('hero', 'niyom'), __('intro', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'hero_eyebrow', __('Label', 'niyom'))
                ->set_default_value('Expertise')
                ->set_help_text(__('Small label above the heading. Brackets are added automatically.', 'niyom')),

            Field::make('textarea', 'hero_title', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('What we know well — and how we show it.')
                ->set_help_text(__('Main page heading (H1). Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'hero_text', __('Text', 'niyom'))
                ->set_default_value('A transparent catalogue of the methods, data types, domains and tools NIYOM works with. Only capabilities supported by verified experience and evidence are listed.')
                ->set_help_text(__('Shown to the right of the heading (below it on smaller screens). Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Primary button (teal) ----------
            Field::make('separator', 'hero_sep_primary', __('Primary Button (optional)', 'niyom')),

            Field::make('text', 'hero_primary_text', __('Button Text', 'niyom'))
                ->set_width(40),
            Field::make('text', 'hero_primary_link', __('Button Link', 'niyom'))
                ->set_width(40),
            Field::make('checkbox', 'hero_primary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Secondary button (outline) ----------
            Field::make('separator', 'hero_sep_secondary', __('Secondary Button (optional)', 'niyom')),

            Field::make('text', 'hero_secondary_text', __('Button Text', 'niyom'))
                ->set_width(40),
            Field::make('text', 'hero_secondary_link', __('Button Link', 'niyom'))
                ->set_width(40),
            Field::make('checkbox', 'hero_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Image ----------
            Field::make('separator', 'hero_sep_image', __('Image', 'niyom')),

            Field::make('image', 'hero_image', __('Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Wide image under the text, shown 1680 × 620 on desktop (cropped to fit). Use at least 2000 px wide.', 'niyom')),

            Field::make('text', 'hero_image_alt', __('Image Alt Text', 'niyom'))
                ->set_default_value('Glowing network of connected molecular nodes on a dark blue background')
                ->set_help_text(__('Describes the image for screen readers. Leave empty if the image is decorative.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            echo '<div class="ex-hero">';
            get_template_part('components/About/hero', null, $fields);
            echo '</div>';
        });
});


// =====================================================================
// Expertise › Methods & data types
// =====================================================================
/**
 * Figma: 03 — Introduction (303:3616). Label, H2 and short text left;
 * "Methods matrix" table right (Deep Ink header row, striped rows).
 * On mobile each table row becomes a stacked card.
 * Markup: components/Expertise/methods.php · Styles: src/styles/components/expertise/_methods.scss
 */
add_action('carbon_fields_register_fields', function () {

    $row = static function ($id, $method, $data) {
        return ['_id' => 'ex-method-' . $id, '_type' => '_', 'method' => $method, 'data' => $data];
    };

    Block::make('niyom_ex_methods', __('Expertise Methods', 'niyom'))
        ->set_description(__('Intro left; table of methods and the data types they suit, right.', 'niyom'))
        ->set_icon('editor-table')
        ->set_keywords([__('methods', 'niyom'), __('data types', 'niyom'), __('table', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'methods_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Methods & data types')
                ->set_help_text(__('Brackets are added automatically: "( METHODS & DATA TYPES )".', 'niyom')),

            Field::make('textarea', 'methods_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Methods matched to the data they suit.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'methods_text', __('Text', 'niyom'))
                ->set_default_value('Each method is listed with the data it is typically applied to and its verification status.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Table ----------
            Field::make('separator', 'methods_sep_table', __('Table', 'niyom')),

            Field::make('text', 'methods_col_method', __('First column heading', 'niyom'))
                ->set_width(50)
                ->set_default_value('Method'),
            Field::make('text', 'methods_col_data', __('Second column heading', 'niyom'))
                ->set_width(50)
                ->set_default_value('Typical data types')
                ->set_help_text(__('Also used as the small label above the data types on mobile.', 'niyom')),

            Field::make('complex', 'methods_items', __('Rows', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Rows', 'niyom'), 'singular_name' => __('Row', 'niyom')])
                ->add_fields([
                    Field::make('textarea', 'method', __('Method', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),
                    Field::make('textarea', 'data', __('Typical data types', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    $row('qc', 'Sequencing data quality control', 'FASTQ, BAM / CRAM, run metrics'),
                    $row('de', 'Differential expression analysis', 'Bulk RNA-seq count matrices'),
                    $row('variants', 'Variant calling & annotation', 'Whole-genome / exome sequencing'),
                    $row('stats', 'Statistical modelling', 'Tabular experimental & observational data'),
                    $row('eda', 'Exploratory data analysis & visualisation', 'Processed omics and tabular data'),
                    $row('reporting', 'Reproducible reporting', 'R scripts, Quarto / R Markdown documents'),
                ])
                ->set_help_text(__('Each tab is one table row. Drag tabs to reorder. Empty rows are hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Expertise/methods', null, $fields);
        });
});


// =====================================================================
// Expertise › Scientific domains & specialisations
// =====================================================================
/**
 * Figma: 05 — Scientific Domains & Specialisations (312:10348). Warm
 * off-white. Intro left; groups right, each a teal label over tag pills.
 * Markup: components/Expertise/domains.php · Styles: src/styles/components/expertise/_domains.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_ex_domains', __('Expertise Domains', 'niyom'))
        ->set_description(__('Intro left; labelled groups of tag pills right.', 'niyom'))
        ->set_icon('tag')
        ->set_keywords([__('domains', 'niyom'), __('tags', 'niyom'), __('specialisations', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'domains_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Domains')
                ->set_help_text(__('Brackets are added automatically: "( DOMAINS )".', 'niyom')),

            Field::make('textarea', 'domains_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Scientific domains and specialisations.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'domains_text', __('Text', 'niyom'))
                ->set_default_value('Domains reflect where NIYOM has worked or can evidence experience. Specialisations will be confirmed and expanded as verified.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Groups ----------
            Field::make('separator', 'domains_sep_groups', __('Tag Groups', 'niyom')),

            Field::make('complex', 'domains_groups', __('Groups', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Groups', 'niyom'), 'singular_name' => __('Group', 'niyom')])
                ->add_fields([
                    Field::make('text', 'title', __('Group label', 'niyom'))
                        ->set_help_text(__('Small teal label above the tags.', 'niyom')),
                    Field::make('textarea', 'tags', __('Tags', 'niyom'))
                        ->set_rows(6)
                        ->set_help_text(__('One tag per line.', 'niyom')),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    [
                        '_id'   => 'ex-domain-biological',
                        '_type' => '_',
                        'title' => 'Biological areas',
                        'tags'  => "Genomics\nTranscriptomics\nMolecular biology\nMicrobiology\nBiomedical research\nPublic health data",
                    ],
                    [
                        '_id'   => 'ex-domain-contexts',
                        '_type' => '_',
                        'title' => 'Study contexts',
                        'tags'  => "Laboratory experiments\nObservational studies\nClinical research (de-identified data)\nField and environmental sampling",
                    ],
                    [
                        '_id'   => 'ex-domain-specialisations',
                        '_type' => '_',
                        'title' => 'Specialisations',
                        'tags'  => "[Specialisation 1 — to be confirmed]\n[Specialisation 2 — to be confirmed]\n[Specialisation 3 — to be confirmed]",
                    ],
                ])
                ->set_help_text(__('Each tab is one group. Drag tabs to reorder. A group without tags is hidden.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Expertise/domains', null, $fields);
        });
});


// =====================================================================
// Expertise › Tools & technologies
// =====================================================================
/**
 * Figma: "Content" (303:3655), "The stack behind our work.". Label + H2
 * left; outlined tool pills (JetBrains Mono) right. The hidden button and
 * hidden card row in this Figma frame are not built.
 * Markup: components/Expertise/stack.php · Styles: src/styles/components/expertise/_stack.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_ex_stack', __('Expertise Tech Stack', 'niyom'))
        ->set_description(__('Heading left; outlined pills with tool names right.', 'niyom'))
        ->set_icon('editor-code')
        ->set_keywords([__('tools', 'niyom'), __('technologies', 'niyom'), __('stack', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'stack_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Tools & technologies')
                ->set_help_text(__('Brackets are added automatically: "( TOOLS & TECHNOLOGIES )".', 'niyom')),

            Field::make('textarea', 'stack_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('The stack behind our work.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('textarea', 'stack_items', __('Tools', 'niyom'))
                ->set_rows(10)
                ->set_default_value("R\nPython\nBioconductor\nNextflow\nSnakemake\nGATK\nDESeq2\nSeurat\nScanpy\nPyMOL\nAlphaFold\nscikit-learn\nPyTorch\nJupyter\nGit\nDocker")
                ->set_help_text(__('One tool per line, shown as pills in this order.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Expertise/stack', null, $fields);
        });
});


// =====================================================================
// Expertise › Reproducibility & quality practices
// =====================================================================
/**
 * Figma: 07 — Reproducibility & Quality Practices (312:10390). Deep Ink.
 * Label + white H2 left, short text right; four numbered practices in a
 * row (cyan numbers, white titles) under thin white rules.
 * Markup: components/Expertise/practices.php · Styles: src/styles/components/expertise/_practices.scss
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_ex_practices', __('Expertise Practices', 'niyom'))
        ->set_description(__('Dark section: heading and text, then numbered practices in a row.', 'niyom'))
        ->set_icon('yes-alt')
        ->set_keywords([__('practices', 'niyom'), __('quality', 'niyom'), __('reproducibility', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'practices_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Reproducibility & quality')
                ->set_help_text(__('Brackets are added automatically: "( REPRODUCIBILITY & QUALITY )".', 'niyom')),

            Field::make('textarea', 'practices_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Practices we apply to every analysis.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('rich_text', 'practices_text', __('Text (right)', 'niyom'))
                ->set_default_value('Quality is built into the workflow rather than checked at the end.')
                ->set_help_text(__('Paragraphs, line breaks, links and images all work.', 'niyom')),

            // ---------- Practices ----------
            Field::make('separator', 'practices_sep_items', __('Practices', 'niyom')),

            Field::make('complex', 'practices_items', __('Practices', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Practices', 'niyom'), 'singular_name' => __('Practice', 'niyom')])
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
                        '_id'    => 'ex-practice-scripted',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Scripted, not manual',
                        'text'   => 'Every transformation is captured in code, so results can be traced back to raw data.',
                    ],
                    [
                        '_id'    => 'ex-practice-versioned',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Versioned and recorded',
                        'text'   => 'Code is version-controlled and package versions are recorded for each project.',
                    ],
                    [
                        '_id'    => 'ex-practice-checked',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Checked at each stage',
                        'text'   => 'Intermediate outputs are reviewed against expectations before moving forward.',
                    ],
                    [
                        '_id'    => 'ex-practice-documented',
                        '_type'  => '_',
                        'number' => '',
                        'title'  => 'Documented for handover',
                        'text'   => 'Methods, assumptions and decisions are written down for your team and reviewers.',
                    ],
                ])
                ->set_help_text(__('Each tab is one practice. Four fit in a row on desktop. Drag tabs to reorder.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Expertise/practices', null, $fields);
        });
});


// =====================================================================
// Expertise › Selected publications
// =====================================================================
/**
 * Figma: 08 — Selected Publications & Contributions (312:10156). Label +
 * H2; bordered cards two per row: type label, title, citation line and a
 * "View publication" link. Cards are entered by hand (no matching post
 * type: Insights cards have images and dates, these do not).
 * Markup: components/Expertise/publications.php · Styles: src/styles/components/expertise/_publications.scss
 */
add_action('carbon_fields_register_fields', function () {

    $card = static function ($id, $type, $title) {
        return [
            '_id'       => 'ex-publication-' . $id,
            '_type'     => '_',
            'type'      => $type,
            'title'     => $title,
            'meta'      => '[Authors] · [Journal or venue] · [Year] · [DOI]',
            'link'      => '',
            'link_text' => 'View publication',
        ];
    };

    Block::make('niyom_ex_publications', __('Expertise Publications', 'niyom'))
        ->set_description(__('Heading and bordered publication cards, two per row.', 'niyom'))
        ->set_icon('book-alt')
        ->set_keywords([__('publications', 'niyom'), __('papers', 'niyom'), __('contributions', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'publications_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Publications & contributions')
                ->set_help_text(__('Brackets are added automatically: "( PUBLICATIONS & CONTRIBUTIONS )".', 'niyom')),

            Field::make('textarea', 'publications_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Selected publications.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('complex', 'publications_items', __('Publications', 'niyom'))
                ->set_layout('tabbed-horizontal')
                ->set_collapsed(false)
                ->setup_labels(['plural_name' => __('Publications', 'niyom'), 'singular_name' => __('Publication', 'niyom')])
                ->add_fields([
                    Field::make('text', 'type', __('Type label', 'niyom'))
                        ->set_help_text(__('Small teal label, e.g. "Peer-reviewed article".', 'niyom')),
                    Field::make('textarea', 'title', __('Title', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),
                    Field::make('textarea', 'meta', __('Citation line', 'niyom'))
                        ->set_rows(2)
                        ->set_help_text(__('Authors · venue · year · DOI. Links, <strong> and <em> are allowed.', 'niyom')),
                    Field::make('text', 'link', __('Link', 'niyom'))
                        ->set_width(40)
                        ->set_help_text(__('E.g. the DOI or repository URL. Without a link the label below is shown as plain text.', 'niyom')),
                    Field::make('text', 'link_text', __('Link Text', 'niyom'))
                        ->set_width(40),
                    Field::make('checkbox', 'new_tab', __('Open in new tab', 'niyom'))
                        ->set_width(20),
                ])
                ->set_header_template('<%- $_index + 1 %>')
                ->set_default_value([
                    $card('article', 'Peer-reviewed article', '[Verified publication title]'),
                    $card('preprint', 'Preprint', '[Verified preprint title]'),
                    $card('conference', 'Conference contribution', '[Verified poster or talk title]'),
                    $card('software', 'Software / workflow', '[Verified tool or repository]'),
                ])
                ->set_help_text(__('Each tab is one card, two per row on desktop. Drag tabs to reorder.', 'niyom')),
        ])
        ->set_render_callback(function ($fields) {
            get_template_part('components/Expertise/publications', null, $fields);
        });
});


// =====================================================================
// Expertise › Open resources (latest Insights posts)
// =====================================================================
/**
 * Figma: 07 — Related Articles (312:10239), "Open resources for
 * researchers.". The cards are the same "Insights" component as on the
 * Home page, so this block shows the latest Insights posts with the Home
 * insights markup. The wrapper .ex-resources sets 120px sides, the wider
 * heading and turns the button into the "ALL INSIGHTS ⟶" text link.
 * Markup: components/Home/insights.php · Styles: src/styles/components/home/insights.scss
 * (+ src/styles/components/expertise/_resources.scss)
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_ex_resources', __('Expertise Resources', 'niyom'))
        ->set_description(__('Heading, "All insights" link and the latest Insights posts as cards.', 'niyom'))
        ->set_icon('lightbulb')
        ->set_keywords([__('resources', 'niyom'), __('insights', 'niyom'), __('articles', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            // ---------- Intro ----------
            Field::make('text', 'insights_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Tools & resources')
                ->set_help_text(__('Brackets are added automatically: "( TOOLS & RESOURCES )".', 'niyom')),

            Field::make('textarea', 'insights_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Open resources for researchers.')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('text', 'insights_button_text', __('Link Text (right)', 'niyom'))
                ->set_width(40)
                ->set_default_value('All insights'),
            Field::make('text', 'insights_button_link', __('Link', 'niyom'))
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
            echo '<div class="ex-resources">';
            get_template_part('components/Home/insights', null, $fields);
            echo '</div>';
        });
});


// =====================================================================
// Expertise › CTA
// =====================================================================
/**
 * Figma: 08 — CTA (303:3816). Teal band with network image, label, white
 * heading and two white buttons (both the white "inverse" style in Figma,
 * so the second button can be switched from outline to white).
 * Markup: components/Shared/cta-band.php · Styles: src/styles/components/shared/_cta-band.scss
 * (+ white second button: src/styles/components/expertise/_cta.scss)
 */
add_action('carbon_fields_register_fields', function () {

    Block::make('niyom_ex_cta', __('Expertise CTA', 'niyom'))
        ->set_description(__('Teal call-to-action band: label, heading and up to two buttons.', 'niyom'))
        ->set_icon('megaphone')
        ->set_keywords([__('cta', 'niyom'), __('call to action', 'niyom'), __('collaboration', 'niyom')])
        ->set_category('niyom-expertise', __('Niyom — Expertise', 'niyom'), 'welcome-learn-more')
        ->set_mode('both')
        ->add_fields([
            Field::make('text', 'cta_band_eyebrow', __('Eyebrow Label', 'niyom'))
                ->set_default_value('Collaboration')
                ->set_help_text(__('Brackets are added automatically: "( COLLABORATION )".', 'niyom')),

            Field::make('textarea', 'cta_band_heading', __('Heading', 'niyom'))
                ->set_rows(2)
                ->set_default_value('Interested in working together on a study, tool or training?')
                ->set_help_text(__('Press Enter for a new line. Links, <strong> and <em> are allowed.', 'niyom')),

            Field::make('image', 'cta_band_image', __('Background Image', 'niyom'))
                ->set_value_type('url')
                ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg')
                ->set_help_text(__('Faint pattern under the teal colour. Use about 2000 px wide. Leave empty for plain teal.', 'niyom')),

            // ---------- Primary button (white) ----------
            Field::make('separator', 'cta_band_sep_primary', __('Primary Button (white)', 'niyom')),

            Field::make('text', 'cta_band_primary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('Explore collaboration opportunities'),
            Field::make('text', 'cta_band_primary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/work-with-us/'),
            Field::make('checkbox', 'cta_band_primary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),

            // ---------- Secondary button ----------
            Field::make('separator', 'cta_band_sep_secondary', __('Secondary Button', 'niyom')),

            Field::make('text', 'cta_band_secondary_text', __('Button Text', 'niyom'))
                ->set_width(40)
                ->set_default_value('View services'),
            Field::make('text', 'cta_band_secondary_link', __('Button Link', 'niyom'))
                ->set_width(40)
                ->set_default_value('/services-overview/'),
            Field::make('checkbox', 'cta_band_secondary_new_tab', __('Open in new tab', 'niyom'))
                ->set_width(20),
            Field::make('checkbox', 'cta_band_secondary_white', __('White button (unticked = white outline)', 'niyom'))
                ->set_option_value('yes')
                ->set_default_value(true),
        ])
        ->set_render_callback(function ($fields) {
            if (!empty($fields['cta_band_secondary_white'])) {
                echo '<div class="ex-cta ex-cta--white-buttons">';
                get_template_part('components/Shared/cta-band', null, $fields);
                echo '</div>';
                return;
            }
            get_template_part('components/Shared/cta-band', null, $fields);
        });
});

<?php
/**
 * Home › Events & Partnerships block (Carbon Fields).
 *
 * Figma: Home — Desktop › Events (294:6685), Home — Mobile › Events (439:2100).
 * Markup: components/Home/events.php · Styles: src/styles/components/home/events.scss
 *
 * Left: eyebrow, heading, text, button. Right: a list of event rows, each
 * entered here (there is no Events post type). Empty fields are not shown.
 *
 * @package niyom
 */

defined('ABSPATH') || exit;

use Carbon_Fields\Block;
use Carbon_Fields\Field;

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

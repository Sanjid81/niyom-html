# Shared components

Sections used on more than one page. Each component is markup only: register a
Carbon Fields block in your page's `inc/blocks/<page>-block.php` with **exactly
these field names**, then render it with
`get_template_part('components/Shared/<name>', null, $fields);`.

Styles live in `src/styles/components/shared/_<name>.scss` (already imported via
`shared/_index.scss`). Empty fields are never printed. First used on the
Computational Biosciences page (`inc/blocks/computational-biosciences-block.php`):
copy a block from there and change the defaults, block id, title and category.

| Component | File | Figma | Notes |
|---|---|---|---|
| Intro / overview | `intro.php` | "03 — Introduction" (CB 294:8091, RS 296:8955, SE 294:7342) | Label + H3 statement left, paragraph right |
| Cards | `cards.php` | CB "Deliverables" 296:8492 | Intro + 3-column Mist cards (icon, title, text) |
| Process | `process.php` | CB "Engagement process" 296:8550 (Step Card 281:51) | Intro + white numbered step cards, **no icons** |
| FAQ | `faq.php` | "07 — FAQs" (CB 296:8579, RS 296:9081) | Accordion, JS in `src/scripts/components/shared-faq.js` |
| CTA band | `cta-band.php` | "08 — CTA" (symbol 303:8294) | Teal band, network image, up to 2 white buttons |
| Tag pills (CSS only) | `_tag.scss` | Tag 281:35 | `<ul class="shared-tags"><li class="shared-tag">…</li></ul>` |

Other reusable sections that are **not** in this folder:

- **Hero with buttons** → `components/About/hero.php`. Fields `hero_eyebrow`,
  `hero_title`, `hero_text`, `hero_image`, `hero_image_alt`, plus optional
  buttons `hero_primary_text|_link|_new_tab` (teal) and
  `hero_secondary_text|_link|_new_tab` (outline). On the service pages the hero
  has no bottom padding (the next section brings 140px); set it in the render
  callback: `$fields['hero_flush_bottom'] = true;`. Copy `niyom_cb_hero`.
- **Step cards with 80px icons** (Research Support "Workflow" 303:1257,
  Scientific Engagement "From request to session" 294:7006) →
  `components/Home/how-it-works.php` (fields `steps_eyebrow`, `steps_heading`,
  optional button `steps_button_text|_link|_new_tab`, `steps_items` with
  `number`, `icon`, `title`, `text`). See `niyom_services_how_it_works`.

Complex-field rules: `->set_layout('tabbed-horizontal')`,
`->set_header_template('<%- $_index + 1 %>')`, and every default row needs a
unique `'_id'` and `'_type' => '_'`.

---

## Intro — `components/Shared/intro.php`

| $args key | Field type | Notes |
|---|---|---|
| `intro_eyebrow` | text | "( LABEL )" brackets added automatically |
| `intro_heading` | textarea | H3 statement; Enter = line break |
| `intro_text` | rich_text | Right column, 520px |

```php
Field::make('text', 'intro_eyebrow', __('Eyebrow Label', 'niyom'))
    ->set_default_value('Overview')
    ->set_help_text(__('Brackets are added automatically: "( OVERVIEW )".', 'niyom')),
Field::make('textarea', 'intro_heading', __('Statement', 'niyom'))
    ->set_rows(3)
    ->set_default_value('…'),
Field::make('rich_text', 'intro_text', __('Text (right)', 'niyom'))
    ->set_default_value('…'),
// render: get_template_part('components/Shared/intro', null, $fields);
```

## Cards — `components/Shared/cards.php`

| $args key | Field type | Notes |
|---|---|---|
| `cards_eyebrow` | text | |
| `cards_heading` | textarea | H2 |
| `cards_text` | rich_text | Right of the heading, 460px |
| `cards_items` | complex | rows: `icon` (image, url — SVG is recoloured teal), `title` (textarea), `text` (rich_text) |

```php
Field::make('text', 'cards_eyebrow', __('Eyebrow Label', 'niyom'))->set_default_value('Deliverables'),
Field::make('textarea', 'cards_heading', __('Heading', 'niyom'))->set_rows(2)->set_default_value('…'),
Field::make('rich_text', 'cards_text', __('Text (right)', 'niyom'))->set_default_value('…'),
Field::make('complex', 'cards_items', __('Cards', 'niyom'))
    ->set_layout('tabbed-horizontal')
    ->set_collapsed(false)
    ->setup_labels(['plural_name' => __('Cards', 'niyom'), 'singular_name' => __('Card', 'niyom')])
    ->add_fields([
        Field::make('image', 'icon', __('Icon', 'niyom'))->set_value_type('url'),
        Field::make('textarea', 'title', __('Title', 'niyom'))->set_rows(2),
        Field::make('rich_text', 'text', __('Text', 'niyom')),
    ])
    ->set_header_template('<%- $_index + 1 %>')
    ->set_default_value([
        ['_id' => 'page-card-1', '_type' => '_', 'icon' => NIYOM_URI . '/assets/icons/deliverable-document.svg', 'title' => '…', 'text' => '…'],
    ]),
// render: get_template_part('components/Shared/cards', null, $fields);
```

## Process — `components/Shared/process.php`

| $args key | Field type | Notes |
|---|---|---|
| `process_eyebrow` | text | |
| `process_heading` | textarea | H2 |
| `process_text` | rich_text | Right of the heading, 520px |
| `process_items` | complex | rows: `number` (text, optional — empty = 01, 02 …), `title` (text), `text` (rich_text) |

```php
Field::make('text', 'process_eyebrow', __('Eyebrow Label', 'niyom'))->set_default_value('Engagement process'),
Field::make('textarea', 'process_heading', __('Heading', 'niyom'))->set_rows(2)->set_default_value('…'),
Field::make('rich_text', 'process_text', __('Text (right)', 'niyom'))->set_default_value('…'),
Field::make('complex', 'process_items', __('Steps', 'niyom'))
    ->set_layout('tabbed-horizontal')
    ->set_collapsed(false)
    ->setup_labels(['plural_name' => __('Steps', 'niyom'), 'singular_name' => __('Step', 'niyom')])
    ->add_fields([
        Field::make('text', 'number', __('Number (optional)', 'niyom')),
        Field::make('text', 'title', __('Title', 'niyom')),
        Field::make('rich_text', 'text', __('Text', 'niyom')),
    ])
    ->set_header_template('<%- $_index + 1 %>')
    ->set_default_value([
        ['_id' => 'page-step-1', '_type' => '_', 'number' => '', 'title' => '…', 'text' => '…'],
    ]),
// render: get_template_part('components/Shared/process', null, $fields);
```

## FAQ — `components/Shared/faq.php`

| $args key | Field type | Notes |
|---|---|---|
| `faq_eyebrow` | text | |
| `faq_heading` | textarea | H2, left column 480px |
| `faq_open_first` | checkbox | First question open on load (Figma) |
| `faq_items` | complex | rows: `question` (textarea), `answer` (rich_text). No answer = no + icon |

Behaviour: `src/scripts/components/shared-faq.js` (already in `src/scripts/index.js`)
adds `.is-ready` and toggles `.is-open`; without JS every answer is visible.
Questions open independently; the + turns into × when open.

```php
Field::make('text', 'faq_eyebrow', __('Eyebrow Label', 'niyom'))->set_default_value('FAQs'),
Field::make('textarea', 'faq_heading', __('Heading', 'niyom'))->set_rows(2)->set_default_value('…'),
Field::make('checkbox', 'faq_open_first', __('Open the first question', 'niyom'))
    ->set_option_value('yes')
    ->set_default_value(true),
Field::make('complex', 'faq_items', __('Questions', 'niyom'))
    ->set_layout('tabbed-horizontal')
    ->set_collapsed(false)
    ->setup_labels(['plural_name' => __('Questions', 'niyom'), 'singular_name' => __('Question', 'niyom')])
    ->add_fields([
        Field::make('textarea', 'question', __('Question', 'niyom'))->set_rows(2),
        Field::make('rich_text', 'answer', __('Answer', 'niyom')),
    ])
    ->set_header_template('<%- $_index + 1 %>')
    ->set_default_value([
        ['_id' => 'page-faq-1', '_type' => '_', 'question' => '…', 'answer' => '…'],
    ]),
// render: get_template_part('components/Shared/faq', null, $fields);
```

Tip: collapsed answers in Figma are hidden layers inside each "FAQ Item"
instance (`I<instance>;281:44`). Read them with get_design_context on that id.

## CTA band — `components/Shared/cta-band.php`

| $args key | Field type | Notes |
|---|---|---|
| `cta_band_eyebrow` | text | White 70% |
| `cta_band_heading` | textarea | White H2, 1000px |
| `cta_band_image` | image (url) | Faint pattern under 90% teal. Default `assets/images/home/expertise-bg.jpg` (same Figma network image) |
| `cta_band_primary_text` / `_link` / `_new_tab` | text / text / checkbox | White "inverse" button |
| `cta_band_secondary_text` / `_link` / `_new_tab` | text / text / checkbox | White-outline button (optional) |

Buttons stack in a column at the bottom right (Figma). A button needs text and a link.

```php
Field::make('text', 'cta_band_eyebrow', __('Eyebrow Label', 'niyom'))->set_default_value('Start here'),
Field::make('textarea', 'cta_band_heading', __('Heading', 'niyom'))->set_rows(2)->set_default_value('…'),
Field::make('image', 'cta_band_image', __('Background Image', 'niyom'))
    ->set_value_type('url')
    ->set_default_value(NIYOM_URI . '/assets/images/home/expertise-bg.jpg'),
Field::make('separator', 'cta_band_sep_primary', __('Primary Button (white)', 'niyom')),
Field::make('text', 'cta_band_primary_text', __('Button Text', 'niyom'))->set_width(40)->set_default_value('…'),
Field::make('text', 'cta_band_primary_link', __('Button Link', 'niyom'))->set_width(40)->set_default_value('/work-with-us/'),
Field::make('checkbox', 'cta_band_primary_new_tab', __('Open in new tab', 'niyom'))->set_width(20),
Field::make('separator', 'cta_band_sep_secondary', __('Secondary Button (outline)', 'niyom')),
Field::make('text', 'cta_band_secondary_text', __('Button Text', 'niyom'))->set_width(40),
Field::make('text', 'cta_band_secondary_link', __('Button Link', 'niyom'))->set_width(40),
Field::make('checkbox', 'cta_band_secondary_new_tab', __('Open in new tab', 'niyom'))->set_width(20),
// render: get_template_part('components/Shared/cta-band', null, $fields);
```

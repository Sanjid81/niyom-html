# Niyom WordPress Theme

## Setup

```bash
composer install   # Carbon Fields -> vendor/
npm install        # build tools -> node_modules/
npm run build      # production build -> dist/
npm run dev        # watch mode while developing
```

Needs PHP 8.0+ and Node 20+.

## Structure

```
niyom/
├── style.css              Theme header only
├── functions.php          Loads the files in inc/
├── header.php / footer.php
├── index.php              Blog, archives, search, single posts
├── page.php               Pages (block content, full width)
├── 404.php
├── admin-editor.css       Block editor styles
├── inc/
│   ├── setup.php          Theme supports, menus, niyom_option() helper
│   ├── enqueue.php        dist/app.css + dist/app.js
│   ├── carbon-fields.php  Boots Carbon Fields from vendor/
│   ├── theme-options.php  Header Options, Footer Options
│   └── blocks.php         Carbon Fields blocks (Banner)
├── components/
│   └── banner.php         Block markup, fields in $args
└── src/
    ├── scripts/
    │   ├── index.js       Webpack entry
    │   └── components/header.js
    └── styles/
        ├── index.scss     Imports everything
        ├── config/        variables, breakpoints, globals, typography
        ├── layout/        header, footer
        └── components/    button, banner
```

## Add a new block

1. Register it in `inc/blocks.php` with `Block::make()`.
2. Render `get_template_part('components/<name>', null, $fields)`.
3. Write markup in `components/<name>.php` (read fields from `$args`).
4. Add `src/styles/components/_<name>.scss` and import it in `src/styles/index.scss`.

## Admin setup

- Appearance → Menus: assign **Main Menu** and **Footer Menu**.
- Theme Options → Header Options / Footer Options: logo, button, contact, social links, copyright (`{year}` = current year).
- Brand colors and fonts: `src/styles/config/_variables.scss`.

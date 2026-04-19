# Auto meta descriptions for WordPress — no SEO plugin needed

Generate `<meta name="description">` tags automatically from native WordPress fields. No SEO plugin required, no configuration, no database tables.

WordPress already has the fields — tagline, excerpt, term description, biographical info — it just doesn't output them as meta descriptions. These snippets fix that.

## How it works

| Page type | WordPress field used | Where to edit it |
|---|---|---|
| Front page | Tagline | Settings → General → Tagline |
| Blog page (when set to a separate page) | Excerpt of that page, falls back to the tagline | Pages → [Blog page] → Excerpt |
| Posts and pages | Excerpt. If empty, content is trimmed to 155 characters (cut at the last space, Gutenberg blocks and shortcodes stripped first) | Post sidebar → Excerpt |
| Category / Tag / Custom taxonomy archives | Term description | Categories → Edit → Description |
| Author archives | Biographical info | Users → Your Profile → Biographical Info |

If a field is empty, no meta description is output for that page — no broken or empty tags.

### Example output

For a post with the excerpt "Step-by-step guide to configure HTTP caching headers in WordPress without installing any cache plugin.", the HTML `<head>` will include:

```html
<title>How to set HTTP cache headers in WordPress</title>
<meta name="description" content="Step-by-step guide to configure HTTP caching headers in WordPress without installing any cache plugin.">
```

The meta tag is placed right below the `<title>`, where SEO crawlers expect to find it.

## Priority and placement

All snippets hook into `wp_head` with priority `1`. That places the `<meta name="description">` tag right after the `<title>`, next to the other SEO-relevant tags, just like proper SEO plugins do it. With the default priority of `10`, the tag ends up buried deep in the `<head>` between scripts and stylesheets. It still works, but it's cleaner up top.

## Files in this repository

### All-in-one mu-plugin

| File | Description |
|---|---|
| `ayudawp-auto-meta-descriptions.php` | All 4 meta description sources in a single file. Drop it in `wp-content/mu-plugins/` and it works immediately. |

### Individual snippets

Use these if you only need meta descriptions for specific page types. Each file is self-contained.

| File | Page type | Source field |
|---|---|---|
| `meta-description-homepage-tagline.php` | Front page and blog page | Tagline, or blog page excerpt |
| `meta-description-posts-excerpt.php` | Posts and pages | Manual excerpt, or first 155 characters of content |
| `meta-description-taxonomy-description.php` | Category / Tag / Taxonomy archives | Term description field |
| `meta-description-author-bio.php` | Author archives | Biographical info from user profile |

## Installation

### Option 1: mu-plugin (recommended)

1. Create the folder `wp-content/mu-plugins/` if it doesn't exist
2. Copy `ayudawp-auto-meta-descriptions.php` into it
3. Done — mu-plugins load automatically, no activation needed

### Option 2: Individual snippets as mu-plugins

Drop any of the individual snippet files directly into `wp-content/mu-plugins/`. They load automatically too. Use this if you only want one or two of them.

### Option 3: functions.php

Copy the code from any individual snippet file into your **child theme's** `functions.php`.

## Verify it works

1. Visit any page on your site
2. View source (`Ctrl+U` or `Cmd+Option+U`)
3. Search for `<meta name="description"`
4. The tag should appear right after the `<title>` tag, with content matching the corresponding WordPress field

Test on each page type: front page, a post with excerpt, a category with description, and an author archive.

## Important notes

**Duplicate meta descriptions:** If you have an SEO plugin active (Yoast, Rank Math, SEOPress, etc.) you may get duplicate `<meta name="description">` tags. Either deactivate the SEO plugin's meta description feature or don't use these snippets alongside it.

**Empty fields = no output:** If your tagline still says "Just another WordPress site", that's your meta description. If a category has no description, no tag is generated. The snippets use whatever you put in the fields — fill them in.

**Excerpts on pages:** WordPress only enables excerpts on posts by default. To enable them on pages too, add this line to your `functions.php` or as a mu-plugin:

```php
add_post_type_support( 'page', 'excerpt' );
```

**Content trimming for posts without excerpt:** When a post has no manual excerpt, the snippet builds one from the content:

1. Removes Gutenberg blocks not meant for excerpts (galleries, embeds, covers, etc.) via `excerpt_remove_blocks()`
2. Strips shortcodes via `strip_shortcodes()` so `[gallery]` markup doesn't leak into the meta
3. Removes remaining HTML with `wp_strip_all_tags()` and normalizes whitespace
4. Trims to 155 characters, cutting at the last space to avoid broken words, and appends `...`

Google typically displays 150-160 characters in search results, so 155 is a safe default. Change the `155` value in the snippet if you want shorter or longer descriptions.

**Front page vs blog page:** If you use a static front page and assign a separate page as your blog (Settings → Reading), the homepage snippet handles both. The static front page uses the site tagline. The blog page uses its own excerpt, falling back to the tagline if empty.

## Requirements

- WordPress 5.2 or higher (for Gutenberg block-aware content trimming)
- PHP 7.4 or higher

The snippets guard calls to Gutenberg-era functions with `function_exists()`, so they won't break on older WordPress versions — they'll just skip the block cleanup step.

## Why not just use an SEO plugin?

You can. But if the only thing you need from your SEO plugin is meta descriptions, you're loading thousands of lines of code, database queries, and admin UI for a feature that takes a handful of lines of PHP.

These snippets do one thing, do it well, and add zero overhead to your site.

For more WordPress native SEO and GEO techniques, see the [complete reference guide](https://github.com/fernandotellado/wordpress-native-seo-geo).

## License

GPL-2.0-or-later

## Support

Need help or have suggestions?

- [Official website](https://servicios.ayudawp.com)
- [YouTube channel](https://www.youtube.com/AyudaWordPressES)
- [Documentation and tutorials](https://ayudawp.com)

## About AyudaWP.com

We are specialists in WordPress security, SEO, AI and performance optimization plugins. We create tools that solve real problems for WordPress site owners while maintaining the highest coding standards and accessibility requirements.

## Author

[Fernando Tellado](https://ayudawp.com) — WordPress specialist since 2005.

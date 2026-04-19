# Auto meta descriptions for WordPress — no SEO plugin needed

Generate `<meta name="description">` tags automatically from native WordPress fields. No SEO plugin required, no configuration, no database tables.

WordPress already has the fields — tagline, excerpt, term description, biographical info — it just doesn't output them as meta descriptions. These snippets fix that.

## How it works

| Page type | WordPress field used | Where to edit it |
|---|---|---|
| Homepage | Tagline | Settings → General → Tagline |
| Posts and pages | Excerpt (or trimmed content if empty) | Post sidebar → Excerpt |
| Category / Tag / Custom taxonomy archives | Term description | Categories → Edit → Description |
| Author archives | Biographical info | Users → Your Profile → Biographical Info |

If a field is empty, no meta description is output for that page — no broken or empty tags.

## Files in this repository

### All-in-one mu-plugin

| File | Description |
|---|---|
| `ayudawp-auto-meta-descriptions.php` | All 4 meta description sources in a single file. Drop it in `wp-content/mu-plugins/` and it works immediately. |

### Individual snippets

Use these if you only need meta descriptions for specific page types. Each file is self-contained.

| File | Page type | Source field |
|---|---|---|
| `meta-description-homepage-tagline.php` | Homepage | Tagline from Settings → General |
| `meta-description-posts-excerpt.php` | Posts and pages | Manual excerpt, or first 25 words of content |
| `meta-description-taxonomy-description.php` | Category / Tag / Taxonomy archives | Term description field |
| `meta-description-author-bio.php` | Author archives | Biographical info from user profile |

## Installation

### Option 1: mu-plugin (recommended)

1. Create the folder `wp-content/mu-plugins/` if it doesn't exist
2. Copy `ayudawp-auto-meta-descriptions.php` into it
3. Done — mu-plugins load automatically, no activation needed

### Option 2: Individual snippets

Copy the code from any individual snippet file into your **child theme's** `functions.php`.

### Option 3: functions.php (single snippet)

If you only need one type, copy the code from the corresponding individual file into your child theme's `functions.php`.

## Verify it works

1. Visit any page on your site
2. View source (`Ctrl+U` or `Cmd+Option+U`)
3. Search for `<meta name="description"`
4. The content should match the corresponding WordPress field

Test on each page type: homepage, a post with excerpt, a category with description, and an author archive.

## Important notes

**Duplicate meta descriptions:** If you have an SEO plugin active (Yoast, Rank Math, SEOPress, etc.) you may get duplicate `<meta name="description">` tags. Either deactivate the SEO plugin's meta description feature or don't use these snippets alongside it.

**Empty fields = no output:** If your tagline still says "Just another WordPress site", that's your meta description. If a category has no description, no tag is generated. The snippets use whatever you put in the fields — fill them in.

**Excerpts on pages:** WordPress only enables excerpts on posts by default. To enable them on pages too, add this line to your `functions.php` or mu-plugin:

```php
add_post_type_support( 'page', 'excerpt' );
```

**Content trimming:** For posts without a manual excerpt, the snippet trims the first 25 words of the content. You can change this number by editing the `25` in `wp_trim_words()`. Google typically displays 150-160 characters, so 25 words is a reasonable default.

## Requirements

- WordPress 4.4 or higher
- PHP 7.4 or higher

## Why not just use an SEO plugin?

You can. But if the only thing you need from your SEO plugin is meta descriptions, you're loading thousands of lines of code, database queries, and admin UI for a feature that takes 30 lines of PHP.

These snippets do one thing, do it well, and add zero overhead to your site.

For more WordPress native SEO and GEO techniques, see the [complete reference guide](https://github.com/fernandotellado/wordpress-native-seo-geo).

## License

GPL-2.0-or-later

## Author

[Fernando Tellado](https://ayudawp.com) — WordPress specialist since 2005.

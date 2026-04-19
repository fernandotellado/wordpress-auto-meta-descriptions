<?php
/**
 * Plugin Name: Auto Meta Descriptions
 * Description: Generates meta descriptions automatically from native WordPress fields. No SEO plugin needed. Homepage uses the tagline, posts and pages use the excerpt, taxonomy archives use the term description, and author archives use the biographical info.
 * Version: 1.0.0
 * Author: Fernando Tellado
 * Author URI: https://ayudawp.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * INSTALLATION:
 * Place this file in wp-content/mu-plugins/ (create the folder if needed).
 * It loads automatically — no activation required.
 *
 * HOW IT WORKS:
 * This plugin checks what type of page the visitor is on and pulls
 * the meta description from the corresponding native WordPress field:
 *
 * - Front page → Tagline (Settings → General → Tagline)
 * - Blog page (when set to a separate page) → That page's excerpt,
 *   falling back to the tagline
 * - Posts/Pages → Excerpt (post sidebar → Excerpt field)
 *   If no excerpt exists, the content is trimmed to 155 characters,
 *   cutting cleanly at the last space. Gutenberg blocks not meant
 *   for excerpts and shortcodes are removed first.
 * - Category/Tag/Custom taxonomy archives → Term description
 *   (Categories → Edit → Description field)
 * - Author archives → Biographical info
 *   (Users → Your Profile → Biographical Info)
 *
 * Priority 1 on wp_head places the meta tag right after <title>,
 * next to the other SEO-relevant tags.
 *
 * IMPORTANT:
 * If you have an SEO plugin active (Yoast, Rank Math, SEOPress, etc.)
 * you may get duplicate meta description tags. Deactivate the SEO
 * plugin's meta description feature or remove this mu-plugin.
 *
 * CHECK IT WORKS:
 * Visit any page on your site → View Source (Ctrl+U) →
 * search for <meta name="description"
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', 'ayudawp_auto_meta_description', 1 );

/**
 * Output a meta description tag based on the current page type.
 */
function ayudawp_auto_meta_description() {
	$description = '';

	if ( is_front_page() ) {
		// Front page: tagline from Settings → General
		$description = get_bloginfo( 'description' );

	} elseif ( is_home() ) {
		// Blog page assigned to a separate page: excerpt of that page
		$blog_page_id = (int) get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$description = get_post_field( 'post_excerpt', $blog_page_id );
		}
		// Fallback to the tagline if the blog page has no excerpt
		if ( empty( $description ) ) {
			$description = get_bloginfo( 'description' );
		}

	} elseif ( is_singular() ) {
		// Posts, pages and custom post types
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$description = $post->post_excerpt;

			// Fallback: trim the content to 155 characters at the last space
			if ( empty( $description ) ) {
				$content = $post->post_content;

				if ( function_exists( 'excerpt_remove_blocks' ) ) {
					$content = excerpt_remove_blocks( $content );
				}

				$content = strip_shortcodes( $content );
				$content = wp_strip_all_tags( $content );
				$content = trim( preg_replace( '/\s+/', ' ', $content ) );

				if ( mb_strlen( $content ) > 155 ) {
					$cut        = mb_substr( $content, 0, 155 );
					$last_space = mb_strrpos( $cut, ' ' );
					if ( false !== $last_space ) {
						$cut = mb_substr( $cut, 0, $last_space );
					}
					$description = $cut . '...';
				} else {
					$description = $content;
				}
			}
		}

	} elseif ( is_category() || is_tag() || is_tax() ) {
		// Taxonomy archives: term description field
		$description = term_description();

	} elseif ( is_author() ) {
		// Author archives: biographical info from user profile
		$description = get_the_author_meta( 'description' );
	}

	// Clean up and output
	$description = trim( wp_strip_all_tags( $description ) );
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}

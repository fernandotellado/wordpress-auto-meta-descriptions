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
 * - Homepage → Tagline (Settings → General → Tagline)
 * - Posts/Pages → Excerpt (post sidebar → Excerpt field)
 *   If no excerpt exists, it trims the first 25 words of the content.
 * - Category/Tag/Custom taxonomy archives → Term description
 *   (Categories → Edit → Description field)
 * - Author archives → Biographical info
 *   (Users → Your Profile → Biographical Info)
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

add_action( 'wp_head', 'ayudawp_auto_meta_description' );

/**
 * Output a meta description tag based on the current page type.
 */
function ayudawp_auto_meta_description() {
	$description = '';

	if ( is_front_page() || is_home() ) {
		// Homepage: tagline from Settings → General
		$description = get_bloginfo( 'description' );

	} elseif ( is_singular() ) {
		// Posts and pages: manual excerpt, or trimmed content
		global $post;
		$description = $post->post_excerpt;
		if ( empty( $description ) ) {
			$description = wp_trim_words(
				wp_strip_all_tags( $post->post_content ),
				25,
				'...'
			);
		}

	} elseif ( is_category() || is_tag() || is_tax() ) {
		// Taxonomy archives: term description field
		$description = term_description();

	} elseif ( is_author() ) {
		// Author archives: biographical info from user profile
		$description = get_the_author_meta( 'description' );
	}

	// Clean up and escape
	$description = esc_attr( wp_strip_all_tags( trim( $description ) ) );

	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . $description . '">' . "\n";
	}
}

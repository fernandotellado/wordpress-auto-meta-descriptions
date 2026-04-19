<?php
/**
 * Meta description for the homepage from the tagline
 *
 * Uses the tagline configured in Settings → General as the
 * meta description for the homepage.
 *
 * If the site has a static front page and a separate page
 * assigned as the blog (Settings → Reading), that page's
 * excerpt is used instead, falling back to the tagline.
 *
 * If your tagline still says "Just another WordPress site",
 * that's exactly what Google will show as your site description.
 * Change it first in Settings → General → Tagline.
 *
 * Priority 1 on wp_head places the meta right after <title>.
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	$description = '';

	if ( is_front_page() ) {
		// Static or default front page: tagline from Settings → General
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
	} else {
		return;
	}

	$description = trim( wp_strip_all_tags( $description ) );
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}, 1 );

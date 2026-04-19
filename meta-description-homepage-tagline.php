<?php
/**
 * Meta description for the homepage from the tagline
 *
 * Uses the tagline configured in Settings → General as the
 * meta description for the homepage (front page and blog page).
 *
 * If your tagline still says "Just another WordPress site",
 * that's exactly what Google will show as your site description.
 * Change it first in Settings → General → Tagline.
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	if ( is_front_page() || is_home() ) {
		$description = esc_attr( wp_strip_all_tags( trim( get_bloginfo( 'description' ) ) ) );
		if ( ! empty( $description ) ) {
			echo '<meta name="description" content="' . $description . '">' . "\n";
		}
	}
});

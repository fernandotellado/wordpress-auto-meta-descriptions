<?php
/**
 * Meta description for author archives from the biographical info
 *
 * Uses the Biographical Info field from the user profile
 * (Users → Your Profile → Biographical Info) as the meta
 * description for author archive pages.
 *
 * Filling in this field with real credentials — experience,
 * qualifications, areas of expertise — doubles as an E-E-A-T
 * signal and a meta description source.
 *
 * If the author has no bio, no meta tag is output.
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	if ( is_author() ) {
		$description = esc_attr( wp_strip_all_tags( trim( get_the_author_meta( 'description' ) ) ) );
		if ( ! empty( $description ) ) {
			echo '<meta name="description" content="' . $description . '">' . "\n";
		}
	}
});

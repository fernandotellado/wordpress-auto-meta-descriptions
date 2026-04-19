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
 * Priority 1 on wp_head places the meta right after <title>.
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	if ( ! is_author() ) {
		return;
	}

	$description = trim( wp_strip_all_tags( get_the_author_meta( 'description' ) ) );
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}, 1 );

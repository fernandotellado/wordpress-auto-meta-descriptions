<?php
/**
 * Meta description for taxonomy archives from the term description
 *
 * Uses the description field of categories, tags, and custom
 * taxonomies as meta description for their archive pages.
 *
 * This field is in Categories → Edit → Description (or the
 * equivalent screen for tags and custom taxonomies). Almost
 * nobody fills it in, but it's indexable content that search
 * engines and AI crawlers can use.
 *
 * If the term has no description, no meta tag is output.
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	if ( is_category() || is_tag() || is_tax() ) {
		$description = esc_attr( wp_strip_all_tags( trim( term_description() ) ) );
		if ( ! empty( $description ) ) {
			echo '<meta name="description" content="' . $description . '">' . "\n";
		}
	}
});

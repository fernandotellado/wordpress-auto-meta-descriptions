<?php
/**
 * Meta description for posts and pages from the excerpt
 *
 * Uses the manual excerpt as meta description. If no excerpt
 * exists, it trims the first 25 words of the post content.
 *
 * The excerpt field is in the post sidebar (block editor) or
 * below the content area (classic editor). If you leave it
 * empty, WordPress cuts the content automatically — and that
 * trimmed text is what ends up in search results.
 *
 * Tip: To enable excerpts on pages too, add this line:
 * add_post_type_support( 'page', 'excerpt' );
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	if ( is_singular() ) {
		global $post;
		$description = $post->post_excerpt;
		if ( empty( $description ) ) {
			$description = wp_trim_words(
				wp_strip_all_tags( $post->post_content ),
				25,
				'...'
			);
		}
		$description = esc_attr( wp_strip_all_tags( trim( $description ) ) );
		if ( ! empty( $description ) ) {
			echo '<meta name="description" content="' . $description . '">' . "\n";
		}
	}
});

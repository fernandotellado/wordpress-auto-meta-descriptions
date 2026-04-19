<?php
/**
 * Meta description for posts and pages from the excerpt
 *
 * Uses the manual excerpt as meta description. If no excerpt
 * exists, it trims the post content to 155 characters, cutting
 * cleanly at the last space to avoid broken words.
 *
 * Before trimming, it removes Gutenberg blocks that don't render
 * as text (embeds, galleries, cover blocks) and shortcodes, so
 * their markup doesn't end up in the meta tag.
 *
 * The excerpt field is in the post sidebar (block editor) or
 * below the content area (classic editor). If you leave it
 * empty, WordPress will use the trimmed content.
 *
 * Tip: To enable excerpts on pages too, add this line:
 * add_post_type_support( 'page', 'excerpt' );
 *
 * Priority 1 on wp_head places the meta right after <title>.
 *
 * Usage: Add to functions.php or as a mu-plugin.
 */
add_action( 'wp_head', function() {
	if ( ! is_singular() ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	// First try the manual excerpt
	$description = $post->post_excerpt;

	// Fallback: trim the content to 155 characters at the last space
	if ( empty( $description ) ) {
		$content = $post->post_content;

		// Remove Gutenberg blocks not meant for excerpts (when available)
		if ( function_exists( 'excerpt_remove_blocks' ) ) {
			$content = excerpt_remove_blocks( $content );
		}

		// Remove shortcodes so they don't leak into the description
		$content = strip_shortcodes( $content );

		// Strip all HTML and normalize whitespace
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

	$description = trim( wp_strip_all_tags( $description ) );
	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}, 1 );

<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Education_Zone
 */

if ( ! function_exists( 'education_zone_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function education_zone_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date( 'j M,Y' ) )
	);

	echo '<span class="posted-on"><i class="fa fa-calendar-o"></i><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' .  $time_string . '</a></span>
	<span class="byline"><i class="fa fa-user"></i><span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span></span>'; // WPCS: XSS OK.
   

	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comment"><i class="fa fa-comment-o"></i>';
		comments_popup_link( esc_html__( 'Leave a comment', 'education-zone' ), esc_html__( '1 Comment', 'education-zone' ), esc_html__( '% Comments', 'education-zone' ) );
		echo '</span>';
	}
}
endif;

if ( ! function_exists( 'education_zone_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function education_zone_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'education-zone' ) );
		if ( $categories_list && education_zone_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'education-zone' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'education-zone' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'education-zone' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'education-zone' ), esc_html__( '1 Comment', 'education-zone' ), esc_html__( '% Comments', 'education-zone' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'education-zone' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function education_zone_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'education_zone_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'education_zone_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so education_zone_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so education_zone_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in education_zone_categorized_blog.
 */
function education_zone_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'education_zone_categories' );
}
add_action( 'edit_category', 'education_zone_category_transient_flusher' );
add_action( 'save_post',     'education_zone_category_transient_flusher' );

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Fire the wp_body_open action.
	 *
	 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
	 *
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 *
		 */
		do_action( 'wp_body_open' );
	}
endif;
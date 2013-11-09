<?php

// Some constants
define(TEMPLATEDIR, get_template_directory_uri());

// Register custom navigation walker for bootstrap meny
require_once('wp_bootstrap_navwalker.php');

/**
 * My Theme setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * this theme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 */
function mytheme_setup()
{
  load_theme_textdomain( 'mytheme', get_template_directory() . '/languages' );
  
  // This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );
  
  // This theme uses wp_nav_menu() in one location.
  register_nav_menu('primary', __('Primary navigation', 'mytheme'));
}
add_action('after_setup_theme', 'mytheme_setup');

function mytheme_scripts_styles()
{
  // Add twitter bootstrap javaScript
  wp_enqueue_script('jquery.bootstrap.min', TEMPLATEDIR . '/js/bootstrap.min.js', array('jquery'), '3.0.0', true);
  
  // Loads twitter bootstrap stylesheheets
  wp_enqueue_style('bootstrap.min', TEMPLATEDIR . '/css/bootstrap.min.css', array(), null);
  wp_enqueue_style('bootstrap-theme.min', TEMPLATEDIR . '/css/bootstrap-theme.min.css', array(), null);
  
  // Loads our main stylesheet.
  wp_enqueue_style('style.css', get_stylesheet_uri());
}
add_action( 'wp_enqueue_scripts', 'mytheme_scripts_styles' );



if ( ! function_exists( 'mytheme_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function mytheme_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'mytheme' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'mytheme' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'mytheme' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'mytheme_entry_meta' ) ) :
/**
 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return void
 */
function mytheme_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . __( 'Sticky', 'mytheme' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		mytheme_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'mytheme' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'mytheme' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links">' . $tag_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'mytheme' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;

if ( ! function_exists( 'mytheme_entry_date' ) ) :
/**
 * Print HTML with date information for current post.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function mytheme_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'mytheme' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'mytheme' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;
?>
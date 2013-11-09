<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    HybridBase
 * @subpackage Functions
 * @version    0.1.0
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/themes/hybrid-base
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'celebrate_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function celebrate_theme_setup() {

	global $content_width;

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Register menus. */
	add_theme_support( 
		'hybrid-core-menus', 
		array( 'primary' ) 
	);
	
	/* Register sidebars. */
	add_theme_support( 
		'hybrid-core-sidebars', 
		array( 'primary' ) 
	);

	/* Load scripts. */
	add_theme_support( 
		'hybrid-core-scripts', 
		array( 'comment-reply' ) 
	);

	/* Load styles. */
	add_theme_support( 
		'hybrid-core-styles', 
		array( '25px', 'gallery', 'parent', 'style' ) 
	);

	add_editor_style();
	
	/* Load widgets. 
	add_theme_support( 'hybrid-core-widgets' );
	*/
	
	/* Load shortcodes. */
	add_theme_support( 'hybrid-core-shortcodes' );
	
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	
	add_action( "{$prefix}_footer", 'celebrate_footer_insert' );

	/* Enable custom template hierarchy.  */
	add_theme_support( 'hybrid-core-template-hierarchy' );
	
	/* Enable theme layouts (need to add stylesheet support). */
	add_theme_support( 
		'theme-layouts', 
		array( '2c-l', '1c' ), 
		array( 'default' => '2c-l', 'customizer' => true ) 
	);

	/* Allow per-post stylesheets. 
	add_theme_support( 'post-stylesheets' );
	*/
	
	/* Support pagination instead of prev/next links. */
	add_theme_support( 'loop-pagination' );

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );

	/* Support media grabber */
	add_theme_support( 'hybrid-core-media-grabber');

	/* Use breadcrumbs. */
	//add_theme_support( 'breadcrumb-trail' );

	/* Nicer [gallery] shortcode implementation. 
	add_theme_support( 'cleaner-gallery' );
	*/

	/* Better captions for themes to style. 
	add_theme_support( 'cleaner-caption' );
	*/

	/* Automatically add feed links to <head>. */
	add_theme_support( 'automatic-feed-links' );

	/* Post formats. */
	add_theme_support( 
		'post-formats', 
		array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) 
	);
	
	/* Add support for a custom header image. */
	celebrate_custom_header_setup();
		
	/* Custom background. */
	add_theme_support( 
		'custom-background',
		array( 'default-color' => 'F2F2F2' )
	);
	
	/* Handle content width for embeds and images. */

	/* Content width. */
	if ( empty( $content_width ) && !is_active_sidebar( 'primary' ) )
		$content_width = 1100;
	elseif ( empty( $content_width ) )
		$content_width = 750;

	/* Enqueue scripts (and related stylesheets) */
	add_action( 'wp_enqueue_scripts', 'celebrate_scripts' );
	
	/* Additional and conditional styles */
	add_action( 'wp_head', 'celebrate_styles' );
	
	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'celebrate_disable_sidebars' );
	add_action( 'template_redirect', 'celebrate_one_column' );
	add_filter( 'dynamic_sidebar_params','celebrate_widget_classes');
	
	/* custom excerpt */
	add_filter( 'excerpt_length', 'celebrate_excerpt_length', 999 );
	add_filter('excerpt_more', 'celebrate_excerpt_more');
	
}

/* === HYBRID CORE 1.6 CHANGES. === 
 *
 * The following changes are slated for Hybrid Core version 1.6 to make it easier for 
 * theme developers to build awesome HTML5 themes. The code will be removed once 1.6 
 * is released.
 */

	/**
	 * Content template.  This is an early version of what a content template function will look like
	 * in future versions of Hybrid Core.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	function celebrate_get_content_template() {

		_deprecated_function( __FUNCTION__, '0.2.7', 'hybrid_get_content_template' ); // change version number for your theme

		return hybrid_get_content_template();
	}



/* End Hybrid Core 1.6 section. */

/* misc */

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function celebrate_one_column() {

	if ( !is_active_sidebar( 'primary' ) )
		add_filter( 'theme_mod_theme_layout', 'celebrate_theme_layout_one_column' );

	elseif ( is_attachment() && wp_attachment_is_image() && 'default' == get_post_layout( get_queried_object_id() ) )
		add_filter( 'theme_mod_theme_layout', 'celebrate_theme_layout_one_column' );

}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since  0.1.0
 * @param  string $layout The layout of the current page.
 * @return string
 */
function celebrate_theme_layout_one_column( $layout ) {
	return '1c';
}


/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since  0.1.0
 * @param  array $sidebars_widgets A multidimensional array of sidebars and widgets.
 * @return array $sidebars_widgets
 */

function celebrate_disable_sidebars( $sidebars_widgets ) {
	global $wp_customize;

	$customize = ( is_object( $wp_customize ) && $wp_customize->is_preview() ) ? true : false;

	if ( !is_admin() && !$customize && '1c' == get_theme_mod( 'theme_layout' ) )
		$sidebars_widgets['primary'] = false;

	return $sidebars_widgets;
}

/**
 * Displays the footer insert from the theme settings page.
 *
 * @since 0.1.0
 */
function celebrate_footer_insert() {
	echo '<div class="footer-content footer-insert">';
	hybrid_footer_content();
	echo '</div>';
}

/**
 * Sets the post excerpt length to 20 words.
 *
 * @since 0.1.0
 */
function celebrate_excerpt_length( $length ) {
	return 20;
}

/**
 * Remove [] from excerpt
 *
 * @since 0.1.0
 */
function celebrate_excerpt_more( $more ) {
	return '... <a href="'.get_permalink().'">read more</a>';
}


/**
 * Add "first" and "last" CSS classes to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 * 
 * @since 0.1.0.
 */
function celebrate_widget_classes($params) {

	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	

	if(!$my_widget_num) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}
	$oddeven = $my_widget_num[$this_id] % 2 ? 'even' : 'odd';

	$class = 'class="widget-' . $my_widget_num[$this_id] . ' ' . $oddeven . ' '; // Add a widget number class for additional styling options

	if($my_widget_num[$this_id] == 1) { // If this is the first widget
		$class .= 'widget-first ';
	} elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
		$class .= 'widget-last ';
	}

	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

	return $params;

}

/**
 * Enqueue scripts and styles for the theme used sitewide.
 *
 * @since 0.1.0.
 */

function celebrate_scripts() {
	
	wp_enqueue_script('masonry', get_template_directory_uri() . '/js/jquery.masonry.min.js', array( 'jquery' ));
	wp_enqueue_script('celebrateScript', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), '20130115', true);
	
	wp_enqueue_style('sourceSans', 'http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200');
	wp_enqueue_style('montserrat', 'http://fonts.googleapis.com/css?family=Montserrat:400,700');
	wp_enqueue_style('ptSans', 'http://fonts.googleapis.com/css?family=PT+Sans:400,700');
}

/**
 * Insert conditional styles for the theme used sitewide.
 *
 * @since 0.1.0.
 */
function celebrate_styles() {
?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php 
}

/**
 * Implement the Custom Header feature.
 */
require( get_template_directory() . '/inc/custom-header.php' );

?>
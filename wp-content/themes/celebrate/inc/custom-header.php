<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Celebrate
 */

/**
 * Setup the WordPress core custom header feature.
 *
 *
 * @uses celebrate_header_style()
 * @uses celebrate_admin_header_style()
 * @uses celebrate_admin_header_image()
 *
 * @package Celebrate
 */
function celebrate_custom_header_setup() {
	$args = array(
		'default-image'          => get_template_directory_uri() . "/images/header.jpg",
		'default-text-color'     => '490D22',		
		'width'                  => 1920,
		'flex-width'            => true,
		'height'                 => 550,
		'flex-height'            => true,
		'wp-head-callback'       => 'celebrate_header_style',
		'admin-head-callback'    => 'celebrate_admin_header_style',
		'admin-preview-callback' => 'celebrate_admin_header_image',
	);

	$args = apply_filters( 'celebrate_custom_header_args', $args );
	
	add_theme_support( 'custom-header', $args );	
}


/**
 * Shiv for celebrate_custom_header().
 *
 * celebrate_custom_header() was introduced to WordPress
 * in version 3.4. To provide backward compatibility
 * with previous versions, we will define our own version
 * of this function.
 *
 * @todo Remove this function when WordPress 3.6 is released.
 * @return stdClass All properties represent attributes of the curent header image.
 *
 * @package Celebrate
 */

if ( ! function_exists( 'celebrate_custom_header' ) ) {
	function celebrate_custom_header() {
		return (object) array(
			'url'           => get_header_image(),
			'thumbnail_url' => get_header_image(),
			'width'         => HEADER_IMAGE_WIDTH,
			'height'        => HEADER_IMAGE_HEIGHT,
		);
	}
}

if ( ! function_exists( 'celebrate_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see celebrate_custom_header_setup().
 */
function celebrate_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo $header_text_color; ?>;
		}		
	<?php endif; ?>
	</style>
	<?php
}
endif; // celebrate_header_style

if ( ! function_exists( 'celebrate_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see celebrate_custom_header_setup().
 */
function celebrate_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
	}
	#headimg h1 {
	}
	#headimg h1 a {
	}
	#desc {
	}
	#headimg img {
	}
	</style>
<?php
}
endif; // celebrate_admin_header_style

if ( ! function_exists( 'celebrate_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see celebrate_custom_header_setup().
 */
function celebrate_admin_header_image() {
	$header_text_color = get_header_textcolor();
?>
	<div id="headimg">
		<?php
		if ( 'blank' == $header_text_color || '' == $header_text_color )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . $header_text_color . ';"';
		?>
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php
}
endif; // celebrate_admin_header_image

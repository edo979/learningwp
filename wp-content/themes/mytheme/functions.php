<?php

// Some constants
define(TEMPLATEDIR, get_template_directory_uri());

// Register custom navigation walker for bootstrap meny
require_once('wp_bootstrap_navwalker.php');

/**
 * My Theme setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 */
function mytheme_setup()
{
  //load_theme_textdomain( 'twentytwelve', get_template_directory() . '/languages' );
  
  // This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );
  
  // This theme uses wp_nav_menu() in one location.
  register_nav_menu('primary', __('Primary navigation', 'mytheme'));
}
add_action('after_setup_theme', 'mytheme_setup');

function myteme_scripts_styles()
{
  // Add twitter bootstrap javaScript
  wp_enqueue_script('jquery.bootstrap.min', TEMPLATEDIR . '/js/bootstrap.min.js', array('jquery'), '3.0.0', true);
  
  // Loads twitter bootstrap stylesheheets
  wp_enqueue_style('bootstrap.min', TEMPLATEDIR . '/css/bootstrap.min.css', array(), null);
  ('bootstrap.min');
  wp_enqueue_style('bootstrap-theme.min', TEMPLATEDIR . '/css/bootstrap-theme.min.css', array(), null);
  ('bootstrap.min');
  
  // Loads our main stylesheet.
  wp_enqueue_style('twentytwelve-style', get_stylesheet_uri());
}
add_action( 'wp_enqueue_scripts', 'myteme_scripts_styles' );

?>
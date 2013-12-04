<?php

/**
 * Front Page Builder
 * 
 * Building your front page easy and with style.
 *
 * @package   esse_Front_Page_Builder
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 *
 * @wordpress-plugin
 * Plugin Name:       Front Page Builder
 * Plugin URI:        http://www.kvalitetnije.com.ba
 * Description:       Easy bulding or edit front page
 * Version:           1.0.0
 * Author:            Edis Selimovic
 * Author URI:        http://www.kvalitetnije.com.ba
 * Text Domain:       esse_FrontPageBuilder
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */
// If this file is called directly, abort.
if (!defined('WPINC'))
{
  die;
}

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */

require_once( plugin_dir_path(__FILE__) . 'public/Front_Page_Builder.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook(__FILE__, array('Front_Page_Builder', 'activate'));
register_deactivation_hook(__FILE__, array('Front_Page_Builder', 'deactivate'));

add_action('plugins_loaded', array('Front_Page_Builder', 'get_instance'));

/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */

/*
 * The code below is intended to to give the lightest footprint possible.
 */
if (is_admin())
{
  require_once( plugin_dir_path(__FILE__) . 'admin/Front_Page_Builder_Admin.php' );
  add_action('plugins_loaded', array('Front_Page_Builder_Admin', 'get_instance'));
}
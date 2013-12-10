<?php

/**
 * Front Page Builder
 *
 * @package   esse_Front_Page_Builder
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 */

/**
 * Plugin class. This class used to work with the
 * public-facing side of the WordPress site.
 *
 * @package esse_Front_Page_Builder
 * @author  Edis Selimovic <prezentabilan@gmail.com>
 */
class Front_Page_Builder
{

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   1.0.0
   *
   * @var     string
   */
  const VERSION = '1.0.0';

  /**
   * Unique identifier for your plugin.
   *
   *
   * The variable name is used as the text domain when internationalizing strings
   * of text. Its value should match the Text Domain file header in the main
   * plugin file.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'esse_FrontPageBuilder';

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct()
  {

    // Load plugin text domain
    add_action('init', array($this, 'load_plugin_textdomain'));

    // Activate plugin when new blog is added
    add_action('wpmu_new_blog', array($this, 'activate_new_site'));

    // Load public-facing style sheet and JavaScript.
    add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

    /* Define custom functionality.
     * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */

    // Create custom post type
    add_action('init', array($this, 'fp_item_create'));

    // Define: custom post type, taxonomy, meta box.
    add_action('init', array($this, 'fp_item_define'));
  }

  /**
   * Return the plugin slug.
   *
   * @since    1.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_plugin_slug()
  {
    return $this->plugin_slug;
  }

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance()
  {

    // If the single instance hasn't been set, set it now.
    if (null == self::$instance)
    {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Fired when the plugin is activated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate($network_wide)
  {

    if (function_exists('is_multisite') && is_multisite())
    {

      if ($network_wide)
      {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ($blog_ids as $blog_id)
        {

          switch_to_blog($blog_id);
          self::single_activate();
        }

        restore_current_blog();
      }
      else
      {
        self::single_activate();
      }
    }
    else
    {
      self::single_activate();
    }
  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public static function deactivate($network_wide)
  {

    if (function_exists('is_multisite') && is_multisite())
    {

      if ($network_wide)
      {

        // Get all blog ids
        $blog_ids = self::get_blog_ids();

        foreach ($blog_ids as $blog_id)
        {

          switch_to_blog($blog_id);
          self::single_deactivate();
        }

        restore_current_blog();
      }
      else
      {
        self::single_deactivate();
      }
    }
    else
    {
      self::single_deactivate();
    }
  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    1.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  public function activate_new_site($blog_id)
  {

    if (1 !== did_action('wpmu_new_blog'))
    {
      return;
    }

    switch_to_blog($blog_id);
    self::single_activate();
    restore_current_blog();
  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    1.0.0
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids()
  {

    global $wpdb;

    // get an array of blog ids
    $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

    return $wpdb->get_col($sql);
  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    1.0.0
   */
  private static function single_activate()
  {
    // @TODO: Define activation functionality here
  }

  /**
   * Fired for each blog when the plugin is deactivated.
   *
   * @since    1.0.0
   */
  private static function single_deactivate()
  {
    // @TODO: Define deactivation functionality here
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain()
  {

    $domain = $this->plugin_slug;
    $locale = apply_filters('plugin_locale', get_locale(), $domain);

    load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
  }

  /**
   * Register and enqueue public-facing style sheet.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {
    wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
  }

  /**
   * Register and enqueues public-facing JavaScript files.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {
    wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.js', __FILE__), array('jquery'), self::VERSION);
  }

  /**
   * Create FP Item custom post type
   * 
   * @since    1.0.0
   */
  public function fp_item_create()
  {
    $labels = array(
        'name'               => __('FP Items', $this->plugin_slug),
        'singular_name'      => __('FP Item', $this->plugin_slug),
        'add_new'            => __('Add New FP Item', $this->plugin_slug),
        'add_new_item'       => __('Add New FP Item', $this->plugin_slug),
        'edit_item'          => __('Edit FP Item', $this->plugin_slug),
        'new_item'           => __('New FP Item', $this->plugin_slug),
        'all_items'          => __('All FP Items', $this->plugin_slug),
        'view_item'          => __('View FP Item', $this->plugin_slug),
        'search_items'       => __('Search FP Item', $this->plugin_slug),
        'not_found'          => __('No FP Item found', $this->plugin_slug),
        'not_found_in_trash' => __('No FP Item found in Trash', $this->plugin_slug),
        'parent_item_colon'  => '',
        'menu_name'          => __('FP Builder', $this->plugin_slug)
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 2,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'taxonomies'         => array('fp_item_type')
    );
    register_post_type('fp_item', $args);
  }

  /**
   * Define taxonomy for fp_item. Is fp item: slide or quote.
   * 
   * @since   1.0.0
   */
  function fp_item_define()
  {
    $labels = array(
        'name'              => __('Type', $this->plugin_slug),
        'singular_name'     => __('Types', $this->plugin_slug),
        'search_items'      => __('Search Types', $this->plugin_slug),
        'all_items'         => __('All Types', $this->plugin_slug),
        'parent_item'       => __('Parent Type', $this->plugin_slug),
        'parent_item_colon' => __('Parent Type:', $this->plugin_slug),
        'edit_item'         => __('Edit Type', $this->plugin_slug),
        'update_item'       => __('Update Type', $this->plugin_slug),
        'add_new_item'      => __('Add New Type', $this->plugin_slug),
        'new_item_name'     => __('New Type Name', $this->plugin_slug),
        'menu_name'         => __('Type', $this->plugin_slug)
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'hierarchical'       => false,
        'rewrite'            => true
    );

    register_taxonomy('fp_item_type', 'fp_item', $args);
  }

  /**
   * Get slides from transient or set slide by default order
   * 
   * @sicne   1.0.0
   */
  function slides_get()
  {
    // Check for cached queries. If none, then execute WP_Query
    if (!( $query = get_transient('esse_fp_builder_query_slides') ))
    {
      return $this->transient_set_data();
    }
    else
    {
      // Make new query and save it to transient data
      //delete_transient('esse_fp_builder_query_slides');
      return $query;
    }
  }

  /**
   * Query database with valid arguments, returning slides order if set or
   * default order if slidesOrder not set.
   * 
   * @return  misc \WP_Query
   * 
   * @since   1.0.0
   */
  private function slides_query()
  {
    // loop arguments
    $args = array(
        'post_type' => 'fp_item',
        'tax_query' => array(
            array(
                'taxonomy' => 'fp_item_type',
                'field'    => 'slug',
                'terms'    => 'slide'
            )
        )
    );

    // get slides order
    $slidesOrder = get_option('fp_builder_slide_order');

    if ($slidesOrder)
    {
      // if slides order set, show slides in order
      $args['post__in'] = $slidesOrder;
      $args['orderby'] = 'post__in'; // @since 3.5
    }

    return new WP_Query($args);
  }

  /**
   * Set or update transient data. First call query_slides to get data then
   * save data in transient for cache and bether performance.
   * 
   * @return misc \WP Query
   * 
   * @since   1.0.0
   */
  private function transient_set_data()
  {
    $query = $this->slides_query();

    // Set transient data for 1 month
    set_transient('esse_fp_builder_query_slides', $query, 60 * 60 * 24 * 7 * 4);

    return $query;
  }

}

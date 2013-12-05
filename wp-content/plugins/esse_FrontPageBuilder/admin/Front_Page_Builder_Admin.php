<?php

/**
 * Front Page Builder.
 *
 * @package   esse_Front_Page_Builder_Admin
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * @package esse_Front_Page_Builder_Admin
 * @author  Edis Selimovic <prezentabilan@gmail.com>
 */
class Front_Page_Builder_Admin
{

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Slug of the plugin screen.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_screen_hook_suffix = null;

  /**
   * Initialize the plugin by loading admin scripts & styles and adding a
   * settings page and menu.
   *
   * @since     1.0.0
   */
  private function __construct()
  {

    /*
     * - Uncomment following lines if the admin class should only be available for super admins
     */
    /* if( ! is_super_admin() ) {
      return;
      } */

    /*
     * Call $plugin_slug from public plugin class.
     *
     */
    $plugin = Front_Page_Builder::get_instance();
    $this->plugin_slug = $plugin->get_plugin_slug();

    // Load admin style sheet and JavaScript.
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

    // Add the options page and menu item.
    add_action('admin_menu', array($this, 'create_front_page_menu'));
    add_action('admin_menu', array($this, 'create_slide_menu'));

    // Add an action link pointing to the options page.
    $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_slug . '.php');
    add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

    /*
     * Define custom functionality.
     *
     * Read more about actions and filters:
     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */
    // Create custom post type
    add_action('init', array($this, 'create_slide_post_type'));

    add_filter('@TODO', array($this, 'filter_method_name'));
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

    /*
     * - Uncomment following lines if the admin class should only be available for super admins
     */
    /* if( ! is_super_admin() ) {
      return;
      } */

    // If the single instance hasn't been set, set it now.
    if (null == self::$instance)
    {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Register and enqueue admin-specific style sheet.
   *
   * @since     1.0.0
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_styles()
  {

    if (!isset($this->plugin_screen_hook_suffix))
    {
      return;
    }

    $screen = get_current_screen();
    if ($this->plugin_screen_hook_suffix == $screen->id)
    {
      wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), array(), Front_Page_Builder::VERSION);
    }
  }

  /**
   * Register and enqueue admin-specific JavaScript.
   *
   * @since     1.0.0
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_scripts()
  {

    if (!isset($this->plugin_screen_hook_suffix))
    {
      return;
    }

    $screen = get_current_screen();
    if ($this->plugin_screen_hook_suffix == $screen->id)
    {
      wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), Front_Page_Builder::VERSION);
    }
  }

  /**
   * Register the administration menu for this plugin into the WordPress Dashboard menu.
   *
   * @since    1.0.0
   */
  public function add_plugin_admin_menu()
  {

    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     * @TODO:
     *
     * - Change 'Page Title' to the title of your plugin admin page
     * - Change 'Menu Text' to the text for menu item for the plugin settings page
     * - Change 'manage_options' to the capability you see fit
     *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
     */
    $this->plugin_screen_hook_suffix = add_options_page(
      __('Page Title', $this->plugin_slug), __('Menu Text', $this->plugin_slug), 'manage_options', $this->plugin_slug, array($this, 'display_plugin_admin_page')
    );
  }

  /**
   * Render the settings page for this plugin.
   *
   * @since    1.0.0
   */
  public function display_plugin_admin_page()
  {
    include_once( 'views/admin.php' );
  }

  /**
   * Add settings action link to the plugins page.
   *
   * @since    1.0.0
   */
  public function add_action_links($links)
  {

    return array_merge(
      array(
        'settings' => '<a href="' . admin_url('options-general.php?page=' . $this->plugin_slug) . '">' . __('Settings', $this->plugin_slug) . '</a>'
      ), $links
    );
  }

  /**
   * Create Slide
   * @since    1.0.0
   */
  public function create_slide_post_type()
  {
    $labels = array(
        'name'               => __('Slides', $this->plugin_slug),
        'singular_name'      => __('Slides', $this->plugin_slug),
        'add_new'            => __('Add New', $this->plugin_slug),
        'add_new_item'       => __('Add New Slide', $this->plugin_slug),
        'edit_item'          => __('Edit Slide', $this->plugin_slug),
        'new_item'           => __('New Slide', $this->plugin_slug),
        'all_items'          => __('All Slide', $this->plugin_slug),
        'view_item'          => __('View Slide', $this->plugin_slug),
        'search_items'       => __('Search Slide', $this->plugin_slug),
        'not_found'          => __('No slides found', $this->plugin_slug),
        'not_found_in_trash' => __('No slides found in Trash', $this->plugin_slug),
        'parent_item_colon'  => '',
        'menu_name'          => __('Front Builder', $this->plugin_slug)
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
        'menu_position'      => 27,
        'supports'           => array('title', 'editor', 'thumbnail')
    );

    register_post_type('slide', $args);
  }

  /**
   * Build main manu for building front page
   * 
   * @since   1.0.0
   */
  function create_front_page_menu()
  {
    $this->plugin_screen_hook_suffix = add_menu_page(
      __('Front Page Builder', $this->plugin_slug)
      , __('Front Page', $this->plugin_slug)
      , 'edit_posts'
      , 'fp-builder-main-menu'
      , array($this, 'display_front_page_menu')
      , ''
      , 26);
  }

  /**
   * Display Front Page Menu
   * 
   * @since   1.0.0
   */
  function display_front_page_menu()
  {
    include_once( 'views/main_menu.php' );
  }
  
  /**
   * Create submenu for slides
   * 
   * @since   1.0.0
   */
  function create_slide_menu()
  {
     $this->plugin_screen_hook_suffix = add_submenu_page(
      'fp-builder-main-menu'
      , __('Slides', $this->plugin_slug)
      , __('Slides', $this->plugin_slug)
      , 'edit_posts'
      , 'fp-builer-slides'
      , array($this, 'display_slide_submenu')
      , ''
      , 26);
  }
  
  /**
   * Display submenu for slides
   * 
   * @since   1.0.0
   */
  function display_slide_submenu()
  {
    include_once( 'views/slides_submenu.php' );
  }

  /**
   * NOTE:     Filters are points of execution in which WordPress modifies data
   *           before saving it or sending it to the browser.
   *
   *           Filters: http://codex.wordpress.org/Plugin_API#Filters
   *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
   *
   * @since    1.0.0
   */
  public function filter_method_name()
  {
    // @TODO: Define your filter hook callback here
  }

}

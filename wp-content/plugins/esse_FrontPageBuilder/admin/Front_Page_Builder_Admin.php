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
    add_action('init', array($this, 'fp_item_post_type'));

    // Define: custom post type, taxonomy, meta box.
    add_action('init', array($this, 'define_fp_item_type'));
    add_action('add_meta_boxes', array($this, 'select_fp_item_type'));
    add_action('save_post', array($this, 'save_fp_item_type'));

    // Action for slides settings
    add_action('admin_menu', array($this, 'create_slide_menu'));
    add_action('wp_ajax_esse_fpb_slides_order', array($this, 'set_slide_order'));
    add_action('trashed_post', array($this, 'delete_slide'));
    add_action('untrashed_post', array($this, 'restore_slide_from_trash'));

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
      wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery-ui-sortable'), Front_Page_Builder::VERSION);
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
   * Create FP Item custom post type
   * 
   * @since    1.0.0
   */
  public function fp_item_post_type()
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
   * Create submenu for slides settings.
   * Menu apper in custom post type (FB Builder menu)
   * 
   * @since   1.0.0
   */
  function create_slide_menu()
  {
    $this->plugin_screen_hook_suffix = add_submenu_page(
      'edit.php?post_type=fp_item'
      , __('Slides Settings', $this->plugin_slug)
      , __('Slides Settings', $this->plugin_slug)
      , 'edit_posts'
      , 'fp-slides-settings'
      , array($this, 'display_slide_settings'));
  }

  /**
   * Display submenu for slides settings.
   * 
   * @since   1.0.0
   */
  function display_slide_settings()
  {
    // loop arguments, loop is in view
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
    //$slidesOrder = array(1756, 1745, 1757);

    if ($slidesOrder)
    {
      // if slides order set, show slides in order
      $args['post__in'] = $slidesOrder;
      $args['orderby'] = 'post__in'; // @since 3.5
    }

    // if slides order not set, show slide in default order
    include_once( 'views/slides_submenu.php' );
  }

  /**
   * Define taxonomy for fp_item. Is fp item: slide or quote.
   * 
   * @since   1.0.0
   */
  function define_fp_item_type()
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
        'labels'       => $labels,
        'public'       => false,
        'hierarchical' => false,
        'query_var'    => true,
        'rewrite'      => true
    );

    register_taxonomy('fp_item_type', 'fp_item', $args);
  }

  /**
   * Create meta box for selectint type of fp item.
   * 
   * @since   1.0.0
   */
  function select_fp_item_type()
  {
    add_meta_box(
      'fp-item-type'
      , __('Type', $this->plugin_slug)
      , array($this, 'meta_box_fp_item_type')
      , 'fp_item', 'side', 'default'
    );
  }

  /**
   * Display meta box for select type of fp_item
   * 
   * @param   object $post post it self
   * @param   type $box
   * 
   * @since   1.0.0
   */
  function meta_box_fp_item_type($post, $box)
  {
    //nonce for security
    wp_nonce_field(plugin_basename(__FILE__), 'esse_save_meta_box');

    include_once( 'views/fp_item_type.php' );
  }

  /**
   * Save taxonomy term from fp item
   * 
   * @param int $post_id Post id
   * 
   * @since   1.0.0
   */
  function save_fp_item_type($post_id)
  {
    if (isset($_POST['fp_item_type']))
    {
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

      check_admin_referer(plugin_basename(__FILE__), 'esse_save_meta_box');

      wp_set_object_terms($post_id, $_POST['fp_item_type'], 'fp_item_type');

      // Add slide to slides order array on the end, if slides order is set
      $slide = __('Slide', $this->plugin_slug);

      if ($slidesOrder = get_option('fp_builder_slide_order') AND $_POST['fp_item_type'] == $slide)
      {
        $slidesOrder[] = $post_id;

        update_option('fp_builder_slide_order', $slidesOrder);
      }
    }
  }

  /**
   * Processing ajax request.
   * Set slide order with drag and drop funcionality. Slide order save in
   * options DB.
   * 
   * @since   1.0.0
   */
  function set_slide_order()
  {
    if (isset($_POST['item']))
    {
      // Update slide order
      update_option('fp_builder_slide_order', $_POST['item']);
    }
  }

  /**
   * Delete slide. Slide move to trash if trash enabled in settings.
   * 
   * @param   int $post_id Post id
   * 
   * @sicne   1.0.0 
   */
  function delete_slide($post_id)
  {
    if ($this->check_is_slide($post_id))
    {
      $slidesOrder = get_option('fp_builder_slide_order');

      // Well then, remove slide from slide order
      $slidesOrder = array_diff($slidesOrder, array($post_id));

      update_option('fp_builder_slide_order', $slidesOrder);
    }
  }

  /**
   * Restore post from trash and add to end of array slidesOrder.
   * 
   * @param   int $post_id Post id
   * 
   * @since   1.0.0
   */
  function restore_slide_from_trash($post_id)
  {
    if ($this->check_is_slide($post_id))
    {
      $slidesOrder = get_option('fp_builder_slide_order');

      // Well then, add slide to end of array slidesOrder
      $slidesOrder[] = $post_id;

      update_option('fp_builder_slide_order', $slidesOrder);
    }
  }

  /**
   * Check is fp_item type of slide, fp_item is custom post type defined above.
   * 
   * @param   type $post_id
   * @return  boolean
   * 
   * @since   1.0.0
   */
  private function check_is_slide($post_id)
  {
    $post = get_post($post_id);

    if ($post->post_type == 'fp_item')
    {
      $term_list = wp_get_post_terms($post->ID, 'fp_item_type', array("fields" => "names"));

      // Proced if post type of slide and slidesOrder is set
      if (in_array('Slide', $term_list))
      {
        return true;
      }
    }

    return false;
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

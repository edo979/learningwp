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
    // Custom post type is created in public class

    // Metabox for selecting type and save custom post type.
    add_action('add_meta_boxes', array($this, 'fp_item_select_type'));
    add_action('save_post', array($this, 'fp_item_save'));

    // Action for slides settings
    add_action('admin_menu', array($this, 'slide_menu'));
    add_action('wp_ajax_esse_fpb_slides_order', array($this, 'slide_order'));
    add_action('trashed_post', array($this, 'slide_delete'));
    add_action('untrashed_post', array($this, 'slide_untrash'));

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
   * Create submenu for slides settings.
   * Menu apper in custom post type (FB Builder menu)
   * 
   * @since   1.0.0
   */
  function slide_menu()
  {
    $this->plugin_screen_hook_suffix = add_submenu_page(
      'edit.php?post_type=fp_item'
      , __('Slides Settings', $this->plugin_slug)
      , __('Slides Settings', $this->plugin_slug)
      , 'edit_posts'
      , 'fp-slides-settings'
      , array($this, 'slide_settings'));
  }

  /**
   * Display submenu for slides settings.
   * 
   * @since   1.0.0
   */
  function slide_settings()
  {
    // Query custom post type with arguments array, loop is inside view.
    $slides = $this->slides_get();

    // if slides order not set, show slide in default order
    include_once( 'views/slides_submenu.php' );

    wp_reset_postdata();
  }

  /**
   * Create meta box for selectint type of fp item.
   * 
   * @since   1.0.0
   */
  function fp_item_select_type()
  {
    add_meta_box(
      'fp-item-type'
      , __('Type', $this->plugin_slug)
      , array($this, 'fp_item_type_meta_box')
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
  function fp_item_type_meta_box($post, $box)
  {
    //nonce for security
    wp_nonce_field(plugin_basename(__FILE__), 'esse_save_meta_box');

    include_once( 'views/fp_item_type.php' );
  }

  /**
   * Save new item or update item. If item is slide, then add item to end 
   * of slidesOrder array.
   * 
   * @param int $post_id Post id
   * 
   * @since   1.0.0
   */
  function fp_item_save($post_id)
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
        // Determine is update ili new slide by id in slideOrder.
        if (!in_array($post_id, $slidesOrder))
        {
          $slidesOrder[] = $post_id;
        }

        $this->slides_order_update($slidesOrder);
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
  function slide_order()
  {
    if (isset($_POST['item']) AND isset($_POST['security']))
    {
      check_ajax_referer('admin-slides-order-nonce', 'security');

      // Update slide order
      $this->slides_order_update($_POST['item']);
    }
  }

  /**
   * Delete slide. Slide move to trash if trash enabled in settings.
   * 
   * @param   int $post_id Post id
   * 
   * @sicne   1.0.0 
   */
  function slide_delete($post_id)
  {
    if ($this->slide_type_check($post_id))
    {
      $slidesOrder = get_option('fp_builder_slide_order');

      // Well then, remove slide from slide order
      $slidesOrder = array_diff($slidesOrder, array($post_id));

      $this->slides_order_update($slidesOrder);
    }
  }

  /**
   * Restore post from trash and add to end of array slidesOrder.
   * 
   * @param   int $post_id Post id
   * 
   * @since   1.0.0
   */
  function slide_untrash($post_id)
  {
    if ($this->slide_type_check($post_id))
    {
      $slidesOrder = get_option('fp_builder_slide_order');

      // Well then, add slide to end of array slidesOrder
      $slidesOrder[] = $post_id;

      $this->slides_order_update($slidesOrder);
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
  private function slide_type_check($post_id)
  {
    $post = get_post($post_id);

    if ($post->post_type == 'fp_item')
    {
      $term_list = wp_get_post_terms($post->ID, 'fp_item_type', array("fields" => "names"));

      // Proced if post type of slide
      if (in_array('Slide', $term_list))
      {
        return true;
      }
    }

    return false;
  }

  /**
   * Update slides order array. Option slides order is use for correct
   * show slides in right order. Also update transient, update option from 4 place:
   * save/update, ordering slide, trash, untrash.
   * 
   * @param   array $slidesOrder Order slides using slides id
   * 
   * @sinc    1.0.0
   */
  private function slides_order_update($slidesOrder)
  {
    //delete_option('fp_builder_slide_order');
    update_option('fp_builder_slide_order', $slidesOrder);

    // Reset transient
    $this->transient_set_data();
  }

  /**
   * Return slides from transient data if aveilable or new query with slides data
   * 
   * @return  misc \WP Query
   * 
   * @since   1.0.0
   */
  private function slides_get()
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

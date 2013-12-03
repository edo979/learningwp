<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package easyTheme
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function easytheme_page_menu_args($args)
{
  $args['show_home'] = true;
  return $args;
}

add_filter('wp_page_menu_args', 'easytheme_page_menu_args');

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function easytheme_body_classes($classes)
{
  // Adds a class of group-blog to blogs with more than 1 published author.
  if (is_multi_author())
  {
    $classes[] = 'group-blog';
  }

  return $classes;
}

add_filter('body_class', 'easytheme_body_classes');

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function easytheme_wp_title($title, $sep)
{
  global $page, $paged;

  if (is_feed())
  {
    return $title;
  }

  // Add the blog name
  $title .= get_bloginfo('name');

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo('description', 'display');
  if ($site_description && ( is_home() || is_front_page() ))
  {
    $title .= " $sep $site_description";
  }

  // Add a page number if necessary:
  if ($paged >= 2 || $page >= 2)
  {
    $title .= " $sep " . sprintf(__('Page %s', 'easytheme'), max($paged, $page));
  }

  return $title;
}

add_filter('wp_title', 'easytheme_wp_title', 10, 2);

/**
 * Custom post type for slider on front page
 */
function easytheme_slide_post_type()
{
  $labels = array(
      'name'               => __('Slide', 'easytheme'),
      'singular_name'      => __('Product', 'easytheme'),
      'add_new'            => __('Add New', 'easytheme'),
      'add_new_item'       => __('Add New Product', 'easytheme'),
      'edit_item'          => __('Edit Product', 'easytheme'),
      'new_item'           => __('New Product', 'easytheme'),
      'all_items'          => __('All Slide', 'easytheme'),
      'view_item'          => __('View Product', 'easytheme'),
      'search_items'       => __('Search Slide', 'easytheme'),
      'not_found'          => __('No products found', 'easytheme'),
      'not_found_in_trash' => __('No products found in Trash', 'easytheme'),
      'parent_item_colon'  => '',
      'menu_name'          => __('Slide', 'easytheme')
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => true,
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array('title', 'editor', 'thumbnail')
  );

  register_post_type('slide', $args);
}

add_action('init', 'easytheme_slide_post_type');

/**
 * Sub menu in Slide custom post type menu, for slide settings
 */
function easytheme_create_menu()
{
  $page_hook_suffix = add_submenu_page(
    'edit.php?post_type=slide'
    , 'Slide Settings'
    , 'Settings'
    , 'edit_posts'
    , 'slide-settings-page'
    , 'easytheme_settings_page');

  //add jquery ui script for sortable options
  add_action('admin_print_scripts-' . $page_hook_suffix, 'easytheme_custom_admin_script');
}

add_action('admin_menu', 'easytheme_create_menu');

/**
 * Register options to save in DB
 */
function easytheme_register_settings()
{
  register_setting(
    'easytheme-slide-settings-group'
    , 'easytheme_slide_options'
    , 'easytheme_sanitize_callback');
}

add_action('admin_init', 'easytheme_register_settings');

/**
 * Add javaScripts to page
 */
function easytheme_custom_admin_script()
{
  // wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('sortableSlides', get_template_directory_uri() . '/js/admin/sortableSlides.js', array('jquery-ui-sortable'), '1.0.0', true);
}

/**
 * Build options page
 */
function easytheme_settings_page()
{
  ?>
  <div class="wrap">
    <h2>Slide Options</h2>

    <table id="admin-slides-sort" class="widefat">
      <thead>
        <tr>
          <th><?php _e('Slides', 'easytheme'); ?></th>
          <th><?php _e('Message', 'easytheme'); ?></th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th><?php _e('Slides', 'easytheme'); ?></th>
          <th><?php _e('Message', 'easytheme'); ?></th>
        </tr>
      </tfoot>
      <tbody>
        <?php
        global $post;

        $slidesOrder = get_option('easytheme_slide_order');
        $args = array('post_type' => 'slide');

        // get slides
        $loop = new WP_Query($args);
        while ($loop->have_posts()) :
          $loop->the_post();
          $slides_id[(string) get_the_ID()] = $post;
        endwhile;

//print_r($slidesOrder);die();
        // Reorder slides to match order on settings page
        ($slidesOrder) ? $slidesOrder : $slidesOrder = array_keys($slides_id); // fallback
        // Build slides on settings page in admin area
        foreach ($slidesOrder as $id) :

          if (!$post = $slides_id[(string) $id])
            continue;
          ?>
          <tr <?php echo "id='item_" . get_the_ID() . "'"; ?> class="list-items">
            <td><?php the_post_thumbnail('medium', array('alt' => 'slider image')); ?></td>
            <td>
              <h3><?php the_title(); ?></h3>
              <p><?php the_content(); ?></p>
              <p><?php the_ID(); ?></p>
            </td>
          </tr>

        <?php endforeach; ?>

      </tbody>
    </table>
  </div><!-- .wrap -->

  <?php
}

/**
 * Process ajax request
 * Build Bootstrap carousel, preserve order from settings page in admin area.
 */
function easytheme_slides_save_order()
{
  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    // Slides order is set in post array
    $slidesOrder = $_POST['item'];
    $args = array('post_type' => 'slide');

    // Save slides order in options DB
    update_option('easytheme_slide_order', $slidesOrder);
    print_r($slidesOrder);die();
  }
  else
  {
    // Use wp default order, POST not set
    $args = array('post_type' => 'slide');

    // If slides order is set use it, or false
    $slidesOrder = get_option('easytheme_slide_order');
  }

  global $post;
  $firstSlideActive = true; // set active class to first slide

  $loop = new WP_Query($args);

  // get slides and save in array using slide id
  while ($loop->have_posts()) :
    $loop->the_post();
    $slides_id[(string) get_the_ID()] = $post;
  endwhile;

  // Reorder slides to match order on settings page
  $slidesOrder ? $slidesOrder : $slidesOrder = array_keys($slides_id); // fallback
  // cache output to transient db
  ob_start();
  ?>

  <!-- Indicators -->
  <ol class="carousel-indicators">
    <?php for ($i = 0; $i < count($slidesOrder); $i++) : ?>
      <li data-target="#mytheme-carousel" data-slide-to="<?php echo $i; ?>" class="<?php if (!$i) echo 'active'; ?>"></li>
    <?php endfor; ?>
  </ol>

  <div class="carousel-inner">
    <?php
    foreach ($slidesOrder as $id) :
      if (!$post = $slides_id[(string) $id])
        continue;
      ?>
      <div class="item<?php if ($firstSlideActive) : ?> active<?php endif; ?>">
        <?php the_post_thumbnail('full', array('alt' => 'slider image')); ?>
        <div class="container">
          <div class="carousel-caption">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
          </div><!-- .carousel-caption -->
        </div><!-- .container -->
      </div><!-- .item -->
      <?php
      $firstSlideActive = false; // disable slide active class
    endforeach;
    ?>
  </div><!-- .carousel-inner -->
  <?php
  $result = ob_get_clean();

  // store result in DB for 1 week
  set_transient('slides_order_result', $result, 60 * 60 * 24 * 7);
}

add_action('wp_ajax_easytheme_slides_update', 'easytheme_slides_save_order');

/**
 * Refresh slideOrder on create new slide or update slide
 * 
 * @param int $post_id new or updated slide
 * @return void
 */
function easytheme_refresh_slide_order($post_id)
{
  $post = get_post($post_id);

  if ('slide' == $post->post_type)
  {
    $slidesOrder = get_option('easytheme_slide_order');
    
    if ($slidesOrder)
    {
      // Recreate slides order on delete
      if ($post->post_status == 'trash')
      {
        // Find slides in slides order and remove it
        $slidesOrder = array_diff($slidesOrder, array($post_id));
      }
      else
      {
        // Recreate slides order on add new
        // check is post_id in slideOrder to determine if new or update slide
        if (in_array($post_id, $slidesOrder, true))
        {
          // This is update. Recreate slide
          easytheme_slides_save_order();
          return;
        }

        // Add new slide
        print_r($slidesOrder);
        array_push($slidesOrder, $post_id);
        
      }

      // Slides order changes, update slide order
      update_option('easytheme_slide_order', $slidesOrder);

      // Recreate slide
      easytheme_slides_save_order();
    }
  }
}

add_action('post_updated', 'easytheme_refresh_slide_order');

/**
 * Sanitize form input data before storing in db
 * 
 * @param array $input
 * @return array
 */
function easytheme_sanitize_callback($input)
{
  $input['max_slides'] = sanitize_text_field($input['max_slides']);

  return $input;
}

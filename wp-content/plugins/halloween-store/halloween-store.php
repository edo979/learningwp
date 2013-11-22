<?php
/*
 * Plugin Name: Halloween Store
 * Plugin URI: http://webdevstudios.com/support/wordpress-plugins/
 * Description: Create a Halloween Store to display product information
 * Version: 1.0
 * Author: Brad Williams
 * Author URI: http://webdevstudios.com
 * License: GPLv2
 */

/* Copyright 2013 Brad Williams (email : brad@webdevstudios.com)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

register_activation_hook(__FILE__, 'halloween_store_install');

function halloween_store_install()
{
  //setup default option values
  $hween_options_arr = array(
      'currency_sign' => '$'
  );

//save our default option values
  update_option('halloween_options', $hween_options_arr);
}

add_action('init', 'halloween_store_init');

function halloween_store_init()
{
  $labels = array(
      'name'               => __('Products', 'halloween-plugin'),
      'singular_name'      => __('Product', 'halloween-plugin'),
      'add_new'            => __('Add New', 'halloween-plugin'),
      'add_new_item'       => __('Add New Product', 'halloween-plugin'),
      'edit_item'          => __('Edit Product', 'halloween-plugin'),
      'new_item'           => __('New Product', 'halloween-plugin'),
      'all_items'          => __('All Products', 'halloween-plugin'),
      'view_item'          => __('View Product', 'halloween-plugin'),
      'search_items'       => __('Search Products', 'halloween-plugin'),
      'not_found'          => __('No products found', 'halloween-plugin'),
      'not_found_in_trash' => __('No products found in Trash', 'halloween-plugin'),
      'parent_item_colon'  => '',
      'menu_name'          => __('Products', 'halloween-plugin')
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
      'supports'           => array('title', 'editor', 'thumbnail', 'excerpt')
  );

  register_post_type('halloween-products', $args);
}

add_action('admin_menu', 'halloween_store_menu');

function halloween_store_menu()
{
  add_options_page(__('Halloween Store Settings Page', 'halloween-plugin'), __('Halloween Store Settings', 'halloween-plugin'), 'manage_options', 'halloween-store-settings', 'halloween_store_settings_page');
}

function halloween_store_settings_page()
{
  //load the plugin options array
  $hween_options_arr = get_option('halloween_options');

  //set the option array values to variables
  $hs_inventory = (!empty($hween_options_arr['show_inventory']) ) ?
    $hween_options_arr['show_inventory'] : '';

  $hs_currency_sign = $hween_options_arr['currency_sign'];
  ?>

  <div class="wrap">
    <h2><?php _e('Halloween Store Options', 'halloween-plugin') ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields('halloween-settings-group'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">
            <?php _e('Show Product Inventory', 'halloween-plugin') ?>
          </th>
          <td><input type="checkbox" name="halloween_options[show_inventory]"
                     <?php echo checked($hs_inventory, 'on'); ?> /></td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Currency Sign', 'halloween-plugin') ?></th>
          <td><input type="text" name="halloween_options[currency_sign]"
                     value="<?php echo esc_attr($hs_currency_sign); ?>"
                     size="1" maxlength="1" /></td>
        </tr>
      </table>
      <p class="submit">
        <input type="submit" class="button-primary"
               value="<?php _e('Save Changes', 'halloween-plugin'); ?>" />
      </p>
    </form>
  </div>
  <?php
  // Action hook to register the plugin option settings
  add_action('admin_init', 'halloween_store_register_settings');

  function halloween_store_register_settings()
  {
    //register the array of settings
    register_setting('halloween-settings-group', 'halloween_options', 'halloween_sanitize_options');
  }

  function halloween_sanitize_options($options)
  {
    $options['show_inventory'] = (!empty($options['show_inventory']) ) ?
      sanitize_text_field($options['show_inventory']) : '';
    $options['currency_sign'] = (!empty($options['currency_sign']) ) ?
      sanitize_text_field($options['currency_sign']) : '';
    return $options;
  }

}

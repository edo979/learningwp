<?php
/**
 * Display select field in admin post list table for selecting type of fp item.
 * With this feature user can view only selected type of fp item for easy
 * manage items.
 *
 * @package   esse_Front_Page_Builder_Admin
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 */
?>

<select name='fp-item-type'>
  <option value = '0'> <?php _e('All types', $this->plugin_slug) ?> </option>

  <?php
  foreach ($fp_item_type as $type) :
    $selected = (!empty($_GET['fp-item-type']) AND $_GET['fp-item-type'] == $type->name) ? 'selected="selected"' : '';
    ?>
    <option <?php echo $selected ?> value = "<?php echo esc_attr($type->name) ?>">
      <?php echo esc_html($type->name) ?>
    </option>

  <?php endforeach; ?>

</select>
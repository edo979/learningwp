<?php
/**
 * Display fp item type meta box.
 *
 * @package   esse_Front_Page_Builder_Admin
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 */
$tax_name = 'fp_item_type';
?>

<div class="tagsdiv" id="fp-item-type">
  <p><?php _e('Select type of Front Page item', $this->plugin_slug); ?></p>
  <select name="<?php echo $tax_name; ?>" id="<?php echo $tax_name; ?>">
    <?php foreach (array(__('Slide', $this->plugin_slug), __('Quote', $this->plugin_slug)) as $g): ?>
      <option value="<?php echo $g ?>" <?php echo selected(get_terms_to_edit($post->ID, $tax_name), $g, false) ?>> <?php echo $g; ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>
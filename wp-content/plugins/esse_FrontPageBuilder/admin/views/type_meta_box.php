<?php
/**
 * Represents the view for the meta box.
 * Select fp_item type.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   esse_Front_Page_Builder_Admin
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 */
?>

<p><?php _e('Please select type of front page item.', $this->plugin_slug); ?></p>
<p>Type:
  <select name="fp_item_type" id="fp_item_type">
    <option value="0" <?php selected($fp_item_type, 'slide'); ?> >
      Slide
    </option>

    <option value="quote" <?php selected($fp_item_type, 'quote'); ?> >
      Quote
    </option>
  </select>
</p>
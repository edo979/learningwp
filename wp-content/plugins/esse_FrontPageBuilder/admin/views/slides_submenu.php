<?php
/**
 * Display slides. Drag and drop slide to make order in which slides show up,
 * on front page in carousel.
 *
 * @package   esse_Front_Page_Builder_Admin
 * @author    Edis Selimovic <prezentabilan@gmail.com>
 * @license   GPL-2.0+
 * @link      http://kvalitetnije.com.ba
 * @copyright 2013 Edis Selimovic
 */
?>

<div class="wrap">
  <h2>Slide Options</h2>
  <p><?php _e('Drag slides to wanted position. Slides on top shows first...', $this->plugin_slug); ?></p>

  <table id="admin-slides-sort" class="widefat">
    <thead>
      <tr>
        <th><?php _e('Slides', $this->plugin_slug); ?></th>
        <th><?php _e('Message', $this->plugin_slug); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th><?php _e('Slides', $this->plugin_slug); ?></th>
        <th><?php _e('Message', $this->plugin_slug); ?></th>
      </tr>
    </tfoot>
    <tbody>
      <?php
      if ($slides->have_posts()) :
        while ($slides->have_posts()) : $slides->the_post();
          ?>
          <tr <?php echo "id='item_" . get_the_ID() . "'"; ?> class="list-items">
            <td><?php the_post_thumbnail('medium', array('alt' => 'slider image')); ?></td>
            <td>
              <h3><?php the_title(); ?></h3>
              <p><?php the_content(); ?></p>
              <p><?php the_ID(); ?></p>
            </td>
          </tr>
          <?php
        endwhile;
      endif;
      ?>
    </tbody>
  </table>
  <div id="slides-order-key" style="visibility: hidden;"><?php echo wp_create_nonce('admin-slides-order-nonce'); ?></div>
</div> <!-- .wrap -->
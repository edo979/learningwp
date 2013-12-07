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
        $slides = new WP_Query($args);
        while ($slides->have_posts()) : $slides->the_post();
      ?>
      
        <tr <?php echo "id='item_'" . get_the_ID() . "'"; ?> class="list_items">
          <td><?php the_post_thumbnail('medium', array('alt' => 'slider image')); ?></td>
          <td>
            <h3><?php the_title(); ?></h3>
            <p><?php the_content(); ?></p>
            <p><?php the_ID(); ?></p>
          </td>
        </tr>
        
      <?php endwhile; ?>
    </tbody>
  </table>
</div> <!-- .wrap -->
<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<section class="entry-content row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'twentythirteen')); ?>
    <?php wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'mytheme') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
    <?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
  </div>
</section><!-- .entry-content row -->

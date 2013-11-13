<?php
/**
 * The template for displaying posts in the Gallery post format
 *
 */
?>

<?php if (is_single()) : ?>
  <?php if (!get_post_gallery()) : ?>

    <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'mytheme')); ?>
    <?php wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'mytheme') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>

  <?php else : ?>

    <?php //echo get_post_gallery(); ?>
    <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'mytheme')); ?>

  <?php endif; // is !get_post_gallery() ?>
<?php endif; // is single?>
<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<div class="row">
  <div class="col-lg-8">
    <div class="panel panel-default">
      <div class="panel-body">

        <?php if (is_single()) : ?>
          <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php else : ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header">
              <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
              </h1>
            </header>

            <div class="entry-content">
              <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'mytheme')); ?>
              <?php wp_link_pages(array('before' => '<div class="page-links">' . __('Pages:', 'mytheme'), 'after' => '</div>')); ?>
            </div><!-- .entry-content -->

            <footer class="entry-meta">
              <div class="author-description">
                <h2><?php printf(__('About %s', 'mytheme'), get_the_author()); ?></h2>
                <p><?php the_author_meta('description'); ?></p>
                <div class="author-link">
                  <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
                    <?php printf(__('View all posts by %s <span class="meta-nav">&rarr;</span>', 'mytheme'), get_the_author()); ?>
                  </a>
                </div><!-- .author-link	-->
            </footer>
          </article>

        <?php endif; // is_single() ?>

      </div><!-- .panel-body	-->
    </div><!-- .panel-default	-->
  </div><!-- .col-lg-8	-->
</div><!-- .row-->
<?php
/**
 * The template for displaying all single posts
 */
get_header();
?>

<div id="content" class="site-content row" role="main">
  <section id="main-column" class="col-lg-8 col-md-8">

    <?php /* The loop */ ?>
    <?php while (have_posts()) : the_post(); ?>

      <div class="panel panel-default">
        <div class="panel-body">
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php if ( ! get_post_format() ) : ?>
              <?php get_template_part('partials/singlePostHeader'); ?>
            <?php endif; ?>

            <section class="entry-content row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php get_template_part('content', get_post_format()); ?>
              </div>
            </section>

            <footer class="row">
              <div class="page-navigation col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'twentythirteen') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
              </div>
            </footer>
          </article><!-- #post -->
        </div><!-- .panel-body -->
      </div><!-- .panel-default -->


    <?php endwhile; ?>
  </section>
  <aside id="sidebar" class="col-lg-4 col-md-4">
    <?php get_sidebar(); ?>
  </aside><!-- #sidebar -->
</div><!-- #content -->

<?php get_footer(); ?>
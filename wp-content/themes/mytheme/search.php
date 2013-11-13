<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
get_header();
?>
<div id="content" class="site-content row" role="main">
  <section id="main-column" class="col-lg-8 col-md-8">

    <?php if (have_posts()) : ?>

      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="page-title ">
            <?php printf(__('Search Results for: %s', 'mytheme'), get_search_query()); ?>
          </h3>
        </div>
      </div>

      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('content', get_post_format()); ?>
      <?php endwhile; ?>

    <?php else : ?>
      <?php get_template_part('content', 'none'); ?>
    <?php endif; ?>

  </section><!-- #main-column -->

  <aside id="sidebar" class="col-lg-4 col-md-4">
    <?php get_sidebar(); ?>
  </aside><!-- #sidebar -->

</div><!-- #main-content -->

<section id="page-navigation" class="row">
  <?php mytheme_paging_nav(); ?>
</section><!-- #page-navigation -->

<?php get_footer(); ?>
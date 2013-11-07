<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
get_header();
?>

<section id="primary" class="site-content container marketing">
  <div id="content" role="main">
    <div class="row">
      <div class="col-lg-8">

        <?php if (have_posts()) : ?>

          <?php /* Start the Loop */ ?>
          <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('content', get_post_format()); ?>
          <?php endwhile; ?>

        <?php endif ?>
        
      </div><!-- .col-lg-8	-->
    </div><!-- .row-->
  </div><!-- #content -->
</section><!-- #primary -->

<?php get_footer(); ?>
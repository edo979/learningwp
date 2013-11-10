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
?>

<?php get_header(); ?>

  <div id="content" class="site-content row" role="main">
    <section id="main-column" class="col-lg-8">
      <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('content', get_post_format()); ?>
        <?php endwhile; ?>
      <?php else : ?>
        <?php get_template_part('content', 'none'); ?>
      <?php endif; ?>
    </section>
    
    <aside id="sidebar" class="col-lg-4">
      <?php get_sidebar(); ?>
    </aside><!-- #sidebar -->
    
  </div><!-- #main-content -->
  
  <section id="page-navigation" class="row">
    <?php mytheme_paging_nav(); ?>
  </section><!-- #page-navigation -->
  
<?php get_footer(); ?>
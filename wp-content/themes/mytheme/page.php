<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */
// Some variables
// Widith column of image in blog page
$leftColumnSize = 'col-lg-2 col-md-2 col-sm-2 hidden-xs';
$rightColumnSize = 'col-lg-10 col-md-10 col-sm-10';

get_header();
?>

<div id="content" class="site-content row" role="main">
  <section id="main-column" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php while (have_posts()) : the_post(); ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
            <section class="col-lg-12 col-md-12 col-sm-12">
              <header class="row">
                <div class="post-feature-image <?php echo $leftColumnSize ?>">

                  <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                    <?php the_post_thumbnail('thumbnail', array('class' => 'thumbnail')); ?>
                  <?php else : ?>
                    <?php // show default image for category ?>
                    <img src="" alt="" style="width: 150px; height: 150px;" class="thumbnail">
                  <?php endif; ?>

                </div><!-- .post-feature-image -->

                <div class="entry-header <?php echo $rightColumnSize ?>">
                  <h1 class="entry-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                  </h1>

                  <div class="entry-meta">
                    <?php mytheme_entry_meta(); ?>
                    <?php edit_post_link(__('Edit', 'mytheme'), '<span class="edit-link">', '</span>'); ?>
                  </div><!-- .entry-meta -->
                </div><!-- .entry-header -->
              </header><!-- .row -->

              <hr>

              <section class="entry-content row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <?php the_content(); ?>
                  <?php wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'mytheme') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
                </div>
              </section>

            </section><!-- .col-lg-12 col-md-12 col-sm-12 -->
          </article><!-- #post -->
        </div><!-- .panel-body -->
      </div><!-- .panel-default -->
    <?php endwhile; ?>
  </section><!-- #main-column -->

</div><!-- #main-content -->

<?php get_footer(); ?>
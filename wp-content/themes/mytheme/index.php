<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 */

// Some variables
// Widith column of image in blog page
$leftColumnSize = 'col-lg-3 col-md-2 col-sm-2 hidden-xs';
$rightColumnSize = 'col-lg-9 col-md-10 col-sm-10';
?>

<?php get_header(); ?>

<div id="content" class="site-content row" role="main">
  <section id="main-column" class="col-lg-8 col-md-8">
    <?php if (have_posts()) : ?>

      <?php if (is_search()) : ?>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="page-title ">
              <?php printf(__('Search Results for: %s', 'mytheme'), get_search_query()); ?>
            </h3>
          </div>
        </div>
      <?php endif; // is search?>

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

                <?php if (is_search()) : // Only display Excerpts for Search  ?>
                  <section class="row">
                    <div class="entry-summary col-lg-12">
                      <hr>
                      <?php the_excerpt(); ?>
                    </div><!-- .entry-summary -->
                  </section><!-- .row -->
                <?php endif; ?>

                <footer class="entry-meta">
                  <?php if (comments_open() && !is_single()) : ?>
                    <div class="comments-link">
                      <?php comments_popup_link('<span class="leave-reply">' . __('Leave a comment', 'mytheme') . '</span>', __('One comment so far', 'mytheme'), __('View all % comments', 'mytheme')); ?>
                    </div><!-- .comments-link -->
                  <?php endif; // comments_open()  ?>

                  <?php if (is_single() && get_the_author_meta('description') && is_multi_author()) : ?>
                    <?php get_template_part('author-bio'); ?>
                  <?php endif; ?>
                </footer><!-- .entry-meta -->
              </section><!-- .col-lg-12 col-md-12 col-sm-12 -->
            </article><!-- #post -->
          </div><!-- .panel-body -->
        </div><!-- .panel-default -->
      <?php endwhile; ?>
    <?php else : ?>
      <?php get_template_part('content', 'none'); ?>
    <?php endif; ?>
  </section>

  <aside id="sidebar" class="col-lg-4 col-md-4">
    <?php get_sidebar(); ?>
  </aside><!-- #sidebar -->

</div><!-- #main-content -->

<section id="page-navigation" class="row">
  <?php mytheme_paging_nav(); ?>
</section><!-- #page-navigation -->

<?php get_footer(); ?>
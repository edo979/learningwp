<?php
/**
 * The template for displaying all single posts
 */
get_header();

// Some variables
// Widith column of image in blog page
$leftColumnSize = 'col-lg-3 col-md-2 col-sm-2 hidden-xs';
$rightColumnSize = 'col-lg-9 col-md-10 col-sm-10';
?>

<div id="content" class="site-content row" role="main">
  <section id="main-column" class="col-lg-8 col-md-8">

    <?php /* The loop */ ?>
    <?php while (have_posts()) : the_post(); ?>

      <div class="panel panel-default">
        <div class="panel-body">
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header clearfix row">
              <div class="post-feature-image <?php echo $leftColumnSize ?>">
                <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                  <?php the_post_thumbnail('thumbnail', array('class' => 'thumbnail')); ?>
                <?php else : ?>
                  <?php
                  // show default image for category
                  ?>
                  <img src="" alt="" style="width: 150px; height: 150px;" class="thumbnail pull-left post-feature-image <?php echo $leftColumnSize ?>">
                <?php endif; ?>
              </div><!-- .post-feature-image -->
                
              <div class="post-header <?php echo $rightColumnSize ?>">
                <h1>
                  <?php the_title(); ?>
                </h1>
                <div class="entry-meta">
                  <?php mytheme_entry_meta(); ?>
                  <?php edit_post_link(__('Edit', 'mytheme'), '<span class="edit-link">', '</span>'); ?>
                </div><!-- .entry-meta -->
             </div><!-- .post-header -->
             
            </header><!-- .entry-header -->

            <hr class="">

            <section class="entry-content">
              <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'mytheme')); ?>
            </section>
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
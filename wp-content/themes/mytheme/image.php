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
          <article id="post-<?php the_ID(); ?>" <?php post_class('image-attachment'); ?>>

            <header class="entry-header row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="entry-meta">
                  <?php
                  $published_text = __('<span class="attachment-meta label label-default">Published on <time class="entry-date" datetime="%1$s">%2$s</time> in <a href="%3$s" title="Return to %4$s" rel="gallery">%5$s</a></span>', 'mytheme');
                  $post_title = get_the_title($post->post_parent);
                  if (empty($post_title) || 0 == $post->post_parent)
                    $published_text = '<span class="attachment-meta label label-default"><time class="entry-date" datetime="%1$s">%2$s</time></span>';

                  printf($published_text, esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_permalink($post->post_parent)), esc_attr(strip_tags($post_title)), $post_title
                  );

                  $metadata = wp_get_attachment_metadata();
                  printf('<span class="attachment-meta full-size-link label label-default"><a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>', esc_url(wp_get_attachment_url()), esc_attr__('Link to full-size image', 'mytheme'), __('Full resolution', 'mytheme'), $metadata['width'], $metadata['height']
                  );

                  edit_post_link(__('Edit', 'mytheme'), '<span class="edit-link">', '</span>');
                  ?>
                </div><!-- .entry-meta -->
              </div><!-- .col-lg-12 ....-->
            </header><!-- .entry-header -->



            <div class="gallery-nav row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <nav id="image-navigation" class="navigation image-navigation" role="navigation">
                <span class="nav-previous"><?php previous_image_link(false, __('<span class="meta-nav">&larr;</span> Previous', 'mytheme')); ?></span>
                <span class="nav-next pull-right"><?php next_image_link(false, __('Next <span class="meta-nav">&rarr;</span>', 'mytheme')); ?></span>
              </nav><!-- #image-navigation -->
              </div><!-- .col-lg-12 .... -->
            </div><!-- .gallery-nav -->



            <section class="entry-content row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="attachment">
                  <?php mytheme_the_attached_image(); ?>

                  <?php if (has_excerpt()) : ?>
                    <div class="entry-caption">
                      <?php the_excerpt(); ?>
                    </div>
                  <?php endif; ?>
                </div><!-- .attachment -->
                
                <?php if (!empty($post->post_content)) : ?>
                  <div class="entry-description">
                    <?php the_content(); ?>
                    <?php wp_link_pages(array('before' => '<div class="page-links">' . __('Pages:', 'mytheme'), 'after' => '</div>')); ?>
                  </div><!-- .entry-description -->
                <?php endif; ?>
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
<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<div class="panel panel-default">
  <div class="panel-body">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <!--<img src="" alt="" class="pull-left thumbnail">-->
      <?php if (has_post_thumbnail() && !post_password_required()) : ?>
        <?php the_post_thumbnail('thumbnail', array('class' => 'pull-left thumbnail')); ?>
      <?php else : ?>
        <?php 
          // show default image for category
        ?>
      <img src="" alt="" style="height:150px; width: 150px;"class="pull-left thumbnail">
      <?php endif; ?>

      <header class="entry-header">
        <h1 class="entry-title">
          <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h1>
        <div class="entry-meta">
          <?php mytheme_entry_meta(); ?>
          <?php edit_post_link(__('Edit', 'mytheme'), '<span class="edit-link">', '</span>'); ?>
        </div><!-- .entry-meta -->
      </header><!-- .entry-header -->

      <?php if (is_search()) : // Only display Excerpts for Search ?>
        <div class="entry-summary">
          <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
      <?php endif; ?>

      <footer class="entry-meta">
        <?php if (comments_open() && !is_single()) : ?>
          <div class="comments-link">
            <?php comments_popup_link('<span class="leave-reply">' . __('Leave a comment', 'mytheme') . '</span>', __('One comment so far', 'mytheme'), __('View all % comments', 'mytheme')); ?>
          </div><!-- .comments-link -->
        <?php endif; // comments_open() ?>

        <?php if (is_single() && get_the_author_meta('description') && is_multi_author()) : ?>
          <?php get_template_part('author-bio'); ?>
        <?php endif; ?>
      </footer><!-- .entry-meta -->
    </article><!-- #post -->
  </div><!-- .panel-body -->
</div><!-- .panel-default -->

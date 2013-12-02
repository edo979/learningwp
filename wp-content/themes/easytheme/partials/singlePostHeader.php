<?php
/**
 * The template part for displaying heder with title and meta data in top of
 * single post.
 */

// Some variables
// Widith column of image in blog page
$leftColumnSize = 'col-lg-3 col-md-2 col-sm-2 hidden-xs';
$rightColumnSize = 'col-lg-9 col-md-10 col-sm-10';
?>


<header class = "entry-header clearfix row">
  <div class = "post-feature-image <?php echo $leftColumnSize ?>">
    <?php if (has_post_thumbnail() && !post_password_required()) :
      ?>
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
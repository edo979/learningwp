<article id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php if ( is_singular( get_post_type() ) ) { ?>
		<header class="entry-header">
			<h1 class="entry-title"><?php single_post_title(); ?></h1>
			<?php
			if (is_multi_author()) {
				echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">' . __( 'Published by [entry-author] on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'celebrate' ) . '</div>' );
			} else {
				echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">' . __( 'Published on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'celebrate' ) . '</div>' );	
			}
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">        
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'celebrate' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->
		
        <footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in: "] [entry-terms before="<br/>Tagged: "]', 'celebrate' ) . '</div>' ); ?>
		</footer><!-- .entry-footer -->
        
	<?php } else { 
			get_the_image(array('size'=> 'full'));
		?>
		<header class="entry-header">
			<?php the_title( '<h2 class="entry-title"><a href="' . get_permalink() . '">', '</a></h2>' ); ?>
            <?php 
			if (is_multi_author()) {
				echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">' . __( 'Published by [entry-author] ', 'celebrate' ) ); 
			} else {
				echo apply_atomic_shortcode( 'entry_byline', '<div class="entry-byline">' . __( 'Published ', 'celebrate' ) ); 
			}?>
			<?php
			if (get_the_title()!='') {
				echo apply_atomic_shortcode( 'entry_byline', __( 'on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'celebrate' ) . '</div>' ); 
			} else {
				echo apply_atomic_shortcode( 'entry_byline', sprintf( __( 'on <a href="%s">[entry-published]</a> [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'celebrate' ), get_permalink()) . '</div>' ); 
			}
			?>			
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'celebrate' ) . '</span>', 'after' => '</p>' ) ); ?>
		</div><!-- .entry-summary -->
        
        <footer class="entry-footer">
			<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in: "] [entry-terms before="<br/>Tagged: "]', 'celebrate' ) . '</div>' ); ?>
		</footer><!-- .entry-footer -->

	<?php } ?>

</article><!-- .hentry -->
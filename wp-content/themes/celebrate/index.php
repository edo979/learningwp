<?php get_header(); // Loads the header.php template. ?>

	<div id="content" class="hfeed">

		<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>
		
        <div class="loop">
			<?php get_template_part( 'loop' ); // Loads the loop.php template. ?>
		</div>
		<?php 
		if (!is_singular()) {
			get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. 
		}		
		?>

	</div><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>
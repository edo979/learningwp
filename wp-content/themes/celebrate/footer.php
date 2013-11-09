			<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>


        </div><!-- #main -->

        <footer id="footer">

            <div class="wrap">

                <div class="footer-content">
                    <?php do_atomic( 'footer' ); // hybrid_footer ?>
                </div><!-- .footer-content -->

            </div>

        </footer><!-- #footer -->

    </div><!-- #container -->
	<?php wp_footer(); // wp_footer ?>
</body>
</html>
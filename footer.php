<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
            <div class="inner">
                <?php
                    if ( dynamic_sidebar('Footer Widgets') ) : else : endif;
                ?>
            </div>
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->


<?php wp_footer(); ?>



</body>
</html>


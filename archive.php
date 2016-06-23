<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

<div class="container">
	<div class="col-left">
		<div id="content" class="site-content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

                    <?php if ( have_posts() ) : ?>

						<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
						the_posts_pagination( array(
							'prev_text'          => __( 'Page', 'tpc' ),
							'next_text'          => __( 'Next 5 entries', 'tpc' ),
							'before_page_number' => '',
						) );
					else :
						get_template_part( 'content', 'none' );

					endif;
					?>

			</main><
		</div>
	</div>
</div>

<?php get_footer(); ?>

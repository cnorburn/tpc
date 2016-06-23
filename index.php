<?php

get_header(); ?>

<div class="container blog">
	<div class="col-left">
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php if ( have_posts() ) : ?>

						<?php if ( is_home() && ! is_front_page() ) : ?>
							<header>
								<h1 class="page-title"><?php single_post_title(); ?></h1>
							</header>
						<?php endif; ?>

                        <?php
                            if ( !is_single() ){
                                if (getCountry()=='uk') {
                                    ?>
                                    <p>Below you'll find some interesting articles about developments in the world of
                                        coaching and leadership
                                        development. Enjoy, and if you'd like to subscribe to posts, just <a
                                            href="/uk/join-mailing-list/">join our mailing list here</a></p>
                                    <?php
                                }else if(getCountry()=='nl'){
                                    ?>
                                    <p>Below you will find the latest blog posts from The Performance Coach.
                                        Many of these are taken from our UK site, <a href="/uk">www.theperformancecoach.com/uk</a>.
                                        <?php
                                }else if(getCountry()=='tr') {
                                    ?>
                                    <h2> Türkiye Bloglar-Türkçe</h2>
                                    <?php
                                }

                            }
                        ?>
                        <?php
						// Start the loop.
						while ( have_posts() ) : the_post();

							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );

						// End the loop.
						endwhile;

						// Previous/next page navigation.
						the_posts_pagination( array(
							'prev_text'          => __( 'Page', 'tpc' ),
							'next_text'          => __( 'Next 5 entries', 'tpc' ),
							'before_page_number' => '',
						) );

					// If no content, include the "No posts found" template.
					else :
						get_template_part( 'content', 'none' );

					endif;
					?>

				</main><!-- .site-main -->
			</div><!-- .content-area -->

		</div>
	</div>

	<div class="col-right">
		<div class="sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>

</div>

<?php get_footer(); ?>


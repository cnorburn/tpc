<?php
/*
Template Name: Full width
*/

get_header(); ?>

    <div class="home">
        <div class="container">
            <?php
            // Start the loop.
            while ( have_posts() ) : the_post();
                get_template_part( 'content', get_post_format() );
                // End the loop.
            endwhile;
            ?>
        </div>
    </div>

<?php get_footer(); ?>
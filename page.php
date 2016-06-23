<?php

get_header(); ?>

<div class="container">
    <div class="col-left">
        <div id="content" class="site-content">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
                        <?php if(function_exists('bcn_display')){
                            bcn_display();
                        }?>
                    </div>

                    <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();
                        get_template_part( 'content', get_post_format() );
                        // End the loop.
                    endwhile;
                    ?>
                </main>
            </div>
        </div>
    </div>

    <div class="col-right">
        <div class="sidebar">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
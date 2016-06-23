<?php
/**
 * Created by PhpStorm.
 * User: carl
 * Date: 25/02/2016
 * Time: 15:25
 */

add_action('wp_ajax_nopriv_get_posts_ajax_country', 'get_posts_ajax_country');
add_action('wp_ajax_get_posts_ajax_country', 'get_posts_ajax_country');

add_action('wp_ajax_nopriv_get_posts_ajax_language_by_country', 'get_posts_ajax_language_by_country');
add_action('wp_ajax_get_posts_ajax_language_by_country', 'get_posts_ajax_language_by_country');

add_action('wp_ajax_nopriv_get_posts_ajax_language', 'get_posts_ajax_language');
add_action('wp_ajax_get_posts_ajax_language', 'get_posts_ajax_language');


function get_posts_ajax_language_by_country(){

    header("Content-Type: text/html");

    if(!empty($_POST['country_id']) && !empty($_POST['language_id'])) {
        switch_to_blog($_POST['country_id']);

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'tax_query'      => array(
                array(
                    'taxonomy'  => 'category',
                    'field'     => 'name',
                    'terms'     => sanitize_title( $_POST['language_id'] )
                )
            )
        );

        postOutputInTheLoop($args);

        restore_current_blog();
    }

    die();

}


function get_posts_ajax_country(){

    header("Content-Type: text/html");

    if(!empty($_POST['country_id'])) {
        switch_to_blog($_POST['country_id']);
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'orderby' => 'post_date',
            'order' => 'DESC'
        );
        postOutputInTheLoop($args);

        restore_current_blog();
    }

    die();
}


function postOutputInTheLoop($args){

    $posts = new WP_Query($args);

    if( $posts->have_posts() ) {
        echo('<ul class="dynamic-posts">');
        while ($posts->have_posts()) : $posts->the_post();
            ?>
            <li>
                <a href="<?php echo the_permalink(); ?>">
                    <div class="image">
                        <img src="<?php echo getImg(get_the_ID()); ?>" alt=""/>
                    </div>
                    <div class="text">
                        <h1><?php the_title()?></h1>
                        <?php the_excerpt () ?>
                        <!--<p>--><?php //echo substr(preg_replace("/\[caption .+?\[\/caption\]|\< *[img][^\>]*[.]*\>/i","",get_the_content(),1),0, 200);?><!--</p>-->
                    </div>
                </a>
            </li>

            <?php

        endwhile;

        echo('</ul>');
    }


}

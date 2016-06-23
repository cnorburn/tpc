<?php

if(!in_category('News') || is_single()){
    edit_post_link( __( 'Edit', 'tpc' ), '<span class="edit-link">', '</span>' );

    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php
        if ( is_single() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
        endif;
        ?>
    </header><!-- .entry-header -->

    <div class="meta">
        <span class="author"><?php the_author_posts_link(); ?></span>
        <?php
        $_date=get_the_date('l, F j, Y','','',false);
        $_time=get_the_time('g:i a');
        $_datePosted=$_date.' at '.$_time;
        ?>
        <span class="date"><?php echo $_datePosted ?></span>
    </div>

    <div class="entry-content">
        <?php
        /* translators: %s: Name of current post */
        the_content( sprintf(
            __( 'Continue reading %s', 'tpc' ),
            the_title( '<span class="screen-reader-text">', '</span>', false )
        ));

        wp_link_pages( array(
            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tpc' ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
            'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'tpc' ) . ' </span>%',
            'separator'   => '<span class="screen-reader-text">, </span>',
        ) );
        ?>
    </div>

    <div class="meta">
        <div id="social">
            <a href="<?php echo esc_url( get_permalink() ).'#comments';?>"><?php comments_number( 'post a comment', 'one comment', '% comments' ); ?></a>
            <span>|</span>
            <a href="#">Share article</a>
        </div>
        <div id="tags"><?php the_tags( 'Tagged: ', ', ', '<br />' ); ?> </div>
    </div>
    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;

    edit_post_link( __( 'Edit', 'tpc' ), '<span class="edit-link">', '</span>' );

    if ( is_single() ) {
        the_post_navigation(array(
            'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next', 'tpc') . '</span> ' .
                '<span class="screen-reader-text">' . __('Next post:', 'tpc') . '</span> ' .
                '<span class="post-title">%title</span>',
            'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous', 'tpc') . '</span> ' .
                '<span class="screen-reader-text">' . __('Previous post:', 'tpc') . '</span> ' .
                '<span class="post-title">%title</span>',
        ));
    }
    ?>

    </article>

<?php
}
?>







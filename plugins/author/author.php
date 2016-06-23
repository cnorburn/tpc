<?php
/*
Plugin Name: tpc ML Authors
Plugin URI: h
Description:
Version: 1.0
Author: tpc-ML
Author URI:
*/


function tpc_author_add_meta_box(){
    add_meta_box( 'tpc_author', 'Author', 'tpc_author', null, 'side','high');
}
add_action( 'add_meta_boxes','tpc_author_add_meta_box' );

function tpc_author($post){

    $author=get_post_meta( $post->ID, 'Author', true );
    $displayAuthor=get_post_meta( $post->ID, 'DisplayAuthor', true );

    global $wpdb;
    $prefixClass = new Prefixes();
    $prefixes = $prefixClass->getAllPrefixes();
    $authors=array();
    foreach ($prefixes as $prefix) {
        $meta = 'wp_' . $prefix->prefix . '_postmeta';
        $authors[] = $wpdb->get_results( 'select distinct(meta_value) from '.$meta.' where meta_key="Author" order by meta_value', OBJECT );
    }
    $authors= call_user_func_array('array_merge', $authors);
    $authors=array_unique($authors, SORT_REGULAR);

    ?>
    <select id="author">
        <option value="null">--no author--</option>
        <?php
            foreach($authors as $_author){
            if($_author->meta_value===$author){
                $selected='selected ';
            }else{
                $selected='';
            }
            echo ('<option value="'.str_replace(' ', '-',$_author->meta_value) .'"'.$selected.'> '.$_author->meta_value.'</option>');
    }
    ?>
    </select>
    <input type="button" id="authoradd" class="button" value="Save">
    <br />
    <input style="margin:10px 9px 13px 2px;" type="checkbox" class="display-author" value="author" <?php if($displayAuthor) echo 'checked' ?>>Display Author?</input>
    <h4>
        <a id="author-add-toggle" href="#author-add" class="hide-if-no-js">
            + Add Article Author					</a>
    </h4>
    <p id="author-add" style="display: none">
        <label>Add Article Author</label>
        <input type="text" name="newauthor" id="newauthor">
        <br />
        <input type="button" id="author-add-submit" class="button author-add-submit" value="Add Article Author">
    </p>


    <script>
        $=jQuery;
        var ajaxurl, wpAjax;
        $(function() {

            $('#author-add-toggle').click(function(e){
                e.preventDefault();
                $('#author-add').toggle();
            });


            $('#author-add-submit').click(function(e){
                e.preventDefault();

                var author=$('#tpc_author .inside #newauthor').val();

                if (author==""){
                    alert('Please enter the authors name.');
                    return;
                }

                var _data = {
                    action: 'add_new_article_author',
                    author: author,
                    post_id: <?php the_ID() ?>
                };

                console.log(_data)

                $.post(ajaxurl, _data, function(response) {
                    if(response==-1){
                        alert('Adding a New Article Author failed')
                    }else{
                        $('#tpc_author .inside #author').append($('<option/>', {
                            value: $('#tpc_author .inside #newauthor').val().replace(' ','-'),
                            text : $('#tpc_author .inside #newauthor').val()
                        }));
                        $('#tpc_author .inside #author option[value=' + author.replace(' ','-') +']').attr('selected','selected');
                    }
                });

            });


            $('#tpc_author .inside input#authoradd').click(function(e) {
                e.preventDefault();

                var author=($('#author').val()==='null') ? 'null' : $('#author option:selected').text();
                var action=(author==='null') ? 'remove_article_author' : 'add_new_article_author';

                var _data = {
                    action: action,
                    author: author,
                    post_id: <?php the_ID() ?>
                };

                $.post(ajaxurl, _data, function(response) {
//                    if(response==-1){
//                        alert('Adding an Article Author failed')
//                    }
                });

            });

            $('input.display-author').change(function() {

                var action=this.checked ? 'display_author' : 'hide_author';

                var _data = {
                    action: action,
                    post_id: <?php the_ID() ?>
                };

                $.post(ajaxurl, _data, function(response) {
                    if(response==-1){
                        alert('Displaying/Hiding author failed')
                    }
                });


            });


        });
    </script>




<?php


}
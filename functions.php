<?php

require_once('filter/filter.php');
require_once('filter/class/class-filter.php');
require_once('filter/class/class-countries.php');
require_once('filter/class/class-categories.php');
require_once('filter/class/class-prefixes.php');
require_once('filter/class/class-endpoints.php');
require_once('filter/class/class-posts.php');
require_once('plugins/site-country/site-country.php');
require_once('plugins/author/author.php');
require_once('templates/includes/get-posts-ajax.php');

add_theme_support( 'post-thumbnails' );
add_filter('show_admin_bar', '__return_false');

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

function tpc_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar Widgets', 'tpc' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'tpc' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Widgets', 'tpc' ),
        'id'            => 'colophon',
        'description'   => __( 'Add widgets here to appear in your footer.', 'tpc' ),
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'tpc_widgets_init' );


function tpc_jquery(){
    wp_enqueue_script( 'jquery' );

    wp_enqueue_script("slimmenu",get_template_directory_uri().'/js/jquery.slimmenu.js',array('jquery'));
    wp_enqueue_script("menu",get_template_directory_uri().'/js/menu.js',array( 'jquery' ));

    wp_enqueue_script("jquery-easing",get_template_directory_uri().'/js/jquery.easing.1.3.js',array( 'jquery' ));
}
add_action('wp_enqueue_scripts','tpc_jquery');

add_action( 'wp_enqueue_scripts', 'tpc_scripts' );
function tpc_scripts() {

    wp_enqueue_style( 'slimmenu', get_template_directory_uri() . '/css/slimmenu.css', array('tpc-style'));


    // Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'tpc-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'tpc-ie', get_template_directory_uri() . '/css/ie.css', array( 'tpc-style' ), '20141010' );
	wp_style_add_data( 'tpc-ie', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'tpc-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'tpc-style' ), '20141010' );
	wp_style_add_data( 'tpc-ie7', 'conditional', 'lt IE 8' );

	wp_enqueue_script( 'tpc-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'tpc-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20141212', true );
}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';

function register_my_menu() {
    register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

function getCountry(){
    return substr($_SERVER['REQUEST_URI'],1,2);
}

//add_action('after_setup_theme','remove_core_updates_1');
//function remove_core_updates_1(){
//    if(! current_user_can('update_core')){return;}
//    add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
//    add_filter('pre_option_update_core','__return_null');
//    add_filter('pre_site_transient_update_core','__return_null');
//}
//function remove_core_updates_2(){
//    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
//}
//add_filter('pre_site_transient_update_core','remove_core_updates_2');
//add_filter('pre_site_transient_update_plugins','remove_core_updates_2');
//add_filter('pre_site_transient_update_themes','remove_core_updates_2');
//remove_action('load-update-core.php','wp_update_plugins');
//add_filter('pre_site_transient_update_plugins','__return_null');


add_action('admin_menu', 'register_site_country_submenu');


function super_unique($array){
    $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

    foreach ($result as $key => $value){
        if ( is_array($value) ){
            $result[$key] = super_unique($value);
        }
    }

    return $result;
}

function getImg($id,$size = 'single-post-thumbnail'){

    //try for featured image first, if none get first attachment
    $image = wp_get_attachment_image_src( get_post_thumbnail_id($id), $size);
    if(!$image){
        $attachments = get_children(array('post_parent' => $id,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => 'ASC',
            'orderby' => 'menu_order ID'));

        foreach($attachments as $att_id => $attachment) {
            $image = wp_get_attachment_url($attachment->ID);
            //just the first image
            break;
        }

    }else{
        $image=$image[0];
    }


    return $image;

}

function date_compare($a, $b){

    if(empty($b['date']) || empty($a['date'])) return;

    $t1 = strtotime($b['date']);
    $t2 = strtotime($a['date']);
    return $t1 - $t2;
}


function custom_excerpt_more( $more ) {
    return '';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );



add_action( 'wp_ajax_add_new_article_author', 'saveArticleAuthor' );
function saveArticleAuthor(){

    if(!empty($_POST['post_id']) && !empty($_POST['author'])) {
        $res=saveNewArticleAuthor(($_POST['post_id']),($_POST['author']));
    }
    wp_die((!$res ? -1 : 0));
}

function saveNewArticleAuthor($id,$author){
    return update_post_meta($id, 'Author',trim($author));
}

add_action( 'wp_ajax_remove_article_author', 'removeArticleAuthor' );
function removeAuthor(){
    if(!empty($_POST['post_id']) && !empty($_POST['author'])) {
        $res=removeArticleAuthor(($_POST['post_id']));
    }
    wp_die((!$res ? -1 : 0));
}

function removeArticleAuthor($id){
    return delete_post_meta($id, 'Author');
}

add_action( 'wp_ajax_display_author', 'displayAuthor' );
function displayAuthor(){
    if(!empty($_POST['post_id'])) {
        $res=setDisplayAuthor(($_POST['post_id']));
    }
    wp_die((!$res ? -1 : 0));
}

function setDisplayAuthor($id){
    return update_post_meta($id, 'DisplayAuthor',true);
}

add_action( 'wp_ajax_hide_author', 'hideAuthor' );
function hideAuthor(){
    if(!empty($_POST['post_id'])) {
        $res=setHideAuthor(($_POST['post_id']));
    }
    wp_die((!$res ? -1 : 0));
}

function setHideAuthor($id){
    return delete_post_meta($id, 'DisplayAuthor');
}


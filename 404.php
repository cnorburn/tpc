<?php

    if(strpos($_SERVER['REQUEST_URI'],'storage/')) {
        //AWS Cloud Storage
        $link = basename($_SERVER['REQUEST_URI']);
        if (isset($link)) {
            $link = str_replace('%20', '-', $link);
            wp_redirect('http://s3-eu-west-1.amazonaws.com/tpc-media/uk/files/' . $link, 301);
            exit;
        }
    }elseif(substr($_SERVER['REQUEST_URI'],3,1) != '/' || getCountry() == 'br'){
        //Old Brasil Links
        $link = basename($_SERVER['REQUEST_URI']);
        if (isset($link)) {
            wp_redirect('/uk/' . $link, 301);
            exit;
        }
    }elseif(strpos($_SERVER['REQUEST_URI'],'.html')){
        //Old .com html pages
        $base=str_replace('.html', '/',basename($_SERVER['REQUEST_URI']));
        if (isset($base)) {
            wp_redirect($base, 301);
            exit;
        }
    }elseif(strpos($_SERVER['REQUEST_URI'],'-html') && !strpos($_SERVER['REQUEST_URI'],'/blog/')){
        //Old Blog pages
        $base='/uk/blog/'.basename($_SERVER['REQUEST_URI']);
        if (isset($base)) {
            wp_redirect($base, 301);
            exit;
        }
//    }elseif(strpos($_SERVER['REQUEST_URI'],'uk/') && !strpos($_SERVER['REQUEST_URI'],'blog/')){
        //Page not found, try it with /blog
        //Need to try this just once though, at the moment falling into a redirect loop
        //Find a way to check link valid before 301 redirect, some how....

//        $base='/uk/blog/'.basename($_SERVER['REQUEST_URI']);
//        if (isset($base)) {
//            wp_redirect($base, 301);
//            exit;
//        }


//    }else{

//        $userInfo = geoip_detect2_get_info_from_current_ip();
//
//        switch ($userInfo->country->isoCode){
//            case 'GB':
//                wp_redirect('/uk/', 301);
//                break;
//            case 'IT':
//                wp_redirect('/it/', 301);
//                break;
//            case 'NL':
//                wp_redirect('/nl/', 301);
//                break;
//            case 'US':
//                wp_redirect('/us/', 301);
//                break;
//            case 'TR':
//                wp_redirect('/tr/', 301);
//                break;
//            case 'BE':
//                wp_redirect('/be/', 301);
//                break;
//            default:
//                wp_redirect('/welcome', 301);
//        }

//        exit;

    }


    get_header();


?>

<div class="container">

    <?php echo (substr($_SERVER['REQUEST_URI'],3,1)); ?>

    <div class="col-left">
        <div id="content" class="site-content">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <p>The page <?php echo($_SERVER['REQUEST_URI']); ?> could not be located on this website.</p>
                    <p>We recommend using the navigation bar to get back on track within our site. Thank you!</p>
                    <p><strong><a href="/<?php echo(getCountry());?>">Return to the Front Page »</a></strong></p>
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



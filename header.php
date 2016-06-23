<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <?php the_title( '<title>', ' - The Performance Coach - Executive Coaching, London &amp; UK | Performance Coaching | Coaching MSc, EMCC &amp; ILM Qualifications</title>' );?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/x fn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Muli" />
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>

	<?php wp_head(); ?>

</head>

<!--[if IE 8 ]>
<body class="ie8 <?php echo getCountry()?>">
<![endif]-->
<!--[if !IE]>-->
<body class="<?php echo getCountry()?>">
<!--<![endif]-->


<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
    <?php
        wp_nav_menu( array( 'theme_location' => 'header-menu' , 'container_class' => 'nav-desktop' ));
        wp_nav_menu( array( 'theme_location' => 'header-menu', 'container_class' => 'nav-mobile','container_id' => 'nav-mobile',
            'items_wrap' => '<ul id="%1$s" class="%2$s slimmenu">%3$s</ul>'));
    ?>
    <div class="banner">
        <header>
            <div>
                <a href="/<?php echo getCountry()?>">
                    <span class="primary">TPC<span></span></span>
                    <span class="secondary"><span>The</span> Performance <span>Coach</span></span>
                </a>
            </div>
        </header>
    </div>
</nav>

<?php

?>

<div id="page">



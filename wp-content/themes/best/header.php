<?php

/* =============================================================
 * header.php
 * =============================================================
 * Displays all of the <head> section and everything up to the 
 * end of the <div class="row"> containing the site title and 
 * <hr> below for responsive spacing
 * ============================================================= */
 
?>
<!doctype html>
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>>    <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?php bloginfo( 'name' ); ?><?php wp_title( '|' ); ?></title>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
    <header>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <span class="navigation-label hidden-desktop">Navigation</span>
                    <nav class="nav-collapse clearfix" role="navigation">
                        <h3 class="visually-hidden">Main Menu</h3>
                        <a class="visually-hidden focusable" href="#main_content" title="Skip to Primary Content">Skip to Primary Content</a>
                        <?php
                            // Topbar navigation
                            wp_nav_menu( array(
                                'theme_location' => 'nav_topbar',
                                'container' => false,
                                'menu_class' => 'nav sf-menu',
                                'fallback_cb' => 'topbar_nav_fallback',
                                'depth' => 4 )
                            );
                        ?>
                        <?php get_search_form(); ?>
                    </nav>
                </div><!-- end .container -->
            </div><!-- end .navbar-inner -->
        </div><!-- end .navbar .navbar-fixed-top -->
    </header>
    <div class="container container-main">
        <div class="row">
            <div class="span12 site-heading">
                <div class="row">
                    <div class="span8 clearfix">
                        <?php if ( of_get_option( 'site_heading', 'no entry' ) ) : ?>
                        <div class="name-logo">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                                <img src="<?php echo ( of_get_option( 'site_heading_img', 'no entry' ) ) ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                            </a>
                        </div>
                        <?php else : ?>
                        <div class="name-text">
                            <hgroup>
                                <h1>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
                                </h1>
                            </hgroup>
                        </div>
                        <?php endif; ?>
                    </div><!-- end .span8 -->
                    <?php if ( of_get_option( 'best_display_header_banner_area', 'no entry' ) ) : ?>
                    <div class="span4 header-banner-area">
                        <?php echo of_get_option( 'best_header_banner_area', 'no entry' ); ?>
                    </div>
                    <?php endif; ?>
                </div><!-- end .row -->
                <hr class="hr-row-divider" style="clear: both;">
            </div><!-- end .span12 -->
        </div><!-- end .row -->
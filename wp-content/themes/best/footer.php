<?php
/* =============================================================
 * footer.php
 * =============================================================
 * Contains the closing of <div class=".container .container-main">
 * and all content after
 * ============================================================= */
?>

    </div><!-- end .container.container-main -->
    
    <?php if ( of_get_option( 'best_display_twitter', 'no entry' ) ) : ?>
    
    <div class="container container-main twitter-feed">
        <div class="row">
            <div class="span12">
            
                <?php
                    $twitter_user = of_get_option( 'best_twitter', 'no entry' );
                    wp_echo_twitter( $twitter_user );
                ?>
                
            </div>
        </div>
    </div>
    
    <?php endif; ?>
    
    <footer role="contentinfo">
    
        <?php if ( of_get_option( 'best_display_footer_top', 'no entry' ) ) : ?>
        
        <div class="container container-main footer-top">
            <div class="row widget-area">
                <div class="span3">
                    
                    <?php if ( !dynamic_sidebar( 'sidebar-5' ) ) : echo '&nbsp;'; endif; ?>
                    
                </div>
                <div class="span3">
                    
                    <?php if ( !dynamic_sidebar( 'sidebar-6' ) ) : echo '&nbsp;'; endif; ?>
                    
                </div>
                <div class="span3">
                    
                    <?php if ( !dynamic_sidebar( 'sidebar-7' ) ) : echo '&nbsp;'; endif; ?>
                    
                </div>
                <div class="span3">
                    
                    <?php if ( !dynamic_sidebar( 'sidebar-8' ) ) : echo '&nbsp;'; endif; ?>
                    
                </div>
            </div><!-- end .row -->
        </div><!-- end .container.container-main .footer-top -->
        
        <?php endif; ?>
        <?php if ( of_get_option( 'best_display_footer_bottom', 'no entry' ) ) : ?>
        
        <div class="container container-main footer-bottom">
            <div class="row">
                <div class="span5">
                    <p><?php echo ( of_get_option( 'best_footer_bottom_tagline', 'no entry' ) ); ?></p>
                </div>
                <div class="span7 clearfix">
                    <nav class="clearfix" role="navigation">
                    
                        <?php
                            // Footer navigation
                            wp_nav_menu( array(
                                'theme_location' => 'nav_footer',
                                'container' => false,
                                'fallback_cb' => 'footer_nav_fallback',
                                'depth' => 1 )
                            );
                        ?>
                        
                    </nav>
                </div>
            </div><!-- end .row -->
        </div><!-- end .container.container-main .footer-bottom -->
        
        <?php endif; ?>
        
    </footer>
      
<?php wp_footer(); ?>
</body>
</html>
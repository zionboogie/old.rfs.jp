<?php
/* =============================================================
 * Template Name: Home
 * =============================================================
 * Page template used for setting the front page
 * ============================================================= */
?>

<?php get_header(); ?>
        <?php if ( of_get_option( 'best_display_intro_text', 'no entry' ) ) : ?>
        <div class="row">
            <div class="span12">
                <p class="hero-p"><?php echo ( of_get_option( 'best_intro_text', 'no entry' ) ) ?></p>
                <hr class="hr-row-divider">
            </div><!-- end .span12 -->
        </div><!-- end .row -->
        <?php endif; ?>
        <div id="main_content" role="main">
            <div class="row">
                <div class="span9">
                    <div class="flexslider">
                        <ul class="slides">
                        
                            <?php get_template_part( 'loop', 'slides' ); ?>
                            
                        </ul>
	                </div>
	                <hr class="hr-row-divider">
	            </div><!-- end .span9 -->

                <?php // Include sidebar for static homepage, which includes widget area to the right of the slider, widget row below slider, and widget area to the left of the blog ?>
                <?php get_sidebar( 'home' ); ?>
                
                <?php get_template_part( 'loop', 'home' ); ?>
                
            </div><!-- end .row -->
        </div><!-- end #main_content -->
<?php get_footer(); ?>
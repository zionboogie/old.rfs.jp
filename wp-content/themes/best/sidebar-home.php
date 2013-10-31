<?php

/* =============================================================
 * sidebar-home.php
 * =============================================================
 * Displays the set of widget areas available for editing in the
 * #main_content section of template-home.php
 * ============================================================= */
 
?>

    <div class="span3 widget-area">
    
        <?php dynamic_sidebar( 'sidebar-2' ); ?>
        
    </div><!-- end .span3 -->
</div><!-- end .row -->

<?php if ( of_get_option( 'best_display_homepage_widget_row', 'no entry' ) ) : ?>

    <div class="row widget-area">
    
        <?php dynamic_sidebar( 'sidebar-3' ); ?>
        
    </div><!-- end .row -->
    
<?php endif; ?>

<div class="row blog-three-up">
    <div class="span3 widget-area">
    
        <?php dynamic_sidebar( 'sidebar-4' ); ?>
        
    </div><!-- end .span3 -->
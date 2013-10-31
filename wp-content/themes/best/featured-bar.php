<?php
/* =============================================================
 * featured-bar.php
 * =============================================================
 * Displays a notification bar on certain pages when activated
 * ============================================================= */
?>

<?php if ( of_get_option( 'best_display_featured_bar' ) ) : ?>
<div class="row">
    <div class="span12">
        <div class="featured-bar">
            <p class="hero-p"><?php echo of_get_option( 'best_featured_bar' ); ?></p>
        </div>
    </div>
</div><!-- end .row -->
<?php endif; ?>
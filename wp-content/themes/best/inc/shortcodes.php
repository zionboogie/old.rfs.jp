<?php

/* =============================================================
 * Shortcode for YouTube embeds
 * ============================================================= */

add_shortcode( 'youtube', 'youtube' );

function youtube($atts) {
    extract( shortcode_atts(
        array(
            'src'    => '',
            'width'  => 560,
            'height' => 315
        ), $atts )
    );
    return
        '<div class="video-container">
             <iframe width="' . $width . '" height="' . $height . '" src="' . $src . '?wmode=transparent"></iframe>
         </div>';
}



/* =============================================================
 * Shortcode for Vimeo embeds
 * ============================================================= */

add_shortcode( 'vimeo', 'vimeo' );

function vimeo($atts) {
    extract( shortcode_atts(
        array(
            'src'    => '',
            'width'  => 500,
            'height' => 282
        ), $atts )
    );
    return
        '<div class="video-container">
             <iframe src="' . $src . '" width="' . $width . '" height="' . $height . '"></iframe>
         </div>';
}



/* =============================================================
 * Shortcode for regular buttons
 * ============================================================= */

add_shortcode( 'button', 'button' );

function button($atts) {
    extract( shortcode_atts(
        array(
            'link'  => '#',
            'text'  => 'Use the "text" attribute to change me!',
            'title' => '',
            'color' => '',
            'size'  => ''
        ), $atts)
    );
    $output = '<a href="' . $link . '" class="btn';
    if ( $color ) { $output .= ' btn-' . $color; }
    if ( $size )  { $output .= ' btn-' . $size; }
    $output .= '" ';
    if ( $title ) { $output .= 'title="' . $title . '"'; }
    $output .= '>' . $text . '</a>';
    return $output;
}



/* =============================================================
 * Shortcode for social buttons
 * ============================================================= */

add_shortcode( 'social', 'social' );

function social($atts) {
    extract( shortcode_atts(
        array(
            'link'    => '#',
            'text'    => 'I\'m a social network link!',
            'title'   => '',
            'network' => '',
            'notext'  => ''
        ), $atts)
    );
    $output = '<a href="' . $link . '" class="zocial';
    if ( $network ) { $output .= ' ' . $network; }
    if ( $notext === 'yes' )  { $output .= ' icon'; }
    $output .= '" ';
    if ( $title ) { $output .= 'title="' . $title . '"'; }
    $output .= '>' . $text . '</a>';
    return $output;
}



/* =============================================================
 * Shortcode for rows and columns
 * ============================================================= */

add_shortcode( 'row', 'row' );

function row( $atts, $content = null ) {
    return '<div class="row">' . do_shortcode( $content ) . '</div>';
}


add_shortcode( 'column', 'column' );

function column( $atts, $content = null ) {
    extract( shortcode_atts(
        array(
            'span' => ''
        ), $atts)
    );
    return '<div class="span' . $span . '">' . do_shortcode( $content ) . '</div>';
}



/* =============================================================
 * Shortcode for alerts
 * ============================================================= */

add_shortcode( 'alert', 'alert' );

function alert( $atts, $content = null ) {
    extract( shortcode_atts(
        array(
            'type' => ''
        ), $atts)
    );
    $output = '<div class="alert';
    if ( $type === 'danger' )  { $output .= ' alert-error'; }
    if ( $type === 'success' ) { $output .= ' alert-success'; }
    if ( $type === 'info' )    { $output .= ' alert-info'; }
    $output .= '">' . do_shortcode( $content ) . '</div>';
    return $output;
}

?>
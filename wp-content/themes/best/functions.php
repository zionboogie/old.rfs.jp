<?php

// Contains all functions essential to setting the theme
require 'inc/theme-setup.php';

// Sets up comments and pingbacks for the theme
require 'inc/comments-and-pingbacks.php';

// Registers all dynamic sidebar areas for the theme
require 'inc/register-sidebars.php';

// Registers custom post types for the theme
require 'inc/custom-posts.php';

// Registers custom shortcodes for the theme
include 'inc/shortcodes.php';

// Loads the Options Panel
// If you're loading from a child theme use stylesheet_directory instead of template_directory
if ( !function_exists( 'optionsframework_init' ) ) {
    define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/admin/' );
    require_once dirname( __FILE__ ) . '/inc/admin/options-framework.php';
}

?>
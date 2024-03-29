<?php

/**
 * Plugin Name: AddQuicktag
 * Plugin URI:  http://bueltge.de/wp-addquicktags-de-plugin/120/
 * Text Domain: addquicktag
 * Domain Path: /languages
 * Description: Allows you to easily add custom Quicktags to the html- and visual-editor.
 * Version:     2.4.1
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de
 * License:     GPLv2+
 * License URI: ./license.txt
 *
 * Add Quicktag Plugin class
 *
 * @since   2.0.0
 */
class Add_Quicktag {

	/**
	 * Option key - String
	 *
	 * @var string
	 */
	static private $option_string = 'rmnlQuicktagSettings';

	/**
	 * Use filter 'addquicktag_pages' for add custom pages
	 *
	 * @var array
	 */
	static private $admin_pages_for_js = array(
		'post.php',
		'post-new.php',
		'comment.php',
		'edit-comments.php',
		'widgets.php'
	);

	/**
	 * Use filter 'addquicktag_post_types' for add custom post_types
	 *
	 * @var array
	 */
	static private $post_types_for_js = array( 'comment', 'edit-comments', 'widgets' );

	/**
	 * @var string
	 */
	static private $plugin;

	/**
	 * Handler for the action 'init'. Instantiates this class.
	 *
	 * @since   2.0.0
	 * @access  public
	 * @return  \Add_Quicktag $instance
	 */
	public static function get_object() {

		static $instance;

		if ( NULL === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Constructor, init the functions inside WP
	 *
	 * @since   2.0.0
	 * @return  \Add_Quicktag
	 */
	private function __construct() {

		if ( ! is_admin() ) {
			return;
		}

		// get string of plugin
		self::$plugin = plugin_basename( __FILE__ );

		// on uninstall remove capability from roles
		register_uninstall_hook( __FILE__, array( 'Add_Quicktag', 'uninstall' ) );
		// on deactivate delete all settings in database
		// register_deactivation_hook( __FILE__, array('Add_Quicktag', 'uninstall' ) );

		// load translation files
		add_action( 'admin_init', array( $this, 'localize_plugin' ) );
		// on init register post type for addquicktag and print js
		add_action( 'init', array( $this, 'on_admin_init' ) );

		add_filter( 'quicktags_settings', array( $this, 'remove_quicktags' ), 10, 1 );
	}


	/**
	 * Include other files and print JS
	 *
	 * @since   07/16/2012
	 * @return  void
	 */
	public function on_admin_init() {

		if ( ! is_admin() ) {
			return NULL;
		}

		// Include settings
		require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'inc/class-settings.php';
		// Include solution for TinyMCE
		require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'inc/class-tinymce.php';

		foreach ( $this->get_admin_pages_for_js() as $page ) {
			add_action( 'admin_print_scripts-' . $page, array( $this, 'get_json' ) );
			add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Remove quicktags
	 *
	 * @since   08/15/2013
	 *
	 * @param   array $qtags_init the Buttons
	 *
	 * @type    string   id
	 * @type    array    buttons, default: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,fullscreen'
	 * @return  array    $qtags_init  the Buttons
	 */
	public function remove_quicktags( $qtags_init ) {

		// No core buttons, not necessary to filter
		if ( empty( $qtags_init[ 'buttons' ] ) ) {
			return $qtags_init;
		}

		if ( is_multisite() && is_plugin_active_for_network( self::$plugin ) ) {
			$options = get_site_option( self::$option_string );
		} else {
			$options = get_option( self::$option_string );
		}

		// No settings, not necessary to filter
		if ( empty( $options[ 'core_buttons' ] ) ) {
			return $qtags_init;
		}

		// get current screen, post type
		$screen = get_current_screen();

		// Convert string to array from default core buttons
		$buttons = explode( ',', $qtags_init[ 'buttons' ] );

		// loop about the options to check for each post type
		foreach ( $options[ 'core_buttons' ] as $button => $post_type ) {

			// if the post type is inside the settings array active, the remove qtags
			if ( is_array( $post_type ) && array_key_exists( $screen->id, $post_type ) ) {

				// If settings have key inside, then unset this button
				if ( FALSE !== ( $key = array_search( $button, $buttons ) ) ) {
					unset( $buttons[ $key ] );
				}
			}
		}

		// Convert new buttons array back into a comma-separated string
		$qtags_init[ 'buttons' ] = implode( ',', $buttons );
		$qtags_init[ 'buttons' ] = apply_filters( 'addquicktag_remove_buttons', $qtags_init[ 'buttons' ] );

		return $qtags_init;
	}

	/**
	 * Uninstall data in options table, if the plugin was uninstall via backend
	 *
	 * @since   2.0.0
	 * @return  void
	 */
	public function uninstall() {

		delete_site_option( self::$option_string );
	}

	/**
	 * Print json data in head
	 *
	 * @since   2.0.0
	 * @return  void
	 */
	public function get_json() {
		global $current_screen;

		if ( ! in_array(
				$current_screen->id,
				$this->get_post_types_for_js()
			) &&
			isset( $current_screen->id )
		) {
			return NULL;
		}

		if ( is_multisite() && is_plugin_active_for_network( $this ->get_plugin_string() ) ) {
			$options = get_site_option( self::$option_string );
		} else {
			$options = get_option( self::$option_string );
		}

		if ( empty( $options[ 'buttons' ] ) ) {
			$options[ 'buttons' ] = '';
		}

		// allow change or enhance buttons array
		$options[ 'buttons' ] = apply_filters( 'addquicktag_buttons', $options[ 'buttons' ] );
		// hook for filter options
		$options = apply_filters( 'addquicktag_options', $options );

		if ( ! $options ) {
			return NULL;
		}

		if ( 1 < count( $options[ 'buttons' ] ) ) {
			// sort array by order value
			$tmp = array();
			foreach ( $options[ 'buttons' ] as $order ) {
				if ( isset( $order[ 'order' ] ) ) {
					$tmp[ ] = $order[ 'order' ];
				} else {
					$tmp[ ] = 0;
				}
			}
			array_multisort( $tmp, SORT_ASC, $options[ 'buttons' ] );
		}

		?>
		<script type="text/javascript">
			var addquicktag_tags = <?php echo json_encode( $options ); ?>,
				addquicktag_post_type = <?php echo json_encode( $current_screen->id ); ?>,
				addquicktag_pt_for_js = <?php echo json_encode( $this->get_post_types_for_js() ); ?>;
		</script>
	<?php
	}

	/**
	 * Enqueue Scripts for plugin
	 *
	 * @internal param string $where
	 *
	 * @since    2.0.0
	 * @access   public
	 * @return  void
	 */
	public function admin_enqueue_scripts() {

		global $current_screen;

		if ( ! in_array(
				$current_screen->id,
				$this->get_post_types_for_js()
			) &&
			isset( $current_screen->id )
		) {
			return NULL;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		if ( version_compare( $GLOBALS[ 'wp_version' ], '3.3alpha', '>=' ) ) {
			wp_enqueue_script(
				self::get_textdomain() . '_script',
				plugins_url( '/js/add-quicktags' . $suffix . '.js', __FILE__ ),
				array( 'jquery', 'quicktags' ),
				'',
				TRUE
			);
			// Load only for WPs, there version is smaller then 3.2
		} else {
			wp_enqueue_script(
				self::get_textdomain() . '_script',
				plugins_url( '/js/add-quicktags_32' . $suffix . '.js', __FILE__ ),
				array( 'jquery', 'quicktags' ),
				'',
				TRUE
			);
		}
		// Alternative to JSON function
		// wp_localize_script( self :: get_textdomain() . '_script', 'addquicktag_tags', get_option( self :: $option_string ) );
	}

	/**
	 * Localize_plugin function.
	 *
	 * @uses    load_plugin_textdomain, plugin_basename
	 * @access  public
	 * @since   2.0.0
	 * @return  void
	 */
	public function localize_plugin() {

		load_plugin_textdomain( $this->get_textdomain(), FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * return plugin comment data
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @param  $value string, default = 'TextDomain'
	 *                Name, PluginURI, Version, Description, Author, AuthorURI, TextDomain, DomainPath, Network, Title
	 *
	 * @return string
	 */
	public function get_plugin_data( $value = 'TextDomain' ) {

		static $plugin_data = array();

		// fetch the data just once.
		if ( isset( $plugin_data[ $value ] ) ) {
			return $plugin_data[ $value ];
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		$plugin_data = get_plugin_data( __FILE__ );

		return empty( $plugin_data[ $value ] ) ? '' : $plugin_data[ $value ];
	}

	/**
	 * Return string of plugin
	 *
	 * @since   2.0.0
	 * @return  string
	 */
	public function get_plugin_string() {

		return self::$plugin;
	}

	/**
	 * Get Post types with UI to use optional the quicktags
	 *
	 * @since   08/1/2013
	 * @return  Array
	 */
	private function get_post_types() {

		// list only post types, there was used in UI
		$args       = array( 'show_ui' => TRUE );
		$post_types = get_post_types( $args, 'names' );
		// simplify the array
		$post_types = array_values( $post_types );
		// merge with strings from var
		$post_types = array_merge( $post_types, self::$post_types_for_js );

		return $post_types;
	}

	/**
	 * Return allowed post types for include scripts
	 *
	 * @since   2.1.1
	 * @access  public
	 * @return  Array
	 */
	public function get_post_types_for_js() {

		return apply_filters( 'addquicktag_post_types', $this->get_post_types() );
	}

	/**
	 * Return allowed post types for include scripts
	 *
	 * @since   2.1.1
	 * @access  public
	 * @return  Array
	 */
	public function get_admin_pages_for_js() {

		return apply_filters( 'addquicktag_pages', self::$admin_pages_for_js );
	}

	/**
	 * Return textdomain string
	 *
	 * @since   2.0.0
	 * @access  public
	 * @return  string
	 */
	public function get_textdomain() {

		return self::get_plugin_data( 'TextDomain' );
	}

	/**
	 * Return string for options
	 *
	 * @since   2.0.0
	 * @return  string
	 */
	public function get_option_string() {

		return self::$option_string;
	}


} // end class

if ( function_exists( 'add_action' ) && class_exists( 'Add_Quicktag' ) ) {
	add_action( 'plugins_loaded', array( 'Add_Quicktag', 'get_object' ) );
} else {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

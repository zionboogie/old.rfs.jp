<?php
/*
Plugin Name: Cockpit
Plugin URI:http://wordpress.org/extend/plugins/cockpit/
Description: 記事を新たに公開した時に、サイトの更新のお知らせをTwitterに自動的に投稿することができます。
Version: 1.0.7
Author: JustSystems
Author URI:http://web-cockpit.jp/
*/

define( 'CP_PLUGIN_DIR', WP_PLUGIN_DIR.'/cockpit' );
define( 'CP_PLUGIN_URL', WP_PLUGIN_URL.'/cockpit' );

load_plugin_textdomain('cockpit', false, basename( dirname( __FILE__ ) ) . '/languages' );

add_action( 'admin_menu' , 'cockpit_option' );
 
function cockpit_option() {
	$icon_url = CP_PLUGIN_URL.'/image/admin/menu_cockpit.png';
	add_menu_page( 'cockpit', 'コックピット', 'administrator', 'cockpit_main', 'cockpit_admin_page', $icon_url, 4 );
}

add_action('init', 'cockpit_cockpit_init');

function cockpit_cockpit_init(){
	if(!class_exists('CockpitManager')){
		require_once CP_PLUGIN_DIR.'/cockpit_service.php';
		require_once CP_PLUGIN_DIR.'/cockpit_service_ex.php';
		$cockpitManager = new CockpitManagerCP('cockpit_main'); 
		$cockpitManager->cockpit_init();
	}
}

add_action( 'publish_future_post', 'cockpit_future_publish_hook');

function cockpit_future_publish_hook($post_id) {
	if(!class_exists('CockpitManager')){
		require_once CP_PLUGIN_DIR.'/cockpit_service.php';
		require_once CP_PLUGIN_DIR.'/cockpit_service_ex.php';
		$cockpitManager = new CockpitManagerCP('cockpit_main'); 
		if(method_exists($cockpitManager, 'cockpit_publish_post_hook')) {
			$cockpitManager->cockpit_publish_post_hook($post_id, true);
		}
	}
}

function cockpit_admin_page() {
	$cockpitManager = new CockpitManagerCP('cockpit_main'); 
	$cockpitManager->cockpit_admin_home();
}

?>
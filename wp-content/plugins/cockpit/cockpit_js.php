<?php

require('../../../wp-config.php');
$wp->init();
$wp->parse_request();
$wp->query_posts();
$wp->register_globals();
require_once('cockpit_service.php');

ini_set( 'display_errors', 0 );

$post_title = $_POST['post_title'];
$post_url = $_POST['post_url'];

$cpmanager = new CockpitManager;
echo $cpmanager->cockpit_generate_twitter_text($post_title, $post_url, false);

?>
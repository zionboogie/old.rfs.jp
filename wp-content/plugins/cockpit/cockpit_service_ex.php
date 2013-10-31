<?php

class CockpitManagerCP extends CockpitManager {

function cockpit_plugin_update() {
?>
<?php
	$update_plugins = get_plugin_updates();
	foreach( (array) $update_plugins as $update_plugin ) {
		if('cockpit' === $update_plugin->update->slug) {
?>
<form method="post" action="update-core.php?action=do-plugin-upgrade" name="upgrade-plugins">
<?php wp_nonce_field('upgrade-core'); wp_nonce_field('upgrade-core'); ?>
<input type="hidden" name="checked[]" value="cockpit/cockpit.php"/><div class="submit cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ).'/image/admin/eyecatch2.png';?>" class="hpb_eyecatch">コックピット の更新があります。<input id="upgrade-plugins" class="button-primary" type="submit" value="今すぐ更新する" name="upgrade" /></div>	</form>	
<?php
		}
	}
}
	
}

?>
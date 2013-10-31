jQuery(document).ready(function(){
	var isCockpitOpened = false;
	jQuery("#publish[value=<?php _e( 'Publish' ); ?>]").click( function(e){
		if(isCockpitOpened)
			return;
		isCockpitOpened = true;
		e.preventDefault();
		var div_dialog = jQuery('<div id="dialog_cockpit_post_confirm" class="ui-dialog" ><p>公開したことをコックピットでSNSに投稿しましょう。</p><p>投稿するには、①現在のページの[Twitterに投稿]をクリックするか、②投稿を表示をクリックしてページを確認後、ページ上部の[コックピットで投稿]をクリックします。こちらはキャプチャ付き。</p><p>※コックピットの解析結果に投稿が反映されるまで１時間程度かかります。</p><form id="agree-form"><input type="checkbox" id="cockpit_nolongeropen">次回から表示しない</input></form>');
		div_dialog.dialog({
			autoOpen: false,
			title: 'コックピット',
			closeOnEscape: true,
			modal: false,
			buttons: {
				"OK": function(){
					jQuery(this).dialog('close');
					if(jQuery("#cockpit_nolongeropen").is(':checked')){
						jQuery("#post").append('<input type="hiddeen" name="cockpit_openguide_manual" value="1" />');
					}
					e.target.click();
				}
			}
		});
		div_dialog.dialog('open');
	});
});
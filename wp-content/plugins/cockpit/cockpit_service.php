<?php

class CockpitManager {

private $guidance;
private $title_icon;

function __construct($guidance='', $title_icon=''){
	$this->guidance = $guidance;
	$this->title_icon = $title_icon;
}

function cockpit_init()
{
	// no longer open sns post guide
	if(isset($_POST['cockpit_openguide_auto']))
	{
		update_option('cockpit_openguide_auto','0');
	} else if(isset($_POST['cockpit_openguide_manual'])) {
		update_option('cockpit_openguide_manual','0');
	}
	
	load_plugin_textdomain('cockpit', false, basename( dirname( __FILE__ ) ) . '/languages' );

	if( $this->is_active_cockpit_acount() ) {
		add_action( 'wp_head', array($this, 'cockpit_head') );
		add_filter( 'manage_posts_columns', array($this, 'cockpit_add_post_columns_name') );
		add_action( 'manage_posts_custom_column', array($this, 'cockpit_add_column'), 10, 2);
		add_filter( 'manage_pages_columns', array($this, 'cockpit_add_page_columns_name'));
		add_action( 'manage_pages_custom_column', array($this, 'cockpit_add_page_column'), 10, 2);
		add_action( 'admin_menu' , array($this, 'cockpit_add_meta_box') );
		if( is_user_logged_in() ){
			$user = get_userdata( get_current_user_id() );
			$user_level = (int) $user->user_level;
			if( $user_level >= 2 ){
				add_action( 'admin_bar_menu', array($this, 'cockpit_add_adminbarmenu'), 500 );
			}
		}
		add_action( 'admin_print_scripts', array( $this, 'cockpit_before_publishing'), 20);
	}
	
	//view guidance
	if($this->guidance!==''){
		if( !$this->is_active_cockpit_acount() && stristr(basename($_SERVER['REQUEST_URI']),'plugins.php')) {
			add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".sprintf(__('コックピットをご利用いただくには、コックピットの <a href="%s">アカウント設定</a> が必要です。', 'cockpit'), admin_url('admin.php?page='.$this->guidance))."</p></div>';" ) );
		}
	}
	// post publish hoook
	add_action( 'publish_post', array( $this, 'cockpit_publish_post_hook') );
	// page publish hoook
	add_action( 'publish_page', array( $this, 'cockpit_publish_post_hook') );
	// custom post publish hook
	$arguments = array(
	'public'   => true,
	'_builtin' => false
	);
	$all_post_types = get_post_types( $arguments, 'names', 'and' );
	foreach ( $all_post_types as $one_post_type ) {
		add_action( 'publish_' . $one_post_type, array( $this, 'cockpit_publish_post_hook') );
	}
}

function cockpit_plugin_update() {

}

function cockpit_publish_post_hook($post_id, $future_post = false)
{
	if(!$this->is_active_cockpit_acount()){
		return;
	}
	if(isset($_POST['cockpit_cancel_auto_post']))
	{
		return;
	}
	$post = get_post($post_id);
	if( $future_post || ($post && $_POST['post_status'] == 'publish' && $_POST['original_post_status'] != 'publish' )) {
		$auto_tweet = get_option('cockpit_auto_tweet', 1 );
		$token = $this->cockpit_get_token($error_token);
		if($error_token === '' ){
			$twitter_info = $this->cockpit_get_twitter_info($token, $error);
			$twitter_active = false;
			if($error === '' && count($twitter_info) != 0){
				$twitter_active = true; 
			}
			if(!($auto_tweet && $twitter_active)){
				return;
			}
			if($this->cockpit_tweet($token, $post->post_title, esc_url( get_permalink($post_id) ), $error)){
				update_post_meta($post_id, '_cockpit_lasttweet_status', __('投稿済み', 'cockpit'));
				update_post_meta($post_id, '_cockpit_lasttweet', date_i18n('Y年m月d日 H:i'));
			} else {
				update_post_meta($post_id, '_cockpit_lasttweet_status', __('エラー', 'cockpit'));
				update_post_meta($post_id, '_cockpit_lasttweet', date_i18n('Y年m月d日 H:i'));
			}
		} else {
			update_post_meta($post_id, '_cockpit_lasttweet_status', __('エラー', 'cockpit'));
			update_post_meta($post_id, '_cockpit_lasttweet', date_i18n('Y年m月d日 H:i'));
		}
	}
}

function cockpit_before_publishing() {
	if(!is_admin()){
		return;
	}
	$token = $this->cockpit_get_token($error_token);
	if($error_token !== '' || $token === ''){
		return;
	}
	wp_enqueue_style('cockpit_style', plugins_url( '', __FILE__ ).'/cockpit_style.css');
	$auto_tweet = get_option('cockpit_auto_tweet', 1 );
	$twitter_info = $this->cockpit_get_twitter_info($token, $error);
	$twitter_active = false;
	if($error === '' && count($twitter_info) != 0){
		$twitter_active = true; 
	}
	if($auto_tweet && $twitter_active){
		if(get_option('cockpit_openguide_auto', 1 ) == 0) {
			return;
		}
?>
<script><!--

function cockpit_post_sns_confirm(tw_message, e) {
	var div_dialog = jQuery('<div class="ui-dialog" ><h3>以下の内容で自動投稿します。よろしいですか？</h3><div class="subbox"><h4>投稿内容</h4><div class="cockpit_tw_preview">'+tw_message+'</div><p><ul><li>投稿内容の変更や自動投稿の 有効／無効 の切り替えは、コックピットの設定画面で行えます。</li><li>投稿による集客効果の解析結果が、コックピットサービスの解析結果画面に反映されるまで、１時間程度かかります。</li></ul></div><form id="agree-form"><div id="cockpit_confirm_footerbox"><input type="checkbox" id="cockpit_nolongeropen">次回からこの画面を表示しない</input></div></form></div>');
	div_dialog.dialog({
		autoOpen: false,
		title: 'コックピット',
		dialogClass: 'dialog_cockpit_post_confirm wp-dialog',
		closeOnEscape: false,
		width: 600,
		height: 515,
		modal: true,
		draggable: false,
		resizable: false,
		buttons: [ {
			text : "OK",
			"class": 'button-primary',
			click : function(){
				if(jQuery("#cockpit_nolongeropen").is(':checked')){
					jQuery("#poststuff").append('<input type="hidden" name="cockpit_openguide_auto" value="1" />');
				}
				jQuery(this).dialog('close');
				e.target.click();
			}},{
			text : "キャンセル",
			"class": 'button-secondary',
			click : function(){
				$post_obj = jQuery("#poststuff");
				$post_obj.append('<input type="hiddeen" name="cockpit_cancel_auto_post" value="1" />');
				if(jQuery("#cockpit_nolongeropen").is(':checked')){
					$post_obj.append('<input type="hiddeen" name="cockpit_openguide_auto" value="1" />');
				}
				jQuery(this).dialog('close');
				e.target.click();
			}
		} ]
	});
	div_dialog.dialog('open');
}

jQuery( function() {
	var isCockpitOpened = false;
	jQuery("input#publish").click( function(e){
		if(jQuery(this).val() != '<?php _e( 'Publish' ); ?>')
			return;
		if(isCockpitOpened)
			return;
		isCockpitOpened = true;
		e.preventDefault();
		var post_title = jQuery('#title').val();
		var post_url = '<span class=cockpit_tw_preview_url> ここにURLが入ります </span>';
		var getTWMSG =  '<?php echo plugins_url( '', __FILE__ ); ?>/cockpit_js.php';
		var postdata = "post_title="+post_title+"&post_url="+post_url;
		jQuery.ajax({
			async: false,
			type: 'POST',
			url: getTWMSG,
			data: postdata,
			success: function(data) {
				cockpit_post_sns_confirm(data, e);
			}
		});
	});
});
//-->
</script>
<?php
} else {
		if(get_option('cockpit_openguide_manual', 1) == 0) {
			return;
		}
?>
<script><!--
jQuery( function() {
	var isCockpitOpened = false;
	jQuery("input#publish").click( function(e){
		if(jQuery(this).val() != '<?php _e( 'Publish' ); ?>')
			return;
		if(isCockpitOpened)
			return;
		isCockpitOpened = true;
		e.preventDefault();
		var div_dialog = jQuery('<div class="ui-dialog" ><h3>公開したことをコックピットからSNSに投稿しましょう。</h3><div class="subbox">次のいずれかの方法でSNSに投稿できます。<div class="methodul"><div class="methodli" ><h4>投稿方法１</h4><p>コックピット連携ボックスの<img class="cockpit_button_name" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/img_post-btn1.png" />をクリックします。</p></div><div class="methodli" ><h4>投稿方法２</h4><p>1.記事を公開した後に表示されるメッセージの<img class="cockpit_button_name" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/img_post-btn2.png" />をクリックしてサイトを確認します。</p><p>2.画面上部にあるツールバーの<img class="cockpit_button_name" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/img_post-btn3.png" />をクリックします。</p><p><img id="admin_img_post_btn" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/img_post-btn.png"/></p></div></div></div><form id="agree-form"><div id="cockpit_confirm_footerbox"><input type="checkbox" id="cockpit_nolongeropen">次回からこの画面を表示しない</input></div></form>');
		div_dialog.dialog({
			autoOpen: false,
			title: 'コックピット',
			dialogClass: 'dialog_cockpit_post_confirm wp-dialog',
			closeOnEscape: false,
			width: 600,
			height: 550,
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [ {
				text : "OK",
				"class": 'button-primary',
				click: function(){
					jQuery(this).dialog('close');
					if(jQuery("#cockpit_nolongeropen").is(':checked')){
						jQuery("#poststuff").append('<input type="hidden" name="cockpit_openguide_manual" value="1" />');
					}
					e.target.click();
				}
			}]
		});
		div_dialog.dialog('open');
	});
});
//-->
</script>
<?php
}
}

function cockpit_hdr_image_caption() {
?>
<div class="hdr_image_caption"><?php _e('SNS連携アクセス解析サービス『コックピット』との連携により、Twitterにサイトの更新連絡やお知らせを自動投稿できます。<br>ご利用には、サービスへの登録とアカウント設定が必要です。', 'cockpit'); ?></div>
<?php
}

function cockpit_admin_home() {
	wp_enqueue_style('cockpit_style', plugins_url( '', __FILE__ ).'/cockpit_style.css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('cockpit-js', plugins_url( '', __FILE__ ).'/cockpit.js');

	$token = $this->cockpit_get_token($error_token, '', '', $error_code);
	if($error_token !== '' && $error_code == 401){
		update_option('cockpit_activate', 0);
		update_option('cockpit_siteId', 0);
	}			
			
	if($_POST['cockpit_delete_site'] == 1) {
		update_option('cockpit_activate', 0);
		update_option('cockpit_siteId', 0);
	} 
?>
<div id="cockpit_body">
<?php if($this->title_icon !== '') { ?>
<div id="cockpit_title"><h2><img src="<?php echo $this->title_icon; ?>"><?php _e('コックピット設定', 'cockpit'); ?></h2></div>
<?php } ?>
<?php
	$this->cockpit_plugin_update();
	// cooperate-on
	$token = '';
	if ($_POST['cockpit_add_site'] == 1) {
		if(isset($_POST['cockpit_account']) && isset($_POST['cockpit_password'])){
			update_option('cockpit_account', $_POST['cockpit_account']);
			update_option('cockpit_password', $_POST['cockpit_password']);
		}
		$token = $this->cockpit_get_token($error_token);
		if($error_token !== '' ){
?>
<div class="cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/eyecatch.png" class="cockpit_eyecatch"><span><?php echo $error_token; ?></span></div>
<?php
		} else {
			$sites = $this->cockpit_getsites($token, $error_getsites);
			if($error_getsites !== ''){
?>
<div class="cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/eyecatch.png" class="cockpit_eyecatch"><span><?php echo $error_getsites; ?></span></div>
<?php
			} else {
				if(!isset($_POST['cockpit_siteId']) && !isset($_POST['cockpit_add_new'])) {
					$this->cockpit_confirm_site_page($sites);
					return;
				} else {
					$siteId ='';
					if(isset($_POST['cockpit_siteId']) && $_POST['cockpit_add_new'] == 0) {
						$siteId = $_POST['cockpit_siteId'];
					}
					//register site
					if($siteId === ''){
						$siteId = $this->cockpit_registration_site($token, $error_register);
						if($error_register !== ''){
?>
<div class="cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/eyecatch.png" class="cockpit_eyecatch"><span><?php echo $error_register; ?></span></div>
<?php
							$siteId = '';
						}
					}
					if( $siteId !== '' ) {
						update_option('cockpit_activate',1);
						update_option('cockpit_registered',1);
						update_option('cockpit_siteId', $siteId);
						$this->cockpit_update_trackingcode($token, $error_trackingcode);
						if($error_token !== '' ){
?>
<div class="cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/eyecatch.png" class="cockpit_eyecatch"><span><?php echo $error_trackingcode; ?></span></div>
<?php
						}
					}
				}
			}
		}
	}
?>
	<?php wp_nonce_field('cockpit-options');
	if( !$this->is_active_cockpit_acount() ) { ?>
<div class="position_box">
<?php if( get_option('cockpit_registered', 0 ) == 1 ) { ?>
<img class="hdr_img" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/hdr_img-off.png" />
<a href="https://web-cockpit.jp/"  class="cockpit_catch_link btn_cockpit" target="_blank"></a>
<?php } else { ?>
<img class="hdr_img" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/hdr_img-entry.png" />
<a href="https://www.justsystems.com/jp/links/ccptplg/cpapply.html?p=ccptplg" class="cockpit_catch_link btn_entry" target="_blank" class="button-secondary" ></a>
<?php } ?>
</div>
<?php $this->cockpit_hdr_image_caption(); ?>
<?php
		$this->cockpit_account_on();

	} else {
		if($token === '') {
			$token = $this->cockpit_get_token($error_token);
		}
?>
<div class="position_box">
<img class="hdr_img" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/hdr_img-on.png" />
<a href="https://web-cockpit.jp/app/#login3/<?php echo $token.'/'.get_option('cockpit_siteId'); ?>" class="cockpit_catch_link btn_cockpit" target="_blank" ></a>
</div>
<?php $this->cockpit_hdr_image_caption(); ?>
<?php
		$this->cockpit_settings($token);
	}
?>
<?php
}

function cockpit_confirm_site_page($sites) {
?>
<img class="hdr_img" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/hdr_img-entry_g.png" />
<?php $this->cockpit_hdr_image_caption(); ?>
<table><tr><td><h3 class="header_caption"><?php _e('アカウント設定', 'cockpit'); ?></h3></td><td class="hrimg hr_caption"/></table>
<?php
$site = null;
$home_url = rtrim(str_ireplace(array('http://', 'https://', 'www.'), '', home_url()), '/');
foreach( $sites as $exist_site ) {
	if(preg_match( '{' . $home_url . '}' , rtrim(str_ireplace(array('http://', 'https://', 'www.'), '', $exist_site['url']), '/')) == 1) {
		$site = $exist_site;
		break;
	}
}
if(!is_null($site)){
?>
<div><?php _e('既に同じURLのサイトが登録されています。このサイトを使用します。よろしいですか？', 'cockpit'); ?></div>
<form method="post" action="<?php str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" class="confirm_target_site">
<h3><?php _e('アクセス解析するサイト', 'cockpit'); ?></h3>
<table class="form_table"><tr><td><?php _e('あなたのサイト名', 'cockpit'); ?></td><td><?php echo $site['name']; ?></td></tr>
<tr><td>URL</td><td><?php echo $site['url']; ?></td></tr>
<tr><td>登録日時</td><td><?php echo date('Y年m月d日 H:i', strtotime($site['regist_date'])); ?></td></tr></table>
</select><input name="cockpit_siteId" value="<?php echo $site['site_id']; ?>" type="hidden"><input type="hidden" name="cockpit_add_site" value="1"><input class="button-primary" id="cockpit_on" type="submit" value="<?php _e('コックピットと連携', 'cockpit'); ?>"></input><a href="" class="button-secondary" >キャンセル</a></form>
<?php
} else {
?>
<div><?php _e('次のサイトをコックピットでアクセス解析するサイトに登録します。よろしいですか？', 'cockpit'); ?></div>
<form method="post" action="<?php str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" class="confirm_target_site">
<h3><?php _e('アクセス解析するサイト', 'cockpit'); ?></h3>
<table class="form_table"><tr><td><?php _e('あなたのサイト名', 'cockpit'); ?></td><td><?php echo get_bloginfo('name'); ?></td></tr>
<tr><td>URL</td><td><?php echo home_url(); ?></td></tr>
<tr><td>登録日時</td><td><?php echo date_i18n ('Y年m月d日 H:i'); ?></td></tr></table>
</select><input name="cockpit_add_new" value="1" type="hidden"><input type="hidden" name="cockpit_add_site" value="1"><input class="button-primary" id="cockpit_on" type="submit" value="<?php _e('コックピットと連携', 'cockpit'); ?>"></input><a href="" class="button-secondary" >キャンセル</a></form>
<?php
}
}

function cockpit_settings($token) {
	$twitter_info = $this->cockpit_get_twitter_info($token, $error);
	$twitter_active = false;
	if($error !== '') {
?>
<div class="cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/eyecatch.png" class="cockpit_eyecatch"><span><?php echo $error; ?></span></div>
<?php
	}
	if($error === '' && count($twitter_info) != 0){
		$twitter_active = true; 
	}
	if($twitter_active == false){
?>
<div class="cockpit_eyecatch_area"><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/eyecatch.png" class="cockpit_eyecatch"><span><?php _e('自動投稿を有効化するには、利用するTwitterアカウントの設定が必要です。', 'cockpit'); ?></span><a href="https://web-cockpit.jp/app/#login3/<?php echo $token; ?>/<?php echo get_option('cockpit_siteId'); ?>" target="_blank" class="button-primary" >Twitterアカウントを設定</a></div>
<?php } ?>
<form method="post" name="cockpit-settings" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<div class="cockpit_block"><table><tr><td><h3 class="header_caption"><?php _e('Twitter設定', 'cockpit'); ?></h3></td><td class="hrimg hr_caption"/></table>
<table class="sns_form_table">
<tr><td class="form_td"><?php if($twitter_active == false) { ?><span class="caution">*</span> <?php } ?><?php _e('Twitterアカウント', 'cockpit'); ?></td><td>
<?php 
	if($twitter_active == true) {
?>
<img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/btn_account-tw.png"><br>
<div class="tw_account_box">
<table class="sns_account"><tr><td><img src="<?php echo $twitter_info['profile_image_url']; ?>" class="sns_account_icon"></td><td><b><?php echo $twitter_info['name']; ?></b><br>＠<?php echo $twitter_info['screen_name']; ?></td></tr></table>
</div>
<?php
	} else {
?>
<a class="tw_account_add" href="https://web-cockpit.jp/app/#login3/<?php echo $token; ?>/<?php echo get_option('cockpit_siteId'); ?>" target="_blank" ></a><br>
<div class="tw_account_box">
<table class="sns_account"><tr><td><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/icon_no-account.png"></td><td id="tw_no_account"><span>アカウントが登録されていません</span></tr></table>
</div>
<?php
	}
?></td></tr>
<tr><td><p><?php _e('自動投稿', 'cockpit'); ?></p></td><td>
<?php _e('<p>記事やページを投稿したときに、コックピットが自動でサイトの更新をTwitterに投稿します。<br>
サイトの更新をTwitterに投稿することで、より多くのアクセス数が見込めます。</p>', 'cockpit'); ?>
<?php $auto_tweet = get_option('cockpit_auto_tweet', 1 ); ?>
<span class="radio_align" ><input type="radio" name="cockpit_auto_tweet" value="0" <?php if($twitter_active==false){ checked(true, true); } else { checked( $auto_tweet, 0 ); }?> ></input><label> <?php _e('自動投稿しない', 'cockpit'); ?></label></span><br><span><input type="radio" name="cockpit_auto_tweet" value="1" id="cockpit_auto_tweet" <?php if($twitter_active == true){ checked( $auto_tweet, 1 );} ?> <?php if($twitter_active==false) echo 'disabled="disabled"'; ?>></input><label id="cockpit_auto_tweet_label"> <?php _e('自動投稿する', 'cockpit'); ?></label></span>
<?php if($twitter_active == false) { ?>
<span class="caution">* <?php _e('自動投稿を有効化するには、利用するTwitterアカウントの設定が必要です。', 'cockpit'); ?></span>
<?php } ?>
</td></tr></table>
<div class="cockpit_settings"><table class="sns_form_table"><tr><td class="form_td"><p><?php _e('投稿フォーマット', 'cockpit'); ?></p></td><td>
<div><p><?php _e('投稿内容の前後に、あらかじめ決まった形式でコメントを設定しておくことができます。', 'cockpit'); ?></p><p><?php _e('コメント（前）', 'cockpit'); ?></p><input size="70" maxlength="30" type="text" name="cockpit_tweet_common_comment_before" onkeyup="jQuery('#cockpit_tweet_common_comment_before').text(value.length + ' / 30<?php _e('字', 'cockpit'); ?>');" value="<?php $comment_before = get_option('cockpit_tweet_common_comment_before'); echo $comment_before; ?>"></input><span id="cockpit_tweet_common_comment_before"><?php echo mb_strlen($comment_before); ?> / 30<?php _e('字', 'cockpit'); ?></span>
<p><?php _e('コメント（後）', 'cockpit'); ?></p><input size="70" maxlength="30" type="text" name="cockpit_tweet_common_comment_after" onkeyup="jQuery('#cockpit_tweet_common_comment_after').text(value.length + ' / 30<?php _e('字', 'cockpit'); ?>');" value="<?php $comment_after = get_option('cockpit_tweet_common_comment_after'); echo $comment_after; ?>"></input><span id="cockpit_tweet_common_comment_after"><?php echo mb_strlen($comment_after); ?> / 30<?php _e('字', 'cockpit'); ?></span><br>
<img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/img_sample-tw.png" />
</div></td></tr></table></div>
<div class="btn_accordion"></div>
<input type="hidden" name="action" value="update" /><input type="hidden" name="page_options" value="cockpit_tweet_password, cockpit_tweet_account, cockpit_auto_tweet, cockpit_tweet_common_comment_before, cockpit_tweet_common_comment_after, cockpit_tweet_contents" />
<input class="button-primary" type="submit" <?php if( !$this->cockpit_is_twitter_registration() ) { echo 'onclick="OpenCockpitSNSAccountSettingPage();" '; } ?>name="submit" value="<?php _e('設定を保存する', 'cockpit'); ?>"/></div></form>
<?php
	if( $this->is_active_cockpit_acount() ) {
		$this->cockpit_account_off();
	} 
}

function cockpit_is_twitter_registration() {
	return false;
}

function cockpit_account_off() {
	$cockpit_account = get_option('cockpit_account');
	$cockpit_password = get_option('cockpit_password');
?>
<script type="text/javascript"><!--
   function confirmDeleteCockpitInfo() {
      var res = confirm("コックピットでこのサイトをアクセス解析できなくなります。\nよろしいですか？");
      if( res == true ) {
         return true;
      }
      return false;
   }
// --></script>
<div class="cockpit_block"><form method="post" action="<?php str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<table><tr><td><h3 class="header_caption"><?php _e('アカウント設定', 'cockpit'); ?></h3></td><td class="hrimg hr_caption"/></table>
<img id="just_account_logo" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/just_account.png"/>
<table class="form_table">
<tr><td class="form_td"><?php _e('メールアドレス', 'cockpit'); ?></td><td><input size="60" type="text" name="cockpit_account" autocomplete="off" value="<?php echo $cockpit_account; ?>" readonly /></td></tr>
<tr><td><?php _e('パスワード', 'cockpit'); ?></td><td><input size="60" type="password" name="cockpit_password" autocomplete="off" value="<?php echo $cockpit_password; ?>" readonly /></td></tr></table>
<h3><?php _e('アクセス解析するサイト', 'cockpit'); ?></h3>
<table class="form_table">
<tr><td><?php _e('あなたのサイト名', 'cockpit'); ?></td><td><?php echo get_option('cockpit_sitename'); ?></td></tr>
<tr><td class="form_td"><?php _e('URL', 'cockpit'); ?></td><td><?php echo get_option('cockpit_siteurl'); ?></td></tr>
<tr><td><?php _e('登録日時', 'cockpit'); ?></td><td><?php echo date('Y年m月d日', strtotime(get_option('cockpit_site_register_date'))); ?></td></tr>					
</table>
<input type="hidden" name="cockpit_delete_site" value="1"><input class="button-primary" id="cockpit_off" onClick="return confirmDeleteCockpitInfo();" type="submit" name="submit" value="<?php _e('コックピットとの連携を解除', 'cockpit'); ?>"/></p></form></div>
<?php
}

function cockpit_account_on() {
	$cockpit_account = get_option('cockpit_account');
	$cockpit_password = get_option('cockpit_password');
?>
<div><form method="post" action="<?php str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<table><tr><td><h3 class="header_caption"><?php _e('アカウント設定', 'cockpit'); ?></h3></td><td class="hrimg hr_caption"/></table>
<?php
	$cockpit_account_url = 'http://www.justsystems.com/jp/links/hpb/cockpitapply.html';
	printf( __('<p>Justアカウントに登録したメールアドレスとパスワードを入力して、［次へ］をクリックします。</p><br>', 'cockpit'), $cockpit_account_url );
?>
<div id="hpb_aa_acount_form"><img id="just_account_logo" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/just_account.png"/>
<table class="form_table">
<tr><td><?php _e('メールアドレス', 'cockpit'); ?></td><td><input size="60" type="text" name="cockpit_account" autocomplete="off" value="<?php echo $cockpit_account; ?>" /></td></tr>
<tr><td><?php _e('パスワード', 'cockpit'); ?></td><td><input size="60" type="password" name="cockpit_password" autocomplete="off" value="<?php echo $cockpit_password; ?>" /></td></tr>
<tr><td></td><td><img id="reset_allow" src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/reset_password.png" ><a class="link_noline" href="<?php $url_reset = $this->cockpit_get_reset_password($error); if($error === ''){ echo $url_reset;} ?>" target="_blank">パスワードをお忘れの方</a></td></tr>
</table></div>
<p class="submit"><input type="hidden" name="cockpit_add_site" value="1"><input type="hidden" name="cockpit_plugin_url" value="<?php echo plugins_url( '', __FILE__ ); ?>"><input class="button-primary cockpit_next_button" type="submit" name="submit" id="dialog_link" value="<?php _e('次へ ≫', 'cockpit'); ?>"/></p>
</form></div>
<?php
}

function is_active_cockpit_acount() {
	if( get_option('cockpit_activate') == 1 ) {
		return true;
	}
	return false;
}

function cockpit_add_post_columns_name($columns) {
	$columns['cockpit_cooperation'] = __('コックピット連携', 'cockpit');
	return $columns;
}

function cockpit_add_column($column_name, $post_id) {
	if( $column_name == 'cockpit_cooperation' ) {
		$post = get_post( $post_id );
		if( !empty($post_id) && $post->post_status == 'publish' ) {
			$this->cockpit_get_post_sns_link(true, $post->post_title, esc_url( get_permalink($post_id) ));
		}
	}
}

function cockpit_add_page_columns_name($columns) {
	$columns['cockpit_cooperation'] = __('コックピット連携', 'cockpit');
	return $columns;
}

function cockpit_add_page_column($column_name, $post_id) {
	if( $column_name == 'cockpit_cooperation' ) {
		$post = get_post( $post_id );
		if( !empty($post_id) && $post->post_status == 'publish' ) {
			$this->cockpit_get_post_sns_link(true, $post->post_title, esc_url( get_permalink($post_id) ));
		}
	}
}

function cockpit_generate_twitter_text($post_title, $post_url, $is_url_encode = true) {
	$cockpit_post_sns_param = '';
	$cockpit_post_comment_before = get_option('cockpit_tweet_common_comment_before');
	$cockpit_post_comment_after = get_option('cockpit_tweet_common_comment_after');
	if($cockpit_post_comment_before !== ''){
		$cockpit_post_comment_before .= ' ';
	}
	if($cockpit_post_comment_after !== ''){
		$cockpit_post_comment_after = ' '.$cockpit_post_comment_after;
	}
	$count_cockpit_post_comment_before = mb_strlen($cockpit_post_comment_before);
	$count_cockpit_post_comment_after = mb_strlen($cockpit_tweet_common_comment_after);
	$cockpit_post_title_url .= $post_title.' '.$post_url;
	$count_cockpit_post_title_url = mb_strlen($post_title) + 21;
	$count_adjust = $count_cockpit_post_comment_before + $count_cockpit_post_title_url + $count_cockpit_post_comment_after - 140;
	if($count_adjust > 0){
		if(mb_strlen($post_title) >= $count_adjust){
			$cockpit_post_title_url = mb_substr($post_title, 0, $count_adjust).' '.$post_url;
		}
	}
	$cockpit_post_sns_param = $cockpit_post_comment_before.$cockpit_post_title_url.$cockpit_post_comment_after;
	if($is_url_encode) {
		$cockpit_post_sns_param = urlencode($cockpit_post_sns_param);
	}
	return $cockpit_post_sns_param;
}

function cockpit_get_post_sns_link($echo, $post_title, $post_url) {
	$cockpit_text = $this->cockpit_generate_twitter_text($post_title, $post_url);
	$token = $this->cockpit_get_token($error_token);
	$siteId = get_option(cockpit_siteId);
	$message =  '<div style="height: 20px; padding-left: 25px; padding-bottom:5px; background: url('.plugins_url( '', __FILE__ ).'/image/admin/icon_post.png) left top no-repeat;"><a style="vertical-align:middle;" href="https://web-cockpit.jp/app/#post/'.$token.'/'.$siteId.'/'.$cockpit_text.'" target="_blank" title="'.__('コックピットでTwitterに投稿', 'cockpit').'">'.__('コックピットで投稿', 'cockpit').'</a></div>';
	if($echo) {
		echo $message;
	} else {
		return $message;
	}
}

function cockpit_get_post_sns_link_metabox($echo, $post_title, $post_url, $disable=false) {
	$cockpit_text = $this->cockpit_generate_twitter_text($post_title, $post_url);
	$token = $this->cockpit_get_token($error_token);
	$siteId = get_option(cockpit_siteId);
	if(!$disable){
		$href = 'href="https://web-cockpit.jp/app/#post/'.$token.'/'.$siteId.'/'.$cockpit_text.'" target="_blank" ';
	} else {
		$href = '';
	}
	$message =  '<a '.$href.'class="button-primary" id="cockpit_post_twitter"'.disabled($disable, true, false).'>'.__('Twitterに投稿', 'cockpit').'</a>';
	if($echo) {
		echo $message;
	} else {
		return $message;
	}
}

function cockpit_get_post_sns_link_adminbar($echo, $post_title, $post_url) {
	$token = $this->cockpit_get_token($error_token);
	$cockpit_text = $this->cockpit_generate_twitter_text($post_title, $post_url);
	$siteId = get_option(cockpit_siteId);
	$message =  '<div style="height:28px; padding-left: 15px; background: url('.plugins_url( '', __FILE__ ).'/image/admin/icon_post.png) left center no-repeat;"><a style="vertical-align:middle;" href="https://web-cockpit.jp/app/#post/'.$token.'/'.$siteId.'/'.$cockpit_text.'" target="_blank" title="'.__('コックピットでTwitterに投稿', 'cockpit').'">'.__('コックピットで投稿', 'cockpit').'</a></div>';
	if($echo) {
		echo $message;
	} else {
		return $message;
	}
}

function cockpit_add_meta_box() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'cockpit_cooperation_post', __('コックピット連携', 'cockpit'), array($this, 'cockpit_inner_custom_box'), 'post', 'side', 'high' );
		add_meta_box( 'cockpit_cooperation_post', __('コックピット連携', 'cockpit'), array($this, 'cockpit_inner_custom_box'), 'page', 'side', 'high'  );
		$arguments = array(
		'public'   => true,
		'_builtin' => false
		);
		$all_post_types = get_post_types( $arguments, 'names', 'and' );
		foreach ( $all_post_types as $one_post_type ) {
			add_meta_box( 'cockpit_cooperation_post', __('コックピット連携', 'cockpit'), array($this, 'cockpit_inner_custom_box'), $one_post_type, 'side', 'high' );
		}
	} else {
		add_action('dbx_post_advanced', array($this, 'cockpit_inner_custom_box') );
		add_action('dbx_page_advanced', array($this, 'cockpit_inner_custom_box') );
		$arguments = array(
		'public'   => true,
		'_builtin' => false
		);
		$all_post_types = get_post_types( $arguments, 'names', 'and' );
		foreach ( $all_post_types as $one_post_type ) {
			add_action('dbx_'.$one_post_type.'_advanced', array($this, 'cockpit_inner_custom_box') );
		}
	}
}

function cockpit_inner_custom_box() {
	wp_enqueue_style('cockpit_style', plugins_url( '', __FILE__ ).'/cockpit_style.css');
	
	if ( isset( $_GET['post'] ) ) {
 		$post_id = $post_ID = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
 		$post_id = $post_ID = (int) $_POST['post_ID'];
	} else {
	 	$post_id = $post_ID = 0;
	}
	$post = get_post( $post_id );
?>
<div class="inside"><div class="submitbox"><div id="minor-publishing"><div id="misc-publishing-actions">
<div class="misc-pub-section">利用するSNSアカウント:<?php
	$token = $this->cockpit_get_token($error);
	$twitter_info = $this->cockpit_get_twitter_info($token, $error);
	$twitter_active = false;
	if($error === '' && count($twitter_info) != 0){
		$twitter_active = true; 
	}
	if($twitter_active){
?>
<table class="sns_account_meta"><tr><td><img src="<?php echo $twitter_info['profile_image_url']; ?>" style="width:36px;height:36px;"/><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/mark_tw.png" style="width:39px;height:39px;position:relative;right:36px;top:3px;margin-right:-36px;"></td><td><b><?php echo $twitter_info['name']; ?></b><br>＠<?php echo $twitter_info['screen_name']; ?></td></tr></table>
<?php
	} else {
?>
<table><tr><td><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/icon_no-account.png" style="width:36px;height:36px;"/><img src="<?php echo plugins_url( '', __FILE__ ); ?>/image/admin/mark_tw.png" style="width:39px;height:39px;position:relative;right:36px;top:3px;margin-right:-30px;"></td><td><p><?php _e('アカウントが<br/>登録されていません', 'cockpit'); ?></p></td></tr></table><?php
	}
	$auto_tweet = get_option('cockpit_auto_tweet', 1 );
	if($auto_tweet != 0 && $twitter_active){
		$isRenameMeta = get_option('cockpit_retrieve_meta', 0);
		if($isRenameMeta == 0){
			global $wpdb;
			$wpdb->query("UPDATE `wp_postmeta` SET `meta_key` = '_cockpit_lasttweet' WHERE `meta_key` = 'cockpit_lasttweet'");
			$wpdb->query("UPDATE `wp_postmeta` SET `meta_key` = '_cockpit_lasttweet_status' WHERE `meta_key` = 'cockpit_lasttweet_status'");
			update_option('cockpit_retrieve_meta', 1);
		}

		$lasttweet = get_post_meta($post_id, '_cockpit_lasttweet', true);
		$lasttweet_status = get_post_meta($post_id, '_cockpit_lasttweet_status', true);
	}
?>
</div>
<div class="misc-pub-section<?php if(!($auto_tweet != 0 && $twitter_active)) { echo '-last'; }?>">自動投稿: <b><?php
	$auto_tweet = get_option('cockpit_auto_tweet', 1 );
	if($auto_tweet != 0 && $twitter_active){
		echo 'する';
	} else {
		echo 'しない';
	}
?></b></div>
<?php if($auto_tweet != 0 && $twitter_active) { ?>
<div class="misc-pub-section">ステータス: <b><?php if($lasttweet != ''){ echo $lasttweet_status;} ?></b></div><div class="misc-pub-section-last">投稿日時: <b><?php if($lasttweet != ''){ echo $lasttweet;} ?></b></div>
<?php
	}
?>
</div></div></div></div><div id="major-publishing-actions">
<a class="button-secondary" id="cockpit_page_link" target="_blank" href="https://web-cockpit.jp/app/#login3/<?php echo $this->cockpit_get_token($error).'/'.get_option('cockpit_siteId'); ?>" >コックピットを表示</a>
<?php
	if( !empty($post_id) && $post->post_status == 'publish' ) {
		$this->cockpit_get_post_sns_link_metabox(true, $post->post_title, esc_url( get_permalink($post_id) ));
?>
<?php
	} else {
		$this->cockpit_get_post_sns_link_metabox(true, $post->post_title, esc_url( get_permalink($post_id) ), true);
	}
?>
<div class="clear"></div></div>
<?php
}

function cockpit_add_adminbarmenu( &$wp_admin_bar ) {
	global $wp_the_query;
	$current_object = $wp_the_query->get_queried_object();

	if ( $current_object || (! empty( $current_object->post_type )
		&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )
		&& current_user_can( $post_type_object->cap->edit_post, $current_object->ID )
		&& ( $post_type_object->show_ui || 'attachment' == $current_object->post_type )) )
	{
		global $post;
		if( !empty($post) ){
			$menu = array( 'id' => 'cockpit_postsns', 'title' => $this->cockpit_get_post_sns_link_adminbar(false, $post->post_title, esc_url( get_permalink($post_id) )), 'href' => '' );
		}
		$wp_admin_bar->add_menu( $menu );
	} else if( is_home() ) {
		$menu = array( 'id' => 'cockpit_postsns', 'title' => $this->cockpit_get_post_sns_link_adminbar(false, get_bloginfo('name'), esc_url(home_url())), 'href' => '' );
		$wp_admin_bar->add_menu( $menu );
	}
}

function cockpit_get_token(&$error, $mail_address = '', $password = '', &$error_code = null) {
	$token = '';$error = '';
	
	$token_last = get_option('cockpit_token_last', '');
	if( $token_last !== '' ) {
		$token_last_date = get_option('cockpit_token_last_date', '');
		if($token_last_date == date_i18n('ymd')){
			return $token_last;
		}
	}

	if($mail_address === '')
		$mail_address = get_option('cockpit_account');
	if($password === '')
		$password = get_option('cockpit_password');
	try {	
		$body = '{
					"mail_address" :"'.$mail_address.'",
					"password" : "'.$password.'"
				}';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/entry/authenticate',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error =  $response->get_error_message();
				return ;
			} else {
				$status_code = $response['response']['code'];
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($return){
					$token = $return['t'];
					update_option('cockpit_token_last', $token);
					update_option('cockpit_token_last_date', date_i18n('ymd'));
				}
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
		return $token;
	}
	$error_code = $status_code;
	return $token;	
}

function cockpit_get_twitter_info($token, &$error) {
	$twitterinfo = array();$error = '';
	$siteid = get_option('cockpit_siteId');
	try {	
		$body = '{
					"site_id" :"'.$siteid.'",
					"t" : "'.$token.'"
				}';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/site/get',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error =  $response->get_error_message();
				return $twitterinfo;
			} else {
				$status_code = $response['response']['code'];
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($return){
					$twitterinfo = $return['twitter_user'];
					update_option('cockpit_sitename', $return['name']);
					update_option('cockpit_siteurl', $return['url']);
					update_option('cockpit_site_register_date', $return['regist_date']);
				}
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
		return $twitterinfo;
	}
	return $twitterinfo;	
}

function cockpit_get_reset_password(&$error) {
	$url = '';
	$error = '';
	try {	
		$body = '';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/entry/reset_password',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error =  $response->get_error_message();
				return $url;
			} else {
				$status_code = $response['response']['code'];
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($return){
					$url = $return['uri'];
				}
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
	}
	return $url;	
}

function cockpit_registration_site($token, &$error) {
	$siteId = -1;$error = '';
	$site_name = get_bloginfo('name');
	if( $site_name == '' ) {
		$site_name = preg_replace( '#^(https?://)?(www.)?#', '', get_home_url() );
	}
	try {
		$body = '{
					"name" :"'.$site_name.'",
					"url" : "'.home_url().'",
					"t" : "'.$token.'"
				}';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/site/add',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error = sprintf(__('エラーが発生しました。エラー:%s', 'cockpit'), $response->get_error_message());
			} else {
				$status_code = $response['response']['code'];
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($error === ''){
					if($return){
						 $siteId = $return['site_id'];
					}
					return $siteId;
				} 
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
	}
	return $siteId;
}

function cockpit_getsites($token, &$error) {
	$sites = array();$error = '';
	try {
		$body = '{
					"t" : "'.$token.'"
				}';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/site/list',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error = sprintf(__('エラーが発生しました。エラー:%s', 'cockpit'), $response->get_error_message());
				return $sites;
			} else {
				$status_code = $response['response']['code'];
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($error === ''){
					if($return){
						 $sites = $return['sites'];
					}
				} else {
					return $sites;
				}
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
		return $sites;
	}
	return $sites;
}

function cockpit_update_trackingcode($token, &$error) {
	$error = '';
	try {
		$body = '{
					"site_id" : "'.get_option('cockpit_siteId').'",
					"html_type" : "html",
					"t" : "'.$token.'"
				}';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/site/tc',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error = sprintf(__('エラーが発生しました。エラー:%s', 'cockpit'), $response->get_error_message());
				return false;
			} else {
				$status_code = $response['response']['code'];
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($error === ''){
					$cockpit_tracking_code = '';
					if($return){
						 $cockpit_tracking_code = $return['tc'];
					}
					update_option('cockpit_tracking_code_html', $cockpit_tracking_code );
					return true;
				} else {
					return false;
				}
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
		return false;
	}
	return false;
}

function cockpit_head() {
	//tracking code
	if( !is_search() && !is_archive() && $this->is_active_cockpit_acount()){
		echo get_option('cockpit_tracking_code_html');
	}
}

function cockpit_tweet($token, $post_title, $post_url, &$error) {
	$error = '';
	try {
		$body = '{
					"site_id" : "'.get_option('cockpit_siteId').'",
					"message" : '.json_encode($this->cockpit_generate_twitter_text($post_title, $post_url, false)).',
					"t" : "'.$token.'"
				}';
		if ( function_exists( 'wp_remote_post' ) ) {
			$response = wp_remote_post('https://web-cockpit.jp/api2/sns/post',
				array(	
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'sslverify' => false,
				'cookies' => array(),
					'headers' => array('Content-Type' => 'application/json',),
					'body' => $body,
					));
			if(is_wp_error($response)){
				$error = sprintf(__('エラーが発生しました。エラー:%s', 'cockpit'), $response->get_error_message());
				return false;
			} else {
				$status_code = $response['response']['code'];
				if($status_code == 204){
					return true;
				}
				$response_body = wp_remote_retrieve_body($response);
				$return = json_decode($response_body,true);
				$error = $this->cockpit_get_error_message($status_code, $return);
				if($error === ''){
					return true;
				} else {
					return false;
				}
			}
		}
	} catch (Exception $e) {
		$error =  sprintf(__('エラーが発生しました。エラーコード:%s', 'cockpit'), $e->getMessage());
		return false;
	}
	return false;
}

function cockpit_get_error_message($status_code, $response){
	$message = '';
	switch($status_code){
		case 200:
		case 204:
			break;
		case 413:
			$message = 'サイトの登録に失敗しました。登録できるサイトは５サイトまでです。登録サイトの解除はコックピットサービスにログイン後、設定画面で行えます。';
			break;
		case 500:
			$message = 'サーバー内部エラーが発生しました。';
			break;
		case 503:
			$message = 'サービスが利用できません。しばらくしてからもう一度お試しください。';
			break;
		case 401:
			$message = '認証に失敗しました。メールアドレスあるいはパスワードが不正です。Justアカウントがコックピットサービスに登録されているかご確認ください。';
			break;
		case 400:
		case 422:
			if( !is_null($response['error'])){
				$message = sprintf(__('エラーが発生しました。エラー:%s', 'cockpit'), $response['error']);
			} else {
				$message = sprintf(__('エラーが発生しました。エラーコード[%d]', 'cockpit'), $status_code);
			}
			break;
		default:
			$message = sprintf(__('エラーが発生しました。エラーコード[%d]', 'cockpit'), $status_code);
	}
	return $message;
}
}

?>
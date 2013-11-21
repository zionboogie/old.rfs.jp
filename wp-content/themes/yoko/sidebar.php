<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
?>

<!-- #sidecol-1st -->
<div id="sidecol-1st" class="widget-area" role="complementary">

<a id="menu-smapho"></a>
<aside id="categories-4" class="widget widget_categories">
	<h3 class="widget-title">category</h3>
	<?php wp_list_categories('include=238,231,232,189,177,4,198,199,182,48,147,77,178,179,180,181,193,190,240&title_li=&hide_empty=0&orderby=count&order=asc') ?>
</aside>

<div style="margin-bottom:40px">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9885420769768716";
/* 180×150 右エリアバナー */
google_ad_slot = "5341324198";
google_ad_width = 180;
google_ad_height = 150;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

</div>

<aside id="tag_cloud-3" class="widget widget_tag_cloud">
	<h3 class="widget-title">tagcloud</h3>
	<div class="tagcloud">
		<?php wp_tag_cloud('smallest=10&largest=10&orderby=count&order=DESC'); ?>
	</div>
</aside>

	<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
	<!-- #categories -->
	<aside id="categories" class="widget widget_categories">
		<ul>
			<?php wp_list_categories('title_li=<h3 class="widget-title">' . __('Categories', 'yoko') . '</h3>' ); ?>
		</ul>
	</aside>

	<!-- #archives -->
	<aside id="archives" class="widget widget_archive">
		<h3 class="widget-title"><?php _e( 'Archives', 'yoko' ); ?></h3>
		<ul class="ul-list">
			<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	</aside>
	<?php endif; // end sidebar 1 widget area ?>


</div>
<!-- / #sidecol-1st -->

<!-- #sidecol-2nd -->
<div id="sidecol-2nd" class="widget-area" role="complementary">

	<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>
	<?php endif; // end sidebar 2 widget area ?>

	<aside id="widget-twitter" class="widget widget-twitter">
		<h3 class="widget-title">Twitter</h3>

		<div id="box-twitter"></div>


<!--
<a class="twitter-timeline" width="180" href="https://twitter.com/rfs_jp" data-widget-id="262433444422877184">@rfs_jp からのツイート</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
-->

<script type="text/javascript"><!--
google_ad_client = "ca-pub-9885420769768716";
/* 120×600 右エリアバナー */
google_ad_slot = "8791802075";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</aside>

	<aside id="widget-profile" class="widget widget-profile">
		<h3 class="widget-title">プロフィール</h3>

		<div class="box-profile">
			<p>玉川 純 [タマガワ ジュン]<br />
			1997年7月にデザイン事務所RHYTHMFACTORY [リズムファクトリー] を設立。<br />
			</p>

			<p>RHYTHMFACTORY<br />
			サイト制作のパートナーはリズムファクトリー。<br />
			<small><a href="http://www.rhythmfactory.jp/" target="_blank" class="a-url">http://www.rhythmfactory.jp/</a></small><br />
			tel: 03-5768-2668<br />
			fax: 03-5768-2678</p>
		</div>
	</aside>

	<aside id="box-side2nd-banner">
		<a href="http://www.rhythmfactory.jp/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/common/i/wrap/bn_rhythm.gif" alt="素敵なウェブサイト制作のお手伝い 株式会社リズムファクトリー"></a>
	</aside>

</div>
<!-- / #sidecol-2nd -->
<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js ie6" lang="ja"><![endif]-->
<!--[if IE 7]><html class="no-js ie7" lang="ja"><![endif]-->
<!--[if IE 8]><html class="no-js ie8" lang="ja"><![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--><html class="no-js" lang="ja"><!--<![endif]-->


<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'yoko' ), max( $paged, $page ) );
?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<![endif]-->
<?php wp_head(); ?>

<script src='http://platform.twitter.com/widgets.js?ver=1.1'></script>
<!--<script src="<?php echo get_template_directory_uri(); ?>/common/js/jquery.gettweet.js"></script>-->
<script src="<?php echo get_template_directory_uri(); ?>/common/js/smoothscroll.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/common/js/main.js"></script> 
<script>
SyntaxHighlighter.config.clipboardSwf = '<?php echo get_template_directory_uri(); ?>/common/js/syntaxhighlighter/scripts/clipboard.swf';
SyntaxHighlighter.all();
</script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-FG5C9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-FG5C9');</script>
<!-- End Google Tag Manager -->

<!-- #container -->
<div id="container">

<!-- header -->
<header id="branding"><div class="inner">
<?php global $yoko_options;
$yoko_settings = get_option( 'yoko_options', $yoko_options ); ?>		

	<!-- #site-title -->
	<hgroup id="site-title">
		<h1 id="logo"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
	</hgroup>
	<!-- / #site-title -->

	<!-- #main-navi -->
<!--
	<nav id="main-navi">
		<?php /*wp_nav_menu( array( 'theme_location' => 'primary' ) ); */?>
	</nav>
-->
	<!-- / #main-navi -->

<div id="box-ad-header">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9885420769768716";
/* 468発60 ヘッダバナー */
google_ad_slot = "5483752052";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>

	<!-- #head-tool -->
	<div id="head-tool">

		<!-- #sub-navi -->
		<nav id="sub-navi">
<?php
if (is_nav_menu( 'Sub Menu' ) ) {
wp_nav_menu( array('menu' => 'Sub Menu' ));} ?>
			<ul>
				<li><a href="http://twitter.com/rfs_jp" id="btn-twitter" target="_blank">Twitter</a></li>
				<li><a href="http://b.hatena.ne.jp/my/add.confirm?url=http://rfs.jp/" id="btn-hatena" target="_blank">HATENA BOOKMARK</a></li>
				<li><a href="http://rfs.jp/feed" id="btn-rss" target="_blank">RSS SUBSCRIBE</a></li>
			</ul>
		</nav>
		<!-- / #sub-navi -->

		<!-- #box-search -->
		<aside id="box-search" class="widget-search">
			<form role="search" method="get" class="searchform" action="http://rfs.jp" >
			    <input type="text" class="search-input" value="" name="s" id="s" placeholder="search..." />
			    <input type="image" src="/wp-content/themes/yoko/common/i/header/ico_search.png" alt="search" class="button" />
		    </form>
		</aside>
		<!-- / #box-search -->
	</div>
	<!-- / #head-tool -->
</div></header>
<!-- / header -->

<a id="btn-menu-smapho" href="#menu-smapho">MENU</a>
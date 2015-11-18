<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
get_header(); ?>

<!-- category.php -->

<!-- #wrap -->
<div id="wrap">
<!-- #main -->
<div id="main">
	<!-- / #content -->
	<div id="content">
		<section id="article-list">
			<!-- #topic-path -->
			<div id="topic-path">
				<a href="<?php bloginfo('url'); ?>" class="ic-home">HOME</a>
				<?php echo get_category_parents($cat, true, ''); ?>
			</div>
			<!-- / #topic-path -->

			<header class="page-header">
				<h1 class="page-title <?php single_cat_title('h-') ?>"><?php printf( __( '%s', 'yoko' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
				<?php $categorydesc = category_description(); if ( ! empty( $categorydesc ) ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $categorydesc . '</div>' ); ?>
			</header>


<?php 

// カテゴリーの詳細データを取得
$cat_info	= get_category( $cat );
$categories	= get_categories(array(
	'parent'	=> $cat_info->cat_ID,
	'orderby'	=> 'slug',
	'order'		=> 'asc'
));

global $pg_navi; 

if( !$categories ){

	// ページナビゲーションの作成
	$paginate_base = get_pagenum_link(1);
	if ( strpos($paginate_base, '?') ) {
		$paginate_format	= '';
		$paginate_base		= add_query_arg('paged', '%#%');
	} else {
		$paginate_format = (substr($paginate_base, -1 ,1) == '/' ? '' : '/') 
			. user_trailingslashit('page/%#%/', 'paged');
		$paginate_base .= '%_%';
	}
	$pg_navi	= paginate_links( array(
		'base'		=> $paginate_base,
		'format'	=> $paginate_format,
		'total'		=> $wp_query->max_num_pages,
		'mid_size'	=> 7,
		'current'	=> ($paged ? $paged : 1),
	)); 
	echo '<div class="page-navi">' . "\n" . $pg_navi . "</div>\n";

	// 記事一覧の出力
	echo ('<div class="post-list">');
	while ( have_posts() ){
		the_post();
		echo( '<h3><a href="' );
		the_permalink();
		echo( '">' );
		the_title();
		echo( '</a> ');
		the_time("Y年m月j日");
		echo( "</h3>\n");
	}
	echo ('</div>');

} else {
	/* 記事リスト */
	foreach( $categories as $category ){
		echo '<ul class="li-category"><li><h3>', $category->name, '</h3><ul class="li-post">';

		$posts	= get_posts('numberposts=-1&orderby=ID&order=ASC&category=' . $category->cat_ID);
		if( $posts ){
			foreach( $posts as $post ){
				/* asideフォーマットは表示しない Perlの関数等 */
				if ( 'aside' != get_post_format( $post->ID )  ){
					echo '<li><a rel="" href="', the_permalink(), '">', the_title(), '</a></li>';
				}
			}
		}
		echo "</ul></li></ul>";
	}

}
?>

<!-- .page-navi -->
<div class="page-navi">
<?php
echo $pg_navi;
?>
</div>
<!-- / .page-navi -->
		</section>


	</div>
	<!-- / #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

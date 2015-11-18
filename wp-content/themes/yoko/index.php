<?php
get_header(); ?>
<!-- index.php -->

<!-- #wrap -->
<div id="wrap">

<!-- #content -->
<div id="content">

<!-- #entries -->
<section id="entries">
	<h1 id="h-headline">Headline</h1>

<!-- .page-navi -->
<div class="page-navi">
<?php 
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
echo $pg_navi;
?>
</div>
<!-- / .page-navi -->

    <div class="post-list">
	<?php while (have_posts()) : the_post(); ?>
		<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <?php the_time("Y年m月j日") ?></h3>
	<?php endwhile; ?>     
	</div>

<?php
/*
query_posts( 'paged='.$paged );
while ( have_posts() ){
	// 投稿データをロードする
	the_post();
	// content.phpの読み込み
//	get_template_part( 'content', get_post_format());
}
*/
?>

<!-- .page-navi -->
<div class="page-navi">
<?php
global $pg_navi; 
echo $pg_navi;
?>
</div>
<!-- / .page-navi -->
</section>
<!-- / #entries -->

</div>
<!-- / #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

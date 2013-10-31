<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */

get_header(); ?>

<!-- #wrap -->
<div id="wrap">

<!-- #content -->
<div id="content">


	<div id="topic-path">
		<a href="<?php bloginfo('url'); ?>" class="ic-home">HOME</a>
		<?php $cat = get_the_category(); echo get_category_parents($cat[0], true, ''); ?>
	</div>

	<nav id="nav-below">
		<?php 
		previous_post_link('<div class="nav-previous">%link</div>',  '%title', TRUE, '');
		next_post_link('<div class="nav-next">%link</div>', '%title', TRUE, '');
		 ?>
	</nav>

	<section id="entry">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'single' ); ?>
		<?php comments_template( '', true ); ?>
		<?php endwhile; ?>
	</section>

	<nav id="nav-below">
	<?php 
	previous_post_link('<div class="nav-previous">%link</div>',  '%title', TRUE, '');
	next_post_link('<div class="nav-next">%link</div>', '%title', TRUE, '');
	 ?>
	</nav>


</div>
<!-- / #content -->
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php
/**
 * index
 */

get_header(); ?>

<div id="wrap">
<!-- #main -->
<div id="main">

	<!-- #content -->
	<div id="content">

<section id="entries">
		<?php the_post(); ?>

		<!-- header -->
		<header class="page-header">
			<h1 class="page-title"><?php
				printf( __( 'TAG: %s', 'yoko' ), '<span>' . single_tag_title( '', false ) . '</span>' );
			?></h1>
		</header><!--end page-header-->
		<!-- / header -->

		<?php rewind_posts(); ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

		<?php /* next/previous */ ?>
		<?php if (  $wp_query->max_num_pages > 1 ) : ?>
			<nav id="nav-below">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'yoko' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'yoko' ) ); ?></div>
			</nav><!-- end nav-below -->
		<?php endif; ?>
</sction>

	</div>
	<!-- / #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

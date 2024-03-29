<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
get_header(); ?>

<!-- archive.php -->

<div id="wrap">
<div id="main">

	<div id="content">
				<?php the_post(); ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Archives: <span>%s</span>', 'yoko' ), get_the_date() ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Archives: <span>%s</span>', 'yoko' ), get_the_date( 'F Y' ) ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Archives: <span>%s</span>', 'yoko' ), get_the_date( 'Y' ) ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives', 'yoko' ); ?>
						<?php endif; ?>
					</h1>
				</header><!-- end page header -->

				<?php rewind_posts(); ?>
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>
				
				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if (  $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-below">
						<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'yoko' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'yoko' ) ); ?></div>
					</nav><!-- end nav-below -->
				<?php endif; ?>
	</div><!-- end content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
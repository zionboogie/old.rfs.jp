<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */

get_header(); ?>

<div id="wrap">
<div id="main">

	<div id="content">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>



	</div><!-- end content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
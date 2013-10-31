<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="page-entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>


	<div class="single-entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) ); ?>					
		<?php edit_post_link( __( 'Edit &rarr;', 'yoko' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!--end entry-content-->
	
</article><!-- end post-<?php the_ID(); ?> -->
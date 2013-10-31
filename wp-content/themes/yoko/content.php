<!-- content.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<!-- .entry-header -->
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'yoko' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header>
	<!-- / .entry-header -->

	<!-- .entry-details -->
	<div class="entry-details">
		<?php if ( has_post_thumbnail() ): ?>
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
		<?php endif; ?>
		<span class="po-date"><?php echo get_the_date(); ?></span>
		<?php comments_popup_link( __( 'コメントの追加', 'yoko' ), __( '1 COMMENT', 'yoko' ), __( '% COMMENTS', 'yoko' ),"po-comment" ); ?>

		<div class="related-links">
			<?php if ( count( get_the_category() ) ) : ?>
			<?php printf( __( '<span class="po-category">CATEGORY:</span> %2$s', 'yoko' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?> 
			<?php endif; ?>

			<?php $tags_list = get_the_tag_list( '', ', ' ); 
			if ( $tags_list ): ?>
			<?php printf( __( '<span class="po-tag">TAGS:</span> %2$s', 'yoko' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?> 
			<?php endif; ?>

			<?php edit_post_link( __( 'Edit &rarr;', 'yoko' ), ' <span class="edit-link">', '</span>' ); ?>
		</div>
	</div>
	<!-- / .entry-details -->

	<!-- .entry-content -->
	<div class="entry-content">
		<?php if ( /*is_archive() ||*/ is_search() ) : // archives/search の際は概要表示 ?>
			<?php the_excerpt(); ?>
		<?php else : ?>
			<?php the_content( __( 'READ MORE', 'yoko' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) ); ?>					
		<?php endif; ?>

		<!-- .entry-meta -->
		<footer class="entry-meta"></footer>
		<!-- / .entry-meta -->
	</div>
	<!-- / .entry-content -->

</article>
<!-- / post-<?php the_ID(); ?> -->

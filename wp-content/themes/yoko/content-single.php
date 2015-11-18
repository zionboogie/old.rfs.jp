<!-- content-single.php -->

<!-- post -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<?php if ( has_post_thumbnail()) : ?>
	<div class="single-entry-details">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
	</div>
	<?php endif; ?>

	<!-- .entry-header -->
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>
	<!-- / .entry-header -->

	<!-- .entry-details -->
	<div class="entry-details">
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
		<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<?php the_excerpt(); ?>			
		<?php else : ?>
			<?php the_content( __( 'Continue Reading &rarr;', 'yoko' ) ); ?>		
			<?php wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) ); ?>					
		<?php endif; ?>

<div style="text-align:right;">
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9885420769768716";
/* 320×50 more下バナー */
google_ad_slot = "2150777631";
google_ad_width = 320;
google_ad_height = 50;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>

		<!-- .entry-meta -->
		<footer class="entry-meta">
		</footer>
		<!-- / .entry-meta -->
	</div>
	<!-- / .entry-content -->

</article>
<!-- / post -->

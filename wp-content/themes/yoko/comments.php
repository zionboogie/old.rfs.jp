<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
?>

<!-- #box-comment -->
<div id="box-comment">

<?php if ( post_password_required() ) : ?>

	<div class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'yoko' ); ?></div>
</div>
<!-- / #box-comment -->

<?php
	/* Stop the rest of comments.php from being processed,
	 * but don't kill the script entirely -- we still have
	 * to fully load the template.
	 */
	return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>

<h3 id="comments-title"><?php printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'yoko' ), number_format_i18n( get_comments_number() )); ?></h3>
<p class="write-comment-link">
	<a href="#respond"><?php _e( 'REPLY &rarr;', 'yoko' ); ?></a>
</p>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>

<nav id="comment-nav-above">
	<h1 class="section-heading"><?php _e( 'Comment navigation', 'yoko' ); ?></h1>
	<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'yoko' ) ); ?></div>
	<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'yoko' ) ); ?></div>
</nav>

	<?php endif; // check for comment navigation ?>

<ol class="commentlist">
<?php wp_list_comments( array( 'callback' => 'yoko_comment' ) ); ?>
</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>

<nav id="comment-nav-below">
	<h1 class="section-heading"><?php _e( 'Comment navigation', 'yoko' ); ?></h1>
	<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'yoko' ) ); ?></div>
	<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'yoko' ) ); ?></div>
</nav>

	<?php endif; // check for comment navigation ?>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

	<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	* let's leave a little note, shall we?
	* But only on posts! We don't want the note on pages.
	*/
		if ( ! comments_open() && ! is_page() ) :
		?>

<p class="nocomments"><?php _e( 'Comments are closed.', 'yoko' ); ?></p>

		<?php endif; // end ! comments_open() && ! is_page() ?>

	<?php endif; ?>

<?php endif; ?>

<?php comment_form(
array(
    'fields'				=> array(
		'author' => '<div class="comment-form-author"><label for="author">お名前</label><input id="author" name="author" type="text" value="" size="30" aria-required="false" /></div>', 
		'<div class="comment-form-email"><label for="email">メールアドレス</label><input id="email" name="email" type="text" value="" size="30" aria-required="false" /></div>',
		'<div class="comment-form-url"><label for="url">ウェブサイト</label><input id="url" name="url" type="text" value="" size="30" /></div>'
    ),
	'comment_notes_before'	=>__( '', 'yoko'),
	'comment_notes_after'	=> '',
	'comment_field'			=> '<p class="comment-form-comment"><label for="comment">' . _x( 'メッセージ <span class="required">(必須)</span>', 'noun', 'yoko' ) . '</label><textarea id="comment" name="comment" rows="8"></textarea></p>',
)
); ?>



</div>
<!-- / #box-comment -->

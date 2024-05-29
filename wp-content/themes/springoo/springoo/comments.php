<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Springoo
 * @author ThemeRox
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="clearfix">
	<?php if ( have_comments() ) : ?>
		<div class="springoo-section-heading post-comments-heading">
			<h2>
			<?php
			comments_number(
				__( 'No Comments', 'springoo' ),
				__( 'One Comment', 'springoo' ),
				__( '% Comments', 'springoo' )
			);
			?>
			</h2>
		</div>
		<div class="comments-container">
			<ul class="comment-list">
				<?php wp_list_comments( array( 'callback' => 'springoo_comments' ) ); ?>
			</ul>
		</div>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="pagination post-pagination clearfix">
				<?php previous_comments_link( __( 'Older Comments', 'springoo' ) ); ?>
				<?php next_comments_link( __( 'Newer Comments', 'springoo' ) ); ?>
			</div>
		<?php endif; ?>
	<?php elseif ( ! comments_open() && ! is_page() && get_post_type() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p><?php esc_html_e( 'Comments are closed.', 'springoo' ); ?></p>
	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<?php
		$springoo_commenter     = wp_get_current_commenter();
		$springoo_req           = get_option( 'require_name_email' );
		$springoo_aria_req      = ( $springoo_req ? 'required' : '' );
		$required_mark          = ( $springoo_req ? ' *' : '' );
		$springoo_fields        = array(
			'author'  => '<div class="col-md-6"><input type="text" placeholder="' . __( 'Name', 'springoo' ) . $required_mark . '" name="author" id="author" ' . $springoo_aria_req . '></div>',
			'email'   => '<div class="col-md-6"><input type="email" placeholder="' . __( 'Email', 'springoo' ) . $required_mark . '" name="email" id="email" ' . $springoo_aria_req . '></div>',
			'website' => '<div class="col-md-6"><input type="url" placeholder="' . __( 'Website', 'springoo' ) . $required_mark . '" name="url" id="url" ' . $springoo_aria_req . '></div>',
			'subject' => '<div class="col-md-6"><input type="text" placeholder="' . __( 'Subject', 'springoo' ) . $required_mark . '" name="subject" id="subject" ' . $springoo_aria_req . '></div>',
		);
		$springoo_comments_args = array(
			'fields'              => $springoo_fields,
			'comment_field'       => '<div class="col-md-12"><textarea name="comment" id="comment" ' . $springoo_aria_req . ' placeholder="' . __( 'Enter your comments', 'springoo' ) . $required_mark . '"></textarea></div>',
			'id_form'             => 'post-comments-form',
			/* translators: %s: Author of the comment being replied to. */
			'title_reply_to'      => esc_html__( 'Reply to %s', 'springoo' ),
			'title_reply_before'  => '<div id="reply-title" class="springoo-section-heading comment-reply-title"><h2>',
			'title_reply_after'   => '</h2></div>',
			'cancel_reply_before' => ' <span class="cancel-reply">',
			'cancel_reply_link'   => '<i class="pl pl-close" aria-hidden="true"></i><span class="sr-only">' . esc_html__( 'Cancel reply', 'springoo' ) . '</span>',
			'cancel_reply_after'  => '</span>',
		);
		comment_form( $springoo_comments_args );
		?>
	<?php endif; ?>
</div>

<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<div class="review-left-side">
			<div class="position-sticky top-0">
				<h2 class="woocommerce-Reviews-title">
					<?php
					$count = $product->get_review_count();
					if ( $count && wc_review_ratings_enabled() ) {
						/* translators: 1: reviews count 2: product name */
						$reviews_title = sprintf( esc_html( _n( 'Rating & Review of %2$s', 'Ratings & Reviews of %2$s', $count, 'springoo' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
						echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // phpcs:ignore.
					} else {
						esc_html_e( 'Reviews', 'springoo' );
					}
					?>
				</h2>

				<?php
				/**
				 * Springoo Reviews Summery Hook.
				 */
				do_action( 'springoo_reviews_summery', $product->get_id() );
				?>
			</div>
		</div>
		<div class="review-right-side">
			<div class="position-sticky top-0">
				<?php
				/**
				 * Springoo Reviews Sort Filter Hook.
				 */
				do_action( 'springoo_reviews_sort_filter', $product->get_id() );
				?>

				<?php if ( have_comments() ) : ?>
					<ol class="commentlist">
						<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
					</ol>

					<?php
					if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
						echo '<nav class="woocommerce-pagination">';
						paginate_comments_links(
							apply_filters(
								'woocommerce_comment_pagination_args',
								array(
									'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
									'next_text' => is_rtl() ? '&larr;' : '&rarr;',
									'type'      => 'list',
								)
							)
						);
						echo '</nav>';
					endif;
					?>
				<?php else : ?>
					<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'springoo' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Review Form Modal -->
	<div class="modal fade review_form_wrapper" id="review_dialog" tabindex="-1" aria-labelledby="review_product_title" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<div class="product__thumb"><?php echo wp_kses_post( $product->get_image() ); ?></div>
					<div class="product__content">
						<h5 class="product__title" id="review_product_title"><?php
							printf(
							/* translators: 1. sr-only span tag open, 2. span close, 3. Product title */
								esc_html__( '%1$sWrite a review for:%2$s %3$s', 'springoo' ),
								'<span class="sr-only">',
								'</span>',
								esc_html( wp_strip_all_tags( $product->get_title() ) )
							);
							?></h5>
						<span
							aria-hidden="true"><?php echo wp_kses_post( wc_format_content( $product->get_short_description() ? wc_trim_string( $product->get_short_description() ) : wc_trim_string( $product->get_description(), 20 ) ) ); ?></span>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
						<div id="review_form">
							<?php
							$commenter    = wp_get_current_commenter();
							$comment_form = array(
								/* translators: %s is product title */
								'title_reply'         => '',
								/* translators: %s is product title */
								'title_reply_to'      => '',
								'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
								'title_reply_after'   => '</span>',
								'comment_notes_after' => '',
								'label_submit'        => esc_html__( 'Submit Review', 'springoo' ),
								'logged_in_as'        => '',
								'comment_field'       => '',
							);

							$name_email_required = (bool) get_option( 'require_name_email', 1 );
							$fields              = array(
								'author' => array(
									'label'    => __( 'Name', 'springoo' ),
									'type'     => 'text',
									'value'    => $commenter['comment_author'],
									'required' => $name_email_required,
								),
								'email'  => array(
									'label'    => __( 'Email', 'springoo' ),
									'type'     => 'email',
									'value'    => $commenter['comment_author_email'],
									'required' => $name_email_required,
								),
							);

							$comment_form['fields'] = array();

							foreach ( $fields as $key => $field ) {
								$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
								$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

								if ( $field['required'] ) {
									$field_html .= '&nbsp;<span class="required">*</span>';
								}

								$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

								$comment_form['fields'][ $key ] = $field_html;
							}

							$account_page_url = wc_get_page_permalink( 'myaccount' );
							if ( $account_page_url ) {
								/* translators: %s opening and closing link tags respectively */
								$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'springoo' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
							}

							if ( wc_review_ratings_enabled() ) {
								$comment_form['comment_field'] .= '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Overall Rating', 'springoo' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required" title="' . esc_attr__( 'Required', 'springoo' ) . '">*</span>' : '' ) . '</label>';
								$comment_form['comment_field'] .= '<select name="rating" id="rating" required>';
								$comment_form['comment_field'] .= '<option value="">' . esc_html__( 'Rate&hellip;', 'springoo' ) . '</option>';
								$comment_form['comment_field'] .= '<option value="5">' . esc_html__( 'Perfect', 'springoo' ) . '</option>';
								$comment_form['comment_field'] .= '<option value="4">' . esc_html__( 'Good', 'springoo' ) . '</option>';
								$comment_form['comment_field'] .= '<option value="3">' . esc_html__( 'Average', 'springoo' ) . '</option>';
								$comment_form['comment_field'] .= '<option value="2">' . esc_html__( 'Not that bad', 'springoo' ) . '</option>';
								$comment_form['comment_field'] .= '<option value="1">' . esc_html__( 'Very poor', 'springoo' ) . '</option>';
								$comment_form['comment_field'] .= '</select></div>';
							}

							$gallery_field = '';

							if ( 'yes' === get_option( 'woocommerce_enable_review_image', 'yes' ) ) {

								$gallery_field = '<div class="comment-form-gallery" ><label for="review-gallery">' . esc_html__( 'Add Photo', 'springoo' ) . '</label><input type="file" id="review-gallery" name="review_gallery[]" multiple accept="image/jpeg,image/jpg,image/png"/></div>';

								if ( 'yes' === get_option( 'woocommerce_review_image_allow_if_verified', 'no' ) ) {
									if ( ! wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
										$gallery_field = '';
									}
								} else {
									if ( 'yes' === get_option( 'woocommerce_review_image_allow_if_logged_in', 'no' ) ) {
										if ( ! is_user_logged_in() ) {
											$gallery_field = '';
										}
									}
								}
							}

							$comment_form['comment_field'] .= $gallery_field;

							$comment_form['comment_field'] .= '<p class="comment-form-tilte"><label for="title">' . esc_html__( 'Title', 'springoo' ) . '</label><input type="text" id="title" name="comment_title" placeholder="' . esc_attr__( "What's most important to know?", 'springoo' ) . '"></p>';

							$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Write your Review', 'springoo' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required placeholder="' . esc_attr__( 'What did you like or dislike? What did you use this product for?', 'springoo' ) . '"></textarea></p>';

							comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
							?>
						</div>
					<?php else : ?>
						<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'springoo' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="clear"></div>
</div>

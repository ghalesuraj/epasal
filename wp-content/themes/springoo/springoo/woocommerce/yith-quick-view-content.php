<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

while ( have_posts() ) :
	the_post(); ?>

	<div class="product">
		<div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<?php
					do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>
				<div class="col-md-6 col-sm-12">
					<div class="summary entry-summary">
						<div class="summary-content">
							<?php do_action( 'woocommerce_single_product_summary' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php endwhile; ?>
<script>
	jQuery(document).ready(function($) {
		$('.quantity input.qty').after('<span class="qty-btn inc si si-thin-add"></span><span class="qty-btn dec si si-thin-minus"></span>');
	});
</script>

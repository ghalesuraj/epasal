<div id="dokan-seller-listing-wrap" class="grid-view v1">
	<div class="seller-listing-content">
		<?php
		$column = springoo_get_mod( 'dokan_template_store_columns' );
		?>
		<ul class="dokan-seller-wrap springoo-seller-wrap-preloader">
			<?php for ( $x = 1; $x <= $column; $x ++ ) { ?>
				<li class="dokan-single-seller woocommerce coloum-<?php echo esc_attr( $column ); ?> ">
					<div class="store-wrapper-preloader">
						<div class="store-header">
							<div class="store-banner">
								<div class="image"></div>
							</div>
							<div class="seller-avatar">
								<div class="avatar"></div>
							</div>
						</div>

						<div class="store-content">
							<div class="store-data-container">
								<div class="featured-favourite">
									<div class="featured-label"></div>
								</div>
								<div class="store-data">
									<h2></h2>
									<div class="dokan-seller-rating"></div>
									<p class="store-address"></p>
									<p class="store-email"></p>
									<p class="store-phone"></p>
								</div>
							</div>
						</div>

						<div class="store-footer">
							<div class="button"></div>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>

		<?php if ( $sellers['users'] ) : ?>
			<ul class="dokan-seller-wrap m-0">
				<?php
				foreach ( $sellers['users'] as $seller ) {
					$vendor                   = dokan()->vendor->get( $seller->ID );
					$store_banner_id          = $vendor->get_banner_id();
					$store_name               = $vendor->get_shop_name();
					$store_url                = $vendor->get_shop_url();
					$store_rating             = $vendor->get_rating();
					$is_store_featured        = $vendor->is_featured();
					$store_email              = $vendor->get_email();
					$store_phone              = $vendor->get_phone();
					$store_info               = dokan_get_store_info( $seller->ID );
					$store_address            = dokan_get_seller_short_address( $seller->ID );
					$store_banner_url         = $store_banner_id ? wp_get_attachment_image_src( $store_banner_id, $image_size ) : DOKAN_PLUGIN_ASSEST . '/images/default-store-banner.png';
					$show_store_open_close    = dokan_get_option( 'store_open_close', 'dokan_appearance', 'on' );
					$dokan_store_time_enabled = isset( $store_info['dokan_store_time_enabled'] ) ? $store_info['dokan_store_time_enabled'] : '';
					$store_open_is_on         = ( 'on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled && ! $is_store_featured ) ? 'store_open_is_on' : '';
					?>

					<li class="dokan-single-seller woocommerce coloum-<?php echo esc_attr( $per_row ); ?> <?php echo ( ! $store_banner_id ) ? 'no-banner-img' : ''; ?>">
						<div class="store-wrapper">
							<div class="store-header">
								<div class="store-banner">
									<a href="<?php echo esc_url( $store_url ); ?>">
										<img src="<?php echo is_array( $store_banner_url ) ? esc_attr( $store_banner_url[0] ) : esc_attr( $store_banner_url ); ?>">
									</a>
								</div>

								<div class="seller-avatar">
									<a href="<?php echo esc_url( $store_url ); ?>">
										<img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $vendor->get_shop_name() ); ?>" size="150">
									</a>
								</div>
							</div>

							<div
									class="store-content <?php echo ! $store_banner_id ? esc_attr( 'default-store-banner' ) : ''; ?>">
								<div class="store-data-container">
									<div class="featured-favourite">
										<?php if ( $is_store_featured ) : ?>
											<div class="featured-label"><?php esc_html_e( 'Featured', 'springoo' ); ?></div>
										<?php endif ?>

										<?php do_action( 'dokan_seller_listing_after_featured', $seller, $store_info ); ?>
									</div>
									<?php if ( 'on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled ) : ?>
										<?php if ( dokan_is_store_open( $seller->ID ) ) { ?>
											<span class="dokan-store-is-open-close-status dokan-store-is-open-status" title="<?php esc_attr_e( 'Store is Open', 'springoo' ); ?>"><?php esc_html_e( 'Open', 'springoo' ); ?></span>
										<?php } else { ?>
											<span class="dokan-store-is-open-close-status dokan-store-is-closed-status" title="<?php esc_attr_e( 'Store is Closed', 'springoo' ); ?>"><?php esc_html_e( 'Closed', 'springoo' ); ?></span>
										<?php } ?>
									<?php endif ?>

									<div class="store-data <?php echo esc_attr( $store_open_is_on ); ?>">
										<h2>
											<a href="<?php echo esc_url( $store_url ); ?>"><?php echo esc_html( $store_name ); ?></a> <?php apply_filters( 'dokan_store_list_loop_after_store_name', $vendor ); ?>
										</h2>

										<?php
										//phpcs:disable
										if ( ! empty( $store_rating['count'] ) ) : ?>
                                            <div class="dokan-seller-rating" title="
											<?php
											echo sprintf(
											// translators: 1) seller rating
												esc_attr__( 'Rated %s out of 5', 'springoo' ), number_format_i18n( $store_rating['rating'] )
											); ?>">
												<?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
                                                <p class="rating">
													<?php
													echo esc_html(
													// translators: 1) seller rating
														sprintf( __( '%s out of 5', 'springoo' ), number_format_i18n( $store_rating['rating'] ) )
													);
													?>
                                                </p>
                                            </div>
										<?php endif;
										//phpcs:enable
										?>

										<?php if ( ! dokan_is_vendor_info_hidden( 'address' ) && $store_address ) : ?>
											<?php
											$allowed_tags = [
												'span' => [
													'class' => [],
												],
												'br'   => [],
											];
											?>
											<p class="store-address"><?php echo wp_kses( $store_address, $allowed_tags ); ?></p>
										<?php endif ?>

										<?php if ( ! dokan_is_vendor_info_hidden( 'email' ) && $store_email ) { ?>
											<p class="store-email">
												<?php echo esc_html( $store_email ); ?>
											</p>
										<?php } ?>

										<?php if ( ! dokan_is_vendor_info_hidden( 'phone' ) && $store_phone ) { ?>
											<p class="store-phone">
												<?php echo esc_html( $store_phone ); ?>
											</p>
										<?php } ?>

										<?php do_action( 'dokan_seller_listing_after_store_data', $seller, $store_info ); ?>
									</div>
								</div>
							</div>

							<div class="store-footer">
								<a href="<?php echo esc_url( $store_url ); ?>" title="<?php esc_attr_e( 'Visit Store', 'springoo' ); ?>">
									<?php esc_html_e( 'Visit Store', 'springoo' ); ?>
									<i class="si si-thin-arrow-long-right"></i>
								</a>
								<?php do_action( 'dokan_seller_listing_footer_content', $seller, $store_info ); ?>
							</div>
						</div>
					</li>

				<?php } ?>
				<div class="dokan-clearfix"></div>
			</ul> <!-- .dokan-seller-wrap -->

			<?php
			$user_count   = $sellers['count'];
			$num_of_pages = ceil( $user_count / $limit );

			if ( $num_of_pages > 1 ) {
				echo '<div class="pagination-container clearfix">';

				$pagination_args = [
					'current'   => $paged,
					'total'     => $num_of_pages,
					'base'      => $pagination_base,
					'type'      => 'array',
					'prev_text' => __( '&larr;', 'springoo' ),
					'next_text' => __( '&rarr;', 'springoo' ),
				];

				if ( ! empty( $search_query ) ) {
					$pagination_args['add_args'] = [
						'dokan_seller_search' => $search_query,
					];
				}

				$page_links = paginate_links( $pagination_args );

				if ( $page_links ) {
					$pagination_links  = '<div class="pagination-wrap">';
					$pagination_links .= '<ul class="pagination"><li>';
					$pagination_links .= join( "</li>\n\t<li>", $page_links );
					$pagination_links .= "</li>\n</ul>\n";
					$pagination_links .= '</div>';

					echo wp_kses_post( $pagination_links );
				}

				echo '</div>';
			}
			?>


		<?php else : ?>
			<p class="dokan-error mt-0"><?php esc_html_e( 'No vendor found!', 'springoo' ); ?></p>
		<?php endif; ?>

	</div>
</div>

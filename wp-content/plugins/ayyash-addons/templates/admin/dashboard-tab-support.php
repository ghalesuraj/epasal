<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

use AyyashAddons\AyyashPluginsServices\Ayyash_Addons_Services;

$service = Ayyash_Addons_Services::get_instance();
?>
	<div class="ayyash-addons-settings-panel support">
		<div class="ayyash-addons-settings-panel__body">
			<!--<div class="support-wrapper">
				<div class="support-item">
					<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/video-icon.png' ); */?>" alt="<?php /*esc_attr_e( 'Check Our Video Tutorials', 'ayyash-addons' ); */?>">
					<h3><?php /*esc_html_e( 'Video Tutorials', 'ayyash-addons' ); */?></h3>
					<p><?php /*esc_html_e( 'Learn, how to use Absolute Widgets and manage them?', 'ayyash-addons' ); */?></p>
					<div class="row">
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=59knMAjukUw" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/advance_accordion.jpg') */?>" alt="<?php /*esc_attr_e( 'Advance Accordion', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Advance Accordion', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=Uyxm0XX9Y2A" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/advance_tab.jpg') */?>" alt="<?php /*esc_attr_e( 'Advance Tab', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Advance Tab', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=fPpMO6kEWWU" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/call_to_action.jpg') */?>" alt="<?php /*esc_attr_e( 'Call To Action', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Call To Action', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=K76eWrXz6TM" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/counter.jpg') */?>" alt="<?php /*esc_attr_e( 'Counter', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Counter', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=3IQTWcSCPM0" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/faq.jpg') */?>" alt="<?php /*esc_attr_e( 'FAQ', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'FAQ', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=nho_QQ-rXgc" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/fun_fact.jpg') */?>" alt="<?php /*esc_attr_e( 'Fun Fact', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Fun Fact', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=dT52WL9vzn8" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/icon_box.jpg') */?>" alt="<?php /*esc_attr_e( 'Icon Box', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Icon Box', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=qNzc1Xi4vdA" data-fancybox>
								<img src="<?php /*ayyash_addons_plugin_url( 'assets/images/image_carousel.jpg' ) */?>"  alt="<?php /*esc_attr_e( 'Image Carousel', 'ayyash-addons' ); */?>">
								<h5><?php /*esc_html_e( 'Image Carousel', 'ayyash-addons' ); */?></h5>
							</a>
						</div>
					</div>
					<div class="support-btn">
						<a href="https://www.youtube.com/watch?v=Pn840eDpNC4&list=UUPo-5ZNIiNBux6ciUlNcgfw"><?php /*esc_html_e( 'Check More Video', 'ayyash-addons' ); */?></a>
					</div>
				</div>
			</div>-->

			<div class="support-wrapper">
				<div class="support-item">
					<img src="<?php ayyash_addons_plugin_url( 'assets/images/question.png' ); ?>" alt="<?php esc_attr_e( 'Have questions?', 'ayyash-addons' ); ?>">
					<h3><?php esc_html_e( 'FAQ', 'ayyash-addons' ); ?></h3>
					<p><?php esc_html_e( 'Frequently Asked Questions', 'ayyash-addons' ); ?></p>

					<div class="advance-accordion">
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Where to find the documentation for Ayyash addon? ', 'ayyash-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'You will find Ayyash Addons documentation from here. If you are stuck with anything, feel free to contact our friendly and professional support team. ', 'ayyash-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'How often do you update Ayyash Addons? ', 'ayyash-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'With our fully dedicated team, we are always working to improve and add new features to Ayyash Addons. In general, we always try to update our plugin on a weekly basis.', 'ayyash-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Is Ayyash Addons responsive for the smaller devices? ', 'ayyash-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'Yes, all the widgets and elements are fully responsive for all the available devices. ', 'ayyash-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Does Ayyash Addons support multisite? ', 'ayyash-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'Yes, Ayyash Addons is fully multisite compatible.', 'ayyash-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Do Ayyash Addons require any coding skills? ', 'ayyash-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'No, you don’t need any coding skills to use our widgets. All of our widgets come with drag & drop capability. ', 'ayyash-addons' ); ?></p>
							</div>
						</article>
					</div>

					<div class="support-btn">
						<a href="https://wpayyash.com/docs/ayyash-addons/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=show-more-docs"><?php esc_html_e( 'Show More', 'ayyash-addons' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="ayyash-addons-settings-panel__footer">
			<div class="ayyash-addons-cta-area">
				<div class="ayyash-addons-cta-wrapper" style="align-items: flex-start;">
					<div class="ayyash-addons-left-content">
						<h3><?php esc_html_e( 'Need Support Faster!', 'ayyash-addons' ); ?></h3>
						<p style="margin-bottom:0;"><?php esc_html_e( "We don't share your data with third parties, while keeping them encrypted in our server.", 'ayyash-addons' ); ?></p>
						<p style="font-size:14px;margin-top:0;"><?php esc_html_e( "We use this data for support, debugging and for improving this plugin and it's performance.", 'ayyash-addons' ); ?></p>
						<ul class="what-collected" style="display: none">
							<?php foreach ( $service->get_data_we_collect() as $line ) { ?>
								<li><?php echo esc_html( $line ); ?></li>
							<?php } ?>
						</ul>
					</div>
					<div class="ayyash-addons-cta-btn" style="margin: 17px auto;text-align:center;display:block;">
						<?php if ( ! $service->is_tracking_allowed() ) { ?>
						<a href="<?php echo esc_url( $service->opt_in_url() ); ?>#support" style="display:inline-block" class="cta-btn"><?php esc_html_e( 'Enable Data Sharing', 'ayyash-addons' ); ?></a>
						<?php } else { ?>
						<a
							href="<?php echo esc_url( $service->opt_out_url() ); ?>#support"
							onclick="return confirm('<?php esc_attr_e( 'Are you sure you want disable data sharing?', 'ayyash-addons' ); ?>')"
							style="--clr-white: #f02f5e;background: transparent;border: 1px solid #f02f5e;padding: 10px 20px;font-weight: 400;font-size:14px;"
							class="cta-btn"
						><?php esc_html_e( 'Disable Data Sharing', 'ayyash-addons' ); ?></a>
						<?php } ?>
						<span class="terms" style="font-size:0.75em;margin-top:10px;">
							<a href="#" onclick="jQuery('.what-collected').toggleSlide(); return false;"><?php esc_html_e( 'What we collect collecting?', 'ayyash-addons' ); ?></a>
							<?php
							printf(
								/* translators: 1: Privacy Policy Link, 2: Terms Links */
								esc_html__( 'Please read our %1$s and %2$s', 'ayyash-addons' ),
								'<a href="https://wpayyash.com/privacy-policy/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=tracker-policy" target="_blank" rel="noopener">' . esc_html__( 'Privacy Policy', 'ayyash-addons' ) . '</a>',
								'<a href="https://wpayyash.com/terms-of-service/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=tracker-terms" target="_blank" rel="noopener">' . esc_html__( 'Terms of Services', 'ayyash-addons' ) . '</a>'
							);
							?>
						</span>
					</div>
				</div>
			</div>

			<div class="support-ticket">
				<div class="support-ticket-thumb">
					<img src="<?php ayyash_addons_plugin_url( 'assets/images/ticket-thumb.png' ); ?>" alt="<?php esc_attr_e( 'Get Support', 'ayyash-addons' ); ?>">
				</div>
				<div class="support-ticket-content">
					<h3><?php esc_html_e( 'Need more support?', 'ayyash-addons' ); ?></h3>
					<p></p>
					<span>
					<?php
					printf(
						/* translators: 1. WordPress Support Forum Link, 2. Facebook Page Link, 3. Live Chat Site Link. */
						esc_html__( 'Stuck with something? Get help from the community on %1$s or %2$s. In case of emergency, initiate a live chat at %3$s website.', 'ayyash-addons' ),
						'<a href="https://wordpress.org/support/plugin/absolute-addons/">' . esc_html__( 'WordPress.org Forum', 'ayyash-addons' ) . '</a>',
						'<a href="https://www.facebook.com/AbsoluteAddons/">' . esc_html__( 'Facebook Community', 'ayyash-addons' ) . '</a>',
						'<a href="https://wpayyash.com/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=support-live-chat">' . esc_html__( 'Ayyash Addons', 'ayyash-addons' ) . '</a>'
					);
					?>
					</span>
				</div>
				<div class="support-button">
					<a href="https://wpayyash.com/open-support-request/?utm_source=plugin-dashboard&utm_medium=open-ticket&utm_campaign=support-request"><?php esc_html_e( 'Open a Ticket', 'ayyash-addons' ); ?></a>
				</div>
			</div>
		</div>
	</div>
<?php
// End of file dashboard-tab-support.php.

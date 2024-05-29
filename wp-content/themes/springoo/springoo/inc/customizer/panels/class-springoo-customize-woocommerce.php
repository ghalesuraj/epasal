<?php
/**
 * @package Springoo
 * @author  Springoo
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Contains methods for adding Layout Customization Panel and all settings under it
 *
 * @since Pxlrtheme 1.0
 */
class Springoo_Customize_WooCommerce {

	/**
	 * Register panel and add settings.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer Instance.
	 *
	 * @return void
	 */
	public static function register( $wp_customize ) {

		$panel_id = 'woocommerce';

		$wp_customize->add_panel(
			$panel_id,
			array(
				'priority'       => 35,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => 'WooCommerce',
			)
		);

		// Woocommerce General.
		$section_id = 'woocommerce_general';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'General', 'springoo' ),
				'priority'   => 9,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		$setting_id = $section_id . '_enable_product_flying_cart';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Enable Product Flying Cart', 'springoo' ),
					'description' => __(
						'Enable product zoom feature at single product page',
						'springoo'
					),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
				)
			)
		);

		// Enable Product Zoom.
		$setting_id = $section_id . '_enable_product_zoom';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Enable Product Zoom', 'springoo' ),
					'description' => __(
						'Enable product zoom feature at single product page',
						'springoo'
					),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
				)
			)
		);

		// Enable Product Lightbox.
		$setting_id = $section_id . '_enable_product_lightbox';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Enable Product Lightbox', 'springoo' ),
					'description' => __(
						'Enable product lightbox feature at single product page',
						'springoo'
					),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
				)
			)
		);

		// Enable Product Gallery Slider.
		$setting_id = $section_id . '_enable_product_gallery_slider';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Enable Product Gallery Slider', 'springoo' ),
					'description' => __(
						'Enable product gallery slider feature at single product page',
						'springoo'
					),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'checkbox',
				)
			)
		);

		//Discount Label Text Prefix
		$setting_id = $section_id . '_discount_label_text_prefix';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Discount Label Text Prefix', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		//Discount Label Text Suffix

		$setting_id = $section_id . '_discount_label_text_suffix';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Discount Label Text Suffix', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);


		// Share Product.
		$section_id = 'woocommerce_share_product';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Share Product', 'springoo' ),
				'priority'   => 9,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		$setting_id = $section_id . '_fb_share';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Facebook App ID', 'springoo' ),
					'description' => __( 'Enter your Facebook App ID to enable Facebook share button', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'text',
				)
			)
		);

		$setting_id = $section_id . '_twitter_share';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Twitter Username', 'springoo' ),
					'description' => __(
						'Enter your Twitter username to enable Twitter share button',
						'springoo'
					),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'type'        => 'text',
				)
			)
		);

		// Woocommerce Shop & Archive.
		$section_id = 'woocommerce_shop_archive';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Shop', 'springoo' ),
				'priority'   => 9,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		// Shop page Title Bar.
		$setting_id = $section_id . '_title-bar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop & Archive Title.
		$setting_id = $section_id . '_title';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Shop Title', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Shop & Archive Post Per page.
		$setting_id = $section_id . '_per_page';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Shop Posts Per Page', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Shop Layout.
		$setting_id = $section_id . '_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop Layout.
		$setting_id = $section_id . '_sidebar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Shop Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop Column.
		$setting_id = $section_id . '_column';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Shop Column', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop & Archive Shop Result Count.
		$setting_id = $section_id . '_sort';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Shop Sort', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Shop & Archive Shop Result Count.
		$setting_id = $section_id . '_result_count';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Shop Result Count', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// phpcs:disable Squiz.PHP.CommentedOutCode.Found
		// @TODO Check with Niamul Vai.
		/*
		$setting_id = $section_id . '_product_archive_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Choose Product Archive Layout', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);*/
		// phpcs:enable

		// Product Catalogue.
		$section_id = 'woocommerce_product_catalog';

		$setting_id = $section_id . '_title-bar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_sidebar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Product Category Archive Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Woocommerce Single.
		$section_id = 'woocommerce_single';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Single', 'springoo' ),
				'priority'   => 13,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		// Shop Single Layout.
		$setting_id = $section_id . '_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop Single Layout.
		$setting_id = $section_id . '_sidebar';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Single Product Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop Single Up-Sells Count.
		$setting_id = $section_id . '_upsells_count';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_attr',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Up-Sells Count', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Shop Single Up-Sells Column.
		$setting_id = $section_id . '_upsells_columns';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Up-Sells Column', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);



		// Shop Single Related Item Count.
		$setting_id = $section_id . '_related_item_count';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Related Item Count', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Shop Single Related Item Column.
		$setting_id = $section_id . '_related_product_column';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Related Products Column', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop Single attribute Label.
		$setting_id = $section_id . '_attribute_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);


		// Shop Single Review
		$setting_id = $section_id . '_reviews_note';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Reviews Note', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		// Shop Single Review Note Enable
		$setting_id = $section_id . '_reviews_note_enable';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Reviews Note', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'checkbox',
				)
			)
		);

		// Shop Single Review Note Label
		$setting_id = $section_id . '_reviews_note_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Reviews Note Label ', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Shop Single Review Note Content
		$setting_id = $section_id . '_reviews_note_content';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Textarea_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => __( 'Reviews Note', 'springoo' ),
					'section'     => $section_id,
					'settings'    => $setting_id,
					'description' => __( 'For multiple note use comma to separate ', 'springoo' ),
				)
			)
		);

		// Woocommerce shop category and tag page.
		$section_id = 'woocommerce_taxonomy_archive';
		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Archive', 'springoo' ),
				'priority'   => 13,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		$setting_id = $section_id . '_cat_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Category Archive settings', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		//layout
		$setting_id = $section_id . '_cat_layout';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// sidebar.
		$setting_id = $section_id . '_cat_sidebar';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Category Archive Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_tag_label';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'helptext',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Springoo_Customize_Misc_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => __( 'Tag Archive settings', 'springoo' ),
					'section' => $section_id,
					'type'    => 'helptext',
				)
			)
		);

		//layout
		$setting_id = $section_id . '_tag_layout';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// sidebar.
		$setting_id = $section_id . '_tag_sidebar';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Tag Archive Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Woocommerce Cart.
		$section_id = 'woocommerce_cart';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Cart', 'springoo' ),
				'priority'   => 15,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		$setting_id = $section_id . '_title-bar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Cart Layout Settings.
		$setting_id = $section_id . '_sidebar_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Cart Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Shop Single Layout.
		$setting_id = $section_id . '_sidebar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Cart Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Cart Up-Sells Count.
		$setting_id = $section_id . '_cross_sell_count';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Cross Sell Count', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
				)
			)
		);

		// Cart Up-Sells Column.
		$setting_id = $section_id . '_cross_sell_column';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Cross Sell Column', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// Woocommerce Checkout.
		$section_id = 'woocommerce_checkout';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'Checkout', 'springoo' ),
				'priority'   => 16,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		$setting_id = $section_id . '_title-bar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_sidebar_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Cart Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_sidebar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Checkout Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		// My Account Page Settings.
		$section_id = 'woocommerce_myaccount';

		$wp_customize->add_section(
			$section_id,
			array(
				'title'      => __( 'My Account', 'springoo' ),
				'priority'   => 16,
				'capability' => 'edit_theme_options',
				'panel'      => $panel_id,
			)
		);

		$setting_id = $section_id . '_title-bar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Show Breadcrumb ?', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_sidebar_layout';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Cart Sidebar Position', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_sidebar';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => springoo_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'springoo_sanitize_choice',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( 'Checkout Sidebar', 'springoo' ),
					'section'  => $section_id,
					'settings' => $setting_id,
					'type'     => 'select',
					'choices'  => springoo_get_choices( $setting_id ),
				)
			)
		);

		$setting_id = $section_id . '_sign_in_image';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'         => __( 'Signin image', 'springoo' ),
					'description'   => __( 'Will be display in WooCommerce Sign in form page', 'springoo' ),
					'section'       => $section_id,
					'settings'      => $setting_id,
					'height'        => '',
					'width'         => '',
					'flex_height'   => '',
					'flex_width'    => '',
					'button_labels' => array(
						'select'       => __( 'Select Signin image', 'springoo' ),
						'change'       => __( 'Change Signin image', 'springoo' ),
						'remove'       => __( 'Remove', 'springoo' ),
						'default'      => __( 'Default', 'springoo' ),
						'placeholder'  => __( 'No Signin image selected', 'springoo' ),
						'frame_title'  => __( 'Select Signin image', 'springoo' ),
						'frame_button' => __( 'Choose Signin image', 'springoo' ),
					),
				)
			)
		);

		$setting_id = $section_id . '_sign_up_image';

		$wp_customize->add_setting(
			$setting_id,
			array(
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'         => __( 'Signup image', 'springoo' ),
					'description'   => __( 'Will be display in WooCommerce Sign in form page when user registration is enabled for myaccount page from <b>WooCommerce > Settings > Accounts & Privacy > account creation</b> section', 'springoo' ),
					'section'       => $section_id,
					'settings'      => $setting_id,
					'height'        => '',
					'width'         => '',
					'flex_height'   => '',
					'flex_width'    => '',
					'button_labels' => array(
						'select'       => __( 'Select Signup image', 'springoo' ),
						'change'       => __( 'Change Signup image', 'springoo' ),
						'remove'       => __( 'Remove', 'springoo' ),
						'default'      => __( 'Default', 'springoo' ),
						'placeholder'  => __( 'No Signup image selected', 'springoo' ),
						'frame_title'  => __( 'Select Signup image', 'springoo' ),
						'frame_button' => __( 'Choose Signup image', 'springoo' ),
					),
				)
			)
		);
	}
}

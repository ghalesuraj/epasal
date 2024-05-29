<?php
/**
 * Admin feature for Custom Meta Box
 *
 * @author ThemeRox
 * @category PostMetaBox
 * @package springoo\Core
 * -------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Control core classes for avoid errors.
if ( class_exists( 'CSF' ) ) {

	/**
	 * Post type Audio Metabox.
	 */

	$springoo_audio = 'springoo_audio';

	CSF::createMetabox( $springoo_audio,
		[
			'title'         => esc_html__('Post Audio Settings', 'springoo-pro'),
			'post_type'     => array( 'page', 'post' ),
			'priority'      => 'high',
			'post_formats'  => 'audio',
			'data_type'     => 'unserialize',
			'show_restore'  => true,
			'save_defaults' => true,
			'ajax_save'     => true,
		]
	);

	CSF::createSection(
		$springoo_audio, [
			'fields' => [
				[
					'title'   => esc_html__( 'Select Audio Source', 'springoo-pro' ),
					'id'      => "{$springoo_audio}_source",
					'type'    => 'select',
					'options' => [
						'embedded' => esc_html__( 'Embedded Audio', 'springoo-pro' ),
						'hosted'   => esc_html__( 'Self Hosted Audio', 'springoo-pro' ),
					],
				],
				[
					'title'      => esc_html__( 'Audio Embed Code', 'springoo-pro' ),
					'id'         => "{$springoo_audio}_embedded",
					'desc'       => esc_html__( 'Write Your Audio Embed Code Here', 'springoo-pro' ),
					'type'       => 'text',
					'attributes' => [ 'type' => 'url' ],
					'dependency' => [ "{$springoo_audio}_source", '==', 'embedded' ],
				],
				[
					'title'      => esc_html__( 'Upload a audio file', 'springoo-pro' ),
					'desc'       => esc_html__( 'Audio file only', 'springoo-pro' ),
					'id'         => "{$springoo_audio}_hosted",
					'type'       => 'media',
					'library'    => 'audio',
					'dependency' => [ "{$springoo_audio}_source", '==', 'hosted' ],
				],
			],
		]
	);

	/**
	 * Post type Gallery Metabox.
	 */

	$springoo_gallery = 'springoo_gallery';

	CSF::createMetabox( $springoo_gallery,
		[
			'title'         => esc_html__('Post Gallery Settings', 'springoo-pro'),
			'post_type'     => array( 'page', 'post' ),
			'priority'      => 'high',
			'post_formats'  => 'gallery',
			'data_type'     => 'unserialize',
			'show_restore'  => true,
			'save_defaults' => true,
			'ajax_save'     => true,
		]
	);

	CSF::createSection(
		$springoo_gallery, [
			'fields' => [
				[
					'title' => esc_html__( 'Gallery Image Upload', 'springoo-pro' ),
					'id'    => $springoo_gallery,
					'desc'  => esc_html__( 'Add Gallery Image here', 'springoo-pro' ),
					'type'  => 'gallery',
				],
			],
		]
	);

	/**
	 * Post type Link Metabox.
	 */

	$springoo_link = 'springoo_link';

	CSF::createMetabox( $springoo_link, array(
		'title'         => esc_html__('Post Link Settings', 'springoo-pro'),
		'post_type'     => array( 'page', 'post' ),
		'priority'      => 'high',
		'post_formats'  => 'link',
		'data_type'     => 'unserialize',
		'show_restore'  => true,
		'save_defaults' => true,
		'ajax_save'     => true,
	));

	CSF::createSection(
		$springoo_link, [
			'fields' => [
				[
					'title'      => esc_html__( 'Link URL', 'springoo-pro' ),
					'id'         => "{$springoo_link}_url",
					'desc'       => esc_html__( 'Write Your Link', 'springoo-pro' ),
					'type'       => 'text',
					'attributes' => [
						'type' => 'url',
					],
				],
			],
		]
	);

	/**
	 * Post type Quote Metabox.
	 */

	$springoo_quote = 'springoo_quote';

	CSF::createMetabox( $springoo_quote, array(
		'title'         => esc_html__('Post Quote Settings', 'springoo-pro'),
		'post_type'     => array( 'page', 'post' ),
		'priority'      => 'high',
		'post_formats'  => 'quote',
		'data_type'     => 'unserialize',
		'show_restore'  => true,
		'save_defaults' => true,
		'ajax_save'     => true,
	));

	CSF::createSection(
		$springoo_quote, array(
			'fields' => array(
				array(
					'title' => esc_html__('Quote Text', 'springoo-pro'),
					'id'    => "{$springoo_quote}_text",
					'desc'  => esc_html__('Write Your Quote Here', 'springoo-pro'),
					'type'  => 'textarea',
				),
				array(
					'title' => esc_html__('Quote Author', 'springoo-pro'),
					'id'    => "{$springoo_quote}_author",
					'desc'  => esc_html__('Write Quote Author or Source', 'springoo-pro'),
					'type'  => 'text',
				),
			),
		)
	);

	/**
	 * Post type Video Metabox.
	 */

	$springoo_video = 'springoo_video';

	CSF::createMetabox( $springoo_video,
		[
			'title'         => esc_html__('Post Video Settings', 'springoo-pro'),
			'post_type'     => array( 'page', 'post' ),
			'priority'      => 'high',
			'post_formats'  => 'video',
			'data_type'     => 'unserialize',
			'show_restore'  => true,
			'save_defaults' => true,
			'ajax_save'     => true,
		]
	);

	CSF::createSection(
		$springoo_video, [
			'fields' => [
				[
					'title'   => esc_html__( 'Select Video Type/Source', 'springoo-pro' ),
					'id'      => "{$springoo_video}_source",
					'type'    => 'select',
					'options' => [
						'embedded' => esc_html__( 'Embedded Video', 'springoo-pro' ),
						'hosted'   => esc_html__( 'Self Hosted Video', 'springoo-pro' ),
					],
				],
				[
					'title'      => esc_html__( 'Upload a audio file', 'springoo-pro' ),
					'desc'       => esc_html__( 'Video file only', 'springoo-pro' ),
					'id'         => "{$springoo_video}_hosted",
					'type'       => 'media',
					'library'    => 'video',
					'dependency' => [ "{$springoo_video}_source", '==', 'hosted' ],
				],
				[
					'title'      => esc_html__( 'Video URL', 'springoo-pro' ),
					'id'         => "{$springoo_video}_embedded",
					'desc'       => esc_html__( 'Write Your Video Embed ID Here', 'springoo-pro' ),
					'type'       => 'text',
					'attributes' => [ 'type' => 'url' ],
					'dependency' => [ "{$springoo_video}_source", '==', 'embedded' ],
				],
				[
					'title'      => __( 'Video Durations', 'springoo-pro' ),
					'id'         => "{$springoo_video}_durations",
					'type'       => 'text',
					'dependency' => [ "{$springoo_video}_source", '==', 'hosted' ],
				],
			],
		]
	);

	// Add metabox for Product Specification
	$product_specification = 'springoo_product_specification';

	CSF::createMetabox( $product_specification,
		[
			'title'         => esc_html__( 'Specification', 'springoo-pro' ),
			'post_type'     => 'product',
			'priority'      => 'high',
			'data_type'     => 'unserialize',
			'show_restore'  => true,
			'save_defaults' => true,
			'ajax_save'     => true,
		]
	);

	CSF::createSection(
		$product_specification, [
			'fields' => [
				[
					'title'  => esc_html__( 'Specification', 'springoo-pro' ),
					'id'     => "{$product_specification}_repeater",
					'type'   => 'repeater',
					'fields' => [
						[
							'title' => esc_html__( 'Specification Title', 'springoo-pro' ),
							'id'    => "{$product_specification}_title",
							'type'  => 'text',
						],
						[
							'title' => esc_html__( 'Specification Value', 'springoo-pro' ),
							'id'    => "{$product_specification}_value",
							'type'  => 'text',
						],
					],
					'default' => [
						[
							'springoo_product_specification_title' => 'Weight',
							'springoo_product_specification_value' => '340 kg',
						],
						[
							'springoo_product_specification_title' => 'Dimensions',
							'springoo_product_specification_value' => '70 × 500 × 700 cm',
						],
						[
							'springoo_product_specification_title' => 'Color',
							'springoo_product_specification_value' => 'Gray, Green, Red, Yellow',
						],
						[
							'springoo_product_specification_title' => 'Size',
							'springoo_product_specification_value' => 'L, M, S',
						],
					],
				],
			],

		]
	);
}


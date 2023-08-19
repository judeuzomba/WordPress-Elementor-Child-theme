<?php
/**
 * Register the listings archive editor for Elementor Pro.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace PropertyBuilder\Elementor\Documents;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use ElementorPro\Modules\ThemeBuilder\Documents\Archive;
use ElementorPro\Plugin;

/**
 * Defines a custom elementor document to override the listings taxonomy terms archives pages.
 */
class PropertyArchive extends Archive {

	/**
	 * Get things started.
	 *
	 * @param array $data I have no idea.
	 */
	public function __construct( array $data = array() ) {

		parent::__construct( $data );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );
	}

	/**
	 * Properties for the document.
	 *
	 * @return array
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location']       = 'archive';
		$properties['condition_type'] = 'property_archive';

		return $properties;
	}


	/**
	 * Name of the document.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'property-archive';
	}

	/**
	 * The title displayed within the UI.
	 *
	 * @return string
	 */
	public static function get_title() {
		return esc_html__( 'Property archive', 'posterno-elementor' );
	}

	/**
	 * Load any assets required for the preview.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {

		}
	}

	/**
	 * Additional attributes to load for this specific container.
	 *
	 * @return array
	 */
	public function get_container_attributes() {
		$attributes = parent::get_container_attributes();

		$attributes['class'] .= ' listing';

		return $attributes;
	}

	/**
	 * Load a custom body class while within the editor.
	 *
	 * @param array $body_classes list of classes.
	 * @return array
	 */
	public function filter_body_classes( $body_classes ) {
		$body_classes = parent::filter_body_classes( $body_classes );

		if ( get_the_ID() === $this->get_main_id() || Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {
			$body_classes[] = 'posterno';
		}

		return $body_classes;
	}

	/**
	 * Automatically set a custom preview for the editor.
	 *
	 * @return string
	 */
	public static function get_preview_as_default() {
		return 'post_type_archive/listings';
	}

	/**
	 * Generates the list of preview options for the editor.
	 *
	 * @return array
	 */
	public static function get_preview_as_options() {
		$post_type_archives = array();
		$taxonomies         = array();
		$post_type          = 'property';

		$post_type_object = get_post_type_object( $post_type );

		$post_type_archives[ 'post_type_archive/' . $post_type ] = $post_type_object->label . ' ' . esc_html__( 'Archive', 'posterno-elementor' );

		$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

		$post_type_taxonomies = wp_filter_object_list(
			$post_type_taxonomies,
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			)
		);

		foreach ( $post_type_taxonomies as $slug => $object ) {
			$taxonomies[ 'taxonomy/' . $slug ] = $object->label . ' ' . esc_html__( 'Archive', 'posterno-elementor' );
		}

		$options = array(
			'search' => esc_html__( 'Search results', 'posterno-elementor' ),
		);

		$options += $taxonomies + $post_type_archives;

		return array(
			'archive' => array(
				'label'   => esc_html__( 'Archive', 'posterno-elementor' ),
				'options' => $options,
			),
		);
	}

	/**
	 * Prepare the categories to be displayed for the editor.
	 *
	 * @return array
	 */
	protected static function get_editor_panel_categories() {
		$categories = parent::get_editor_panel_categories();

		unset( $categories['theme-elements-archive'] );

		return $categories;
	}

	/**
	 * Automatically set a preview type for the control.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'preview_type',
			array(
				'default' => 'post_type_archive/property',
			)
		);
	}

}

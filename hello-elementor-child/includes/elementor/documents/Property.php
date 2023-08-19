<?php
/**
 * Register the single listing theme editor for Elementor Pro.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace PropertyBuilder\Elementor\Documents;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use ElementorPro\Modules\ThemeBuilder\Documents\Single;
use ElementorPro\Plugin;

/**
 * Defines a custom elementor document to override the single listings pages.
 */
class Property extends Single {

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

		$properties['location']       = 'single';
		$properties['condition_type'] = 'properties';

		return $properties;
	}


	/**
	 * Name of the document.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'properties';
	}

	/**
	 * The title displayed within the UI.
	 *
	 * @return string
	 */
	public static function get_title() {
		return esc_html__( 'Single Property', 'posterno-elementor' );
	}

	/**
	 * Enqueue custom scripts within the editor when needed.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {
		}
	}

	/**
	 * Custom attributes for containers.
	 *
	 * @return array
	 */
	public function get_container_attributes() {
		$attributes = parent::get_container_attributes();

		$attributes['class'] .= ' listing pno-single-listing-wrapper';

		return $attributes;
	}

	/**
	 * Add a custom body class to the single listing page.
	 *
	 * @param array $body_classes list of classes.
	 * @return array
	 */
	public function filter_body_classes( $body_classes ) {
		$body_classes = parent::filter_body_classes( $body_classes );

		if ( get_the_ID() === $this->get_main_id() || Plugin::elementor()->preview->is_preview_mode( $this->get_main_id() ) ) {
			$body_classes[] = 'listing';
		}

		return $body_classes;
	}

	/**
	 * Load custom hooks before the content if neeed.
	 *
	 * @return void
	 */
	public function before_get_content() {

		remove_all_filters( 'the_content' );

		/**
		 * Hook: triggers before the content of the single listing page is displayed.
		 */
		do_action( 'pno_before_single_listing' );

		parent::before_get_content();

	}

	/**
	 * Load custom hooks after the content if needed.
	 *
	 * @return void
	 */
	public function after_get_content() {

		parent::after_get_content();

		/**
		 * Hook: triggers after the content of the single listing page is displayed.
		 */
		do_action( 'pno_after_single_listing' );

	}

	/**
	 * Protect the content when needed.
	 *
	 * @return void
	 */
	public function print_content() {
		if ( post_password_required() ) {
			echo get_the_password_form(); //phpcs:ignore
			return;
		}

		parent::print_content();
	}

	/**
	 * Automatically load a custom listing when viewing the editor.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'preview_type',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'single/listings',
			)
		);

		$latest_posts = get_posts(
			array(
				'posts_per_page' => 1,
				'post_type'      => 'property',
			)
		);

		if ( ! empty( $latest_posts ) ) {
			$this->update_control(
				'preview_id',
				array(
					'default' => $latest_posts[0]->ID,
				)
			);
		}
	}

}

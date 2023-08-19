<?php
/**
 * Registers custom conditions for the template builder in elementor pro.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace PropertyBuilder\Elementor\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use \ElementorPro\Modules\ThemeBuilder as ThemeBuilder;

/**
 * Registers listings related conditions.
 */
class Property extends ThemeBuilder\Conditions\Condition_Base {

	/**
	 * Condition type.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'builder';
	}

	/**
	 * Condition name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'builder';
	}

	/**
	 * Condition label.
	 *
	 * @return int
	 */
	public function get_label() {
		return esc_html__( 'Property', 'posterno-elementor' );
	}

	/**
	 * Condition "all" label.
	 *
	 * @return bool
	 */
	public function get_all_label() {
		return false;
	}

	/**
	 * Register sub conditions for the main module.
	 *
	 * @return void
	 */
	public function register_sub_conditions() {
        include_once( 'PropertyArchive.php' );
		$listings_archive = new PropertyArchive();

		$listings_single = new ThemeBuilder\Conditions\Post(
			array(
				'post_type' => 'property',
			)
		);

		$this->register_sub_condition( $listings_archive );
		$this->register_sub_condition( $listings_single );

	}

	/**
	 * Trigger verification for main module.
	 *
	 * @param array $args sent for verification.
	 * @return bool
	 */
	public function check( $args ) {
		return is_post_type_archive( 'property' ) || is_tax( get_object_taxonomies( 'property' ) );
	}

}

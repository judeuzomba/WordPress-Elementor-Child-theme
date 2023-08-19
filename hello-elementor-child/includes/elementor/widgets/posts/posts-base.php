<?php
namespace Zen\Elementor\Widgets\Zen_Posts;

use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use ElementorPro\Base\Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Posts
 */
abstract class Posts_Base extends Base_Widget {

	/**
	 * @var \WP_Query
	 */
	protected $query = null;

	protected $_has_template_content = false;

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);



		$this->end_controls_section();
	}

	public function render_plain_content() {}
}

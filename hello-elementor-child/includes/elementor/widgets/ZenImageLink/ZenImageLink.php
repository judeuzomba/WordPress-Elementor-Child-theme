<?php
namespace Zen\Elementor\Widgets\ZenImageLink;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;


class ZenImageLink extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
//        wp_register_script('zen-image-link-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenImageLink/assets/js/base-script.js', '', '1', true);
//        wp_register_style('zen-image-link-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenImageLink/assets/css/base-main.css', '', 1);
    }

    public function get_script_depends() {
        return ['zen-image-link-script'];
    }

    public function get_style_depends() {
        return ['zen-image-link-style'];
    }

	public function get_name() {
		return 'zen_image-link';
	}


	public function get_title() {
		return __( 'Zen Instagram Link', 'elementor' );
	}


	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'url', 'insta' ];
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Data', 'elementor' ),
			]
		);

        $this->add_responsive_control (
            'zen_image_height',
            [
                'label' => __( 'Height', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 400,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .zen-instagram-block' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'link_to_image',
            [
                'label' => __( 'Link to image', 'elementor' ),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'link_to_post',
            [
                'label' => __( 'Link to post', 'elementor' ),
                'type' => Controls_Manager::TEXT,
            ]
        );

		$this->end_controls_section();
	}



	protected function render() {
        $settings = $this->get_active_settings();
        $link_to_image = esc_url_raw($settings['link_to_image']);

        $link_to_post = 'href="' . esc_url_raw($settings['link_to_post']) . '"';

        if (!$settings['link_to_post']) {
            $link_to_post = '';
        }

        ?>
        <a <?php echo $link_to_post ?> target="_blank">
            <div class="zen-instagram-block" style="background: url('<?php echo $link_to_image?>'); background-size: cover; background-position: center center; "></div>
        </a>

        <?php

	}
}

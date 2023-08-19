<?php
namespace Zen\Elementor\Widgets\ZenPaginationButton;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;


class ZenPaginationButton extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
//        wp_register_script('zen-button-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenInfoBlock/assets/js/base-script.js', '', '1', true);
        wp_register_style('zen-button-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenPaginationButton/assets/css/base-main.css', '', 1);
    }
//
//    public function get_script_depends() {
//        return ['zen-info-script'];
//    }

    public function get_style_depends() {
        return ['zen-button-style'];
    }

	public function get_name() {
		return 'zen_button_block';
	}


	public function get_title() {
		return __( 'Zen Bottom Navigation Button', 'elementor' );
	}


	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'icon box', 'icon' ];
	}


	protected function _register_controls() {
        $this->start_controls_section(
            'section_icon',
            [
                'label' => __( 'Button', 'elementor' ),
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __( 'Icon right', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Right', 'elementor-pro' ),
                'label_on' => __( 'Left', 'elementor-pro' ),
                'frontend_available' => true,
                'default' => 'no',
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title typography', 'elementor-pro' ),
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen-btn',
            ]
        );

        $this->add_control(
            'button_title',
            [
                'label' => __( 'Title', 'elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Heading', 'elementor' ),
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => __( 'Width', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .zen-btn' => 'width: {{SIZE}}{{UNIT}} !important',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_height',
            [
                'label' => __( 'Height', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .zen-btn' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'elementor' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'elementor' ),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

		$this->end_controls_section();
	}

	protected function render() {
        $settings = $this->get_active_settings();

        if ( $settings['icon_position'] == 'yes' ) {
            $this->add_render_attribute( 'link', 'class', 'zen-btn_icon-right' );
        }

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'link', $settings['link'] );
            $this->add_render_attribute( 'link', 'class', 'zen-btn' );
        }
        ?>
          <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
              <svg class="zen-btn__icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" x="0px" y="0px" viewBox="0 0 10.4 19.3" style="enable-background:new 0 0 10.4 19.3;" xml:space="preserve">
                <path id="Shape" class="st0" d="M7.5,15L3.1,8.9l0,0l0,0l4.4-6.1L1.4,8.9L7.5,15z"></path>
              </svg>
              <span class="zen-btn__text">
                <?php echo esc_html($settings['button_title']); ?>
              </span>
          </a>
        <?php
	}
}

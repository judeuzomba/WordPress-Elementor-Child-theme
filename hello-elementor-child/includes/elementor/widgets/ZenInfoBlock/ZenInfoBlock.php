<?php
namespace Zen\Elementor\Widgets\Zen_Info_Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;


class ZenInfoBlock extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
        wp_register_script('zen-info-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenInfoBlock/assets/js/base-script.js', '', '1', true);
        wp_register_style('zen-info-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenInfoBlock/assets/css/base-main.css', '', 1);
    }

    public function get_script_depends() {
        return ['zen-info-script'];
    }

    public function get_style_depends() {
        return ['zen-info-style'];
    }

	public function get_name() {
		return 'zen_info_block';
	}


	public function get_title() {
		return __( 'Zen Info Block', 'elementor' );
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
				'label' => __( 'Settings', 'elementor' ),
			]
		);

        $this->add_responsive_control (
            'column_gap',
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
                    '{{WRAPPER}} .zen-info-block__item-inner' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Title typography', 'elementor-pro' ),
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen-info-block__item-title',
            ]
        );

        $this->add_control(
            'info_title',
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


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Description typography', 'elementor-pro' ),
                'name' => 'text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen-info-block__item-toggle-text',
            ]
        );

        $this->add_responsive_control(
            'info_horizontal_padding',
            array(
                'label'      => 'Horizontal description padding',
                'type'       => Controls_Manager::NUMBER,
//                'default'    => array(
//                    'unit' => 'em',
//                ),
//                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    "{{WRAPPER}} .zen-info-block__item-content-text" =>
                        'padding-left: {{VALUE}}px; padding-right: {{VALUE}}px;',
                ),
            )
        );

        $this->add_responsive_control(
            'info_bottom_padding',
            array(
                'label'      => 'Bottom padding',
                'type'       => Controls_Manager::NUMBER,
//                'default'    => '80px',
//                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    "{{WRAPPER}} .zen-info-block__item-toggle" =>
                        'margin-bottom: {{VALUE}}px;',
                ),
            )
        );

        $this->add_control(
            'info_text',
            [
                'label' => __( 'Description', 'elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'This is the text', 'elementor' ),
                'label_block' => true,
            ]
        );

//        $this->add_responsive_control(
//            'info_padding',
//            array(
//                'label'      => 'Text padding',
//                'type'       => Controls_Manager::DIMENSIONS,
//                'default'    => array(
//                    'unit' => 'em',
//                ),
//                'size_units' => array( 'px', 'em', '%' ),
//                'selectors'  => array(
//                    "{{WRAPPER}} .zen-info-block__item-toggle-text" =>
//                        'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
//                ),
//            )
//        );


//        $this->add_group_control(
//            Group_Control_Typography::get_type(),
//            [
//                'label' => __( 'Button typography', 'elementor-pro' ),
//                'name' => 'button_typography',
//                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
//                'selector' => '{{WRAPPER}} .zen-info-block__item-button',
//            ]
//        );

        $this->end_controls_section();

        $button_selectors = [
            '{{WRAPPER}} .elementor-button',
        ];

        $button_hover_selectors = [
            '{{WRAPPER}} .elementor-button:hover',
            '{{WRAPPER}} .elementor-button:focus',
        ];

        $button_selector = implode( ',', $button_selectors );
        $button_hover_selector = implode( ',', $button_hover_selectors );

        $this->start_controls_section(
            'section_buttons',
            [
                'label' => __( 'Button', 'elementor' ),
//                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );



        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography', 'elementor' ),
                'name' => 'button_typography',
                'selector' => $button_selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'button_text_shadow',
                'selector' => $button_selector,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $button_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $button_selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => $button_selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => $button_selector,
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $button_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'elementor' ),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $button_hover_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => __( 'Background Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $button_hover_selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => $button_hover_selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_hover_border',
                'selector' => $button_hover_selector,
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $button_hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    $button_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'info_button_text',
            [
                'label' => __( 'Button text', 'elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Read More', 'elementor' ),
                'label_block' => true,
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

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'link', $settings['link'] );
            $this->add_render_attribute( 'link', 'class', 'elementor-button zen-info-block__item-button' );
        }
        ?>
        <div class="zen-info-block__item">
            <div class="zen-info-block__item-inner">
                <img class="zen-info-block__item-img" src="<?php echo $settings['_background_image']['url']; ?>" alt="">

                <div class="zen-info-block__item-content">
                    <div class="zen-info-block__item-content-text">
                            <span class="zen-info-block__item-title">
                                 <?php echo esc_html($settings['info_title']); ?>
                            </span>

                        <div class="zen-info-block__item-toggle">
                            <div class="zen-info-block__item-toggle-text">
                                <?php echo esc_html($settings['info_text']); ?>
                            </div>
                        </div>



                        <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                            <?php echo esc_html($settings['info_button_text']); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
	}
}

<?php
//require_once( __DIR__ . '/controls-manager.php' );

class Zen_Max_Width extends \Elementor\Widget_Base {

	public function __construct() {
		parent::__construct();
		$this->init_control();
	}

	public function get_name() {
		return 'pafe-max-width';
	}

	public function register_controls( ) {

		$element_name = $this->get_name();

		if ($element_name != 'section' && $element_name != 'column' && $element_name != 'image') {
            $this->start_controls_section(
                'pafe_max_width_section',
                [
                    'label' => __( 'Max Width', 'pafe' ),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

			$this->add_control(
				'pafe_max_width_enable',
				[
					'label' => __( 'Enable Max Width', 'pafe' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'label_on' => 'Yes',
					'label_off' => 'No',
					'return_value' => 'yes',
				]
			);

			$this->add_responsive_control(
				'pafe_max_width',
				[
					'label' => __( 'Max Width', 'pafe' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 1600,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}}' => 'max-width: {{SIZE}}{{UNIT}} !important;',
					],
					'condition' => [
						'pafe_max_width_enable' => 'yes',
					],
				]
			);

			$this->add_responsive_control(
				'pafe_max_width_center_align',
				[
					'label' => __( 'Center Align Element', 'pafe' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'label_on' => 'Yes',
					'label_off' => 'No',
					'return_value' => 'auto',
					'selectors' => [
						'{{WRAPPER}}' => 'display: block !important; margin-left: {{pafe_max_width_center_align}} !important; margin-right: {{pafe_max_width_center_align}} !important;',
					],
					'condition' => [
						'pafe_max_width_enable' => 'yes',
					],
				]
			);

			$this->end_controls_section();
		}

	}

	protected function init_control() {
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );
        add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );
        add_action( 'elementor/element/common/_section_position/after_section_end', [ $this, 'register_controls' ], 10, 2 );
	}

}

new Zen_Max_Width();
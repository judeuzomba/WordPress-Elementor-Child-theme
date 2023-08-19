<?php

class Zen_Column_Link extends \Elementor\Widget_Base {

	public function __construct() {
		parent::__construct();
		$this->init_control();
	}

	public function get_name() {
		return 'pafe-column-link';
	}

	public function register_controls( ) {

		$this->start_controls_section(
			'pafe_column_link_section',
			[
				'label' => __( 'Column Link', 'pafe' ),
//				'tab' => PAFE_Controls_Manager::TAB_PAFE,
			]
		);
		
		$this->add_control(
			'pafe_column_link',
			[
				'label' => __( 'Link', 'pafe' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'description' => __( 'Note that it is not visible in edit/preview mode & can only be viewed on the frontend.', 'pafe' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

	}

	public function before_render_section(){
		$settings = $this->get_settings_for_display();
		$link = $settings['pafe_column_link'];
		if( !empty($link['url']) ) { 
			$this->add_render_attribute( '_wrapper', [
				'data-pafe-section-link' => $link['url'],
				'data-pafe-section-link-external' => $link['is_external'],
			] );
		}
	}

	protected function init_control() {
		add_action( 'elementor/element/column/layout/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_render_section'], 10, 1 );
	}

}

new Zen_Column_Link();
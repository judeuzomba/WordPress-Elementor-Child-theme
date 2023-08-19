<?php
namespace Zen\Elementor\Widgets\ZenCountInfo;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;


class ZenCountInfo extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
//        wp_register_script('zen-info-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenInfoBlock/assets/js/base-script.js', '', '1', true);
        wp_register_style('zen-cont-info-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenCountInfo/assets/css/base-main.css', '', 1);
    }

//    public function get_script_depends() {
//        return ['zen-info-script'];
//    }

    public function get_style_depends() {
        return ['zen-cont-info-style'];
    }

	public function get_name() {
		return 'zen-count-info';
	}


	public function get_title() {
		return __( 'Zen Count Info Block', 'elementor' );
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
				'label' => __( 'Icon Box', 'elementor' ),
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Number typography', 'elementor-pro' ),
                'name' => 'number_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen-count-list__item-number',
            ]
        );

        $this->add_control(
            'info_count_number',
            [
                'label' => __( 'Number', 'elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( '116,100', 'elementor' ),
                'label_block' => true,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text typography', 'elementor-pro' ),
                'name' => 'text_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen-count-list__item-text',
            ]
        );

        $this->add_control(
            'info_count_text',
            [
                'label' => __( 'Text', 'elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'individuals received contemplative care in the face of death, cancer, AIDS, and other illnesses', 'elementor' ),
                'label_block' => true,
            ]
        );

		$this->end_controls_section();
	}

	protected function render() {
        $settings = $this->get_active_settings();
        ?>

          <div class="zen-count-list__item">
              <div class="zen-count-list__item-inner">
                  <div class="zen-count-list__item-line zen-count-list__item-line_top"></div>
                  <span class="zen-count-list__item-number">
                      <?php echo esc_html($settings['info_count_number']); ?>

                  </span>
                  <span class="zen-count-list__item-text">
                       <?php echo esc_html($settings['info_count_text']); ?>
                    </span>
                  <div class="zen-count-list__item-line zen-count-list__item-line_bottom"></div>
              </div>
          </div>

        <?php
	}
}

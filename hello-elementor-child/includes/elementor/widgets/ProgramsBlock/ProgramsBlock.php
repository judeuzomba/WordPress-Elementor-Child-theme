<?php
namespace Zen\Elementor\Widgets\ProgramsBlock;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Frontend;


class ProgramsBlock extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
        wp_register_script('programs-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ProgramsBlock/assets/js/base-script.js', '', '1', true);
        wp_register_style('programs-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ProgramsBlock/assets/css/base-main.css', '', 1);
    }

    public function get_script_depends() {
        return ['programs-script', 'swiper'];
    }

    public function get_style_depends() {
        return ['programs-style'];
    }

	public function get_name() {
		return 'program_block';
	}


	public function get_title() {
		return __( 'Zen Programs Block', 'elementor' );
	}


	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'icon box', 'icon' ];
	}

	private function get_programs() {
        $programs = [];
        $args = array(
            'post_type' => 'zen_programs',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $posts = get_posts($args);

        foreach ($posts as $post) {
            $programs[$post->ID] = $post->post_title;
        }

        return $programs;
    }

	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon Box', 'elementor' ),
			]
		);

        $this->add_control(
            'programs_items',
            [
                'label' => __( 'Elements', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_programs(),
            ]
        );

		$this->end_controls_section();
	}


	protected function render() {
        $settings = $this->get_active_settings();
        $program_data = $settings[ 'programs_items' ];

        $programs = $this->get_programs();

        if ( !$programs || !$program_data ) {
            return;
        }

        $args = array(
            'post_type' => 'zen_programs',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $posts = get_posts( $args );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'link', $settings['link'] );
            $this->add_render_attribute( 'link', 'class', 'zen-programs__btn' );
        }
        ?>
        <div class="zen-programs">
            <div class="gallery-thumbs-wrapper">
              <div class="swiper-button-next swiper-button"></div>
              <div class="swiper-button-prev swiper-button"></div>

              <div class="swiper-container gallery-thumbs">
                  <div class="swiper-wrapper">
                      <?php foreach( $program_data as $id ) { ?>
                          <div class="swiper-slide">
                              <span class="zen-programs__dot-text">
                                  <?php echo esc_html( $programs[$id] ); ?>
                              </span>
                          </div>
                      <?php } ?>
                  </div>
              </div>
            </div>

            <div class="swiper-container gallery-main">
                <div class="swiper-wrapper">
                    <?php foreach( $program_data as $id ) { ?>
                        <div class="swiper-slide swiper-slide-<?php echo $id; ?>">
                            <?php echo Plugin::instance()->frontend->get_builder_content_for_display($id); ?>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
	}



}

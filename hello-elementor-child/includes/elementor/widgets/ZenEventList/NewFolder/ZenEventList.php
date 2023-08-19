<?php
namespace Zen\Elementor\Widgets\ZenEventList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;
use Zen\Elementor\Widgets\Zen_Posts\Posts_Base;


class ZenEventList extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
        wp_register_script('zen-event-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenEventList/assets/js/base-script.js', '', '1', true);
        wp_register_style('zen-event-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenEventList/assets/css/base-main.css', '', '1.3');
    }

    public function get_script_depends() {
        return ['zen-event-script', 'swiper'];
    }

    public function get_style_depends() {
        return ['zen-event-style'];
    }

	public function get_name() {
		return 'zen_event_list';
	}


	public function get_title() {
		return __( 'Zen Event List', 'elementor' );
	}


	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'events' ];
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Events', 'elementor' ),
			]
		);

//        $this->add_control(
//            'events_items',
//            [
//                'label' => __( 'Show first', 'plugin-domain' ),
//                'type' => \Elementor\Controls_Manager::SELECT2,
//                'multiple' => true,
//                'options' => $this->get_events(),
//            ]
//        );
//
//
//        $this->add_control(
//            'hide_events_items',
//            [
//                'label' => __( 'Hide', 'plugin-domain' ),
//                'type' => \Elementor\Controls_Manager::SELECT2,
//                'multiple' => true,
//                'options' => $this->get_events(),
//            ]
//        );
        $this->add_control(
          'show_events_items',
          [
            'label' => __( 'Show', 'plugin-domain' ),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->get_events(),
          ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography', 'elementor-pro' ),
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen-event-list__title, {{WRAPPER}} .zen-event-list__date',
            ]
        );



		$this->end_controls_section();
	}

    private function get_events_query() {
        $your_transient = 'events_for_widget';
        $data_timeout = get_option('_transient_timeout_' . $your_transient);
        if ($data_timeout < time()) {
            $events = tribe_get_events( [
                'posts_per_page' => -1,
                'start_date'     => 'now',
                'tribeHideRecurrence' => 1
            ] );

            set_transient( 'events_for_widget', $events, 0 );
        } else {
            $events = get_transient('events_for_widget');
        }

        return $events;
    }

    private function get_events() {
        $events_array = [];
        $events = $this->get_events_query();

        foreach ($events as $post) {
            $events_array[$post->ID] = $post->post_title;
        }

        return $events_array;
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $events = $this->get_events_query();
	      $show_events_ids = $settings['show_events_items'];
        if ( !$show_events_ids ) {
          $show_events_ids = [];
        }
	      $show_events_ids = array_map('intval', $show_events_ids);

//	      var_dump($show_events_ids);
//        $events_ids = array_column($events, 'ID');
////      choosen show first ids
//        $chosen_events_ids = $settings['events_items'];
//        if ( !$chosen_events_ids ) {
//            $chosen_events_ids = [];
//        }
//        $chosen_events_ids = array_map('intval', $chosen_events_ids); //cast to int
//        $result_ids = array_unique( array_merge( $chosen_events_ids, $events_ids ) );
//
////      choosen hide ids
//        $chosen_hide_ids = $settings['hide_events_items'];
//        if ( !$chosen_hide_ids ) {
//            $chosen_hide_ids = [];
//        }
//        $chosen_hide_ids = array_map('intval', $chosen_hide_ids); //cast to int
//
//        $result_ids = array_diff( $result_ids,  $chosen_hide_ids);
//        $result_ids = $show_events_ids;


        $args = array(
            'post_type' => 'tribe_events',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__in' => $show_events_ids,
            'orderby' => 'publish_date',
        );

        $posts = get_posts( $args );


    ?>

        <div class="zen-event-list">
            <div class="swiper-container zen-event-list__slider">
                <div class="swiper-wrapper">
                    <?php foreach ($posts as $post) {

//echo $post->ID;
//                        var_dump($post);
//                         if ( (get_post_meta( $post->post_parent, 'zen_event_hide_from_list_meta', true ) == 'yes') ) {
//                            continue;
//                         }
                            $start_date =  tribe_get_start_time ( $post->ID, 'l, F j, Y \a\t h:iA');
                            $end_date = tribe_get_end_time ( $post->ID, 'h:i A \E\S\T');
						                $end_date_format = tribe_get_end_date ( $post->ID, false, 'y-m-d');
						                $today = date("y-m-d");

						                if ($end_date_format < $today){
						                  //echo 'true';
						                  continue;
                            }

//                            $image =  tribe_event_featured_image($post->ID);
                            $image = get_the_post_thumbnail_url($post->ID, 'full');
                            $link =  tribe_get_event_link( $post->ID );
                            $excerpt =  wp_trim_words( get_the_excerpt($post->ID), 25, '...' );
                        ?>
                                    <div class="swiper-slide">
                                      <div class="zen-event-list__inner">
                                        <a href="<?php echo $link; ?>">
                                          <div class="zen-event-list__wrap-img" style="background-image: url(<?php echo $image; ?>);"></div>
                                        </a>

                                        <a href="<?php echo $link; ?>" class="zen-event-list__title">
                                        <?php echo $post->post_title; ?>
                                        </a>

                                        <div class="zen-event-excerpt">
                                          <?php echo $excerpt; ?>
                                        </div>

                                        <div class="zen-event-list__bottom">
                                          <div class="zen-event-list__date">
                                          <?php //echo $start_date . ' --- ' . $end_date
										                      //echo $post->ID.'||||'; echo $end_date_format; echo  '------------'; echo $today;
                                          ?>
                                          <?php echo get_post_meta( $post->ID, 'zen_event_subheading', true ); ?>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <button class='zen-event-list__nav_prev zen-event-list__nav'></button>
            <button class='zen-event-list__nav_next zen-event-list__nav'></button>
        </div>
        <?php
	}
}

<?php
namespace Zen\Elementor\Widgets\ZenBreadcrumbs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Schemes;
use Elementor\Controls_Manager;


class ZenBreadcrumbs extends Widget_Base {

    public function __construct($data = [], $args = null) {

        parent::__construct($data, $args);
        wp_register_script('zen-breadcrumbs-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenBreadcrumbs/assets/js/base-script.js', '', '1', true);
        wp_register_style('zen-breadcrumbs-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/ZenBreadcrumbs/assets/css/base-main.css', '', 1);
    }

    public function get_script_depends() {
        return ['zen-breadcrumbs-script'];
    }

    public function get_style_depends() {
        return ['zen-breadcrumbs-style'];
    }

	public function get_name() {
		return 'zen_breadcrumbs_list';
	}


	public function get_title() {
		return __( 'Zen BreadCrumbs', 'elementor' );
	}


	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'breadcrumbs', 'icon' ];
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Breadcrumbs', 'elementor' ),
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography', 'elementor-pro' ),
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .zen_breadcrumbs_block',
            ]
        );



		$this->end_controls_section();
	}

	protected function zen_breadCrumbs() {
        $pageNum = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

        $separator = ' > '; //  »

        global $post;

        // если главная страница сайта
        if( is_front_page() ){

//            if( $pageNum > 1 ) {
//                echo '<a href="' . site_url() . '">Главная</a>' . $separator . $pageNum . '-я страница';
//            } else {
//                echo 'Home';
//            }

        } else {

            echo '<a href="' . site_url() . '">Home</a>' . $separator;

            if( is_single() ) {

                if( is_singular('tribe_events') ) {
                    echo '<a href="' . tribe_get_events_link() . '">' . tribe_get_event_label_plural() . '</a>' . $separator;
                    the_title();
                } else {
                    the_category(', '); echo $separator; the_title();
                }

            } elseif( tribe_is_event() && !tribe_is_day() && !is_single() ) {
                echo 'Events';
            }

            elseif ( is_tax() ){

                single_term_title();

            } elseif ( is_page() ) {

                global $post;

                if ( $post->post_parent ) {
                    $parent_id  = $post->post_parent; // присвоим в переменную
                    $breadcrumbs = array();

                    while ( $parent_id ) {
                        $page = get_page( $parent_id );
                        $breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
                        $parent_id = $page->post_parent;
                    }

                    echo join( $separator, array_reverse( $breadcrumbs ) ) . $separator;

                }

                the_title();

            } elseif ( is_category() ) {

                single_cat_title();

            } elseif( is_tag() ) {

                single_tag_title();

            } elseif ( is_day() ) { // архивы (по дням)

                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
                echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
                echo get_the_time('d');

            } elseif ( is_month() ) { // архивы (по месяцам)

                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
                echo get_the_time('F');

            } elseif ( is_year() ) { // архивы (по годам)

                echo get_the_time('Y');

            } elseif ( is_author() ) { // архивы по авторам

                global $author;
                $userdata = get_userdata($author);
                echo 'Author: ' . $userdata->display_name;

            } elseif ( is_404() ) { // если страницы не существует

                echo '404';

            }
        }

    }

	protected function render() {
        $settings = $this->get_active_settings();
        echo '<div class="zen_breadcrumbs_block">';
            echo $this->zen_breadCrumbs();
        echo '</div>';

	}
}

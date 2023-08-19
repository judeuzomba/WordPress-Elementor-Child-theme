<?php
namespace Zen\Elementor\Widgets\Zen_Posts;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Repeater;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

include_once ( 'posts-base.php');
include_once ( 'skins/skin-base.php');
include_once ( 'skins/skin1.php');


/**
 * Class Zen_Posts
 */
class Zen_Posts extends Posts_Base {
    private $is_cat;

    public function __construct($data = [], $args = null) {
        $this->is_cat = is_category();
        parent::__construct($data, $args);

        add_action('wp_ajax_zen_posts_load', [ $this, 'zen_posts_load' ]);
        add_action('wp_ajax_nopriv_zen_posts_load', [ $this, 'zen_posts_load' ]);

        wp_register_script('zen-posts-isotope', get_stylesheet_directory_uri() . '/includes/elementor/widgets/posts/assets/js/isotope.pkgd.js', '', '1', true);
        wp_register_script('zen-posts-script', get_stylesheet_directory_uri() . '/includes/elementor/widgets/posts/assets/js/base-script.js', ['zen-posts-isotope'], '1', true);
        wp_register_style('zen-posts-style', get_stylesheet_directory_uri() . '/includes/elementor/widgets/posts/assets/css/base-main.css', '', 1);

        wp_localize_script( 'zen-posts-script', 'post_ajax',
            array(
                'nonce' => wp_create_nonce('post_ajax')
            )
        );
    }

    public function get_script_depends() {
        return ['zen-posts-script', 'zen-posts-isotope'];
    }

    public function get_style_depends() {
        return ['zen-posts-style'];
    }

    public function get_name() {
        return 'zen_posts';
    }

    public function get_title() {
        return __( 'Zen Posts', 'elementor-pro' );
    }

    public function get_keywords() {
        return [ 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
    }



    public function on_import( $element ) {
        if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
            $element['settings']['posts_post_type'] = 'post';
        }

        return $element;
    }

    private function filter_by_data($a, $b) {
        $a = str_replace("," , "" , $a);
        $b = str_replace("," , "" , $b);

        $a = strtotime($a['date']);
        $b = strtotime($b['date']);
        if ($a == $b) {
            return 0;
        }

        return ($a > $b) ? -1 : 1;
    }

    public function zen_posts_load() {
        check_ajax_referer( 'post_ajax', 'nonce_code' );
        $post_data = [];

        $paged = $_GET['page'] ?? $_POST['page'] ?? 1;

        $args = [
            'post_type' => 'post',
            'numberposts' => $_POST['posts_per_page'],
            'paged' => $paged,
            'category__not_in' => array(21, 22),
        ];


        if ( isset($_POST['related_tags']) ) {
            $args['category'] = $_POST['related_tags'];
        } else {
            if ( !isset( $_POST['cat'] ) ) {
                $cat = get_category_by_slug($_POST['archive']);
                $id = $cat->term_id;
                $args['category'] = $id;

            } else {
                $args['category'] = $_POST['cat'];
            }
        }

        $posts = get_posts( $args );

        foreach ( $posts as $post ) {
            $user = get_user_by( 'ID', $post->post_author );

            if ( $user ) {
                $user_name = $user->user_nicename;
            }

            $excerpt = wp_trim_words( get_the_excerpt($post->ID), 13, '...' );

            $post_data[$post->ID]['ID'] = $post->ID;
            $post_data[$post->ID]['guid'] = get_post_permalink($post->ID);
            $post_data[$post->ID]['post_name'] = $post->post_name;
            $post_data[$post->ID]['post_excerpt'] = $excerpt;
            $post_data[$post->ID]['post_title'] = $post->post_title;
            $post_data[$post->ID]['image_url'] = get_the_post_thumbnail_url($post, 'full');
            $post_data[$post->ID]['author_name'] = $user->display_name;
            $post_data[$post->ID]['author_url'] = get_author_posts_url($user->ID);
            $post_data[$post->ID]['date'] = get_the_date('j F, Y', $post->ID);

            $terms = get_the_terms($post->ID, 'category' );
            if (  $terms  ) {
                foreach ( $terms as $term ) {
                    $post_data[$post->ID]['terms'][$term->slug]['slug'] = $term->slug;
                    $post_data[$post->ID]['terms'][$term->slug]['name'] = $term->name;
                    $post_data[$post->ID]['terms'][$term->slug]['url'] = get_term_link(intval($term->term_id));
                }
            }
        }

        usort($post_data, array( $this, 'filter_by_data' ));

        echo json_encode($post_data);

        wp_die();
    }

    protected function _register_skins() {
        $this->add_skin( new Skin1( $this ) );

    }

    protected function _register_controls() {
        parent::_register_controls();

    }

}

<?php
namespace Zen\Elementor\Widgets\Zen_Posts;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Skin1 extends Skin_Base {

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

//		add_action( 'elementor/element/zen_posts/section_layout/before_section_end', [ $this, 'register_additional_design_image_controls' ] );
	}

	public function get_id() {
		return 'skin2';
	}

	public function get_title() {
		return __( 'Cards', 'elementor-pro' );
	}

	public function start_controls_tab( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->parent->start_controls_tab( $this->get_control_id( $id ), $args );
	}

	public function end_controls_tab() {
		$this->parent->end_controls_tab();
	}

	public function start_controls_tabs( $id ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->parent->start_controls_tabs( $this->get_control_id( $id ) );
	}

	public function end_controls_tabs() {
		$this->parent->end_controls_tabs();
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_link_controls();
        $this->register_filter_items_control();
	}

	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_card_controls();
//		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}


	public function register_design_card_controls() {
		$this->start_controls_section(
			'section_design_card',
			[
				'label' => __( 'Card', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_padding',
			[
				'label' => __( 'Horizontal Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'padding: 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__meta-data' => 'padding: 10px {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_vertical_padding',
			[
				'label' => __( 'Vertical Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_shadow_box_shadow_type', // The name of this control is like that, for future extensibility to group_control box shadow.
			[
				'label' => __( 'Box Shadow', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-card-shadow-',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'none' => __( 'None', 'elementor-pro' ),
					'gradient' => __( 'Gradient', 'elementor-pro' ),
					//'zoom-in' => __( 'Zoom In', 'elementor-pro' ),
					//'zoom-out' => __( 'Zoom Out', 'elementor-pro' ),
				],
				'default' => 'gradient',
				'separator' => 'before',
				'prefix_class' => 'elementor-posts__hover-',
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_content_controls() {
		parent::register_design_content_controls();

		$this->remove_control( 'meta_spacing' );

		$this->update_control(
			'read_more_spacing',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-post__read-more' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			],
			[
				'recursive' => true,
			]
		);
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function render_post_header() {
//          $taxonomy = $this->get_instance_value( 'badge_taxonomy' );
          $terms = get_the_terms( get_the_ID(), 'category' );
          $list_width_value = '1';
          $attr = '';
          if (  $terms  ) {
              foreach ( $terms as $term ) {
                  $attr .= $term->slug . ' ';
              }
          }

//          $list_width = get_field('list_width', get_the_ID() );
            $list_width = false;

          if ( $list_width ) {
              $list_width_value = $list_width;
          }


		?>
		<article <?php post_class( [ 'elementor-post elementor-grid-item zen-post ' . $attr . ' zen-post_' . $list_width_value ] ); ?> >
			<div class="elementor-post__card">
		<?php
	}

	protected function render_post_footer() {
		?>
			</div>
		</article>
		<?php
	}

	protected function render_categories() {
//		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
//		if ( empty( $taxonomy ) ) {
//			return;
//		}

		$terms = get_the_terms( get_the_ID(), 'category' );
		if (  !$terms  ) {
			return;
		}
		echo "<div class='zen-badges'>";
		foreach ( $terms as $term ) {
          echo '<div class="zen-wrap-badge">
            <a href="' . esc_url(get_category_link( $term->term_id )) . '" class="zen-badge">
              ' . esc_html($term->name) . '
            </a>
          </div>';
      }
      echo "</div>";

	}

  protected function render_thumbnail_placeholder() {
      ?>
    <div class="elementor-post__thumbnail__link elementor-post__thumbnail__link_placeholder">
    </div>
      <?php
  }

	protected function render_thumbnail() {
		if ( 'none' === $this->get_instance_value( 'thumbnail' ) ) {
        $this->render_thumbnail_placeholder();
        return;
    }

		$settings = $this->parent->get_settings();
		$setting_key = 'medium';
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
        $this->render_thumbnail_placeholder();
        return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<a class="elementor-post__thumbnail__link" href="<?php echo get_permalink(); ?>" <?php echo $optional_attributes_html; ?>>
			<div class="elementor-post__thumbnail"><?php echo $thumbnail_html; ?></div>
		</a>
		<?php

	}

	protected function render_post() {
//		$this->render_post_header();
//      $this->render_thumbnail();
//      $this->render_text_header();
//        $this->render_date();
//        $this->render_title();
//        $this->render_author();
//        $this->render_categories();
//      $this->render_text_footer();
//		$this->render_post_footer();
	}
}

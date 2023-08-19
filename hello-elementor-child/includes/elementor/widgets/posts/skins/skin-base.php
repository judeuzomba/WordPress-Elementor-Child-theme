<?php
namespace Zen\Elementor\Widgets\Zen_Posts;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Skin_Base extends Elementor_Skin_Base {

	/**
	 * @var string Save current permalink to avoid conflict with plugins the filters the permalink during the post render.
	 */
	protected $current_permalink;

	protected function _register_controls_actions() {
		add_action( 'elementor/element/zen_posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/zen_posts/section_layout/after_section_end', [ $this, 'register_style_sections' ] );
	}

	public function register_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_design_controls();
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
//		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_link_controls();
    }

	public function register_design_controls() {
		$this->register_design_layout_controls();
//		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}

    protected function register_filter_items_control() {
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_title', [
                'label' => __( 'Category', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_terms_list(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'zen_term_list',
            [
                'label' => __( 'Repeater List', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => 'news',

                    ],
                    [
                        'list_title' => 'podcasts',

                    ],
                    [
                        'list_title' => 'press',

                    ],
                    [
                        'list_title' => 'community',

                    ],
                    [
                        'list_title' => 'recomends-reading',

                    ],
                    [
                        'list_title' => 'continuing-education',

                    ],
                ],
                'title_field' => '{{{ list_title }}}',
                'condition' => [
                    $this->get_control_id( 'post_filter' ) => 'yes',
                ]
            ]
        );
    }

    private function get_terms_list() {
        $terms = get_terms('category', []);
        $term_array = [];

        if ($terms) {
            foreach ($terms as $term) {
                $term_array[$term->slug] = $term->name;
            }
        }

        return $term_array;
    }

    private function get_terms_list_id() {
        $terms = get_terms('category', []);
        $term_array = [];

        if ($terms) {
            foreach ($terms as $term) {
                $term_array[$term->term_id] = $term->name;
            }
        }

        return $term_array;
    }




	protected function register_columns_controls() {
		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
                'selectors' => [
                    '{{WRAPPER}} .zen-post' => 'width: calc( 100% / {{VALUE}});',
                ],
				'prefix_class' => 'elementor-grid%s-',
				'frontend_available' => true,
			]
		);
	}

	protected function register_post_count_control() {
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
                'frontend_available' => true
			]
		);

        $this->add_control(
            'post_filter',
            [
                'label' => __( 'Filter', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Off', 'elementor-pro' ),
                'label_on' => __( 'On', 'elementor-pro' ),
                'frontend_available' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'zen_posts_related',
            [
                'label' => __( 'Related', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'True', 'elementor-pro' ),
                'label_off' => __( 'False', 'elementor-pro' ),
                'default' => 'yes',
                'separator' => 'before',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'related_tags', [
                'label' => __( 'Related Tags', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_terms_list_id(),
                'multiple' => true,
                'label_block' => true,
                'frontend_available' => true,
                'condition' => [
                    $this->get_control_id( 'zen_posts_related' ) => 'yes',
                ],
            ]
        );


	}

	protected function register_title_controls() {


	}

	protected function register_excerpt_controls() {
//		$this->add_control(
//			'show_excerpt',
//			[
//				'label' => __( 'Excerpt', 'elementor-pro' ),
//				'type' => Controls_Manager::SWITCHER,
//				'label_on' => __( 'Show', 'elementor-pro' ),
//				'label_off' => __( 'Hide', 'elementor-pro' ),
//				'default' => 'yes',
//			]
//		);


	}



	protected function register_link_controls() {

	}

	protected function get_optional_link_attributes_html() {
		$settings = $this->parent->get_settings();
		$new_tab_setting_key = $this->get_control_id( 'open_new_tab' );
		$optional_attributes_html = 'yes' === $settings[ $new_tab_setting_key ] ? 'target="_blank"' : '';

		return $optional_attributes_html;
	}


	/**
	 * Style Tab
	 */
	protected function register_design_layout_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => __( 'Layout', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_',
			[
				'label' => __( 'Columns Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
//				'selectors' => [
//					'{{WRAPPER}} .elementor-posts-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
//					'.elementor-msie {{WRAPPER}} .elementor-post' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
//					'.elementor-msie {{WRAPPER}} .elementor-posts-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
//				],

                'selectors' => [
                    '{{WRAPPER}} .zen-post' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
                ],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-posts-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-post' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-posts--align-',
			]
		);

		$this->end_controls_section();
	}



	protected function register_design_content_controls() {
		$this->start_controls_section(
			'section_design_content',
			[
				'label' => __( 'Content', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a',
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);


//		$this->add_control(
//			'heading_excerpt_style',
//			[
//				'label' => __( 'Excerpt', 'elementor-pro' ),
//				'type' => Controls_Manager::HEADING,
//				'separator' => 'before',
//				'condition' => [
//					$this->get_control_id( 'show_excerpt' ) => 'yes',
//				],
//			]
//		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__excerpt p' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Date Typography', 'elementor-pro' ),
                'name' => 'date_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .zen-date',
//                'condition' => [
//                    $this->get_control_id( 'show_date' ) => 'yes',
//                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Author Typography', 'elementor-pro' ),
                'name' => 'author_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .zen-author',
//                'condition' => [
//                    $this->get_control_id( 'show_date' ) => 'yes',
//                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Filter Items', 'elementor-pro' ),
                'name' => 'filter_items__typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .zen-posts-filter__list li a',
//                'condition' => [
//                    $this->get_control_id( 'show_date' ) => 'yes',
//                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .elementor-post__excerpt p',
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_spacing',
			[
				'label' => __( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

    public function render_posts_wrapper_start() {
      echo "<div class='zen-posts'>";
  }

    public function render_posts_wrapper_end() {
      echo "</div>";
  }

	public function render_posts_filter() {
        if (  !$this->get_instance_value( 'post_filter' ) ) {
            return;
        }

        $archive_slug = '';
        if ( is_category() ) {
            $term = get_queried_object();
            $archive_slug = $term->slug;
        }

        $visible_terms_array = [];
        $visible_terms_array_id = [];
        $visible_terms = $this->get_instance_value( 'zen_term_list' ); //repeater from admin

        foreach ( $visible_terms as $term ) {
            $term = get_term_by('slug', $term['list_title'], 'category');
            $visible_terms_array[] = $term;
            $visible_terms_array_id[] = $term->term_id;
        }

        $terms = get_terms( 'category', ['exclude' => $visible_terms_array_id] );
        $non_visible_array = $terms;

        if (  $terms  ) { ?>
          <div class="zen-posts-filter">
            <button class="zen-posts-filter__btn zen-posts-filter__btn_search">
              <span class="search-icon"></span>
            </button>

            <ul class="zen-posts-filter__list">
              <li class="zen-posts-filter__item">
                <a class=" zen-posts-filter__item-link zen-posts-filter__item-link_all <?php if (!is_category()) echo ' zen-posts-filter__item-link_active';  ?>" data-filter="0"  href="#">
                    All
                </a>
              </li>
              <?php
                foreach ( $visible_terms_array as $term ) {
//                        $term = get_term_by('slug', $term['list_title'], 'category');
                    ?>
                  <li class="zen-posts-filter__item">
                    <a class="zen-posts-filter__item-link <?php if ($archive_slug == $term->slug) echo ' zen-posts-filter__item-link_active';  ?>" data-filter=".category-<?php echo $term->slug; ?>" data-cat="<?php echo $term->term_id; ?>"  href="#">
                       <?php echo $term->name; ?>
                    </a>
                  </li>
                <?php } ?>
            </ul>

						<ul class="zen-posts-filter__list zen-posts-filter__list_hidden">
                <?php
                $i = 1;
                foreach ( $non_visible_array as $term ) {
                    ?>
                      <?php if ($term->slug != 'events' && $term->slug != 'event-archives' ){ ?>
                  <li class="zen-posts-filter__item">
                    <a class="zen-posts-filter__item-link <?php if ($archive_slug == $term->slug) echo ' zen-posts-filter__item-link_active';  ?>" data-filter=".category-<?php echo $term->slug; ?>" data-cat="<?php echo $term->term_id; ?>"  href="#">
                        <?php echo $term->name; ?>
                    </a>
                  </li>
                    <?php } ?>

                <?php
                    if ( $i % 7 == 0 ) {
                      echo '<li class="filter_new_line"></li>';
                    }
                    $i++;
                } ?>
            </ul>

            <button class="zen-posts-filter__btn zen-posts-filter__btn_filter">
              <span class="zen-posts-filter__btn-text">
                All Tags
              </span>
              <svg xmlns="http://www.w3.org/2000/svg" width="20.605" height="10.303" viewBox="0 0 20.605 10.303">
                <path id="zen-care-arrow-2" d="M.549,0,10.3,8.847,20.09,0,20.6.309,10.3,10.3,0,.309Z" fill="#b99f7a"/>
              </svg>
            </button>
          </div>
        <?php
        }
  }



	public function render() {

    $this->render_posts_wrapper_start();

    $this->render_posts_filter();

      $this->render_loop_header();


//		if ( $query->in_the_loop ) {
//			$this->current_permalink = get_permalink();
//			$this->render_post();
//		} else {
//			while ( $query->have_posts() ) {
//				$query->the_post();
//
//				$this->current_permalink = get_permalink();
//				$this->render_post();
//			}
//		}

//		wp_reset_postdata();

      $this->render_posts_wrapper_end();

      $this->render_loop_footer();
	}


	public function filter_excerpt_more( $more ) {
		return '';
	}

	public function get_container_class() {
		return 'elementor-posts--skin-' . $this->get_id();
	}

	protected function render_thumbnail_placeholder() {
	  ?>
      <div class="elementor-post__thumbnail__link elementor-post__thumbnail__link_placeholder">
      </div>
    <?php
  }

	protected function render_thumbnail() {
		$thumbnail = $this->get_instance_value( 'thumbnail' );

		if ( 'none' === $thumbnail && ! Plugin::elementor()->editor->is_edit_mode() ) {
			$this->render_thumbnail_placeholder();
		}

		$settings = $this->parent->get_settings();
		$setting_key = 'medium';
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
        $this->render_thumbnail_placeholder();
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<a class="elementor-post__thumbnail__link" href="<?php echo $this->current_permalink; ?>" <?php echo $optional_attributes_html; ?>>
			<div class="elementor-post__thumbnail"><?php echo $thumbnail_html; ?></div>
		</a>
		<?php
	}

	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		$tag = $this->get_instance_value( 'title_tag' );
		?>
		<<?php echo $tag; ?> class="elementor-post__title">
			<a href="<?php echo $this->current_permalink; ?>" <?php echo $optional_attributes_html; ?>>
				<?php the_title(); ?>
			</a>
		</<?php echo $tag; ?>>
		<?php
	}



	protected function render_post_header() {
        $taxonomy = $this->get_instance_value( 'badge_taxonomy' );
        $terms = get_the_terms( get_the_ID(), 'category' );
        $list_width_value = '1';

        if (  $terms  ) {
            $attr = '';
            foreach ( $terms as $term ) {
                $attr .= $term->slug . ' ';
            }
        }

//        $list_width = get_field('list_width', get_the_ID() );
        $list_width = false;

        if ( $list_width ) {
            $list_width_value = $list_width;
        }

		?>
		  <article <?php post_class( [ 'elementor-post elementor-grid-item zen-post ' . $attr . ' zen-post_' . $list_width_value ] ); ?>>
		<?php
	}

	protected function render_post_footer() {
		?>
		</article>
		<?php
	}

	protected function render_text_header() {
		?>
		<div class="elementor-post__text zen-post__wrap-text">
		<?php
	}

	protected function render_text_footer() {
		?>
		</div>
		<?php
	}

	protected function render_loop_header() {
		$classes = [
			'elementor-posts-container',
			'elementor-posts',
            'zen-posts-container',
			$this->get_container_class(),
		];


		$classes[] = 'elementor-grid';

		$this->parent->add_render_attribute( 'container', [
			'class' => $classes,
		] );

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'container' ); ?>>
		<?php
	}

	protected function render_loop_footer() {
		?>
		</div>

		<?php

	}

	protected function render_author() {
		?>
		<div class="zen-author">
			by <?php the_author(); ?>
		</div>
		<?php
	}

	protected function render_date() {
		?>
		<div class="zen-date">
			<?php
			/** This filter is documented in wp-includes/general-template.php */
			echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' );
			?>
		</div>
		<?php
	}

	protected function render_time() {
		?>
		<span class="elementor-post-time">
			<?php the_time(); ?>
		</span>
		<?php
	}

	protected function render_comments() {
		?>
		<span class="elementor-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
	}

	protected function render_post() {
//		$this->render_post_header();
//		$this->render_thumbnail();
//		$this->render_text_header();
//		$this->render_title();
//		$this->render_text_footer();
//		$this->render_post_footer();
	}

	public function render_amp() {

	}
}

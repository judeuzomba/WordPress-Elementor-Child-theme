<?php
//require_once( __DIR__ . '/controls-manager.php' );

class Zen_Animation extends \Elementor\Widget_Base {

    public function __construct() {
        parent::__construct();

//        wp_register_script('zen-animation-script', 'https://unpkg.com/aos@2.3.4/dist/aos.js', '', '1', true);
//        wp_register_style('zen-animation-style', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css', '', 1);

        $this->init_control();
    }

//    public function get_script_depends() {
//        return ['zen-animation-script'];
//    }
//
//    public function get_style_depends() {
//        return ['zen-animation-style'];
//    }


    public function get_name() {
        return 'zen-animation';
    }

    public function register_controls( $element, $section_id ) {

        $element_name = $element->get_name();

        if ($element_name != 'section' && $element_name != 'column' && $element_name != 'image') {
            $element->start_controls_section(
                'zen_animation_section',
                [
                    'label' => __( 'Animations', 'pafe' ),
                    'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                ]
            );

//            $element->add_control(
//                'zen_animation_enable',
//                [
//                    'label' => __( 'Enable Animations', 'pafe' ),
//                    'type' => \Elementor\Controls_Manager::SWITCHER,
//                    'default' => '',
//                    'label_on' => 'Yes',
//                    'label_off' => 'No',
//                    'return_value' => 'yes',
//                ]
//            );

            $element->add_control(
                'zen_animation',
                [
                    'label' => __( 'Animation', 'elementor' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => $this->get_animations_list(),
                    'prefix_class' => 'aos-item zen-aos-animated ',
                    'label_block' => true,
                    'frontend_available' => true,
                ]
            );

            $element->add_control (
                'zen_animation_duration',
                [
                    'label' => __( 'Duration', 'elementor-pro' ),
                    'type' =>  \Elementor\Controls_Manager::SLIDER,
                    'frontend_available' => true,
                    'default' => [
                        'size' => 400,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1500,
                            'step' => 50,
                        ],
                    ],
                ]
            );

            $element->add_control (
                'zen_animation_delay',
                [
                    'label' => __( 'Delay', 'elementor-pro' ),
                    'type' =>  \Elementor\Controls_Manager::SLIDER,
                    'frontend_available' => true,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 50,
                        ],
                    ],
                ]
            );


            $element->end_controls_section();
        }

    }

    private function get_animations_list() {
        $animations_array = [
                        'default' => 'Default',
                        'fade' => 'fade',
                        'fade-up' => 'fade-up',
                        'fade-down' => 'fade-down',
                        'fade-left' => 'fade-left',
                        'fade-right' => 'fade-right',
                        'fade-up-right' => 'fade-up-right',
                        'fade-up-left' => 'fade-up-left',
                        'fade-down-right' => 'fade-down-right',
                        'fade-down-left' => 'fade-down-right',
                        'zoom-in' => 'zoom-in',
                        ];


        return $animations_array;
    }

    protected function init_control() {
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );
        add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );
        add_action( 'elementor/element/common/_section_position/after_section_end', [ $this, 'register_controls' ], 10, 2 );
    }

}

new Zen_Animation();
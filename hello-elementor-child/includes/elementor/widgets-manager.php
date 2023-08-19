<?php
namespace Zen\Elementor;

use ElementorPro\Base\Module_Base;
use PropertyBuilder\Elementor\Documents\PropertyArchive;
use PropertyBuilder\Elementor\Conditions\Property;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Widget_Manager
 *
 * @since 1.0.0
 */
class Widget_Manager {

    private $modules = [];

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var Widget_Manager single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     *
     * @return Widget_Manager an instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_widgets_files() {
        // TODO: move widget folder to variable
        include_once( 'widgets/posts/posts.php' );
        include_once( 'widgets/ZenInfoBlock/ZenInfoBlock.php' );
        include_once( 'widgets/ProgramsBlock/ProgramsBlock.php' );
        include_once( 'widgets/ZenPaginationButton/ZenPaginationButton.php' );
        include_once( 'widgets/ZenEventList/ZenEventList.php' );
        include_once( 'widgets/ZenCountInfo/ZenCountInfo.php' );
        include_once( 'widgets/ZenBreadcrumbs/ZenBreadcrumbs.php' );
        include_once( 'widgets/ZenImageLink/ZenImageLink.php' );

        include_once( 'pafe-responsive-custom-positioning.php' );
        include_once( 'pafe-max-width.php' );
        include_once( 'pafe-column-link.php' );
//        include_once( 'zen-animation.php' );


    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets() {
        $this->include_widgets_files();

        // Register Widgets

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Zen_Posts\Zen_Posts() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Zen_Info_Block\ZenInfoBlock() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ProgramsBlock\ProgramsBlock() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ZenPaginationButton\ZenPaginationButton() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ZenEventList\ZenEventList() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ZenCountInfo\ZenCountInfo() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ZenBreadcrumbs\ZenBreadcrumbs() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ZenImageLink\ZenImageLink() );
    }

    /**
     * Add Widget category
     *
     * Adds new widget category to group theme-specific widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function add_widget_categories( $elements_manager ) {

        $elements_manager->add_category(
            'theme',
            [
                'title' => __( 'Theme', 'wpcasa-berlin' ),
                'icon' => 'fa fa-plug',
            ]
        );

    }

    /**
     *  Widget_Manager class constructor
     *
     * Register action hooks and filters
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct() {
//        include_once( 'documents/PropertyArchive.php' );
//        include_once( 'conditions/Property.php' );
//        require_once 'class-typography.php';
//        add_action(
//            'elementor/documents/register',
//            function( $manager ) {
//
//                $manager->register_document_type( 'property-archive', PropertyArchive::get_class_full_name() );
//
//            }
//        );
//
//        add_action(
//            'elementor/theme/register_conditions',
//            function( $manager ) {
//
//                $listings = new Property();
//
//                $manager->get_condition( 'general' )->register_sub_condition( $listings );
//            }
//        );

        add_action( 'init', [ $this, 'register_widgets' ] );
//        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

//        add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories'] );
    }
}

Widget_Manager::instance();




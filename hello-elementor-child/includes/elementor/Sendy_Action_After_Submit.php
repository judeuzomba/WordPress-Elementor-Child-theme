<?php
/**
 * Class Sendy_Action_After_Submit
 * @see https://developers.elementor.com/custom-form-action/
 * Custom elementor form action after submit to add a subsciber to
 * Sendy list via API
 */
class Sendy_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {
    /**
     * Get Name
     *
     * Return the action name
     *
     * @access public
     * @return string
     */
    public function get_name() {
        return 'sendy';
    }

    /**
     * Get Label
     *
     * Returns the action label
     *
     * @access public
     * @return string
     */
    public function get_label() {
        return __( 'Sendy', 'text-domain' );
    }

    /**
     * Run
     *
     * Runs the action after submit
     *
     * @access public
     * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
     * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
     */
    public function run( $record, $ajax_handler ) {
        $settings = $record->get( 'form_settings' );

        //  Make sure that there is a Sendy installation url
//        if ( empty( $settings['sendy_url'] ) ) {
//            return;
//        }

        //  Make sure that there is a Sendy list ID
//        if ( empty( $settings['sendy_list'] ) ) {
//            return;
//        }

        // Make sure that there is a Sendy Email field ID
        // which is required by Sendy's API to subsribe a user
//        if ( empty( $settings['sendy_email_field'] ) ) {
//            return;
//        }

        // Get sumitetd Form data
        $raw_fields = $record->get( 'fields' );

        // Normalize the Form Data
        $fields = [];
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = $field['value'];
        }


//        ob_start();
//        var_dump($fields);
//        $result = ob_get_clean();
//
//        $post_data = array(
//            'post_title'    => 'test',
//            'post_content'  => $result,
//            'post_status'   => 'publish',
//        );
//
//        $post_id = wp_insert_post( $post_data );


        return;

    }

    public function on_export( $element ) {}
    public function register_settings_section( $widget ) {}

}

add_action( 'elementor_pro/forms/validation/tel', function( $field, $record, $ajax_handler ) {

    // Match this format XXX-XXX-XXXX, 123-456-7890

    if ( preg_match( '/[0-9]{3}-[0-9]{3}-[0-9]{4}/', $field['value'] ) !== 1 ) {
        $ajax_handler->add_error( $field['id'], 'Please make sure the phone number is in XXX-XXX-XXXX format, eg: 123-456-7890' );
    }
}, 10, 3 );


add_action( 'elementor_pro/forms/validation', function ( $record, $ajax_handler ) {
    $fields = $record->get_field( [
        'id' => 'name',
    ] );

    if ( empty( $fields ) ) {
        return;
    }
    $field = current( $fields );
//    $ajax_handler->add_error( $field['id'], 'Invalid Ticket ID, it must be in the format XXX-XXX11X' );


//    if ( 1 !== preg_match( '/^\w{3}-\w{4}$/', $field['value'] ) ) {
//        $ajax_handler->add_error( $field['id'], 'Invalid Ticket ID, it must be in the format XXX-XXX11X' );
//    }
}, 10, 2 );








$sendy_action = new Sendy_Action_After_Submit();

// Register the action with form widget
\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $sendy_action->get_name(), $sendy_action );
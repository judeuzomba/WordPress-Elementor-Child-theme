<?php

function prevent_deleting_pTags($init){
	$init['wpautop'] = false;

	return $init;
}

add_filter('tiny_mce_before_init', 'prevent_deleting_pTags');

if ( ! function_exists( 'hello_child_elementor_scripts_styles' ) ) {

	function hello_child_elementor_scripts_styles() {
				wp_enqueue_style( 'style-child', get_stylesheet_uri() );
				if (is_page(array(1882, 1568))) {
					wp_enqueue_style( 'donate-style', get_stylesheet_directory_uri() . '/assets/css/donate-style.css', [], '1.1');
				}
				if (is_page(array(1568))) {
					wp_enqueue_style( 'foundation-style', get_stylesheet_directory_uri() . '/assets/css/foundation-style.css', [], '1.1');
				}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_child_elementor_scripts_styles', 100 );

add_action( 'admin_enqueue_scripts', 'hello_child_elementor_admin_scripts_styles' );
function hello_child_elementor_admin_scripts_styles( $hook_suffix ){
    wp_enqueue_style( 'admin-style', get_stylesheet_directory_uri() . '/assets/css/admin.css', [], '1.1');
}
require get_stylesheet_directory() . '/includes/elementor/widgets-manager.php';


add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type( 'zen_programs', [
		'label'  => null,
		'labels' => [
			'name'               => __( 'Programs', 'builder' ),
			'singular_name'      => __( 'Program', 'builder' ),
			'add_new'            => __( 'Add program', 'builder' ),
			'add_new_item'       => __( 'New program', 'builder' ),
			'edit_item'          => __( 'Edit program', 'builder' ),
			'new_item'           => __( 'New program', 'builder' ),
			'view_item'          => __( 'View program', 'builder' ),
			'search_items'       => __( 'Search program', 'builder' ),
			'not_found'          => __( 'Program not found', 'builder' ),
			'not_found_in_trash' => __( 'Program not found', 'builder' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Programs', 'builder' ),
		],
		'description'         => '',
		'public'              => true,
		'show_in_menu'        => null,
		'show_in_rest'        => null,
		'rest_base'           => null,
		'menu_position'       => null,
		'menu_icon'           => null,
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'taxonomies'          => [],
		'has_archive'         => false,
		'query_var'           => true,
	] );


	register_post_type( 'zenus', [
		'label'  => null,
		'labels' => [
			'name'               => __( 'ZenUs', 'builder' ),
			'singular_name'      => __( 'ZenUs', 'builder' ),
			'add_new'            => __( 'Add ZenUs', 'builder' ),
			'add_new_item'       => __( 'New ZenUs', 'builder' ),
			'edit_item'          => __( 'Edit ZenUs', 'builder' ),
			'new_item'           => __( 'New ZenUs', 'builder' ),
			'view_item'          => __( 'View ZenUs', 'builder' ),
			'search_items'       => __( 'Search ZenUs', 'builder' ),
			'not_found'          => __( 'ZenUs not found', 'builder' ),
			'not_found_in_trash' => __( 'ZenUs not found', 'builder' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'ZenUs', 'builder' ),
		],
		'description'         => '',
		'public'              => true,
		'show_in_menu'        => null,
		'show_in_rest'        => null,
		'rest_base'           => null,
		'menu_position'       => null,
		'menu_icon'           => null,
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'taxonomies'          => [],
		'has_archive'         => false,
		'query_var'           => true,
	] );

	register_post_type( 'zentalks', [
		'label'  => null,
		'labels' => [
			'name'               => __( 'ZenTalks', 'builder' ),
			'singular_name'      => __( 'ZenTalks', 'builder' ),
			'add_new'            => __( 'Add ZenTalks', 'builder' ),
			'add_new_item'       => __( 'New ZenTalks', 'builder' ),
			'edit_item'          => __( 'Edit ZenTalks', 'builder' ),
			'new_item'           => __( 'New ZenTalks', 'builder' ),
			'view_item'          => __( 'View ZenTalks', 'builder' ),
			'search_items'       => __( 'Search ZenTalks', 'builder' ),
			'not_found'          => __( 'ZenTalks not found', 'builder' ),
			'not_found_in_trash' => __( 'ZenTalks not found', 'builder' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'ZenTalks', 'builder' ),
		],
		'description'         => '',
		'public'              => true,
		'show_in_menu'        => null,
		'show_in_rest'        => null,
		'rest_base'           => null,
		'menu_position'       => null,
		'menu_icon'           => null,
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'taxonomies'          => [],
		'has_archive'         => false,
		'query_var'           => true,
	] );
}



add_action( 'init', 'create_taxonomies' );

function create_taxonomies() {
	register_taxonomy(
		'programs_tag',
		'zen_programs',
		array(
			'label' => __( 'Tags' ),
			'rewrite' => array( 'slug' => 'programs' ),
			'hierarchical' => true,
		)
	);
}

add_filter('manage_zen_programs_posts_columns', 'bs_programs_table_head');
function bs_programs_table_head( $defaults ) {
	$defaults['programs_tag']  = 'Tags';
	return $defaults;
}

add_action( 'manage_zen_programs_posts_custom_column', 'zen_programs_table_content', 10, 2 );
function zen_programs_table_content( $column_name, $post_id ) {
	switch ( $column_name ) {
		case 'programs_tag':
			$event_cats = wp_get_post_terms( $post_id, 'programs_tag', array(
				'fields' => 'names',
			) );
			$categories_list = '-';
			if ( is_array( $event_cats ) ) {
				$categories_list = implode( ', ', $event_cats );
			}
			echo esc_html( $categories_list );
	}
}


add_action( 'admin_menu', 'addEventBox' );

function addEventBox() {
	add_meta_box( 'tribe_events_event_options', 'My Meta Box', 'my_meta_box_callback', null, 'normal', 'high',
		array(
			'__block_editor_compatible_meta_box' => false,
		)
	);
}



add_action('add_meta_boxes', 'zen_events_add_custom_box');
function zen_events_add_custom_box() {
	add_meta_box(
		'tribe_events_event_options_new',
		sprintf( esc_html__( 'Hide From List', 'the-events-calendar' ), 'events' ),
		'zen_event_meta_box_callback',
		'tribe_events',
		'side',
		'default'
	);

	add_meta_box(
		'tribe_events_event_subheading',
		sprintf( esc_html__( 'Subheading', 'the-events-calendar' ), 'events' ),
		'zen_event_meta_box_subheading_callback',
		'tribe_events',
		'normal',
		'default'
	);

	add_meta_box(
		'tribe_events_event_subheading_single',
		sprintf( esc_html__( 'Subheading single', 'the-events-calendar' ), 'events' ),
		'zen_event_meta_box_subheading_single_callback',
		'tribe_events',
		'normal',
		'default'
	);

}

function zen_event_meta_box_subheading_callback( $post, $meta ) {
	?>
	<p>
		<label class="zen_event_subheading">
			<input name="zen_event_subheading" style="width: 70%; height: 40px;" value="<?php echo get_post_meta( $post->ID, 'zen_event_subheading', true ); ?>">
		</label>
	</p>

	<?php
}

function zen_event_meta_box_subheading_single_callback( $post, $meta ) {
	?>
	<p>
		<label class="zen_event_subheading">
			<input name="zen_event_subheading_single" style="width: 70%; height: 40px;" value="<?php echo get_post_meta( $post->ID, 'tribe_events_event_subheading_single', true ); ?>">
		</label>
	</p>

	<?php
}

function zen_event_meta_box_callback( $post, $meta ){
	?>

	<p>
		<label class="selectit">
			<input value="yes" type="checkbox" <?php checked( get_post_meta( $post->ID, 'zen_event_hide_from_list_meta', true ) == 'yes' ) ?> name="ZenEventHideFromList">
			<?php printf( esc_html__( 'Hide from Website List', 'the-events-calendar' ) ); ?>
		</label>
		<span class="dashicons dashicons-editor-help tribe-sticky-tooltip" title="<?php esc_attr_e( "When events are sticky in month view, they'll display first in the list of events shown within a given day block.", 'the-events-calendar' ); ?>"></span>
	</p>

	<p>
		<label class="selectit">
			<input value="yes" type="checkbox" <?php checked( get_post_meta( $post->ID, 'zen_event_hide_from_month_meta', true ) == 'yes' ) ?> name="ZenEventHideFromMonth">
			<?php printf( esc_html__( 'Hide from Month List', 'the-events-calendar' ) ); ?>
		</label>
		<span class="dashicons dashicons-editor-help tribe-sticky-tooltip" title="<?php esc_attr_e( "When events are sticky in month view, they'll display first in the list of events shown within a given day block.", 'the-events-calendar' ); ?>"></span>
	</p>
	<?php
	if ( tribe_is_recurring_event($post->ID) ) { ?>
		<p>
			<label class="selectit">
				<input value="yes" type="checkbox" <?php checked( get_post_meta( $post->ID, 'zen_event_show_first_of_recurring_meta', true ) == 'yes' ) ?> name="ZenEventShowFirstOfRecurringMeta">
				<?php printf( esc_html__( 'Show only the first instance of this recurring event', 'the-events-calendar' ) ); ?>
			</label>
			<span class="dashicons dashicons-editor-help tribe-sticky-tooltip" title="<?php esc_attr_e( "When events are sticky in month view, they'll display first in the list of events shown within a given day block.", 'the-events-calendar' ); ?>"></span>
		</p>
	<?php } ?>

	<?php
}


add_action( 'save_post', 'zen_event_data_save' );
function zen_event_data_save( $post_id ) {

	if ( ! isset( $_POST['ZenEventHideFromList'] ) ) {
		update_post_meta( $post_id, 'zen_event_hide_from_list_meta', '' );
	} else {
		update_post_meta( $post_id, 'zen_event_hide_from_list_meta', $_POST['ZenEventHideFromList'] );
	}

	if ( ! isset( $_POST['ZenEventHideFromMonth'] ) ) {
		update_post_meta( $post_id, 'zen_event_hide_from_month_meta', '' );
	} else {
		update_post_meta( $post_id, 'zen_event_hide_from_month_meta', $_POST['ZenEventHideFromMonth'] );
	}

	if ( ! isset( $_POST['ZenEventShowFirstOfRecurringMeta'] ) ) {
		update_post_meta( $post_id, 'zen_event_show_first_of_recurring_meta', '' );
	} else {
		update_post_meta( $post_id, 'zen_event_show_first_of_recurring_meta', $_POST['ZenEventShowFirstOfRecurringMeta'] );
	}

	if ( ! isset( $_POST['zen_event_subheading'] ) ) {
		update_post_meta( $post_id, 'zen_event_subheading', '' );
	} else {
		update_post_meta( $post_id, 'zen_event_subheading', $_POST['zen_event_subheading'] );
	}

	if ( ! isset( $_POST['zen_event_subheading_single'] ) ) {
		update_post_meta( $post_id, 'tribe_events_event_subheading_single', '' );
	} else {
		update_post_meta( $post_id, 'tribe_events_event_subheading_single', $_POST['zen_event_subheading_single'] );
	}
}


function entex_fn_remove_post_type_from_search_results($query){

	/* check is front end main loop content */
	if(is_admin() || !$query->is_main_query()) return;
	/* check is search result query */
	if($query->is_search()) {
		$post_types_to_remove = ['zen_programs', 'htslider_slider', 'attachment', 'ctct_forms'];
		$searchable_post_types = get_post_types(array('exclude_from_search' => false));

		foreach ($post_types_to_remove as $post_type_to_remove ) {
			if(is_array($searchable_post_types) && in_array($post_type_to_remove, $searchable_post_types)){
				unset( $searchable_post_types[ $post_type_to_remove ] );
				$query->set('post_type', $searchable_post_types);
			}
		}

	}
}
//add_action('pre_get_posts', 'entex_fn_remove_post_type_from_search_results');


function order_search_by_posttype($orderby){
	if (!is_admin() && is_search()) :
		global $wpdb;
		$orderby =
			"
            CASE WHEN {$wpdb->prefix}posts.post_type = 'page' THEN '1'
                 WHEN {$wpdb->prefix}posts.post_type = 'post' THEN '2'
                 WHEN {$wpdb->prefix}posts.post_type = 'tribe_events' THEN '3'
            ELSE {$wpdb->prefix}posts.post_type END ASC";
	endif;
	return $orderby;
}
add_filter('posts_orderby', 'order_search_by_posttype');




add_action( 'save_post_tribe_events', 'update_events_transient', 10, 3 );
function update_events_transient( $post_ID, $post, $update ) {
	delete_transient( 'events_for_widget' );
}

function add_async_attribute($tag, $handle) {
	if ( 'wk-analytics-script' !== $handle )
		return $tag;
	return str_replace( ' src', ' async="async" src', $tag );
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);
add_filter('block_editor_can_edit_post_type', '__return_false', 10);
add_filter('gutenberg_can_edit_post_type', '__return_false');
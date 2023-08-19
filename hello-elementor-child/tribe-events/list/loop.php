<?php
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @version 4.4
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<?php
global $post;
global $more;
$more = false;
?>

<div class="tribe-events-loop">
    <?php $parentArray = []; ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php do_action( 'tribe_events_inside_before_loop' ); ?>

		<!-- Month / Year Headers -->
		<?php tribe_events_list_the_date_headers(); ?>

		<!-- Event  -->
		<?php
		$post_parent = '';
		if ( $post->post_parent ) {
			$post_parent = ' data-parent-post-id="' . absint( $post->post_parent ) . '"';
		}

        if ( get_post_meta( $post->ID, 'zen_event_show_first_of_recurring_meta', true ) == 'yes' ) {
            if( isset( $parentArray[$post->post_parent . '_' . tribe_get_start_date($post->ID, false, 'm')]) && $parentArray[$post->post_parent . '_' . tribe_get_start_date($post->ID, false, 'm')] == 'yes' ) {
                continue;
            }
        }
        if( !isset( $parentArray[$post->post_parent . '_' . tribe_get_start_date($post->ID, false, 'm')]) ) {
            $parentArray[$post->post_parent . '_' . tribe_get_start_date($post->ID, false, 'm')] = 'yes';
        }

		?>
		<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?>" <?php echo $post_parent; ?>>
			<?php




			$event_type = tribe( 'tec.featured_events' )->is_featured( $post->ID ) ? 'featured' : 'event';

			/**
			 * Filters the event type used when selecting a template to render
			 *
			 * @param $event_type
			 */
			$event_type = apply_filters( 'tribe_events_list_view_event_type', $event_type );


			tribe_get_template_part( 'list/single', $event_type );
			?>
		</div>


		<?php do_action( 'tribe_events_inside_after_loop' ); ?>
	<?php endwhile; ?>

</div><!-- .tribe-events-loop -->

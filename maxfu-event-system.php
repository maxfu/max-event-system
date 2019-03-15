<?php
/**
 * Plugin Name: MaxFu Event System
 * Plugin URI:
 * Description: A plugin to show a list of upcoming events on the front-end. Based on Bilal Shahid's article.
 * Version: 1.0
 * Author: Max Fu
 * Author URI:
 * License: GPL2
 * Text Domain:       maxfu-event-system
 * Domain Path:       /languages
 */

/**
 * Defining constants for later use
 */
define( 'ROOT', plugins_url( '', __FILE__ ) );
define( 'IMAGES', ROOT . '/img/' );
define( 'STYLES', ROOT . '/css/' );
define( 'SCRIPTS', ROOT . '/js/' );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function mes_load_textdomain() {
  load_plugin_textdomain( 'maxfu-event-system', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'mes_load_textdomain' );

/**
 * Registering custom post type for events
 */
function mes_custom_post_type() {
	$labels = array(
		'name'								=>	__( 'Events', 'maxfu-event-system' ),
		'singular_name'				=>	__( 'Event', 'maxfu-event-system' ),
		'add_new'							=>	__( 'Add New', 'maxfu-event-system' ),
		'add_new_item'				=>	__( 'Add New Event', 'maxfu-event-system' ),
		'all_items'						=>	__( 'All Events', 'maxfu-event-system' ),
		'edit_item'						=>	__( 'Edit Event', 'maxfu-event-system' ),
		'new_item'						=>	__( 'New Event', 'maxfu-event-system' ),
		'view_item'						=>	__( 'View Event', 'maxfu-event-system' ),
		'not_found'						=>	__( 'No Events Found', 'maxfu-event-system' ),
		'not_found_in_trash'	=>	__( 'No Events Found in Trash', 'maxfu-event-system' )
	);

	$supports = array(
		'title',
		'editor',
		'excerpt',
    'thumbnail'
	);

	$args = array(
		'label'			=>	__( 'Events', 'maxfu-event-system' ),
		'labels'		=>	$labels,
		'description'	=>	__( 'A list of upcoming events', 'maxfu-event-system' ),
		'public'		=>	true,
		'show_in_menu'	=>	true,
		'menu_icon'		=>	IMAGES . 'event.svg',
		'has_archive'	=>	true,
		'rewrite'		=>	true,
		'supports'		=>	$supports
	);

	register_post_type( 'event', $args );
}
add_action( 'init', 'mes_custom_post_type' );


/**
 * Flushing rewrite rules on plugin activation/deactivation
 * for better working of permalink structure
 */
function mes_activation_deactivation() {
	mes_custom_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mes_activation_deactivation' );

/**
 * Adding metabox for event information
 */
function mes_add_event_info_metabox() {
	add_meta_box(
		'mes-event-info-metabox',
		__( 'Event Info', 'maxfu-event-system' ),
		'mes_render_event_info_metabox',
		'event',
		'side',
		'core'
	);
}
add_action( 'add_meta_boxes', 'mes_add_event_info_metabox' );


/**
 * Rendering the metabox for event information
 * @param  object $post The post object
 */
function mes_render_event_info_metabox( $post ) {
	//generate a nonce field
	wp_nonce_field( basename( __FILE__ ), 'mes-event-info-nonce' );

	//get previously saved meta values (if any)
	$event_start_date = get_post_meta( $post->ID, 'event-start-date', true );
	$event_end_date = get_post_meta( $post->ID, 'event-end-date', true );
	$event_venue = get_post_meta( $post->ID, 'event-venue', true );
  $event_rsvp = get_post_meta( $post->ID, 'event-rsvp', true );

	//if there is previously saved value then retrieve it, else set it to the current time
	$event_start_date = ! empty( $event_start_date ) ? $event_start_date : time();

	//we assume that if the end date is not present, event ends on the same day
	$event_end_date = ! empty( $event_end_date ) ? $event_end_date : $event_start_date;

	?>
	<p>
		<label for="mes-event-start-date"><?php _e( 'Event Start Date:', 'maxfu-event-system' ); ?></label>
		<input type="text" id="mes-event-start-date" name="mes-event-start-date" class="widefat mes-event-date-input" value="<?php echo date( 'M d, Y H:i', $event_start_date ); ?>" placeholder="Format: February 18, 2014">
	</p>
	<p>
		<label for="mes-event-end-date"><?php _e( 'Event End Date:', 'maxfu-event-system' ); ?></label>
		<input type="text" id="mes-event-end-date" name="mes-event-end-date" class="widefat mes-event-date-input" value="<?php echo date( 'M d, Y H:i', $event_end_date ); ?>" placeholder="Format: February 18, 2014">
	</p>
	<p>
		<label for="mes-event-venue"><?php _e( 'Event Venue:', 'maxfu-event-system' ); ?></label>
		<input type="text" id="mes-event-venue" name="mes-event-venue" class="widefat" value="<?php echo $event_venue; ?>" placeholder="eg. Times Square">
	</p>
  <p>
		<label for="mes-event-rsvp"><?php _e( 'RSVP Availability:', 'maxfu-event-system' ); ?></label>
    <select name="mes-event-rsvp" id="mes-event-rsvp">
			<option <?php echo ( $event_rsvp === 'All' ) ? 'selected' : '' ?>>All</option>
			<option <?php echo ( $event_rsvp === 'Member' ) ? 'selected' : '' ?>>Member</option>
			<option <?php echo ( $event_rsvp === 'No' ) ? 'selected' : '' ?>>No</option>
		</select>
	</p>
	<?php
}

/**
 * Enqueueing scripts and styles in the admin
 * @param  int $hook Current page hook
 */
function mes_admin_script_style( $hook ) {
	global $post_type;

	if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( 'event' == $post_type ) ) {
    wp_enqueue_script(
			'jquery-ui-datetimepicker',
			'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js',
			array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider' ),
			'1.6.3',
			true
		);

    wp_enqueue_script(
			'upcoming-events',
			SCRIPTS . 'script.js',
			array( 'jquery', 'jquery-ui-datepicker' , 'jquery-ui-datetimepicker' ),
			'1.1',
			true
		);

    wp_enqueue_style(
			'jquery-ui-calendar',
			STYLES . 'jquery-ui-1.10.4.custom.min.css',
			false,
			'1.10.4',
			'all'
		);

    wp_enqueue_style(
			'jquery-ui-datetimepicker-css',
			'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css',
			false,
			'1.6.3',
			'all'
		);
	}
}
add_action( 'admin_enqueue_scripts', 'mes_admin_script_style' );


/**
 * Enqueueing styles for the front-end widget
 */
function mes_widget_style() {
	if ( is_active_widget( '', '', 'mes_upcoming_events', true ) ) {
		wp_enqueue_style(
			'upcoming-events',
			STYLES . 'maxfu-event-system.css',
			false,
			'1.0',
			'all'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'mes_widget_style' );

if (!function_exists('myStrtotime')) {
  function myStrtotime($date_string) {
    $date_string = str_replace('.', '', $date_string); // to remove dots in short names of months, such as in 'janv.', 'févr.', 'avr.', ...
    return strtotime(
      strtr(
        strtolower($date_string), [
          '一月'=>'Jan',
          '二月'=>'Feb',
          '三月'=>'March',
          '四月'=>'Apr',
          '五月'=>'May',
          '六月'=>'Jun',
          '七月'=>'Jul',
          '八月'=>'Aug',
          '九月'=>'Sep',
          '十月'=>'Oct',
          '十一月'=>'Nov',
          '十二月'=>'Dec',
          '星期一' => 'Monday',
          '星期二' => 'Tuesday',
          '星期三' => 'Wednesday',
          '星期四' => 'Thursday',
          '星期五' => 'Friday',
          '星期六' => 'Saturday',
          '星期日' => 'Sunday',
        ]
      )
    );
  }
}


/**
 * Saving the event along with its meta values
 * @param  int $post_id The id of the current post
 */
function mes_save_event_info( $post_id ) {
	//checking if the post being saved is an 'event',
	//if not, then return
	if ( 'event' != $_POST['post_type'] ) {
		return;
	}

	//checking for the 'save' status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['mes-event-info-nonce'] ) && ( wp_verify_nonce( $_POST['mes-event-info-nonce'], basename( __FILE__ ) ) ) ) ? true : false;

	//exit depending on the save status or if the nonce is not valid
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	//checking for the values and performing necessary actions
	if ( isset( $_POST['mes-event-start-date'] ) ) {
    update_post_meta( $post_id, 'event-start-date', myStrtotime( $_POST['mes-event-start-date'] ) );
	}

	if ( isset( $_POST['mes-event-end-date'] ) ) {
    update_post_meta( $post_id, 'event-end-date', myStrtotime( $_POST['mes-event-end-date'] ) );
	}

	if ( isset( $_POST['mes-event-venue'] ) ) {
		update_post_meta( $post_id, 'event-venue', sanitize_text_field( $_POST['mes-event-venue'] ) );
	}

  if ( isset( $_POST['mes-event-rsvp'] ) ) {
    update_post_meta( $post_id, 'event-rsvp', esc_attr( $_POST['mes-event-rsvp'] ) );
  }

  if ( $_POST['post_type'] == "event") {
    // Store data in post meta table if present in post data
    if ( isset($_POST['event-news'] ) && $_POST['event-news'] != '' ) {
      update_post_meta( $post_id, 'event-news', $_POST['event-news'] );
    }
  } else {
    delete_post_meta( $post_id, 'event-news' );
  }
}
add_action( 'save_post', 'mes_save_event_info' );


/**
 * Custom columns head
 * @param  array $defaults The default columns in the post admin
 */
function mes_custom_columns_head( $defaults ) {
	unset( $defaults['date'] );

	$defaults['event_start_date'] = __( 'Start Date', 'maxfu-event-system' );
	$defaults['event_end_date'] = __( 'End Date', 'maxfu-event-system' );
	$defaults['event_venue'] = __( 'Venue', 'maxfu-event-system' );
  $defaults['event_rsvp'] = __( 'RSVP', 'maxfu-event-system' );

	return $defaults;
}
add_filter( 'manage_edit-event_columns', 'mes_custom_columns_head', 10 );

/**
 * Custom columns content
 * @param  string 	$column_name The name of the current column
 * @param  int 		$post_id     The id of the current post
 */
function mes_custom_columns_content( $column_name, $post_id ) {
	if ( 'event_start_date' == $column_name ) {
		$start_date = get_post_meta( $post_id, 'event-start-date', true );
		echo date( 'M d, Y H:i', $start_date );
	}

	if ( 'event_end_date' == $column_name ) {
		$end_date = get_post_meta( $post_id, 'event-end-date', true );
		echo date( 'M d, Y H:i', $end_date );
	}

	if ( 'event_venue' == $column_name ) {
		$venue = get_post_meta( $post_id, 'event-venue', true );
		echo $venue;
	}

  if ( 'event_rsvp' == $column_name ) {
		$rsvp = get_post_meta( $post_id, 'event-rsvp', true );
		echo $rsvp;
	}
}
add_action( 'manage_event_posts_custom_column', 'mes_custom_columns_content', 10, 2 );


/**
 * Including the widget
 */
include( 'inc/widget-maxfu-event-system.php' );

/**
 * Adding Template
 */
 function get_event_template($single_template) {
      global $post;

      if ($post->post_type == 'event') {
           $single_template = dirname( __FILE__ ) . '/inc/single-event.php';
      }
      return $single_template;
 }
 add_filter( 'single_template', 'get_event_template' );

function get_event_archive_template( $archive_template ) {
     global $post;

     if ( is_post_type_archive ( 'event' ) ) {
          $archive_template = dirname( __FILE__ ) . '/inc/archive-event.php';
     }
     return $archive_template;
}
add_filter( 'archive_template', 'get_event_archive_template' ) ;

// Create Missionary Meta Box
function mes_add_event_news_metabox() {
  add_meta_box(
    'mes-event-news-metabox',
    __( 'Event News', 'maxfu-event-system' ),
    'display_event_news_meta_box',
    'event',
    'normal',
    'high'
  );
}
add_action( 'add_meta_boxes', 'mes_add_event_news_metabox' );

// Put Fields In Missionary Meta Box
function display_event_news_meta_box( $post ) {
  $event_news = get_post_meta($post->ID, 'event-news', true);
?>
<table>
  <tr>
    <td><?php wp_editor($event_news, 'event-news', array(
            'wpautop'       =>      true,
            'media_buttons' =>      true,
            'textarea_name' =>      'event-news'
            ));
        ?>
      </td>
    </tr>
</table>
<?php
}

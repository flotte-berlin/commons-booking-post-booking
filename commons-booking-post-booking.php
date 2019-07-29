<?php
/*
Plugin Name:  Commons Booking Post Booking
Plugin URI:   https://github.com/flotte-berlin/commons-booking-post-booking
Description:  Erweitert das Commons Booking Plugin um das Versenden einer Email an Nutzer, deren Buchungszeitraum bevorsteht bzw. beendet wurde
Version:      0.3.1
Author:       poilu
Author URI:   https://github.com/poilu
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

define( 'CB_POST_BOOKING_PATH', plugin_dir_path( __FILE__ ) );

require_once( CB_POST_BOOKING_PATH . 'functions/translate.php' );
require_once( CB_POST_BOOKING_PATH . 'functions/is-plugin-active.php' );
require_once( CB_POST_BOOKING_PATH . 'classes/class-cb-post-booking.php' );
require_once( CB_POST_BOOKING_PATH . 'classes/class-cb-post-booking-admin.php' );

$cb_post_booking = new CB_Post_Booking();

add_action('cb_ahead_booking_check', array($cb_post_booking, 'check_ahead_bookings'));
add_action('cb_ended_booking_check', array($cb_post_booking, 'check_ended_bookings'));

$cb_post_booking_admin = new CB_Post_Booking_Admin();
$cb_post_booking_admin->load_post_booking_admin();
add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ), array($cb_post_booking_admin, 'add_settings_link') );

register_activation_hook( __FILE__, array( $cb_post_booking, 'activate' ));
register_deactivation_hook( __FILE__, array( $cb_post_booking, 'deactivate' ));

?>

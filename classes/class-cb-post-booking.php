<?php

Class CB_Post_Booking {

  public $options;
  public $email_message;

  public function __construct() {
    $this->options = get_option('cb_post_booking_options', array());

    //set email templates
    if(count($this->options) > 0) {
      $this->email_message = array(
        'ahead' =>
          array(
            'subject' => $this->get_option('ahead_email_subject', ''),
            'body' => $this->get_option('ahead_email_body', '')
          ),
        'end' =>
          array(
            'subject' => $this->get_option('end_email_subject', ''),
            'body' => $this->get_option('end_email_body', '')
          )
      );
    }

  }

  /**
  * option getter
  **/
  public function get_option($key, $default = false) {
    return isset($this->options[$key]) ? $this->options[$key] : $default;
  }

  /**
  * calculate cron event start date from given time string
  **/
  public function get_event_start_date_from_time($time_string) {
    $now = new DateTime('now', new DateTimeZone(get_option('timezone_string')));
    $then = new DateTime($time_string, new DateTimeZone(get_option('timezone_string')));

    $first_day = $now < $then ? 'today' : 'tomorrow';
    return new DateTime($first_day . ' ' . $time_string, new DateTimeZone(get_option('timezone_string')));
  }

  /**
  * plugin activation hook - activate cron events for email types that are set as active
  **/
  public function activate() {

    $ahead_check_is_active = $this->get_option('ahead_email_is_active', false );
    if($ahead_check_is_active) {
      $ahead_date = $this->get_event_start_date_from_time($this->get_option('ahead_email_time'));
      $this->activate_event('cb_ahead_booking_check', $ahead_date);
    }

    $ended_check_is_active = $this->get_option('end_email_is_active', false );
    if($ended_check_is_active) {
      $ended_date = $this->get_event_start_date_from_time($this->get_option('end_email_time'));
      $this->activate_event('cb_ended_booking_check', $ended_date);
    }

  }

  /**
  * schedule cron event with given name for provided datetime
  */
  public function activate_event($event_name, $datetime) {
    $timestamp = $datetime->getTimestamp();
    wp_schedule_event( $timestamp, 'daily', $event_name);
  }

  /**
  * unschedule cron event with given name
  **/
  public function deactivate_event($event_name) {
    wp_clear_scheduled_hook($event_name);
  }

  /**
  * plugin deactivation hook - deactivate cron events
  **/
  public function deactivate() {

    $this->deactivate_event('cb_ahead_booking_check');
    $this->deactivate_event('cb_ended_booking_check');

  }

  /**
  * check ahead bookings for that emails have to be sent and execute sending
  **/
  public function check_ahead_bookings() {

    $min_days_since_creation = $this->get_option('ahead_email_min_days_since_creation', 3 );
    $days_in_advance = $this->get_option('ahead_email_days_in_advance', 3 );

    $min_booking_date  = strtotime("-".$min_days_since_creation." days");

    $day = date('Y-m-d',strtotime("+".$days_in_advance." days"));

    $bookings = $this->fetch_confirmed_bookings_by_date('date_start', $day);

    foreach ($bookings as $booking) {
      if(strtotime($booking->booking_time) < $min_booking_date) {
        if(!$this->is_item_usage_restricted($booking->item_id, $booking->date_start, $booking->date_end)) {
          $this->send_booking_mail_by_type('ahead', $booking);
        }
      }
    }
  }

  /**
  * check bookings that end(ed) and send emails
  **/
  public function check_ended_bookings() {

    $day_string = $this->get_option('end_email_day') == 1 ? "-1 days" : "now";
    $date_end = date('Y-m-d',strtotime($day_string));

    $bookings = $this->fetch_confirmed_bookings_by_date('date_end', $date_end);

    foreach ($bookings as $booking) {
      if(!$this->is_item_usage_restricted($booking->item_id, $booking->date_start, $booking->date_end)) {
        $this->send_booking_mail_by_type('end', $booking);
      }
    }

  }

  /**
  * checks, if there is an item usage restriction (total breakdown)
  **/
  public function is_item_usage_restricted($item_id, $date_start, $date_end) {
    $is_restricted = false;

    if(cb_post_booking\is_plugin_active('commons-booking-item-usage-restriction.php') && method_exists('CB_Item_Usage_Restriction','is_item_restricted')) {
      $is_restricted = CB_Item_Usage_Restriction::is_item_restricted($item_id, $date_start, $date_end);
    }

    return $is_restricted;
  }

  /**
  * fetch confirmed bookings where date of given type equals the given date
  **/
  function fetch_confirmed_bookings_by_date($date_type, $date) {
    global $wpdb;

    //get bookings data
    $table_name = $wpdb->prefix . 'cb_bookings';
    $select_statement = "SELECT * FROM $table_name WHERE ".$date_type." = '".$date."' AND status = 'confirmed'";

    $bookings_result = $wpdb->get_results($select_statement);

    return $bookings_result;
  }

  /**
  * create variables used in emails template
  **/
  function create_mail_vars($booking, $item, $location, $user_data) {

    return array(
      'first_name' => $user_data->first_name,
      'last_name' => $user_data->last_name,
      'date_start' => date_i18n( get_option( 'date_format' ), strtotime($booking->date_start) ),
      'date_end' => date_i18n( get_option( 'date_format' ), strtotime($booking->date_end) ),
      'item_name' => $item->post_title,
      'location_name' => $location->post_title,
      'hash' => $booking->hash
    );
  }

  /**
  * send email of given type for the provided booking
  **/
  function send_booking_mail_by_type($type, $booking) {

    $user_data = get_userdata($booking->user_id);

    $item = get_post($booking->item_id);
    $location = get_post($booking->location_id);

    $subject_template = ( $this->email_message[$type]['subject'] );
    $body_template = ( $this->email_message[$type]['body'] );
    $mail_vars = $this->create_mail_vars($booking, $item, $location, $user_data);

    $this->send_mail($user_data->user_email, $subject_template, $body_template, $mail_vars);
  }

  /**
   * Replace template tags – {MYTAG} with tags array
   *
   *@param string to replace
   *@param array of tags
   *
   *@return string
  */
  function replace_email_template_tags( $string, $tags_array ) {
    foreach($tags_array as $key => $value){
        $string = str_replace('{{'.strtoupper($key).'}}', $value, $string);
    }
    return $string;
  }

  /**
  * send an email
  **/
  function send_mail($to, $subject_template, $body_template, $mail_vars) {
    $cb_booking = new CB_Booking();

    $sender_from_email = $cb_booking->settings->get_settings( 'mail', 'mail_from');
    $sender_from_name = $cb_booking->settings->get_settings( 'mail', 'mail_from_name');
    $confirmation_bcc = $cb_booking->settings->get_settings( 'mail', 'mail_bcc');

    // if custom email adress AND name is specified in settings use them, otherwise fall back to standard
    if ( ! empty ( $sender_from_name ) && ! empty ( $sender_from_email )) {
        $headers[] = 'From: ' . $sender_from_name . ' <' . $sender_from_email . '>';
    }

    // if BCC: ist specified, send a copy to the address
    if ( ! empty ( $confirmation_bcc ) ) {
        $headers[] = 'BCC: ' . $confirmation_bcc . "\r\n";
    }

    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $subject = $this->replace_email_template_tags( $subject_template, $mail_vars);
    $body = $this->replace_email_template_tags( $body_template, $mail_vars);

    wp_mail( $to, $subject, $body, $headers );
  }


}

?>

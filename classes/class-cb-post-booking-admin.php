<?php

Class CB_Post_Booking_Admin {

  /**
  * loads post booking admin functionality
  */
  public function load_post_booking_admin() {

    //load translation
    $lang_path = 'commons-booking-post-booking/languages/';
    load_plugin_textdomain( 'commons-booking-post-booking', false, $lang_path );

    add_action('admin_menu', function() {
        add_options_page( cb_post_booking\__('POST_BOOKING_SETTINGS_TITLE', 'commons-booking-post-booking', 'Post Booking Settings'), cb_post_booking\__('POST_BOOKING_SETTINGS_MENU', 'commons-booking-post-booking', 'Commons Booking Post Booking'), 'manage_options', 'commons-booking-post-booking', array($this, 'render_options_page') );
    });

    add_action( 'admin_init', function() {
      register_setting( 'cb-post-booking-settings', 'cb_post_booking_options', array($this, 'validate_options'));
    });

  }

  /**
  * sanitize and validate the options provided by input array
  **/
  public function validate_options($input = array()) {
    //var_dump($input);
    $validated_input = array();

    //ahead email
    $validated_input['ahead_email_subject'] = $input['ahead_email_subject'];
    $validated_input['ahead_email_body'] = $input['ahead_email_body'];

    if(isset($input['ahead_email_min_days_since_creation']) && !empty($input['ahead_email_min_days_since_creation'])) {
      $ahead_email_min_days_since_creation = (integer) $input['ahead_email_min_days_since_creation'];
      if($ahead_email_min_days_since_creation >= 1 && $ahead_email_min_days_since_creation <= 14) {
        $validated_input['ahead_email_min_days_since_creation'] = $ahead_email_min_days_since_creation;
      }
      else {
        $validated_input['ahead_email_min_days_since_creation'] = 3;
      }
    }
    else {
      $validated_input['ahead_email_min_days_since_creation'] = 3;
    }

    if(isset($input['ahead_email_days_in_advance']) && !empty($input['ahead_email_days_in_advance'])) {
      $ahead_email_days_in_advance = (integer) $input['ahead_email_days_in_advance'];
      if($ahead_email_days_in_advance >= 1 && $ahead_email_days_in_advance <= 14) {
        $validated_input['ahead_email_days_in_advance'] = $ahead_email_days_in_advance;
      }
      else {
        $validated_input['ahead_email_days_in_advance'] = 3;
      }
    }
    else {
      $validated_input['ahead_email_days_in_advance'] = 3;
    }

    if(isset($input['ahead_email_is_active'])) {
      $validated_input['ahead_email_is_active'] = 'on';
    }

    if(isset($input['ahead_email_time']) && !empty($input['ahead_email_time'])) {
      $is_valid_ahead_email_time = $this->is_valid_time($input['ahead_email_time']);
      if($is_valid_ahead_email_time) {
        $validated_input['ahead_email_time'] = $input['ahead_email_time'];
      }
      else {
        unset($validated_input['ahead_email_is_active']);
      }
    }
    else {
      unset($validated_input['ahead_email_is_active']);
    }

    //end email
    $validated_input['end_email_subject'] = $input['end_email_subject'];
    $validated_input['end_email_body'] = $input['end_email_body'];

    if(isset($input['end_email_is_active'])) {
      $validated_input['end_email_is_active'] = 'on';
    }

    if(isset($input['end_email_time']) && !empty($input['end_email_time'])) {
      $is_valid_end_email_time = $this->is_valid_time($input['end_email_time']);
      if($is_valid_end_email_time) {
        $validated_input['end_email_time'] = $input['end_email_time'];
      }
      else {
        unset($validated_input['end_email_is_active']);
      }
    }
    else {
      unset($validated_input['end_email_is_active']);
    }

    if(isset($input['end_email_day']) && !empty($input['end_email_day'])) {
      $end_email_day = (integer) $input['end_email_day'];
      if($end_email_day == 1 || $end_email_day == 2) {
        $validated_input['end_email_day'] = $end_email_day;
      }
      else {
        $validated_input['end_email_day'] = 1;
      }
    }
    else {
      $validated_input['end_email_day'] = 1;
    }

    // location start email
    $validated_input['location_start_email_subject'] = $input['location_start_email_subject'];
    $validated_input['location_start_email_body'] = $input['location_start_email_body'];

    if(isset($input['location_start_email_is_active'])) {
      $validated_input['location_start_email_is_active'] = 'on';
    }

    if(isset($input['location_start_email_time']) && !empty($input['location_start_email_time'])) {
      $is_valid_start_email_time = $this->is_valid_time($input['location_start_email_time']);
      if($is_valid_start_email_time) {
        $validated_input['location_start_email_time'] = $input['location_start_email_time'];
      }
      else {
        unset($validated_input['location_start_email_is_active']);
      }
    }
    else {
      unset($validated_input['location_start_email_is_active']);
    }

    if(isset($input['location_start_email_day']) && !empty($input['location_start_email_day'])) {
      $location_start_email_day = (integer) $input['location_start_email_day'];
      if($location_start_email_day == 2 || $location_start_email_day == 3) {
        $validated_input['location_start_email_day'] = $location_start_email_day;
      }
      else {
        $validated_input['location_start_email_day'] = 1;
      }
    }
    else {
      $validated_input['location_start_email_day'] = 1;
    }

    //location end email
    $validated_input['location_end_email_subject'] = $input['location_end_email_subject'];
    $validated_input['location_end_email_body'] = $input['location_end_email_body'];

    if(isset($input['location_end_email_is_active'])) {
      $validated_input['location_end_email_is_active'] = 'on';
    }

    if(isset($input['location_end_email_time']) && !empty($input['location_end_email_time'])) {
      $is_valid_end_email_time = $this->is_valid_time($input['location_end_email_time']);
      if($is_valid_end_email_time) {
        $validated_input['location_end_email_time'] = $input['location_end_email_time'];
      }
      else {
        unset($validated_input['location_end_email_is_active']);
      }
    }
    else {
      unset($validated_input['location_end_email_is_active']);
    }

    if(isset($input['location_end_email_day']) && !empty($input['location_end_email_day'])) {
      $location_end_email_day = (integer) $input['location_end_email_day'];
      if($location_end_email_day == 2 || $location_end_email_day == 3) {
        $validated_input['location_end_email_day'] = $location_end_email_day;
      }
      else {
        $validated_input['location_end_email_day'] = 1;
      }
    }
    else {
      $validated_input['location_end_email_day'] = 1;
    }

    $user_roles = $this->get_all_user_roles();
    $validated_input['location_email_role_exceptions'] = [];
    foreach ($input['location_email_role_exceptions'] as $role_name => $value) {
      if(in_array($role_name, array_keys($user_roles))) {
        $validated_input['location_email_role_exceptions'][$role_name] = 'on';
      }
    }

    //activate/deactivate cron jobs
    $this->set_cronjobs($validated_input);

    return $validated_input;
  }

  /**
  * check if given time string is valid ('H:i' format)
  **/
  public function is_valid_time($time_string) {
    $format = 'H:i';
    $dateObj = DateTime::createFromFormat($format, $time_string);
    return $dateObj && $dateObj->format($format) == $time_string;
  }

  /**
  * set cronjobs based on the given options input
  **/
  public function set_cronjobs($input) {

    $cb_post_booking = new CB_Post_Booking();

    $cb_post_booking->deactivate_event('cb_ahead_booking_check');
    if(isset($input['ahead_email_is_active'])) {
      $ahead_date = $cb_post_booking->get_event_start_date_from_time($input['ahead_email_time']);
      $cb_post_booking->activate_event('cb_ahead_booking_check', $ahead_date);
    }

    $cb_post_booking->deactivate_event('cb_ended_booking_check');
    if(isset($input['end_email_is_active'])) {
      $ended_date = $cb_post_booking->get_event_start_date_from_time($input['end_email_time']);
      $cb_post_booking->activate_event('cb_ended_booking_check', $ended_date);
    }

    $cb_post_booking->deactivate_event('cb_location_start_booking_check');
    if(isset($input['location_start_email_is_active'])) {
      $started_date = $cb_post_booking->get_event_start_date_from_time($input['location_start_email_time']);
      $cb_post_booking->activate_event('cb_location_start_booking_check', $started_date);
    }

    $cb_post_booking->deactivate_event('cb_location_end_booking_check');
    if(isset($input['location_end_email_is_active'])) {
      $ended_date = $cb_post_booking->get_event_start_date_from_time($input['location_end_email_time']);
      $cb_post_booking->activate_event('cb_location_end_booking_check', $ended_date);
    }

  }

  /**
  * add link to settings page (on wp plugins page)
  **/
  public function add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=commons-booking-post-booking">' . __( 'Settings') . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
  }

  /**
  * render the settings page based on template
  **/
  public function render_options_page() {
    $cb_post_booking = new CB_Post_Booking();

    $user_roles = $this->get_all_user_roles();

    include_once( CB_POST_BOOKING_PATH . 'templates/cb-post-booking-admin.php');
  }

  public function get_all_user_roles() {
    global $wp_roles;
    $roles = $wp_roles->roles;
    
    return $roles;
  }

  /*** settings on location admin page ***/

  /**
  * loads special days admin functionality
  */
  public function load_post_booking_location_admin() {

    global $pagenow;
    if (( $pagenow == 'post.php' ) || (get_post_type() == 'post')) {
      require_once( CB_POST_BOOKING_PATH . '../commons-booking/admin/includes/CMB2/init.php' );

      add_filter( 'cmb2_meta_boxes', array($this, 'add_metabox') );
    }

  }

  public function add_metabox(array $meta_boxes) {
    //var_dump($meta_boxes);

    $meta_boxes['cb_location_metabox_contactinfo']['fields'][] = [
      "id" => "cb_post_booking_emails",
      "type" => "checkbox",
      "name" => cb_post_booking\__('SEND_EMAILS_TO_LOCATION_CONTACT', 'commons-booking-post-booking', 'send emails about starting & ending bookings')
    ];

    return $meta_boxes;
  }
}

?>

<div class="wrap">
      <h1><?= cb_post_booking\__('SETTINGS_PAGE_HEADER', 'commons-booking-post-booking', 'Commons Booking Post Booking - Settings') ?></h1>

      <?= cb_post_booking\__('SETTINGS_DESCRIPTION', 'commons-booking-post-booking', 'Settings for additional Commons Booking emails, which are sent before and after the booking period.<br> The following template tags are available: {{FIRST_NAME}}, {{LAST_NAME}}, {{DATE_START}}, {{DATE_END}}, {{ITEM_NAME}}, {{LOCATION_NAME}}, {{HASH}}') ?>

      <form method="post" action="options.php">
        <?php
          settings_fields( 'cb-post-booking-settings' );
          do_settings_sections( 'cb-post-booking-settings' );
        ?>

        <h2><?= cb_post_booking\__('AHEAD_EMAIL_HEADER', 'commons-booking-post-booking', 'Email as booking reminder') ?></h2>

        <p>
          <?= cb_post_booking\__('AHEAD_EMAIL_DESCRIPTION', 'commons-booking-post-booking', "X days in advantage an email is sent to users to remind them of their booking.<br> Here you could ask them to cancel the booking if they don't need the item.") ?>
        </p>

        <table>

            <tr>
                <th><?= cb_post_booking\__('EMAIL_SUBJECT', 'commons-booking-post-booking', 'email subject') ?>:</th>
                <td><input type="text" placeholder="<?= cb_post_booking\__('AHEAD_EMAIL_SUBJECT_PLACEHOLDER', 'commons-booking-post-booking', 'i.e. Booking period ahead') ?>" name="cb_post_booking_options[ahead_email_subject]" value="<?php echo esc_attr( $cb_post_booking->get_option('ahead_email_subject') ); ?>" size="50" /></td>
            </tr>
            <tr>
                <th><?= cb_post_booking\__('EMAIL_CONTENT', 'commons-booking-post-booking', 'email content') ?>:</th>
                <td><textarea placeholder="<?= cb_post_booking\__('AHEAD_EMAIL_CONTENT_PLACEHOLDER', 'commons-booking-post-booking', "i.e. <h2>Dear {{FIRST_NAME}},</h2><p>your booking period will start shortly. if you won't use the booked item, please cancel your booking, so other interested people would have the opportunity to do so.</p>") ?>" name="cb_post_booking_options[ahead_email_body]" rows="10" cols="53"><?php echo esc_attr( $cb_post_booking->get_option('ahead_email_body') ); ?></textarea></td>
            </tr>

            <tr>
                <th><?= cb_post_booking\__('MIN_DAYS_SINCE_CREATION', 'commons-booking-post-booking', 'min. days since creation') ?>:</th>
                <td><input type="number" name="cb_post_booking_options[ahead_email_min_days_since_creation]" min="1" max="14" value="<?php echo esc_attr($cb_post_booking->get_option('ahead_email_min_days_since_creation') ?: 3) ?>"></td>
            </tr>

            <tr>
                <th><?= cb_post_booking\__('DAYS_IN_ADVANCE', 'commons-booking-post-booking', 'days in advance') ?>:</th>
                <td><input type="number" name="cb_post_booking_options[ahead_email_days_in_advance]" min="1" max="14" value="<?php echo esc_attr($cb_post_booking->get_option('ahead_email_days_in_advance') ?: 3) ?>"></td>
            </tr>

            <tr>
                <th><?= cb_post_booking\__('EMAIL_ACTIVE', 'commons-booking-post-booking', 'Activate email?') ?></th>
                <td>
                    <label>
                        <input type="checkbox" id="cb_post_booking_ahead_email_is_active" name="cb_post_booking_options[ahead_email_is_active]" <?php echo esc_attr( $cb_post_booking->get_option('ahead_email_is_active') ) == 'on' ? 'checked="checked"' : ''; ?> />
                        <?= cb_post_booking\__('EMAIL_ACTIVE_CONFIRM', 'commons-booking-post-booking', 'Yes, send email') ?>
                        <?= cb_post_booking\__('AT', 'commons-booking-post-booking', 'at') ?>
                        <input type="time" id="cb_post_booking_ahead_email_time" name="cb_post_booking_options[ahead_email_time]" value="<?php echo $cb_post_booking->get_option('ahead_email_time') ?>">
                        <?= cb_post_booking\__('CLOCK', 'commons-booking-post-booking', "o'clock") ?>
                    </label><br/>
                </td>
            </tr>

        </table>

        <h2><?= cb_post_booking\__('END_EMAIL_HEADER', 'commons-booking-post-booking', 'Email after booking period ended') ?></h2>

        <p>
          <?= cb_post_booking\__('END_EMAIL_DESCRIPTION', 'commons-booking-post-booking', "Every day the plugin sends an email to all users who have a confirmed booking that ended.") ?>
        </p>

        <table>

            <tr>
                <th><?= cb_post_booking\__('EMAIL_SUBJECT', 'commons-booking-post-booking', 'email subject') ?>:</th>
                <td><input type="text" placeholder="<?= cb_post_booking\__('END_EMAIL_SUBJECT_PLACEHOLDER', 'commons-booking-post-booking', 'i.e. Your booking period ended') ?>" name="cb_post_booking_options[end_email_subject]" value="<?php echo esc_attr( $cb_post_booking->get_option('end_email_subject') ); ?>" size="50" /></td>
            </tr>
            <tr>
                <th><?= cb_post_booking\__('EMAIL_CONTENT', 'commons-booking-post-booking', 'email content') ?>:</th>
                <td><textarea placeholder="<?= cb_post_booking\__('END_EMAIL_CONTENT_PLACEHOLDER', 'commons-booking-post-booking', "i.e. <h2>Dear {{FIRST_NAME}},</h2><p>your booking has ended. We hope everything worked as expected. Please let us know, if any problems occured.</p>") ?>" name="cb_post_booking_options[end_email_body]" rows="10" cols="53"><?php echo esc_attr( $cb_post_booking->get_option('end_email_body') ); ?></textarea></td>
            </tr>

            <tr>
                <th><?= cb_post_booking\__('EMAIL_ACTIVE', 'commons-booking-post-booking', 'Activate email?') ?></th>
                <td>
                    <label>
                        <input type="checkbox" id="cb_post_booking_end_email_is_active" name="cb_post_booking_options[end_email_is_active]" <?php echo esc_attr( $cb_post_booking->get_option('end_email_is_active') ) == 'on' ? 'checked="checked"' : ''; ?> />
                        <?= cb_post_booking\__('EMAIL_ACTIVE_CONFIRM', 'commons-booking-post-booking', 'Yes, send email') ?>
                        <?= cb_post_booking\__('AT', 'commons-booking-post-booking', 'at') ?>
                        <input type="time" id="cb_post_booking_end_email_time" name="cb_post_booking_options[end_email_time]" value="<?php echo $cb_post_booking->get_option('end_email_time') ?>">
                        <?= cb_post_booking\__('CLOCK', 'commons-booking-post-booking', "o'clock") ?>
                        <?= cb_post_booking\__('FOR_BOOKINGS_FROM', 'commons-booking-post-booking', 'for bookings of') ?>
                        <select name="cb_post_booking_options[end_email_day]">
                          <option value="1" <?php echo $cb_post_booking->get_option('end_email_day') == 1 ? 'selected' : '' ?>><?= cb_post_booking\__('DAY_BEFORE', 'commons-booking-post-booking', 'the day before') ?></option>
                          <option value="2" <?php echo $cb_post_booking->get_option('end_email_day') == 2 ? 'selected' : '' ?>><?= cb_post_booking\__('CURRENT_DAY', 'commons-booking-post-booking', 'the current day') ?></option>
                        </select>
                    </label><br/>
                </td>
            </tr>

            <tr>
                <td><?php submit_button(); ?></td>
            </tr>

        </table>

      </form>
    </div>

<script>

  jQuery( document ).ready(function() {
    $ = jQuery;

    function check_required($if_checked, $then_required) {
      console.log('check_required: ', $if_checked, $then_required);
      if($if_checked.is(':checked')) {
        $then_required.prop('required', true);
      }
      else {
        $then_required.prop('required', false);
      }
    }

    var $ahead_email_is_active = $('#cb_post_booking_ahead_email_is_active');
    var $end_email_is_active = $('#cb_post_booking_end_email_is_active');

    check_required($ahead_email_is_active, $('#cb_post_booking_ahead_email_time'));
    check_required($end_email_is_active, $('#cb_post_booking_end_email_time'));

    $ahead_email_is_active.change(function() {
      check_required($ahead_email_is_active, $('#cb_post_booking_ahead_email_time'));
    });

    $end_email_is_active.change(function() {
      check_required($end_email_is_active, $('#cb_post_booking_end_email_time'));
    });
  });

</script>

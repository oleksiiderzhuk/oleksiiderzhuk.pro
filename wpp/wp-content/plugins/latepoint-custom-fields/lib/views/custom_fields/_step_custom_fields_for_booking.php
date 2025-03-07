<div class="step-custom-fields-for-booking-w latepoint-step-content" data-step-name="custom_fields_for_booking">
  <div class="os-row">
  <?php
    if(isset($custom_fields_for_booking) && !empty($custom_fields_for_booking)){
		  echo OsCustomFieldsHelper::output_custom_fields_for_model($custom_fields_for_booking, $booking, 'booking');
    }?>
  </div>
</div>
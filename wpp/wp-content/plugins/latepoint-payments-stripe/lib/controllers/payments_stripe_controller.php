<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


if ( ! class_exists( 'OsPaymentsStripeController' ) ) :


  class OsPaymentsStripeController extends OsController {


    function __construct(){
      parent::__construct();

      $this->action_access['public'] = array_merge($this->action_access['public'], ['webhook']);
      $this->action_access['customer'] = array_merge($this->action_access['customer'], ['create_payment_intent', 
                                                                                        'create_checkout_session']);
      $this->views_folder = plugin_dir_path( __FILE__ ) . '../views/payments_stripe/';
    }


    // catches webhooks from stripe
    public function webhook(){
      $payload = @file_get_contents('php://input');
      $event = null;

      try {
          $event = \Stripe\Event::constructFrom(
              json_decode($payload, true)
          );
      } catch(\UnexpectedValueException $e) {
          // Invalid payload
          http_response_code(400);
          exit();
      } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        http_response_code(400);
        exit();
      }

      // Handle the event
      switch ($event->type) {
        case 'payment_intent.succeeded':
          $payment_intent = $event->data->object; // contains a \Stripe\PaymentIntent
          // Then define and call a method to handle the successful payment intent.
          // handlePaymentIntentSucceeded($paymentIntent);
          if($payment_intent->metadata['booking_intent_key']){
            OsBookingIntentHelper::convert_intent_to_booking($payment_intent->metadata['booking_intent_key']);
          }
          break;
        case 'checkout.session.completed':
          $checkout_session = $event->data->object; // contains a \Stripe\CheckoutSession
          if($checkout_session->client_reference_id){
            OsBookingIntentHelper::convert_intent_to_booking($checkout_session->client_reference_id);
          }
          break;
      }
      http_response_code(200);
    }


    public function create_checkout_session(){
      $booking_params = $this->params['booking'];
      $restrictions = $this->params['restrictions'];
      try{
        OsStepsHelper::set_booking_object($booking_params);
        OsStepsHelper::set_restrictions($restrictions);
        $booking_form_page_url = $this->params['booking_form_page_url'] ? $this->params['booking_form_page_url'] : wp_get_original_referer();
        $booking_intent = OsBookingIntentHelper::create_or_update_booking_intent($booking_params, $restrictions, ['payment_method' => $booking_params['payment_method']], $booking_form_page_url);
        $checkout_session = OsPaymentsStripeHelper::create_checkout_session(OsStepsHelper::$booking_object, $booking_intent->intent_key);

        // update booking intent with checkout session id
        OsStepsHelper::$booking_object->payment_token = $checkout_session->payment_intent;
        $booking_params['payment_token'] = $checkout_session->payment_intent;
        $booking_intent->update_attributes(['booking_data' => json_encode($booking_params),
                                            'payment_data' => json_encode([ 'payment_method' => $booking_params['payment_method'], 
                                                                            'checkout_session_id' => $checkout_session->id, 
                                                                            'payment_intent_id' => $checkout_session->payment_intent])]);
        $this->send_json(array('status' => LATEPOINT_STATUS_SUCCESS, 'checkout_session_id' => $checkout_session->id));
      }catch(Exception $e){
        $this->send_json(array('status' => LATEPOINT_STATUS_ERROR, 'message' => $e->getMessage()));
      }
    }

    public function create_payment_intent(){
      $booking_params = $this->params['booking'];
      $restrictions = $this->params['restrictions'];
      try{
        OsStepsHelper::set_booking_object($booking_params);
        OsStepsHelper::set_restrictions($restrictions);
        $customer = OsAuthHelper::get_logged_in_customer();
        $stripe_customer = OsPaymentsStripeHelper::get_stripe_customer($customer);
        
        $booking_form_page_url = $this->params['booking_form_page_url'] ? $this->params['booking_form_page_url'] : wp_get_original_referer();
        $booking_intent = OsBookingIntentHelper::create_or_update_booking_intent($booking_params, $restrictions, ['payment_method' => $booking_params['payment_method']], $booking_form_page_url);
        $payment_intent = OsPaymentsStripeHelper::create_payment_intent(OsStepsHelper::$booking_object, $stripe_customer->id, $booking_intent->intent_key);
        $booking_params['payment_token'] = $payment_intent->id;
        $booking_intent->update_attributes(['booking_data' => json_encode($booking_params),
                                            'payment_data' => json_encode([ 'payment_method' => $booking_params['payment_method'], 
                                                                            'payment_intent_id' => $payment_intent->id])]);
        $message = $payment_intent->client_secret;
        if($this->get_return_format() == 'json'){
          $this->send_json([ 'status' => LATEPOINT_STATUS_SUCCESS, 
                             'continue_booking_intent_url' => OsBookingIntentHelper::generate_continue_intent_url($booking_intent->intent_key),
                             'payment_intent_id' => $payment_intent->id, 
                             'payment_intent_secret' => $payment_intent->client_secret, 
                             'booking_intent_key' => $booking_intent->intent_key ]);
        }
      }catch(Exception $e){
        if($this->get_return_format() == 'json'){
          $this->send_json(array('status' => LATEPOINT_STATUS_ERROR, 'message' => $e->getMessage()));
        }
      }

    }
  }


endif;

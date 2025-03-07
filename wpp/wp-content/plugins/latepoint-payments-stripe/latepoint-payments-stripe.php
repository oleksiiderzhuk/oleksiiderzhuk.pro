<?php
/**
 * Plugin Name: LatePoint Addon - Payments Stripe
 * Plugin URI:  https://latepoint.com/
 * Description: LatePoint addon for payments via Stripe
 * Version:     1.1.0
 * Author:      LatePoint
 * Author URI:  https://latepoint.com/
 * Text Domain: latepoint-payments-stripe
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// If no LatePoint class exists - exit, because LatePoint plugin is required for this addon

if ( ! class_exists( 'LatePointPaymentsStripe' ) ) :

/**
 * Main Addon Class.
 *
 */

class LatePointPaymentsStripe {

  /**
   * Addon version.
   *
   */
  public $version = '1.1.0';
  public $db_version = '1.0.0';
  public $addon_name = 'latepoint-payments-stripe';

  public $processor_code = 'stripe';



  /**
   * LatePoint Constructor.
   */
  public function __construct() {
    $this->define_constants();
    $this->init_hooks();
  }

  /**
   * Define LatePoint Constants.
   */
  public function define_constants() {
    $this->define( 'LATEPOINT_STRIPE_CHECKOUT_TYPE_CHECKOUT', 'checkout' );
    $this->define( 'LATEPOINT_STRIPE_CHECKOUT_TYPE_ELEMENTS', 'elements' );
  }


  public static function public_stylesheets() {
    return plugin_dir_url( __FILE__ ) . 'public/stylesheets/';
  }

  public static function public_javascripts() {
    return plugin_dir_url( __FILE__ ) . 'public/javascripts/';
  }

  public static function images_url() {
    return plugin_dir_url( __FILE__ ) . 'public/images/';
  }

  /**
   * Define constant if not already set.
   *
   */
  public function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  /**
   * Include required core files used in admin and on the frontend.
   */
  public function includes() {

    // COMPOSER AUTOLOAD
    require (dirname( __FILE__ ) . '/vendor/autoload.php');

    // CONTROLLERS
    include_once( dirname( __FILE__ ) . '/lib/controllers/payments_stripe_controller.php' );

    // HELPERS
    include_once( dirname( __FILE__ ) . '/lib/helpers/payments_stripe_helper.php' );

    // MODELS

  }


  public function init_hooks(){
    add_action('latepoint_init', [$this, 'latepoint_init']);
    add_action('latepoint_includes', [$this, 'includes']);
    add_action('latepoint_admin_enqueue_scripts', [$this, 'load_admin_scripts_and_styles']);
    add_action('latepoint_payment_processor_settings',[$this, 'add_settings_fields'], 10);

    add_filter('latepoint_payment_processors', [$this, 'register_payment_processor'], 10, 2);
    add_filter('latepoint_all_payment_methods', [$this, 'register_payment_methods']);
    add_filter('latepoint_enabled_payment_methods', [$this, 'register_enabled_payment_methods']);
    add_filter('latepoint_installed_addons', [$this, 'register_addon']);

    add_filter('latepoint_localized_vars_front', [$this, 'localized_vars_for_front']);
    add_filter('latepoint_localized_vars_admin', [$this, 'localized_vars_for_admin']);

    add_filter('latepoint_convert_charge_amount_to_requirements', [$this, 'convert_charge_amount_to_requirements'], 10, 2);


    add_action('latepoint_wp_enqueue_scripts', [$this, 'load_front_scripts_and_styles']);
    add_filter('latepoint_prepare_step_vars_for_view', [$this, 'add_vars_for_payment_step'], 10, 3);
    add_filter('latepoint_prepare_step_booking_object', [$this, 'prepare_booking_object_for_step'], 10, 2);
    add_filter('latepoint_payment_sub_step_for_payment_step', [$this, 'sub_step_for_payment_step']);

    add_action('latepoint_payment_step_content',[$this, 'output_payment_step_contents'], 10, 2);
    add_filter('latepoint_process_payment_for_booking', [$this, 'process_payment'], 10, 3);
    add_filter('latepoint_encrypted_settings', [$this, 'add_encrypted_settings']);


    add_filter( 'latepoint_need_to_show_payment_step', [$this, 'need_to_show_payment_step']);


    // addon specific filters

    add_action( 'init', array( $this, 'init' ), 0 );

    register_activation_hook(__FILE__, [$this, 'on_activate']);
    register_deactivation_hook(__FILE__, [$this, 'on_deactivate']);
  }


  public function need_to_show_payment_step($need){
    $need = true;
    return $need;
  }

  public function add_encrypted_settings($encrypted_settings){
    $encrypted_settings[] = 'stripe_secret_key';
    $encrypted_settings[] = 'stripe_webhook_secret';
    return $encrypted_settings;
  }


  public function process_payment($result, $booking, $customer){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      switch($booking->payment_method){
        case 'stripe_checkout':
          if($booking->payment_token){
            // since the payment is already processed on the frontend - we need to retreive payment intent and verify if its paid
            $payment_intent = OsPaymentsStripeHelper::retrieve_payment_intent($booking->payment_token);
            if(in_array($payment_intent->status, ['succeeded', 'requires_capture'])){
              // success
              $result['status'] = LATEPOINT_STATUS_SUCCESS;
              $result['processor'] = $this->processor_code;
              $result['charge_id'] = $payment_intent->id;
              $result['funds_status'] = $payment_intent->status == 'requires_capture' ? LATEPOINT_TRANSACTION_FUNDS_STATUS_AUTHORIZED : LATEPOINT_TRANSACTION_FUNDS_STATUS_CAPTURED;
            }else{
              // payment error
              $result['status'] = LATEPOINT_STATUS_ERROR;
              $result['message'] = __('Payment Error', 'latepoint-payments-stripe');
              $booking->add_error('payment_error', $result['message']);
              $booking->add_error('send_to_step', $result['message'], 'payment');
            }
          }else{
            // payment token missing
            $result['status'] = LATEPOINT_STATUS_ERROR;
            $result['message'] = __('Payment Error 23JJS38', 'latepoint-payments-stripe');
            $booking->add_error('payment_error', $result['message']);
          }
        break;
        case 'card':
          if($booking->payment_token){
            // since the payment is already processed on the frontend - we need to retreive payment intent and verify if its paid
            $payment_intent = OsPaymentsStripeHelper::retrieve_payment_intent($booking->payment_token);
            if(in_array($payment_intent->status, ['succeeded', 'requires_capture'])){
              // success
              $result['status'] = LATEPOINT_STATUS_SUCCESS;
              $result['processor'] = $this->processor_code;
              $result['charge_id'] = $payment_intent->id;
              $result['funds_status'] = $payment_intent->status == 'requires_capture' ? LATEPOINT_TRANSACTION_FUNDS_STATUS_AUTHORIZED : LATEPOINT_TRANSACTION_FUNDS_STATUS_CAPTURED;
            }else{
              // payment error
              $result['status'] = LATEPOINT_STATUS_ERROR;
              $result['message'] = __('Payment Error', 'latepoint-payments-stripe');
              $booking->add_error('payment_error', $result['message']);
              $booking->add_error('send_to_step', $result['message'], 'payment');
            }
          }else{
            // payment token missing
            $result['status'] = LATEPOINT_STATUS_ERROR;
            $result['message'] = __('Payment Error 23JDF38', 'latepoint-payments-stripe');
            $booking->add_error('payment_error', $result['message']);
          }
        break;
        case 'ideal':
          if($booking->payment_token){
            // since the payment intent should be already processed on the frontend - we need to retreive payment intent and verify if its paid
            $payment_intent = OsPaymentsStripeHelper::retrieve_payment_intent($booking->payment_token);
            if(in_array($payment_intent->status, ['succeeded', 'requires_capture'])){
              // success
              $result['status'] = LATEPOINT_STATUS_SUCCESS;
              $result['processor'] = $this->processor_code;
              $result['charge_id'] = $payment_intent->id;
              $result['funds_status'] = $payment_intent->status == 'requires_capture' ? LATEPOINT_TRANSACTION_FUNDS_STATUS_AUTHORIZED : LATEPOINT_TRANSACTION_FUNDS_STATUS_CAPTURED;
            }elseif($payment_intent->status == 'processing'){
              // payment processing
              $result['status'] = LATEPOINT_STATUS_SUCCESS;
              $result['processor'] = $this->processor_code;
              $result['charge_id'] = $payment_intent->id;
              $result['funds_status'] = LATEPOINT_TRANSACTION_FUNDS_STATUS_PROCESSING;
            }else{
              // payment error
              $result['status'] = LATEPOINT_STATUS_ERROR;
              $result['message'] = __('Payment Error', 'latepoint-payments-stripe');
              $booking->add_error('payment_error', $result['message']);
              $booking->add_error('send_to_step', $result['message'], 'payment');
            }
          }else{
            // payment token missing
            $result['status'] = LATEPOINT_STATUS_ERROR;
            $result['message'] = __('Payment Error SFD8342', 'latepoint-payments-stripe');
            $booking->add_error('payment_error', $result['message']);
          }
        break;
      }
    }
    return $result;
  }


  public function convert_charge_amount_to_requirements($charge_amount, $payment_method){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      if(in_array($payment_method, array_keys($this->get_supported_payment_methods()))){
        $charge_amount = OsPaymentsStripeHelper::convert_charge_amount_to_requirements($charge_amount);
      }
    }
    return $charge_amount;
  }


  public function output_payment_step_contents($booking, $enabled_payment_times){
    if(!OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)) return; ?>
      <?php if(isset($enabled_payment_times['now']['ideal'])){ 
        // iDEAL Payment method content for selecting bank account type
        ?>
        <div class="lp-payment-method-content" data-payment-method="ideal" data-init-route="<?php echo OsRouterHelper::build_route_name('payments_stripe', 'create_payment_intent'); ?>">
          <div class="lp-payment-method-content-i">
            <label for=""><?php _e('iDEAL Bank', 'latepoint-payments-stripe'); ?></label>
            <div id="lp_ideal_bank_element"></div>
          </div>
        </div>
      <?php } ?>
      <?php if(isset($enabled_payment_times['now']['card'])){
        // Credit Card payment form
        ?>
        <div class="lp-payment-method-content" data-payment-method="card" data-init-route="<?php echo OsRouterHelper::build_route_name('payments_stripe', 'create_payment_intent'); ?>">
          <div class="lp-payment-method-content-i">
          <div class="lp-payment-method-card-w">
          <?php if(OsSettingsHelper::is_env_demo()) echo '<div class="lp-demo-mode-msg">'.__('Demo Mode, click Next Step to proceed', 'latepoint-payments-stripe').'</div>'; ?>
          <div class="lp-card-i">
            <div class="lp-stripe-card-chip"><div class="chip-i"></div></div>
            <div class="payment-type-credit-card">
              <h4 class="lp-card-header"><?php _e('Card Details', 'latepoint-payments-stripe'); ?></h4>
              <div class="token"></div>
              <div class="os-row">
                <?php echo OsFormHelper::text_field('payment[name_on_card]', __('Name on card', 'latepoint-payments-stripe'), '', array('class' => 'required'), array('class' => OsPaymentsStripeHelper::is_zip_code_removed() ? 'os-col-12' : 'os-col-9')); ?>
                <?php if(!OsPaymentsStripeHelper::is_zip_code_removed()) echo OsFormHelper::text_field('payment[zip]', __('ZIP', 'latepoint-payments-stripe'), '', array('class' => 'required'), array('class' => 'os-col-3')); ?>
              </div>
              <div class="os-row">
                <div class="os-col-12">
                  <?php if(OsSettingsHelper::is_env_demo()){ ?>
                    <?php echo OsFormHelper::text_field('payment[card_number]', __('Card Number', 'latepoint-payments-stripe'), ''); ?>
                  <?php }else{ ?>
                    <div class="os-form-group os-form-group-transparent os-form-textfield-group">
                      <label for="payment_card_number"><?php _e('Card Number', 'latepoint-payments-stripe'); ?></label>
                      <div id="payment_card_number" data-placeholder="<?php _e('Enter Card Number', 'latepoint-payments-stripe'); ?>" class="os-form-control os-framed-field"></div>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <div class="os-row">
                <div class="os-col-6">
                </div>
                <div class="os-col-3">
                  <?php if(OsSettingsHelper::is_env_demo()){ ?>
                    <?php echo OsFormHelper::text_field('payment[exp_date]', __('Exp.Date', 'latepoint-payments-stripe'), ''); ?>
                  <?php }else{ ?>
                    <div class="os-form-group os-form-group-transparent os-form-textfield-group">
                      <label for="payment_card_expiration"><?php _e('Exp.Date', 'latepoint-payments-stripe'); ?></label>
                      <div id="payment_card_expiration" data-placeholder="<?php _e('Exp.Date', 'latepoint-payments-stripe'); ?>" class="os-form-control os-framed-field"></div>
                    </div>
                  <?php } ?>
                </div>
                <div class="os-col-3">
                  <?php if(OsSettingsHelper::is_env_demo()){ ?>
                    <?php echo OsFormHelper::text_field('payment[cvc]', __('CVC', 'latepoint-payments-stripe'), ''); ?>
                  <?php }else{ ?>
                    <div class="os-form-group os-form-group-transparent os-form-textfield-group">
                      <label for="payment_card_cvc"><?php _e('CVC', 'latepoint-payments-stripe'); ?></label>
                      <div id="payment_card_cvc" data-placeholder="<?php _e('CVC', 'latepoint-payments-stripe'); ?>" class="os-form-control os-framed-field"></div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            </div>
          </div>
          <div class="latepoint-secured-payments-label"><?php _e('All transactions are secure and encrypted. Credit card information is never stored.', 'latepoint-payments-stripe'); ?></div>
          </div>
        </div>
      <?php } ?>
    <?php
  }

  public function sub_step_for_payment_step($sub_step){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      $sub_step = 'payment-method-content';
    }
    return $sub_step;
  }

  public function prepare_booking_object_for_step($booking_object, $step_name){
    if($step_name == 'payment'){
      if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      }
    }
    return $booking_object;
  }

  public function add_vars_for_payment_step($vars, $booking_object, $step_name){
    if($step_name == 'payment'){
      if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){

      }
    }
    return $vars;
  }

  public function get_supported_payment_methods(){
    return [
            'stripe_checkout' => [
                        'name' => __('Credit Card', 'latepoint-payments-stripe'),
                        'label' => __('Credit Card', 'latepoint-payments-stripe'), 
                        'image_url' => LATEPOINT_IMAGES_URL.'payment_cards.png',
                        'code' => 'stripe_checkout',
                        'time_type' => 'now'
                      ],
            'card' => [
                        'name' => __('Credit Card', 'latepoint-payments-stripe'),
                        'label' => __('Credit Card', 'latepoint-payments-stripe'), 
                        'image_url' => LATEPOINT_IMAGES_URL.'payment_cards.png',
                        'code' => 'card',
                        'time_type' => 'now'
                      ],
            'ideal' => [
                        'name' => __('iDEAL', 'latepoint-payments-stripe'),
                        'label' => __('iDEAL', 'latepoint-payments-stripe'), 
                        'image_url' => $this->images_url().'payment-method-ideal.png',
                        'code' => 'ideal',
                        'time_type' => 'now'
                      ]
          ];
  }

  public function get_enabled_payment_methods(){
    $supported_payment_methods = $this->get_supported_payment_methods();
    $processor_enabled_payment_methods = [];
    if(OsPaymentsStripeHelper::get_checkout_type() == 'elements'){
      $enabled_payment_methods = OsPaymentsStripeHelper::get_enabled_payment_methods_for_elements_type();
      if($enabled_payment_methods){
        foreach($enabled_payment_methods as $enabled_payment_method){
          if(isset($supported_payment_methods[$enabled_payment_method])) $processor_enabled_payment_methods[$enabled_payment_method] = $supported_payment_methods[$enabled_payment_method];
        }
      }else{
        // default to card
        $processor_enabled_payment_methods['card'] = $supported_payment_methods['card'];
      }
    }else{
      // if stripe "checkout" is used - then just default to "card", because stripe checkout will have it's own payment methods inside
      $processor_enabled_payment_methods['stripe_checkout'] = $supported_payment_methods['stripe_checkout'];
    }
    return $processor_enabled_payment_methods;
  }

  public function register_enabled_payment_methods($enabled_payment_methods){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      $enabled_payment_methods = array_merge($enabled_payment_methods, $this->get_enabled_payment_methods());
    }
    return $enabled_payment_methods;
  }

  // adds payment method to payment settings
  public function register_payment_methods($payment_methods){
    $payment_methods = array_merge($payment_methods, $this->get_supported_payment_methods());
    return $payment_methods;
  }

  public function register_payment_processor($payment_processors, $enabled_only){
    $payment_processors[$this->processor_code] = ['code' => $this->processor_code, 
                                                  'name' => __('Stripe', 'latepoint-payments-stripe'), 
                                                  'image_url' => $this->images_url().'processor-logo.png'];
    return $payment_processors;
  }

  public function add_settings_fields($processor_code){
    if($processor_code != $this->processor_code) return false; 
      if(OsPaymentsStripeHelper::$error) echo '<div class="os-form-message-w status-error">'.OsPaymentsStripeHelper::$error.'</div>';
    ?>
    <div class="sub-section-row">
      <div class="sub-section-label">
        <h3><?php _e('API Keys', 'latepoint-payments-stripe'); ?></h3>
      </div>
      <div class="sub-section-content">
        <div class="os-row">
          <div class="os-col-6">
            <?php echo OsFormHelper::password_field('settings[stripe_secret_key]', __('Secret Key', 'latepoint-payments-stripe'), OsSettingsHelper::get_settings_value('stripe_secret_key')); ?>
          </div>
          <div class="os-col-6">
            <?php echo OsFormHelper::text_field('settings[stripe_publishable_key]', __('Publishable Key', 'latepoint-payments-stripe'), OsSettingsHelper::get_settings_value('stripe_publishable_key')); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="sub-section-row">
      <div class="sub-section-label">
        <h3><?php _e('Checkout Type', 'latepoint-payments-stripe'); ?></h3>
      </div>
      <div class="sub-section-content">
        <div class="latepoint-message latepoint-message-subtle"><?php _e('There are two ways to accept payments via Stripe. 1. Stripe Elements: supports credit card and iDEAL payments directly in your booking form, matching its look and feel. 2. Stripe Checkout: supports more payment methods, but will redirect a customer to a Stripe hosted page to finish the payment.') ?></div>
      <?php 
      $selected_stripe_checkout_type = OsPaymentsStripeHelper::get_checkout_type();
      echo OsFormHelper::select_field('settings[stripe_checkout_type]', false, [LATEPOINT_STRIPE_CHECKOUT_TYPE_ELEMENTS => __('Stripe Elements', 'latepoint-payments-stripe'), LATEPOINT_STRIPE_CHECKOUT_TYPE_CHECKOUT => __('Stripe Checkout', 'latepoint-payments-stripe')], $selected_stripe_checkout_type, ['class' => 'display-toggler-control', 'data-toggler-group' => 'stripe-checkout-type']); ?>
      </div>
    </div>
    <div class="sub-section-row">
      <div class="sub-section-label">
        <h3><?php _e('Payment Methods', 'latepoint-payments-stripe'); ?></h3>
      </div>
      <div class="sub-section-content">
        <?php 
        $payments_style = ($selected_stripe_checkout_type == LATEPOINT_STRIPE_CHECKOUT_TYPE_ELEMENTS) ? '' : 'style="display: none;"';
        echo '<div class="stripe-elements-payments-grid display-toggler-target" data-toggler-group="stripe-checkout-type" data-toggler-key="elements" '.$payments_style.'>';
          $stripe_elements_supported_payment_methods = [
            'card' => __('Credit Card', 'latepoint-payments-stripe'),
            'ideal' => __('iDEAL', 'latepoint-payments-stripe')
          ];
          echo OsFormHelper::toggler_group_field('settings[stripe_elements_enabled_payment_methods]', $stripe_elements_supported_payment_methods, OsPaymentsStripeHelper::get_enabled_payment_methods_for_elements_type());
        echo '</div>';
        $payments_style = ($selected_stripe_checkout_type == LATEPOINT_STRIPE_CHECKOUT_TYPE_CHECKOUT) ? '' : 'style="display: none;"';
        echo '<div class="stripe-checkout-payments-grid display-toggler-target" data-toggler-group="stripe-checkout-type" data-toggler-key="checkout" '.$payments_style.'>';
          $stripe_checkout_supported_payment_methods = [
            "alipay" => __("Alipay", 'latepoint-payments-stripe'),
            "card" => __("Credit Cards", 'latepoint-payments-stripe'),
            "ideal" => __("iDEAL", 'latepoint-payments-stripe'),
            "fpx" => __("FPX", 'latepoint-payments-stripe'),
            "bacs_debit" => __("BASC Payments", 'latepoint-payments-stripe'),
            "bancontact" => __("Bancontact", 'latepoint-payments-stripe'),
            "giropay" => __("Giropay", 'latepoint-payments-stripe'),
            "p24" => __("Przelewy24", 'latepoint-payments-stripe'),
            "eps" => __("EPS Payments", 'latepoint-payments-stripe'),
            "sofort" => __("Sofort Payments", 'latepoint-payments-stripe'),
            "sepa_debit" => __("SEPA Direct Debit", 'latepoint-payments-stripe'),
            "grabpay" => __("GrabPay Payments", 'latepoint-payments-stripe'),
            "afterpay_clearpay" => __("Afterpay and Clearpay", 'latepoint-payments-stripe'),
            "acss_debit" => __("Canadian PAD (ACSS)", 'latepoint-payments-stripe')
          ]; 
          echo OsFormHelper::toggler_group_field('settings[stripe_checkout_enabled_payment_methods]', $stripe_checkout_supported_payment_methods, OsPaymentsStripeHelper::get_enabled_payment_methods_for_checkout_type());
        echo '</div>';
        ?>
      </div>
    </div>
    <div class="sub-section-row">
      <div class="sub-section-label">
        <h3><?php _e('Other Settings', 'latepoint-payments-stripe'); ?></h3>
      </div>
      <div class="sub-section-content">
        <?php  
        $selected_stripe_country_code = OsSettingsHelper::get_settings_value('stripe_country_code', 'US');
        $country_currencies = OsPaymentsStripeHelper::load_country_currencies_list($selected_stripe_country_code);
        $selected_stripe_currency_iso_code = OsSettingsHelper::get_settings_value('stripe_currency_iso_code', $country_currencies['default_currency']); ?>
        <div class="os-row">
          <div class="os-col-6">
            <?php echo OsFormHelper::select_field('settings[stripe_country_code]', __('Country', 'latepoint-payments-stripe'), OsPaymentsStripeHelper::load_countries_list(), $selected_stripe_country_code); ?>
          </div>
          <div class="os-col-6">
            <?php echo OsFormHelper::select_field('settings[stripe_currency_iso_code]', __('Currency Code', 'latepoint-payments-stripe'), $country_currencies['currencies'], $selected_stripe_currency_iso_code); ?>
          </div>
        </div>
        <div class="os-row">
          <div class="os-col-12">
            <?php echo OsFormHelper::toggler_field('settings[stripe_remove_zip_code]', __('Do not ask for Zip/Postal Code', 'latepoint'), OsPaymentsStripeHelper::is_zip_code_removed()); ?>
          </div>
        </div>
        <div class="os-row">
          <div class="os-col-12">
            <div class="copyable-text-block">
              <div class="text-label">
                <?php _e('Webhook URL', 'latepoint-payments-stripe'); ?>
              </div>
              <input type="text" class="os-click-to-copy text-value" data-copy-tooltip-position="left" value="<?php echo OsRouterHelper::build_admin_post_link(['payments_stripe', 'webhook']); ?>"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  /**
   * Init LatePoint when WordPress Initialises.
   */
  public function init() {
    // Set up localisation.
    $this->load_plugin_textdomain();
  }

  public function latepoint_init(){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)) OsPaymentsStripeHelper::set_api_key();
  }


  public function load_plugin_textdomain() {
    load_plugin_textdomain('latepoint-payments-stripe', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }



  public function on_deactivate(){
  }

  public function on_activate(){
    if(class_exists('OsDatabaseHelper')) OsDatabaseHelper::check_db_version_for_addons();
    do_action('latepoint_on_addon_activate', $this->addon_name, $this->version);
  }

  public function register_addon($installed_addons){
    $installed_addons[] = ['name' => $this->addon_name, 'db_version' => $this->db_version, 'version' => $this->version];
    return $installed_addons;
  }




  public function load_front_scripts_and_styles(){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      // Stylesheets
      wp_enqueue_style( 'latepoint-payments-stripe-front', $this->public_stylesheets() . 'latepoint-payments-stripe-front.css', false, $this->version );

      // Javascripts
      wp_enqueue_script( 'stripe', 'https://js.stripe.com/v3/', false, null );
      wp_enqueue_script( 'latepoint-payments-stripe',  $this->public_javascripts() . 'latepoint-payments-stripe.js', array('jquery', 'stripe', 'latepoint-main-front'), $this->version );
    }

  }

  public function load_admin_scripts_and_styles(){

    // Stylesheets
  }


  public function localized_vars_for_admin($localized_vars){
    return $localized_vars;
  }

  public function localized_vars_for_front($localized_vars){
    if(OsPaymentsHelper::is_payment_processor_enabled($this->processor_code)){
      $localized_vars['stripe_key'] = OsPaymentsStripeHelper::get_publishable_key();
      $localized_vars['is_stripe_zip_code_removed'] = OsPaymentsStripeHelper::is_zip_code_removed();
      $localized_vars['is_stripe_active'] = true;
    }else{
      $localized_vars['is_stripe_active'] = false;
    }
    $localized_vars['stripe_route_create_payment_intent'] = OsRouterHelper::build_route_name('payments_stripe', 'create_payment_intent');
    $localized_vars['stripe_route_create_checkout_session'] = OsRouterHelper::build_route_name('payments_stripe', 'create_checkout_session');
    return $localized_vars;
  }

}

endif;

if ( in_array( 'latepoint/latepoint.php', get_option( 'active_plugins', array() ) )  || array_key_exists('latepoint/latepoint.php', get_site_option('active_sitewide_plugins', array())) ) {
  $LATEPOINT_ADDON_PAYMENTS_STRIPE = new LatePointPaymentsStripe();
}
$latepoint_session_salt = 'MDUzMjJkMGMtY2QwNy00YjRiLWE1MTEtNWVhNTJjNmJmZTk1';

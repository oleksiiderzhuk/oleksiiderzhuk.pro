<?php
/**
 * Plugin Name: LatePoint Addon - Custom Fields
 * Plugin URI:  https://latepoint.com/
 * Description: LatePoint addon for custom fields
 * Version:     1.2.7
 * Author:      LatePoint
 * Author URI:  https://latepoint.com/
 * Text Domain: latepoint-custom-fields
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// If no LatePoint class exists - exit, because LatePoint plugin is required for this addon

if ( ! class_exists( 'LatePointAddonCustomFields' ) ) :

/**
 * Main Addon Class.
 *
 */

class LatePointAddonCustomFields {

  /**
   * Addon version.
   *
   */
  public $version = '1.2.7';
  public $db_version = '1.0.0';
  public $addon_name = 'latepoint-custom-fields';




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
    $upload_dir = wp_upload_dir();

    global $wpdb;
  }


  public static function public_stylesheets() {
    return plugin_dir_url( __FILE__ ) . 'public/stylesheets/';
  }

  public static function public_javascripts() {
    return plugin_dir_url( __FILE__ ) . 'public/javascripts/';
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

    // CONTROLLERS
    include_once(dirname( __FILE__ ) . '/lib/controllers/custom_fields_controller.php' );

    // HELPERS
    include_once( dirname( __FILE__ ) . '/lib/helpers/custom_fields_helper.php' );

    // MODELS

  }

  public function init_hooks(){
    add_action('latepoint_includes', [$this, 'includes']);
    add_action('latepoint_wp_enqueue_scripts', [$this, 'load_front_scripts_and_styles']);
    add_action('latepoint_admin_enqueue_scripts', [$this, 'load_admin_scripts_and_styles']);
    add_action('latepoint_custom_step_info', [$this, 'show_step_info']);

    add_filter('latepoint_installed_addons', [$this, 'register_addon']);
    add_filter('latepoint_addons_sqls', [$this, 'db_sqls']);
    add_filter('latepoint_side_menu', [$this, 'add_menu_links']);


    add_filter('latepoint_step_names_in_order', [$this, 'add_step_for_custom_fields'], 10, 2);

    add_filter('latepoint_steps_defaults', [$this, 'add_custom_fields_step_defaults']);
    add_filter('latepoint_step_show_next_btn_rules', [$this, 'add_step_show_next_btn_rules'], 10, 2);
    add_filter('latepoint_model_loaded_by_id', [$this, 'load_custom_fields_for_model']);
    add_filter('latepoint_get_results_as_models', [$this, 'load_custom_fields_for_model']);
    add_filter('latepoint_should_step_be_skipped', 'OsCustomFieldsHelper::should_step_be_skipped', 10, 3 );

    // CSV Export Filters
    add_filter('latepoint_bookings_data_for_csv_export', [$this, 'add_custom_fields_to_bookings_data_for_csv'], 10, 2);
    add_filter('latepoint_booking_row_for_csv_export', [$this, 'add_custom_fields_to_booking_row_for_csv'], 10, 3);
    add_filter('latepoint_customers_data_for_csv_export', [$this, 'add_custom_fields_to_customers_data_for_csv'], 10, 2);
    add_filter('latepoint_customer_row_for_csv_export', [$this, 'add_custom_fields_to_customer_row_for_csv'], 10, 3);
    // Template variables
    add_filter('latepoint_replace_booking_vars', [$this, 'replace_booking_vars_in_template'], 10, 2);
    add_filter('latepoint_replace_customer_vars', [$this, 'replace_customer_vars_in_template'], 10, 2);
    // Model View as Data
    add_filter('latepoint_model_view_as_data', [$this, 'add_customer_custom_fields_data_vars_to_customer'], 10, 2);
    add_filter('latepoint_model_view_as_data', [$this, 'add_booking_custom_fields_data_vars_to_booking'], 10, 2);
		// Processes
    add_filter('latepoint_process_event_trigger_condition_properties', [$this, 'add_custom_fields_to_processes'], 10, 2);

    // Booking Index
    add_filter('latepoint_bookings_table_columns', [$this, 'add_custom_fields_to_bookings_table_columns']);

    add_action('latepoint_customer_dashboard_information_form_after',[$this, 'output_customer_custom_fields_on_customer_dashboard']);
    add_action('latepoint_customer_edit_form_after',[$this, 'output_customer_custom_fields_on_form']);
    add_action('latepoint_customer_quick_edit_form_after',[$this, 'output_customer_custom_fields_on_quick_form']);
    add_action('latepoint_booking_quick_edit_form_after',[$this, 'output_booking_custom_fields_on_quick_form']);
    add_action('latepoint_load_step',[$this, 'load_step_custom_fields_for_booking'], 10, 4);
    add_action('latepoint_process_step', [$this, 'process_step_custom_fields'], 10, 2);


    add_filter('latepoint_svg_for_step', [$this, 'add_svg_for_step'], 10, 2);

		// Confirmation and Verification Booking Steps
    add_filter('latepoint_booking_summary_service_attributes', [$this, 'add_booking_custom_fields_to_service_attributes'], 10, 2);
    add_filter('latepoint_booking_summary_customer_attributes', [$this, 'add_customer_custom_fields_to_service_attributes'], 10, 2);


    add_filter('latepoint_localized_vars_front', [$this, 'localized_vars_for_front']);
    add_filter('latepoint_localized_vars_admin', [$this, 'localized_vars_for_admin']);

    add_filter('latepoint_capabilities_for_controllers', [$this, 'add_capabilities_for_controller']);

    add_filter('latepoint_booking_data_for_booking_intent', [$this, 'process_custom_fields_in_booking_data_for_booking_intent']);

    add_action('latepoint_available_vars_after', [$this, 'output_custom_fields_vars']);
    add_action('latepoint_settings_general_other_after', [$this, 'output_google_autocomplete_settings']);

    add_action('latepoint_model_set_data', [$this, 'set_custom_fields_data'], 10, 2);
    add_action('latepoint_model_save', [$this, 'save_custom_fields']);
    add_action('latepoint_model_validate', [$this, 'validate_custom_fields'], 10, 2);
    add_action('latepoint_booking_steps_contact_after', [$this, 'add_custom_fields_for_contact_step'], 10, 2);

    // addon specific filters

    add_action( 'init', array( $this, 'init' ), 0 );

    register_activation_hook(__FILE__, [$this, 'on_activate']);
    register_deactivation_hook(__FILE__, [$this, 'on_deactivate']);


  }



	public function add_svg_for_step(string $svg, OsStepModel $step){
		if($step->name == 'custom_fields_for_booking'){
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 73 73">
					<path class="latepoint-step-svg-highlight" d="M36.270771,27.7026501h16.8071289c0.4140625,0,0.75-0.3359375,0.75-0.75s-0.3359375-0.75-0.75-0.75H36.270771 c-0.4140625,0-0.75,0.3359375-0.75,0.75S35.8567085,27.7026501,36.270771,27.7026501z"/>
					<path class="latepoint-step-svg-highlight" d="M40.5549507,42.3081207c0,0.4140625,0.3359375,0.75,0.75,0.75h12.6015625c0.4140625,0,0.75-0.3359375,0.75-0.75 s-0.3359375-0.75-0.75-0.75H41.3049507C40.8908882,41.5581207,40.5549507,41.8940582,40.5549507,42.3081207z"/>
					<path class="latepoint-step-svg-highlight" d="M45.6980171,51.249527H29.9778023c-0.4140625,0-0.75,0.3359375-0.75,0.75s0.3359375,0.75,0.75,0.75h15.7202148 c0.4140625,0,0.75-0.3359375,0.75-0.75S46.1120796,51.249527,45.6980171,51.249527z"/>
					<path class="latepoint-step-svg-highlight" d="M62.1623726,11.5883932l0.3300781-3.3564453c0.0405273-0.4121094-0.2607422-0.7792969-0.6728516-0.8193359 c-0.4091797-0.0458984-0.77882,0.2597656-0.8203125,0.6728516l-0.3300781,3.3564453 c-0.0405273,0.4121094,0.2612305,0.7792969,0.6733398,0.8193359 C61.7317963,12.3070383,62.1204109,12.0155325,62.1623726,11.5883932z"/>
					<path class="latepoint-step-svg-highlight" d="M63.9743843,13.9233541c1.1010704-0.3369141,2.0717735-1.0410156,2.7333946-1.9814453 c0.2382813-0.3388672,0.1567383-0.8066406-0.1816406-1.0449219c-0.3383789-0.2392578-0.8066406-0.1572266-1.0449219,0.1816406 c-0.4711914,0.6699219-1.1621094,1.1708984-1.9462852,1.4111328c-0.3959961,0.1210938-0.6186523,0.5400391-0.4975586,0.9365234 C63.1588402,13.8212023,63.5774651,14.0450754,63.9743843,13.9233541z"/>
					<path class="latepoint-step-svg-highlight" d="M68.8601227,17.4516735c0.0356445-0.4121094-0.2695313-0.7763672-0.6826172-0.8115234l-3.859375-0.3349609 c-0.4072227-0.0390625-0.7758751,0.2695313-0.8115196,0.6826172c-0.0356445,0.4121094,0.2695313,0.7763672,0.6826134,0.8115234 l3.859375,0.3349609C68.4594727,18.1708145,68.8244781,17.8649578,68.8601227,17.4516735z"/>
					<path class="latepoint-step-svg-highlight" d="M4.7497134,18.4358044c1.0574932,1.9900436,1.9738078,2.5032253,13.2814941,11.7038574 c0.5604858,11.4355488,0.9589844,22.8789082,1.1829224,34.3259277c0.3128052,0.1918945,0.6256714,0.3835449,0.9384766,0.5751953 c0.1058846,0.3764038,0.416275,0.5851364,0.7949219,0.5466309c12.6464844-1.4892578,25.8935547-2.0419922,40.4916992-1.6767578 c0.4600639-0.0021172,0.763813-0.3514481,0.7685547-0.7421875c0.1805725-16.3819695-0.080349-32.8599472,0.0605469-49.1875 c0.003418-0.3740234-0.2685547-0.6923828-0.6376953-0.7480469c-14.1435547-2.140625-28.5092773-2.3291016-42.6953125-0.5664063 c-0.331604,0.0407715-0.5751953,0.2971191-0.6331177,0.6113281c-0.3464966,0.277832-0.6930542,0.5556641-1.0396118,0.8334961 c0.1156616,1.137207,0.0985718,2.392333,0.1765137,3.5629873c-2.2901011-1.8925772-4.5957651-3.8081045-6.9354258-5.7802725 c-0.7441406-0.6269531-1.6889648-0.9277344-2.683105-0.8378906C4.4105406,11.3600969,3.320657,15.7476349,4.7497134,18.4358044z M60.7629585,14.6196432c-0.1265907,15.9033155,0.1148987,31.8954544-0.046875,47.7734375 c-14.0498047-0.3193359-26.8598633,0.2099609-39.1044922,1.6074219c0.0154419-10.8208008-0.2228394-21.3803711-0.6828613-31.503418 c8.6963615,7.0753174,9.1210613,7.5400124,10.6517334,8.1962891c2.7804565,1.1923828,7.8590698,1.5974121,8.4487305,0.6987305 c0.0741577-0.0522461,0.1495361-0.1047363,0.2015381-0.1826172c0.1469727-0.2207031,0.1669922-0.5029297,0.0517578-0.7412109 c-1.0354347-2.1505203-2.3683548-6.0868149-3.1914063-6.7568359c-5.5252628-4.5023842-10.581501-8.5776329-16.84375-13.7214375 c-0.1300049-1.973877-0.2654419-3.9484863-0.4165039-5.9221182C33.4343452,12.4419088,47.1985054,12.6274557,60.7629585,14.6196432 z M9.5368834,13.0405416c9.0454321,7.6246099,17.5216217,14.4366217,26.5917969,21.8203125 c0.3883591,0.3987503,1.5395088,3.3786926,2.2700195,5.078125c-1.4580688-0.1650391-2.9936523-0.479248-4.7089233-0.8842773 c0.4859009-0.9790039,1.1461182-1.8769531,1.953064-2.6108398c0.3061523-0.2783203,0.3286133-0.7529297,0.0498047-1.0595703 c-0.2783203-0.3046875-0.7519531-0.328125-1.0595703-0.0498047c-0.9295654,0.8461914-1.6932373,1.8774414-2.2598877,3.0026855 c-8.9527779-7.1637478-17.1909065-14.1875877-25.8739014-21.1394062c-0.5556641-0.4443359-0.8725586-1.09375-0.8481445-1.7363272 C5.7526169,12.8167362,8.1288319,11.8543167,9.5368834,13.0405416z"/>
				</svg>';
		}
		return $svg;
	}

	/**
	 *
	 * Uploads files that were submitted through the booking form at the time of creation of booking_intent, when we
	 * convert intent to booking later we will just use those URLs from intent custom fields in booking_data
	 *
	 * @param array $booking_data
	 * @return array
	 */
	public function process_custom_fields_in_booking_data_for_booking_intent(array $booking_data): array{


		// get files from $_FILES object
		$files = OsParamsHelper::get_file('booking');

    $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'agent');
    if(!isset($booking_data['custom_fields'])) $booking_data['custom_fields'] = [];
    if($custom_fields_structure){
      foreach($custom_fields_structure as $custom_field){
				switch($custom_field['type']){
					case 'file_upload':
						if(!empty($files['name']['custom_fields'][$custom_field['id']])){
							if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
							for($i = 0; $i<count($files['name']['custom_fields'][$custom_field['id']]); $i++){
								$file = [
									'name' => $files['name']['custom_fields'][$custom_field['id']][$i],
									'type' => $files['type']['custom_fields'][$custom_field['id']][$i],
									'tmp_name' => $files['tmp_name']['custom_fields'][$custom_field['id']][$i],
									'error' => $files['error']['custom_fields'][$custom_field['id']][$i],
									'size' => $files['size']['custom_fields'][$custom_field['id']][$i]
								];
								$result = wp_handle_upload($file, ['test_form' => false]);
								if(!isset($result['error']) && !empty($result['url'])){
									$booking_data['custom_fields'][$custom_field['id']] = $result['url'];
								}
							}
						}
						break;
					default:
						break;
				}
      }
    }

		return $booking_data;
	}

	public function add_capabilities_for_controller($required_capabilities){
		$required_capabilities['OsCustomFieldsController']['per_action'] = ['reload_custom_fields_for_quick_form' => ['booking__view']];
		return $required_capabilities;
	}

	public function add_custom_fields_to_processes(array $properties, string $event_type): array{
		$custom_fields['customer'] = OsCustomFieldsHelper::get_custom_fields_arr('customer');
		$custom_fields['booking'] = OsCustomFieldsHelper::get_custom_fields_arr('booking');

		switch ($event_type){
			case 'booking_created':
			case 'booking_updated':
				foreach($custom_fields['customer'] as $custom_field){
					$properties['custom_fields_for_customer__'.$custom_field['id']] = __('Customer/', 'latepoint-custom-fields').$custom_field['label'];
				}
				foreach($custom_fields['booking'] as $custom_field){
					$properties['custom_fields_for_booking__'.$custom_field['id']] = __('Booking/', 'latepoint-custom-fields').$custom_field['label'];
				}
				break;
			case 'customer_created':
				foreach($custom_fields['customer'] as $custom_field){
					$properties['custom_fields_for_customer__'.$custom_field['id']] = __('Customer/', 'latepoint-custom-fields').$custom_field['label'];
				}
				break;
		}
		return $properties;
	}

	public function output_google_autocomplete_settings(){
    echo '<div class="sub-section-row">
			      <div class="sub-section-label">
			        <h3>'.__('Google Places API', 'latepoint-custom-fields').'</h3>
			      </div>
			      <div class="sub-section-content">
						<div class="latepoint-message latepoint-message-subtle">'.__('In order for address autocomplete to work, you need an API key. To learn how to create an API key for Google Places API', 'latepoint-custom-fields').' <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/place-autocomplete#get-started">'.__('click here', 'latepoint-custom-fields').'</a></div>
							<div class="os-row">
								<div class="os-col-6">'.OsFormHelper::text_field('settings[google_places_api_key]', __('Google Places API key', 'latepoint-custom-fields'), OsSettingsHelper::get_settings_value('google_places_api_key'), ['theme' => 'bordered']).'</div>
								<div class="os-col-6">'.OsFormHelper::select_field('settings[google_places_country_restriction]', __('Country Restriction', 'latepoint-custom-fields'), OsCustomFieldsHelper::load_countries_list(), OsSettingsHelper::get_settings_value('google_places_country_restriction', '')).'</div>
							</div>
						</div>
					</div>';
	}

	public function add_customer_custom_fields_to_service_attributes($attributes, $booking){
		$customer = $booking->customer;
    $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'customer');
    if(isset($customer->custom_fields) && $customer->custom_fields){
      foreach($customer->custom_fields as $key => $custom_field){
        $value = ($custom_fields_structure[$key]['type'] == 'checkbox') ? OsCustomFieldsHelper::get_checkbox_value($custom_field) : $custom_field;
        if(!empty($value) && isset($custom_fields_structure[$key]) && $custom_fields_structure[$key]['hide_on_summary'] != 'on') $attributes[] = ['label' => $custom_fields_structure[$key]['label'], 'value' => $value];
      }
    }
		return $attributes;
	}

	public function add_booking_custom_fields_to_service_attributes($attributes, $booking){
    $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'customer');
    if(isset($booking->custom_fields) && $booking->custom_fields){
      foreach($booking->custom_fields as $key => $custom_field){
        $value = ($custom_fields_structure[$key]['type'] == 'checkbox') ? OsCustomFieldsHelper::get_checkbox_value($custom_field) : $custom_field;
        if(!empty($value) && isset($custom_fields_structure[$key]) && $custom_fields_structure[$key]['hide_on_summary'] != 'on') $attributes[] = ['label' => $custom_fields_structure[$key]['label'], 'value' => $value];
      }
    }
		return $attributes;
	}


	public function add_booking_custom_fields_data_vars_to_booking(array $data, OsModel $booking): array {
		if (is_a($booking, 'OsBookingModel')) {
	    $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'all');
	    foreach($custom_fields_for_booking as $custom_field){
	      $data['custom_fields'][$custom_field['id']] = $booking->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? '');
	    }
	    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'all');
	    foreach($custom_fields_for_customer as $custom_field){
	      $data['customer']['custom_fields'][$custom_field['id']] = $booking->customer->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? '');
	    }
		}
		return $data;
	}

  public function add_customer_custom_fields_data_vars_to_customer(array $data, OsModel $customer): array {
		if (is_a($customer, 'OsCustomerModel')) {
			$custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'all');
			foreach ($custom_fields_for_customer as $custom_field) {
				$data['custom_fields'][$custom_field['id']] = $customer->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? '');
			}
		}
    return $data;
  }
  
  public function add_custom_fields_to_bookings_table_columns($columns){
    $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'all');
    if($custom_fields_for_booking){
      foreach($custom_fields_for_booking as $custom_field){
        $columns['booking'][$custom_field['id']] = $custom_field['label'];
      }
    }
    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'all');
    if($custom_fields_for_customer){
      foreach($custom_fields_for_customer as $custom_field){
        $columns['customer'][$custom_field['id']] = $custom_field['label'];
      }
    }
    return $columns;
  }

  public function add_custom_fields_for_contact_step($customer, $booking_object){
    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'customer', $booking_object);
		echo OsCustomFieldsHelper::output_custom_fields_for_model($custom_fields_for_customer, $customer, 'customer');
  }


  public function replace_customer_vars_in_template($text, $customer){
    if($customer){
      $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
      if(!empty($custom_fields_for_customer)){
        $needles = [];
        $replacements = [];
        foreach($custom_fields_for_customer as $custom_field){
          $needles[] = '{{'.$custom_field['id'].'}}';
          $value = ($custom_field['type'] == 'checkbox') ? OsCustomFieldsHelper::get_checkbox_value($customer->get_meta_by_key($custom_field['id'], '')) : ($customer->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? ''));
          $replacements[] = $value;
        }
        $text = str_replace($needles, $replacements, $text);
      }
    }
    return $text;
  }


  public function replace_booking_vars_in_template($text, $booking){
    if($booking){
      $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'agent');
      if(!empty($custom_fields_for_booking)){
        $needles = [];
        $replacements = [];
        foreach($custom_fields_for_booking as $custom_field){
          $needles[] = '{{'.$custom_field['id'].'}}';
          $value = ($custom_field['type'] == 'checkbox') ? OsCustomFieldsHelper::get_checkbox_value($booking->get_meta_by_key($custom_field['id'], '')) : ($booking->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? ''));
          $replacements[] = $value;
        }
        $text = str_replace($needles, $replacements, $text);
      }
    }
    return $text;
  }

  public function add_custom_fields_to_bookings_data_for_csv($bookings_data, $params = []){

    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
    // update labels row
    foreach($custom_fields_for_customer as $custom_field){
      $bookings_data[0][] = $custom_field['label'];
    }
    $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'agent');
    // update labels row
    foreach($custom_fields_for_booking as $custom_field){
      $bookings_data[0][] = $custom_field['label'];
    }
    return $bookings_data;
  }

  public function add_custom_fields_to_booking_row_for_csv($booking_row, $booking, $params = []){

    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
    foreach($custom_fields_for_customer as $custom_field){
      $booking_row[] = $booking->customer->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? '');
    }
    $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'agent');
    foreach($custom_fields_for_booking as $custom_field){
      $booking_row[] = $booking->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? '');
    }
    return $booking_row;
  }

  public function add_custom_fields_to_customers_data_for_csv($customers_data, $params = []){

    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
    // update labels row
    foreach($custom_fields_for_customer as $custom_field){
      $customers_data[0][] = $custom_field['label'];
    }
    return $customers_data;
  }

  public function add_custom_fields_to_customer_row_for_csv($customer_row, $customer, $params = []){

    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
    foreach($custom_fields_for_customer as $custom_field){
      $customer_row[] = $customer->get_meta_by_key($custom_field['id']) ?: ($custom_field['value'] ?? '');
    }
    return $customer_row;
  }


  public function output_customer_custom_fields_on_customer_dashboard($customer){
    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'customer');
    if($custom_fields_for_customer) echo '<div class="os-row">'.OsCustomFieldsHelper::output_custom_fields_for_model($custom_fields_for_customer, $customer, 'customer').'</div>';
  }

  public function output_customer_custom_fields_on_form($customer){
    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
    if($custom_fields_for_customer){ ?>
        <div class="white-box">
          <div class="white-box-header">
            <div class="os-form-sub-header">
              <h3><?php _e('Custom Fields', 'latepoint-custom-fields'); ?></h3>
            </div>
          </div>
          <div class="white-box-content">
	          <div class="os-row">
	            <?php echo OsCustomFieldsHelper::output_custom_fields_for_model($custom_fields_for_customer, $customer, 'customer'); ?>
	          </div>
          </div>
        </div>
      <?php 
    }
  }


  public function output_booking_custom_fields_on_quick_form($booking){
    $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'agent', $booking);
    echo '<div class="latepoint-custom-fields-for-booking-wrapper" data-route-name="'.OsRouterHelper::build_route_name('custom_fields', 'reload_custom_fields_for_quick_form').'">';
    if(isset($custom_fields_for_booking) && !empty($custom_fields_for_booking)){ ?>
      <?php echo '<div class="os-row">'.OsCustomFieldsHelper::output_custom_fields_for_model($custom_fields_for_booking, $booking, 'booking').'</div>';
    }
    echo '</div>';
  }

  public function output_customer_custom_fields_on_quick_form($customer){
    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');
    if(isset($custom_fields_for_customer) && !empty($custom_fields_for_customer)){ ?>
      <?php echo '<div class="os-row">'.OsCustomFieldsHelper::output_custom_fields_for_model($custom_fields_for_customer, $customer, 'customer').'</div>';
    }
  }


  public function load_custom_fields_for_model($model){
    if(($model instanceof OsBookingModel) || ($model instanceof OsCustomerModel)){
      $fields_for = ($model instanceof OsBookingModel) ? 'booking' : 'customer';
      $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr($fields_for, 'agent');
      $metas = [];
      $model->custom_fields = [];
      if($model instanceof OsBookingModel){
        $metas = OsMetaHelper::get_booking_metas($model->id);
      }elseif($model instanceof OsCustomerModel){
        $metas = OsMetaHelper::get_customer_metas($model->id);
      }
      if($metas && $custom_fields_structure){
        foreach($custom_fields_structure as $key => $custom_field){
          if(isset($metas[$key])) $model->custom_fields[$key] = $metas[$key];
        }
      }
    }

    return $model;
  }


  public function set_custom_fields_data($model, $data = []){
    if(($model instanceof OsBookingModel) || ($model instanceof OsCustomerModel)){
      if($data && isset($data['custom_fields'])){
        $fields_for = ($model instanceof OsBookingModel) ? 'booking' : 'customer';
        $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr($fields_for, 'agent');
        if(!isset($model->custom_fields)) $model->custom_fields = [];
        foreach($data['custom_fields'] as $key => $custom_field){
          // check if data is allowed
          if(isset($custom_fields_structure[$key])) $model->custom_fields[$key] = $custom_field;
        }
      }
    }
  }

  public function validate_custom_fields($model, $alternative_validation = false){
    if($alternative_validation) return;
    if(($model instanceof OsBookingModel) || ($model instanceof OsCustomerModel)){
      $fields_for = ($model instanceof OsBookingModel) ? 'booking' : 'customer';
      $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr($fields_for, 'agent', ($fields_for == 'booking') ? $model : false);
      if(!isset($model->custom_fields)) $model->custom_fields = [];
      $errors = OsCustomFieldsHelper::validate_fields($model->custom_fields, $custom_fields_structure, $fields_for);
      if($errors){
        foreach($errors as $error){
          $model->add_error($error['type'], $error['message']);
        }
      }
    }
  }

  public function save_custom_fields($model){
    if($model->is_new_record()) return;
    if(($model instanceof OsBookingModel) || ($model instanceof OsCustomerModel)){
      $fields_for = ($model instanceof OsBookingModel) ? 'booking' : 'customer';
			// get files from $_FILES object
			$files = OsParamsHelper::get_file($fields_for);

      $custom_fields_structure = OsCustomFieldsHelper::get_custom_fields_arr($fields_for, 'agent', ($fields_for == 'booking') ? $model : false);
      if(!isset($model->custom_fields)) $model->custom_fields = [];
      if($custom_fields_structure){
        foreach($custom_fields_structure as $custom_field){
					switch($custom_field['type']){
						case 'file_upload':
							if(!empty($files['name']['custom_fields'][$custom_field['id']])){
								if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
								for($i = 0; $i<count($files['name']['custom_fields'][$custom_field['id']]); $i++){
									$file = [
										'name' => $files['name']['custom_fields'][$custom_field['id']][$i],
										'type' => $files['type']['custom_fields'][$custom_field['id']][$i],
										'tmp_name' => $files['tmp_name']['custom_fields'][$custom_field['id']][$i],
										'error' => $files['error']['custom_fields'][$custom_field['id']][$i],
										'size' => $files['size']['custom_fields'][$custom_field['id']][$i]
									];

									$same_file_already_exists = false;
									// check if there is already file for this field, compare it to the one that is being uploaded
									$existing_file_url = $model->get_meta_by_key($custom_field['id']);
									if($existing_file_url){
										$context = null; // FOR LOCAL(SSL) TESTING USE: $context = stream_context_create( [ 'ssl' => [ 'verify_peer' => false, 'verify_peer_name' => false, ] ]);
										$headers = get_headers($existing_file_url, 1, $context);
										// compare existing file with the one that is being uploaded
										$same_file_already_exists = (!empty($headers['Content-Length']) && ($headers['Content-Length'] == filesize($file['tmp_name'])) && (md5(file_get_contents($existing_file_url, false, $context)) == md5_file($file['tmp_name'])));
									}

									if(!$same_file_already_exists){
										try{
											$result = wp_handle_upload($file, ['test_form' => false]);
											if(!isset($result['error']) && !empty($result['url'])){
												$model->save_meta_by_key($custom_field['id'], $result['url']);
											}
										}catch(Exception $e){
											OsDebugHelper::log('File upload error', 'file_upload_error', ['error_message' => $e->getMessage()]);
										}
									}
								}
							}elseif(!empty($model->custom_fields[$custom_field['id']])){
								// file is already saved and is part of booking data, assign set model's meta to it's URL
		            $model->save_meta_by_key($custom_field['id'], $model->custom_fields[$custom_field['id']]);
							}
							break;
						default:
		          if(isset($model->custom_fields[$custom_field['id']])){
		            $model->save_meta_by_key($custom_field['id'], $model->custom_fields[$custom_field['id']]);
		          }
							break;
					}
        }
      }
    }
  }

  public function output_custom_fields_vars(){
    $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'agent');
    $custom_fields_for_customer = OsCustomFieldsHelper::get_custom_fields_arr('customer', 'agent');

    if($custom_fields_for_booking || $custom_fields_for_customer){ ?>
      <div class="available-vars-block">
        <h4><?php _e('Custom Fields', 'latepoint-custom-fields'); ?></h4>
        <ul>
          <?php 
            if($custom_fields_for_customer){
              echo '<li><strong>'.__('For Customer:', 'latepoint-custom-fields').'</strong></li>';
              foreach($custom_fields_for_customer as $custom_field){ ?>
                <li><span class="var-label"><?php echo $custom_field['label']; ?></span> <span class="var-code os-click-to-copy">{{<?php echo $custom_field['id']; ?>}}</span></li>
              <?php }
            }
            if($custom_fields_for_booking){
              echo '<li style="padding-top: 10px;"><strong>'.__('For Booking:', 'latepoint-custom-fields').'</strong></li>';
              foreach($custom_fields_for_booking as $custom_field){ ?>
                <li><span class="var-label"><?php echo $custom_field['label']; ?></span> <span class="var-code os-click-to-copy">{{<?php echo $custom_field['id']; ?>}}</span></li>
              <?php }
            } ?>
        </ul>
      </div>
    <?php }
  }


  public function process_step_custom_fields($step_name, $booking_object){
    if($step_name == 'custom_fields_for_booking'){

      $booking_params = OsParamsHelper::get_param('booking');
      $custom_fields_data = $booking_params['custom_fields'];
      $custom_fields_for_booking = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'customer', $booking_object);

      $is_valid = true;
      $errors = OsCustomFieldsHelper::validate_fields($custom_fields_data, $custom_fields_for_booking, 'booking');
      $error_messages = [];
      if($errors){
        $is_valid = false;
        foreach($errors as $error){
          $error_messages[] = $error['message'];
        }
      }
      if(!$is_valid){
        wp_send_json(array('status' => LATEPOINT_STATUS_ERROR, 'message' => $error_messages));
        return;
      }
    }
  }


  public function load_step_custom_fields_for_booking($step_name, $booking_object, $format = 'json', $restrictions = false){
    if($step_name == 'custom_fields_for_booking'){
      $custom_fields_controller = new OsCustomFieldsController();
      $custom_fields_controller->vars['custom_fields_for_booking'] = OsCustomFieldsHelper::get_custom_fields_arr('booking', 'customer', $booking_object);
      $custom_fields_controller->vars['booking'] = $booking_object;
      $custom_fields_controller->vars['current_step'] = $step_name;
      $custom_fields_controller->set_layout('none');
      $custom_fields_controller->set_return_format($format);
      $custom_fields_controller->format_render('_step_custom_fields_for_booking', [], [
        'step_name'         => $step_name, 
        'show_next_btn'     => OsStepsHelper::can_step_show_next_btn($step_name, $booking_object, $restrictions), 
        'show_prev_btn'     => OsStepsHelper::can_step_show_prev_btn($step_name, $booking_object, $restrictions), 
        'is_first_step'     => OsStepsHelper::is_first_step($step_name), 
        'is_last_step'      => OsStepsHelper::is_last_step($step_name), 
        'is_pre_last_step'  => OsStepsHelper::is_pre_last_step($step_name)]);
    }
  }

  public function show_step_info($step_name = ''){
    if($step_name == 'custom_fields_for_booking' && !OsCustomFieldsHelper::get_custom_fields_arr('booking', 'customer')){
      echo '<a href="'. OsRouterHelper::build_link(OsRouterHelper::build_route_name('settings', 'payments') ).'" class="step-message">'.__('You have not created any custom fields for booking, this step will be skipped', 'latepoint-custom-fields').'</a>';
    }
  }


  public function add_step_show_next_btn_rules($rules, $step_name){
    $rules['custom_fields_for_booking'] = true;
    return $rules;
  }

  public function add_custom_fields_step_defaults($defaults){
    $defaults['custom_fields_for_booking'] = [ 'title' => __('Custom Fields', 'latepoint-custom-fields'),
                                    'order_number' => 3,
                                    'sub_title' => __('Custom Fields', 'latepoint-custom-fields'),
                                    'description' => __('Please answer this set of questions to proceed.', 'latepoint-custom-fields') ];
    return $defaults;
  }


  public function add_step_for_custom_fields($steps, $show_all_steps){
    if(array_search('custom_fields_for_booking', $steps) === false){
      // if services step exists - add after it
      if(array_search('services', $steps) !== false){
        array_splice($steps, (array_search('services', $steps) + 1), 0, 'custom_fields_for_booking');
      }else{
        array_push($steps, 'custom_fields_for_booking');
      }
    }
    return $steps;
  }


  public function add_menu_links($menus){
    if(!OsAuthHelper::is_admin_logged_in()) return $menus;
    for($i=0; $i<count($menus); $i++) {
	    if (isset($menus[$i]['id']) && $menus[$i]['id'] == 'form_fields') {
		    $menus[$i] = ['id' => 'form_fields', 'label' => __('Form Fields', 'latepoint-custom-fields'), 'icon' => 'latepoint-icon latepoint-icon-layers', 'link' => OsRouterHelper::build_link(['custom_fields', 'for_customer']),
			    'children' => [
				    ['label' => __('Customer Fields', 'latepoint-custom-fields'), 'icon' => '', 'link' => OsRouterHelper::build_link(['custom_fields', 'for_customer'])],
				    ['label' => __('Booking Fields', 'latepoint-custom-fields'), 'icon' => '', 'link' => OsRouterHelper::build_link(['custom_fields', 'for_booking'])],
			    ]
		    ];
	    }
    }
    return $menus;
  }

  /**
   * Init LatePoint when WordPress Initialises.
   */
  public function init() {
    // Set up localisation.
    $this->load_plugin_textdomain();
  }

  public function load_plugin_textdomain() {
    load_plugin_textdomain('latepoint-custom-fields', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }


  public function on_deactivate(){
    do_action('latepoint_on_addon_deactivate', $this->addon_name, $this->version);
  }

  public function on_activate(){
    do_action('latepoint_on_addon_activate', $this->addon_name, $this->version);
  }

  public function register_addon($installed_addons){
    $installed_addons[] = ['name' => $this->addon_name, 'db_version' => $this->db_version, 'version' => $this->version];
    return $installed_addons;
  }

  public function db_sqls($sqls){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();


    return $sqls;
  }


  public function load_front_scripts_and_styles(){
    // Stylesheets
    wp_enqueue_style( 'latepoint-custom-fields-front', $this->public_stylesheets() . 'latepoint-custom-fields-front.css', false, $this->version );

    // Javascripts
		wp_enqueue_script( 'latepoint-custom-fields-front',  $this->public_javascripts() . 'latepoint-custom-fields-front.js', array('jquery'), $this->version );
    // Google Places API
    if(!empty(OsSettingsHelper::get_settings_value('google_places_api_key'))){
			wp_enqueue_script( 'google-places-api', OsCustomFieldsHelper::get_google_places_api_url(), false , null );
    }
  }

  public function load_admin_scripts_and_styles($localized_vars){
    // Stylesheets
    wp_enqueue_style( 'latepoint-custom-fields-admin', $this->public_stylesheets() . 'latepoint-custom-fields-admin.css', false, $this->version );

    // Javascripts
    wp_enqueue_script( 'latepoint-custom-fields-admin',  $this->public_javascripts() . 'latepoint-custom-fields-admin.js', array('jquery'), $this->version );

    if(!empty(OsSettingsHelper::get_settings_value('google_places_api_key'))){
			wp_enqueue_script( 'google-places-api', OsCustomFieldsHelper::get_google_places_api_url(), false , null );
    }
  }


  public function localized_vars_for_admin($localized_vars){
		$localized_vars['google_places_country_restriction'] = OsSettingsHelper::get_settings_value('google_places_country_restriction', '');
		$localized_vars['custom_fields_remove_file_prompt'] = __('Are you sure you want to remove this file?', 'latepoint-custom-fields');
		$localized_vars['custom_fields_remove_required_file_prompt'] = __('This file is required and can not be removed, but you can replace it with a different file. Do you want to replace it?', 'latepoint-custom-fields');
		$localized_vars['custom_field_default_value_field_html_route'] = OsRouterHelper::build_route_name('custom_fields', 'default_value_field');
		$localized_vars['custom_field_types_with_default_value'] = json_encode(OsCustomFieldsHelper::get_custom_field_types_with_default_value());
    return $localized_vars;
  }


  public function localized_vars_for_front($localized_vars){
		$localized_vars['google_places_country_restriction'] = OsSettingsHelper::get_settings_value('google_places_country_restriction', '');
		$localized_vars['custom_fields_remove_file_prompt'] = __('Are you sure you want to remove this file?', 'latepoint-custom-fields');
		$localized_vars['custom_fields_remove_required_file_prompt'] = __('This file is required and can not be removed, but you can replace it with a different file. Do you want to replace it?', 'latepoint-custom-fields');
    return $localized_vars;
  }

}

endif;

if ( in_array( 'latepoint/latepoint.php', get_option( 'active_plugins', array() ) )  || array_key_exists('latepoint/latepoint.php', get_site_option('active_sitewide_plugins', array())) ) {
  $LATEPOINT_ADDON_CUSTOM_FIELDS = new LatePointAddonCustomFields();
}
$latepoint_session_salt = 'MDZkZjRiNTItYmFiNS00ZTRkLTk0NTEtMmU0YmJmMDYxYjQ1';

<?php
/**
 * Plugin Name: LatePoint Addon - Reminders
 * Plugin URI:  https://latepoint.com/
 * Description: LatePoint addon for reminders
 * Version:     1.1.0
 * Author:      LatePoint
 * Author URI:  https://latepoint.com/
 * Text Domain: latepoint-reminders
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// If no LatePoint class exists - exit, because LatePoint plugin is required for this addon

if ( ! class_exists( 'LatePointAddonReminders' ) ) :

/**
 * Main Addon Class.
 *
 */

class LatePointAddonReminders {

  /**
   * Addon version.
   *
   */
  public $version = '1.1.0';
  public $db_version = '1.0.0';
  public $addon_name = 'latepoint-reminders';




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
  }


  public static function public_stylesheets() {
    return plugin_dir_url( __FILE__ ) . 'public/stylesheets/';
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

    // CONTROLLERS
    include_once(dirname( __FILE__ ) . '/lib/controllers/reminders_controller.php' );

    // HELPERS

    // MODELS

  }

  public function init_hooks(){
    add_action('latepoint_includes', [$this, 'includes']);

    add_action( 'init', array( $this, 'init' ), 0 );


    add_filter('latepoint_installed_addons', [$this, 'register_addon']);
    add_filter('latepoint_reminders_addon_installed', [$this, 'show_reminders']);
    add_filter('latepoint_event_time_offset_settings_html', [$this, 'add_event_time_offset_settings_html'], 10, 2);


    add_action('latepoint_process_scheduled_jobs', [$this, 'process_scheduled_jobs']);

    register_activation_hook(__FILE__, [$this, 'on_activate']);
    register_deactivation_hook(__FILE__, [$this, 'on_deactivate']);

  }


	public function add_event_time_offset_settings_html($html, \LatePoint\Misc\ProcessEvent $event){
		$html = '<div class="time-offset-actions">
		            <div class="time-offset-label">'.__('Actions will be executed:', 'latepoint').'</div>
		            '.OsFormHelper::number_field('process[event][time_offset][value]', '', $event->time_offset ? $event->time_offset['value'] : 1, 1, null, ['theme' => 'bordered']).'
		            '.OsFormHelper::select_field('process[event][time_offset][unit]', '', ['minute' => __('minutes', 'latepoint'), 'hour' => __('hours', 'latepoint'), 'day' => __('days', 'latepoint')], $event->time_offset ? $event->time_offset['unit'] : 'day').'
		            '.OsFormHelper::select_field('process[event][time_offset][before_after]', '', ['before' => __('before the event', 'latepoint'), 'after' => __('after the event', 'latepoint')], $event->time_offset ? $event->time_offset['before_after'] : 'after').'
							</div>';
		return $html;
	}

  public function process_scheduled_jobs(){
    OsProcessJobsHelper::process_scheduled_jobs();
  }

  public function show_reminders($show_reminders){
    return true;
  }


  /**
   * Init LatePoint when WordPress Initialises.
   */
  public function init() {
    // Set up localisation.
    $this->load_plugin_textdomain();
  }


  public function load_plugin_textdomain() {
    load_plugin_textdomain('latepoint-reminders', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }

  public function on_deactivate(){
    wp_clear_scheduled_hook('latepoint_process_scheduled_jobs');
  }

  public function on_activate(){
    if(class_exists('OsDatabaseHelper')) OsDatabaseHelper::check_db_version_for_addons();
    do_action('latepoint_on_addon_activate', $this->addon_name, $this->version);

    if (! wp_next_scheduled ( 'latepoint_process_scheduled_jobs' )) {
      wp_schedule_event(time(), 'latepoint_5_minutes', 'latepoint_process_scheduled_jobs');
    }
  }

  public function register_addon($installed_addons){
    $installed_addons[] = ['name' => $this->addon_name, 'db_version' => $this->db_version, 'version' => $this->version];
    return $installed_addons;
  }





}

endif;
if ( in_array( 'latepoint/latepoint.php', get_option( 'active_plugins', array() ) )  || array_key_exists('latepoint/latepoint.php', get_site_option('active_sitewide_plugins', array())) ) {
	$LATEPOINT_ADDON_REMINDERS = new LatePointAddonReminders();
}
$latepoint_session_salt = 'MDZkZjRiNTItYmFiNS00ZTRkLTk0NTEtMmU0YmJmMDYxYjQ1';

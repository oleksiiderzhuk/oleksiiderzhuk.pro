<?php
/**
 * Class that handles utility functionality.
 *
 * @link    https://wpmudev.com
 * @since   4.11.4
 * @author  Joel James <joel@incsub.com>
 * @package WPMUDEV_Dashboard_Utils
 */

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

/**
 * Class WPMUDEV_Dashboard_Utils
 */
class WPMUDEV_Dashboard_Utils {

	/**
	 * WPMUDEV_Dashboard_Utils constructor.
	 *
	 * @since 4.11.4
	 */
	public function __construct() {
		// Load Dash plugin first whenever possible.
		add_filter( 'pre_update_option_active_plugins', array( $this, 'set_plugin_priority' ), 9999 );
		add_filter( 'pre_update_site_option_active_sitewide_plugins', array( $this, 'set_plugin_priority' ), 9999 );

		// Disable cron if required.
		add_action( 'plugins_loaded', array( $this, 'maybe_disable_cron' ) );
		// Handle admin action request.
		add_action( 'wp_ajax_wpmudev_dashboard_admin_request', array( $this, 'run_admin_request' ) );
		add_action( 'wp_ajax_nopriv_wpmudev_dashboard_admin_request', array( $this, 'run_admin_request' ) );
		// Clear staff flag on logout.
		add_action( 'wp_logout', array( $this, 'unset_staff_flag' ) );
		// Make sure SSO is valid.
		add_action( 'wpmudev_after_remove_allowed_user', array( $this, 'recheck_sso_user' ) );
		// Do hub sync if server properties change.
		add_action( 'admin_init', array( $this, 'sync_on_site_info_change' ) );
	}

	/**
	 * Disable cron if possible.
	 *
	 * We are making an admin request only to process our actions.
	 * Don't let WP Cron to slow down the request.
	 *
	 * @since 4.11.7
	 *
	 * @return void
	 */
	public function maybe_disable_cron() {
		// Disable cron if possible.
		if ( $this->is_wpmudev_admin_request() && ! defined( 'DISABLE_WP_CRON' ) ) {
			define( 'DISABLE_WP_CRON', true );
		}
	}

	/**
	 * Set Dash plugin to load first by updating its position.
	 *
	 * This is the safest method than creating a MU plugin to get
	 * priority in plugin initialization order. Some plugins may change
	 * it, but that's okay.
	 *
	 * @since 4.11.4
	 *
	 * @param array $plugins Plugin list.
	 *
	 * @return array
	 */
	public function set_plugin_priority( $plugins ) {
		// Move to top.
		if ( isset( $plugins[ WPMUDEV_Dashboard::$basename ] ) ) {
			// Remove dash plugin.
			unset( $plugins[ WPMUDEV_Dashboard::$basename ] );

			// Set to first.
			return array_merge(
				array( WPMUDEV_Dashboard::$basename => time() ),
				$plugins
			);
		}

		return $plugins;
	}

	/**
	 * Make an self post request to wp-admin.
	 *
	 * Make an HTTP request to our own WP Admin to process admin side actions
	 * specifically hub sync or status updates which requires to be run on wp admin.
	 *
	 * @since 4.11.6
	 *
	 * @uses  admin_url()
	 * @uses  wp_remote_post()
	 * @uses  wp_generate_password()
	 * @uses  set_site_transient()
	 * @uses  delete_site_transient()
	 *
	 * @param array $data Request data.
	 *
	 * @return string|bool
	 */
	public function send_admin_request( $data = array() ) {
		// Create a random hash.
		$hash = md5( wp_generate_password() );
		// Create nonce.
		$nonce = wp_create_nonce( 'wpmudev_dashboard_admin_request' );

		// Set data in cache.
		set_site_transient(
			$hash,
			$data,
			120 // Expire it after 2 minutes in case we couldn't delete it.
		);

		// Request arguments.
		$args = array(
			'blocking'  => true,
			'timeout'   => 45,
			'sslverify' => false,
			'cookies'   => array(),
			'body'      => array(
				'action' => 'wpmudev_dashboard_admin_request',
				'nonce'  => $nonce,
				'hash'   => $hash,
			),
		);

		// Set cookies if required.
		if ( ! empty( $_COOKIE ) ) {
			foreach ( $_COOKIE as $name => $value ) {
				$args['cookies'][] = new WP_Http_Cookie( compact( 'name', 'value' ) );
			}
		}

		// Make post request.
		$response = wp_remote_post( admin_url( 'admin-ajax.php' ), $args );

		// Delete data after getting response.
		delete_site_transient( $hash );

		// If request not failed.
		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			// Get response body.
			return wp_remote_retrieve_body( $response );
		}

		return false;
	}

	/**
	 * Handle the post request for processing admin request.
	 *
	 * After verification a hook is triggered so we can use it
	 * to perform admin actions.
	 *
	 * @since 4.11.6
	 *
	 * @return void
	 */
	public function run_admin_request() {
		// Make sure required values are set.
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : ''; // phpcs:ignore
		$hash  = isset( $_POST['hash'] ) ? $_POST['hash'] : ''; // phpcs:ignore

		// Nonce and hash are required.
		if ( empty( $nonce ) || empty( $hash ) ) {
			wp_send_json_error(
				array(
					'code'    => 'invalid_params',
					'message' => __( 'Required parameters are missing', 'wpmudev' ),
				)
			);
		}

		// If nonce check failed.
		if ( ! wp_verify_nonce( $nonce, 'wpmudev_dashboard_admin_request' ) ) {
			wp_send_json_error(
				array(
					'code'    => 'nonce_failed',
					'message' => __( 'Admin request nonce check failed', 'wpmudev' ),
				)
			);
		}

		// Get request data from cache.
		$data = get_site_transient( $hash );

		// Make sure action and params are set.
		if ( false === $data ) {
			wp_send_json_error(
				array(
					'code'    => 'invalid_request',
					'message' => __( 'Invalid request.', 'wpmudev' ),
				)
			);
		}

		/**
		 * Process the admin request and send response.
		 *
		 * Always remember to send a json response using wp_send_json_error
		 * or wp_send_json_success.
		 *
		 * @since 4.11.6
		 *
		 * @param array $data Request data.
		 *
		 */
		do_action( 'wpmudev_dashboard_admin_request', $data );
	}

	/**
	 * Clear staff flag cookie on logout.
	 *
	 * @since 4.11.6
	 *
	 * @return void
	 */
	public function unset_staff_flag() {
		setcookie( 'wpmudev_is_staff', '', 1 );
	}

	/**
	 * Make sure the user ID is valid for SSO.
	 *
	 * @since 4.11.18
	 *
	 * @param int $user_id User ID.
	 *
	 * @return void
	 */
	public function recheck_sso_user( $user_id ) {
		$sso_user_id = WPMUDEV_Dashboard::$settings->get( 'userid', 'sso' );
		// If the removed user id is matching sso user id.
		if ( (int) $sso_user_id === (int) $user_id ) {
			$new_sso_user_id = $this->get_admin_user_for_sso();
			// Set new user id for SSO.
			WPMUDEV_Dashboard::$settings->set( 'userid', $new_sso_user_id, 'sso' );
		}
	}

	/**
	 * Get a admin user id for SSO.
	 *
	 * @since 4.11.18
	 *
	 * @return int
	 */
	public function get_admin_user_for_sso() {
		$user_id = get_current_user_id();
		// If we couldn't find a user.
		if ( empty( $user_id ) ) {
			$users = WPMUDEV_Dashboard::$site->get_allowed_users( true );
			if ( ! empty( $users[0] ) ) {
				$user_id = $users[0];
			}

			// Still empty?.
			if ( empty( $user_id ) ) {
				// Let's get an admin user now.
				$users = WPMUDEV_Dashboard::$site->get_available_users();
				if ( ! empty( $users[0] ) ) {
					$user_id = $users[0]->ID;
				}
			}
		}

		return $user_id;
	}

	/**
	 * Check if current request is Dashboard's admin request.
	 *
	 * @since 4.11.7
	 *
	 * @return bool
	 */
	private function is_wpmudev_admin_request() {
		// Check if all data is set.
		$is_valid_request = isset( $_POST['action'], $_POST['nonce'], $_POST['hash'] ); // phpcs:ignore

		// Check if wpmudev request.
		return $is_valid_request && 'wpmudev_dashboard_admin_request' === $_POST['action']; // phpcs:ignore
	}

	/**
	 * Check if current page is Dashboard's admin page.
	 *
	 * @since 4.11.15
	 *
	 * @return bool
	 */
	public function is_wpmudev_admin_page() {
		$screen = get_current_screen();

		// All dashboard page ids starts with wpmudev.
		return isset( $screen->parent_base ) && 'wpmudev' === $screen->parent_base;
	}

	/**
	 * Rename a folder to new name for backup.
	 *
	 * @since 4.11.9
	 *
	 * @param string $to   New folder name.
	 *
	 * @param string $from Current folder name.
	 *
	 * @return bool
	 */
	public function rename_plugin( $from, $to = '' ) {
		// Default backup name.
		$to = empty( $to ) ? $from . '-bak' : $to;

		// Rename plugin folder.
		return rename(
			WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $from,
			WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $to
		);
	}

	/**
	 * Check if a feature can be accessed.
	 *
	 * Currently only free memberships are being checked.
	 *
	 * @since 4.11.9
	 *
	 * @param string $feature Feature name.
	 *
	 * @return bool
	 */
	public function can_access_feature( $feature ) {
		$is_hosted_third_party = WPMUDEV_Dashboard::$api->is_hosted_third_party();
		$membership_type       = WPMUDEV_Dashboard::$api->get_membership_status();

		// Items not allowed for free users.
		$free_disallow = array( 'plugins', 'support', 'whitelabel', 'translations' );

		return ( 'free' !== $membership_type && ! $is_hosted_third_party ) || ! in_array( $feature, $free_disallow, true );
	}

	/**
	 * Get site information.
	 *
	 * Get site and server properties to show in Hub widget.
	 *
	 * @since 4.11.19
	 *
	 * @return bool
	 */
	public function get_site_info() {
		global $wp_version;

		// Prepare info.
		$info = array(
			'wp_version'   => $wp_version,
			'php_version'  => phpversion(),
			'wp_debug'     => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'issues_total' => $this->get_site_health_issues_total(),
			'php_memory'   => ini_get( 'memory_limit' ),
			'is_multisite' => is_multisite(),
			'server_ip'    => isset( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : '',
		);

		/**
		 * Filter hook to modify site info data.
		 *
		 * @since 4.11.19
		 *
		 * @param array $info Info.
		 */
		return apply_filters( 'wpmudev_dashboard_get_site_info', $info );
	}

	/**
	 * Get site properties.
	 *
	 * Get site and server properties to show in Hub widget.
	 *
	 * @since 4.11.19
	 *
	 * @return int
	 */
	public function get_site_health_issues_total() {
		// Get site health issues count.
		$issues = get_transient( 'health-check-site-status-result' );
		if ( ! empty( $issues ) ) {
			$issues = json_decode( $issues, true );
		}

		// If issues found.
		if ( isset( $issues['recommended'], $issues['critical'] ) ) {
			return $issues['recommended'] + $issues['critical'];
		}

		return 0;
	}

	/**
	 * Do a hub sync when site info changes.
	 *
	 * @since 4.11.19
	 *
	 * @return void
	 */
	public function sync_on_site_info_change() {
		// Get previous info.
		$previous = WPMUDEV_Dashboard::$settings->get( 'site_info', 'general', array() );
		// Get current site info.
		$current = $this->get_site_info();
		if ( $current !== $previous ) {
			// Do hub sync to update on Hub.
			WPMUDEV_Dashboard::$site->schedule_shutdown_refresh();

			WPMUDEV_Dashboard::$settings->set( 'site_info', $current, 'general' );

			/**
			 * Action hook to trigger on site info change.
			 *
			 * @since 4.11.19
			 *
			 * @param array $previous Previous info.
			 * @param array $previous Current info.
			 */
			do_action( 'wpmudev_dashboard_site_info_changed', $previous, $current );
		}
	}
}

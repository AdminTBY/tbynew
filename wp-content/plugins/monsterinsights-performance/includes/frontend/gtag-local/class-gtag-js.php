<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MonsterInsights_Gtag_Local_Js class holds the functionality necessary to add gtag.js code to a
 * local JS file.
 *
 * Which will help improve the performance of the website.
 *
 * @since 1.4.2
 */
final class MonsterInsights_Gtag_Local_Js {

	/**
	 * Hold info about the uploads directory.
	 *
	 * @since 1.4.2
	 *
	 * @var array
	 */
	protected $uploads_dir;

	/**
	 * Name of the base directory to hold local JS file.
	 *
	 * @since 1.4.2
	 *
	 * @var string
	 */
	protected $local_base_dir_name = 'monsterinsights';

	/**
	 * Name of sub directory to hold local JS file.
	 *
	 * @since 1.4.2
	 *
	 * @var string
	 */
	protected $local_gtag_dir_name = 'gtag';

	/**
	 * Name of the temp local JS file.
	 *
	 * @since 1.4.2
	 *
	 * @var string
	 */
	protected $local_gtag_temp_file_name = 'latest-gtag.js';

	/**
	 * Name of the local JS file.
	 *
	 * @since 1.4.2
	 *
	 * @var string
	 */
	protected $local_gtag_file_name = 'gtag.js';

	/**
	 * Catch errors that is generated from the class.
	 *
	 * @since 1.4.2
	 *
	 * @var string
	 */
	public $error = '';

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public function __construct() {

		// Add admin notices.
		add_action( 'admin_notices', array( $this, 'add_admin_notices' ) );

		// Change gtag file src from remote to local.
		add_filter( 'monsterinsights_frontend_output_gtag_src', array( $this, 'add_local_gtag_js' ), 10, 1 );

		// Run plugin init on admin_init.
		add_action( 'admin_init', array( $this, 'init' ) );

		// Run the Cron Job action to fetch the content from remote URL.
		add_action( 'monsterinsights_fetch_remote_gtag_js', array(
			$this,
			'monsterinsights_get_remote_gtag_js_content'
		) );

		// Run this hook when user wants to manually fetch gtag.js code from Google Servers upon a button click from Settings/Advanced.
		add_action( 'wp_ajax_monsterinsights_vue_get_local_gtag_js_from_remote', array(
			$this,
			'get_local_gtag_js_from_remote'
		) );
	}

	/**
	 * Get uploads directory info.
	 *
	 * @return mixed
	 * @since 1.4.2
	 *
	 */
	public function uploads_dir() {

		if ( ! isset( $this->uploads_dir ) ) {
			$this->uploads_dir = wp_upload_dir();
		}

		return $this->uploads_dir;
	}

	/**
	 * Initialize the functionality to add local gtag.js.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public function init() {

		if ( $this->passed_security_checks() ) {
			// Add & Run Cron Job.
			$this->monsterinsights_schedule_fetch_remote_gtag_js();

			$this->report_last_modified_difference();
		} else {
			$this->disable_cron();
		}
	}

	/**
	 * Do cleanup and remove Cron form WP.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	protected static function disable_cron() {
		if ( has_action( 'monsterinsights_fetch_remote_gtag_js' ) ) {
			wp_clear_scheduled_hook( 'monsterinsights_fetch_remote_gtag_js' );
		}
	}

	/**
	 * Run this function when the plugin in deleted.
	 * Used by uninstall.php inside plugin's root.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public static function do_cleanup() {
		// Remove cron job.
		self::disable_cron();
	}

	/**
	 * Update file src from remote to local JS file URL.
	 *
	 * @param string $url Remote URL for gtag.
	 *
	 * @return string
	 * @since 1.4.2
	 *
	 * @uses monsterinsights_frontend_output_gtag_src
	 * @see /plugins/monsterinsights/includes/frontend/tracking/class-tracking-gtag.php Line:177
	 *
	 */
	public function add_local_gtag_js( $url ) {

		if ( $this->enable_local_gtag_js() ) {

			if ( $this->is_file_readable() && $this->get_local_file_size() > 0 ) {

				return $this->get_local_file( 'url' );
			}
		}

		// Return original URL if anything goes wrong.
		return esc_url( $url );
	}

	/**
	 * Check if user wants to include the local gtag.js file.
	 * User can turn on or turn off this settings from the Performance
	 * Settings section in the admin.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	protected function enable_local_gtag_js() {
		if ( 'on' === monsterinsights_get_option( 'add_local_gtag_js_file', 'off' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if 'uploads' directory is writeable by the server.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	protected function is_uploads_dir_writeable() {

		if ( $this->filesystem()->exists( $this->uploads_dir()['basedir'] ) && $this->filesystem()->is_writable( $this->uploads_dir['basedir'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get path to local file. This only includes the path to the directory
	 * and not the file.
	 *
	 * @return string
	 * @since 1.4.2
	 *
	 */
	protected function get_local_file_dir_path() {
		$uploads_dir = $this->uploads_dir();

		return wp_normalize_path( $uploads_dir['basedir'] . '/' . $this->local_base_dir_name . '/' . $this->local_gtag_dir_name );
	}

	/**
	 * Get URL to local file. This only includes the URL to the directory
	 * and not the file.
	 *
	 * @return string
	 * @since 1.4.2
	 *
	 */
	protected function get_local_dir_url() {
		$uploads_dir = $this->uploads_dir();
		$baseurl     = $uploads_dir['baseurl'];
		$baseurl     = str_replace( 'http://', '//', $baseurl );
		$baseurl     = str_replace( 'https://', '//', $baseurl );

		return esc_url( $baseurl . '/' . $this->local_base_dir_name . '/' . $this->local_gtag_dir_name );
	}

	/**
	 * Get size of the local JS file.
	 *
	 * @return int
	 * @since 1.4.2
	 *
	 */
	protected function get_local_file_size() {
		return intval( $this->filesystem()->size( $this->get_local_file() ) );
	}

	/**
	 * Get size of the local JS file.
	 *
	 * @return int
	 * @since 1.4.2
	 *
	 */
	protected function get_local_file_modified_time() {
		return date_i18n( get_option( 'date_format' ), $this->filesystem()->mtime( $this->get_local_file() ) ) . ' @ ' . date_i18n( get_option( 'time_format' ), $this->filesystem()->mtime( $this->get_local_file() ) );
	}

	/**
	 * Get Path or URL to local file.
	 *
	 * @param string $mode What to fetch path or the URL to the file. Defaults to 'path'.
	 * @param string $type Temp or Original File. Defaults to original.
	 *
	 * @return string
	 * @since 1.4.2
	 *
	 */
	protected function get_local_file( $mode = 'path', $type = 'original' ) {

		if ( 'path' === $mode ) {

			if ( 'temp' === $type ) {
				return wp_normalize_path( $this->get_local_file_dir_path() . '/' . $this->local_gtag_temp_file_name );
			} else {
				return wp_normalize_path( $this->get_local_file_dir_path() . '/' . $this->local_gtag_file_name );
			}
		}

		return esc_url( $this->get_local_dir_url() . '/' . $this->local_gtag_file_name );
	}

	/**
	 * Check if the has not been updated since 72 hours. If that is the case
	 * then add the error/warning to admin notices.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public function report_last_modified_difference() {

		if ( ! $this->local_js_file_exists() ) {
			monsterinsights_delete_option( 'local_gtag_file_modified_at' );

			return;
		}

		$last_modified = monsterinsights_get_option( 'local_gtag_file_modified_at' );

		$settings_url = admin_url( 'admin.php?page=monsterinsights_settings&monsterinsights-scroll=monsterinsights-local-gtag-js-settings&monsterinsights-highlight=monsterinsights-local-gtag-js-settings#/advanced' );

		if ( ( time() - $last_modified ) > 3 * DAY_IN_SECONDS ) {
			$this->error = sprintf(
				__(
					'%1$sMonsterInsights: Performance Addon%2$s - Hey, we noticed the local gtag file has not been updated in more than %3$s, please try a %4$smanual fetch%5$s or contact support if you need help.',
					'monsterinsights-performance'
				),
				'<strong>',
				'</strong>',
				human_time_diff( $last_modified ),
				sprintf( '<a href="%1$s">', esc_url( $settings_url ) ),
				'</a>'
			);
		}
	}

	/**
	 * Check if local JS file is readable by the server user.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	protected function is_file_readable() {
		return $this->filesystem()->exists( $this->get_local_file() ) && $this->filesystem()->is_readable( $this->get_local_file() );
	}

	/**
	 * Check if local JS file exists.
	 *
	 * @param string $type Temp or Original File. Defaults to original.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	protected function local_js_file_exists( $type = 'original' ) {
		return $this->filesystem()->exists( $this->get_local_file( 'path', $type ) );
	}

	/**
	 * Create local directory.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 * @var string $type Wether to create a original directory or a temp
	 *                   directory.
	 *
	 */
	protected function create_local_dir() {

		if ( wp_mkdir_p( $this->get_local_file_dir_path() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Create local JS File.
	 *
	 * @param string $type Temp or Original File. Defaults to original.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	protected function create_local_js_file( $type = 'original' ) {

		if ( ! $this->create_local_dir() ) {
			return false;
		}

		$file = $this->get_local_file( 'path', $type );

		if ( ! $this->filesystem()->is_dir( $file ) && $handle = fopen( $file, 'w+' ) ) {
			fclose( $handle );

			return true;
		}

		return false;
	}

	/**
	 * Delete the local JS File.
	 *
	 * @param string $type Temp or Original File. Defaults to original.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	public function delete_local_gtag_js_file( $type = 'original' ) {

		if ( $this->local_js_file_exists( $type ) ) {

			return $this->filesystem()->delete( $this->get_local_file( 'path', $type ), false, 'f' );
		}

		return true;
	}

	/**
	 * Add some validations before proceeding to creating an actual file.
	 *
	 * @return bool
	 * @since 1.4.2
	 *
	 */
	public function passed_security_checks() {

		if ( ! $this->enable_local_gtag_js() ) {
			return false;
		}

		if ( ! $this->is_uploads_dir_writeable() ) {

			$this->error = sprintf(
				__(
					'%1$sMonsterInsights - Performance Addon:%2$s Uploads directory does not exists or is not writeable.',
					'monsterinsights-performance'
				),
				'<strong>',
				'</strong>'
			);

			return false;
		}

		if ( ! $this->create_local_dir() ) {

			$this->error = sprintf(
				__(
					'%1$sMonsterInsights - Performance Addon:%2$s Error: Unable to create custom directory monsterinsights/gtag inside uploads directory.',
					'monsterinsights-performance'
				),
				'<strong>',
				'</strong>'
			);

			return false;
		}

		if ( '' === monsterinsights_get_ua() && ! monsterinsights_get_v4_id() ) {

			$this->error = sprintf(
				__(
					'%1$sMonsterInsights - Performance Addon:%2$s Unable to fetch UA Code. It seems you are not connected to your Google Analytics account. Please confirm the connection between MonsterInsights and Google Analytics.',
					'monsterinsights-performance'
				),
				'<strong>',
				'</strong>'
			);

			return false;
		}

		return true;
	}

	/**
	 * Return google gtag url with UA Code.
	 *
	 * @return string
	 * @since 1.4.2
	 *
	 */
	private function google_gtag_url() {
		$connected_type = MonsterInsights()->auth->get_connected_type();
		$current_code   = $connected_type === 'ua'
			? monsterinsights_get_ua_to_output()
			: monsterinsights_get_v4_id_to_output();

		return 'https://www.googletagmanager.com/gtag/js?id=' . $current_code;
	}

	/**
	 * Get the content from remote URL for gtag.
	 *
	 * @param string $url Remote gtag URL.
	 * @param string $request_type If it is a Cron or an Ajax request. Defaults to cron.
	 *
	 * @return mixed
	 * @since 1.4.2
	 *
	 */
	public function get_remote_gtag_js_file_content( $url, $request_type = 'cron' ) {

		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {

			$response_body = wp_remote_retrieve_body( $response );

			$hashed_remote_response = hash( "sha512", $response_body );

			if ( strlen( $response_body ) > 0 ) {

				if ( ! $this->local_js_file_exists() ) {
					return $this->create_file( $response_body, $hashed_remote_response );
				} else {
					return $this->update_file( $response_body, $hashed_remote_response );
				}
			}
		} else {

			if ( 'ajax' === $request_type ) {
				return 'WP Error: ' . $response->get_error_message();
			}

			self::write_log( $response->get_error_message() );
		}

		return false;
	}

	/**
	 * Create local gtag.js file if not created.
	 *
	 * @param string $response_body Content fetched from Google.
	 * @param string $hashed_remote_response Hashed Content fetched from Google.
	 *
	 * @since 1.4.2
	 *
	 */
	protected function create_file( $response_body, $hashed_remote_response ) {

		$local_file = $this->create_local_js_file();

		if ( $local_file ) {

			if ( $this->is_file_readable() ) {

				if ( $this->filesystem()->put_contents( $this->get_local_file(), $response_body ) ) {

					$file_contents = $this->filesystem()->get_contents( $this->get_local_file() );

					$hashed_local_file = hash( "sha512", $file_contents );

					if ( $hashed_local_file === $hashed_remote_response ) {
						$this->update_file_modified_time_option();

						return true;
					}
				}
			}
		}

		$this->delete_local_gtag_js_file();

		return false;
	}

	/**
	 * Update local gtag.js file if already created.
	 *
	 * @param string $response_body Content fetched from Google.
	 * @param string $hashed_remote_response Hashed Content fetched from Google.
	 *
	 * @since 1.4.2
	 *
	 */
	protected function update_file( $response_body, $hashed_remote_response ) {

		$temp_file = $this->create_local_js_file( 'temp' );

		if ( $temp_file ) {

			$temp_file = $this->get_local_file( 'path', 'temp' );

			$this->filesystem()->put_contents( $temp_file, $response_body );

			$temp_file_contents     = $this->filesystem()->get_contents( $temp_file );
			$hashed_local_temp_file = hash( "sha512", $temp_file_contents );

			if ( $hashed_local_temp_file === $hashed_remote_response ) {

				$this->filesystem()->put_contents( $this->get_local_file(), $response_body );

				$this->update_file_modified_time_option();

				$this->delete_local_gtag_js_file( 'temp' );

				return true;
			} else {
				$this->delete_local_gtag_js_file( 'temp' );

				return false;
			}
		}

		return false;
	}

	/**
	 * Update last modified time of the file to site options.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	protected function update_file_modified_time_option() {
		$current_time = current_time( 'timestamp' );
		monsterinsights_update_option( 'local_gtag_file_modified_at', $current_time );
		do_action( 'monsterinsights_after_update_settings', 'local_gtag_file_modified_at', $current_time );
	}

	/**
	 * Add a Cron Job to run every 24 hours.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public function monsterinsights_schedule_fetch_remote_gtag_js() {
		// Make sure this event hasn't been scheduled
		if ( ! wp_next_scheduled( 'monsterinsights_fetch_remote_gtag_js' ) ) {

			// Add log trace.
			MonsterInsights_Gtag_Local_Js::write_log( 'MonsterInsights - Performance Addon: Cron Job Added.' );

			// Schedule the event to run daily (once).
			wp_schedule_event( time(), 'daily', 'monsterinsights_fetch_remote_gtag_js' );
		}
	}

	/**
	 * Run this function to fetch content from remote gtag URL and
	 * create a local JS file.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public function monsterinsights_get_remote_gtag_js_content() {

		// Fetch content from remote URL and create the file.
		$result = $this->get_remote_gtag_js_file_content( $this->google_gtag_url() );

		if ( $result ) {
			// Add Success Log.
			MonsterInsights_Gtag_Local_Js::write_log( 'MonsterInsights - Performance Addon: Local gtag.js File Added SuccessFully!' );
		} else {
			// Add Error Log.
			MonsterInsights_Gtag_Local_Js::write_log( 'MonsterInsights - Performance Addon: Unable to create Local gtag.js File.' );
		}
	}

	/**
	 * Fetch latest gtag.js code from remote upon Ajax Request.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 * @uses hook: wp_ajax_monsterinsights_vue_get_local_gtag_js_from_remote
	 *
	 */
	public function get_local_gtag_js_from_remote() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return;
		}

		if ( ! $this->passed_security_checks() ) {

			$result['result'] = false;
			$result['error']  = $this->error;

			wp_send_json( $result );
		}

		$error = esc_html__( 'We encountered an issue grabbing the latest version of the gtag.js file from Google servers. This is usually a temporary issue so please try again later. The current file was not replaced so tracking should not be affected.', 'monsterinsights-performance' );

		$result['result'] = false;
		$result['error']  = $error;

		$response = $this->get_remote_gtag_js_file_content( $this->google_gtag_url(), 'ajax' );

		if ( true === $response ) {
			$result['result']  = true;
			$result['success'] = monsterinsights_get_option( 'local_gtag_file_modified_at' );
		} else {
			if ( is_bool( $response ) && false === $response ) {
				$result['result'] = false;
				$result['error']  = $error;
			} else {
				$result['result'] = false;
				$result['error']  = $response;
			}
		}

		wp_send_json( $result );
	}

	/**
	 * Add notice to admin panel.
	 *
	 * @return string
	 * @since 1.4.2
	 *
	 */
	public function add_admin_notices() {

		if ( '' !== $this->error ) {

			self::write_log( $this->error );

			$class = 'notice notice-error';
			printf(
				'<div class="%1$s"><p>%2$s</p></div>',
				esc_attr( $class ),
				wp_kses(
					$this->error,
					array(
						'strong' => array(),
						'a'      => array(
							'href' => array()
						)
					)
				)
			);
		}
	}

	/**
	 * Check if WP Filesystem is available via $wp_filesystem.
	 *
	 * If not then include the file.php and make WP Filesystem available.
	 *
	 * @return object
	 * @since 1.4.2
	 *
	 */
	public function filesystem() {
		global $wp_filesystem;

		if ( is_null( $wp_filesystem ) ) {

			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	/**
	 * Static method to add messages to WP Debug Log file.
	 *
	 * @return void
	 * @since 1.4.2
	 *
	 */
	public static function write_log( $log ) {

		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && function_exists( 'error_log' ) ) {

			if ( is_array( $log ) || is_object( $log ) ) {

				error_log( print_r( $log, true ) );

			} else {

				error_log( $log );
			}
		}
	}
}

new MonsterInsights_Gtag_Local_Js();

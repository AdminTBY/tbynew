<?php
/**
 * Plugin Name: Real3D Flipbook Elementor Addon
 * Description: Extends Real3D Flipbook for use inside Elementor
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.3
 * Author:      creativeinteractivemedia
 * Author URI:  https://codecanyon.net/user/creativeinteractivemedia
 * Text Domain: r3dfb-el
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Elementor Real3D Flipbook Addon Class
 *
 * The init class that runs the plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.2.0
 */
final class Elementor_Real3D_Flipbook {

	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.3';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum Real3D Flipbook Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Real3D Flipbook version required to run the plugin.
	 */
	const MINIMUM_REAL3D_FLIPBOOK_VERSION = '3.8.3';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'r3dfb-el' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}


		// Check if Real3D Flipbook is installed
        if (!defined('REAL3D_FLIPBOOK_VERSION')) {
            // Display notice that Real3D Flipbook is required
            add_action('admin_notices', array( $this, 'admin_notice_missing_real3d_flipbook' ));
            return;
        }

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required Real3D Flipbook version
		if ( ! version_compare( REAL3D_FLIPBOOK_VERSION, self::MINIMUM_REAL3D_FLIPBOOK_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_real3d_flipbook_version' ) );
			return;
		}

		

		// Check for required PHP version
		/*if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}*/

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'r3dfb-el' ),
			'<strong>' . esc_html__( 'Real3D Flipbook Elementor Addon', 'r3dfb-el' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'r3dfb-el' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_real3d_flipbook() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'r3dfb-el' ),
			'<strong>' . esc_html__( 'Real3D Flipbook Elementor Addon', 'r3dfb-el' ) . '</strong>',
			'<strong>' . esc_html__( 'Real3D Flipbook', 'r3dfb-el' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'r3dfb-el' ),
			'<strong>' . esc_html__( 'Real3D Flipbook Elementor Addon', 'r3dfb-el' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'r3dfb-el' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_real3d_flipbook_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'r3dfb-el' ),
			'<strong>' . esc_html__( 'Real3D Flipbook Elementor Addon', 'r3dfb-el' ) . '</strong>',
			'<strong>' . esc_html__( 'Real3D Flipbook', 'r3dfb-el' ) . '</strong>',
			self::MINIMUM_REAL3D_FLIPBOOK_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'r3dfb-el' ),
			'<strong>' . esc_html__( 'Real3D Flipbook Elementor Addon', 'r3dfb-el' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'r3dfb-el' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate Elementor_Real3D_Flipbook.
new Elementor_Real3D_Flipbook();

<?php
/**
 * Plugin Name: YayMail Pro - WooCommerce Email Customizer
 * Secret Key: 83a5bb0e2ad5164690bc7a42ae592cf5
 * Plugin URI: https://yaycommerce.com/yaymail-woocommerce-email-customizer/
 * Description: Create awesome transactional emails with a drag and drop email builder
 * Version: 3.0.5
 * Author: YayCommerce
 * Author URI: https://yaycommerce.com
 * Text Domain: yaymail
 * WC requires at least: 3.0.0
 * WC tested up to: 6.9.1
 * Domain Path: /i18n/languages/
 */

namespace YayMail;

use YayMail\License;

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'YayMail\\init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Fallback.php';
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

if ( ! defined( 'YAYMAIL_PRO' ) ) {
	define( 'YAYMAIL_PRO', false );
}

if ( ! defined( 'YAYMAIL_PREFIX' ) ) {
	define( 'YAYMAIL_PREFIX', 'yaymail' );
}

if ( ! defined( 'YAYMAIL_DEBUG' ) ) {
	define( 'YAYMAIL_DEBUG', false );
}

if ( ! defined( 'YAYMAIL_VERSION' ) ) {
	define( 'YAYMAIL_VERSION', '3.0.5' );
}

if ( ! defined( 'YAYMAIL_PLUGIN_URL' ) ) {
	define( 'YAYMAIL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YAYMAIL_PLUGIN_PATH' ) ) {
	define( 'YAYMAIL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YAYMAIL_PLUGIN_BASENAME' ) ) {
	define( 'YAYMAIL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

define( 'YAYMAIL_SELLER_API_URL', 'https://yaycommerce.com/' );
define( 'YAYMAIL_ITEM_ID', 4216 );

spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__;
		$base_dir = __DIR__ . '/includes';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class_name = substr( $class, $len );

		$file = $base_dir . str_replace( '\\', '/', $relative_class_name ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

if ( ! function_exists( 'install_yaymail_admin_notice' ) ) {
	function install_yaymail_admin_notice() {
		?>
			<div class="error">
			
				<p>
				<?php
				// translators: %s: search WooCommerce plugin link
				 printf( 'YayMail ' . esc_html__( 'is enabled but not effective. It requires %1$sWooCommerce%2$s in order to work.', 'yaymail' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">', '</a>' );
				?>
				</p>
			</div>
		<?php
	}
}
if ( ! function_exists( 'YayMail\\init' ) ) {

	function init( $plugin ) {

		$license = License::get_instance( __FILE__ );
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'YayMail\\install_yaymail_admin_notice' );
		}

		if ( $license->get_license_key() ) {
			Plugin::getInstance();
			I18n::getInstance();
			Page\Settings::getInstance();
			MailBuilder\WooTemplate::getInstance();
			MailBuilder\YaymailElement::getInstance();
		}
	}
}

add_action( 'plugins_loaded', 'YayMail\\init' );

register_activation_hook( __FILE__, array( 'YayMail\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'YayMail\\Plugin', 'deactivate' ) );
/* Anti-Leecher Identifier */
/* Credited By BABIATO-FORUM */
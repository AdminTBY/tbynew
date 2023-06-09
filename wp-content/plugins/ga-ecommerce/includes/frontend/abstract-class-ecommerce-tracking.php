<?php

/**
 * Class MonsterInsights_eCommerce_Tracking_Abstract
 *
 * Tracks transactions as soon as they're set to paid on the server. Abstract so needs to be extended.
 *
 * @since 6.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class MonsterInsights_eCommerce_Tracking_Abstract {

	/**
	 * @var string $uuid_meta_key The name of the meta key used to store the UUID
	 */
	protected $uuid_meta_key = '_yoast_gau_uuid';
	protected $cookie_meta_key = '_monsterinsights_cookie';

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->load();
	}

	/**
	 * Store the visitor ID and attached experiments and variations, as stored in the cookie, with the transaction.
	 *
	 * @param int $payment_id The ID of the payment to attached the data to.
	 *
	 * @since 6.0.0
	 *
	 */
	public function store_user_id( $payment_id ) {
		$ga_uuid = $this->read_cookie();
		if ( $ga_uuid ) {
			$cookie = $this->get_cookie();
			update_post_meta( $payment_id, $this->uuid_meta_key, $ga_uuid );
			update_post_meta( $payment_id, $this->cookie_meta_key, $cookie );
		}
	}

	/**
	 * Hooks the needed actions.
	 *
	 * @since 6.0.0
	 */
	protected function load() {
		add_action( $this->get_store_user_id_hook(), array( $this, 'store_user_id' ), 10, 1 );

		$this->get_order_actions();
	}

	/**
	 * Check if payment is of correct post type.
	 *
	 * @param mixed $payment_id ID of the payment.
	 *
	 * @return bool
	 * @since 7.4.0
	 *
	 */
	protected function check_payment_post_type( $payment_id ) {
		if ( is_object( $payment_id ) ) {
			$payment_id = get_post( $payment_id )->ID;
		}

		$post_type = $this->get_order_post_type();

		if ( $post_type != get_post_type( $payment_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Executing the transaction, only when the new status is paid.
	 *
	 * @param int $payment_id
	 *
	 * @since 6.0.0
	 *
	 */
	protected function do_transaction( $payment_id ) {

		if ( ! $this->check_payment_post_type( $payment_id ) ) {
			return;
		}

		$is_in_ga = get_post_meta( $payment_id, '_monsterinsights_is_in_ga', true );
		$skip_ga  = apply_filters( 'monsterinsights_ecommerce_do_transaction_skip_ga', false, $payment_id );
		if ( $is_in_ga === 'yes' || $skip_ga ) {
			return;
		}

		$payload = $this->get_payment_payload( $payment_id );

		$this->send_hit( $payload['main'] );

		foreach ( $payload['products'] as $single_payload ) {
			$this->send_hit( $single_payload );
		}

		update_post_meta( $payment_id, '_monsterinsights_is_in_ga', 'yes' );
	}

	/**
	 * Undo the transaction, will executed when going from paid to another status
	 *
	 * @param int $payment_id
	 *
	 * @link  https://support.google.com/analytics/answer/1037443?hl=en
	 *
	 * @since 6.0.0
	 *
	 */
	protected function undo_transaction( $payment_id ) {

		if ( ! $this->check_payment_post_type( $payment_id ) ) {
			return;
		}

		$is_in_ga = get_post_meta( $payment_id, '_monsterinsights_is_in_ga', true );
		$skip_ga  = apply_filters( 'monsterinsights_ecommerce_undo_transaction_skip_ga', false, $payment_id );
		if ( $is_in_ga !== 'yes' || $skip_ga ) {
			return;
		}

		$payload = $this->get_payment_payload( $payment_id );

		// Reverse the transaction
		$payload['main']['tr'] = 0 - $payload['main']['tr'];
		$payload['main']['tt'] = 0 - $payload['main']['tt'];

		$this->send_hit( $payload['main'] );

		// Reverse each product too
		foreach ( $payload['products'] as $single_payload ) {
			$single_payload['iq'] = 0 - $single_payload['iq'];
			$this->send_hit( $single_payload );
		}

		delete_post_meta( $payment_id, '_monsterinsights_is_in_ga' );
	}

	/**
	 * Default array, with values that should be in every payload
	 *
	 * @param int $payment_id
	 *
	 * @return array $payload
	 * @since 6.0.0
	 *
	 */
	protected function get_default_payload( $payment_id ) {

		$ga_uuid = get_post_meta( $payment_id, $this->uuid_meta_key, true );
		if ( ! is_string( $ga_uuid ) || '' === $ga_uuid ) {
			$ga_uuid = $this->generate_uuid();
		}

		$payload = array(
			'cid' => $ga_uuid,
			't'   => 'transaction',
			'ti'  => $this->get_order_number( $payment_id ),
			'ta'  => $this->get_payment_method( $payment_id ),
			'ts'  => '0.00',
		);

		$user_id = $this->get_user_id( $payment_id );
		if ( ! empty( $user_id ) ) {
			$payload['uid'] = $user_id;
		}

		return $payload;
	}

	/**
	 * Getting the order number.
	 *
	 * Instead of payment_id maybe there is a custom order_number
	 *
	 * @param integer $payment_id
	 *
	 * @return integer
	 */
	protected function get_order_number( $payment_id ) {
		return $payment_id;
	}

	/**
	 * Getting the product SKU if exist otherwise return product_id
	 *
	 * @param integer $product_id
	 *
	 * @return mixed
	 */
	protected function get_product_sku( $product_id ) {
		return $product_id;
	}

	/**
	 * Retrieve the details for the payment
	 *
	 * @param int $payment_id
	 *
	 * @return array $payload
	 * @link  https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide#ecom
	 *
	 * @since 6.0.0
	 *
	 */
	protected function get_payment_payload( $payment_id ) {

		// Get the order_details
		$order = $this->get_order_details( $payment_id );

		$tax = ( array_key_exists( 'total_tax', $order ) && 0 !== $order['total_tax'] ) ? (string) number_format( $order['total_tax'], 2, '.', '' ) : 0;

		$payload = array(
			'main' => array_merge(
				$this->get_default_payload( $payment_id ),
				array(
					'tr' => (string) number_format( $order['total_amount'], 2, '.', '' ),
					'tt' => $tax,
					'cu' => $order['currency'],
				)
			),
		);

		$payload['products'] = $this->parse_items( $order['items'], $payload['main'] );

		return $payload;

	}

	/**
	 * Parses the cart items for analytics
	 *
	 * Uses payload to get similar data to use in the array to return
	 *
	 * @param array $items
	 * @param array $payload
	 *
	 * @return array
	 * @since 6.0.0
	 *
	 */
	protected function parse_items( $items, $payload ) {

		$return = array();

		if ( is_array( $items ) ) {
			$default_item = array(
				'cid' => $payload['cid'],
				't'   => 'item',
				'ti'  => $payload['ti'],
				'cu'  => $payload['cu'],
			);

			if ( ! empty( $payload['uid'] ) ) {
				$default_item['uid'] = $payload['uid'];
			}

			foreach ( $items as $item ) {
				$return[] = array_merge( $default_item, $this->parse_item( $item ) );
			}
		}

		return $return;
	}

	/**
	 * Sends a hit to Google Analytics Universal collection.
	 *
	 * @param array $payload The values to send to Google Analytics Universal.
	 *
	 * @return bool
	 * @since 6.0.0
	 *
	 * @link  https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
	 *
	 */
	protected function send_hit( $payload ) {
		$default_payload = array(
			'v'   => 1,
			'tid' => monsterinsights_get_ua_to_output( array( 'ecommerce' => $payload ) ),
		);

		$body = http_build_query( array_merge( $default_payload, $payload ), '', '&' );

		$args = array(
			'body'       => $body,
			'user-agent' => 'MonsterInsights eCommerce Tracker ' . MONSTERINSIGHTS_VERSION,
			'timeout'    => 60,
			'blocking'   => false,
		);

		wp_remote_post( 'https://www.google-analytics.com/collect', $args );

		return true;
	}

	/**
	 * Returns the Google Analytics clientId to store for later use
	 *
	 * @return bool|string False if cookie isn't set, GA UUID otherwise
	 * @link  https://developers.google.com/analytics/devguides/collection/analyticsjs/domains#getClientId
	 *
	 * @since 6.0.0
	 *
	 */
	protected function read_cookie() {
		if ( empty( $_COOKIE['_ga'] ) ) {
			return false;
		}

		/**
		 * Example cookie formats:
		 *
		 * GA1.2.XXXXXXX.YYYYY
		 * _ga=1.2.XXXXXXX.YYYYYY -- We want the XXXXXXX.YYYYYY part
		 *
		 */

		$ga_cookie    = $_COOKIE['_ga'];
		$cookie_parts = explode( '.', $ga_cookie );
		if ( is_array( $cookie_parts ) && ! empty( $cookie_parts[2] ) && ! empty( $cookie_parts[3] ) ) {
			$uuid = (string) $cookie_parts[2] . '.' . (string) $cookie_parts[3];
			if ( is_string( $uuid ) ) {
				return $uuid;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Returns the Google Analytics clientId to store for later use
	 *
	 * @return GA UUID or error code.
	 * @since 6.0.0
	 *
	 */
	protected function get_cookie() {
		if ( empty( $_COOKIE['_ga'] ) ) {
			return 'FCE';
		}

		$ga_cookie    = $_COOKIE['_ga'];
		$cookie_parts = explode( '.', $ga_cookie );
		if ( is_array( $cookie_parts ) && ! empty( $cookie_parts[2] ) && ! empty( $cookie_parts[3] ) ) {
			$uuid = (string) $cookie_parts[2] . '.' . (string) $cookie_parts[3];
			if ( is_string( $uuid ) ) {
				return $ga_cookie;
			} else {
				return 'FA';
			}
		} else {
			return 'FAE';
		}
	}

	/**
	 * Generate UUID v4 function - needed to generate a CID when one isn't available
	 *
	 * @link http://www.stumiller.me/implementing-google-analytics-measurement-protocol-in-php-and-wordpress/
	 *
	 * @since 6.0.0
	 * @return string
	 */
	public function generate_uuid() {

		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	/**
	 * Every class extending this class, should have get_store_user_id_hook method
	 *
	 * @return mixed
	 * @since 6.0.0
	 *
	 */
	abstract protected function get_store_user_id_hook();

	/**
	 * Every class extending this class, should have get_user_id method
	 *
	 * @return mixed
	 * @since 6.0.3
	 *
	 */
	abstract protected function get_user_id( $payment_id );

	/**
	 * Every class extending this class, should have get_order_actions method
	 *
	 * @return void
	 * @since 6.0.0
	 *
	 */
	abstract protected function get_order_actions();

	/**
	 * Every class extending this class, should have maybe_do_transaction method
	 *
	 * @return string
	 * @since 6.0.0
	 *
	 */
	abstract public function maybe_do_transaction();

	/**
	 * Every class extending this class, should have maybe_undo_transaction method
	 *
	 * @return string
	 * @since 6.0.0
	 *
	 */
	abstract public function maybe_undo_transaction();

	/**
	 * Every class extending this class, should have get_order_post_type method
	 *
	 * @return string
	 * @since 6.0.0
	 *
	 */
	abstract protected function get_order_post_type();

	/**
	 * Every class extending this class, should have get_payment_method method
	 *
	 * @param integer $payment_id
	 *
	 * @return mixed
	 * @since 6.0.0
	 *
	 */
	abstract protected function get_payment_method( $payment_id );

	/**
	 * Every class extending this class, should have get_order_details method
	 *
	 * @param integer $payment_id
	 *
	 * @return array
	 * @since 6.0.0
	 *
	 */
	abstract protected function get_order_details( $payment_id );

	/**
	 * Every class extending this class, should have get_order_details method
	 *
	 * @param array $item
	 *
	 * @return array
	 * @since 6.0.0
	 *
	 */
	abstract protected function parse_item( $item );
}


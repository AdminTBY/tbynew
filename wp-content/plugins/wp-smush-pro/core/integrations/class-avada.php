<?php
/**
 * Avada integration module.
 *
 * @since 3.3.0
 * @package Smush\Core\Integrations
 */

namespace Smush\Core\Integrations;

use Smush\Core\Modules\CDN;
use Smush\Core\Modules\Helpers\Parser;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Avada
 */
class Avada {

	/**
	 * CDN module instance.
	 *
	 * @var CDN $cdn
	 */
	private $cdn;

	/**
	 * Avada constructor.
	 *
	 * @since 3.3.0
	 *
	 * @param CDN $cdn  CDN module.
	 */
	public function __construct( CDN $cdn ) {
		if ( $cdn->is_active() ) {
			$this->cdn = $cdn;
			add_filter( 'smush_cdn_bg_image_tag', array( $this, 'replace_cdn_links' ) );

			if ( defined( 'FUSION_BUILDER_PLUGIN_DIR' ) ) {
				add_filter( 'smush_after_process_background_images', array( $this, 'smush_cdn_image_replaced' ), 10, 3 );
			}
		}
	}

	/**
	 * Replace all the image src with cdn link.
	 *
	 * @param string $content Content of the current post.
	 * @param string $image   Backround Image tag without src.
	 * @param string $img_src Image src.
	 * @return string
	 */
	public function smush_cdn_image_replaced( $content, $image, $img_src ) {
		if ( $this->cdn->is_supported_path( $img_src ) ) {
			$new_src = $this->cdn->generate_cdn_url( $img_src );

			if ( $new_src ) {
				$content = str_replace( $img_src, $new_src, $content );
			}
		}

		return $content;
	}

	/**
	 * Replace images from data-bg-url with CDN links.
	 *
	 * @since 3.3.0
	 *
	 * @param string $img  Image.
	 *
	 * @return string
	 */
	public function replace_cdn_links( $img ) {
		$image_src = Parser::get_attribute( $img, 'data-bg-url' );
		if ( $image_src ) {
			// Store the original source to be used later on.
			$original_src = $image_src;

			// Replace the data-bg-url of the image with CDN link.
			if ( $this->cdn->is_supported_path( $image_src ) ) {
				$image_src = $this->cdn->generate_cdn_url( $image_src );

				if ( $image_src ) {
					$img = preg_replace( '#(data-bg-url=["|\'])' . $original_src . '(["|\'])#i', '\1' . $image_src . '\2', $img, 1 );
				}
			}
		}

		return $img;
	}

}
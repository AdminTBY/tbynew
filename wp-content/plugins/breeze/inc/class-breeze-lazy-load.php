<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

class Breeze_Lazy_Load {
	/**
	 * Whether Lazy load is active.
	 * @var false|mixed
	 * @since 1.2.0
	 * @access private
	 */
	private $lazy_load_enabled = false;

	/**
	 * Must use native Lazy Load.
	 * @var false|mixed
	 * @since 1.2.0
	 * @access private
	 */
	private $lazy_load_native = false;

	/**
	 * The page HTML content.
	 *
	 * @var string
	 * @since 1.2.0
	 * @access private
	 */
	private $content = '';

	/**
	 * Exclude images that have these attributes from processing.
	 *
	 * @since 1.2.0
	 * @var string[]
	 */
	private $exclude_if_atts = array();

	/**
	 * Breeze_Lazy_Load constructor.
	 *
	 * @param false $is_enabled If the base Lazy Load is enabled.
	 * @param string $content the HTML content of the page.
	 * @param false $is_native Whether to use native lazy load or Javascript based.
	 *
	 * @access public
	 * @since 1.2.0
	 */
	function __construct( $content = '', $is_enabled = false, $is_native = false ) {
		$this->lazy_load_enabled = $is_enabled;
		$this->lazy_load_native  = $is_native;
		$this->content           = $content;

		$this->exclude_if_atts = apply_filters(
			'breeze_excluded_attributes',
			array(
				'data-src',
				'data-no-lazy',
				'data-lazy-original',
				'data-lazy-src',
				'data-lazysrc',
				'data-lazyload',
				'data-bgposition',
				'data-envira-src',
				'fullurl',
				'lazy-slider-img',
				'data-srcset',
				'data-spai',
			)
		);

	}

	/**
	 * Apply lazy load library option.
	 *
	 * @return false|string
	 * @access public
	 * @since 1.2.0
	 */
	public function apply_lazy_load_feature() {
		$content = $this->content;

		if ( defined( 'REST_REQUEST' ) || is_feed() || is_admin() || is_comment_feed() || is_preview() || is_robots() || is_trackback() ) {
			return $content;
		}

		if ( false === $this->lazy_load_enabled ) {
			return $content;
		}

		if ( '' === trim( $content ) ) {
			return $content;
		}

		// If this option is set to true then loading="lazy" attribute will be use instead.
		// The native lazy load is not yet supported by all browsers. ( As of February 2021, 73% of browsers support lazy loading. )
		$use_native = apply_filters( 'breeze_use_native_lazy_load', $this->lazy_load_native );

		$content = mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' );

		$html_dom                     = new DOMDocument();
		$html_dom->preserveWhiteSpace = false;// phpcs:ignore
		$html_dom->formatOutput       = false;// phpcs:ignore

		libxml_use_internal_errors( true );

        preg_match_all( '/<script\b(?![^>]*\bsrc\s*=)[^>]*>(.*?)<\/script>/is', $content, $script_matches );

		if ( ! empty( $script_matches ) && ! empty( $script_matches[0][0] ) ) {
			foreach ( $script_matches[0] as $index => $script_js ) {
				$content = str_replace( $script_js, '<!--{BREEZE_SCRIPT_PH' . $index . '}-->', $content );
			}
		}
		$html_dom->loadHTML( $content, LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );  //  | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | HTML_PARSE_NOIMPLIED

		$dom_last_error = libxml_get_last_error();
		$dom_all_error  = libxml_get_errors();

		$dom_xpath = new DOMXPath( $html_dom );
		/**
		 * Fetch all images
		 */
		$get_dom_images = $dom_xpath->query( '//img' );
		if ( ! is_null( $get_dom_images ) ) {
			foreach ( $get_dom_images as $image_element ) {
				// If native is enabled.
				if ( true === $use_native ) {
					$loading = $image_element->getAttribute( 'loading' );
					if ( empty( $loading ) ) {
						$image_element->setAttribute( 'loading', 'lazy' );
					}
				} else {
					// Going the classic way by implementing lazy load via JavaScript.

					// Check if the image is to be ignored.
					if ( is_array( $this->exclude_if_atts ) && ! empty( $this->exclude_if_atts ) ) {
						$exclude_it = false;
						foreach ( $this->exclude_if_atts as $ex_attr ) {
							$exclude_found = $image_element->getAttribute( $ex_attr );
							if ( ! empty( $exclude_found ) ) {
								$exclude_it = true;
							}
						}

						if ( true === $exclude_it ) {
							continue;
						}
					}
					// Get the image URL
					$current_src = $image_element->getAttribute( 'src' );
					if ( true !== $this->excluded_images( $current_src ) ) {
						// Add lazy-load data attribute.
						$image_element->setAttribute( 'data-breeze', $current_src );
						// Remove the current image source.
						$image_element->removeAttribute( 'src' );
						$get_width  = $image_element->getAttribute( 'width' );
						$get_height = $image_element->getAttribute( 'height' );
						$image_element->setAttribute( 'src', $this->generate_simple_placeholder( $get_width, $get_height ) );

						// Fetch the current image CSS classes.
						$current_classes = $image_element->getAttribute( 'class' );
						// Append breeze lazy-load CSS class.
						if ( empty( trim( $current_classes ) ) ) {
							$current_classes = 'br-lazy';
						} else {
							$current_classes .= ' br-lazy';
						}
						$image_element->removeAttribute( 'class' );
						$image_element->setAttribute( 'class', $current_classes );

						// handle SRCSET and SIZES attributes.
						$srcset = $image_element->getAttribute( 'srcset' );
						$sizes  = $image_element->getAttribute( 'sizes' );
						if ( ! empty( $srcset ) ) {
							$image_element->setAttribute( 'data-brsrcset', $srcset );
							$image_element->removeAttribute( 'srcset' );
						}

						if ( ! empty( $sizes ) ) {
							$image_element->setAttribute( 'data-brsizes', $sizes );
							$image_element->removeAttribute( 'sizes' );
						}
					}
				}
			}
		}


		$apply_to_iframes = Breeze_Options_Reader::get_option_value( 'breeze-lazy-load-iframes' );
		$apply_to_iframes = apply_filters( 'breeze_enable_lazy_load_iframes', $apply_to_iframes );

		if ( true === filter_var( $apply_to_iframes, FILTER_VALIDATE_BOOLEAN ) ) {
			// Iterate each iframe item found in the content.
			foreach ( $html_dom->getElementsByTagName( 'iframe' ) as $iframe ) {
				// fetch iframe video source.
				$src = $iframe->getAttribute( 'src' );

				$allowed_url         = false;
				$allowed_iframes_url = apply_filters( 'breeze_iframe_lazy_load_list', array(
					'youtube.com',
					'dailymotion.com/embed/video',
					'facebook.com/plugins/video.php',
					'player.vimeo.com',
					'fast.wistia.net/embed/',
					'players.brightcove.net',
					's3.amazonaws.com',
					'cincopa.com/media',
					'twitch.tv',
					'bitchute.com',
					'media.myspace.com/play/video',
					'tiktok.com/embed',
				) );

				foreach ( $allowed_iframes_url as $iframe_url ) {
					if ( false !== strpos( $src, $iframe_url ) ) {
						$allowed_url = true;
						break;
					}
				}

				// We only need iframes that handle youtube videos.
				if ( true === $allowed_url ) {
					// Fetch the video ID from iframe source.
					$video_link = explode( '/', $src );
					$video_id   = end( $video_link );

					// Set the video ID as attribute on iframe.
					$iframe->setAttribute( 'data-video-id', $video_id );

					// Fetch the current CSS classes if any
					$current_classes = $iframe->getAttribute( 'class' );
					if ( ! empty( trim( $current_classes ) ) ) {
						$current_classes .= ' ';
					}
					$current_classes .= 'br-lazy';

					// Remove the current CSS class attribute.
					$iframe->removeAttribute( 'class' );
					// Add the CSS classes back including the extra.
					$iframe->setAttribute( 'class', $current_classes );

					$iframe->setAttribute( 'data-breeze', $src );
					$iframe->removeAttribute( 'src' );
				}
			}

			foreach ( $html_dom->getElementsByTagName( 'video' ) as $video_tab ) {
				$src = $video_tab->getAttribute( 'src' );

				// Fetch the current CSS classes if any
				$current_classes = $video_tab->getAttribute( 'class' );
				if ( ! empty( trim( $current_classes ) ) ) {
					$current_classes .= ' ';
				}
				$current_classes .= 'br-lazy';

				// Remove the current CSS class attribute.
				$video_tab->removeAttribute( 'class' );
				// Add the CSS classes back including the extra.
				$video_tab->setAttribute( 'class', $current_classes );

				$video_tab->setAttribute( 'data-breeze', $src );
				$video_tab->removeAttribute( 'src' );
			}
		}

		//return $html_dom->saveHTML( $html_dom->documentElement );
		$content_return = $html_dom->saveHTML();


		if ( ! empty( $script_matches ) && ! empty( $script_matches[0][0] ) ) {
			foreach ( $script_matches[0] as $index => $script_js ) {
				$content_return = str_replace( '<!--{BREEZE_SCRIPT_PH' . $index . '}-->', $script_js, $content_return );
			}
		}

		return $content_return;

	}

	/**
	 * We need to exclude some images with very specific functionality.
	 * Example of excluded: Captcha, WooCommerce placeholder image.
	 *
	 * @param string $image_url The image full URL path.
	 *
	 * @return bool
	 * @since 1.2.0
	 * @access private
	 */
	private function excluded_images( $image_url = '' ) {
		$excluded_images_by_url = apply_filters(
			'breeze_excluded_images_url',
			array(
				'wpcf7_captcha/', // Contact Form 7 - Really Simple CAPTCHA.
				'woocommerce/assets/images/placeholder.png',
			)
		);

		if ( ! empty( $excluded_images_by_url ) ) {
			foreach ( $excluded_images_by_url as $partial_path ) {
				if ( false !== strpos( $image_url, $partial_path ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Generate a simple svg placeholder image.
	 *
	 * @param int $width Original image width.
	 * @param int $height Original image height.
	 *
	 * @return string
	 * @access private
	 * @since 1.2.0
	 */
	private function generate_simple_placeholder( $width = 0, $height = 0 ) {
		if ( ! is_numeric( $width ) ) {
			$width = 0;
		}

		if ( ! is_numeric( $height ) ) {
			$height = 0;
		}

		if ( ! empty( $width ) ) {
			$width = absint( $width );
		}

		if ( ! empty( $height ) ) {
			$height = absint( $height );
		}

		return "data:image/svg+xml;utf8,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20{$width}%20{$height}'%3E%3C/svg%3E";
	}
}

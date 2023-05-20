<?php
namespace Elementor_Real3D_Flipbook;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/real3d-flipbook.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Real3D_Flipbook() );
	}

	public function f_after_register_scripts() {

		//enqueue flipbook scripts 
		if (\Elementor\Plugin::instance()->editor->is_edit_mode() || \Elementor\Plugin::instance()->preview->is_preview_mode()) {

            if (!wp_script_is( 'real3d-flipbook', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook");
		     }

		     if (!wp_script_is( 'real3d-flipbook-book3', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-book3");
		     }

		     if (!wp_script_is( 'real3d-flipbook-bookswipe', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-bookswipe");
		     }

		     if (!wp_script_is( 'real3d-flipbook-iscroll', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-iscroll");
		     }

		     if (!wp_script_is( 'real3d-flipbook-threejs', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-threejs");
		     }
		     if (!wp_script_is( 'real3d-flipbook-webgl', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-webgl");
		     }

		     if (!wp_script_is( 'real3d-flipbook-pdfjs', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-pdfjs");
		     }

		     if (!wp_script_is( 'real3d-flipbook-pdfworkerjs', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-pdfworkerjs");
		     }

		     if (!wp_script_is( 'real3d-flipbook-pdfservice', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-pdfservice");
		     }

		     if (!wp_script_is( 'real3d-flipbook-embed', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-embed");
		     }

		     if (!wp_style_is( 'real3d-flipbook-style', 'enqueued' )) {
		     	wp_enqueue_style("real3d-flipbook-style");
		     }

		     if (!wp_style_is( 'real3d-flipbook-font-awesome', 'enqueued' )) {
		     	wp_enqueue_style("real3d-flipbook-font-awesome");
		     }

			
        }

	}


	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'f_after_register_scripts' ] );
		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

	}
}

// Instantiate Plugin Class
Plugin::instance();

<?php
namespace Elementor_Real3D_Flipbook\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Real3D_Flipbook extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'real3d-flipbook';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Real3D Flipbook', 'elementor-real3d-flipbook' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		// return 'eicon-posts-ticker';
		return 'eicon-document-file';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elementor-real3d-flipbook' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'General', 'elementor-real3d-flipbook' ),
			]
		);

		$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
        $names_array = array();
        foreach ($real3dflipbooks_ids as $id) {
          $book = get_option('real3dflipbook_'.$id);
          $name = $book['name'];
          $names_array[$id] = __($name, 'elementor-real3d-flipbook');
        }

		$this->add_control(
			'real3d_flipbook_id',
			[
				'label' => __( 'Select Flipbook', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $names_array
			]
		);

		$this->add_control(
			'real3d_flipbook_mode',
			[
				'label' => __( 'Embed mode', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'normal' => __('Normal (boxed)', 'elementor-real3d-flipbook'),
					'lightbox' => __('Lightbox', 'elementor-real3d-flipbook'),
					'fullscreen' => __('Fullscreen', 'elementor-real3d-flipbook'),
				]
			]
		);

		$this->add_control(
			'real3d_flipbook_view_mode',
			[
				'label' => __( 'View mode', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'webgl' => __('WebGL', 'elementor-real3d-flipbook'),
					'3d' => __('3D', 'elementor-real3d-flipbook'),
					'2d' => __('2D', 'elementor-real3d-flipbook'),
					'swipe' => __('Swipe', 'elementor-real3d-flipbook'),
				]
			]
		);


		$this->add_control(
			'real3d_flipbook_pdf',
			[
				'label' => esc_html__( 'PDF URL', 'elementor-real3d-flipbook' ),
				'description' => __( 'PDF file for flipbook', 'elementor-real3d-flipbook' ),
				'type' => Controls_Manager::MEDIA,
				// 'default' => [
				// 	'url' => null,
				// ],
				// 'render_type'        => 'none',
				// 'frontend_available' => true, 
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_lightbox',
			[
				'label' => __( 'Lightbox', 'elementor-real3d-flipbook' ),
			]
		);


		$this->add_control(
			'real3d_flipbook_lightboxcssclass',
			[
				'label' => __( 'CSS class', 'elementor-real3d-flipbook' ),
				'type' => Controls_Manager::TEXT,
				'description' => __('click on any element withh this CSS class will trigger lightbox', 'elementor-real3d-flipbook'),
			]
		);

		$this->add_control(
			'real3d_flipbook_lightboxtext',
			[
				'label' => __( 'Text', 'elementor-real3d-flipbook' ),
				'type' => Controls_Manager::TEXT,
				'description' => __('click on text will trigger lightbox', 'elementor-real3d-flipbook'),
			]
		);

		$this->add_control(
			'real3d_flipbook_lightboxthumbnail',
			[
				'label' => __( 'Thumbnail URL', 'elementor-real3d-flipbook' ),
				'type' => Controls_Manager::TEXT,
				'description' => __('click on thumbnail will trigger lightbox', 'elementor-real3d-flipbook'),
			]
		);

		$this->end_controls_section();




		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'elementor-real3d-flipbook' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_transform',
			[
				'label' => __( 'Text Transform', 'elementor-real3d-flipbook' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'elementor-real3d-flipbook' ),
					'uppercase' => __( 'UPPERCASE', 'elementor-real3d-flipbook' ),
					'lowercase' => __( 'lowercase', 'elementor-real3d-flipbook' ),
					'capitalize' => __( 'Capitalize', 'elementor-real3d-flipbook' ),
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'text-transform: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$book_id = $settings['real3d_flipbook_id'];
		$book_mode = $settings['real3d_flipbook_mode'];
		$book_view_mode = $settings['real3d_flipbook_view_mode'];
		$book_pdf = $settings['real3d_flipbook_pdf']['url'];
		$book_lightboxtext = $settings['real3d_flipbook_lightboxtext'];
		$book_lightboxthumbnail = $settings['real3d_flipbook_lightboxthumbnail'];
		$book_lightboxcssclass = $settings['real3d_flipbook_lightboxcssclass'];

        $flipbook_shortcode = '[real3dflipbook id="'.$book_id.'" ';
        if($book_mode != '')
        	$flipbook_shortcode .= 'mode="'.strtolower($book_mode).'" ';
        if($book_view_mode != '')
        	$flipbook_shortcode .= 'viewmode="'.$book_view_mode.'" ';
        if($book_pdf != '')
        	$flipbook_shortcode .= 'pdf="'.$book_pdf.'" ';
        if($book_lightboxtext != '')
        	$flipbook_shortcode .= 'lightboxtext="'.$book_lightboxtext.'" ';
        if($book_lightboxthumbnail != '')
        	$flipbook_shortcode .= 'thumb="'.$book_lightboxthumbnail.'" ';
        if($book_lightboxcssclass != '')
        	$flipbook_shortcode .= 'lightboxcssclass="'.$book_lightboxcssclass.'" ';

        $flipbook_shortcode .= ']';

		echo '<div class="real3dflipbook-elementor">';
		echo do_shortcode($flipbook_shortcode);
		echo '</div>';

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		// $settings = $this->get_settings_for_display();
		// $book_id = $settings['real3d_flipbook_id'];
		// $book_mode = $settings['real3d_flipbook_mode'];
		// $book_view_mode = $settings['real3d_flipbook_view_mode'];
		// $book_pdf = $settings['real3d_flipbook_pdf'];
		// $book_lightboxtext = $settings['real3d_flipbook_lightboxtext'];
		// $book_lightboxthumbnail = $settings['real3d_flipbook_lightboxthumbnail'];
		// $book_lightboxcssclass = $settings['real3d_flipbook_lightboxcssclass'];

  //       $flipbook_shortcode = '[real3dflipbook id="'.$book_id.'" ';
  //       if($book_mode != '')
  //       	$flipbook_shortcode .= 'mode="'.$book_mode.'" ';
  //       if($book_view_mode != '')
  //       	$flipbook_shortcode .= 'viewmode="'.$book_view_mode.'" ';
  //       if($book_pdf != '')
  //       	$flipbook_shortcode .= 'pdf="'.$book_pdf.'" ';
  //       if($book_lightboxtext != '')
  //       	$flipbook_shortcode .= 'lightboxtext="'.$book_lightboxtext.'" ';
  //       if($book_lightboxthumbnail != '')
  //       	$flipbook_shortcode .= 'thumb="'.$book_lightboxthumbnail.'" ';
  //       if($book_lightboxcssclass != '')
  //       	$flipbook_shortcode .= 'lightboxcssclass="'.$book_lightboxcssclass.'" ';

  //       $flipbook_shortcode .= ']';

		?>
		<div class="real3d-flipbook-id">
			[real3dflipbook id="{{{ settings.real3d_flipbook_id }}}"]
		</div>
		<?php
	}
}

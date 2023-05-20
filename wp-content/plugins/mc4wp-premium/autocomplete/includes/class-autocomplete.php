<?php

/**
 * Class MC4WP_Autocomplete
 *
 * @ignore
 */
class MC4WP_Autocomplete {

	/**
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * @var bool Is the script enqueued already?
	 */
	protected $is_script_enqueued = false;

	/**
	 * @param string $plugin_file
	 */
	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	public function add_hooks() {
		add_filter( 'mc4wp_form_css_classes', array( $this, 'form_css_classes' ), 10, 2 );
		add_filter( 'mc4wp_form_settings', array( $this, 'form_settings' ) );
		add_action( 'mc4wp_output_form', array( $this, 'maybe_enqueue_script' ) );
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function form_settings( $settings ) {
		$defaults = array(
			'autocomplete' => 0
		);
		return array_merge( $defaults, $settings );
	}

	/**
	 * @param array $classes
	 * @param MC4WP_Form $form
	 *
	 * @return array
	 */
	public function form_css_classes( $classes, MC4WP_Form $form ) {
		if ( $form->settings['autocomplete'] ) {
			$classes[] = 'autocomplete';
		}

		return $classes;
	}

	/**
	 * Enqueues the AJAX script whenever a form is outputted with AJAX enabled.
	 *
	 * This also fetches the "general error" text of the first form it encounters with AJAX enabled. Not optimal, but does the trick.
	 *
	 * @param MC4WP_Form $form
	 */
	public function maybe_enqueue_script( MC4WP_Form $form ) {
		if ( ! $form->settings['autocomplete'] || $this->is_script_enqueued ) {
			return;
		}

		wp_enqueue_style( 'mc4wp-autocomplete', plugins_url( '/assets/css/autocomplete.css', $this->plugin_file ), array(), MC4WP_PREMIUM_VERSION );
		wp_enqueue_script( 'mc4wp-autocomplete', plugins_url( '/assets/js/autocomplete.js', $this->plugin_file ), array(), MC4WP_PREMIUM_VERSION, true );

		//Order decides suggestion priority, most common names on top.
		$domains = array(
			"gmail.com",
			"yahoo.com",
			"hotmail.com",
			"aol.com",
			"hotmail.co.uk",
			"hotmail.fr",
			"msn.com",
			"yahoo.fr",
			"wanadoo.fr",
			"orange.fr",
			"comcast.net",
			"yahoo.co.uk",
			"yahoo.com.br",
			"yahoo.co.in",
			"live.com",
			"rediffmail.com",
			"free.fr",
			"gmx.de",
			"web.de",
			"yandex.ru",
			"ymail.com",
			"libero.it",
			"outlook.com",
			"uol.com.br",
			"bol.com.br",
			"mail.ru",
			"cox.net",
			"hotmail.it",
			"sbcglobal.net",
			"sfr.fr",
			"live.fr",
			"verizon.net",
			"live.co.uk",
			"googlemail.com",
			"yahoo.es",
			"ig.com.br",
			"live.nl",
			"bigpond.com",
			"terra.com.br",
			"yahoo.it",
			"neuf.fr",
			"yahoo.de",
			"alice.it",
			"rocketmail.com",
			"att.net",
			"laposte.net",
			"facebook.com",
			"bellsouth.net",
			"yahoo.in",
			"hotmail.es",
			"charter.net",
			"yahoo.ca",
			"yahoo.com.au",
			"rambler.ru",
			"hotmail.de",
			"tiscali.it",
			"shaw.ca",
			"yahoo.co.jp",
			"sky.com",
			"earthlink.net",
			"optonline.net",
			"freenet.de",
			"t-online.de",
			"aliceadsl.fr",
			"virgilio.it",
			"home.nl",
			"qq.com",
			"telenet.be",
			"me.com",
			"yahoo.com.ar",
			"tiscali.co.uk",
			"yahoo.com.mx",
			"voila.fr",
			"gmx.net",
			"mail.com",
			"planet.nl",
			"tin.it",
			"live.it",
			"ntlworld.com",
			"arcor.de",
			"yahoo.co.id",
			"frontiernet.net",
			"hetnet.nl",
			"live.com.au",
			"yahoo.com.sg",
			"zonnet.nl",
			"club-internet.fr",
			"juno.com",
			"optusnet.com.au",
			"blueyonder.co.uk",
			"bluewin.ch",
			"skynet.be",
			"sympatico.ca",
			"windstream.net",
			"mac.com",
			"centurytel.net",
			"chello.nl",
			"live.ca",
			"aim.com",
			"bigpond.net.au",
			"mc4wp.com"
		);

		$domains = (array) apply_filters( 'mc4wp_forms_email_domain_suggestions', $domains );

		$vars = array(
			'domains' => $domains,
		);
		wp_localize_script( 'mc4wp-autocomplete', 'mc4wp_autocomplete_vars', $vars );
		$this->is_script_enqueued = true;
	}
}

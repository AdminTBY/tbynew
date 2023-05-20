<?php
/**
 * CF7_File_Download
 *
 * @package CF7_File_Download\Classes
 * @version  1.0
 */


defined( 'ABSPATH' ) || exit;


class CF7_File_Download {

	public function __construct() {
		// Hook to add download file logic
		add_action( 'wp_footer', array($this,'add_download_catalog_logic'));
		// Hook to add scripts and styles
		add_action('admin_enqueue_scripts', array( $this, 'add_admin_scripts' ));
		// Hook to add settings page
		add_action( 'admin_init', array($this,'init_settings' ));
		// Hook to add admin menu attaching the settings page on hook above
		add_action( 'admin_menu', array($this,'cf7fd_admin_menu' ));
		// Load a text domain
		add_action( 'plugins_loaded', array($this,'load_plugin_textdomain') );


	}
	/**
	* Load text domain
	* @since  1.0
	* @return null
	*/
	public function load_plugin_textdomain(){
		    load_plugin_textdomain( 'cf7-file-download', FALSE, CF7FD_PLUGIN_DIR . '/languages/' );

	}
	/**
	* Add setting to admin menu
	* @since  1.0
	* @return null
	*/
	public function cf7fd_admin_menu(){

		 add_menu_page(
	        __( 'CF7 File Download', 'cf7-file-download' ),
	        __( 'CF7 File Download', 'cf7-file-download' ),
	        'manage_options',
	        'cf7fd-page',
	        array($this,'cf7fd_page_content'),
	        'dashicons-schedule',
	        10
    	);

	}
	/**
	* Initialize and Register Settings
	* @since  1.0
	* @return null
	*/
	public function init_settings($input){

		 add_settings_section(
	        'cf7fd_page_setting_section',
	        __( 'File Download Settings', 'cf7-file-download' ),
	        array($this,'cf7d_form_settings_markup'),
	        'cf7fd-page'
    	);
		

		register_setting( 'cf7fd-page', 'cf7fd_settings_options' );
 
		


	}
	/**
	* Fields Markup
	* @since 1.0
	* @return null
	*/
	public function cf7d_form_settings_markup() {
		
		$form_id=isset(get_option( 'cf7fd_settings_options' )['id']) ? get_option( 'cf7fd_settings_options' )['id']  : '';
		$file_url=isset(get_option( 'cf7fd_settings_options' )['url']) ? get_option( 'cf7fd_settings_options' )['url']   : '';
		$file_name=isset(get_option( 'cf7fd_settings_options' )['filename']) ? get_option( 'cf7fd_settings_options' )['filename']   : '';
		$form_settings=get_option( 'cf7fd_settings_options' ,array());
		ob_start();
				include CF7FD_PLUGIN_DIR.'templates/form-template.php';
		echo ob_get_clean();
	   

	}
	
	/** Add settings page content
	* @since 1.0
	* @return null
	*/
	public function cf7fd_page_content(){
		 ?>
	    <h1> <?php esc_html_e( 'Contact Form 7 File Download Settings', 'cf7-file-download' ); ?> </h1>
	    <?php 
	    // Admin notices for errors and successfuly update
	    settings_errors(); 
	    ?>
	    <form method="POST" action="options.php">
	    <?php
	    settings_fields( 'cf7fd-page' );
	    do_settings_sections( 'cf7fd-page' );
	    submit_button();
	    ?>
	    </form>
	    <?php
	}
	/**
	* Setting section call back
	* @since 1.0
	* @return null
	*/
	public function cf7fd_section_callback_function() {
		?>

		 <p> <?php esc_html_e( 'Set the contact form ID and the attachment url to be downloaded', 'cf7-file-download' ); ?> </p>


		 <?php
		
	}
	
	/**
	* CF7 Download catalog logic
	* @since  1.0
	* @return null
	*/
	public function add_download_catalog_logic(){
		$catalog_url=get_option( 'cf7fd_settings_options' )['url'];
		$contactform_id=(int) get_option( 'cf7fd_settings_options' )['id'];
		$filename=get_option( 'cf7fd_settings_options' )['filename'];
		$form_settings=get_option( 'cf7fd_settings_options' ,array());
		if (  isset( $form_settings['forms'])){
			echo '<script>';
			$index=1;
			foreach ( $form_settings['forms'] as $p ) {

				?>


				
						document.addEventListener( 'wpcf7mailsent', function( event ) {
							
							if ( <?php echo $p['id'];?> == event.detail.contactFormId ){
								jQuery('body').append('<a id="cf7fd-attachment-link<?php echo $p['id'];?><?php echo $index;?>" href="<?php echo $p['url'];?>" download="<?php echo $p['name'];?>"></a>');
								jQuery('#cf7fd-attachment-link<?php echo $p['id'];?><?php echo $index;?>')[0].click();

								setTimeout(function(){
									jQuery('#cf7fd-attachment-link<?php echo $p['id'];?><?php echo $index;?>').remove();
								},2000);
							}


						}, false );
					


			<?php
			$index=$index+1;
			       
			    } 
    		echo "</script>";

		}
		
		?>
		
		<script>
			document.addEventListener( 'wpcf7mailsent', function( event ) {
				if ( <?php echo $contactform_id;?> == event.detail.contactFormId ){
					jQuery('body').append('<a id="cf7fd-attachment-link" href="<?php echo $catalog_url;?>" download="<?php echo $filename;?>"></a>');
					jQuery('#cf7fd-attachment-link')[0].click();

					setTimeout(function(){
						jQuery('#cf7fd-attachment-link').remove();
					},2000);
				}


			}, false );
		</script>






		<?php




	}
	/**
	* Register and enqueue scripts
	* @since  1.0
	* @return null
	*/
	public function add_admin_scripts(){

		wp_register_script( 'octal-marblize-script', CF7FD_PLUGIN_URL . 'assets/scripts.js', array(
								'jquery'
			), false, true );

		wp_enqueue_script('octal-marblize-script');
		wp_enqueue_style( 'octal-marblize-style', CF7FD_PLUGIN_URL . 'assets/styles.css' );

	}



}

?>
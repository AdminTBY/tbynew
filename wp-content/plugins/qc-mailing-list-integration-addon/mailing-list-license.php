<?php
// Exit if accessed directly
if (!defined('ABSPATH')){
	exit; 
}

/**
 * Help & License Submenu
 */
if( !class_exists('QC_mailing_list_Help_License_Sub_Menu') ){

	class QC_mailing_list_Help_License_Sub_Menu
	{
		
		function __construct()
		{
			add_action('admin_menu', array($this, 'help_license_submenu') );
		}

		function help_license_submenu(){
			add_submenu_page( 
		        'qc-mailing-list-integration',
		        esc_html__('Help & License', 'qc-mailing-list-integration'),
		        esc_html__('Help & License', 'qc-mailing-list-integration'),
		        'manage_options',
		        'qc-mailing-list-help-license',
		        array($this, 'qcld_mailing_list_help_license_callback')
		    );
		}

		function qcld_mailing_list_help_license_callback(){
?>
			<div id="wrap">
				<div id="licensing" class="qcld_ml_section_box qcld_ml_bg_white qcld_ml_py_20 qcld_ml_px_15 qcld_pd_20">
						<h1 class="qcld_ml_box_title"><?php esc_html_e('Please Insert your license Key', 'qc-mailing-list-integration'); ?></h1>
					<div class="qcld-mailing_list-section-block">
						<?php if( get_mailing_list_valid_license() ){ ?>
							<div class="qcld-success-notice">
								<p><?php esc_html_e('Thank you, Your License is active', 'qc-mailing-list-integration'); ?></p>
							</div>
						<?php } ?>

						<?php
						
							$track_domain_request = wp_remote_get(mailing_list_LICENSING_PRODUCT_DEV_URL."wp-json/qc-domain-tracker/v1/getdomain/?license_key=".get_mailing_list_licensing_key());
							if( !is_wp_error( $track_domain_request ) || wp_remote_retrieve_response_code( $track_domain_request ) === 200 ){
								$track_domain_result = json_decode($track_domain_request['body']);
								
								if( !empty($track_domain_result) ){
									$max_domain_num = intval($track_domain_result[0]->max_domain) + 1;
									$total_domains = json_decode($track_domain_result[0]->domain, true);
									$total_domains_num = count($total_domains);

									if( $max_domain_num <= $total_domains_num){
								?>
										<div class="qcld-error-notice">
											<p><?php esc_html_e('You have activated this key for maximum number of sites allowed by your license. Please', 'qc-mailing-list-integration'); ?> <a href='<?php echo esc_url("https://www.quantumcloud.com/products/"); ?>'><?php esc_html_e('purchase additional license.', 'qc-mailing-list-integration'); ?></a></p>
										</div>
								<?php
									}
								}
								
							}
						?>
						
						<form onsubmit="return false" id="qc-license-form" method="post" action="options.php">
							<?php
								delete_mailing_list_update_transient();
								delete_mailing_list_renew_transient();
								
								delete_option('_site_transient_update_plugins');
								settings_fields( 'qcld_mailing_list_license' );
								do_settings_sections( 'qcld_mailing_list_license' );

							?>
							<table class="form-table">

								<tr id="quantumcloud_portfolio_license_row" class="qcwbes_d_none">
									<th>
										<label for="qcld_mailing_list_enter_license_key"><?php esc_html_e('Enter License Key:', 'qc-mailing-list-integration'); ?></label>
									</th>
									<td>
										<input type="<?php echo esc_attr(get_mailing_list_licensing_key()!=''?'password':'text'); ?>" id="qcld_mailing_list_enter_license_key" name="qcld_mailing_list_enter_license_key" class="regular-text" value="<?php echo esc_attr(get_mailing_list_licensing_key()); ?>">
										<p><?php esc_html_e('You can copy the license key from', 'qc-mailing-list-integration'); ?> <a target="_blank" href='<?php echo esc_url("https://www.quantumcloud.com/products/account/"); ?>'><?php esc_html_e('your account', 'qc-mailing-list-integration'); ?></a></p>
									</td>
								</tr>

								<tr id="show_envato_plugin_downloader" class="qcwbes_d_none">
									<th>
										<label for="qcld_mailing_list_enter_envato_key"><?php esc_html_e('Enter Purchase Code:', 'qc-mailing-list-integration'); ?></label>
									</th>
									<td colspan="4">
										<input type="<?php echo esc_attr(get_mailing_list_envato_key()!=''?'password':'text'); ?>" id="qcld_mailing_list_enter_envato_key" name="qcld_mailing_list_enter_envato_key" class="regular-text" value="<?php echo esc_attr(get_mailing_list_envato_key()); ?>">
										<p><?php esc_html_e('You can install the', 'qc-mailing-list-integration'); ?> <a target="_blank" href="<?php echo esc_url('https://envato.com/market-plugin/'); ?>"><?php esc_html_e('Envato Plugin', 'qc-mailing-list-integration'); ?></a> <?php esc_html_e('to stay up to date.', 'qc-mailing-list-integration'); ?></p>
									</td>
								</tr>

			                    <tr>
			                        <th>
			                            <label for="qcld_mailing_list_enter_license_or_purchase_key"><?php esc_html_e('Enter License Key or Purchase Code:', 'qc-mailing-list-integration'); ?></label>
			                        </th>
			                        <td>
			                            <input type="<?php echo esc_attr(get_mailing_list_license_purchase_code()!=''?'password':'text'); ?>" id="qcld_mailing_list_enter_license_or_purchase_key" name="qcld_mailing_list_enter_license_or_purchase_key" class="regular-text" value="<?php echo esc_attr(get_mailing_list_license_purchase_code()); ?>" required>
			                        </td>
			                    </tr>

							</table>

			                <input type="hidden" name="qcld_mailing_list_buy_from_where" value="<?php echo esc_attr(get_mailing_list_licensing_buy_from()); ?>" >

							<?php submit_button(); ?>
						</form>
					</div>
				</div>

				<div id="qcld_ml_mailchimp_section" class="qcld_ml_section_box qcld_ml_bg_white qcwbmc_mt_35 qcld_ml_py_20 qcld_ml_px_15 qcld_pd_20">
					<h2 class="qcld_ml_box_title"><?php esc_html_e('Mailchimp API Key', 'qc-mailing-list-integration'); ?></h2>
					<div class="qcld-mailing_list-section-block qcwbmc_mt_20 qcld_ml_font_size_16">
						<p>
							<?php esc_html_e('Visit', 'qc-mailing-list-integration'); ?>
							<a href='<?php echo esc_url("https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key"); ?>' target='_blank'><?php esc_html_e('this link', 'qc-mailing-list-integration'); ?></a>
							<?php esc_html_e('to know how to get mailchimp API Key.', 'qc-mailing-list-integration'); ?>
						</p>
					</div>
				</div>

				<div id="qcld_ml_zapier_section" class="qcld_ml_section_box qcld_ml_bg_white qcwbmc_mt_35 qcld_ml_py_20 qcld_ml_px_15 qcld_pd_20">
					<h2 class="qcld_ml_box_title"><?php esc_html_e('Zapier Webhook Integration', 'qc-mailing-list-integration'); ?></h2>
					<div class="qcld-mailing_list-section-block qcwbmc_mt_20 qcld_ml_font_size_16">
						<ul>
							<li>
								<span><?php esc_html_e('Go to your Zap', 'qc-mailing-list-integration'); ?></span>
							</li>
							<li>
								<span><?php esc_html_e('Click on the "Choose App" field and Search for the', 'qc-mailing-list-integration'); ?> <strong><?php esc_html_e('"Webhooks by Zapier"', 'qc-mailing-list-integration'); ?></strong> <?php esc_html_e('app and Select it', 'qc-mailing-list-integration'); ?></span>
								<div class="qcld_mlimg_box">
									<img src="<?php echo esc_url(QCLD_MAILING_LIST_INTEGRATION_ADDON_URL.'/admin/assets/images/setup-zap-app.png'); ?>" alt="" width="663" height="472">
								</div>
							</li>
							<li>
								<span><?php esc_html_e('Click on the', 'qc-mailing-list-integration'); ?> <strong><?php esc_html_e('"Choose Trigger Event"', 'qc-mailing-list-integration'); ?></strong> <?php esc_html_e('field and select the "Catch Hook"', 'qc-mailing-list-integration'); ?></span>
								<div class="qcld_mlimg_box">
									<img src="<?php echo esc_url(QCLD_MAILING_LIST_INTEGRATION_ADDON_URL.'/admin/assets/images/setup-zap-app-catch.png'); ?>" alt="" width="661" height="464">
								</div>
							</li>
							<li>
								<span><?php esc_html_e('Go to Next Step by Click the', 'qc-mailing-list-integration'); ?> <strong><?php esc_html_e('"Continue"', 'qc-mailing-list-integration'); ?></strong> <?php esc_html_e('button and copy the Webhook Url from', 'qc-mailing-list-integration'); ?> <strong><?php esc_html_e('"Custom Webhook URL"', 'qc-mailing-list-integration'); ?></strong> <?php esc_html_e('field', 'qc-mailing-list-integration'); ?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
<?php
		}
	}
}
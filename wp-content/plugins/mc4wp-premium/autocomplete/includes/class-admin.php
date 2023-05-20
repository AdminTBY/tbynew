<?php

class MC4WP_Autocomplete_Admin {

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'mc4wp_admin_form_after_behaviour_settings_rows', array( $this, 'show_setting' ), 10, 2 );
	}

	/**
	 * @param            $opts
	 * @param MC4WP_Form $form
	 */
	public function show_setting( $opts, $form ) {
		?>
        <tr valign="top">
            <th scope="row"><?php esc_html_e( 'Enable autocomplete for email domains?', 'mailchimp-for-wp' ); ?></th>
            <td>
                <label>
                    <input type="radio" name="mc4wp_form[settings][autocomplete]"
                           value="1" <?php checked( $opts['autocomplete'], 1 ); ?> />&rlm;
					<?php _e( 'Yes' ); ?>
                </label> &nbsp;
                <label>
                    <input type="radio" name="mc4wp_form[settings][autocomplete]"
                           value="0" <?php checked( $opts['autocomplete'], 0 ); ?> />&rlm;
					<?php _e( 'No' ); ?>
                </label> &nbsp;
                <p class="description"><?php esc_html_e( 'Select "yes" if you want to enable autocomplete for common email domains.', 'mailchimp-for-wp' ); ?></p>
            </td>
        </tr>
		<?php
	}
}

<?php
/**
 * Code For Event Detail Contact Section
 *
 * @package tbyrefresh
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php

$event_contact_section_details = get_field('event_contact_section_details', 'option');
if( $event_contact_section_details ): ?>
    <div class="contact-information">
        <div class="block-inner">
            <h5 class="sub-title text-black font-bold text-uppercase"><?php echo $event_contact_section_details['contact_title']; ?></h5>
            <div class="street-address-event description font-regular text-black">
            <?php echo $event_contact_section_details['contact_company_information']; ?>
            </div>
        </div>
        <a target="_blank" href="mailto:<?php echo $event_contact_section_details['contact_email_address']; ?>">Email Us</a>
    </div>
<?php endif; ?>

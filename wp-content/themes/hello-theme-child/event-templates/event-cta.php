<?php
/**
 * Code For Event Detail CTA
 *
 * @package tbyrefresh
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>


<div class="event-detail-cta">
    <div class="product-subscribe-bar">
        <div class="event-detail-cta cta-heading font-bold text-white text-uppercase">
            <?php echo the_field('event_cta_heading', 'option'); ?>
        </div>

        <div class="event-detail-cta cta-subheading font-medium text-white title">
            <?php echo the_field('event_cta_sub_heading', 'option'); ?>
        </div>

        <div class="cta-btn event-detail-btn">
            <a data-fancybox data-src="#mailchimp-form" href="javascript:;" class="elementor-button">
                <?php echo the_field('event_cta_button_text', 'option'); ?>
            </a>
        </div>

        <div style="display: none" id="mailchimp-form" class="event-detail-pop-form">
                <div class="logo">
                            <img src="<?php echo the_field('event_popup_logo', 'option'); ?>"
                                alt="logo" />
                            <span class="title text-black font-bold">
                                <?php echo the_field('event_popup_title', 'option'); ?>
                            </span>
                </div>

                <?php
                $mailchimp_popup_shortcode = get_field('event_detail_mailchimp_form_shortcode', 'option');
                echo do_shortcode($mailchimp_popup_shortcode);
                ?>
        </div>
    </div>
</div>
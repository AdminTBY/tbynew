<?php
/**
 * Become A Sponser Section on Event Detail Page
 *
 * @package tbyrefresh
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="become-sponsor">

    <div class="block-inner">
        <h5 class="sub-title text-black font-bold text-uppercase">BECOME A SPONSOR</h5>
        <div class="street-address-event description font-regular text-black">
            <?php echo the_field('event_detail_sponser_content', 'option'); ?>

        </div>
    </div>

    <a data-fancybox data-src="#modal-form" href="javascript:;" class="btn btn-primary"
        style="border: 'none';">
        <button class="modalbtn-eventdetail">
            <?php echo the_field('event_detail_sponser_button_text', 'option'); ?>
        </button>
    </a>

    <div style="display: none" id="modal-form" class="event-detail-pop-form">

        <div class="logo">
                    <img src="<?php echo the_field('event_popup_logo', 'option'); ?>"
                        alt="logo" />
                        <span class="title text-black font-bold">
                                <?php echo the_field('event_popup_title', 'option'); ?>
                        </span>
        </div>

        <p class="description font-regular text-grey">
            <?php echo the_field('event_popup_description', 'option'); ?>
        </p>

        <?php
        $popup_shortcode = get_field('event_form_shortcode', 'option');
        echo do_shortcode($popup_shortcode);
        ?>

    </div>

</div>
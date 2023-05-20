<?php
/**
 * The Sponser List on event Detail Page Data.
 *
 * @package tbyrefresh
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<div class="speaker-list pad5">
<div class="sponser-section">
<h2 class="sub-section-title text-black font-bold border-line m-t-0">Sponsors</h2>
<?php if (have_rows('event_sponsors_information')): ?>
<?php while (have_rows('event_sponsors_information')):

    the_row();
    $sponser_type = get_sub_field('sponser_type');
    ?>
            <h4 class="sub-title text-black font-bold text-uppercase"><?php echo $sponser_type; ?></h4>
            <div class="sponserby-list">
                        <?php if (have_rows('event_logo_with_url')):
                            while (have_rows('event_logo_with_url')):

                                the_row();
                                $event_logo = get_sub_field('event_logo');
                                $event_name = get_sub_field('event_name');
                                $event_sponser_abstract = get_sub_field('event_sponser_abstract');
                                $event_sponser_url = get_sub_field('event_sponser_url');
                                ?>
                           <?php if ($event_sponser_url) {

                               $link_url = $event_sponser_url['url'];
                               $link_title = $event_sponser_url['title'];
                               $link_target = $event_sponser_url['target'] ? $event_sponser_url['target'] : '_self';
                               ?>
                            <a class="individual-services" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                                <div class="services-image-div image-wrap">    

                                    <div class="sponserurl"><img src="<?php echo esc_url($event_logo['url']) ? $event_logo['url'] : get_stylesheet_directory_uri() . '' . "/assets/images/NoImageAvailable.png"; ?>"
                                            alt="<?php echo esc_attr($event_logo['alt']); ?>">
                                    </div>                                   

                                </div>

                                <div class="services-content-div">

                                    <h2 class="sub-title Brandon-text-medium text-black"><?php echo $event_name; ?>
                                    </h2>

                                    <div class="description Brandon-text-regular text-black">
                                        <?php echo $event_sponser_abstract; ?>
                                        <p></p>
                                    </div>

                                </div>

                           </a>
                            <?php
                           } ?>
                    <?php
                            endwhile;
                        endif; ?> 
            
            </div>

<?php $sponser_by_image_counter++;
endwhile;else:echo "No Sponsers Found.";endif; ?>
</div>
            <!-- Nested Repeater End -->
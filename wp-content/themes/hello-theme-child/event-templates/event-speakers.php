<?php
/**
 * The Speakers List on event Detail Page Data.
 *
 * @package tbyrefresh
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="speaker-list pad5">
            <div class="latest-article-wrapper">
            
                <h2 class="sub-section-title text-black font-bold border-line m-t-0">Speakers</h2>
            
                <div class=" latest-article-slider">
                    <div class="event-block-main-outer event-slider swiper-container">
                        <div class="swiper-wrapper">

    <?php if (have_rows('event_speakers')):
        $project_description_with_image_counter = 1;
        $speaker_id = 1;
        while (have_rows('event_speakers')):
            the_row(); ?>
                            <div class="article-block-inner swiper-slide" data-swiper-autoplay="2000">
								
                                <?php
                                $attachment_photo = get_sub_field('speaker_image');
                                $speaker_firstname = get_sub_field('firstname');
                                $speaker_lastname = get_sub_field('lastname');
                                $speaker_jobtitle = get_sub_field('jobtitle');
                                $speaker_company = get_sub_field('company');
                                $attachment_description = get_sub_field('biography');
                                $custom_class = get_sub_field('custom_class');
                                ?>

                                <div class="card-deck col-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <a data-fancybox data-src="#modal-<?php echo $speaker_id; ?>"
                                                href="javascript:;">
                                                <div class="row individual-services artcle-inner-content">

                                                    <div class="col-sm-6 col-12 services-image-div image-wrap">

                                                        <img src="<?php echo $attachment_photo['url'] ? $attachment_photo['url'] : get_stylesheet_directory_uri() . '' . "/assets/images/placeholder.png"; ?>"
                                                            alt="<?php echo esc_attr($attachment_photo['alt']); ?>">
                                                    </div>

                                                    <div class="col-sm-6 col-12 services-content-div">

                                                        <h2 class="sub-title font-bold text-black">
                                                            <?php echo $speaker_firstname . ' ' . $speaker_lastname; ?>
                                                        </h2>
                                                        <h2 class="sub-description font-medium text-grey">
                                                            <?php echo $speaker_jobtitle . ' ' . $speaker_company; ?>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </a>

                                            <!-- Popup Code start -->
                                            <div style="display: none" id="modal-<?php echo $speaker_id; ?>">
                                                <div class="services-modal-inner">
                                                    <div class="services-image-div image-wrap">
                                                        <img src="<?php echo $attachment_photo['url']; ?>"
                                                            alt="<?php echo esc_attr($attachment_photo['alt']); ?>">
                                                    </div>
                                                    <div class="inner-content">
                                                        <h2 class="sub-section-title font-bold text-red">
                                                            <?php echo $speaker_firstname . ' ' . $speaker_lastname; ?>
                                                        </h2>
                                                        <div class="sub-title font-medium text-grey">
                                                            <?php echo $speaker_jobtitle . ' </br> ' . $speaker_company; ?>
                                                        </div>

                                                        <div class="description font-medium text-grey">
                                                            <?php echo $attachment_description; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Popup Code end -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $project_description_with_image_counter++;
                            $speaker_id++;

        endwhile;
    else:
        echo "No Speakers Found.";
    endif; ?>
                        </div>

                    </div>
                    <?php if (have_rows('event_speakers')): ?>
                    <div class="swiper-button-next event-slider">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg"
                            alt="" />
                    </div>
                    <div class="swiper-button-prev event-slider">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-left.svg"
                            alt="" />
                    </div>
                    <div class="swiper-pagination event-slider"></div>
                <?php endif; ?>
                </div>
            </div>
        </div>
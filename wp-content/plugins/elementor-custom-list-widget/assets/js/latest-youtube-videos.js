jQuery(document).ready(function () {
  jQuery(".play-now-btn").on("click", function () {
    var youtube_video_url = jQuery(this).attr("data-video-url");
    var youtube_video_title = jQuery(this).attr("data-title");

    jQuery(".youtube-video-iframe").attr({
      src: youtube_video_url,
      title: youtube_video_title,
    });
    jQuery(".for-playing-now-msg.show_element").removeClass("show_element").addClass("hide_element");
    jQuery(".hide_element.play-now-btn").removeClass("hide_element").addClass("show_element");
    jQuery(this).addClass("hide_element").removeClass("show_element");
    jQuery(this).parent(".youtube-play-now-sec").find(".for-playing-now-msg.hide_element").removeClass("hide_element").addClass("show_element");
    jQuery(".img_border").removeClass("img_border");
    jQuery(this).parents(".youtube-videos-right-sec").find(".youtube-video-img img").addClass("img_border");
    jQuery(".youtube-video-title").removeClass("active-text");
    jQuery(this).parents(".youtube-videos-right-sec").find(".youtube-video-title").addClass("active-text");
  });
});

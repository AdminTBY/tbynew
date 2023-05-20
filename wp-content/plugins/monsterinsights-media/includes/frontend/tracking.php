<?php

function monsterinsights_media_output_after_script( $options ) {
	$track_user = monsterinsights_track_user();
	$ua         = monsterinsights_get_ua_to_output();
	$v4_id      = monsterinsights_get_v4_id_to_output();

	// Video
	$video_progress_tracking_marks = apply_filters( 'monsterinsights_media_video_tracking_marks', array( 10, 25, 50, 75 ) );
	// -----

	if ( $track_user && ( $ua || $v4_id ) ) {
		$attr_string = function_exists( 'monsterinsights_get_frontend_analytics_script_atts' )
			? monsterinsights_get_frontend_analytics_script_atts()
			: ' type="text/javascript" data-cfasync="false"';

		ob_start();
		echo PHP_EOL;
		?>

<!-- MonsterInsights Media Tracking -->
<script<?php echo $attr_string; ?>>
    var monsterinsights_tracked_video_marks = {};
    var monsterinsights_youtube_percentage_tracking_timeouts = {};

    // Works for YouTube and Vimeo
    function monsterinsights_media_get_id_for_iframe( source, service ) {
    var iframeUrlParts = source.split('?');
    var stripedUrl = iframeUrlParts[0].split('/');
    var videoId = stripedUrl[ stripedUrl.length - 1 ];

    return service + '-player-' + videoId;
    }

    function monsterinsights_media_record_video_event( provider, event, label, parameters = {} ) {

    __gtagTracker('event', event, {
    event_category: 'video-' + provider,
    event_label: label,
    non_interaction: event === 'impression',
    ...parameters
    });
    }

    function monsterinsights_media_maybe_record_video_progress( provider, label, videoId, videoParameters ) {
    var progressTrackingAllowedMarks = <?php echo esc_html( json_encode( $video_progress_tracking_marks ) ); ?>;

    if ( typeof monsterinsights_tracked_video_marks[ videoId ] == 'undefined' ) {
    monsterinsights_tracked_video_marks[ videoId ] = [];
    }

    var { video_percent } = videoParameters;

    if ( progressTrackingAllowedMarks.includes( video_percent ) && !monsterinsights_tracked_video_marks[ videoId
    ].includes( video_percent ) ) {
    monsterinsights_media_record_video_event( provider, 'video_progress', label, videoParameters );

    // Prevent multiple records for the same percentage
    monsterinsights_tracked_video_marks[ videoId ].push( video_percent );
    }
    }

    // --- Vimeo --- //

    function monsterinsights_on_vimeo_load() {
    var vimeoIframes = document.querySelectorAll("iframe[src*='vimeo']");

    vimeoIframes.forEach(function( iframe ) {
    var videoLabel = iframe.title || iframe.getAttribute('src');
    var player = new Vimeo.Player(iframe);
    var playerId = iframe.getAttribute('id');

    if ( !playerId ) {
    playerId = monsterinsights_media_get_id_for_iframe( iframe.getAttribute('src'), 'vimeo' );
    iframe.setAttribute( 'id', playerId );
    }

    var videoParameters = {
    video_provider: 'vimeo',
    video_title: iframe.title,
    video_url: iframe.getAttribute('src')
    };

    /**
    * Record Impression
    **/
    monsterinsights_media_record_video_event( 'vimeo', 'impression', videoLabel, videoParameters );

    /**
    * Record video start
    **/
    player.on('play', function(data) {
    videoParameters.video_duration = data.duration;
    videoParameters.video_current_time = data.seconds;
    videoParameters.video_percent = 0;

    monsterinsights_media_record_video_event( 'vimeo', 'video_start', videoLabel, videoParameters );
    });

    /**
    * Record video progress
    **/
    player.on('timeupdate', function(data) {
    var progress = Math.floor(data.percent * 100);

    videoParameters.video_duration = data.duration;
    videoParameters.video_current_time = data.seconds;
    videoParameters.video_percent = progress;

    monsterinsights_media_maybe_record_video_progress( 'vimeo', videoLabel, playerId, videoParameters );
    });

    /**
    * Record video complete
    **/
    player.on('ended', function(data) {
    videoParameters.video_duration = data.duration;
    videoParameters.video_current_time = data.seconds;
    videoParameters.video_percent = 100;

    monsterinsights_media_record_video_event( 'vimeo', 'video_complete', videoLabel, videoParameters );
    });
    });
    }

    function monsterinsights_media_init_vimeo_events() {
    var vimeoIframes = document.querySelectorAll("iframe[src*='vimeo']");

    if ( vimeoIframes.length ) {

    // Maybe load Vimeo API
    if ( window.Vimeo === undefined ) {
    var tag = document.createElement("script");
    tag.src = "https://player.vimeo.com/api/player.js";
    tag.setAttribute("onload", "monsterinsights_on_vimeo_load()");
    document.body.append(tag);
    } else {
    // Vimeo API already loaded, invoke callback
    monsterinsights_on_vimeo_load();
    }
    }
    }

    // --- End Vimeo --- //

    // --- YouTube --- //
    function monsterinsights_media_on_youtube_load() {

    var ga4Enabled = monsterinsights_frontend.v4_id !== '';
    var youtubeIframes = document.querySelectorAll("iframe[src*='youtube'],iframe[src*='youtu.be']");

    youtubeIframes.forEach(function( iframe ) {
    var videoLabel = iframe.title || iframe.getAttribute('src');
    var playerId = iframe.getAttribute('id');

    if ( !playerId ) {
    playerId = monsterinsights_media_get_id_for_iframe( iframe.getAttribute('src'), 'youtube' );
    iframe.setAttribute( 'id', playerId );
    }

    var videoParameters = {
    video_provider: 'youtube',
    video_title: iframe.title,
    video_url: iframe.getAttribute('src')
    };

    /**
    * Record Impression
    **/
    monsterinsights_media_record_video_event( 'youtube', 'impression', videoLabel, videoParameters );

    /**
    * Record progress callback
    **/
    function __track_youtube_video_progress( player, videoLabel, videoParameters ) {
    var { playerInfo } = player;

    var duration = playerInfo.duration//player.getDuration();
    var currentTime = playerInfo.currentTime//player.getCurrentTime();

    var percentage = (currentTime / duration) * 100;
    var progress = Math.floor(percentage);

    videoParameters.video_duration = duration;
    videoParameters.video_current_time = currentTime;
    videoParameters.video_percent = progress;

    monsterinsights_media_maybe_record_video_progress( 'youtube', videoLabel, playerId, videoParameters );
    }

    function __youtube_on_state_change( event ) {
    var state = event.data;
    var player = event.target;
    var { playerInfo } = player;

    /**
    * YouTube's API doesn't offer a progress or timeupdate event.
    * We have to track progress manually by asking the player for the current time, every X milliseconds, using an
    interval
    **/

    if ( state === YT.PlayerState.PLAYING) {

    /**
    * Record video start
    **/
    videoParameters.video_duration = playerInfo.duration;
    videoParameters.video_current_time = playerInfo.currentTime;
    videoParameters.video_percent = 0;

    monsterinsights_media_record_video_event( 'youtube', 'video_start', videoLabel, videoParameters );

    monsterinsights_youtube_percentage_tracking_timeouts[ playerId ] = setInterval(__track_youtube_video_progress, 500,
    player, videoLabel, videoParameters );
    } else if ( state === YT.PlayerState.PAUSED ) {
    // When the video is paused clear the interval
    clearInterval( monsterinsights_youtube_percentage_tracking_timeouts[ playerId ] );
    } else if ( state === YT.PlayerState.ENDED ) {

    /**
    * Record video complete
    **/
    videoParameters.video_duration = playerInfo.duration;
    videoParameters.video_current_time = playerInfo.currentTime;
    videoParameters.video_percent = 100;

    monsterinsights_media_record_video_event( 'youtube', 'video_complete', videoLabel, videoParameters );
    clearInterval( monsterinsights_youtube_percentage_tracking_timeouts[ playerId ] );
    }
    }

    new YT.Player(playerId, {
    events: {
    onStateChange: __youtube_on_state_change
    }
    });
    });
    }

    function monsterinsights_media_init_youtube_events() {
    var youtubeIframes = document.querySelectorAll("iframe[src*='youtube'],iframe[src*='youtu.be']");

    // YouTube always looks for a function called onYouTubeIframeAPIReady
    window.onYouTubeIframeAPIReady = monsterinsights_media_on_youtube_load;

    if ( youtubeIframes.length ) {

    // Maybe load YouTube API
    if ( window.YT === undefined ) {
    var tag = document.createElement("script");
    tag.src = "https://www.youtube.com/iframe_api";
    document.body.append(tag);
    } else {
    // YouTube API already loaded, invoke callback
    monsterinsights_media_on_youtube_load();
    }
    }
    }
    // --- End YouTube --- //

    // --- HTML Videos ---//
    function monsterinsights_media_init_html_video_events() {
    var videos = document.querySelectorAll('video');
    var videosCount = 0;

    videos.forEach(function( video ) {

    var videoLabel = video.title;

    if ( !videoLabel ) {
    var videoCaptionEl = video.nextElementSibling;

    if ( videoCaptionEl && videoCaptionEl.nodeName.toLowerCase() === 'figcaption' ) {
    videoLabel = videoCaptionEl.textContent;
    } else {
    videoLabel = video.getAttribute('src');
    }
    }

    var videoTitle = videoLabel;

    var playerId = video.getAttribute('id');

    if ( !playerId ) {
    playerId = 'html-player-' + videosCount;
    video.setAttribute('id', playerId);
    }

    var videoParameters = {
    video_provider: 'html',
    video_title: videoTitle,
    video_url: video.getAttribute('src')
    };

    /**
    * Record Impression
    **/
    monsterinsights_media_record_video_event( 'html', 'impression', videoLabel, videoParameters );

    /**
    * Record video start
    **/
    video.addEventListener('play', function() {
    videoParameters.video_duration = video.duration;
    videoParameters.video_current_time = video.currentTime;
    videoParameters.video_percent = 0;

    monsterinsights_media_record_video_event( 'html', 'video_start', videoLabel, videoParameters );
    }, false );

    /**
    * Record video progress
    **/
    video.addEventListener('timeupdate', function() {
    var percentage = (video.currentTime / video.duration) * 100;
    var progress = Math.floor(percentage);

    videoParameters.video_duration = video.duration;
    videoParameters.video_current_time = video.currentTime;
    videoParameters.video_percent = progress;

    monsterinsights_media_maybe_record_video_progress( 'html', videoLabel, playerId, videoParameters );
    }, false );

    /**
    * Record video complete
    **/
    video.addEventListener('ended', function() {
    var percentage = (video.currentTime / video.duration) * 100;
    var progress = Math.floor(percentage);

    videoParameters.video_duration = video.duration;
    videoParameters.video_current_time = video.currentTime;
    videoParameters.video_percent = progress;

    monsterinsights_media_record_video_event( 'html', 'video_complete', videoLabel, videoParameters );
    }, false );

    videosCount++;
    });
    }
    // --- End HTML Videos ---//

    function monsterinsights_media_init_video_events() {
    /**
    * HTML Video - Attach events & record impressions
    */
    monsterinsights_media_init_html_video_events();

    /**
    * Vimeo - Attach events & record impressions
    */
    monsterinsights_media_init_vimeo_events();

    /**
    * YouTube - Attach events & record impressions
    */
    monsterinsights_media_init_youtube_events();
    }

    /* Attach events */
    function monsterinsights_media_load() {

    if ( !__gtagTracker ) {
    setTimeout(monsterinsights_media_load, 200);
    return;
    }

    if ( window.addEventListener ) {
    window.addEventListener( "load", monsterinsights_media_init_video_events, false );
    } else if ( window.attachEvent ) {
    window.attachEvent( "onload", monsterinsights_media_init_video_events);
    }
    }

    monsterinsights_media_load();
    </script>
    <!-- End MonsterInsights Media Tracking -->

    <?php
		echo PHP_EOL;
		echo ob_get_clean();
	}
}

add_action( 'wp_head', 'monsterinsights_media_output_after_script', 16 );
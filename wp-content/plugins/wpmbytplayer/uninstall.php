<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

if(!$ytppro) {
    // Uninstall all the mbYTPlayer settings
    delete_option('mbYTPlayer_version');
    delete_option('mbYTPlayer_Home_is_active');
    delete_option('mbYTPlayer_home_video_url');
    delete_option('mbYTPlayer_home_video_page');
}
/*delete_option('mbYTPlayer_version');
delete_option('mbYTPlayer_Home_is_active');
delete_option('mbYTPlayer_home_video_url');
delete_option('mbYTPlayer_home_video_page');*/

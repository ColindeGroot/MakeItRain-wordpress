<?php
/*
Plugin Name: mb.YTPlayer for background videos
Plugin URI: https://pupunzi.open-lab.com/mb-jquery-components/jquery-mb-ytplayer/
Description: Play a Youtube video as background of your page. Go to <strong>mb.ideas > mb.YTPlayer</strong> to activate the background video option for your homepage.
Author: Pupunzi (Matteo Bicocchi)
Version: 3.3.6
Author URI: http://pupunzi.com
Text Domain: wpmbytplayer
*/

define("MBYTPLAYER_VERSION", "3.3.6");

// Set unique string for this site
function mbYTPlayer_get_domain(){
	$lic_domain = $_SERVER['HTTP_HOST'];
	if(!isset($lic_domain) || empty($lic_domain))
		$lic_domain = $_SERVER['SERVER_NAME'];
	if(!isset($lic_domain) || empty($lic_domain))
		$lic_domain = get_bloginfo('name');

	return $lic_domain;
}
$ytplayer_lic_domain = mbYTPlayer_get_domain();
$ytplayer_this_plugin = plugin_basename(__FILE__);

$ytplayer_plus_link = "https://pupunzi.com/wpPlus/go-plus.php?locale=" . get_locale() . "&plugin_prefix=YTPL&plugin_version=" . MBYTPLAYER_VERSION . "&lic_domain=" . $ytplayer_lic_domain . "&lic_theme=" . get_template() . "&php=" . phpversion();

function mbYTPlayer_get_price($plugin_prefix) {

    $url = 'https://pupunzi.com/wpPlus/controller.php';

    $data = array(
        'CMD' => 'GET-PRICE',
        'plugin_prefix' => $plugin_prefix,
    );

	$args = array(
	  'body' => $data,
	  'timeout' => '5',
	  'redirection' => '5',
	  'httpversion' => '1.0',
	  'blocking' => true,
	  'headers' => array(),
	  'cookies' => array()
	);

	$response = wp_remote_post($url, $args);

    if (empty($response) || !is_array($response)) {
      $response = array("result" => "OK", "COM" => "NA", "DEV" => "NA");
    } else {
      $response = $response['body'];
    }

    $res_array = json_decode($response, true);
    return $res_array;
}

if (version_compare(phpversion(), '5.6.0', '>')) {
    require('inc/mb_notice/notice.php');
    //$ytp_notice->reset_notice();
    $ytp_message = '<b>mb.YTPlayer</b>: <br>Go to Plus to get all the player features! ' . ' <a target="_blank" href="' . $ytplayer_plus_link . '">' . __('Get your <b>Plus</b> now!', 'wpmbytplayer') . '</a>';
    $ytp_notice = new mbideas_notice('mbYTPlayer', plugin_basename(__FILE__), MBYTPLAYER_VERSION);
    $ytp_notice->add_notice($ytp_message, 'success');
}

register_activation_hook(__FILE__, 'mbYTPlayer_install');
function mbYTPlayer_install()
{
// add and update our default options upon activation
    add_option('mbYTPlayer_version', MBYTPLAYER_VERSION);
    add_option('mbYTPlayer_is_active', 'true');

    add_option('mbYTPlayer_video_url', '');
    add_option('mbYTPlayer_video_page', 'static');
    add_option('mbYTPlayer_remember_last_time', false);
    add_option('mbYTPlayer_init_delay', false);
}

add_action('admin_init', 'mbYTPlayer_register_settings');
function mbYTPlayer_register_settings()
{
    //register YTPlayer settings
    register_setting('YTPlayer-activate-group', 'mbYTPlayer_is_active');

    register_setting('YTPlayer-settings-group', 'mbYTPlayer_version');
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_video_url');
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_video_page');
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_remember_last_time');
    register_setting('YTPlayer-settings-group', 'mbYTPlayer_init_delay');
}

$mbYTPlayer_version = get_option('mbYTPlayer_version');
$mbYTPlayer_is_active = get_option('mbYTPlayer_is_active');
$mbYTPlayer_video_url = get_option('mbYTPlayer_video_url');
$mbYTPlayer_video_page = get_option('mbYTPlayer_video_page');
$mbYTPlayer_remember_last_time = get_option('mbYTPlayer_remember_last_time');
$mbYTPlayer_init_delay = get_option('mbYTPlayer_init_delay');

$mbYTPlayer_show_controls = "false";
$mbYTPlayer_show_videourl = "false";
$mbYTPlayer_ratio = "16/9";
$mbYTPlayer_audio_volume = 50;
$mbYTPlayer_mute = true;
$mbYTPlayer_start_at = 0;
$mbYTPlayer_stop_at = 0;
$mbYTPlayer_loop = "true";
$mbYTPlayer_opacity = 10;
$mbYTPlayer_quality = "default";
$mbYTPlayer_add_raster = "true";
$mbYTPlayer_realfullscreen = "false";
$mbYTPlayer_stop_on_blur = "true";
$mbYTPlayer_track_ga = "false";

if($mbYTPlayer_version !=  MBYTPLAYER_VERSION) {
    update_option('mbYTPlayer_price', mbYTPlayer_get_price("YTPL"));
    update_option('mbYTPlayer_version', MBYTPLAYER_VERSION);
}

$mbYTPlayer_price = get_option('mbYTPlayer_price');
if (empty($mbYTPlayer_price)) {
    update_option('mbYTPlayer_price', mbYTPlayer_get_price("YTPL"));
    $mbYTPlayer_price = get_option('mbYTPlayer_price');
}
if (empty($mbYTPlayer_is_active)) {
    $mbYTPlayer_is_active = false;
}
if (empty($mbYTPlayer_show_videourl)) {
    $mbYTPlayer_show_videourl = "false";
}
if (empty($mbYTPlayer_video_page)) {
    $mbYTPlayer_video_page = "static";
}
if (empty($mbYTPlayer_remember_last_time)) {
    $mbYTPlayer_remember_last_time = "false";
}
if (empty($mbYTPlayer_init_delay)) {
    $mbYTPlayer_init_delay = 0;
}


add_filter('plugin_action_links', 'mbYTPlayer_action_links', 10, 2);
function mbYTPlayer_action_links($links, $file)
{
    // check to make sure we are on the correct plugin
    if ($file == plugin_basename(__FILE__)) {
        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a style="color: #008000" href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Go PLUS</a> | ';
        $settings_link .= '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wpmbytplayer/mbYTPlayer.php">Settings</a>';
        // add the link to the list
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_action('wp_enqueue_scripts', 'mbYTPlayer_init');
function mbYTPlayer_init()
{
    global $mbYTPlayer_version;

    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('mb.YTPlayer', plugins_url('/js/jquery.mb.YTPlayer.js', __FILE__), array('jquery'), $mbYTPlayer_version, true, 1000);
        wp_enqueue_style('mb.YTPlayer_css', plugins_url('/css/mb.YTPlayer.css', __FILE__), array(), $mbYTPlayer_version, 'screen');
    }
}

add_action('plugins_loaded', 'mbYTPlayer_localize');
function mbYTPlayer_localize()
{
    load_plugin_textdomain('wpmbytplayer', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// scripts to load in the footer
add_action('wp_footer', 'mbYTPlayer_player_foot', 20);
function mbYTPlayer_player_foot()
{
    global $mbYTPlayer_video_url,
           $mbYTPlayer_show_controls,
           $mbYTPlayer_ratio,
           $mbYTPlayer_show_videourl,
           $mbYTPlayer_start_at,
           $mbYTPlayer_stop_at,
           $mbYTPlayer_mute,
           $mbYTPlayer_loop,
           $mbYTPlayer_opacity,
           $mbYTPlayer_quality,
           $mbYTPlayer_add_raster,
           $mbYTPlayer_track_ga,
           $mbYTPlayer_realfullscreen,
           $mbYTPlayer_stop_on_blur,
           $mbYTPlayer_video_page,
           $mbYTPlayer_is_active,
           $mbYTPlayer_audio_volume,
           $mbYTPlayer_remember_last_time,
           $mbYTPlayer_init_delay;

    $canShowMovie = is_front_page() && !is_home(); // A static page set as home;
    if ($mbYTPlayer_video_page == "blogindex")
        $canShowMovie = is_home(); // the blog index page;
    else if ($mbYTPlayer_video_page == "both")
        $canShowMovie = is_front_page() || is_home(); // A static page set as home;
    else if ($mbYTPlayer_video_page == "all")
        $canShowMovie = true; // on all pages;

    if ($canShowMovie && $mbYTPlayer_is_active) { // && !isMbMobile()

        if (empty($mbYTPlayer_video_url))
            return false;

        if ($mbYTPlayer_opacity > 1)
            $mbYTPlayer_opacity = $mbYTPlayer_opacity / 10;

        $vids = explode(',', $mbYTPlayer_video_url);
        $vids = array_filter($vids);
        $n = rand(0, count($vids) - 1);
        $mbYTPlayer_video_url_revised = $vids[$n];

        $mbYTPlayer_start_at = $mbYTPlayer_start_at > 0 ? $mbYTPlayer_start_at : 1;
        $mbYTPlayer_player_homevideo =
            '<div id=\"bgndVideo_home\" data-property=\"{videoURL:\'' . $mbYTPlayer_video_url_revised . '\', mobileFallbackImage:null, opacity:' . $mbYTPlayer_opacity . ', autoPlay:true, containment:\'body\', startAt:' . $mbYTPlayer_start_at . ', stopAt:' . $mbYTPlayer_stop_at . ', mute:' . $mbYTPlayer_mute . ', vol:' . $mbYTPlayer_audio_volume . ', optimizeDisplay:true, showControls:' . $mbYTPlayer_show_controls . ', printUrl:' . $mbYTPlayer_show_videourl . ', loop:' . $mbYTPlayer_loop . ', addRaster:' . $mbYTPlayer_add_raster . ', quality:\'' . $mbYTPlayer_quality . '\', ratio:\'' . $mbYTPlayer_ratio . '\', realfullscreen:' . $mbYTPlayer_realfullscreen . ', gaTrack:' . $mbYTPlayer_track_ga . ', stopMovieOnBlur:' . $mbYTPlayer_stop_on_blur . ', remember_last_time:' . $mbYTPlayer_remember_last_time . '}\"></div>';
        echo '
	<!-- START mbYTPlayer -->
    <script type="text/javascript">

        function onYouTubePlayerAPIReady() {
        if(ytp.YTAPIReady)
          return;
        ytp.YTAPIReady=true;
        jQuery(document).trigger("YTAPIReady");
      }

    jQuery(function(){
      	var homevideo = "' . $mbYTPlayer_player_homevideo . '";
      	setTimeout(function(){
            jQuery("body").prepend(homevideo);
            jQuery("#bgndVideo_home").YTPlayer();	
            },' . $mbYTPlayer_init_delay . ')
      });

    </script>
	<!-- END  -->
        ';
    }
};

add_shortcode( 'mbYTPlayer', '__return_false' );

/**
 * Add root menu
 */
require("inc/mb-admin-menu.php");

add_action('admin_menu', 'mbYTPlayer_add_option_page');
function mbYTPlayer_add_option_page()
{
    add_submenu_page('mb-ideas-menu', 'YTPlayer', 'YTPlayer', 'manage_options', __FILE__, 'mbYTPlayer_options_page');
}
function mbYTPlayer_options_page()
{ // Output the options page
	global $ytplayer_plus_link, $mbYTPlayer_price;
	?>

  <div class="wrap">
    <a href="http://pupunzi.com"><img style=" width: 350px" src="<?php echo plugins_url('images/logo.png', __FILE__); ?>" alt="Made by Pupunzi"/></a>
    <h2 class="title"><?php _e('mb.YTPlayer', 'wpmbytplayer'); ?></h2>
    <img style=" width: 150px; position: absolute; right: 0; top: 0; z-index: 100" src="<?php echo plugins_url('images/YTPL.svg', __FILE__); ?>" alt="mb.YTPlayer icon"/>
    <h3><?php _e('From here you can set a background video for your home page.', 'wpmbytplayer'); ?></h3>

    <form id="optionsForm" method="post" action="options.php">
		<?php settings_fields('YTPlayer-activate-group'); ?>
		<?php do_settings_sections('YTPlayer-activate-group'); ?>
      <table class="form-table">
        <!--
		  mbYTPlayer_is_active
		  --------------------–--------------------–--------------------–--------------------–--------------------–------- -->
        <tr valign="top">
          <th scope="row"><?php _e('activate the background video', 'wpmbytplayer'); ?></th>
          <td>
            <div class="onoffswitch">
              <input class="onoffswitch-checkbox" type="checkbox" id="mbYTPlayer_is_active"
                     name="mbYTPlayer_is_active" value="true" <?php if (get_option('mbYTPlayer_is_active')) {
				  echo ' checked="checked"';
			  } ?>/> <label class="onoffswitch-label" for="mbYTPlayer_is_active"></label>
            </div>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Use the no-cookie host to serve the video', 'wpmbytplayer'); ?></th>
          <td>
            <p><a href="<?php echo $ytplayer_plus_link ?>" target="_blank"><?php _e('This option is available on the PLUS version of this plugin.', 'wpmbytplayer'); ?></a>
          </td>
        </tr>
      </table>
    </form>
    <!--
	  mbYTPlayer_video_url
	  --------------------–--------------------–--------------------–--------------------–--------------------–------- -->
    <form id="optionsForm" method="post" action="options.php">
		<?php settings_fields('YTPlayer-settings-group'); ?>
		<?php do_settings_sections('YTPlayer-settings-group'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row"> <?php _e('The Youtube video url is:', 'wpmbytplayer'); ?></th>
          <td>
			  <?php
			  $ytpl_video_url = get_option('mbYTPlayer_video_url');
			  $vids = explode(',', $ytpl_video_url);
			  $n = count($vids);
			  $n = $n > 2 ? 2 : $n;
			  $w = (480/$n) - ($n>1 ? (3*$n) : 0);
			  $h = 315/$n;
			  foreach ($vids as $vurl) {
				  $YouTubeCheck = preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $vurl, $matches);
				  if ($YouTubeCheck) {
					  $ytvideoId = $matches[0];
					  ?>
                  <iframe width="<?php echo $w ?>" height="<?php echo $h ?>" style="display: inline-block" src="https://www.youtube.com/embed/<?php echo $ytvideoId ?>?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe><?php
				  }
			  }?>
            <textarea name="mbYTPlayer_video_url" style="width:100%" value="<?php echo esc_attr(get_option('mbYTPlayer_video_url')); ?>"><?php echo esc_attr(get_option('mbYTPlayer_video_url')); ?></textarea>
            <p><?php _e('Copy and paste here the URL of the Youtube video you want as your homepage background. If you add more then one URL comma separated it will be chosen one randomly each time you reach the page', 'wpmbytplayer'); ?>
            </p>
          </td>
        </tr>
        <!--
		  mbYTPlayer_video_page
		  --------------------–--------------------–--------------------–--------------------–--------------------–------- -->
        <tr valign="top">
          <th scope="row"><?php _e('The page where to show the background video is:', 'wpmbytplayer'); ?></th>
          <td>
            <input type="radio" name="mbYTPlayer_video_page" value="static" <?php if (get_option('mbYTPlayer_video_page') == "static" || get_option('mbYTPlayer_video_page') == "") { echo ' checked'; } ?> /> <?php _e('Static Homepage', 'wpmbytplayer'); ?> <br>
            <input type="radio" name="mbYTPlayer_video_page" value="blogindex" <?php if (get_option('mbYTPlayer_video_page') == "blogindex") { echo ' checked'; } ?>/> <?php _e('Blog index Homepage', 'wpmbytplayer'); ?> <br>
            <input type="radio" name="mbYTPlayer_video_page" value="both" <?php if (get_option('mbYTPlayer_video_page') == "both") { echo ' checked'; } ?>/> <?php _e('Both', 'wpmbytplayer'); ?>  <br>
            <input type="radio" name="mbYTPlayer_video_page" value="all" <?php if (get_option('mbYTPlayer_video_page') == "all") { echo ' checked'; } ?>/> <?php _e('All', 'wpmbytplayer'); ?>  <br>

            <p><?php _e('Choose on which page you want the background video to be shown', 'wpmbytplayer'); ?></p>
          </td>
        </tr>
        <!--
		  mbYTPlayer_remember_last_time
		  --------------------–--------------------–--------------------–--------------------–--------------------–------- -->
        <tr valign="top">
          <th scope="row"><?php _e('Remember last video time position:', 'wpmbytplayer'); ?></th>
          <td>
            <input type="checkbox" id="mbYTPlayer_remember_last_time" name="mbYTPlayer_remember_last_time" value="true" <?php if (get_option('mbYTPlayer_remember_last_time') == "true") {
				echo ' checked="checked"';
			} ?>/>
            <label for="mbYTPlayer_remember_last_time"><?php _e('Check to start the video from where you left last time', 'wpmbytplayer'); ?></label>
          </td>
        </tr>
        <!--
		mbYTPlayer_init_delay
		--------------------–--------------------–--------------------–--------------------–--------------------–------- -->
        <tr valign="top" style="background: #ffd8d7">
          <td colspan="2">
            <div style="font-weight: normal; color: #a00102; text-transform: uppercase"><?php _e('Red zone!', 'wpmbytplayer') ?></div>
            <div style="margin-top: 10px"><?php _e('<strong>Rarely there could be a conflict with a theme or a plugins</strong> that prevent the player to work; adding a delay to the initialize event could solve the problem. Be careful that this option will delay the start of the video', 'wpmbytplayer'); ?></div>
            <div style="font-weight: 100; margin-top: 10px"><?php _e('This is a global option and will affect any player in any page', 'wpmbytplayer'); ?></div>
          </td>
        </tr>
        <tr valign="top" style="background: #ffd8d7">
          <th scope="row"><?php _e('Time to wait before initialization:', 'wpmbytplayer'); ?></th>
          <td>
            <select id="mbYTPlayer_init_delay" name="mbYTPlayer_init_delay">
              <option value="0" <?php echo (get_option('mbYTPlayer_init_delay') == 0 ? "selected" : "") ?>><?php _e('none', 'wpmbytplayer'); ?></option>
              <option value="1000" <?php echo (get_option('mbYTPlayer_init_delay') == 1000 ? "selected" : "") ?>>1 sec.</option>
              <option value="1500" <?php echo (get_option('mbYTPlayer_init_delay') == 1500 ? "selected" : "") ?>>1.5 sec.</option>
              <option value="2000" <?php echo (get_option('mbYTPlayer_init_delay') == 2000 ? "selected" : "") ?>>2 sec.</option>
              <option value="3000" <?php echo (get_option('mbYTPlayer_init_delay') == 3000 ? "selected" : "") ?>>3 sec.</option>
            </select>
            <label for="mbYTPlayer_init_delay" style="display: block"><?php _e('Add a delay in seconds for the player initialization<br>(most times it needs 2 sec.)', 'wpmbytplayer'); ?></label>
          </td>
        </tr>
      </table>

      <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
      </p>
    </form>
    <a href="<?php echo $ytplayer_plus_link ?>" target="_blank"> <img
          src="<?php echo plugins_url('/images/pro-opt.png', __FILE__); ?>"></a>
  </div>

  <!-- ---------------------------—---------------------------—---------------------------—---------------------------
Right column
---------------------------—---------------------------—---------------------------—---------------------------— -->
  <div class="rightCol">

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
	PLUS box
	---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div id="getLic" class="box box-success">
      <h3><?php _e('Get your <strong>PLUS</strong> plug-in!', 'wpmbytplayer'); ?></h3>
		<?php _e('The <strong>mb.YTPlayer PLUS</strong> plug-in enable the advanced settings panel, add a short-code editor on the post/page editor page and remove the water-mark from the video player.', 'wpmbytplayer'); ?>
      <br>
      <br>
      <a target="_blank" href="<?php echo $ytplayer_plus_link ?>" class="getKey">
        <span><?php printf(__('<strong>Go PLUS</strong> for <b>%s EUR</b> Only', 'wpmbytplayer'), $mbYTPlayer_price["COM"]) ?></span>
      </a>
    </div>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
	ADVs box
	---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div id="ADVs" class="box"></div>

    <!-- ---------------------------—---------------------------—---------------------------—---------------------------
	Info box
	---------------------------—---------------------------—---------------------------—---------------------------— -->
    <div class="box">
      <h3><?php _e('Thanks for installing <b>mb.YTPlayer</b>!', 'wpmbytplayer'); ?></h3>

      <p>
		  <?php printf(__('You\'re using mb.YTPlayer v. <b>%s</b>', 'wpmbytplayer'), MBYTPLAYER_VERSION); ?>
        <br><?php _e('by', 'wpmbytplayer'); ?> <a href="http://pupunzi.com">mb.ideas (Pupunzi)</a>
      </p>
      <hr>
      <p><?php _e('Don’t forget to follow me on twitter', 'wpmbytplayer'); ?>: <a
            href="https://twitter.com/pupunzi">@pupunzi</a><br>
		  <?php _e('Visit my site', 'wpmbytplayer'); ?>: <a href="http://pupunzi.com">http://pupunzi.com</a><br>
		  <?php _e('Visit my blog', 'wpmbytplayer'); ?>: <a
            href="http://pupunzi.open-lab.com">http://pupunzi.open-lab.com</a><br>
        Paypal: <a
            href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=V6ZS8JPMZC446&lc=GB&item_name=mb%2eideas&item_number=MBIDEAS&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted"
            target="_blank"><?php _e('donate', 'wpmbytplayer'); ?></a>
      <hr>
      <!-- Begin MailChimp Signup Form -->
      <form action="http://pupunzi.us6.list-manage2.com/subscribe/post?u=4346dc9633&amp;id=91a005172f"
            method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
            target="_blank" novalidate>
        <label for="mce-EMAIL"><?php _e('Subscribe to my mailing list <br>to stay in touch', 'wpmbytplayer'); ?>
          :</label>
        <br>
        <br>
        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL"
               placeholder="<?php _e('your email address', 'wpmbytplayer'); ?>" required>
        <input type="submit" value="<?php _e('Subscribe', 'wpmbytplayer'); ?>" name="subscribe"
               id="mc-embedded-subscribe" class="button">
      </form>
      <!--End mc_embed_signup-->
      <hr>

      <!--SHARE-->

      <div id="share" style="margin-top: 10px; min-height: 80px">
        <a href="https://twitter.com/share" class="twitter-share-button"
           data-url="https://wordpress.org/plugins/wpmbytplayer/"
           data-text="I'm using the mb.YTPlayer WP plugin for background videos" data-via="pupunzi"
           data-hashtags="HTML5,wordpress,plugin">Tweet</a>
        <script>!function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (!d.getElementById(id)) {
					js = d.createElement(s);
					js.id = id;
					js.src = "//platform.twitter.com/widgets.js";
					fjs.parentNode.insertBefore(js, fjs);
				}
			}(document, "script", "twitter-wjs");</script>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/it_IT/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
        <div style="margin-top: 10px" class="fb-like"
             data-href="https://wordpress.org/plugins/wpmbytplayer/" data-send="false"
             data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>
      </div>
    </div>

  </div>
  <script>
	  jQuery(function () {

		  var activate = jQuery("#mbYTPlayer_is_active");
		  activate.on("change", function () {
			  var val = this.checked ? 1 : 0;
			  jQuery.ajax({
				  type    : "post",
				  dataType: "json",
				  url     : ajaxurl,
				  data    : {action: "mbytp_activate", activate: val},
				  success : function (resp) {}
			  })
		  });

		  // Add ADVs
		  jQuery.ajax({
			  type    : "post",
			  dataType: "html",
			  url     : "https://pupunzi.com/wpPlus/advs.php",
			  data    : {plugin: "YTPL"},
			  success : function (resp) {
				  jQuery("#ADVs").html(resp);
			  }
		  })

	  })
  </script>
	<?php
}

add_action('admin_enqueue_scripts', 'mbYTPlayer_load_admin_script');
function mbYTPlayer_load_admin_script($hook)
{
    if ($hook == 'mb-ideas_page_wpmbytplayer/mbYTPlayer' && $hook != 'toplevel_page_mb-ideas-menu') {
        wp_enqueue_style('ytp_admin_css', plugins_url('/inc/mb_admin.css', __FILE__), null, MBYTPLAYER_VERSION);
    }
}

add_filter('admin_body_class', 'mbYTPlayer_add_body_classes');
function mbYTPlayer_add_body_classes($classes)
{
    $screen = (get_current_screen()->id == "mb-ideas_page_wpmbytplayer/mbYTPlayer") ? 1 : 0;
    $classes = '';
    if ($screen)
        $classes = 'mb-free';
    return $classes;
}

/**
 * activate option saved via ajax
 */
add_action('wp_ajax_mbytp_activate', 'mbYTPlayer_activate');
function mbYTPlayer_activate()
{
    $activate = $_POST["activate"] == 1 ? true : false;
    update_option('mbYTPlayer_is_active', $activate);
}

/**
 * Water-mark
 */
add_action('wp_head', 'mbYTPlayer_custom_js');
function mbYTPlayer_custom_js() {
    if (!wp_script_is('jquery', 'done')) {
        wp_enqueue_script('jquery');
    }
    $script = 'jQuery(function(){var a=null;setInterval(function(){jQuery(".YTPOverlay").show().each(function(){var b=jQuery(this);jQuery("[class*=ytp_wm_]",b).remove();a="ytp_wm_"+Math.floor(1E5*Math.random());var c=jQuery("<img/>").attr("src","data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAG9CAYAAAB56wSaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAADSBJREFUeNrsXT90IjcTl/2ojtRcDTWuQ23qj357fzWpoTZ1qEO/Pam5mtRsbeqjPq7OKm+Wk+XRakZ/FmzPvMdzsge7v9WM5p9GI6WEeHTn+8LPnz/7X758OVNvWH9/Uv8Z1p8BXDrVn319j2MyYPVD9M3X9Wdb37j0fFeDWRqAbKrqzzP1JX3AFvWfCfyvfuM/sTcHUKv60/c8T4N6ooC7bwH1YIBSwJ4VXLfpDwIoBd9ZUkbsvuXfHpFrx/ptD9YLTAE0lcbwm2Bg2I8xOSsCJt3vQcAc7KqQ0Ro6hH2vAdffn8HksWkSOmJj5NoOufbg+P26EfD67w5mJOXlvcAwQT5RXwCZdQcur13ARvYFm40tI1al0Pz3DI3eRzQ8NrLYCwxSAaOMzgxjdz2yJ+LIvrQB6zmuYzef16PUgJ465GvvsJ0D5AXOIcAOjgmx8HAAm7lPxPv7WQns4ApxZdvRerQKh3x9ixH+v5jASkRPFQ45PAQDg7dfE0FtkYc9upRvKkdxAnIycLgxZQ1q6/jtzJIx7TCukgCzWDM0dFdFYQn8bgEzfcHxhrOTNvbgDd8uaQsCLFYheiwHoAFYiymIw/aqwEDG/kfxwToBBu7zjOl25wEG7GoA9WPu1UvIrkdHnNA9sEB2VT7BZylYy2GcBbBrB1bilFrzD43pzqFjiMbvEdnlcgwpdA4xQ70WQAUAGjDZNc0t/NQI+wSAtOtzpoT/uWdlBTHkLoeCDgW2hRmWzYW5D/ydnp2bmm1PudyZGFZe9FkNTodtf1McxxTAlgyvQH9nUgM8ZQcGb39A/Kg2SsZWjubvG54DB8AJ0lKHLMCQyGnGtAZH0HW7bMAs/6sAGaMa9LMipOfvUshDCJshDZoXWAibOwdGdZOuBgxxLF95KlcH5vLtfMCuFY0/qPdKdx4P9pUGD/W96nut7FnqYyXHg62UlWM1AtxX7jU1EsoZ8H51vEA0sPtblTEBJsAEmAB778A4mn+A2E/MjX6svzeODetYwBQtAzQVGRNgRBmrlJDQB6bggBdZI79EU/XnhVvFGQWMmXZ6lf/PBgwpVaCSBqUzivvkwGpQ8wQ2cM0JmO8JoIpEhnnOWc6594AaEj2KimgpyAsWPrfn/y1yo3Ooezsd4FkX78OLroNlDB6wQf6JtDAKv1+qt8vSpLLmNlZOHCqAtFoLI/kMQOxRe4iRMQwYa8UNwGEL8+MYYEPkQSH5Mew3oxhg/RT+GYzamRuccDzYHxE67JgTWJRRlmBEoiQCFUiKQFgpwD6k8C+VkJDQDeUuYFU2G9UO5CJUXYyvOWKi+T+FSSpFNwh9Ss2fkoxSQf2XtAOsi8K2pp+BmQvZuPbLdanHCvU2QeNNeXYB7CvzemfAvjOvdwZMWxA7P7b11Zjd7KwUEs3fIk86Da836D2ATGkh1zJVcooq7xjCa2/dxxpz+FbpvBqfDAxGYK7whdNLyxtI6lESeyRwvvY4UwDVRnuwhVSPl7SW1POwj7KiO2GKYaPTgo04ZVeNC9wRRnAfGrP2Eo7EhbXmtn6oNni2vhO1ljR2CO4MCriXlOgKZq6tJpIu2VTmbIIH7pCcBLa085Iz4MUML1VhHnMCUxHAlAD7tEmVtoziNueDfXtGhJUCLDfJIpeQkJgkMUkC7B2YJFLkpAL6cHYBrIk9y9TpyzZ1wS38rlIC9GV7QvpAJQHISdx1CjCkm5Zmb6HoG+2CAMbsGckKMDo5zO2vQu3akCxrTUyLkoH1EgAKakeYDZjRVpVaaqMTMOUtzcoTCP0uy6zsClBOzR8FiGIri2sAogh/cQ1AqdSFBvPNmKWcYOSQE9hUhW8/kyhJgAkJfWhK4VprWzqiaPPcUVJTg/G7w2s9Q9rgnxj7yd3APlN4BV2bgd9w94hzA96l5UY3I2Ov3g6RkdQ9ydZJgQGolfq1Q/UMo7BjOpisbs0UYGsDFOvmyM5n8sj5NrA/GTfVVQTzgM3FC4PdU0pv/lZgRh/YRoif4frQt2tQBy6QxlLwIgv1q06xsI+f4Y6Y+fA1VDw1k0DffIE9AEZ5bo4OgGs2w/d9TqIP2OWQMENxmt1LJ/YDoHeB6WJfRgcmy8nwfPnAwH/vG3495g7v7Z6uUKViss0enUafDUIrh83N60djNEzWfXMI/Nl6GXME/zH+O6hyuI9EM79Z32mzi+bMdeU2stVax3ZkjgNmyMJ3x+RwhXWmvXTZUTYw0/6NDGV5smbc1DZFoL8GDpYPqMBcAe+LJSPNbCrV66zhHHTV2Zg0tm4rHfL2wh4xa3Smli6qkBk8ho8N6lKFbvhxjaoJ7hBSGjPUtALPilaopg32xrIkfURtBOXHNoZcrEyHr+UohiNkf8zvTtSv87y0JZnHAjMrMs8ADjtfsNFx35GuNM15Sc1ozSl9oyj+mJ0mL33txa1RtZ2BdL3TEXBNExdX356JetuunNWCiXv01QKZeWdjMgwd/77KupbETBafYVTztvlCQDbH2prC/wNG7xgSsgm9OwLdd3OgCkrdUI94syaUGyEeaQXex65t9sE9/lDE1Tqq5n8iqgdUX2H3iDo4wDK+VFo24EDvzTFvN7ZMK6Q94dywFGsVuPLb87AwJOAYWKdqq6TAWoTUjKiHDgATjyyWMcBGbfJj6SRqUs55JjlHxmwleMRmHDyEYhdLSGOR9o/EbmbBwj0sfpxTnUuWgkXCfiqw4LO7Up3GiD2YvGUxde6CIujqFoGpDwmMU9J8akmE9DH10jZhYvr2YPksjokaiowJMCV9e4Q+MbVpfrse7EdoB3lYkRul0vzPSGC7sB7YR1zwF8T/GilmB7hYf2yEvMBSJdgVIZpfgAkwASbAruAovomEEPuJBRy6xAaLorKFb0lJdqQKMKEPQze5QtboMVgp69+i8OvShA3nnKMuwjdb8+vQbdNVc8YQk7QHgKdbA9aQzgYFlS7Eylh1TfmjLKTq8N6Xe00uf9TaHurOQb1kWKaQP05tT3NKXhLWRa2JOwA+AHuHOYHdB9zwABVzu5zqohcwYk1HyunNAAvYPpsXGMjVnKA2/ttVo9oXXZPoMWrZyxnURLLIqs0kPSnCTgUVsbwcykofKG2y/sxlzENysBrIOrf7w60iSCpHKYAlk6OmK7jvBXtdyZFRHNd05g0GtkpRMhraqKPXYhP3kYCa40iHXc1KH7umKcxWLxEgza7HlIa9dwV2VT7BD3UUQ7qGsN1ujms9NKY7h1ibRcmsBHZNVfiJU+ek9WMtm1V87JrmFn5OT5XLueGpAuCYWaln1y51k5dYYFmcw6jwzXAidd6CfAx3l6y86LManLarf6d0Hn2tfalegf7OpAZ4yg4M3v6A+FFtlIytHM3fNzwHDoCgGCFoXxK3r51hmrZJ9755/K8CZIxq0M8AsMwGLIbNyfNjqdjcOTCqm3Q1YIhj+cpTuTowl29HbfSoOgbo7e7W6wjICpkMUkUgwASYABNg1yBfiiAVDZIBY6QIRMYEWKiMVUpI6AMTt1JlovC0lG5AtQvdHhQEDOLCQtHWxy9npmYFhrTDpBDpDNRYBVso/rJe02sxj+a3Ok9yacxtWc7R/JMWVplC7srq6GXCQ1fA3gh3SxHSQy4Zw2TrzYzT/w+tVNkHtIYCs1OXlWem7RE5fcg1K006ENSEOIoC7N0Ak31JImMSVyIkh7cKda1gS25HLFEXAkyAJdb8Ng1ig1jL7Ul2JlfM+VsYiT8mwN7NrLycindrwE5d7d8VGRNgnxqYanGt+7c6YuuU3kSOYCTrrsAYGdN5/KYSvX+Lwt+Uys9uhZWoeVKZ91i2AdPBrY912eTvzqMuqOuUybdqUBZSOTufN6k2t3CWnqktJtp6E5tR0iIJMANgkt31OZokNKdMZT3gJNSIP6jI9ciUrnXIOfV5gTFbSWxV7iYJTGFP2s/nLoF6yNIBKWaRK6lCjRJ+g7J2OQoB1klfKA6wTjtpUYBllaNQYMlbSYDKqSgj38W+JK1u1pYe9J6o0UXAix1a/RR6eGtK+sq83hmw78zrnQErEaO+9enCTva+hcxKoQ9DubbKPkJM0IdQ7qCYTfnuGMI7sZzGCjmfa+5xv8lnKN0RR2DucK0vVZ7IeahR4HwlzfbZqC4fba3oB6aQSp57hMjIR9yD5xqdFmzEKU0RXOCOMIKuaN0bl/YSjsSFtTWbVlaQbB9gMYpJEYwdgjuDhMiyxTaauY6DCqgq5hjxypxN8MAdknTBdkG85EyqYIaXqjCPOYGpCGBKgOUmqRxGZq9Uqgiwq5JUDgsJiUkSkyTA3oFJIkVOKsEBhzmANbFn2eWyoC+lhI1eMoCUDezcNr5JAHISd50CDCkI0ewtFH1XcxDA4BxsboDRyWFue0xq071kWWtiWpQMrJcAUFA3+WzAAsppdAKmvKVZeQKh32WZlV0Byqn5owBRbGVxDUAU4S+uASiVurhscOEWjKfcYoZRzLYziZIEmNC7on8FGACaNfrF+pUsRgAAAABJRU5ErkJggg==");
ytp_wm=jQuery("<div/>").addClass(a).html(c);c.attr("style","filter:none!important;-webkit-transform:none!important;transform:none!important;padding:0!important;margin:0!important;height:100%!important; width:auto!important;display:block!important;visibility:visible!important;top:0!important;right:0!important;opacity:1!important;position:absolute!important;margin:auto!important;z-index:10000!important;");ytp_wm.attr("style","filter:none!important;-webkit-transform:none!important;transform:none!important;padding:0!important;margin:0!important;display:block!important;position:absolute!important;top:0!important;bottom:0!important;right:0!important;margin:auto!important;z-index:10000!important;width:100%!important;height:100%!important;max-height:220px!important;");
b.prepend(ytp_wm)})},5E3)});';
    echo "<script>".$script."</script>";
}

/**
 * Deactivate plugin if PLUS version exist.
 */
add_action('plugins_loaded', 'mbYTPlayer_free_deactivate');
function mbYTPlayer_free_deactivate()
{
    global $ytppro;
    if ($ytppro) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        deactivate_plugins(plugin_basename(__FILE__));
        $dir = plugin_dir_path(__FILE__);
        deleteDir($dir);
    }
};

if(!function_exists("deleteDir")) {
    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}



=== mb.YTPlayer for background videos ===

Contributors: pupunzi
Tags: video player, youtube, full background, video, HTML5, flash, mov, jquery, pupunzi, mb.components, cover video, embed, embed videos, embed youtube, embedding, plugin, shortcode, video cover, video HTML5, youtube, youtube embed, youtube player, youtube videos
Requires at least: 3.0
Tested up to: 5.7
Stable tag:  3.3.6
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DSHAHSJJCQ53Y
License: GPLv2 or later

Play any Youtube video as background of your page or as custom player inside an element of the page.

== Description ==

A Chrome-less Youtube® video player that let you play any YouTube® video as background of your WordPress® page or post.
You can activate it for your home page from the settings panel (no license needed) or on any post or page using the short code (need the <a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Plus version</a>) as described in the Reference section of the settings.

[youtube http://www.youtube.com/watch?v=lTW937ld02Y]

If you prefer to use a Vimeo video you can take a look at <a href="https://wordpress.org/plugins/wp-vimeoplayer/">mb.VimeoPlayer Wordpress plug-in</a>

note:
If you don't want ADs on your background video and you are the owner of it you can disable this on your Youtube channel as explained here: http://candidio.com/blog/how-to-remove-ads-from-your-youtube-videos .


* demo: <a href="http://pupunzi.com/mb.components/mb.YTPlayer/demo/demo.html" target="_blank">See the Youtube background video in action!</a>
* video: <a href="http://www.youtube.com/watch?v=lTW937ld02Y" target="_blank">See a short video tutorial</a>
* pupunzi blog: <a href="http://pupunzi.open-lab.com" target="_blank">Go to my blog</a>
* pupunzi site: <a href="http://pupunzi.com" target="_blank">Go to my site</a>

This plug in has been tested successfully on:

* Chrome 11+, Firefox 7+, Opera 9+    on Mac OsX, Windows and Linux
* Safari 5+    on Mac OsX
* IE7+    on Windows (via Adobe Flash player)

== Installation ==

Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation, and then activate the plugin from the plugins page.

== Screenshots ==

1. The settings panel.
2. You can add a video as background or targeted to a DOM element in any page or post by inserting a shortcode (<a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Plus version</a>) generated via the editor button.
3. The shortcode editor (<a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Plus version</a>).

== To set your homepage background video: ==

1. Go to the mbYTPlayer settings panel (you can find it under the "settings" section of the WP backend.
2. set the complete YT video url
3. set all the other parameters as you need.

You can also set it by placing a shortcode in the home page via the YTPlayer shortcode window (<a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Plus version</a>).
You can open it by clicking on the YTPlayer button in the top toolbar of the page editor.

== To set a video as background of a post or a page: ==
Use the editor button or write the below shortcode into the content of your post or page (<a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Plus version</a>):

[mbYTPlayer url="http://www.youtube.com/watch?v=V2rifmjZuKQ" ratio="4/3" mute="false" loop="true" showcontrols="true" opacity=1]

* @ url = the YT url of the video you want as background
* @ ratio = the aspect ratio of the video 4/3 or 16/9
* @ mute = a boolean to mute the video
* @ loop = a boolean to loop the video on its end
* @ showcontrols = a boolean to show or hide controls and progression of the video
* @ opacity = a value from 0 to 1 that set the opacity of the background video
* @ id = The ID of the element in the DOM where you want to target the player (default is the BODY)
* @ quality:
  * small: Player height is 240px, and player dimensions are at least 320px by 240px for 4:3 aspect ratio.
  * medium: Player height is 360px, and player dimensions are 640px by 360px (for 16:9 aspect ratio) or 480px by 360px (for 4:3 aspect ratio).
  * large: Player height is 480px, and player dimensions are 853px by 480px (for 16:9 aspect ratio) or 640px by 480px (for 4:3 aspect ratio).
  * hd720: Player height is 720px, and player dimensions are 1280px by 720px (for 16:9 aspect ratio) or 960px by 720px (for 4:3 aspect ratio).
  * hd1080: Player height is 1080px, and player dimensions are 1920px by 1080px (for 16:9 aspect ratio) or 1440px by 1080px (for 4:3 aspect ratio).
  * highres: Player height is greater than 1080px, which means that the player's aspect ratio is greater than 1920px by 1080px.
  * default: YouTube selects the appropriate playback quality.

== mb.YTPlayer Plus ==
With the <a href="https://pupunzi.com/wpPlus/go-plus.php?plugin_prefix=YTPL" target="_blank">Plus version</a> you'll get:

* Remove the water-mark from the video
* Set the video opacity
* Set the video quality
* Set the video aspect ratio
* Set the video start point
* Set the video end point
* Show the control bar
* Choose the full screen behavior
* Set the starting audio volume
* Choose if the video should start mute or not
* Choose if the video should loop
* Add a raster image over the video
* Track the video views on Google Analytics
* Choose if the player should pause if the windows blur
* Add any YTPlayer video as background of any page or as backgraund of any element of your page enabling the short-code editor.
* Use the YTPlayer to display a clean Youtube video as player (via short-code).

== What about mobile ==
<b>The mb.YTPlayer now works on mobile devices!</b>
The video starts as soon the device screen is touched.

== Changelog ==

= 3.3.6 =
Feature: Updated to the latest js script.

= 3.3.5 =
Feature: Defined a better behaviour If you've set the autoplay option of a video that has the audio active: Instead of starting the video at the first user interaction with the page, now the video start playing mute and the audio is activated with the first user interaction.
Feature: The plugin now supports the https://www.youtube-nocookie.com host to remove the Youtube cookies.

= 3.3.4 =
Bug Fix: With the latest maOS Big Sur system update the User Agent has been changed and the OS detection was failing on Safari causing a blocking error.

= 3.3.3 =
Updated: with the latest mb.YTPlayer.js (3.3.4) that fixes a bug for mobile devices.

= 3.3.2 =
Updated with the latest mb.YTPlayer.js (3.3.3) that fixes a bug if the wrong YT API key is used.

= 3.3.1 =
Bug fix: there was a bug if the server was not allowing content from external urls.

= 3.3.0 =
Bug fix: On mobile Chrome browser the size of the background video was broken on orientation change.

== Frequently Asked Questions ==

= I'm using the plug in as background video and I can see the control bar on the bottom but the video doesn't display =
 Your theme is probably using a wrapper for the content and it probably has a background color or image. You should check the CSS and remove that background to let the video that is behind do display correctly.

= Does it work on mobile devices =
Yes! the only difference is that the background video starts once the device scrin is touched after the page has been loaded.

= I would have an image on the background before the video starts and after the video end; how can I do? =
The simplest way is to add an image as background of the body via CSS.

= I set the video quality to hd1080 but it doesn't display at this quality; why? =
The video quality option is just a suggestion for the Youtube API; the video is served by Youtube with the quality that best fits the bandwidth and the display size according to that setting.

= The video stops some seconds before the real end; why? =
To prevent the display of the "play" button provided by the Youtube API the video intentionally stops some seconds before the end; if you are the owner of the video I can suggest to make it a little bit longer (about 3/4 seconds).

= I love your plugin! What can I do to help?
Creating and supporting this plugin takes up a lot of my free time, therefore I would highly appreciate if you could take a couple of minutes to write a review. This will help other WordPress users to start using this plugin and keep me motivated to maintain and support it. You can also make a donation to support my work!

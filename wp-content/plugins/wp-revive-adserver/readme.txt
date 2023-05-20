=== Plugin Name ===
Contributors: zoan
Donate link: https://rezultmedia.com/revive-ad-server-wordpress-plugin/
Tags: ad server, Revive Ad Server, display ads, banner ads
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 2.2.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display Revive Ad Server ad campaigns anywhere on your WordPress website using Shortcodes.

== Description ==
Display Revive Ad Server ad campaigns anywhere on your WordPress website using Shortcodes. Supports your choice including Asynchronous JS, Javascript, and IFrame Invocation Code.

WP-Revive includes the following Revive Adserver invocation tags, Asynchronous JS, Javascript Invocation and IFrame. Simply place the invocation tag of your choice where you would like display banners to appear on your website using the WP-Revive programmed shortcode for the selected invocation tag. Zones you set up in Revive Adserver are easily added within each shortcode. All banner statistics are tracked within Revive Adserver's dashboard as well as setting up and managing your display ad campaigns.

Version 2 now includes the ability to convert Revive Adserver video ad's VAST1 template to VAST2 template for use with VAST2 compatible video players. 

About Revive Adserver
Revive Adserver is a free Open Source ad server that allows website owners, ad networks and advertisers to serve ads on websites, in apps and in video players.


Shortcodes
Asynchronous JS Invocation Code:  [wprevive_async zoneid="ZONE # GOES HERE"]
Javascript Invocation Code:  [wprevive_js zoneid="ZONE # GOES HERE"]
IFrame Invocation Code:  [wprevive_iframe zoneid="ZONE # GOES HERE" width="AD UNIT WIDTH" height="AD UNIT HEIGHT"]



== Installation ==
1. Upload WP-Revive files to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.

2. Activate the plugin through the 'Plugins' screen in WordPress

3. Use the Settings->WP-Revive  screen to configure the plugin
== Frequently Asked Questions ==
= Does this plugin work with newest WP version and also older versions? =

Yes, this plugin works with WordPress 6.0.
It also works with WordPress 5.0 or greater.

= Can I manage my ad campaigns from WordPress admin with WP-Revive plugin?=

No, WP-Revive is strictly used to display banner campaigns configured within Revive Adserver dashboard. 

= Does the plugin contain a VAST2 compatible video player?=

No, the plugin solely converts Revive Adserver VAST1 templates to VAST2 templates for use with VAST2 compatible players. Video player and any necessary player plugins are not included. 


== Screenshots ==

Plugin admin screen

== Changelog ==
= 2.2.1 =
* Added support for Hosted Edition
* Tested against WordPress Version 6.0

= 2.1 =
* Fixed issue with Settings link
* Tested against WordPress Version 5.9

= 2.0 =
* Added VAST1 to VAST2 template conversion for Revive Adserver video campaigns

= 1.1.1 =
* Fixed Activation redirect issue with admin ajax.
* Tested against WordPress Version 5.8

= 1.1 =
* Fixed Shortcodes.
* Tested against WordPress Version 5.8

= 1.0 =
* Initial version.

== Upgrade Notice ==
= 2.0 =
* Added VAST1 to VAST2 template conversion for Revive Adserver video campaigns

= 1.1.1 =
* Fixed Activation redirect issue with admin ajax.

= 1.1 =
Fixed Bug with ad insertion shortcodes.

Tested against WordPress Version 5.8

= 1.0 =
Initial Release

=== WP License Manager ===
Contributors: jarkkolaine
Tags: license management,licence,license,updates
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 0.5.2
License: GPLv3 or later.
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Turn your WordPress site into a software license manager for WordPress plugins, themes, and other downloadable products.

== Description ==

WP License Manager is an easy to use plugin that will turn your WordPress site into a license manager that can be used for WordPress themes, plugins and other downloadable products.

Downloadable files are stored in Amazon Simple Storage Service (S3) to keep them safe from people without a license.

Possible uses for WP License Manager:

* Hosting your own premium plugins and themes and serving updates to them.
* Hosting private plugins that you don't want to share in the open.
* If you get more creative, the license manager plugin can be extended to be used for all kinds of digital goods; downloadable games, for example.


== Installation ==

The easiest way to install the plugin is through the WordPress plugin directory.

To install the plugin manually:

1. Unzip the plugin package
1. Upload directory `wp-license-manager` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

After you have completed the installation, configure plugin settings by clicking on the "License Manager Settings"
menu option in the WordPress Settings menu. Now, you are ready to start adding products and licenses.

= Make your theme or plugin use WP License Manager =

Making your themes and plugins use your own License Manager site requires some basic PHP programming
knowledge (but nothing that couldn't be handled by a little copy-pasting, actually...).

To make the integration as easy as possible, we have created a PHP class that you can pretty much drop in
to your theme or plugin to make it query updates from your own license server instead of WordPress.org.

The class and detailed integration instructions can be found on GitHub:
[WP License Manager Client](http://github.com/jarkkolaine/wp-license-manager-client)


== Frequently Asked Questions ==

FAQ coming soon.


== Screenshots ==

1. The plugin adds a custom post type, "Products", that can be used to manage your license controlled themes and plugins.
2. For each product, in addition to the description, you can define custom fields such as the plugin's version number, file, and WordPress requirements.
3. You can add licenses for each of your products. A license can be time limited or valid forever.
4. The list of licenses shows the licenses and some information about them.


== Changelog ==

= 0.5.3 =
* Changed the name of the products custom post type to prevent conflicts with other plugins.

= 0.5.2 =
* Added support for PHP versions < 5.3. We highly recommend upgrading to PHP 5.3+ but understand that not all users have the possibility to do so.
* Trimmed the download size a bit.

= 0.5.0 =
* First public release

== Upgrade Notice ==

= 0.5.2 =
Adds support for PHP 5.2

= 0.5.0 =
First public release

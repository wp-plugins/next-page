=== Next Page ===
Contributors: sillybean
Tags: page
Requires at least: 2.5
Tested up to: 3.0.1
Stable tag: 1.5


This plugin provides shortcodes and template tags for next/previous navigation in pages. 

== Description ==

This plugin provides shortcodes and template tags for next/previous navigation in pages. Includes a code and tag for the parent page, allowing Drupal-like book navigation.

=== Translations ===

Belorussian translation by <a href="http://pc.de/">Marcis Gasuns</a>

== Installation ==

1. Upload the `next-page` directory to `/wp-content/plugins/` 
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set the display options under Settings &arr; Next Page.

== Screenshots ==

1. A portion of the options page

== Changelog ==

= 1.5 =
* Fixed a bug that caused the links to appear at the top of the content when shortcodes are used (thanks, Psychochild!)
* Fixed a bug where an excluded page with children would result in links to the wrong pages (thanks, lemony!)
* Fixed a bug where links were shown even if there was no next/previous page in the sequence (thanks, Andrew!)
* Added option to loop back to the first page when the last page is being displayed, and to last when first is displayed
* Belorussian translation by <a href="http://pc.de/">Marcis Gasuns</a> (August 5, 2010)
= 1.4 =
* Fixed a bug that could cause the wrong content to appear on pages where the next/previous links are used
* Moved option removal to uninstall instead of deactivation
* Fixed a few non-localized strings (March 19, 2010)
= 1.3 = 
* Revised for settings API
* Internationalization (January 31, 2010)
= 1.2 = 
* Added option to exclude pages by ID
* Improved handling of special characters (September 16, 2009)
= 1.1 =
* Added security check before allowing users to manage options
* Fixed typo in template tags shown on options page (August 3, 2009)
= 1.0 = 
* First release (July 4, 2009)
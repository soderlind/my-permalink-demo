=== My Permalink Demo ===
Contributors: PerS
Donate link: http://soderlind.no/donate/
Tags:  permalink, rewrite rules, flush_rewrite_rules, generate_rewrite_rules, parse_request, permalink_structure, query_vars, wp_rewrite 
Requires at least: 3.4
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Demo plugin to show how to add a custom permalink to your plugin

== Description ==

While working on my [Read Offline](http://wordpress.org/extend/plugins/read-offline/) plugin, I wanted to implement permalinks. After intensive googling and reading the WordPress source I thought I'd share my findings. 

This commented plugin demonstrates how to implement a custom permalink for your plugin. To test, add the `[mypermalink]` or `[mypermalink val="ipsum"]` shortcode to a page or post.

You can [view the plugin source](http://soderlind.no/archives/2012/11/01/wordpress-plugins-and-permalinks-how-to-use-pretty-links-in-your-plugin/) at soderlind.no

== Installation ==

1. Download the plugin and extract the my-permalink-demo.zip
1. Upload the extracted `my-permalink-demo` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.0.1 =
* Fixed a bug in my_permalink_url() that gave 404 for blogs in a subdirectory.
= 1.0.0 =
* Initial release
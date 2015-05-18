<?php
/*
    Plugin Name: WPMU Minify HTML
    Plugin URI: https://github.com/belkincapital/wpmu-minifyhtml
    Description: Minify the HTML output on the frontend. This will help with pagespeed. On Multisite you may either Network Activate this plugin or you may install it on a case by case basis within the subsites you choose.
    
    Author: Jason Jersey
    Author URI: https://www.twitter.com.com/degersey
    Version: 1.0
    Text Domain: wpmu-minifyhtml
    Domain Path: /languages/
    License: GNU General Public License 2.0 
    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
    
    Copyright 2015 Belkin Capital Ltd (contact: https://belkincapital.com/contact/)

    This plugin is opensource; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published
    by the Free Software Foundation; either version 2 of the License,
    or (at your option) any later version (if applicable).

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111 USA
*/


/* Exit if accessed directly
 * Since 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
 
/* Minify HTML output
 * wp_loaded = fired once WP, all plugins, and the theme are fully loaded and instantiated.
 * template_redirect = fired before WordPress determines which template page to load.
 */
add_action('template_redirect','wp_minify_html_output');
function wp_minify_html_output(){
	ob_start('wp_html_compress_output');
}
 
function wp_html_compress_output($buffer) {
	$initial=strlen($buffer);
	$buffer=explode("<!--wp-compress-html-->", $buffer);
	$count=count ($buffer);
 
	for ($i = 0; $i <= $count; $i++){

		if (stristr($buffer[$i], '<!--wp-compress-html no compression-->')){

			$buffer[$i]=(str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]));

		} else {

			$buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
			$buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
			$buffer[$i]=(str_replace("\n", "", $buffer[$i]));
			$buffer[$i]=(str_replace("\r", "", $buffer[$i]));

			while (stristr($buffer[$i], '  ')){
			$buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
			}
		}
		$buffer_out.=$buffer[$i];
	}

	$final=strlen($buffer_out);
	$savings=($initial-$final)/$initial*100;
	$savings=round($savings, 2);
	$buffer_out.=" ";
	return $buffer_out;
}

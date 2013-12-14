<?php
/*
Plugin Name: Theme Core Functions
Description: This plugin contains all the core function for Enrique's themes in order to data will not be lost when the user changes the theme.
Plugin URI: http://enriquechavez.co
Author: Enrique Chavez
Author URI: http://enriquechavez.co
Version: 1.0
License: GPL2
Text Domain: ec-core-functions
Domain Path: /lang
*/
/*

    Copyright (C) 2013  Enrique Chavez  noone@tmeister.net

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Theme Core Functions
 */
class ChavezCoreFunctions
{
	var $theme_options;
	function __construct()
	{
		add_action('plugins_loaded', array(
			$this,
			'init'
		));
	}
	function init()
	{
		if (function_exists('vc_set_as_theme')) {
			vc_set_as_theme();
			require_once( plugin_dir_path( __FILE__ ) . '/shortcodes/ec-core-shortcodes.php' );
		}
	}
}
new ChavezCoreFunctions;


/*
 * Custom Row Markup the function need to be globals.
*/

function vc_theme_vc_row($atts, $content = null, $base = '')
{
	$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = '';
	extract(shortcode_atts(array(
		'el_class' => '',
		'bg_image' => '',
		'bg_color' => '',
		'bg_image_repeat' => '',
		'font_color' => '',
		'padding' => '',
		'margin_bottom' => ''
	) , $atts));
	wp_enqueue_style('js_composer_front');
	wp_enqueue_script('wpb_composer_front_js');
	wp_enqueue_style('js_composer_custom_css');
	if ($el_class != '') {
		$el_class = " " . str_replace(".", "", $el_class);
	}
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row ' . get_row_css_class() . $el_class, '');
	$style = customBuildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);
	//
	$output.= '<section class="' . $css_class . '"' . $style . '>';
	$output.= '<div class="container">';
	$output.= wpb_js_remove_wpautop($content);
	$output.= '</div>';
	$output.= '</section> <!-- /.section -->';
	return $output;
}

function customBuildStyle($bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '')
{
	$has_image = false;
	$style = '';
	if ((int)$bg_image > 0 && ($image_url = wp_get_attachment_url($bg_image, 'large')) !== false) {
		$has_image = true;
		$style.= "background-image: url(" . $image_url . ");";
	}
	if (!empty($bg_color)) {
		$style.= 'background-color: ' . $bg_color . ';';
	}
	if (!empty($bg_image_repeat) && $has_image) {
		if ($bg_image_repeat === 'cover') {
			$style.= "background-repeat:no-repeat;background-size: cover;";
		}
		elseif ($bg_image_repeat === 'contain') {
			$style.= "background-repeat:no-repeat;background-size: contain;";
		}
		elseif ($bg_image_repeat === 'no-repeat') {
			$style.= 'background-repeat: no-repeat;';
		}
	}
	if (!empty($font_color)) {
		$style.= 'color: ' . $font_color . ';';
	}
	if ($padding != '') {
		$style.= 'padding: ' . (preg_match('/(px|em|\%|pt|cm)$/', $padding) ? $padding : $padding . 'px') . ';';
	}
	if ($margin_bottom != '') {
		$style.= 'margin-bottom: ' . (preg_match('/(px|em|\%|pt|cm)$/', $margin_bottom) ? $margin_bottom : $margin_bottom . 'px') . ';';
	}

	return empty($style) ? $style : ' style="' . $style . '"';
}

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
 *
 * @package ChavezCore
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
			add_filter('vc_shortcodes_css_class', array(
				$this,
				'custom_vc_column_classes'
			) , 10, 2);
			$this->remove_vc_shortcodes();
			$this->remove_vc_options();

		}
		require_once (plugin_dir_path(__FILE__) . '/shortcodes/ec-core-shortcodes.php');
	}
	function custom_vc_column_classes($class, $tag)
	{
		if ($tag == 'vc_column' || $tag == 'vc_column_inner') {
			$class = preg_replace('/vc_span(\d{1,2})/', 'col-lg-$1', $class);
		}

		return $class;
	}
	function remove_vc_shortcodes()
	{
		vc_remove_element('vc_facebook');
		vc_remove_element('vc_googleplus');
		vc_remove_element('vc_tweetmeme');
		vc_remove_element('vc_pinterest');
		vc_remove_element('vc_toggle');
		vc_remove_element('vc_image_carousel');
		vc_remove_element('vc_cta_button');
	}
	function remove_vc_options()
	{
		vc_remove_param('vc_progress_bar', 'title');
	}
}
new ChavezCoreFunctions;
/**
 * Custom Row Markup the function need to be globals.
 * @param type $atts
 * @param type $content
 * @param type $base
 * @return type
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
	$output.= '<div class="row">';
	$output.= wpb_js_remove_wpautop($content);
	$output.= '</div> <!-- /.row -->';
	$output.= '</div> <!-- /.container -->';
	$output.= '</section> <!-- /.section -->';

	return $output;
}
/**
 * Build the row style according to the shortcode attributes.
 * @param type $bg_image
 * @param type $bg_color
 * @param type $bg_image_repeat
 * @param type $font_color
 * @param type $padding
 * @param type $margin_bottom
 * @return type
 */
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
/**
 * Custom Progress Bar Layout
 * @param type $atts
 * @param type $content
 * @param type $base
 * @return type
 */
function vc_theme_vc_progress_bar($atts, $content = null, $base = '')
{
	$output = $title = $values = $units = $bgcolor = $custombgcolor = $options = $el_class = '';
	extract(shortcode_atts(array(
		'title' => '',
		'values' => '',
		'units' => '',
		'bgcolor' => 'bar_grey',
		'custombgcolor' => '',
		'options' => '',
		'el_class' => ''
	) , $atts));
	wp_enqueue_script('waypoints');
	//$el_class = $this->getExtraClass($el_class);
	$bar_options = '';
	$options = explode(",", $options);
	if (in_array("animated", $options)) $bar_options.= " animated";
	if (in_array("striped", $options)) $bar_options.= " striped";
	if ($bgcolor == "custom" && $custombgcolor != '') {
		$custombgcolor = ' style="background-color: ' . $custombgcolor . ';"';
		$bgcolor = "";
	}
	if ($bgcolor != "") $bgcolor = " " . $bgcolor;
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_progress_bar wpb_content_element' . $el_class, $base);

	$output = '<div class="' . $css_class . '">';

	/*$output.= wpb_widget_title(array(
		'title' => $title,
		'extraclass' => 'wpb_progress_bar_heading'
	));*/

	//$output.= '<h3 class="wpb_progress_bar_heading"><span class="title">'.$title.'</span></h3>';


	$graph_lines = explode(",", $values);
	$max_value = 0.0;
	$graph_lines_data = array();

	foreach ($graph_lines as $line) {
		$new_line = array();
		$color_index = 2;
		$data = explode("|", $line);
		$new_line['value'] = isset($data[0]) ? $data[0] : 0;
		$new_line['percentage_value'] = isset($data[1]) && preg_match('/^\d{1,2}\%$/', $data[1]) ? (float)str_replace('%', '', $data[1]) : false;
		if ($new_line['percentage_value'] != false) {
			$color_index+= 1;
			$new_line['label'] = isset($data[2]) ? $data[2] : '';
		}
		else {
			$new_line['label'] = isset($data[1]) ? $data[1] : '';
		}
		$new_line['bgcolor'] = (isset($data[$color_index])) ? ' style="background-color: ' . $data[$color_index] . ';"' : $custombgcolor;
		if ($new_line['percentage_value'] === false && $max_value < (float)$new_line['value']) {
			$max_value = $new_line['value'];
		}
		$graph_lines_data[] = $new_line;
	}

	foreach ($graph_lines_data as $line) {
		$unit = ($units != '') ? ' <span class="vc_label_units">' . $line['value'] . $units . '</span>' : '';
		$output.= '<div class="vc_single_bar' . $bgcolor . '">';
		$output.= '<small class="vc_label">' . $line['label'] . '</small>';
		if ($line['percentage_value'] !== false) {
			$percentage_value = $line['percentage_value'];
		}
		elseif ($max_value > 100.00) {
			$percentage_value = (float)$line['value'] > 0 && $max_value > 100.00 ? round((float)$line['value'] / $max_value * 100, 4) : 0;
		}
		else {
			$percentage_value = $line['value'];
		}
		$output.= '<span class="vc_bar' . $bar_options . '" data-percentage-value="' . ($percentage_value) . '" data-value="' . $line['value'] . '"' . $line['bgcolor'] . '>'. $unit .'</span>';
		$output.= '</div>';
	}
	$output.= '</div>';
	return $output . "<!-- ./Progress Bar -->\n";
}

<?php

/*
* Call to action Shortcode
*/

if (!function_exists('ec_call_to_action')) {
	function ec_call_to_action($atts, $content = '')
	{
		global $theme_options;
		if( !is_array($theme_options) ){
			$theme_options['accent_color'] = '#f2f2f2';
		}

		extract(
			shortcode_atts(
				array(
					'text'              => '',
					'text_bg'           => $theme_options['accent_color'],
					'text_color'        => '#ffffff',
					'button_text'       => '',
					'button_bg'         => '#000000',
					'button_text_color' => '#ffffff',
					'button_url'        => '#',
					'el_class'          => ''

     			),
     			$atts
     		)
		);

		$button_url = vc_build_link($button_url);
		$text_class = 'style="background:'.$text_bg.'; color:'.$text_color.'"';
		ob_start();

		?>

		<div class="ec-call-to-action">
			<span class="call-text" <?php echo $text_class ?>>
				<h2><?php echo $text ?></h2>
			</span>
			<span class="call-button" style="background:<?php echo $button_bg ?>">
				<div class="tab-pointer"><div class="triangle" style="border-left-color:<?php echo $text_bg ?>"></div></div>
				<a href="<?php echo $button_url['url'] ?>" title="<?php echo $button_url['title'] ?>" target="<?php echo $button_url['target'] ?>" style="color:<?php echo $button_text_color;?>;">
					<h2>
						<?php echo $button_text ?>
					</h2>
				</a>
			</span>
		</div>


		<?php
		$output =  ob_get_contents();
		ob_end_clean();
		return $output;
	}
	add_shortcode('ec_call_to_action', 'ec_call_to_action' );
}

/*
 * Map for Visual Composer
*/
if (function_exists('vc_map')) {
	vc_map(array(
		'name'                    => __('Call To Action Banner', 'geopoint'),
		'base'                    => 'ec_call_to_action',
		'category'                => 'Content',
		'class'                   => '',
		'show_settings_on_create' => true,
		'params'                  => array(
			array(
				'type'        => 'textarea',
				'class'       => 'div',
				'heading'     => __('Text', 'geopoint'),
				'param_name'  => 'text',
				'description' => __('Enter your content.', 'geopoint')
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __('Text Background Color', 'geopoint'),
				'param_name'  => 'text_bg',
				'description' => __('Select a background color for the content. Default: Accent Color', 'geopoint')
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __('Text Color', 'geopoint'),
				'param_name'  => 'text_color',
				'description' => __('Select a text color for the content. Default: White', 'geopoint')
			),
			array(
				'type'        => 'textfield',
				'class'       => 'div',
				'heading'     => __('Button Text', 'geopoint'),
				'param_name'  => 'button_text',
				'description' => __('Enter your botton text.', 'geopoint')
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __('Button Background Color', 'geopoint'),
				'param_name'  => 'button_bg',
				'description' => __('Select a background color for the button. Default: Black', 'geopoint')
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __('Button Text Color', 'geopoint'),
				'param_name'  => 'button_text_color',
				'description' => __('Select a text color for the button. Default: White', 'geopoint')
			),
			array(
				'type'        => 'vc_link',
				'class'       => '',
				'heading'     => __('External URL', 'geopoint'),
				'param_name'  => 'button_url',
				'description' => __('Enter the Link for the Button.', 'geopoint')
			),


			array(
				'type' => 'textfield',
				'heading' => __('Extra class name', "js_composer"),
				'param_name' => 'el_class',
				'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer')
		    )
		)
	));
}
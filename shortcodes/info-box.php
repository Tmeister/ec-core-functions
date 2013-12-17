<?php
/*
 * ShortCode for Information boxes
*/
if (!function_exists('ec_info_box')) {
	function ec_info_box($atts, $content = '')
	{
		extract(
			shortcode_atts(
				array(
					'media_type'       => 'icon',
					'icon_type'        => 'fa-align-center',
					'header_image'     => false,
					'info_title'       => __('Title', 'geopoint'),
					'info_description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.',
					'info_external'    => false,
					'learn_more'       => 'Learn More',
					'el_class'         => ''
     			),
     			$atts
     		)
		);
		$extra_class = $el_class;
		if( $info_external ){
			$external_link = vc_build_link($info_external);
		}
		if( $header_image ){
			$image = wp_get_attachment_image( $header_image, 'full' );
			$extra_class .= ' image-media';
		}
		ob_start();
	?>
		<div class="ec-info-box <?php echo $extra_class; ?>">
			<?php if ($media_type == 'icon'): ?>
				<div class="circle-icon">
					<i class="fa <?php echo $icon_type ?>"></i>
				</div>
			<?php else: ?>
				<div class="media">
					<?php if ($info_external): ?>
						<a href="<?php echo $external_link['url'] ?>" title="<?php echo $external_link['title'] ?>" target="<?php echo $external_link['target'] ?>">
							<?php echo $image; ?>
							<div class="mask"></div>
						</a>
					<?php else: ?>
						<?php echo $image; ?>
					<?php endif ?>

				</div>
			<?php endif ?>
			<div class="info-data">
				<div class="indicator-up"></div>
				<h3 class="info-title">
					<?php if ($info_external): ?>
						<a href="<?php echo $external_link['url'] ?>" title="<?php echo $external_link['title'] ?>" target="<?php echo $external_link['target'] ?>"><?php echo $info_title ?></a>
					<?php else: ?>
						<?php echo $info_title ?>
					<?php endif ?>
				</h3>
				<p class="description">
					<?php echo $info_description; ?>
				</p>
				<?php if ($info_external && !$header_image): ?>
					<p class="learn-more">
						<a href="<?php echo $external_link['url'] ?>" title="<?php echo $external_link['title'] ?>" target="<?php echo $external_link['target'] ?>"><?php echo $learn_more ?></a>
					</p>
				<?php endif ?>
			</div>
		</div>
	<?php
		$output =  ob_get_contents();
		ob_end_clean();
		return $output;
	}
	add_shortcode('ec_info_box', 'ec_info_box');
}

/*
 * Map for Visual Composer
*/
if (function_exists('vc_map')) {
	vc_map(array(
		'name'                    => __('Info Box', 'geopoint'),
		'base'                    => 'ec_info_box',
		'category'                => 'Content',
		'class'                   => '',
		'show_settings_on_create' => true,
		'params'                  => array(
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __('Media Type', 'geopoint'),
				'param_name'  => 'media_type',
				'holder'      => 'div',
				'description' => __('What do you want to use, Icons or Image for the info box header?', 'geopoint'),
				'value'       => array(
					__('Icon', 'geopoint') => 'icon',
					__('Image', 'geopoint') => 'image'
				)
			),
			array(
				'type'        => 'dropdown',
				'class'       => '',
				'heading'     => __('Icon Type', 'geopoint'),
				'param_name'  => 'icon_type',
				'description' => __('If you select icon for the header, please choose what icon to use.', 'geopoint'),
				'value'       => ec_get_fa_icons()
			),
			array(
				'type'        => 'attach_image',
				'class'       => '',
				'heading'     => __('Image', 'geopoint'),
				'param_name'  => 'header_image',
				'description' => __('If you select image for the header, please choose the image to use.', 'geopoint')
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __('Info Box Title', 'geopoint'),
				'param_name'  => 'info_title',
				'description' => __('Info Box title.', 'geopoint')
			),
			array(
				'type'        => 'textarea_html',
				'class'       => '',
				'heading'     => __('Info Box content', 'geopoint'),
				'param_name'  => 'info_description',
				'description' => __('The box content.', 'geopoint')
			),
			array(
				'type'        => 'vc_link',
				'class'       => '',
				'heading'     => __('External URL', 'geopoint'),
				'param_name'  => 'info_external',
				'description' => __('If you want to link a external URL for the info box please add it.', 'geopoint')
			),
			array(
				'type'        => 'textfield',
				'class'       => '',
				'heading'     => __('Learn More Copy', 'geopoint'),
				'param_name'  => 'learn_more',
				'description' => __('Type the copy for the "Learn More" link text. Default_ "Learn More"', 'geopoint')
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
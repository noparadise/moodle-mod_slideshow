<?php 
	$settings->add(new admin_setting_configtext('slideshow_maxwidth', 
					'Maxwidth ?',get_string("configmaxwidth", "slideshow"), '640'));
	$settings->add(new admin_setting_configtext('slideshow_maxheight', 
					'Maxheight ?',get_string("configmaxheight", "slideshow"), '480'));
	$settings->add(new admin_setting_configselect('slideshow_securepix', 
					'Secure pix ?',get_string("securepix", "slideshow"),'0',
					array('0' => get_string('no'), '1' => get_string('yes'))));
?>

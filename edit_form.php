<?php
require_once($CFG->libdir.'/formslib.php');
class mod_slideshow_edit_form extends moodleform {
    function definition() {
        global $CFG;
        
        $mform = $this->_form;
        $captions = $this->_customdata['captions'];
        $htmledit = $this->_customdata['htmledit'];
        $context = $this->_customdata['context'];
        $slideshowid = $this->_customdata['slideshowid'];
        
        
				$imagenum = 1;
				$thumbnail_path = slideshow_get_thumbnail_path($context);
				
				foreach ($captions as $caption) {
					$mform->addElement('header', 'header', '<img src="'.$thumbnail_path["base"].$caption["image"].'.'.$thumbnail_path["extension"].'"> ('.$caption['image'].'.'.$thumbnail_path["extension"].')');
					$mform->addElement('text', 'title'.$imagenum, get_string('title', 'slideshow',$caption['image']));
					$mform->setType('title'.$imagenum, PARAM_RAW);
					$mform->setDefault('title'.$imagenum, $caption['title']);
					if ($htmledit) {
						$mform->addElement('editor', 'caption'.$imagenum,get_string('caption', 'slideshow',$caption['image']));
						$mform->setType('caption'.$imagenum, PARAM_RAW);
						$mform->setDefault('caption'.$imagenum, array('text' => $caption['caption']));
					} else {
						$mform->addElement('textarea', 'caption'.$imagenum,get_string('caption', 'slideshow',$caption['image']));
						$mform->setType('caption'.$imagenum, PARAM_RAW);
						$mform->setDefault('caption'.$imagenum, $caption['caption']);
					}
					$mform->addElement('hidden', 'image'.$imagenum);
					$mform->setType('image'.$imagenum, PARAM_RAW);
					$mform->setDefault('image'.$imagenum, $caption['image']);
					$imagenum++;
				}
        $mform->addElement('hidden', 'imagenum');
        $mform->setType('imagenum', PARAM_RAW);
        $mform->setDefault('imagenum', $imagenum);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_RAW);
        $mform->setDefault('id', $context->instanceid);
        $this->add_action_buttons(true);
    }
}
class mod_slideshow_comment_form extends moodleform {
    function definition() {
			global $CFG;
			
			$mform = $this->_form;
			$htmledit = $this->_customdata['htmledit'];
			$context = $this->_customdata['context'];
			$slideshowid = $this->_customdata['slideshowid'];
			$slidenumber = $this->_customdata['slidenumber'];
			$img_filename = $this->_customdata['imgfilename'];

			$thumbnail_path = slideshow_get_thumbnail_path($context);
		        
			$mform->addElement('header', 'header', '<img src="'.$thumbnail_path["base"].$img_filename.'.'.$thumbnail_path["extension"].'"> ('.$img_filename.'.'.$thumbnail_path["extension"].')');
			if ($htmledit) {
				$mform->addElement('editor', 'slidecomment', get_string('comment', 'slideshow'));
				$mform->setType('comment', PARAM_RAW);
			} else {
				$mform->addElement('textarea', 'slidecomment', get_string('comment', 'slideshow'));
				$mform->setType('comment', PARAM_CLEAN);
			}

			$mform->addElement('hidden', 'slideshowid', $slideshowid);
			$mform->setType('slideshowid', PARAM_RAW);

			$mform->addElement('hidden', 'slidenumber', $slidenumber);
			$mform->setType('slidenumber', PARAM_RAW);
			
			$mform->addElement('hidden', 'id', $context->instanceid);
			$mform->setType('id', PARAM_RAW);
			$this->add_action_buttons(true, 'Save');
    }
}

/**
 * Defines the form for adding/editing a media element to a slide.
 */
class mod_slideshow_media_form extends moodleform {
    function definition() {
			global $CFG;
			
			$mform = $this->_form;
			$context = $this->_customdata['context'];
			$slideshowid = $this->_customdata['slideshowid'];
			$slidenumber = $this->_customdata['slidenumber'];
			$img_filename = $this->_customdata['imgfilename'];
			$media = $this->_customdata['media'];

			$thumbnail_path = slideshow_get_thumbnail_path($context);
			// FIXME Naïve way to get path to the full-size slide.
			$thumbnail_path["base"] = str_replace("thumb_", "resized_", $thumbnail_path["base"]);

			$mform->addElement('header', 'header', '('.$img_filename.'.'.$thumbnail_path["extension"].')');

			$slide_width = $CFG->slideshow_maxwidth;
			$slide_height = $CFG->slideshow_maxheight;
			$img_html = '<div id="slide" style="background-image: url(\''.$thumbnail_path["base"].$img_filename.'.'.$thumbnail_path["extension"].'\'); width: ' . $slide_width . 'px; height:' . $slide_height . 'px;"><span id="media_outline" style="border: 1px solid #000; padding: 20px; margin-top: 20px; display: block; width: 400px; height: 300px; background: #B5D045; cursor: hand; cursor: pointer;">' . get_string('media_edit_position', 'slideshow') . '</span></div>';
			$mform->addElement('html', $img_html); 


			$mform->addElement('text', 'mediaurl', get_string('media_edit_url', 'slideshow'));
			$mform->setType('mediaurl', PARAM_TEXT);

			$mform->addElement('hidden', 'mediaX', get_string('media_edit_x', 'slideshow'));
			$mform->setType('mediaX', PARAM_INT);

			$mform->addElement('hidden', 'mediaY', get_string('media_edit_y', 'slideshow'));
			$mform->setType('mediaY', PARAM_INT);

			$mform->addElement('text', 'mediawidth', get_string('media_edit_width', 'slideshow'));
			$mform->setType('mediawidth', PARAM_INT);
			
			$mform->addElement('text', 'mediaheight', get_string('media_edit_height', 'slideshow'));
			$mform->setType('mediaheight', PARAM_INT);

			if($media) {
				$mform->setDefault('mediaurl', $media->url);
				$mform->setDefault('mediaX', $media->x);
				$mform->setDefault('mediaY', $media->y);
				$mform->setDefault('mediawidth', $media->width);
				$mform->setDefault('mediaheight', $media->height);
			}

			$mform->addElement('checkbox', 'mediadelete', "Delete media from slide");

			$mform->addElement('hidden', 'slideshowid', $slideshowid);
			$mform->setType('slideshowid', PARAM_RAW);

			$mform->addElement('hidden', 'slidenumber', $slidenumber);
			$mform->setType('slidenumber', PARAM_RAW);
			
			$mform->addElement('hidden', 'id', $context->instanceid);
			$mform->setType('id', PARAM_RAW);
			$this->add_action_buttons(true, 'Save');
    }
}
?>

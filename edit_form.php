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
				// Need to trim 'img' from end of base path, as $caption["image"] contains imgX where X is the number of the slide.
				$thumbnail_path["base"] = substr($thumbnail_path["base"], 0, -3);
				
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
        $this->add_action_buttons(true, 'Save');
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

			$thumbnail_path = slideshow_get_thumbnail_path($context);
		        
			$mform->addElement('header', 'header', '<img src="'.$thumbnail_path["base"].$slidenumber.'.'.$thumbnail_path["extension"].'"> ('.$slidenumber.'.'.$thumbnail_path["extension"].')');
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
?>

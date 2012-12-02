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
        
        $thumburl = $CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_slideshow/content/0/slideshow'.$slideshowid.'/thumb_';
        
		$imagenum = 1;
		foreach ($captions as $caption) {
			$mform->addElement('header', 'header', '<img src="'.$thumburl.$caption['image'].'.jpg"> ('.$caption['image'].'.jpg)');
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
?>
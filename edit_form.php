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
class mod_slideshow_comment_form extends moodleform {
    function definition() {
			global $CFG;
			global $DB;
			
			$mform = $this->_form;
			$htmledit = $this->_customdata['htmledit'];
			$context = $this->_customdata['context'];
			$slideshowid = $this->_customdata['slideshowid'];
			$slidenumber = $this->_customdata['slidenumber'];

			// Generate correct path to images
			$conditions = array('contextid'=>$context->id, 'component'=>'mod_slideshow','filearea'=>'content','itemid'=>0);
			$file_records =  $DB->get_records('files', $conditions);
			foreach ($file_records as $file_record) {
					// check only image files
					if (  preg_match("/\.jpe?g$/", $file_record->filename) || preg_match("/\.gif$/", $file_record->filename) || preg_match("/\.png$/", $file_record->filename)) {
							$showdir = $file_record->filepath;
							$extension = pathinfo($file_record->filename, PATHINFO_EXTENSION);
					}
			}
			$urlroot = $CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_slideshow/content/0';
			$baseurl = $urlroot.$showdir;
			$thumburl = $baseurl . 'thumb_img';
        
			$mform->addElement('header', 'header', '<img src="'.$thumburl.$slidenumber.'.'.$extension.'"> ('.$slidenumber.'.'.$extension.')');
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

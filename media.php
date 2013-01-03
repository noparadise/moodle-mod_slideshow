<?php

/// This page prints a form to edit media and titles for the images in the slideshow folder
    global $DB;
		global $PAGE;
    require_once("../../config.php");
    require_once("lib.php");

    $id = optional_param('id',0,PARAM_INT);
    $a = optional_param('a',0,PARAM_INT);
    $img_num = optional_param('img_num',0,PARAM_INT);

    if ($a) {  // Two ways to specify the module
        $slideshow = $DB->get_record('slideshow', array('id'=>$a), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('slideshow', $slideshow->id, $slideshow->course, false, MUST_EXIST);

    } else {
        $cm = get_coursemodule_from_id('slideshow', $id, 0, false, MUST_EXIST);
        $slideshow = $DB->get_record('slideshow', array('id'=>$cm->instance), '*', MUST_EXIST);
    }

    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    require_login($course->id);
    add_to_log($course->id, "slideshow", "media", "media.php?id=$cm->id", "$slideshow->id");

    $form = data_submitted();
		if ($form) {
			if (isset($form->cancel)) {
				redirect("view.php?id=$id");
				die;
			}
			if(isset($form->mediadelete) && $form->mediadelete) {
				if($media = slideshow_slide_get_media($form->slideshowid, $form->slidenumber)) {
					if(! $DB->delete_records("slideshow_media", array('slideshowid' => $form->slideshowid, 'slidenumber' => $form->slidenumber))) {
						print_error("Error deleting media.");
					}
				}
			}
			else {
				slideshow_write_media($form, $slideshow);
			}
			redirect("view.php?id=$id&img_num=$form->slidenumber");
			die;
    }
    add_to_log($course->id, "slideshow", "media", "media.php?id=$cm->id", "$slideshow->id");
		// Print header.
    $PAGE->set_url('/mod/slideshow/media.php',array('id' => $cm->id));
		$PAGE->set_title(get_string('pluginname', 'mod_slideshow') . ': ' . $slideshow->name);
    $PAGE->navbar->add($slideshow->name);
    echo $OUTPUT->header();
    $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
		
		// Javascript to allow positioning of media on slide.
		$jsmodule = array(
                'name' => 'mod_slideshow',
                'fullpath' => '/mod/slideshow/module.js',
                'requires' => array("node", "dd-plugin"));
   $PAGE->requires->js_init_call('M.local_slideshow.init',
                 null, false, $jsmodule);

		// Print the main part of the page
		$img_count = 0;
		$img_filenames = array();
		if(has_capability('moodle/course:update',$coursecontext)){
			$conditions = array('contextid'=>$context->id, 'component'=>'mod_slideshow','filearea'=>'content','itemid'=>0);
			$file_records =  $DB->get_records('files', $conditions);
			foreach ($file_records as $filerecord) {
				$filename = $filerecord->filename;
				if ( preg_match("#\.jpe?g$#i", $filename) || preg_match("#\.gif$#i", $filename) || preg_match("#\.png$#i", $filename)) {
					if (preg_match("#^thumb_?#i", $filename)) {
						continue;
					}
					if (preg_match("#^resized_#i", $filename)) {
						if ($slideshow->keeporiginals) {
							continue;
						}else{
							$filename = str_replace('resized_','',$filename);
						}
					}
					$img_filenames[] = $filename;
				}
			}
    
			// Display the actual form.
			require_once('edit_form.php');
			echo $OUTPUT->heading(get_string('media_add', 'slideshow'));
			echo get_string('media_instructions', 'slideshow');
			
			// Extract the filename for the current image, will be passed to the edit form.
			$img_filenames[$img_num] = pathinfo($img_filenames[$img_num], PATHINFO_FILENAME);
			$media = slideshow_slide_get_media($slideshow->id, $img_num);
			$mform = new mod_slideshow_media_form('media.php', array('context' => $context, 'slideshowid' => $slideshow->id, 'slidenumber' => $img_num, 'imgfilename' => $img_filenames[$img_num], 'media' => $media));
			$mform->display();
	} else {
		echo get_string('noauth','slideshow','');
	}	
/// Finish the page
    echo $OUTPUT->footer($course);
?>

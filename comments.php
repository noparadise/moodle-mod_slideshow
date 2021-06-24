<?php

/// This page prints a form to edit comments and titles for the images in the slideshow folder
    global $DB;
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
		add_to_log($course->id, "slideshow", "comments", "comments.php?id=$cm->id", "$slideshow->id");

		$form = data_submitted();
		if ($form && $slideshow->commentsallowed) {
			if (isset($form->cancel)) {
				redirect("view.php?id=$id");
				die;
			}
			slideshow_write_comment($form, $slideshow);
			redirect("view.php?id=$id&img_num=$form->slidenumber");
			die;
		}
		add_to_log($course->id, "slideshow", "comments", "comments.php?id=$cm->id", "$slideshow->id");

		/// Print header.
		$PAGE->set_url('/mod/slideshow/comments.php',array('id' => $cm->id));
		$PAGE->navbar->add($slideshow->name);
		echo $OUTPUT->header();
		$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
		$context = get_context_instance(CONTEXT_MODULE, $cm->id);
			
		/// Print the main part of the page
		if($slideshow->commentsallowed) {
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
				sort($img_filenames);

				// Display the actual form.
				require_once('edit_form.php');
				echo $OUTPUT->heading(get_string('comment_add', 'slideshow'));
				echo get_string('comment_instructions', 'slideshow');
				$htmledit = isset($slideshow->htmlcaptions) ? $slideshow->htmlcaptions:0;
				$img_filename = pathinfo($img_filenames[$img_num], PATHINFO_FILENAME);
				$mform = new mod_slideshow_comment_form('comments.php', array('htmledit' => $htmledit, 'context' => $context, 'slideshowid' => $slideshow->id, 'slidenumber' => $img_num, 'imgfilename' => $img_filename));
				$mform->display();
			} else {
				echo get_string('noauth','slideshow','');
			}
		} else {
			echo get_string('comments_not_allowed', 'slideshow');
		}
/// Finish the page
    echo $OUTPUT->footer($course);
?>

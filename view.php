<?php  
/// This page prints a particular instance of slideshow
    global $DB;
		global $USER;
    require_once("../../config.php");
    require_once("lib.php");

    $id = optional_param('id',0,PARAM_INT);
    $a = optional_param('a',0,PARAM_INT);
    $autoshow = optional_param('autoshow',0,PARAM_INT);
    $img_num = optional_param('img_num',0,PARAM_INT);
    $recompress = optional_param('recompress',0,PARAM_INT);
    $pause = optional_param('pause',0,PARAM_INT);
		// Value of 0 overrides the last read position in case the first slide is specifically
		// requested (i.e. clicking through the last slide or on the first thumbnail).
		$lr = optional_param('lr', 1, PARAM_INT);

    if ($a) {  // Two ways to specify the module
        $slideshow = $DB->get_record('slideshow', array('id'=>$a), '*', MUST_EXIST);
        $cm = get_coursemodule_from_instance('slideshow', $slideshow->id, $slideshow->course, false, MUST_EXIST);

    } else {
        $cm = get_coursemodule_from_id('slideshow', $id, 0, false, MUST_EXIST);
        $slideshow = $DB->get_record('slideshow', array('id'=>$cm->instance), '*', MUST_EXIST);
    }

    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

    require_course_login($course, true, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);

		// If a last read record exists it will match these conditions.
		$lastreadconditions = array('userid' => $USER->id, 'slideshowid' => $slideshow->id);

    if ($img_num == 0) {    // qualifies add_to_log, otherwise every slide view increments log
        add_to_log($course->id, "slideshow", "view", "view.php?id=$cm->id", "$slideshow->id");
				
				// If a last read position exists load it.
				if($lr && $DB->record_exists('slideshow_read_positions', $lastreadconditions)) {
					$img_num = $DB->get_record('slideshow_read_positions', $lastreadconditions)->slidenumber;
				} else {
					// User specifically requested first slide, save as last position.
					slideshow_save_last_position($slideshow, $USER, $img_num, $lastreadconditions);
				}
		} else {
			slideshow_save_last_position($slideshow, $USER, $img_num, $lastreadconditions);
		}

/// Print header.
    $PAGE->set_url('/mod/slideshow/view.php',array('id' => $cm->id));
		$PAGE->set_title(get_string('pluginname', 'mod_slideshow') . ': ' . $slideshow->name);
    $PAGE->set_button($OUTPUT->update_module_button($cm->id, 'slideshow'));
    if ($autoshow) { // auto progress of images, no crumb trail
			echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>' . $slideshow->name . '</title></head><body>';
        $slideshow->layout = 9; //layout 9 prevents thumbnails being created
        if (!$pause){
            if(!($autodelay = $slideshow->delaytime)>0) {     // set seconds wait for auto popup progress
                $pause = true;                               // if time 0 then pause...
            } 
        } 
        if ($slideshow->autobgcolor) { // include style to make background black in popup ... 
            echo '<STYLE type="text/css">body {
                background-color:black ! important; background-image : none ! important; color : #ccc ! important} 
                td,h1,h2,h3,h4,h5,h6 { color: #ccc ! important ; } 
                A:link, A:visited{color : #06c}A:hover{color : #0c3}
                </STYLE>';
        }

		
    } else { // normal page header
        echo $OUTPUT->header();
    }


/// Print the main part of the page
    slideshow_secure_script($CFG->slideshow_securepix); // prints javascript ("open image in new window" also conditional on $CFG->slideshow_securepix)
    $conditions = array('contextid'=>$context->id, 'component'=>'mod_slideshow','filearea'=>'content','itemid'=>0);
    $file_records =  $DB->get_records('files', $conditions);
    $fs = get_file_storage();
    $files = array();
    $thumbs = array();
    $resized =  array();
    $showdir = '/';
    foreach ($file_records as $file_record) {
        // check only image files
        if (  preg_match("/\.jpe?g$/i", $file_record->filename) || preg_match("/\.gif$/i", $file_record->filename) || preg_match("/\.png$/i", $file_record->filename)) {
            $showdir = $file_record->filepath;
            if (preg_match("/^thumb_?/i", $file_record->filename)) {
                $filename = str_replace('thumb_','',$file_record->filename);
                $thumbs[$filename] = $filename;
                continue;
            }
            if (preg_match("/^resized_/i", $file_record->filename)) {
                $filename = str_replace('resized_','',$file_record->filename);
                $resized[$filename] = $filename;
                continue;
            }
            $files[$file_record->filename] = new stored_file($fs, $file_record,'content');
        }
    }

    $img_count = 0;
    $maxwidth = $CFG->slideshow_maxwidth;
    $maxheight = $CFG->slideshow_maxheight; 
    $urlroot = $CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_slideshow/content/0';
    //$urlroot = $CFG->wwwroot.'/mod/slideshow/pluginfile.php/'.$context->id.'/mod_slideshow/content/0';
    $baseurl = $urlroot.$showdir;
    $filearray = array();
    $error = '';
    foreach ($files as $filename => $file) {
        // OK, let's look at the pictures in the folder ...
        //  iterate and process images 
        if (in_array($filename, $thumbs) || in_array($filename, $resized)) {
            continue; // done those already
        }
        $filearray[$filename] = $filename;
        // create thumbnail if non existant 
        $tfile_record = array('contextid'=>$file->get_contextid(), 'filearea'=>$file->get_filearea(),
            'component'=>$file->get_component(),'itemid'=>$file->get_itemid(), 'filepath'=>$file->get_filepath(),
            'filename'=>'thumb_'.$file->get_filename(), 'userid'=>$file->get_userid());
        try {
            // this may fail for various reasons
            $fs->convert_image($tfile_record, $file, 80, 60, true);
        } catch (Exception $e) {
            //oops!
            $img_count=0;
            $error = '<p><b>'.$e->getMessage().'</b> '.$filename.'</p>';
            break;
        }
        // create resized image if non existant 
        $tfile_record = array('contextid'=>$file->get_contextid(), 'filearea'=>$file->get_filearea(),
            'component'=>$file->get_component(),'itemid'=>$file->get_itemid(), 'filepath'=>$file->get_filepath(),
            'filename'=>'resized_'.$file->get_filename(), 'userid'=>$file->get_userid());
        try {
            // this may fail for various reasons
            $fs->convert_image($tfile_record, $file, $maxwidth, $maxheight, true);
        } catch (Exception $e) {
            //oops!
            $img_count=0;
            $error = '<p><b>'.$e->getMessage().'</b> '.$filename.'</p>';
            break;
        }
        if(!$slideshow->keeporiginals) {
            $file->delete(); // dump the original
        }
        $img_count ++;
    }
    if ($img_count == 0 and count($resized) > 0) {
        $filearray = $resized;
        $img_count = count($filearray);
    } elseif ($img_count < count($resized)) {
        $filearray = array_merge($filearray,$resized);
        $img_count = count($filearray);
    }
        
    sort($filearray);

    if ($slideshow->centred){
			$container_width = $CFG->slideshow_maxwidth;
			// Add space for second column if comments are allowed or if captions are displayed to the right.
			if($slideshow->commentsallowed || $slideshow->filename == 3) {
				$container_width += 320;
			}
			echo '<div class=sdcomment1 style="width: ' . $container_width . 'px;">';
		}

		echo '<div class=sdcomment2 style="width: ' . $CFG->slideshow_maxwidth . 'px;">';
		
    if($img_count) {
        //
        // $slideshow->layout defines thumbnail position - 1 is on top, 2 is bottom
        // $slideshow->filename defines the position of captions. 1 is on top, 2 is bottom, 3 is on the right
        //
        if ($slideshow->layout == 1){
            // print thumbnail row
            slideshow_display_thumbs($filearray);
        }
        // process caption text
        $currentimage = slideshow_filetidy($filearray[$img_num]);
        $caption_array[$currentimage] = slideshow_caption_array($slideshow->id,$currentimage);
            
        if (isset($caption_array[$currentimage])){
            $captionstring =  $caption_array[$currentimage]['caption'];
            $titlestring = $caption_array[$currentimage]['title'];
        } else {
            $captionstring = $currentimage;
            $titlestring='';
        }
        //
        // if there is a title, show it!
        if($titlestring){
            echo format_text('<h1>'.$titlestring.'</h1>');
        }
        if ($slideshow->filename == 1){
            echo '<p>'.$captionstring.'<p>';
				}

        // display main picture, with link to next page and plain text for alt and title tags
				echo "<div id=\"slide\" class=sdslide style=\"width: $CFG->slideshow_maxwidth; height: $CFG->slideshow_maxheight;\">";
				// The lr parameter overrides the last read position, in case the user reaches the end of the slideshow and wants to see the first slide.
        echo '<a name="pic" href="?id='.($cm->id).'&img_num='.fmod($img_num+1,$img_count).'&autoshow='.$autoshow.'&lr=0">';
        echo '<img class=sdmainimg src="'.$baseurl.'resized_'.$filearray[$img_num].'" alt="'.$filearray[$img_num]
            .'" title="'.$filearray[$img_num].'">';
        echo "</a></div>";
 
				// If there is media on this slide overlay it over the slide.
				if($media = slideshow_slide_get_media($slideshow->id, $img_num)) {
					$top = $media->y;
					$left = $media->x;
					echo '<div id="media_wrapper" class="sdmediawrapper">';
					echo '<div id="media" class="sdmedia" style="top: ' . $top . 'px; left: ' . $left . 'px">';
					echo $PAGE->get_renderer('core', 'media')->embed_url(new moodle_url($media->url), '', $media->width, $media->height); // Empty string is because 2nd param is title attr.
					echo '</div>';
					echo '</div>';
				}

       if ($slideshow->filename == 2){
						$margin_top = $CFG->slideshow_maxheight + 20;
            echo '<p style="margin: ' . $margin_top . 'px 0 0 0;">'.$captionstring.'</p>';
        }
            
        if ($slideshow->layout == 2){
            // print thumbnail row
			echo "<div id='thumbnail_container' class='sdthumbnailcontainer' style='margin-top: ".($CFG->slideshow_maxheight + 10)."px;'>";
            slideshow_display_thumbs($filearray);
			echo "</div>";
        }
          
        if (!$autoshow){
            // set up regular navigation options (autopoup, image in new window, teacher options)
            $popheight = $CFG->slideshow_maxheight +100;
            $popwidth = $CFG->slideshow_maxwidth +100;
						// Set a fixed top margin of maxheight+20 because media wrapper is absolute, need to display navigation options underneath it.
						$margin_string = "margin: " . ($CFG->slideshow_maxheight + 20) . "px 0 0 0;";
						// If captions are displayed below image the margin has already been taken care by the caption paragraph.
						if($slideshow->filename == 2) {
							$margin_string = "margin: 0 0 0 0;";
						}
            echo '<ul class="sdul" style="' . $margin_string .' width: ' . $CFG->slideshow_maxwidth . 'px"><li><a target="popup" href="?id='
                .($cm->id)."&autoshow=1\" onclick=\"return openpopup('/mod/slideshow/view.php?id="
                .($cm->id)."&autoshow=1', 'popup', 'menubar=0,location=0,scrollbars,resizable,width=$popwidth,height=$popheight', 0);\">"
                .get_string('autopopup','slideshow')."</a></li>";
            if(! $CFG->slideshow_securepix){
                if (isset($slideshow->keeporiginals) and 
                    $DB->record_exists('files', array('contextid'=>$context->id, 'filepath'=>$showdir, 'filename' => $filearray[$img_num]))) {
                    echo '<li><a href="'.$baseurl.$filearray[$img_num].'" target="_blank">'.get_string('open_new', 'slideshow').'</a></li>';
                } else {
                    echo '<li><a href="'.$baseurl.'resized_'.$filearray[$img_num].'" target="_blank">'.get_string('open_new', 'slideshow').'</a></li>';
                }
            }
            if (has_capability('moodle/course:update',$context)){
                echo '<li><a href="captions.php?id='.$cm->id.'">'.get_string('edit_captions', 'slideshow').'</a></li>';
								echo '<li><a href="media.php?id=' . $cm->id . '&img_num=' . $img_num . '">' . get_string('media_add', 'slideshow') . '</a></li>';
            }
						echo '</ul>';
        } else {
            //
            // set up autoplay navigation (< || >)
            echo '<p align="center"><a href="?id='.($cm->id).'&img_num='.fmod($img_count+$img_num-1,$img_count).'&autoshow='.$autoshow."\">&lt;&lt;</a>";
            if (!$pause){
                echo '<a href="?id='.($cm->id).'&img_num='.$img_num.'&autoshow='.$autoshow."&pause=1\">||</a>";
                echo '<meta http-equiv="Refresh" content="'.$autodelay.'; url=?id='
                    .($cm->id).'&img_num='.fmod($img_num+1,$img_count)."&autoshow=1\">";
            } else {
                echo "||";
            }
            echo '<a href="?id='.($cm->id).'&img_num='.fmod($img_num+1,$img_count).'&autoshow='.$autoshow."\">&gt;&gt;</a></p>";
        }

				// Close slide column.
				echo'</div>';
				if($slideshow->centred) {
					echo '</div>';
				}

				// Second column only displayed if comments are allowed or captions are shown to the right.
				if($slideshow->commentsallowed || $slideshow->filename==3) {
					// Fixed width because, depending on user's resolution, maxwidth could cause the second column to be very narrow.
					// In that case it makes more sense for the column to be cleared under the image.					
					echo '<div class="sdcommentTable" >';
					if($slideshow->filename == 3) {
						echo '<p>' . $captionstring . '</p>';
					}

					if($slideshow->commentsallowed) {
						echo '<center>';
						echo '<h3>' . get_string('comments_header', 'slideshow') . '</h3>';
						echo '</center>';
						$comments = array();
						$comments = slideshow_slide_comments_array($slideshow->id, $img_num);

						if($comments){
							$commentcolor = FALSE;
							foreach($comments as $comment) : 
								$user = $DB->get_record('user', array('id' => $comment->userid)); 
										if($commentcolor === FALSE){
											echo '<div class="sdcommentcolor">';
												echo '<p>';
													echo $user->firstname . ' ' . $user->lastname;
												echo '</p>';
												echo '<div class="sdcommentcolorsubdiv" ><p>';
													echo $comment->slidecomment;
												echo '</p></div>';
												$commentcolor = TRUE;
										}
										else {
											echo '<div class="sdnocommentcolor">';
												echo '<p>';
													echo $user->firstname . ' ' . $user->lastname;
												echo '</p>';
												echo '<div class="nocommentcolorsubdiv>';
													echo $comment->slidecomment;
												echo '</div>';
												$commentcolor = FALSE;
										}
											echo '</div>'	
						?>	
										
									
								
<?php				endforeach;
						}

						//echo '</ul>';
						echo '<center><br><a href="comments.php?id=' . $cm->id . '&img_num=' . $img_num . '" class="sdcommentButton">' . get_string('comment_add', 'slideshow') . '</a></center>';
						echo '<br></div>';						}
				}

    } else {
        echo '<p>'.get_string('none_found', 'slideshow').' <b>'.$showdir.'</b></p>';
        echo '<p><b>'.$error.'</b></p>';
				
				// Close slide column.
				echo'</div>';
				if($slideshow->centred) {
					echo '</div>';
				}
    }

/// Finish the page
    if ($autoshow){
        echo '</body></html>';
    } else {
    echo $OUTPUT->footer($course);
    }
?>

<?php
/**
 * Form to define a new instance of slideshow or edit an instance.
 * It is used from /course/modedit.php.
 *
 **/
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->libdir.'/filelib.php');
require_once('lib.php');
class mod_slideshow_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $DB, $COURSE;
        $mform    = $this->_form;
		$config = get_config('slideshow');

//-------------------------------------------------------------------------------
//-------------------------------------------------------------------------------

	$mform->addElement('header', 'general', get_string('general', 'form'));

    $mform->addElement('text', 'name', get_string('name'), array('size'=>'48'));
    if (!empty($CFG->formatstringstriptags)) {
        $mform->setType('name', PARAM_TEXT);
    } else {
        $mform->setType('name', PARAM_CLEAN);
    }
    $mform->addRule('name', null, 'required', null, 'client');
    $this->add_intro_editor(true);
    $ynopts = array(0=>get_string('no'),1=>get_string('yes')); 
    $mform->addElement('filemanager', 'location', get_string('maindirectory', 'slideshow'), null,
                    array('subdirs' => 1, 'accepted_types' => array('*.jpg','*.gif','*.png','*.zip') ));
    $options = array (0=>get_string("display_none", "slideshow"),
                            1=>get_string("display_over", "slideshow"), 
                            2=>get_string("display_under", "slideshow"),
                            3=>get_string("display_right", "slideshow")
                        ); 
	$mform->addElement('select', 'filename', get_string("display_filename", "slideshow"), $options);
    $layout = array (0=>get_string("display_none", "slideshow"),
                            1=>get_string("display_over", "slideshow"),
                            2=>get_string("display_under", "slideshow")
                        );  
	$mform->addElement('select', 'layout', get_string("thumbnail_layout", "slideshow"), $layout);
	$mform->addElement('select', 'centred', get_string("centred", "slideshow"),$ynopts);
    $mform->setDefault('centred', 1);
	$mform->addElement('select', 'htmlcaptions', get_string("htmlcaptions", "slideshow"),$ynopts);
    $mform->setDefault('htmlcaptions', 1);
	$mform->addElement('select', 'commentsallowed', get_string("comments", "slideshow"),$ynopts);
    $mform->setDefault('commentsallowed', 1);

    $dtimes = array (0=>get_string("noautoplay", "slideshow"), 
                        5=>'5s',
                        10=>'10s',
                        15=>'15s',
                        20=>'20s',
                        40=>'40s',
                        60=>'1 min',
                        );
	$mform->addElement('select', 'delaytime', get_string("showtime", "slideshow"), $dtimes);
    $mform->setDefault('delaytime', 0);
	$mform->addElement('select', 'autobgcolor', get_string("onblack", "slideshow"),$ynopts);
    $mform->setDefault('autobgcolor', 0);
	$mform->addElement('select', 'keeporiginals', get_string("keeporiginals", "slideshow"),$ynopts);
    $mform->setDefault('keeporiginals', 0);


//-------------------------------------------------------------------------------
    $features = new stdClass;
    $features->groups = false;
    $features->groupings = false;
    $features->groupmembersonly = false;
    $this->standard_coursemodule_elements($features);

	$this->add_action_buttons();
    }
    function data_preprocessing(&$default_values) {
        if ($this->current->instance) {
            // editing existing instance - copy existing files into draft area
            $draftitemid = file_get_submitted_draft_itemid('location');
            file_prepare_draft_area($draftitemid, $this->context->id, 'mod_slideshow', 'content', 0, array('subdirs'=>true));
            $default_values['location'] = $draftitemid;
        }

    }
}
?>

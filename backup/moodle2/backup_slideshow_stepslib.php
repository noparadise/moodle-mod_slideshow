<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Define all the backup steps that will be used by the backup_slideshow_activity_task
 *
 * @package    mod
 * @subpackage slideshow
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete resource structure for backup, with file and id annotations
 */
class backup_slideshow_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

		// Define each element separated
        $slideshow = new backup_nested_element('slideshow', array('id'), array(
            'name', 'location', 'layout', 'filename',
            'delaytime','centred','autobgcolor','timemodified','htmlcaptions', 'commentsallowed', 'keeporiginals'));

		$captions = new backup_nested_element('captions');

        $caption = new backup_nested_element('caption', array('id'), array(
            'slideshow','image', 'title', 'caption'));

		$comments = new backup_nested_element('comments');
		$comment = new backup_nested_element('comment', array('id'), array('slideshowid', 'userid', 'slidenumber', 'slidecomment'));

		$read_positions = new backup_nested_element('read_positions');
		$read_position = new backup_nested_element('read_position', array('id'), array('slideshowid', 'userid', 'slidenumber'));

		$media = new backup_nested_element('media');
		$media_entry = new backup_nested_element('media_entry', array('id'), array('slideshowid', 'slidenumber', 'url', 'width', 'height', 'x', 'y'));

		// Build the tree

        $slideshow->add_child($captions);
        $captions->add_child($caption);

				$slideshow->add_child($comments);
				$comments->add_child($comment);

				$slideshow->add_child($read_positions);
				$read_positions->add_child($read_position);

				$slideshow->add_child($media);
				$media->add_child($media_entry);

        // Define sources
        $slideshow->set_source_table('slideshow', array('id' => backup::VAR_ACTIVITYID));
        $caption->set_source_sql("
            SELECT *
              FROM {slideshow_captions}
             WHERE slideshow = ? ",
            array(backup::VAR_PARENTID));
				$comment->set_source_sql("
						SELECT *
							FROM {slideshow_comments}
						WHERE slideshowid = ? ",
						array(backup::VAR_PARENTID));
				$read_position->set_source_sql("
						SELECT *
							FROM {slideshow_read_positions}
						WHERE slideshowid = ? ",
						array(backup::VAR_PARENTID));
				$media_entry->set_source_sql("
						SELECT *
							FROM {slideshow_media}
						WHERE slideshowid = ? ",
						array(backup::VAR_PARENTID));

		// Define id annotations
        // (none)

        // Define file annotations
        $slideshow->annotate_files('mod_slideshow', 'content', null);

        // Return the root element (resource), wrapped into standard activity structure
        return $this->prepare_activity_structure($slideshow);
    }
}

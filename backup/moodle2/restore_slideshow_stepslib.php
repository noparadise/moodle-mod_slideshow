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
 * @package moodlecore
 * @subpackage slideshow
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_slideshow_activity_task
 */

/**
 * Structure step to restore one resource activity
 */
class restore_slideshow_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('slideshow', '/activity/slideshow');
        $paths[] = new restore_path_element('slideshow_caption', '/activity/slideshow/captions/caption');
				$paths[] = new restore_path_element('slideshow_comment', '/activity/slideshow/comments/comment');
				$paths[] = new restore_path_element('slideshow_read_position', '/activity/slideshow/read_positions/read_position');
				$paths[] = new restore_path_element('slideshow_media_entry', '/activity/slideshow/media/media_entry');

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_slideshow($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the resource record
        $newitemid = $DB->insert_record('slideshow', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

	protected function process_slideshow_caption($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->slideshow = $this->get_new_parentid('slideshow');

        $newitemid = $DB->insert_record('slideshow_captions', $data);
        $this->set_mapping('slideshow_caption', $oldid, $newitemid);
    }

	protected function process_slideshow_comment($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->slideshowid = $this->get_new_parentid('slideshow');

        $newitemid = $DB->insert_record('slideshow_comments', $data);
        $this->set_mapping('slideshow_comment', $oldid, $newitemid);
    }

	protected function process_slideshow_read_position($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->slideshowid = $this->get_new_parentid('slideshow');

        $newitemid = $DB->insert_record('slideshow_read_positions', $data);
        $this->set_mapping('slideshow_read_position', $oldid, $newitemid);
    }

	protected function process_slideshow_media_entry($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->slideshowid = $this->get_new_parentid('slideshow');

        $newitemid = $DB->insert_record('slideshow_media', $data);
        $this->set_mapping('slideshow_media_entry', $oldid, $newitemid);
    }

    protected function after_execute() {
        // Add slideshow related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_slideshow', 'content', null);
    }
}

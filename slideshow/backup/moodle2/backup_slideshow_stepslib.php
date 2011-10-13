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
            'delaytime','centred','autobgcolor','timemodified','htmlcaptions','keeporiginals'));

		$captions = new backup_nested_element('captions');

        $caption = new backup_nested_element('caption', array('id'), array(
            'slideshow','image', 'title', 'caption'));

		// Build the tree

        $slideshow->add_child($captions);
        $captions->add_child($caption);

        // Define sources
        $slideshow->set_source_table('slideshow', array('id' => backup::VAR_ACTIVITYID));
        $caption->set_source_sql("
            SELECT *
              FROM {slideshow_captions}
             WHERE slideshow = ? ",
            array(backup::VAR_PARENTID));

		// Define id annotations
        // (none)

        // Define file annotations
        $slideshow->annotate_files('mod_slideshow', 'content', null);

        // Return the root element (resource), wrapped into standard activity structure
        return $this->prepare_activity_structure($slideshow);
    }
}

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
 * Define all the restore steps that will be used by the restore_url_activity_task
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define all the restore steps that will be used by the restore_url_activity_task
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_codesnippet_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define the base path in the XML that we need to restore.
     *
     * @return restore_path_element The element structure that we're going to retore.
     */
    protected function define_structure() {

        $paths = [];
        $paths[] = new restore_path_element('codesnippet', '/activity/codesnippet');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Push the instance of activity from the decoded backup into the database.
     *
     * @param array $data The activity data, decoded from the XML
     */
    protected function process_codesnippet($data) {
        global $DB;

        // Convert the old ids to new ones.
        $data = (object) $data;
        $data->course = $this->get_courseid();

        // Insert the codesnippet record and apply it as an activity.
        $newitemid = $DB->insert_record('codesnippet', $data);
        $this->apply_activity_instance($newitemid);
    }
}

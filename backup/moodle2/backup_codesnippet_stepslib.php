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
 * Define all the backup steps that will be used by the backup_codesnippet_activity_task
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete code snippet structure for backup, with id annotations
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_codesnippet_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define the structure of where our data lives so we can feed it into the XML.
     *
     * @return backup_nested_element A node that contains the data to be stored into XML.
     */
    protected function define_structure() {

        // Define each element separately so we can code them into the XML.
        $codesnippet = new backup_nested_element('codesnippet', array('id'), array(
            'name', 'intro', 'introformat', 'language', 'linenumbers', 'timemodified'));

        // Build the tree: nothing to do here as this is a simple element.

        // Define sources: just our one table.
        $codesnippet->set_source_table('codesnippet', array('id' => backup::VAR_ACTIVITYID));

        // Define additional id annotations: none.

        // Define file annotations: none.

        // Return the root element (codesnippet), wrapped into standard activity structure.
        return $this->prepare_activity_structure($codesnippet);
    }
}
